<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\Gallery;

class FocusController extends Controller
{
    public function sampah()
    {
        // === Poster (portrait) ===
        $posterDir = asset('images/fokus-kerja/sampah/poster');
        $posters = collect(is_dir($posterDir) ? File::files($posterDir) : [])
            ->filter(fn($f) => in_array(strtolower($f->getExtension()), ['jpg','jpeg','png','webp']))
            ->sortBy(fn($f) => $f->getFilename())
            ->take(3)
            ->map(fn($f) => asset('images/fokus-kerja/sampah/poster/'.$f->getFilename()))
            ->values()
            ->all();

        // === Galeri  ===
        $galleryImages = Gallery::where('is_published', true)
            ->where('media_type', 'image')
            ->latest()
            ->limit(12)
            ->get(['id','title','media_type','media_path']);

        // === Hero ===
        $hero = asset('images/fokus-kerja/sampah/hero.jpeg');  

        // Artikel terbaru
        $latestArticles = Article::where('status','published')->latest()->take(3)->get();

        return view('fokus-kerja.sampah', compact('hero','posters','galleryImages','latestArticles'));
    }


    // --- Halaman stub opsional ---
    public function edukasi(Request $request)
    {
        $hero = asset('images/fokus-kerja/sampah/hero.jpeg');          
        $imgA   = asset('images/fokus-kerja/edukasi/kegiatan-2.jpeg');  
        $imgB   = asset('images/fokus-kerja/edukasi/kegiatan-3.JPG');

        $latestArticles = Article::query()
            ->where('status', 'published')
            ->latest('created_at')
            ->take(3)
            ->get();
        
         // === Galeri  ===
        $galleryImages = Gallery::where('is_published', true)
            ->where('media_type', 'image')
            ->latest()
            ->limit(12)
            ->get(['id','title','media_type','media_path']);

        return view('fokus-kerja.edukasi', compact('hero',  'imgA', 'imgB', 'latestArticles', 'galleryImages'));
    }
    public function pemberdayaan()
    {
        $hero = asset('images/fokus-kerja/pemberdayaan/hero.jpeg');

        $latestArticles = Article::where('status', 'published')
            ->latest('created_at')
            ->take(3)
            ->get();
        
         // === Galeri  ===
        $galleryImages = Gallery::where('is_published', true)
            ->where('media_type', 'image')
            ->latest()
            ->limit(12)
            ->get(['id','title','media_type','media_path']);

        return view('fokus-kerja.pemberdayaan', compact('hero', 'latestArticles', 'galleryImages'));
    }
    public function monitoring()
    {
        $hero = asset('images/fokus-kerja/monitoring/hero.jpg');

        $latestArticles = Article::where('status', 'published')
            ->latest('created_at')
            ->take(3)
            ->get();
        
         // === Galeri  ===
        $galleryImages = Gallery::where('is_published', true)
            ->where('media_type', 'image')
            ->latest()
            ->limit(12)
            ->get(['id','title','media_type','media_path']);

        return view('fokus-kerja.monitoring', compact('hero', 'latestArticles', 'galleryImages'));
    }
    public function kolaborasi()
    {
        $hero = asset('images/fokus-kerja/kolaborasi/hero.jpg');

        $mid = [
            asset('images/fokus-kerja/kolaborasi/mid-1.jpeg'),
            asset('images/fokus-kerja/kolaborasi/mid-2.jpeg'),
            asset('images/fokus-kerja/kolaborasi/mid-3.jpeg'),
        ];

        $latestArticles = Article::where('status','published')->latest()->take(3)->get();

         // === Galeri  ===
        $galleryImages = Gallery::where('is_published', true)
            ->where('media_type', 'image')
            ->latest()
            ->limit(12)
            ->get(['id','title','media_type','media_path']);

        return view('fokus-kerja.kolaborasi', compact('hero', 'mid', 'latestArticles', 'galleryImages'));
    }
}
