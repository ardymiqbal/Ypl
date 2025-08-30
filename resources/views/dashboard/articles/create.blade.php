@extends('layouts.app')

@section('title','Artikel Baru')

@section('content')
<h1 class="text-2xl font-bold mb-4">Buat Artikel</h1>

<form method="POST" action="{{ route('articles.store') }}" enctype="multipart/form-data" class="bg-white rounded-xl shadow p-6 space-y-4">
  @csrf

  <div>
    <label class="block text-sm font-medium">Judul</label>
    <input name="title" value="{{ old('title') }}" class="mt-1 w-full border rounded p-2" required>
    @error('title')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
  </div>

  <div>
    <label class="block text-sm font-medium">Ringkasan</label>
    <textarea name="summary" rows="3" class="mt-1 w-full border rounded p-2" required>{{ old('summary') }}</textarea>
    @error('summary')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
  </div>

  <div>
    <label class="block text-sm font-medium">Konten</label>
    <textarea name="content" rows="8" class="mt-1 w-full border rounded p-2" required>{{ old('content') }}</textarea>
    @error('content')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
  </div>

  <div class="grid md:grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium">Penulis</label>
      <input name="author" value="{{ old('author') }}" class="mt-1 w-full border rounded p-2" required>
      @error('author')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
      <label class="block text-sm font-medium">Hashtags (pisahkan dengan koma)</label>
      <input name="hashtags" value="{{ old('hashtags') }}" class="mt-1 w-full border rounded p-2" required placeholder="Hutan, Laut, Edukasi Lingkungan">
      @error('hashtags')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
  </div>

  <div class="grid md:grid-cols-2 gap-4">
    <div>
      <label class="block text-sm font-medium">Status</label>
      <select name="status" class="mt-1 w-full border rounded p-2" required>
        <option value="draft" @selected(old('status')==='draft')>Draft</option>
        <option value="published" @selected(old('status')==='published')>Published</option>
      </select>
      @error('status')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
    <div>
      <label class="block text-sm font-medium">Thumbnail (gambar)</label>
      <input type="file" name="thumbnail" accept="image/*" class="mt-1 w-full border rounded p-2" required>
      @error('thumbnail')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    </div>
  </div>

  <div>
    <label class="block text-sm font-medium">Documentation (maks 3 gambar)</label>
    <input id="doc-input-create" type="file" name="documentation[]" multiple class="mt-1 w-full border rounded p-2" accept="image/*">
    <p class="text-xs text-gray-500 mt-1">Pilih hingga 3 gambar sekaligus.</p>
    @error('documentation')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
    @error('documentation.*')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
  </div>

  <div class="flex justify-end gap-3">
    <a href="{{ route('articles.index') }}" class="px-4 py-2 rounded bg-gray-200">Batal</a>
    <button class="px-4 py-2 rounded bg-blue-600 text-white">Simpan</button>
  </div>
</form>

{{-- Batasi pilihan file di sisi client: maksimal 3 --}}
<script>
  (function () {
    const input = document.getElementById('doc-input-create');
    if (!input) return;

    input.addEventListener('change', () => {
      const max = 3;
      if (input.files.length > max) {
        alert(`Maksimal ${max} gambar. Hanya ${max} pertama yang dipakai.`);
        // Sisakan hanya 3 file pertama
        const dt = new DataTransfer();
        Array.from(input.files).slice(0, max).forEach(f => dt.items.add(f));
        input.files = dt.files;
      }
    });
  })();
</script>
@endsection
