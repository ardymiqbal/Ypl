@extends('layouts.app')

@section('title','Item Galeri Baru')

@section('content')
<h1 class="text-2xl font-bold mb-4">Buat Item Galeri</h1>

<form method="POST" action="{{ route('galleries.store') }}" enctype="multipart/form-data" class="bg-white rounded-xl shadow p-6 space-y-4">
@csrf
  <div>
    <label class="block text-sm font-medium">Judul</label>
    <input name="title" value="{{ old('title') }}" class="mt-1 w-full border rounded p-2" required>
    @error('title')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
  </div>

  <div>
    <label class="block text-sm font-medium">Deskripsi (opsional)</label>
    <textarea name="description" rows="3" class="mt-1 w-full border rounded p-2">{{ old('description') }}</textarea>
    @error('description')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
  </div>

  <div>
    <span class="block text-sm font-medium mb-1">Tipe Media</span>
    <label class="mr-4"><input type="radio" name="media_type" value="image" @checked(old('media_type','image')==='image')> Gambar</label>
    <label><input type="radio" name="media_type" value="video" @checked(old('media_type')==='video')> Video</label>
    @error('media_type')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
  </div>

  <div>
    <label class="block text-sm font-medium">File Media</label>
    <input type="file" name="media" class="mt-1 w-full border rounded p-2" required>
    <div class="text-xs text-gray-500 mt-1">Gambar: jpg/jpeg/png/webp. Video: mp4/mov/avi/mkv/webm. Maks 20MB.</div>
    @error('media')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
  </div>

  <div>
    <label class="inline-flex items-center gap-2">
      <input type="checkbox" name="is_published" value="1" @checked(old('is_published'))>
      <span>Publikasikan sekarang</span>
    </label>
  </div>

  <div class="flex justify-end gap-3">
    <a href="{{ route('galleries.index') }}" class="px-4 py-2 rounded bg-gray-200">Batal</a>
    <button class="px-4 py-2 rounded bg-blue-600 text-white">Simpan</button>
  </div>
</form>
@endsection
