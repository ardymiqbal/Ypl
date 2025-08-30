@extends('layouts.app')

@section('title','Pengelolaan Sampah & Daur Ulang')

{{-- Lebarkan konten + padding 60px --}}
@section('container_class','w-full max-w-none px-4 md:px-[60px]')
@section('container_padding','py-6 md:py-8')

@section('content')
@php use Illuminate\Support\Str; @endphp

{{-- ======= HEADER + FOTO UTAMA (diperkecil) ======= --}}
<section class="bg-white rounded-3xl shadow p-4 sm:p-6 md:p-8">
  <h1 class="font-extrabold leading-tight text-gray-900 text-balance text-[clamp(22px,6vw,40px)] md:text-[clamp(28px,3.8vw,52px)]">
    Pengelolaan Sampah dan Daur Ulang
  </h1>
  <h2 class="mt-1 text-blue-700 font-semibold text-balance text-[clamp(16px,4.8vw,22px)] md:text-3xl">
    Kelompok Pemerhati Sampah Desa Lukpanenteng
  </h2>

  {{-- Hero: tinggi pas untuk HP --}}
  <div class="mt-4 overflow-hidden rounded-xl ring-1 ring-black/5">
    <img
      src="{{ $hero }}"
      alt="Kegiatan Kelompok"
      class="w-full h-56 sm:h-64 md:h-[28rem] lg:h-[34rem] xl:h-[40rem] object-cover"
    />
  </div>

  {{-- Deskripsi --}}
  <div class="mt-5 font-sans font-medium text-gray-800 text-pretty text-[15px] leading-7 md:text-[18px] md:leading-8">
    <p>
      Kelompok Pemerhati Sampah Desa Lukpanenteng adalah wadah masyarakat Desa Lukpanenteng yang peduli  terhadap kebersihan lingkungan dan pengelolaan sampah secara berkelanjutan. Dibentuk pada tanggal 15 Juli  2025 atas inisiatif dari Direktur Yayasan Pemerhati Lingkungan (YPL) Ibu Fatiyah Suryani Mile dan diresponi  secara positif oleh Kelompok Perempuan dan didukung oleh Pemerintah Daerah Kabupaten Banggai Kepulauan.  Kelompok ini bertujuan mengurangi pencemaran lingkungan melalui pendekatan gotong royong, edukasi, dan  inovasi pengelolaan sampah.  
      Kami percaya bahwa desa yang bersih adalah pondasi utama bagi kehidupan yang sehat, nyaman, dan berdaya.  Oleh karena itu, kami berkomitmen menjalankan berbagai kegiatan seperti pemilahan sampah, pengelolaan TPS  3R, pengolahan kompos dari limbah organik, dan edukasi lingkungan kepada masyarakat.  Nilai-Nilai Kami :  
    </p>

    <ul class="mt-4 list-disc list-inside space-y-1">
      <li>Mengurangi timbulan sampah rumah tangga.</li>
      <li>Memperkuat edukasi pilah sampah sejak dini.</li>
      <li>Mengolah anorganik bernilai ekonomi (bank sampah/ekonomi sirkular).</li>
      <li>Membangun jejaring kolaborasi lintas sektor.</li>
      <li>Mendorong perilaku ramah lingkungan pada kegiatan masyarakat.</li>
    </ul>

    <p class="mt-4">
      Kami terbuka untuk kolaborasi dengan berbagai pihak, termasuk pemerintah, sekolah, pemuda, dan mitra  lingkungan lainnya. Bersama-sama, mari wujudkan Desa Lukpanenteng yang bersih, sehat, dan berkelanjutan.
    </p>
  </div>
</section>

