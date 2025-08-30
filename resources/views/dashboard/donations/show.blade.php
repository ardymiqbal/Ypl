@extends('layouts.app')

@section('title','Detail Donasi — Dashboard')

@section('content')
<a href="{{ route('donations.index') }}" class="text-blue-600 hover:underline">← Kembali</a>

<div class="mt-3 bg-white rounded-xl shadow ring-1 ring-black/5 p-6">
  <h1 class="text-xl md:text-2xl font-extrabold mb-4">Detail Donasi</h1>

  <dl class="grid sm:grid-cols-2 gap-4 text-sm md:text-base">
    <div>
      <dt class="text-gray-500">Nama</dt>
      <dd class="font-medium">{{ $donation->name }}</dd>
    </div>
    <div>
      <dt class="text-gray-500">Email</dt>
      <dd class="font-medium">{{ $donation->email }}</dd>
    </div>
    <div>
      <dt class="text-gray-500">Status</dt>
      <dd class="font-medium capitalize">{{ $donation->status }}</dd>
    </div>
    <div>
      <dt class="text-gray-500">Tanggal</dt>
      <dd class="font-medium">{{ $donation->created_at->format('d M Y H:i') }}</dd>
    </div>
    <div class="sm:col-span-2">
      <dt class="text-gray-500">Pesan</dt>
      <dd class="font-medium whitespace-pre-line">{{ $donation->message ?: '-' }}</dd>
    </div>
  </dl>

  <div class="mt-5">
    <h2 class="font-semibold mb-2">Bukti Transfer</h2>
    @if(Str::endsWith(strtolower($donation->proof_path), ['.jpg','.jpeg','.png','.webp']))
      <img src="{{ asset('storage/'.$donation->proof_path) }}" class="w-full max-w-lg rounded shadow" alt="">
    @else
      <iframe src="{{ asset('storage/'.$donation->proof_path) }}" class="w-full h-[60vh] rounded"></iframe>
    @endif
  </div>

  <div class="mt-6 flex gap-3">
    <a href="{{ route('donations.edit',$donation) }}" class="px-3 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Edit</a>
    <form action="{{ route('donations.destroy',$donation) }}" method="POST"
          onsubmit="return confirm('Hapus donasi ini?')">
      @csrf @method('DELETE')
      <button class="px-3 py-2 rounded bg-red-600 text-white hover:bg-red-700">Hapus</button>
    </form>
  </div>
</div>
@endsection
