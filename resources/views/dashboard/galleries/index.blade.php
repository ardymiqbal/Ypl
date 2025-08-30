@extends('layouts.app')

@section('title','Kelola Galeri')

@section('content')
@php use Illuminate\Support\Str; @endphp

{{-- Header --}}
<div class="flex items-center justify-between mb-2">
  <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900">Galeri</h1>
  <a href="{{ route('galleries.create') }}"
     class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/50">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
    <span class="hidden sm:inline">Item Baru</span>
    <span class="sm:hidden">Tambah</span>
  </a>
</div>

{{-- NOTE: tampil di bawah header --}}
<div class="mb-4 rounded-lg border border-amber-200 bg-amber-50 text-amber-800 p-3 text-sm flex items-start gap-2">
  <svg class="w-4 h-4 mt-0.5 shrink-0" viewBox="0 0 24 24" fill="none" aria-hidden="true">
    <path d="M12 9v6m0 4a9 9 0 1 0 0-18 9 9 0 0 0 0 18Zm0-10h.01" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
  <p>Catatan: Hanya <span class="font-semibold">12 gambar</span> dan <span class="font-semibold">1 video</span> terbaru yang ditampilkan di halaman home.</p>
</div>

{{-- ======= MOBILE: CARD LIST (md:hidden) ======= --}}
<div class="md:hidden space-y-3">
  @forelse($galleries as $g)
    @php
      $src = Str::startsWith($g->media_path, ['http://','https://'])
              ? $g->media_path
              : asset('storage/'.$g->media_path);
      $published = (bool) $g->is_published;
      $isImage   = $g->media_type === 'image';
    @endphp

    <article class="rounded-xl bg-white ring-1 ring-slate-200 shadow-sm overflow-hidden">
      {{-- Preview --}}
      <div class="bg-slate-50">
        @if($isImage)
          <img src="{{ $src }}" alt="{{ e($g->title) }}" class="w-full aspect-[16/10] object-cover">
        @elseif($g->media_type === 'video')
          <video class="w-full aspect-[16/10] object-cover" preload="metadata" muted playsinline>
            <source src="{{ $src }}#t=0.1" type="video/mp4">
          </video>
        @else
          <div class="w-full aspect-[16/10] grid place-items-center text-slate-400 text-sm">Tidak ada preview</div>
        @endif
      </div>

      {{-- Meta --}}
      <div class="p-4">
        <div class="font-semibold text-slate-900 line-clamp-2">{{ $g->title }}</div>
        <div class="text-xs text-slate-500 break-all">{{ $g->slug }}</div>

        <div class="mt-2 flex flex-wrap items-center gap-2">
          {{-- Type chip --}}
          <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px]
                       {{ $isImage ? 'bg-sky-50 text-sky-700 ring-1 ring-sky-200' : 'bg-violet-50 text-violet-700 ring-1 ring-violet-200' }}">
            <span class="w-1.5 h-1.5 rounded-full {{ $isImage ? 'bg-sky-500' : 'bg-violet-500' }}"></span>
            {{ ucfirst($g->media_type) }}
          </span>

          {{-- Status chip --}}
          <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px]
                       {{ $published ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : 'bg-amber-50 text-amber-700 ring-1 ring-amber-200' }}">
            <span class="w-1.5 h-1.5 rounded-full {{ $published ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
            {{ $published ? 'Published' : 'Draft' }}
          </span>

          <span class="text-xs text-slate-500 ml-auto">{{ $g->created_at->format('d M Y') }}</span>
        </div>

        {{-- Actions --}}
        <div class="mt-3 flex items-center gap-2">
          <a href="{{ route('galleries.edit',$g) }}"
             class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-sky-600 text-white hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500/50">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M4 21h16M12 4l8 8-8-8Zm0 0L5 11m7-7l7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Edit
          </a>
          <form method="POST" action="{{ route('galleries.destroy',$g) }}" class="flex-1"
                onsubmit="return confirm('Hapus item ini?')">
            @csrf @method('DELETE')
            <button
              class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-rose-600 text-white hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500/50">
              <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M6 7h12M10 11v6M14 11v6M9 7l1-2h4l1 2M6 7l1 12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2L18 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
              Hapus
            </button>
          </form>
        </div>
      </div>
    </article>
  @empty
    <div class="rounded-xl bg-white ring-1 ring-slate-200 p-4 text-center text-slate-500">Belum ada item.</div>
  @endforelse
