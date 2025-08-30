<?php

namespace App\Http\Controllers;

use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

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
}
