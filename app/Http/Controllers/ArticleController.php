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
        // Validasi input
        $validated = $request->validate([
            'title'           => ['required','string','max:255'],
            'summary'         => ['required','string'],
            'content'         => ['required','string'],
            'author'          => ['required','string','max:255'],
            'hashtags'        => ['required','string','max:255'],
            'status'          => ['required', Rule::in(['draft','published'])],
            'thumbnail'       => ['required','image','mimes:jpg,jpeg,png,webp','max:10240'],

            // HANYA gambar & maksimal 3 file
            'documentation'   => ['nullable','array','max:3'],
            'documentation.*' => ['nullable','image','mimes:jpg,jpeg,png,webp','max:10240'],
        ]);

        // Sanitasi ringan konten
        $validated['content'] = Purifier::clean($validated['content'], 'default');

        // Upload thumbnail
        $validated['thumbnail'] = $request->file('thumbnail')->store('thumbnails', 'public');

        // Upload documentation (maks 3)
        $docs = [];
        if ($request->hasFile('documentation')) {
            // Ambil hanya 3 file pertama (tambahan guard selain validator)
            $files = array_slice($request->file('documentation'), 0, 3);
            foreach ($files as $file) {
                $docs[] = $file->store('documentation', 'public');
            }
        }
        // Model di-cast ke array, jadi serahkan array atau null
        $validated['documentation'] = $docs ?: null;

        // Slug otomatis
        $validated['slug'] = Article::uniqueSlug($validated['title']);

        Article::create($validated);

        return redirect()
            ->route('articles.index')
            ->with('success','Artikel berhasil dibuat.');
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
        // ========= VALIDASI =========
        $validator = Validator::make($request->all(), [
            'title'                => ['required','string','max:255'],
            'summary'              => ['required','string','max:2000'],
            'content'              => ['required','string','max:20000'],
            'author'               => ['required','string','max:255'],
            'hashtags'             => ['required','string','max:255'],
            'status'               => ['required', Rule::in(['draft','published'])],

            // thumbnail opsional, batasi size
            'thumbnail'            => ['nullable','image','mimes:jpg,jpeg,png,webp','max:10240'],

            // dokumentasi: maksimal 3 total (kept + new)
            'keep_existing_docs'   => ['nullable','array'],
            'keep_existing_docs.*' => ['string'],
            'documentation'        => ['nullable','array','max:3'],
            'documentation.*'      => ['nullable','image','mimes:jpg,jpeg,png,webp','max:10240'],
        ]);

        // Dokumen lama yang ada di DB (sudah cast ke array di model)
        $existing = $article->documentation ?? [];

        // File lama yang ingin dipertahankan (hanya yang memang ada)
        $kept = collect($request->input('keep_existing_docs', []))
            ->intersect($existing)->values()->all();
        $keptCount = count($kept);

        // Hitung file baru yang diunggah
        $newFiles = $request->file('documentation', []);
        $newCount = is_array($newFiles) ? count($newFiles) : 0;

        // Gabungan kept + new tidak boleh > 3
        $validator->after(function ($v) use ($keptCount, $newCount) {
            if (($keptCount + $newCount) > 3) {
                $v->errors()->add('documentation', 'Maksimal 3 gambar total (gabungan yang dipertahankan + yang baru).');
            }
        });

        // Eksekusi validasi
        $validated = $validator->validate();

        // ========= SANITASI KONTEN =========
        // Konsisten dengan store(): gunakan Purifier
        $validated['content'] = Purifier::clean($validated['content'], 'default');

        // ========= PERSIAPAN FILE =========
        $oldThumb = $article->thumbnail;   // simpan path lama untuk cleanup setelah update
        $newThumbPath = null;

        // Simpan thumbnail baru (jika ada). Simpan path tapi JANGAN hapus yang lama dulu.
        if ($request->hasFile('thumbnail')) {
            $newThumbPath = $request->file('thumbnail')->store('thumbnails','public');
            $validated['thumbnail'] = $newThumbPath;
        } else {
            $validated['thumbnail'] = $article->thumbnail; // tetap pakai yang lama
        }

        // Simpan dok baru sesuai slot tersisa
        $slot = max(0, 3 - $keptCount);
        $newDocs = [];
        if ($slot > 0 && is_array($newFiles)) {
            foreach (array_slice($newFiles, 0, $slot) as $file) {
                $newDocs[] = $file->store('documentation','public');
            }
        }

        // Dok yang tidak dipertahankan (akan dihapus SETELAH update sukses)
        $removed = array_diff($existing, $kept);

        // Gabungkan kept + new sebagai nilai final di DB
        $mergedDocs = array_slice(array_merge($kept, $newDocs), 0, 3);
        $validated['documentation'] = $mergedDocs ?: null;

        // Slug unik mengikuti judul
        $validated['slug'] = Article::uniqueSlug($validated['title'], $article->id);

        // ========= TRANSAKSI DB =========
        DB::beginTransaction();
        try {
            $article->update($validated);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();

            // Cleanup file yang baru tersimpan jika DB gagal agar tidak orphan
            if ($newThumbPath) {
                Storage::disk('public')->delete($newThumbPath);
            }
            foreach ($newDocs as $p) {
                Storage::disk('public')->delete($p);
            }
            throw $e;
        }

        // ========= CLEANUP SETELAH SUKSES =========
        // Hapus dok yang tidak dipertahankan
        foreach ($removed as $p) {
            if ($p && !Str::startsWith($p,'http')) {
                Storage::disk('public')->delete($p);
            }
        }

        // Hapus thumbnail lama jika diganti & path lama local
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
        // Hapus thumbnail (jika local)
        if ($article->thumbnail && !Str::startsWith($article->thumbnail,'http')) {
            Storage::disk('public')->delete($article->thumbnail);
        }

        // Hapus dokumentasi (jika local)
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
        $qPlain = ltrim($qTrim, '#');          // buang '#' bila user ketik #tag
        if (mb_strlen($qPlain) < 2) {          // optional: cegah wildcard berat
            $qPlain = '';
        }

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
                      ->orWhere('hashtags',$op, $like); // kolom hashtag bertipe string
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $q = $qTrim; // kembalikan ke view agar input tetap terisi
        return view('articles.index', compact('articles','q'));
    }

}
