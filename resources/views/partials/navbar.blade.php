{{-- resources/views/partials/navbar.blade.php --}}
{{-- NAVBAR dengan background gambar + overlay --}}
<nav class="relative text-white z-[60]">
  {{-- Background image + overlay gradasi --}}
  <div class="absolute inset-0 -z-10">
    <img src="{{ asset('images/nav-footer-bg.png') }}" alt="" class="w-full h-full object-cover">
    <div class="absolute inset-0 bg-gradient-to-r from-[#123e66]/90 via-[#123e66]/75 to-[#123e66]/60"></div>
  </div>

  <div class="w-full max-w-none px-4 md:px-6">
    <div class="flex items-center justify-between py-3 md:py-4 min-h-[96px]">
      {{-- Brand --}}
      <a href="{{ route('home') }}" class="flex items-center gap-4">
        <div class="shrink-0 h-[72px] w-[72px] md:h-[96px] md:w-[96px] rounded-full overflow-hidden">
          <img src="{{ asset('images/logo.png') }}" alt="Logo" class="h-full w-full object-cover transform scale-[1.25]">
        </div>
        <div class="leading-tight">
          <div class="font-extrabold tracking-wider uppercase text-sm md:text-base">
            YAYASAN PEMERHATI LINGKUNGAN
          </div>
          <div class="italic font-semibold text-[11px] md:text-sm text-white/90">
            Bergerak Bersama Untuk Jaga Lingkungan
          </div>
        </div>
      </a>

      {{-- Menu desktop --}}
      <ul class="hidden md:flex items-center gap-6 font-semibold">
        {{-- Dashboard hanya untuk admin --}}
        @auth('admin')
          <li>
            <a href="{{ route('dashboard') }}"
               class="inline-flex items-center h-10 px-2 hover:opacity-90">
              Dashboard
            </a>
          </li>
        @endauth

        <li>
          <a href="{{ route('home') }}"
             class="inline-flex items-center h-10 px-2 hover:opacity-90">
            Home
          </a>
        </li>

        {{-- Fokus Kerja (dropdown) --}}
        <li class="relative group
                   before:content-[''] before:absolute before:top-full before:left-0 before:w-full before:h-2">
          <button type="button"
                  class="inline-flex items-center h-10 px-2 gap-1 hover:opacity-90 focus:outline-none">
            Fokus Kerja
            <svg class="w-4 h-4 translate-y-[1px]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </button>

          <div class="absolute right-0 top-full w-80 rounded-xl shadow-lg ring-1 ring-black/10
                      bg-white text-gray-800 p-2 z-50
                      opacity-0 translate-y-1 scale-95 pointer-events-none
                      transition-all duration-150 ease-out
                      group-hover:opacity-100 group-hover:translate-y-0 group-hover:scale-100 group-hover:pointer-events-auto
                      group-focus-within:opacity-100 group-focus-within:translate-y-0 group-focus-within:scale-100 group-focus-within:pointer-events-auto">
            <a href="{{ route('fokus-kerja.sampah') }}"
               class="block px-3 py-2 rounded-md hover:bg-[#123e66] hover:text-white focus:bg-[#123e66] focus:text-white">
               Pengelolaan Sampah dan Daur Ulang
            </a>
            <a href="{{ route('fokus-kerja.edukasi') }}"
               class="block px-3 py-2 rounded-md hover:bg-[#123e66] hover:text-white focus:bg-[#123e66] focus:text-white">
               Edukasi dan Kampanye Lingkungan
            </a>
            <a href="{{ route('fokus-kerja.pemberdayaan') }}"
               class="block px-3 py-2 rounded-md hover:bg-[#123e66] hover:text-white focus:bg-[#123e66] focus:text-white">
               Pemberdayaan Masyarakat Lokal
            </a>
            <a href="{{ route('fokus-kerja.monitoring') }}"
               class="block px-3 py-2 rounded-md hover:bg-[#123e66] hover:text-white focus:bg-[#123e66] focus:text-white">
               Monitoring dan Pengawasan Laut
            </a>
            <a href="{{ route('fokus-kerja.kolaborasi') }}"
               class="block px-3 py-2 rounded-md hover:bg-[#123e66] hover:text-white focus:bg-[#123e66] focus:text-white">
               Kolaborasi dan Jejaringan
            </a>
          </div>
        </li>

        {{-- Mari Beraksi (dropdown) --}}
        <li class="relative group
                   before:content-[''] before:absolute before:top-full before:left-0 before:w-full before:h-2">
          <button type="button"
                  class="inline-flex items-center h-10 px-2 gap-1 hover:opacity-90 focus:outline-none">
            Mari Beraksi
            <svg class="w-4 h-4 translate-y-[1px]" viewBox="0 0 24 24" fill="none" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
          </button>

          <div class="absolute right-0 top-full w-64 rounded-xl shadow-lg ring-1 ring-black/10
                      bg-white text-gray-800 p-2 z-50
                      opacity-0 translate-y-1 scale-95 pointer-events-none
                      transition-all duration-150 ease-out
                      group-hover:opacity-100 group-hover:translate-y-0 group-hover:scale-100 group-hover:pointer-events-auto
                      group-focus-within:opacity-100 group-focus-within:translate-y-0 group-focus-within:scale-100 group-focus-within:pointer-events-auto">
            <a href="{{ route('actions.relawan') }}"
               class="block px-3 py-2 rounded-md hover:bg-[#123e66] hover:text-white focus:bg-[#123e66] focus:text-white">
               Bergabung Relawan
            </a>
            <a href="{{ route('actions.magang') }}"
               class="block px-3 py-2 rounded-md hover:bg-[#123e66] hover:text-white focus:bg-[#123e66] focus:text-white">
               Magang
            </a>
          </div>
        </li>

        <li>
          <a href="{{ route('articles.public.index') }}"
             class="inline-flex items-center h-10 px-2 hover:opacity-90">
            Kabar Terbaru
          </a>
        </li>

        <li>
          <a href="{{ route('donations.create') }}"
             class="inline-flex items-center h-10 px-3 py-1.5 rounded bg-white/15 hover:bg-white/25 transition">
            Donasi
          </a>
        </li>

        {{-- Auth area (Logout) --}}
        @auth('admin')
          <li>
            <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
              @csrf
              <button type="submit"
                class="inline-flex items-center h-10 px-2 gap-1 focus:outline-none
                       transition-colors hover:text-red-600 focus:text-red-600"
                title="Keluar">
                Keluar
              </button>
            </form>
          </li>
        @endauth
      </ul>

      {{-- Toggle mobile --}}
      <button id="navToggle" class="md:hidden inline-flex items-center justify-center w-9 h-9 rounded bg-white/10 hover:bg-white/20">
        <span class="sr-only">Buka menu</span>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
        </svg>
      </button>
    </div>

    {{-- Menu mobile --}}
    <div id="mobileNav" class="hidden md:hidden border-t border-white/10">
      <ul class="py-3 space-y-1">
        {{-- Dashboard hanya untuk admin --}}
        @auth('admin')
          <li>
            <a href="{{ route('dashboard') }}" class="block px-2 py-2 hover:bg-white/10 rounded">
              Dashboard
            </a>
          </li>
        @endauth

        <li><a href="{{ route('home') }}" class="block px-2 py-2 hover:bg-white/10 rounded">Home</a></li>

        <li class="px-2 pt-2 text-white/80 text-xs uppercase">Fokus Kerja</li>
        <li><a href="{{ route('fokus-kerja.sampah') }}" class="block px-4 py-2 hover:bg-white/10 rounded">Pengelolaan Sampah & Daur Ulang</a></li>
        <li><a href="{{ route('fokus-kerja.edukasi') }}" class="block px-4 py-2 hover:bg-white/10 rounded">Edukasi & Kampanye Lingkungan</a></li>
        <li><a href="{{ route('fokus-kerja.pemberdayaan') }}" class="block px-4 py-2 hover:bg-white/10 rounded">Pemberdayaan Masyarakat Lokal</a></li>
        <li><a href="{{ route('fokus-kerja.monitoring') }}" class="block px-4 py-2 hover:bg-white/10 rounded">Monitoring & Pengawasan Laut</a></li>
        <li><a href="{{ route('fokus-kerja.kolaborasi') }}" class="block px-4 py-2 hover:bg-white/10 rounded">Kolaborasi & Jejaringan</a></li>

        <li class="px-2 pt-3 text-white/80 text-xs uppercase">Mari Beraksi</li>
        <li><a href="{{ route('actions.relawan') }}" class="block px-4 py-2 hover:bg-white/10 rounded">Bergabung Relawan</a></li>
        <li><a href="{{ route('actions.magang') }}" class="block px-4 py-2 hover:bg-white/10 rounded">Magang</a></li>

        <li><a href="{{ route('articles.public.index') }}" class="block px-2 py-2 hover:bg-white/10 rounded">Kabar Terbaru</a></li>
        <li><a href="{{ route('donations.create') }}" class="block px-2 py-2 hover:bg-white/10 rounded">Donasi</a></li>

        {{-- Auth area (Login/Logout) --}}
        @auth('admin')
          <li>
            <form action="{{ route('admin.logout') }}" method="POST" class="m-0 px-2">
              @csrf
              <button type="submit" class="w-full text-left block px-0 py-2 hover:bg-white/10 rounded">
                Keluar
              </button>
            </form>
          </li>
        @endauth
      </ul>
    </div>
  </div>
</nav>

{{-- Script sederhana toggle mobile menu --}}
<script>
  (function(){
    const t = document.getElementById('navToggle');
    const m = document.getElementById('mobileNav');
    if (!t || !m) return;
    t.addEventListener('click', () => m.classList.toggle('hidden'));
  })();
</script>
