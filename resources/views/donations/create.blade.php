@extends('layouts.app')

@section('title','Donasi')

@section('content')
<div class="max-w-4xl mx-auto">
  {{-- Header kecil --}}
  <div class="text-center mb-4">

  </div>

  <div class="grid md:grid-cols-2 gap-6">
    {{-- ============ PANEL REKENING ============ --}}
    <aside class="bg-white rounded-2xl shadow ring-1 ring-slate-200 p-5 md:p-6">
      <div class="flex items-center gap-2">
        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-100 text-slate-600 ring-1 ring-slate-200">
          {{-- ikon bank --}}
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
            <path d="M3 10l9-6 9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M4 10h16v9H4zM2 19h20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          </svg>
        </span>
        <h2 class="text-lg md:text-xl font-extrabold text-slate-900">Transfer Bank</h2>
      </div>

      <div class="mt-4 grid grid-cols-1 gap-3">
        <div>
          <p class="text-xs uppercase tracking-wide text-slate-500">Bank</p>
          <div class="mt-1 inline-flex items-center gap-2">
            <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-yellow-50 text-yellow-700 ring-1 ring-yellow-200 text-xs font-semibold">
              BNI
            </span>
            <span class="text-slate-700">Bank Negara Indonesia</span>
          </div>
        </div>

        <div>
          <p class="text-xs uppercase tracking-wide text-slate-500">Atas Nama</p>
          <p class="mt-1 font-semibold text-slate-900">YAYASAN PEMERHATI LINGKUNGAN</p>
        </div>

        <div>
          <p class="text-xs uppercase tracking-wide text-slate-500">No. Rekening</p>
          <div class="mt-1 flex flex-wrap items-center gap-2">
            <span id="accNo" class="font-mono text-lg tracking-wider text-slate-900 select-all">
              0087161592
            </span>
            <button type="button"
                    class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg text-sm bg-blue-600 text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/50"
                    data-copy="72732392183" aria-label="Salin nomor rekening">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <rect x="9" y="9" width="11" height="11" rx="2" stroke="currentColor" stroke-width="2"/>
                <rect x="4" y="4" width="11" height="11" rx="2" stroke="currentColor" stroke-width="2"/>
              </svg>
              Salin
            </button>
          </div>
        </div>
      </div>

      <div class="mt-5 rounded-xl bg-slate-50 ring-1 ring-slate-200 p-3 border-l-4 border-blue-300">
        <p class="text-sm text-slate-900 font-semibold">Instruksi</p>
        <ol class="mt-1 text-sm text-slate-700 list-decimal list-inside space-y-1">
          <li>Transfer ke rekening BCA di atas.</li>
          <li>Simpan bukti transfer.</li>
          <li>Upload bukti pada formulir di sebelah.</li>
        </ol>
      </div>
    </aside>

    {{-- ============ FORM DONASI ============ --}}
    <section class="bg-white rounded-2xl shadow p-6 md:p-8">
      <h1 class="text-2xl md:text-3xl font-extrabold mb-4 text-slate-900">Form Donasi</h1>

      @if(session('success'))
        <div class="mb-4 p-3 rounded bg-emerald-100 text-emerald-800">{{ session('success') }}</div>
      @endif

      <form action="{{ route('donations.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        <div>
          <label class="block text-sm font-medium mb-1 text-slate-700">Nama</label>
          <input type="text" name="name" class="w-full rounded-lg border-slate-300 focus:border-sky-400 focus:ring-sky-400" value="{{ old('name') }}" required>
          @error('name') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium mb-1 text-slate-700">Email</label>
          <input type="email" name="email" class="w-full rounded-lg border-slate-300 focus:border-sky-400 focus:ring-sky-400" value="{{ old('email') }}" required>
          @error('email') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium mb-1 text-slate-700">Pesan (opsional)</label>
          <textarea name="message" rows="4" class="w-full rounded-lg border-slate-300 focus:border-sky-400 focus:ring-sky-400">{{ old('message') }}</textarea>
          @error('message') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium mb-1 text-slate-700">Bukti Transfer (jpg, png, webp, pdf, maks 5MB)</label>
          <input type="file" name="proof" accept=".jpg,.jpeg,.png,.webp,.pdf" class="w-full rounded-lg border-slate-300 focus:border-sky-400 focus:ring-sky-400" required>
          @error('proof') <div class="text-red-600 text-sm mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="pt-2">
          <button class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
            Kirim Donasi
          </button>
        </div>
      </form>
    </section>
  </div>

  {{-- toast copy --}}
  <div id="copyToast" class="fixed inset-x-0 bottom-6 mx-auto w-fit px-3 py-2 rounded-lg bg-slate-900 text-white text-sm shadow-lg opacity-0 pointer-events-none transition-opacity duration-200"
       aria-live="polite">
    Nomor rekening tersalin
  </div>
</div>

{{-- Script salin nomor rekening --}}
<script>
  (function () {
    const btn = document.querySelector('[data-copy]');
    const toast = document.getElementById('copyToast');
    if (!btn) return;
    btn.addEventListener('click', async () => {
      const text = btn.getAttribute('data-copy') || '';
      try {
        await navigator.clipboard.writeText(text);
      } catch (_) {
        const sel = document.createElement('textarea');
        sel.value = text; document.body.appendChild(sel);
        sel.select(); document.execCommand('copy'); document.body.removeChild(sel);
      }
      toast.classList.remove('opacity-0');
      setTimeout(()=> toast.classList.add('opacity-0'), 1500);
    });
  })();
</script>
@endsection
