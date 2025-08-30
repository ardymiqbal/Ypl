@extends('layouts.app')

{{-- Full width + padding kiri/kanan 15 (≈60px) --}}
@section('container_class','w-full max-w-none px-4 md:px-[60px]')

{{-- Hapus padding atas supaya hero tidak “turun” --}}
@section('container_padding','pt-0 pb-8')

@section('title','YAYASAN PEMERHATI LINGKUNGAN')

@section('content')
@php
  use Illuminate\Support\Str;

  // Jika controller tidak mengoper $heroImages, pakai default berikut
  $hero = $heroImages ?? [
    asset('images/hero1.jpeg'),
    asset('images/hero2.jpg'),
    asset('images/hero3.jpg'),
  ];
@endphp

{{-- ================= HERO SLIDER (FULL BLEED) ================= --}}
<section id="heroRoot"
         class="relative overflow-hidden shadow -mx-4 md:-mx-[60px] group select-none"
         data-slides='{{ json_encode(array_values($hero), JSON_HEX_TAG|JSON_HEX_AMP|JSON_HEX_APOS|JSON_HEX_QUOT) }}'>

  <img id="heroSlide"
       src="{{ $hero[0] ?? '' }}"
       alt="Hero"
       class="w-full h-[44vh] sm:h-[50vh] md:h-[62vh] lg:h-[72vh] object-cover transition-transform duration-200 will-change-transform">

  <div class="absolute inset-0 bg-gradient-to-t from-blue-950/70 via-blue-900/40 to-transparent"></div>
  <div class="absolute inset-0 flex items-end pb-6 md:pb-10 lg:pb-10">
    <div class="p-6 md:p-8 lg:p-10 text-white max-w-4xl lg:max-w-7xl">
      <h1 class="font-extrabold leading-tight tracking-tight text-balance text-[clamp(22px,5.5vw,40px)] md:text-[clamp(28px,3.8vw,52px)]">
        Kami percaya perubahan dimulai dari kesadaran dan aksi kita untuk bumi!
      </h1>
    </div>
  </div>

  <!-- Panah kiri (hidden, muncul saat hover/focus) -->
  <button id="prevHero" type="button"
          class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full w-9 h-9 grid place-items-center shadow
                 transition-opacity duration-200 opacity-0 pointer-events-none
                 group-hover:opacity-100 group-hover:pointer-events-auto
                 focus:opacity-100 focus-visible:opacity-100"
          aria-label="Sebelumnya">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
    </svg>
  </button>

  <!-- Panah kanan (hidden, muncul saat hover/focus) -->
  <button id="nextHero" type="button"
          class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white rounded-full w-9 h-9 grid place-items-center shadow
                 transition-opacity duration-200 opacity-0 pointer-events-none
                 group-hover:opacity-100 group-hover:pointer-events-auto
                 focus:opacity-100 focus-visible:opacity-100"
          aria-label="Berikutnya">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
    </svg>
  </button>

  <div class="absolute bottom-3 left-0 right-0 flex justify-center gap-2">
    @foreach($hero as $i => $h)
      <span class="hero-dot w-2.5 h-2.5 rounded-full bg-white/50 {{ $i===0 ? 'ring-2 ring-white bg-white' : '' }}"></span>
    @endforeach
  </div>
</section>