{{-- ======= POSTER EDUKASI (portrait / diperpanjang) ======= --}}
<section class="mt-8">
  <div class="mt-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
    @forelse($posters as $p)
      <button type="button"
              class="js-open-media block overflow-hidden rounded-xl bg-white ring-1 ring-black/5 shadow hover:shadow-lg transition"
              data-title="Poster Edukasi"
              data-type="image"
              data-src="{{ $p }}">
        {{-- portrait lebih panjang --}}
        <img src="{{ $p }}" alt="Poster" class="w-full aspect-[3/4] md:aspect-[2/3] object-cover">
      </button>
    @empty
      <p class="text-gray-600">Belum ada poster.</p>
    @endforelse
  </div>
</section>

{{-- ================= GALERI: SHOW 3 ONLY, CENTER HIGHLIGHT ================= --}}
@php
  $galItems = collect($galleryImages ?? [])->map(function($g){
    $mediaUrl = Str::startsWith($g->media_path, ['http://','https://'])
      ? $g->media_path
      : route('galleries.media', $g); // <-- ambil via route streaming
    return ['title'=>$g->title, 'type'=>$g->media_type, 'src'=>$mediaUrl];
  })->values()->all();
@endphp

<section id="gallery3"
         class="mt-10 px-4 md:px-0 overflow-x-hidden"
         data-items='{{ json_encode($galItems, JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) }}'>
  <div class="grid grid-cols-[1fr_auto_1fr] items-center mb-4 md:mb-6">
    <div></div>
    <h2 class="justify-self-center text-3xl font-bold">Galeri</h2>
    <div class="justify-self-end flex gap-2">
      <button id="g3Prev" type="button"
        class="p-2 rounded-full bg-white shadow ring-1 ring-black/5 hover:bg-gray-50"
        aria-label="Sebelumnya">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
      </button>
      <button id="g3Next" type="button"
        class="p-2 rounded-full bg-white shadow ring-1 ring-black/5 hover:bg-gray-50"
        aria-label="Berikutnya">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
      </button>
    </div>
  </div>

  @if($galleryImages->count())
    <div id="g3Wrap"
         class="mt-2 sm:mt-3 flex items-stretch justify-center gap-3 sm:gap-4 select-none
                transition-opacity duration-300 ease-out
                min-h-[160px] sm:min-h-[220px] md:min-h-[280px]">
    </div>
    <div class="mt-4 text-right">
      <!-- <a href="{{ route('galleries.public') }}" class="text-blue-600 hover:underline">Lihat semua →</a> -->
    </div>
  @else
    <p class="text-gray-600">Belum ada item galeri.</p>
  @endif
</section>


{{-- ======= KABAR TERBARU ======= --}}
<section class="mt-10">
  <h3 class="font-bold text-gray-800 text-[18px] sm:text-2xl md:text-3xl">Kabar Terbaru</h3>
  @if($latestArticles->count())
    <div class="mt-3 grid gap-4 sm:gap-6 sm:grid-cols-2 lg:grid-cols-3">
      @foreach($latestArticles as $a)
        @php $thumb = Str::startsWith($a->thumbnail,'http') ? $a->thumbnail : route('articles.thumb'.$a->thumbnail); @endphp
        <article class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden ring-1 ring-black/5">
          <a href="{{ route('articles.show',$a->slug) }}">
            <img src="{{ $thumb }}" alt="{{ e($a->title) }}" class="w-full aspect-[16/10] md:aspect-[16/9] object-cover">
          </a>
          <div class="p-3 sm:p-4">
            <a href="{{ route('articles.show',$a->slug) }}" class="font-semibold text-[15px] sm:text-base leading-snug line-clamp-2 hover:text-blue-600">
              {{ $a->title }}
            </a>
            <p class="mt-1 text-[11px] sm:text-xs text-gray-500">{{ $a->author }} · {{ $a->created_at->format('d M Y') }}</p>
          </div>
        </article>
      @endforeach
    </div>
  @else
    <p class="text-gray-600 mt-2">Belum ada artikel.</p>
  @endif
</section>


