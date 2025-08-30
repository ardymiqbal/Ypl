{{-- FOOTER full-width dengan background gambar + overlay + garis dekor --}}
<footer class="relative mt-16 text-white w-full">
  {{-- Background full --}}
  <div class="absolute inset-0 -z-10">
    <img src="{{ asset('images/nav-footer-bg.png') }}" alt="" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-[#123e66]/80"></div>
  </div>

  {{-- Konten full width (tanpa max-w); pakai padding sisi biar rapi --}}
  <div class="w-full px-4 md:px-[60px] py-10">
  <div class="grid gap-8 md:grid-cols-3">
    {{-- Brand kiri --}}
    <div class="flex items-center gap-3 -mt-1 md:-mt-2">
      <img
        src="{{ asset('images/logo.png') }}"
        alt="Logo"
        class="h-[88px] w-[88px] md:h-[128px] md:w-[128px] rounded-full object-cover shrink-0"
      >
      <div class="leading-tight">
        <div class="font-extrabold tracking-wider uppercase">YAYASAN PEMERHATI LINGKUNGAN</div>
        <div class="italic font-semibold text-white/90">Bergerak Bersama Untuk Jaga Lingkungan</div>
      </div>
    </div>


    


      {{-- Alamat --}}
      <div>
        <div class="font-semibold mb-1">Alamat</div>
        <p class="text-white/90 text-sm leading-6">
          Jl. Raya Koyoan KM. 16 Desa Koyoan, Kec. Nambo<br>
          Kab. Banggai, Sulawesi Tengah / 94760
        </p>
      </div>

      {{-- Kontak + sosmed --}}
      <div>
        <div class="font-semibold mb-1">Kontak</div>
        <div class="space-y-1 text-sm">
          <div class="flex items-center gap-2">
            {{-- icon email --}}
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 6h16v12H4z"/>
              <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M22 6l-10 7L2 6"/>
            </svg>
            <p>
              yayasanpemerhatilingkungan99@gmail.com</p>
          </div>
          <div class="flex items-center gap-2">
            {{-- icon phone --}}
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h2l2 4-2 1c.6 1.8 2.2 3.4 4 4l1-2 4 2v2a2 2 0 01-2 2h-1C7.8 18 3 13.2 3 7V5z"/>
            </svg>
            <p>082398814571</p>
          </div>
        </div>

        <div class="flex items-center gap-3 mt-3">
          {{-- Ikon sosmed (link contoh) --}}
          <a href="https://www.facebook.com/profile.php?id=61579997130447" class="p-2 rounded bg-white/10 hover:bg-white/20" aria-label="Facebook">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M13 22v-8h3l1-4h-4V7a1 1 0 011-1h3V2h-3a5 5 0 00-5 5v3H6v4h3v8h4z"/>
            </svg>
          </a>
          <a href="https://www.instagram.com/yayasan.pemerhati_lingkungan?igsh=eGU3ZTl5NHBjemt3" class="p-2 rounded bg-white/10 hover:bg-white/20" aria-label="Instagram">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M7 2h10a5 5 0 015 5v10a5 5 0 01-5 5H7a5 5 0 01-5-5V7a5 5 0 015-5zm0 2a3 3 0 00-3 3v10a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H7zm5 3a5 5 0 110 10 5 5 0 010-10zm6-1a1 1 0 110 2 1 1 0 010-2z"/>
            </svg>
          </a>
          <a href="https://www.tiktok.com/@ypl4437?_t=ZS-8zHg9k7D4xb&_r=1" class="p-2 rounded bg-white/10 hover:bg-white/20" aria-label="TikTok">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M20 8.5a6.5 6.5 0 01-4-1.4v7.1a5.2 5.2 0 11-5.2-5.2c.2 0 .4 0 .6.1v2.6a2.6 2.6 0 102.6 2.6V2h3.1A6.5 6.5 0 0020 5.9v2.6z"/>
            </svg>
          </a>
          <a href="https://youtube.com/@yayasanpemerhatilingkungan?si=scjNAV00in1VLcei" class="p-2 rounded bg-white/10 hover:bg-white/20" aria-label="YouTube">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
              <path d="M23 12c0-2.3-.3-3.9-.7-4.9-.4-1-1.1-1.7-2.1-2.1C18.2 4.5 12 4.5 12 4.5s-6.2 0-8.2.5c-1 .4-1.7 1.1-2.1 2.1C1.3 8.1 1 9.7 1 12s.3 3.9.7 4.9c.4 1 1.1 1.7 2.1 2.1 2 .5 8.2.5 8.2.5s6.2 0 8.2-.5c1-.4 1.7-1.1 2.1-2.1.4-1 .7-2.6.7-4.9zM10 15.5v-7l6 3.5-6 3.5z"/>
            </svg>
          </a>
        </div>
      </div>
    </div>

    {{-- Garis dekor dengan titik di ujung --}}
    <div class="relative mt-6">
      <div class="h-0.5 bg-white/60 rounded"></div>
      <span class="absolute -left-1 -top-1.5 w-3 h-3 rounded-full bg-white shadow-[0_0_10px_2px_rgba(255,255,255,0.6)]"></span>
      <span class="absolute -right-1 -top-1.5 w-3 h-3 rounded-full bg-white shadow-[0_0_10px_2px_rgba(255,255,255,0.6)]"></span>
    </div>

    <div class="text-xs text-white/80 mt-4">
      © {{ date('Y') }} Yayasan Pemerhati Lingkungan
    </div>
  </div>
</footer>