{{-- ====== TENTANG + VISI & MISI (FULL-WIDTH WHITE) ====== --}}
<section class="relative -mx-4 md:-mx-[60px] bg-white">
  <div class="px-4 md:px-[60px] py-8 md:py-12">
    <div class="max-w-7xl mx-auto">
      <div>
        <h2 class="text-center text-[15px] sm:text-xl md:text-3xl tracking-widest font-extrabold text-blue-700 uppercase leading-tight">
          TENTANG KAMI
        </h2>

        <h3 class="text-center mt-2 text-[22px] sm:text-2xl md:text-3xl font-extrabold pt-3">
          Bergerak Bersama Untuk Jaga Lingkungan
        </h3>

        <p class="mt-5 font-sans font-medium text-gray-800 text-pretty text-[15px] leading-7 sm:text-base sm:leading-7 md:text-[18px] md:leading-8 text-center">
          Yayasan Pemerhati Lingkungan (YPL) adalah organisasi nirlaba yang didirikan pada tahun 1999 sebagai bentuk kepedulian
          terhadap kondisi lingkungan hidup yang semakin memprihatinkan. Kami hadir untuk menjadi wadah kolaborasi masyarakat,
          komunitas, dan pemangku kepentingan dalam menjaga, melestarikan, dan memulihkan lingkungan demi keberlanjutan hidup
          generasi sekarang dan mendatang.
        </p>

        <p class="mt-4 font-sans font-medium text-gray-800 text-pretty text-[15px] leading-7 sm:text-base sm:leading-7 md:text-[18px] md:leading-8 text-center">
          Kami menginisiasi berbagai kegiatan seperti edukasi lingkungan, penghijauan, pengelolaan sampah, konservasi sumber
          daya alam, keberlanjutan metode penangkapan ikan, pariwisata hingga advokasi kebijakan ramah lingkungan. Melalui
          fokus-kerja-fokus-kerja nyata, kami mengajak semua pihak untuk peduli, bertindak, dan menjadi bagian dari solusi atas krisis
          lingkungan yang kita hadapi hari ini.
        </p>
      </div>

      <div class="mt-10 grid md:grid-cols-12 gap-8 items-center">
        <div class="md:col-span-5 flex justify-center">
          <div class="w-56 h-56 sm:w-64 sm:h-64 md:w-[22rem] md:h-[22rem] rounded-full overflow-hidden">
            <img src="{{ asset('images/logo-text.png') }}" alt="Logo" class="w-full h-full object-cover">
          </div>
        </div>

        <div class="md:col-span-7">
          <h4 class="text-blue-700 font-extrabold uppercase text-2xl md:text-3xl">VISI</h4>
          <p class="mt-2 text-gray-800 text-[15px] sm:text-base md:text-lg leading-7 md:leading-8 font-medium">
            Mewujudkan kelautan dan perikanan yang berkelanjutan ekosistem dan lingkungan yang berkualitas bagi perlindungan keanekaragaman hayati dan optimalisasi manfaat ekonomi secara adil dan cara berkelanjutan dari keanekaragaman hayati dan pelayanan lingkungan.
          </p>

          <h4 class="text-blue-700 font-extrabold uppercase text-2xl md:text-3xl mt-8">MISI</h4>
          <p class="mt-2 text-gray-800 text-[15px] sm:text-base md:text-lg leading-7 md:leading-8 font-medium">
            Terwujudnya kelestarian alam hayati sumber daya untuk kesejahteraan masyarakat yang mendukungnya terwujudnya keberlanjutan kelautan, perikanan dan sumber daya lingkungan hidup untuk kesejahteraan masyarakat dan mendukung terwujudnya Indonesia maju yaitu berdaulat, mandiri dan berkepribadian berdasarkan gotong royong.
          </p>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- ================= LATEST NEWS (ARTIKEL) ================= --}}
<section class="relative -mx-4 md:-mx-[60px]">
  <div class="absolute inset-0 -z-10">
    <img src="{{ asset('images/article.png') }}" class="w-full h-full object-cover" alt="">
  </div>

  <div class="px-4 md:px-[60px] py-10">
    <div class="max-w-7xl mx-auto">
      <div class="text-center">
        <h2 class="inline-block rounded px-2 py-1 sm:bg-transparent text-white text-lg sm:text-2xl md:text-4xl font-semibold tracking-wide">
          Latest News
        </h2>
      </div>

      <div class="mt-6 space-y-6">

        @forelse($articles as $a)
          @php
            // Thumbnail: jika eksternal pakai langsung, jika lokal ambil dari storage/public
            $thumb = $a->thumbnail
              ? (Str::startsWith($a->thumbnail, ['http://','https://'])
                    ? $a->thumbnail
                    : asset('storage/'.$a->thumbnail))
              : null;
          @endphp

          <article class="bg-white/90 backdrop-blur-sm rounded-xl shadow">
            <div class="grid gap-5 md:grid-cols-12 p-4 md:p-5">
              <a href="{{ route('articles.show',$a->slug) }}" class="md:col-span-4 block">
                @if($thumb)
                  <img class="w-full aspect-[16/9] object-cover rounded-lg shadow"
                       src="{{ $thumb }}" alt="{{ e($a->title) }}">
                @else
                  <div class="w-full aspect-[16/9] rounded-lg shadow bg-slate-100 grid place-items-center text-slate-400 text-sm">
                    Tanpa gambar
                  </div>
                @endif
              </a>

              <div class="md:col-span-8">
                <div class="flex flex-wrap gap-2 text-xs mb-1">
                  @foreach($a->hashtag_array as $t)
                    <a href="{{ route('articles.public.index',['q'=>$t]) }}" class="text-blue-700 hover:underline font-semibold">#{{ e($t) }}</a>
                  @endforeach
                </div>
                <h3 class="text-xl md:text-2xl font-extrabold leading-snug">
                  <a href="{{ route('articles.show',$a->slug) }}" class="hover:underline">{{ $a->title }}</a>
                </h3>
                <p class="mt-2 text-gray-700 text-[15px] sm:text-sm md:text-base leading-6 sm:leading-6 md:leading-7 line-clamp-3">
                  {{ $a->summary }}
                </p>
                <p class="mt-2 text-xs text-gray-500">{{ $a->author }} · {{ $a->created_at->format('d M Y') }}</p>
              </div>
            </div>
          </article>
        @empty
          <p class="text-white/90">Belum ada artikel.</p>
        @endforelse

        @if(method_exists($articles,'links'))
          <div class="pt-2">
            <div class="bg-white/90 backdrop-blur-sm rounded-lg p-3">
              {{ $articles->links() }}
            </div>
          </div>
        @endif
      </div>
    </div>
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

