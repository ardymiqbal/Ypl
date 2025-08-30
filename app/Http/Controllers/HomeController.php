<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Gallery;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Halaman beranda.
     */
    public function index(Request $request)
    {
        // Hero images boleh kamu ganti sumbernya (storage/URL)
        $heroImages = [
            asset('images/hero1.jpeg'),
            asset('images/hero2.jpg'),
            asset('images/hero3.jpg'),
        ];

        // Artikel published untuk beranda
        $articles = Article::query()
            ->where('status', 'published')
            ->latest()
            ->limit(3)
            ->get();

         // HANYA IMAGE untuk slider
        $galleryImages = Gallery::where('is_published', true)
            ->where('media_type', 'image')
            ->latest()
            ->limit(12)
            ->get(['id','title','media_type','media_path']);

        // 1 VIDEO unggulan (terbaru)
        $featuredVideo = Gallery::where('is_published', true)
            ->where('media_type', 'video')
            ->latest()
            ->first(['id','title','media_type','media_path']);
        
        return view('home', compact('articles', 'galleryImages', 'featuredVideo', 'heroImages'));
    }

    /**
     * NEW: Halaman daftar semua artikel (published) + search & filter tag.
     * Route: GET /artikel  (name: articles.public.index)
     */
    public function articlesIndex(Request $request)
    {
        $data = $request->validate([
            'q'   => ['nullable','string','max:100'],
            'tag' => ['nullable','string','max:50'],
        ]);
        $q   = trim((string)($data['q'] ?? ''));
        $tag = trim((string)($data['tag'] ?? ''));

        $like = '%'.str_replace(['%','_'], ['\\%','\\_'], $q).'%';

        $articles = Article::query()
            ->where('status','published')
            ->when($q !== '', fn($qB) =>
                $qB->where(function($s) use ($like){
                    $s->where('title','like',$like)
                    ->orWhere('summary','like',$like)
                    ->orWhere('author','like',$like);
                })
            )
            ->when($tag !== '', fn($qB) =>
                $qB->whereRaw('FIND_IN_SET(?, REPLACE(hashtags," ","")) > 0', [$tag])
            )
            ->latest()
            ->paginate(3)
            ->withQueryString();

        return view('articles.index', compact('articles','q','tag'));
    }


    /**
     * Halaman detail artikel published.
     * Route: GET /artikel/{slug}
     */
    public function showArticle(string $slug)
    {
        $article = Article::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Artikel terkait: berdasarkan author atau hashtag, exclude diri sendiri
        $related = Article::query()
            ->where('status', 'published')
            ->where('id', '!=', $article->id)
            ->where(function ($q) use ($article) {
                $q->where('author', $article->author);

                $tags = is_array($article->hashtag_array) ? $article->hashtag_array : [];
                foreach ($tags as $t) {
                    $q->orWhereRaw('FIND_IN_SET(?, REPLACE(hashtags, " ", "")) > 0', [$t]);
                }
            })
            ->latest()
            ->limit(4)
            ->get();

        return view('articles.show', compact('article', 'related'));
    }

    /**
     * Halaman galeri publik (hanya yang published).
     * Route: GET /galeri
     */
    public function gallery()
    {
        $items = Gallery::query()
            ->where('is_published', true)
            ->latest()
            ->paginate(12);

        return view('galleries.index', compact('items'));
    }
}
