<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GalleryController extends Controller
{
    public function index()
    {
        $galleries = Gallery::latest()->paginate(12);
        return view('dashboard.galleries.index', compact('galleries'));
    }

    public function public()
    {
        // (opsional) batasi hanya yang published:
        // $items = Gallery::where('is_published', true)->latest()->paginate(12);
        $items = Gallery::latest()->paginate(12);
        return view('galleries.index', compact('items'));
    }

    public function create()
    {
        return view('dashboard.galleries.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'       => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'media_type'  => ['required', Rule::in(['image','video'])],
            'media'       => array_merge(
                ['required','file','max:204800'], // 200MB
                $request->input('media_type') === 'video'
                    ? ['mimes:mp4,mov,avi,mkv,webm']
                    : ['mimes:jpg,jpeg,png,webp']
            ),
            'is_published'=> ['nullable','boolean'],
        ]);

        // SIMPAN KE STORAGE LOKAL (BUKAN DISK PUBLIC)
        // menghasilkan path seperti: galleries/abc123.jpg
        $path = $request->file('media')->store('galleries'); // default disk 'local'

        $data = [
            'title'        => $validated['title'],
            'description'  => $validated['description'] ?? null,
            'media_type'   => $validated['media_type'],
            'media_path'   => $path,                       // simpan relative path storage
            'is_published' => (bool)$request->boolean('is_published'),
            'slug'         => Gallery::uniqueSlug($validated['title']),
        ];

        Gallery::create($data);

        return redirect()->route('galleries.index')->with('success','Item galeri dibuat.');
    }

    public function edit(Gallery $gallery)
    {
        return view('dashboard.galleries.edit', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title'       => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'media_type'  => ['required', Rule::in(['image','video'])],
            'media'       => array_merge(
                ['nullable','file','max:204800'],
                $request->input('media_type') === 'video'
                    ? ['mimes:mp4,mov,avi,mkv,webm']
                    : ['mimes:jpg,jpeg,png,webp']
            ),
            'is_published'=> ['nullable','boolean'],
        ]);

        $data = [
            'title'        => $validated['title'],
            'description'  => $validated['description'] ?? null,
            'media_type'   => $validated['media_type'],
            'is_published' => (bool)$request->boolean('is_published'),
        ];

        if ($request->hasFile('media')) {
            // Hapus file lama di storage lokal (kalau bukan URL)
            if ($gallery->media_path && !Str::startsWith($gallery->media_path, ['http://','https://'])) {
                Storage::disk('local')->delete($gallery->media_path);
            }
            // Simpan baru ke storage lokal
            $data['media_path'] = $request->file('media')->store('galleries'); // disk 'local'
        } else {
            $data['media_path'] = $gallery->media_path;
        }

        $data['slug'] = Gallery::uniqueSlug($data['title'], $gallery->id);

        $gallery->update($data);

        return redirect()->route('galleries.index')->with('success','Item galeri diperbarui.');
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->media_path && !Str::startsWith($gallery->media_path, ['http://','https://'])) {
            Storage::disk('local')->delete($gallery->media_path);
        }
        $gallery->delete();
        return back()->with('success','Item galeri dihapus.');
    }

    /**
     * STREAM MEDIA dari storage lokal.
     * - Mengembalikan 200/206 dengan header Content-Type yang tepat
     * - Dukung HTTP Range sederhana untuk video scrubbing
     * - Sembunyikan path asli storage dari publik
     */
    public function media(Request $request, Gallery $gallery): StreamedResponse
    {
        // Batasi akses jika belum published (opsional, sesuaikan kebijakan)
        if (!$gallery->is_published && !auth('admin')->check()) {
            abort(404);
        }

        // Jika path adalah URL eksternal, redirect saja
        if (Str::startsWith($gallery->media_path, ['http://','https://'])) {
            return redirect()->to($gallery->media_path);
        }

        $disk = Storage::disk('local');
        $path = $gallery->media_path;

        if (!$path || !$disk->exists($path)) {
            abort(404);
        }

        $size = $disk->size($path);
        $mime = $disk->mimeType($path) ?: 'application/octet-stream';
        $file = $disk->path($path);

        // Tangani Range untuk video
        $range = $request->header('Range'); // contoh: bytes=0- or bytes=1000-2000
        $start = 0;
        $end   = $size - 1;
        $status = 200;
        $headers = [
            'Content-Type'        => $mime,
            'Content-Length'      => $size,
            'Accept-Ranges'       => 'bytes',
            'Cache-Control'       => 'public, max-age=86400',
            'Content-Disposition' => 'inline; filename="'.basename($path).'"',
        ];

        if ($range && preg_match('/bytes=(\d*)-(\d*)/i', $range, $m)) {
            $start = ($m[1] !== '') ? (int)$m[1] : $start;
            $end   = ($m[2] !== '') ? (int)$m[2] : $end;

            if ($start > $end || $start > $size - 1) {
                // Range tidak valid
                return response('', 416, [
                    'Content-Range' => "bytes */{$size}",
                ]);
            }

            $status = 206;
            $length = $end - $start + 1;

            $headers['Content-Length'] = $length;
            $headers['Content-Range']  = "bytes {$start}-{$end}/{$size}";
        }

        return response()->stream(function () use ($file, $start, $end) {
            $chunk = 8192;
            $handle = fopen($file, 'rb');
            if ($handle === false) {
                return;
            }
            try {
                fseek($handle, $start);
                $bytesToOutput = $end - $start + 1;
                while ($bytesToOutput > 0 && !feof($handle)) {
                    $read = ($bytesToOutput > $chunk) ? $chunk : $bytesToOutput;
                    $buffer = fread($handle, $read);
                    echo $buffer;
                    flush();
                    $bytesToOutput -= strlen($buffer);
                    if (connection_aborted()) { break; }
                }
            } finally {
                fclose($handle);
            }
        }, $status, $headers);
    }
}