{{-- ================= VIDEO UNGGULAN (SATU, BESAR) ================= --}}
@php
  $v = $featuredVideo ?? null;
  $vSrc = $v
    ? (Str::startsWith($v->media_path, ['http://','https://'])
        ? $v->media_path
        : route('galleries.media', $v))  // <-- via route streaming
    : null;
@endphp

@if($v && $vSrc)
<section id="featuredVideo" class="mt-12">
  <div class="max-w-6xl mx-auto">
    <article class="rounded-2xl overflow-hidden bg-white ring-1">
      <div class="relative">
        <video
          class="w-full aspect-[16/9] md:aspect-[21/9] object-cover"
          src="{{ $vSrc }}#t=0.1"
          preload="metadata"
          controls
          playsinline
        ></video>
      </div>
    </article>
  </div>
</section>
@endif


{{-- ================= PARTNER ================= --}}
<section class="mt-12 p-6 md:p-8">
  <h2 class="text-center text-2xl md:text-2xl lg:text-3xl font-extrabold tracking-wide text-gray-800">
    Partner Kerjasama Kami
  </h2>
  <div class="mt-8 md:mt-10 lg:mt-12 flex flex-wrap items-center justify-center gap-8 md:gap-12">
    <img src="{{ asset('images/partner1.png') }}" class="h-[84px] sm:h-[110px] md:h-[140px] lg:h-[180px] object-contain" alt="Partner 1">
    <img src="{{ asset('images/partner2.png') }}" class="h-[84px] sm:h-[110px] md:h-[140px] lg:h-[180px] object-contain" alt="Partner 2">
    <img src="{{ asset('images/partner3.jpg') }}" class="h-[84px] sm:h-[110px] md:h-[140px] lg:h-[180px] object-contain" alt="Partner 3">
  </div>
</section>

{{-- ========= STYLE kecil ========= --}}
<style>


  /* rapikan pemenggalan baris heading & paragraf */
  .text-balance { text-wrap: balance; }
  .text-pretty  { text-wrap: pretty; }

  /* Safari/iOS kadang butuh sentuhan line-height heading di layar kecil */
  @media (max-width: 480px) {
    h1, h2, h3 { line-height: 1.2; }
  }
  @media (max-width: 640px) {
  #g3Wrap button { margin-left: auto; margin-right: auto; }
  #gallery3 img, #gallery3 video { max-width: 100%; height: auto; }
  }
</style>


{{-- ========= SCRIPT: SLIDER HERO ========= --}}
<script>
  (function () {
    const root   = document.getElementById('heroRoot');
    const imgEl  = document.getElementById('heroSlide');
    const dots   = Array.from(document.querySelectorAll('.hero-dot'));
    const slides = JSON.parse(root?.dataset.slides || '[]');

    let idx = 0, timer = null;

    function setDots(i){
      dots.forEach((d,k)=>{
        d.classList.toggle('ring-2', k===i);
        d.classList.toggle('bg-white', k===i);
        d.classList.toggle('bg-white/50', k!==i);
      });
    }
    function startAuto(){
      if (timer) clearInterval(timer);
      timer = setInterval(()=> show(idx + 1), 6000);
    }
    function show(i){
      if (!slides.length) return;
      idx = (i + slides.length) % slides.length;
      if (imgEl) imgEl.src = slides[idx];
      setDots(idx);
      startAuto();
    }

    // Panah
    const nextBtn = document.getElementById('nextHero');
    const prevBtn = document.getElementById('prevHero');
    nextBtn && nextBtn.addEventListener('click', ()=> show(idx + 1));
    prevBtn && prevBtn.addEventListener('click', ()=> show(idx - 1));

    // === Drag/Swipe (mouse & touch) ===
    let isDown = false, startX = 0, moved = false;
    const THRESH = 50; // piksel ambang swipe
    function getX(e){
      return (e.touches && e.touches[0] ? e.touches[0].clientX : e.clientX);
    }
    function onDown(e){
      isDown = true; moved = false; startX = getX(e);
      root.classList.add('cursor-grabbing');
      if (timer) clearInterval(timer);
    }
    function onMove(e){
      if (!isDown) return;
      const dx = getX(e) - startX;
      if (Math.abs(dx) > 5) moved = true;
      // efek “tarik” halus saat geser
      if (imgEl) { imgEl.style.transform = `translateX(${dx * 0.15}px)`; }
    }
    function onUp(e){
      if (!isDown) return;
      const dx = getX(e) - startX;
      isDown = false;
      root.classList.remove('cursor-grabbing');
      if (imgEl) { imgEl.style.transform = ''; } // reset posisi

      if (Math.abs(dx) > THRESH) {
        if (dx < 0) show(idx + 1); else show(idx - 1);
      } else {
        startAuto();
      }
    }

    // Mouse
    root.addEventListener('mousedown', onDown);
    window.addEventListener('mousemove', onMove);
    window.addEventListener('mouseup', onUp);
    // Touch
    root.addEventListener('touchstart', onDown, {passive:true});
    root.addEventListener('touchmove',  onMove, {passive:true});
    root.addEventListener('touchend',   onUp);

    show(0);
  })();
  </script>

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
