@extends('layouts.app')

@section('title', $article->title)

@section('content')
@php use Illuminate\Support\Str; @endphp

<article class="max-w-5xl mx-auto">
    {{-- Header: Judul + meta --}}
    <header class="mb-6">
        <h1 class="text-4xl font-extrabold tracking-tight">{{ $article->title }}</h1>
        <p class="mt-1 text-slate-600 text-[15px] md:text-base">
            Oleh <strong class="font-semibold text-slate-800">{{ $article->author }}</strong>
            <span>·</span>
            {{ $article->created_at->format('d M Y') }}
        </p>
    </header>

    {{-- Thumbnail --}}
    @php
        $thumbUrl = Str::startsWith($article->thumbnail, ['http://','https://'])
            ? $article->thumbnail
            : route('articles.thumb', $article);
    @endphp
    <div class="flex justify-center">
        <img
            class="w-full aspect-[16/9] object-cover rounded-2xl shadow"
            src="{{ $thumbUrl }}"
            alt="{{ e($article->title) }}"
        >
    </div>

    {{-- Konten utama --}}
    <section class="mt-8">
        <div class="bg-white rounded-2xl shadow p-6">
            <div class="text-lg leading-8 break-words [overflow-wrap:anywhere] whitespace-pre-line">
                {!! $article->content !!}
            </div>
        </div>
    </section>

    {{-- Hashtags --}}
    <div class="mt-4 flex flex-wrap gap-2">
        @foreach($article->hashtag_array as $t)
            <a href="{{ route('articles.public.index',['q'=>$t]) }}"
               class="text-xs px-2 py-1 bg-blue-50 text-blue-700 rounded hover:bg-blue-100">#{{ e($t) }}</a>
        @endforeach
    </div>

    {{-- Dokumentasi (gambar) --}}
    @php
        $raw  = $article->documentation;
        $docs = is_array($raw) ? $raw : (is_string($raw) ? (json_decode($raw, true) ?: []) : []);
    @endphp

    @if(!empty($docs))
    <section class="mt-10">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-xl font-semibold">Documentation</h3>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @foreach($docs as $i => $d)
                @php
                    $src = Str::startsWith($d, ['http://','https://'])
                        ? $d
                        : route('articles.doc', ['article'=>$article, 'i'=>$i]);
                @endphp
                <button type="button"
                        class="relative group block w-full overflow-hidden rounded-xl bg-gray-100 shadow js-open-media"
                        data-title="Documentation"
                        data-type="image"
                        data-src="{{ $src }}">
                    <span class="absolute top-2 left-2 z-10 text-[10px] uppercase tracking-wide bg-black/60 text-white px-2 py-0.5 rounded">Foto</span>
                    <img class="w-full h-40 md:h-44 object-cover transition-transform duration-300 group-hover:scale-105"
                         src="{{ $src }}" alt="doc">
                </button>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Artikel terkait --}}
    @if($related->count())
    <section class="mt-12">
        <h2 class="text-2xl font-semibold mb-4">Artikel Terkait</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($related as $a)
                @php
                    $thumbRel = Str::startsWith($a->thumbnail, ['http://','https://'])
                        ? $a->thumbnail
                        : route('articles.thumb', $a);
                @endphp
                <article class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                    <a href="{{ route('articles.show',$a->slug) }}">
                        <img class="w-full aspect-[16/9] object-cover" src="{{ $thumbRel }}" alt="{{ e($a->title) }}">
                    </a>
                    <div class="p-4">
                        <a href="{{ route('articles.show',$a->slug) }}" class="font-semibold line-clamp-1 hover:text-blue-600">{{ $a->title }}</a>
                        <p class="text-sm text-gray-500 mt-1">Oleh {{ $a->author }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
    @endif
</article>

{{-- Lightbox sederhana --}}
<div id="lightbox" class="fixed inset-0 hidden z-50 bg-black/90 backdrop-blur-sm">
    <div class="absolute top-4 right-4 flex items-center gap-2">
        <span id="lightboxTitle" class="text-white/90 text-sm"></span>
        <button type="button" id="btnCloseLightbox"
                class="px-3 py-1 rounded bg-white/20 text-white hover:bg-white/30">
            Tutup ✕
        </button>
    </div>
    <div class="w-full h-full flex items-center justify-center p-4">
        <div id="lightboxBody" class="max-w-[95vw] max-h-[95vh]"></div>
    </div>
</div>

<script>
(function () {
    const lb       = document.getElementById('lightbox');
    const body     = document.getElementById('lightboxBody');
    const titleEl  = document.getElementById('lightboxTitle');
    const btnClose = document.getElementById('btnCloseLightbox');

    function openMediaModal(title, src) {
        if (!lb || !body) return;
        if (titleEl) titleEl.textContent = title || '';
        body.innerHTML = '';
        const img = document.createElement('img');
        img.src = src;
        img.alt = title || 'gambar';
        img.className = 'max-w-full max-h-[95vh] w-auto h-auto object-contain rounded';
        body.appendChild(img);
        lb.classList.remove('hidden');
        lb.addEventListener('click', onBackdropClick);
        btnClose && btnClose.addEventListener('click', closeMediaModal);
        window.addEventListener('keydown', onEscClose);
    }
    function closeMediaModal() {
        if (!lb || !body) return;
        lb.classList.add('hidden');
        body.innerHTML = '';
        lb.removeEventListener('click', onBackdropClick);
        btnClose && btnClose.removeEventListener('click', closeMediaModal);
        window.removeEventListener('keydown', onEscClose);
    }
    function onBackdropClick(e) { if (e.target === lb) closeMediaModal(); }
    function onEscClose(e)      { if (e.key === 'Escape') closeMediaModal(); }

    document.addEventListener('click', function (e) {
        const btn = e.target.closest('.js-open-media');
        if (!btn) return;
        openMediaModal(btn.dataset.title || '', btn.dataset.src || '');
    });
})();
</script>
@endsection
