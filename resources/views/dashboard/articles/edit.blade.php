@extends('layouts.app')

@section('title','Edit Artikel')

@section('content')
@php
  use Illuminate\Support\Str;
  use Illuminate\Support\Facades\Route;

  // ==== Thumbnail URL (aman di shared hosting) ====
  $thumbUrl = null;
  if ($article->thumbnail) {
    if (Str::startsWith($article->thumbnail, ['http://','https://','/'])) {
      $thumbUrl = $article->thumbnail;
    } elseif (Route::has('articles.thumb')) {
      $thumbUrl = route('articles.thumb', $article);
    } else {
      // normalisasi ke asset
      $t = ltrim($article->thumbnail, '/');
      if (Str::startsWith($t, 'public/')) {
        $t = 'storage/'.substr($t, 7); // public/... -> storage/...
      } elseif (!Str::startsWith($t, 'storage/')) {
        $t = 'storage/'.$t;
      }
      $thumbUrl = asset($t);
    }
  }

  // ==== Helper URL dokumentasi (normalisasi path) ====
  $docUrl = function (?string $p) {
    if (!$p) return null;
    if (Str::startsWith($p, ['http://','https://','/'])) return $p;

    $p = ltrim($p, '/');
    if (Str::startsWith($p, 'public/')) {
      $p = 'storage/'.substr($p, 7);          // public/... -> storage/...
    } elseif (!Str::startsWith($p, 'storage/')) {
      $p = 'storage/'.$p;                      // relatif -> storage/relatif
    }
    return asset($p);
  };

  // Ambil array dokumentasi dengan aman
  $docs = is_array($article->documentation)
    ? $article->documentation
    : (json_decode($article->documentation ?? '[]', true) ?: []);
@endphp

<h1 class="text-2xl font-bold mb-4">Edit Artikel</h1>

<form method="POST" action="{{ route('articles.update',$article) }}" enctype="multipart/form-data" class="bg-white rounded-xl shadow p-6 space-y-4">
@csrf @method('PUT')
  <div>
    <label class="block text-sm font-medium">Judul</label>
    <input name="title" value="{{ old('title', $article->title) }}" class="mt-1 w-full border rounded p-2" required>
    @error('title')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
  </div>

  <div>
    <label class="block text-sm font-medium">Ringkasan</label>
    <textarea name="summary" rows="3" class="mt-1 w-full border rounded p-2" required>{{ old('summary',$article->summary) }}</textarea>
    @error('summary')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
  </div>

  <div>
    <label class="block text-sm font-medium">Konten</label>
    <textarea name="content" rows="8" class="mt-1 w-full border rounded p-2" required>{{ old('content',$article->content) }}</textarea>
    @error('content')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
  </div>

  <div class="grid md:grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium">Penulis</label>
      <input name="author" value="{{ old('author',$article->author) }}" class="mt-1 w-full border rounded p-2" required>
      @error('author')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
      <label class="block text-sm font-medium">Hashtags</label>
      <input name="hashtags" value="{{ old('hashtags',$article->hashtags) }}" class="mt-1 w-full border rounded p-2" required>
      @error('hashtags')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
  </div>

  <div class="grid md:grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium">Status</label>
      <select name="status" class="mt-1 w-full border rounded p-2" required>
        <option value="draft" @selected(old('status',$article->status)==='draft')>Draft</option>
        <option value="published" @selected(old('status',$article->status)==='published')>Published</option>
      </select>
      @error('status')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
      <label class="block text-sm font-medium">Thumbnail (opsional ganti)</label>
      <input type="file" name="thumbnail" accept="image/*" class="mt-1 w-full border rounded p-2">
      <div class="text-xs text-gray-500 mt-1">
        Saat ini:
        @if($thumbUrl)
          <a class="underline" target="_blank" rel="noopener" href="{{ $thumbUrl }}">lihat</a>
        @else
          <span class="text-gray-400">—</span>
        @endif
      </div>
      @error('thumbnail')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
  </div>

  @if(count($docs))
    <div>
      <label class="block text-sm font-medium mb-2">Documentation saat ini (centang yang ingin dipertahankan)</label>
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
        @foreach($docs as $p)
          @php
            $src = $docUrl($p); // <- gunakan normalisasi path
            $isVideo = Str::endsWith(strtolower((string)$p), ['.mp4','.mov','.avi','.mkv','.webm']);
          @endphp
          <label class="border rounded p-2 flex gap-2 items-start bg-gray-50">
            <input type="checkbox" name="keep_existing_docs[]" value="{{ $p }}" checked class="mt-1">
            <div class="flex-1">
              @if($isVideo)
                <video class="w-full" controls preload="metadata" playsinline>
                  <source src="{{ $src }}">
                </video>
              @else
                <img class="w-full h-40 object-cover rounded" src="{{ $src }}" alt="doc" loading="lazy" decoding="async">
              @endif
              <div class="text-xs mt-1 break-all">{{ $p }}</div>
            </div>
          </label>
        @endforeach
      </div>
    </div>
  @endif

  <div>
    <label class="block text-sm font-medium">Tambah Documentation (opsional, maks 3 total)</label>
    <input type="file" name="documentation[]" multiple class="mt-1 w-full border rounded p-2" accept="image/*">
    @error('documentation.*')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    @error('documentation')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
  </div>

  <div class="flex justify-end gap-3">
    <a href="{{ route('articles.index') }}" class="px-4 py-2 rounded bg-gray-200">Batal</a>
    <button class="px-4 py-2 rounded bg-blue-600 text-white">Perbarui</button>
  </div>
</form>
@endsection
