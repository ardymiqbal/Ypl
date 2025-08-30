@extends('layouts.app')

@section('title','Galeri')

@section('content')
    @php use Illuminate\Support\Str; @endphp

    <h1 class="text-3xl font-bold mb-6">Galeri</h1>

    @if($items->count())
        <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($items as $g)
                @php
                    $mediaUrl = Str::startsWith($g->media_path,'http')
                        ? $g->media_path
                        : asset('storage/'.$g->media_path);
                @endphp

                <div id="{{ $g->slug }}" class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                    {{-- tombol tanpa inline onclick; pakai data-atribut --}}
                    <button
                        class="w-full text-left js-open-media"
                        type="button"
                        data-title="{{ e($g->title) }}"
                        data-type="{{ e($g->media_type) }}"
                        data-src="{{ $mediaUrl }}">
                        @if($g->media_type === 'image')
                            <img class="w-full aspect-square object-cover"
                                 src="{{ $mediaUrl }}" alt="{{ e($g->title) }}">
                        @else
                            <video class="w-full aspect-square object-cover" preload="metadata" muted>
                                <source src="{{ $mediaUrl }}#t=0.1" type="video/mp4">
                            </video>
                        @endif
                    </button>
                    <div class="p-3">
                        <div class="font-medium line-clamp-1">{{ $g->title }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">{{ $items->links() }}</div>
    @else
        <p class="text-gray-600">Belum ada item galeri.</p>
    @endif

    {{-- Lightbox / Fullscreen Modal --}}
    <div id="lightbox"
         class="fixed inset-0 hidden z-50 bg-black/90 backdrop-blur-sm">
        <div class="absolute top-4 right-4 flex items-center gap-2">
            <span id="lightboxTitle" class="text-white/90 text-sm"></span>
            <button type="button"
                    id="btnCloseLightbox"
                    class="px-3 py-1 rounded bg-white/20 text-white hover:bg-white/30">
                Tutup ✕
            </button>
        </div>

        <div class="w-full h-full flex items-center justify-center p-4">
            <div id="lightboxBody" class="max-w-[95vw] max-h-[95vh]"></div>
        </div>
    </div>

    {{-- Script: event delegation untuk .js-open-media --}}
    <script>
        (function () {
            const lb        = document.getElementById('lightbox');
            const body      = document.getElementById('lightboxBody');
            const titleEl   = document.getElementById('lightboxTitle');
            const btnClose  = document.getElementById('btnCloseLightbox');
            let escBound    = false;

            function openMediaModal(title, type, src) {
                if (!lb || !body) return;

                titleEl && (titleEl.textContent = title || '');

                body.innerHTML = '';
                if (type === 'image') {
                    const img = document.createElement('img');
                    img.src = src;
                    img.alt = title || 'gambar';
                    img.className = 'max-w-full max-h-[95vh] w-auto h-auto object-contain rounded';
                    body.appendChild(img);
                } else {
                    const video = document.createElement('video');
                    video.controls = true;
                    video.preload = 'metadata';
                    video.src = src;
                    video.className = 'max-w-full max-h-[95vh] w-auto h-auto object-contain rounded bg-black';
                    body.appendChild(video);
                }

                lb.classList.remove('hidden');

                // klik area gelap untuk menutup
                lb.addEventListener('click', onBackdropClick);
                btnClose && btnClose.addEventListener('click', closeMediaModal);

                if (!escBound) {
                    window.addEventListener('keydown', onEscClose);
                    escBound = true;
                }
            }

            function closeMediaModal() {
                if (!lb || !body) return;
                lb.classList.add('hidden');
                body.innerHTML = '';
                lb.removeEventListener('click', onBackdropClick);
                btnClose && btnClose.removeEventListener('click', closeMediaModal);
            }

            function onBackdropClick(e) {
                if (e.target === lb) closeMediaModal();
            }

            function onEscClose(e) {
                if (e.key === 'Escape') closeMediaModal();
            }

            // Delegasi klik untuk semua tombol .js-open-media
            document.addEventListener('click', function (e) {
                const btn = e.target.closest('.js-open-media');
                if (!btn) return;
                const title = btn.dataset.title || '';
                const type  = btn.dataset.type  || 'image';
                const src   = btn.dataset.src   || '';
                openMediaModal(title, type, src);
            });
        })();
    </script>
@endsection
