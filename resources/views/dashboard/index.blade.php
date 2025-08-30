@extends('layouts.app')

@section('title','Dashboard')

@section('content')
  <h1 class="text-3xl font-bold mb-6">Dashboard</h1>

  <div class="grid md:grid-cols-3 gap-6">
      <div class="bg-white rounded-xl shadow p-6">
          <h2 class="text-xl font-semibold mb-3">Artikel</h2>
          <a href="{{ route('articles.index') }}" class="px-3 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Kelola Artikel</a>
      </div>

      <div class="bg-white rounded-xl shadow p-6">
          <h2 class="text-xl font-semibold mb-3">Galeri</h2>
          <a href="{{ route('galleries.index') }}" class="px-3 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Kelola Galeri</a>
      </div>

      <div class="bg-white rounded-xl shadow p-6">
          <h2 class="text-xl font-semibold mb-3">Donasi</h2>
          <a href="{{ route('donations.index') }}" class="px-3 py-2 rounded bg-blue-600 text-white hover:bg-blue-700">Kelola Donasi</a>
      </div>
  </div>
@endsection
