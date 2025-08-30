@extends('layouts.app')

@section('title','Admin Login')

@section('container_class','max-w-md mx-auto px-4')
@section('container_padding','py-10')

@section('content')
  <div class="bg-white rounded-xl shadow p-6 md:p-8">
    <h1 class="text-2xl font-extrabold text-center">Login Admin</h1>
    <p class="mt-1 text-center text-gray-600 text-sm">Akses khusus untuk pengelola.</p>

    @if ($errors->any())
      <div class="mt-4 p-3 rounded bg-red-50 text-red-700 text-sm">
        <ul class="list-disc list-inside">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('admin.login.submit') }}" class="mt-6 space-y-4">
      @csrf
      <div>
        <label class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus
               class="mt-1 w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none">
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" name="password" required
               class="mt-1 w-full border rounded-lg p-2.5 focus:ring-2 focus:ring-blue-500 focus:outline-none">
      </div>
      <div class="flex items-center justify-between">
        <label class="inline-flex items-center gap-2 text-sm">
          <input type="checkbox" name="remember" class="rounded border-gray-300">
          Ingat saya
        </label>
        <button class="px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700">
          Masuk
        </button>
      </div>
    </form>
  </div>
@endsection
