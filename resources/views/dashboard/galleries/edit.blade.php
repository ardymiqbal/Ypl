@extends('layouts.app')

@section('title','Edit Item Galeri')

@section('content')
@php use Illuminate\Support\Str; @endphp

<h1 class="text-2xl font-bold mb-4">Edit Item Galeri</h1>

<form method="POST" action="{{ route('galleries.update',$gallery) }}" enctype="multipart/form-data" class="bg-white rounded-xl shadow p-6 space-y-4">
@csrf @method('PUT')
  <div>
    <label class="block text-sm font-medium">Judul</label>
    <input name="title" value="{{ old('title',$gallery->title) }}" class="mt-1 w-full border rounded p-2" required>
    @error('title')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
  </div>

  <div>
    <label class="block text-sm font-medium">Deskripsi (opsional)</label>
    <textarea name="description" rows="3" class="mt-1 w-full border rounded p-2">{{ old('description',$gallery->description) }}</textarea>
    @error('description')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
  </div>

  <div>
    <span class="block text-sm font-medium mb-1">Tipe Media</span>
    <label class="mr-4"><input type="radio" name="media_type" value="image" @checked(old('media_type',$gallery->media_type)==='image')> Gambar</label>
    <label><input type="radio" name="media_type" value="video" @checked(old('media_type',$gallery->media_type)==='video')> Video</label>
    @error('media_type')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
  </div>

  <div>
    <label class="block text-sm font-medium">File Media (opsional ganti)</label>
    <input type="file" name="media" class="mt-1 w-full border rounded p-2">

    @php
      $currentSrc = Str::startsWith($gallery->media_path, ['http://','https://'])
        ? $gallery->media_path
        : route('galleries.media', $gallery);
    @endphp

    <div class="text-xs text-gray-500 mt-1">Saat ini:
      <a class="underline" target="_blank" href="{{ $currentSrc }}">lihat</a>
    </div>

    {{-- Preview langsung --}}
    <div class="mt-2">
      @if($gallery->media_type === 'image')
        <img src="{{ $currentSrc }}" alt="{{ e($gallery->title) }}" class="w-64 h-36 object-cover rounded ring-1 ring-black/5">
      @elseif($gallery->media_type === 'video')
        <video src="{{ $currentSrc }}#t=0.1" class="w-64 h-36 object-cover rounded ring-1 ring-black/5" preload="metadata" controls playsinline muted></video>
      @endif
    </div>

    @error('media')<div class="text-red-600 text-sm">{{ $message }}</div>@enderror
  </div>

  <div>
    <label class="inline-flex items-center gap-2">
      <input type="checkbox" name="is_published" value="1" @checked(old('is_published',$gallery->is_published))>
      <span>Publikasikan</span>
    </label>
  </div>

  <div class="flex justify-end gap-3">
    <a href="{{ route('galleries.index') }}" class="px-4 py-2 rounded bg-gray-200">Batal</a>
    <button class="px-4 py-2 rounded bg-blue-600 text-white">Perbarui</button>
  </div>
</form>
@endsection
