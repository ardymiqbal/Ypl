<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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

        $path = $request->file('proof')->store('donations', 'public');

        Donation::create([
            'name'       => $data['name'],
            'email'      => $data['email'],
            'message'    => $data['message'] ?? null,
            'proof_path' => $path,
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
        // Validasi hanya kolom status
        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'verified', 'rejected'])],
        ]);

        // Update hanya kolom status
        $donation->update([
            'status' => $validated['status'],
        ]);

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
}