</div>

{{-- ======= DESKTOP: TABLE (hidden md:block) ======= --}}
<div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-slate-50 text-slate-700">
        <tr class="border-b border-slate-200">
          <th class="p-3 text-left">Judul</th>
          <th class="p-3 text-center">Tipe</th>
          <th class="p-3 text-left">File</th>
          <th class="p-3 text-center">Publikasi</th>
          <th class="p-3 text-center">Tanggal</th>
          <th class="p-3 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        @forelse($galleries as $g)
          @php
            $src = Str::startsWith($g->media_path, ['http://','https://'])
                    ? $g->media_path
                    : asset('storage/'.$g->media_path);
            $published = (bool) $g->is_published;
            $isImage   = $g->media_type === 'image';
          @endphp
          <tr>
            <td class="p-3 align-top">
              <div class="font-medium text-slate-900">{{ $g->title }}</div>
              <div class="text-xs text-slate-500">{{ $g->slug }}</div>
            </td>

            <td class="p-3 text-center align-top">
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px]
                           {{ $isImage ? 'bg-sky-50 text-sky-700 ring-1 ring-sky-200' : 'bg-violet-50 text-violet-700 ring-1 ring-violet-200' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $isImage ? 'bg-sky-500' : 'bg-violet-500' }}"></span>
                {{ ucfirst($g->media_type) }}
              </span>
            </td>

            <td class="p-3 align-top">
              @if($isImage)
                <img src="{{ $src }}" alt="{{ e($g->title) }}" class="w-28 h-16 object-cover rounded ring-1 ring-black/5">
              @elseif($g->media_type === 'video')
                <video class="w-28 h-16 object-cover rounded ring-1 ring-black/5" preload="metadata" muted playsinline>
                  <source src="{{ $src }}#t=0.1" type="video/mp4">
                </video>
              @else
                <span class="text-slate-400 text-xs">—</span>
              @endif
            </td>

            <td class="p-3 text-center align-top">
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px]
                           {{ $published ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : 'bg-amber-50 text-amber-700 ring-1 ring-amber-200' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $published ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                {{ $published ? 'Published' : 'Draft' }}
              </span>
            </td>

            <td class="p-3 text-center align-top text-slate-700">
              {{ $g->created_at->format('d M Y') }}
            </td>

            <td class="p-3 align-top">
              <div class="flex gap-2 justify-end">
                <a href="{{ route('galleries.edit',$g) }}"
                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-sky-600 text-white hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500/50">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M4 21h16M12 4l8 8-8-8Zm0 0L5 11m7-7l7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                  Edit
                </a>
                <form method="POST" action="{{ route('galleries.destroy',$g) }}" onsubmit="return confirm('Hapus item ini?')">
                  @csrf @method('DELETE')
                  <button
                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-rose-600 text-white hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500/50">
                    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M6 7h12M10 11v6M14 11v6M9 7l1-2h4l1 2M6 7l1 12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2L18 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    Hapus
                  </button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="p-4 text-center text-slate-500">Belum ada item.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Pagination --}}
<div class="mt-4">
  <div class="bg-white/60 rounded-xl p-2 flex justify-center md:justify-end">
    {{ $galleries->links() }}
  </div>
</div>

{{-- Helpers --}}
<style>
  .text-balance { text-wrap: balance; }
  .text-pretty  { text-wrap: pretty; }
</style>
@endsection
