<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title','Yayasan Pemerhati Lingkungan')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo-text.png') }}">

    {{-- Tailwind CDN (boleh ganti ke Vite bila perlu) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- CSRF meta untuk form --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Util kecil untuk clamp --}}
    <style>
      .line-clamp-3{display:-webkit-box;line-clamp:3;-webkit-box-orient:vertical;overflow:hidden}
    </style>

    @stack('head')
</head>
<body class="bg-gray-50 text-gray-800 min-h-screen flex flex-col">

    {{-- NAVBAR (pakai background gambar) --}}
    @include('partials.navbar')

    {{-- KONTEN --}}
    <main class="flex-1 w-full">
      <div class="@yield('container_class','max-w-7xl mx-auto px-4 md:px-6') @yield('container_padding','py-8')">
        @yield('content')
      </div>
    </main>

    {{-- FOOTER (pakai background gambar) --}}
    @include('partials.footer')

    {{-- Lightbox global (modal + JS) --}}
    @include('partials.media-lightbox')

    @stack('scripts')
</body>
</html>