<!-- ========= SCRIPT: GALERI 3-ITEM, CENTER HIGHLIGHT (SMOOTH) ========= -->
<script>
(function () {
  const root = document.getElementById('gallery3');
  if (!root) return;

  const wrap = document.getElementById('g3Wrap');
  const prev = document.getElementById('g3Prev');
  const next = document.getElementById('g3Next');

  // Data: [{title, type:'image'|'video', src}]
  const items = JSON.parse(root.dataset.items || '[]');
  let center = 0;                      // index item tengah
  const cardCache = new Map();         // cache Node biar tidak rebuild
  let isDragging = false, startX = 0, dx = 0, rafId = null;
  const THRESH = 50;                   // ambang swipe
  const DRAG_FACTOR = 0.35;            // sensitivitas drag → gerak lebih halus

  // --- Util ---
  function clampIndex(i) {
    const n = items.length;
    return (i + n) % n;
  }
  function idxs(c) {
    const n = items.length;
    if (n === 0) return [];
    if (n === 1) return [0];
    if (n === 2) return [c % 2, (c + 1) % 2];
    return [clampIndex(c - 1), clampIndex(c), clampIndex(c + 1)];
  }
  function btnState() {
    const disable = items.length < 2;
    [prev, next].forEach(b => {
      if (!b) return;
      b.classList.toggle('opacity-50', disable);
      b.classList.toggle('pointer-events-none', disable);
      b.setAttribute('aria-disabled', disable ? 'true' : 'false');
    });
  }

  async function preload(it) {
    try {
      if (it.type === 'image') {
        const im = new Image();
        im.src = it.src;
        // prioritaskan decode agar pas muncul tidak “kedip”
        await (im.decode ? im.decode() : new Promise((res, rej) => {
          im.onload = res; im.onerror = res;
        }));
      } else {
        // Preload metadata video saja untuk cepat; play hanya di tengah
        const v = document.createElement('video');
        v.preload = 'metadata';
        v.muted = true;
        v.playsInline = true;
        v.src = it.src + (it.src.includes('#') ? '' : '#t=0.1');
        await new Promise(res => {
          v.onloadeddata = res; v.onerror = res;
        });
      }
    } catch (_) {}
  }

  function setRole(btn, role) {
    const inner = btn.firstElementChild;
    // reset
    btn.classList.remove('opacity-60','hover:opacity-80','scale-95','opacity-100','scale-105','z-10');
    inner.classList.remove('ring-2','ring-blue-500/40','shadow-2xl');

    if (role === 'center') {
      btn.classList.add('opacity-100','scale-105','z-10');
      inner.classList.add('ring-2','ring-blue-500/40','shadow-2xl');
      // kelola video: hanya center yang “aktif”
      const vid = btn.querySelector('video');
      if (vid) { try { vid.currentTime = Math.min(vid.currentTime || 0, 0.1); vid.play().catch(()=>{}); } catch(_) {} }
    } else {
      btn.classList.add('opacity-60','hover:opacity-80','scale-95');
      const vid = btn.querySelector('video');
      if (vid) { try { vid.pause(); } catch(_) {} }
    }
  }

  function makeCard(i) {
    if (cardCache.has(i)) return cardCache.get(i);

    const it = items[i];
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'js-open-media shrink-0 w-full max-w-[320px] sm:max-w-none sm:w-[44%] md:w-[30%] lg:w-[26%] transition-all duration-300 ease-out will-change-transform';
    btn.dataset.title = it.title || '';
    btn.dataset.type  = it.type  || 'image';
    btn.dataset.src   = it.src   || '';

    const inner = document.createElement('div');
    inner.className = 'rounded-2xl overflow-hidden bg-white ring-1 ring-black/5 shadow';

    if (it.type === 'video') {
      const v = document.createElement('video');
      v.className   = 'w-full aspect-[3/2] sm:aspect-[4/3] object-cover';
      v.preload     = 'metadata'; v.muted = true; v.playsInline = true;
      v.src         = it.src + (it.src.includes('#') ? '' : '#t=0.1');
      inner.appendChild(v);
    } else {
      const img = document.createElement('img');
      img.className = 'w-full aspect-[3/2] sm:aspect-[4/3] object-cover';
      img.loading   = 'lazy';
      img.decoding  = 'async';
      img.src = it.src; img.alt = it.title || 'media';
      inner.appendChild(img);
    }

    btn.appendChild(inner);
    cardCache.set(i, btn);
    return btn;
  }

  async function render(newCenter, withFade = true) {
    const showIdx = idxs(newCenter);
    // Preload semua target terlebih dahulu
    await Promise.all(showIdx.map(i => preload(items[i])));

    // Susun children: left, center, right (atau sesuai jumlah)
    const frag = document.createDocumentFragment();
    showIdx.forEach((i, k) => {
      const role = (showIdx.length === 1) ? 'center' :
                   (showIdx.length === 2 ? (k === 0 ? 'center':'right') :
                    (k === 1 ? 'center' : (k === 0 ? 'left' : 'right')));
      const card = makeCard(i);
      setRole(card, role);
      frag.appendChild(card);
    });

    if (withFade) wrap.classList.add('opacity-0');
    // replaceChildren lebih cepat, tapi kita pakai cache node supaya tidak alokasi ulang
    wrap.replaceChildren(frag);
    // next frame → fade-in
    requestAnimationFrame(() => wrap.classList.remove('opacity-0'));

    center = newCenter;
    btnState();
  }

  // --- Animasi geser (drag) halus ---
  function getX(e) { return (e.touches && e.touches[0] ? e.touches[0].clientX : e.clientX); }

  function onDown(e) {
    if (items.length < 2) return;
    isDragging = true; dx = 0; startX = getX(e);
    wrap.style.transition = 'none';
    // sentuh GPU
    wrap.style.willChange = 'transform';
    cancelAnimationFrame(rafId);
  }
  function onMove(e) {
    if (!isDragging) return;
    const raw = getX(e) - startX;
    dx = raw * DRAG_FACTOR;
    if (!rafId) {
      rafId = requestAnimationFrame(() => {
        wrap.style.transform = `translate3d(${dx}px,0,0)`;
        rafId = null;
      });
    }
  }
  function onUp() {
    if (!isDragging) return;
    isDragging = false;

    // kembalikan transisi snap
    wrap.style.transition = 'transform 280ms ease-out';
    wrap.style.willChange = 'auto';

    // putuskan pindah slide atau snap balik
    if (Math.abs(dx) > THRESH) {
      const dir = dx < 0 ? 1 : -1;
      wrap.style.transform = `translate3d(${dir * -60}px,0,0)`; // sedikit ekstra untuk kesan momentum
      render(clampIndex(center + (dir > 0 ? 1 : -1)), false).then(() => {
        // reset transform setelah render
        requestAnimationFrame(() => {
          wrap.style.transform = 'translate3d(0,0,0)';
        });
      });
    } else {
      wrap.style.transform = 'translate3d(0,0,0)';
    }
    dx = 0;
  }

  // --- Event ---
  // Klik tombol
  prev && prev.addEventListener('click', () => {
    if (items.length < 2) return;
    render(clampIndex(center - 1));
  });
  next && next.addEventListener('click', () => {
    if (items.length < 2) return;
    render(clampIndex(center + 1));
  });

  // Drag (mouse + touch) — pakai passive untuk performa
  wrap.addEventListener('mousedown', onDown);
  window.addEventListener('mousemove', onMove, { passive: true });
  window.addEventListener('mouseup', onUp, { passive: true });

  wrap.addEventListener('touchstart', onDown, { passive: true });
  window.addEventListener('touchmove', onMove, { passive: true });
  window.addEventListener('touchend', onUp, { passive: true });
  window.addEventListener('touchcancel', onUp, { passive: true });

  // Init
  if (items.length) {
    wrap.classList.add('opacity-0');
    render(0);
  } else {
    btnState();
  }
})();
</script>
@endsection
