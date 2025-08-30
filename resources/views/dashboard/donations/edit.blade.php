@extends('layouts.app')

@section('title','Edit Donasi — Dashboard')

@section('content')
@php
  use Illuminate\Support\Str;
@endphp

<a href="{{ route('donations.index') }}" class="text-blue-600 hover:underline">← Kembali</a>

<div class="mt-3 bg-white rounded-xl shadow ring-1 ring-black/5 p-6">
  <h1 class="text-xl md:text-2xl font-extrabold mb-4">Edit Donasi</h1>

  {{-- Info donatur (read-only) --}}
  <div class="grid sm:grid-cols-2 gap-4 mb-6">
    <div>
      <label class="block text-sm text-gray-600">Nama</label>
      <input type="text" class="w-full rounded border-gray-300 bg-gray-50" value="{{ $donation->name }}" disabled>
    </div>
    <div>
      <label class="block text-sm text-gray-600">Email</label>
      <input type="text" class="w-full rounded border-gray-300 bg-gray-50" value="{{ $donation->email }}" disabled>
    </div>
  </div>

  <div class="mb-6">
    <label class="block text-sm text-gray-600">Pesan</label>
    <textarea class="w-full rounded border-gray-300 bg-gray-50" rows="4" disabled>{{ $donation->message }}</textarea>
  </div>

  @if($donation->proof_path)
    @php
      $fileUrl = Str::startsWith($donation->proof_path, ['http://','https://'])
        ? $donation->proof_path
        : asset('storage/'.$donation->proof_path);
      $lower = strtolower($donation->proof_path);
    @endphp
    <div class="mb-6">
      <span class="block text-sm text-gray-600 mb-1">Bukti Transfer</span>
      @if(Str::endsWith($lower, ['.jpg','.jpeg','.png','.webp']))
        <img src="{{ $fileUrl }}" class="h-40 rounded border" alt="">
      @else
        <a href="{{ $fileUrl }}" target="_blank" rel="noopener" class="text-blue-600 hover:underline">
          Lihat file
        </a>
      @endif
    </div>
  @endif

  {{-- Form: hanya status --}}
  <form action="{{ route('donations.update',$donation) }}" method="POST" class="space-y-4">
    @csrf @method('PUT')

    <div class="max-w-xs">
      <label class="block text-sm font-medium mb-1">Status</label>
      <select name="status" class="w-full rounded border-gray-300" required>
        @foreach(['pending'=>'Pending','verified'=>'Verified','rejected'=>'Rejected'] as $k=>$v)
          <option value="{{ $k }}" @selected(old('status',$donation->status)===$k)>{{ $v }}</option>
        @endforeach
      </select>
      @error('status') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
    </div>

    <div class="pt-2">
      <button class="px-4 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">
        Simpan Perubahan
      </button>
    </div>
  </form>
</div>
@endsection
