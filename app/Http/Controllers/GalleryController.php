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
        // Tidak ada where('is_published', true)
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

        // Validasi media kondisional
        'media'       => array_merge(
            ['required','file','max:204800'], // 200MB contoh; sesuaikan
            $request->input('media_type') === 'video'
                ? ['mimes:mp4,mov,avi,mkv,webm']                 // atau pakai 'mimetypes:video/mp4,video/quicktime,video/x-msvideo,video/x-matroska,video/webm'
                : ['mimes:jpg,jpeg,png,webp']
        ),

        'is_published'=> ['nullable','boolean'],
    ]);

    $path = $request->file('media')->store('galleries','public');

    $data = [
        'title'        => $validated['title'],
        'description'  => $validated['description'] ?? null,
        'media_type'   => $validated['media_type'],
        'media_path'   => $path,
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
        
            // Media opsional ganti, dengan rule kondisional yang sama
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
            if ($gallery->media_path && !str_starts_with($gallery->media_path, 'http')) {
                Storage::disk('public')->delete($gallery->media_path);
            }
            $data['media_path'] = $request->file('media')->store('galleries','public');
        } else {
            $data['media_path'] = $gallery->media_path;
        }
    
        $data['slug'] = Gallery::uniqueSlug($data['title'], $gallery->id);
    
        $gallery->update($data);
    
        return redirect()->route('galleries.index')->with('success','Item galeri diperbarui.');
    }


    public function destroy(Gallery $gallery)
    {
        if ($gallery->media_path && !str_starts_with($gallery->media_path,'http')) {
            Storage::disk('public')->delete($gallery->media_path);
        }
        $gallery->delete();
        return back()->with('success','Item galeri dihapus.');
    }

    public function media(Request $request, Gallery $gallery): StreamedResponse
{
    // Jika item tidak published dan bukan admin -> 404 (opsional, sesuai kebijakanmu)
    if (!$gallery->is_published && !auth('admin')->check()) {
        abort(404);
    }

    // Jika path adalah URL eksternal, redirect saja
    if (Str::startsWith($gallery->media_path, ['http://','https://'])) {
        return redirect()->to($gallery->media_path);
    }

    // Coba ambil dari disk 'public' (sesuai kode store/update mu sekarang)
    $disk = Storage::disk('public');
    $path = $gallery->media_path;

    // Fallback: kalau ternyata filenya disimpan di 'local', coba disk local
    if (!$disk->exists($path)) {
        $disk = Storage::disk('local');
        if (!$disk->exists($path)) abort(404);
    }

    $size = $disk->size($path);
    $mime = $disk->mimeType($path) ?: 'application/octet-stream';
    $absolute = $disk->path($path);

    // Dukungan HTTP Range (agar video bisa di-seek)
    $range = $request->header('Range'); // contoh: bytes=0- or bytes=1000-2000
    $start = 0;
    $end   = $size - 1;
    $status = 200;
    $headers = [
        'Content-Type'        => $mime,
        'Accept-Ranges'       => 'bytes',
        'Cache-Control'       => 'public, max-age=86400',
        'Content-Disposition' => 'inline; filename="'.basename($path).'"',
        'Content-Length'      => $size,
    ];

    if ($range && preg_match('/bytes=(\d*)-(\d*)/i', $range, $m)) {
        $start = ($m[1] !== '') ? (int)$m[1] : $start;
        $end   = ($m[2] !== '') ? (int)$m[2] : $end;

        if ($start > $end || $start > $size - 1) {
            return response('', 416, ['Content-Range' => "bytes */{$size}"]);
        }

        $status = 206;
        $length = $end - $start + 1;
        $headers['Content-Length'] = $length;
        $headers['Content-Range']  = "bytes {$start}-{$end}/{$size}";
    }

    return response()->stream(function () use ($absolute, $start, $end) {
        $chunk = 8192;
        $fh = fopen($absolute, 'rb');
        if ($fh === false) return;
        try {
            fseek($fh, $start);
            $remain = $end - $start + 1;
            while ($remain > 0 && !feof($fh)) {
                $read = ($remain > $chunk) ? $chunk : $remain;
                $buffer = fread($fh, $read);
                echo $buffer;
                flush();
                $remain -= strlen($buffer);
                if (connection_aborted()) break;
            }
        } finally {
            fclose($fh);
        }
    }, $status, $headers);
}
}
