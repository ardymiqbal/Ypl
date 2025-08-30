<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DonationController extends Controller
{
    /** LIST (Dashboard) */
    public function index(Request $request)
    {
        $q      = $request->input('q');
        $status = $request->input('status');

        $donations = Donation::query()
            ->when($q, fn($w) =>
                $w->where(function($x) use ($q){
                    $x->where('name','like',"%{$q}%")
                      ->orWhere('email','like',"%{$q}%")
                      ->orWhere('message','like',"%{$q}%");
                })
            )
            ->when($status, fn($w) => $w->where('status', $status))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('dashboard.donations.index', compact('donations','q','status'));
    }

    /** FORM (Publik) */
    public function create()
    {
        return view('donations.create');
    }

    /** STORE (Publik) */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => ['required','string','max:150'],
            'email'   => ['required','email','max:150'],
            'message' => ['nullable','string','max:5000'],
            'proof'   => ['required','file','mimes:jpg,jpeg,png,webp,pdf','max:5120'], // 5MB
        ]);

        // Simpan ke disk 'public' -> storage/app/public/donations/....
        $path = $request->file('proof')->store('donations', 'public');

        Donation::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'message'    => $data['message'] ?? null,
            'proof_path' => $path, // contoh: donations/xxx.jpg
            'status'     => 'pending',
        ]);

        return redirect()->route('donations.create')
            ->with('success','Terima kasih!');
    }

    /** DETAIL (Dashboard) */
    public function show(Donation $donation)
    {
        return view('dashboard.donations.show', compact('donation'));
    }

    /** EDIT (Dashboard) */
    public function edit(Donation $donation)
    {
        return view('dashboard.donations.edit', compact('donation'));
    }

    /** UPDATE (Dashboard) */
    public function update(Request $request, Donation $donation)
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'verified', 'rejected'])],
        ]);

        $donation->update(['status' => $validated['status']]);

        return redirect()
            ->route('donations.show', $donation)
            ->with('success', 'Status donasi berhasil diperbarui.');
    }

    /** DELETE (Dashboard) */
    public function destroy(Donation $donation)
    {
        if ($donation->proof_path && Storage::disk('public')->exists($donation->proof_path)) {
            Storage::disk('public')->delete($donation->proof_path);
        }
        $donation->delete();

        return redirect()
            ->route('donations.index')
            ->with('success', 'Donasi dihapus.');
    }

    /**
     * STREAM FILE (Dashboard) — akses bukti tanpa symlink.
     * Tidak set mimetype manual; biarkan framework mengatur.
     */
    public function file(Donation $donation)
    {
        // Jika path eksternal (http/https), arahkan langsung
        if ($donation->proof_path && Str::startsWith($donation->proof_path, ['http://','https://'])) {
            return redirect()->away($donation->proof_path);
        }

        // Path lokal di storage/app/public/...
        $relative = ltrim($donation->proof_path ?? '', '/');
        if ($relative === '') abort(404);

        $full = storage_path('app/public/'.$relative);
        if (!is_file($full)) abort(404);

        // Kirim sebagai inline sehingga gambar/pdf bisa tampil di <img>/<iframe>
        return response()->file($full);
    }
}
