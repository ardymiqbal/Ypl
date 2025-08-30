<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Support\Facades\DB;

class ArticleController extends Controller
{
    /**
     * Tampilkan daftar artikel di dashboard (semua status).
     */
    public function index()
    {
        $articles = Article::latest()->paginate(12);
        return view('dashboard.articles.index', compact('articles'));
    }

    /**
     * Form create artikel.
     */
    public function create()
    {
        return view('dashboard.articles.create');
    }

    /**
     * Simpan artikel baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => ['required','string','max:255'],
            'summary'         => ['required','string'],
            'content'         => ['required','string'],
            'author'          => ['required','string','max:255'],
            'hashtags'        => ['required','string','max:255'],
            'status'          => ['required', Rule::in(['draft','published'])],
            'thumbnail'       => ['required','image','mimes:jpg,jpeg,png,webp','max:10240'],
            'documentation'   => ['nullable','array','max:3'],
            'documentation.*' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:10240'],
        ]);

        $validated['content']   = Purifier::clean($validated['content'], 'default');
        $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');

        $docs = [];
        if ($request->hasFile('documentation')) {
            $files = array_slice($request->file('documentation'), 0, 3);
            foreach ($files as $file) {
                $docs[] = $file->store('documentation', 'public');
            }
        }
        $validated['documentation'] = $docs ?: null;
        $validated['slug'] = Article::uniqueSlug($validated['title']);

        Article::create($validated);

        return redirect()->route('articles.index')->with('success','Artikel berhasil dibuat.');
    }

    /**
     * Form edit artikel.
     */
    public function edit(Article $article)
    {
        return view('dashboard.articles.edit', compact('article'));
    }

    /**
     * Update artikel.
     */
    public function update(Request $request, Article $article)
    {
        $validator = Validator::make($request->all(), [
            'title'                => ['required','string','max:255'],
            'summary'              => ['required','string','max:2000'],
            'content'              => ['required','string','max:20000'],
            'author'               => ['required','string','max:255'],
            'hashtags'             => ['required','string','max:255'],
            'status'               => ['required', Rule::in(['draft','published'])],
            'thumbnail'            => ['nullable','image','mimes:jpg,jpeg,png,webp','max:10240'],
            'keep_existing_docs'   => ['nullable','array'],
            'keep_existing_docs.*' => ['string'],
            'documentation'        => ['nullable','array','max:3'],
            'documentation.*'      => ['nullable','image','mimes:jpg,jpeg,png,webp','max:10240'],
        ]);

        $existing   = $article->documentation ?? [];
        $kept       = collect($request->input('keep_existing_docs', []))->intersect($existing)->values()->all();
        $keptCount  = count($kept);
        $newFiles   = $request->file('documentation', []);
        $newCount   = is_array($newFiles) ? count($newFiles) : 0;

        $validator->after(function ($v) use ($keptCount, $newCount) {
            if (($keptCount + $newCount) > 3) {
                $v->errors()->add('documentation', 'Maksimal 3 gambar total (gabungan yang dipertahankan + yang baru).');
            }
        });

        $validated = $validator->validate();
        $validated['content'] = Purifier::clean($validated['content'], 'default');

        $oldThumb     = $article->thumbnail;
        $newThumbPath = null;

        if ($request->hasFile('thumbnail')) {
            $newThumbPath = $request->file('thumbnail')->store('thumbnails','public');
            $validated['thumbnail'] = $newThumbPath;
        } else {
            $validated['thumbnail'] = $article->thumbnail;
        }

        $slot   = max(0, 3 - $keptCount);
        $newDocs = [];
        if ($slot > 0 && is_array($newFiles)) {
            foreach (array_slice($newFiles, 0, $slot) as $file) {
                $newDocs[] = $file->store('documentation','public');
            }
        }

        $removed    = array_diff($existing, $kept);
        $mergedDocs = array_slice(array_merge($kept, $newDocs), 0, 3);
        $validated['documentation'] = $mergedDocs ?: null;

        $validated['slug'] = Article::uniqueSlug($validated['title'], $article->id);

        DB::beginTransaction();
        try {
            $article->update($validated);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            if ($newThumbPath) Storage::disk('public')->delete($newThumbPath);
            foreach ($newDocs as $p) Storage::disk('public')->delete($p);
            throw $e;
        }

        foreach ($removed as $p) {
            if ($p && !Str::startsWith($p,'http')) {
                Storage::disk('public')->delete($p);
            }
        }
        if ($newThumbPath && $oldThumb && $oldThumb !== $newThumbPath && !Str::startsWith($oldThumb,'http')) {
            Storage::disk('public')->delete($oldThumb);
        }

        return redirect()->route('articles.index')->with('success','Artikel berhasil diperbarui.');
    }

    /**
     * Hapus artikel + file terkait.
     */
    public function destroy(Article $article)
    {
        if ($article->thumbnail && !Str::startsWith($article->thumbnail,'http')) {
            Storage::disk('public')->delete($article->thumbnail);
        }
        $docs = $article->documentation ?? [];
        foreach ($docs as $p) {
            if (!Str::startsWith($p,'http')) {
                Storage::disk('public')->delete($p);
            }
        }
        $article->delete();
        return back()->with('success','Artikel dihapus.');
    }

    public function publicIndex(Request $request)
    {
        $request->validate(['q' => 'nullable|string|max:100']);

        $qRaw   = (string) $request->query('q', '');
        $qTrim  = trim($qRaw);
        $qPlain = ltrim($qTrim, '#');
        if (mb_strlen($qPlain) < 2) $qPlain = '';

        $driver = DB::connection()->getDriverName();
        $op     = $driver === 'pgsql' ? 'ILIKE' : 'LIKE';
        $like   = "%{$qPlain}%";

        $articles = Article::query()
            ->where('status', 'published')
            ->when($qPlain !== '', function ($q) use ($op, $like) {
                $q->where(function ($s) use ($op, $like) {
                    $s->where('title',   $op, $like)
                      ->orWhere('summary',$op, $like)
                      ->orWhere('author', $op, $like)
                      ->orWhere('hashtags',$op, $like);
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $q = $qTrim;
        return view('articles.index', compact('articles','q'));
    }

    /* =========================
     * MEDIA ROUTES (STREAMING)
     * ========================= */

    /** Tampilkan thumbnail artikel (lokal) lewat streaming file. */
    public function thumb(Article $article)
    {
        $path = $article->thumbnail;
        if (!$path) abort(404);

        // Jika URL eksternal, redirect saja
        if (Str::startsWith($path, ['http://','https://'])) {
            return redirect()->away($path);
        }

        $full = storage_path('app/public/'.$path);
        if (!is_file($full)) abort(404);

        // Tanpa set mime manual (aman untuk shared hosting)
        return response()->file($full);
    }

    /** Tampilkan salah satu dokumentasi (lokal) berdasarkan index. */
    public function doc(Article $article, int $i)
    {
        $docs = $article->documentation ?? [];
        if (!is_array($docs)) {
            if (is_string($docs)) $docs = json_decode($docs, true) ?: [];
            else $docs = [];
        }

        if (!isset($docs[$i])) abort(404);
        $path = $docs[$i];

        if (Str::startsWith($path, ['http://','https://'])) {
            return redirect()->away($path);
        }

        $full = storage_path('app/public/'.$path);
        if (!is_file($full)) abort(404);

        return response()->file($full);
    }
}
