@extends('layouts.app')

@section('title','Kelola Artikel')

@section('content')
{{-- Header --}}
<div class="flex items-center justify-between mb-4">
  <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900">Artikel</h1>
  <a href="{{ route('articles.create') }}"
     class="inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500/50">
    <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true"><path d="M12 5v14M5 12h14" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
    <span class="hidden sm:inline">Artikel Baru</span>
    <span class="sm:hidden">Tambah</span>
  </a>
</div>

{{-- ======= MOBILE: CARD LIST (md:hidden) ======= --}}
<div class="md:hidden space-y-3">
  @forelse($articles as $a)
    @php $published = $a->status === 'published'; @endphp
    <div class="rounded-xl bg-white ring-1 ring-slate-200 shadow-sm p-4">
      <div class="flex items-start justify-between gap-3">
        <div class="min-w-0">
          <div class="font-semibold text-slate-900 line-clamp-2">{{ $a->title }}</div>
          <div class="text-xs text-slate-500 break-all">{{ $a->slug }}</div>
        </div>
        <span class="shrink-0 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs
                     {{ $published ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : 'bg-amber-50 text-amber-700 ring-1 ring-amber-200' }}">
          <span class="w-1.5 h-1.5 rounded-full {{ $published ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
          {{ ucfirst($a->status) }}
        </span>
      </div>

      <div class="mt-3 flex items-center justify-between text-sm text-slate-600">
        <div class="truncate">{{ $a->author }}</div>
        <div class="shrink-0 text-slate-500">{{ $a->created_at->format('d M Y') }}</div>
      </div>

      <div class="mt-3 flex items-center gap-2">
        <a href="{{ route('articles.edit',$a) }}"
           class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-sky-600 text-white hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500/50">
          <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M4 21h16M12 4l8 8-8-8Zm0 0L5 11m7-7l7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
          Edit
        </a>
        <form method="POST" action="{{ route('articles.destroy',$a) }}" class="flex-1"
              onsubmit="return confirm('Hapus artikel ini?')">
          @csrf @method('DELETE')
          <button
            class="w-full inline-flex items-center justify-center gap-2 px-3 py-2 rounded-lg bg-rose-600 text-white hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-rose-500/50">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M6 7h12M10 11v6M14 11v6M9 7l1-2h4l1 2M6 7l1 12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2L18 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Hapus
          </button>
        </form>
      </div>
    </div>
  @empty
    <div class="rounded-xl bg-white ring-1 ring-slate-200 p-4 text-center text-slate-500">Belum ada artikel.</div>
  @endforelse
</div>

{{-- ======= DESKTOP: TABLE (hidden md:block) ======= --}}
<div class="hidden md:block bg-white rounded-xl shadow overflow-hidden">
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-slate-50 text-slate-700">
        <tr class="border-b border-slate-200">
          <th class="p-3 text-left">Judul</th>
          <th class="p-3">Status</th>
          <th class="p-3">Penulis</th>
          <th class="p-3">Tanggal</th>
          <th class="p-3">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        @forelse($articles as $a)
          @php $published = $a->status === 'published'; @endphp
          <tr>
            <td class="p-3">
              <div class="font-medium text-slate-900">{{ $a->title }}</div>
              <div class="text-xs text-slate-500">{{ $a->slug }}</div>
            </td>
            <td class="p-3 text-center">
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs
                           {{ $published ? 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200' : 'bg-amber-50 text-amber-700 ring-1 ring-amber-200' }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $published ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                {{ ucfirst($a->status) }}
              </span>
            </td>
            <td class="p-3 text-center text-slate-700">{{ $a->author }}</td>
            <td class="p-3 text-center text-slate-700">{{ $a->created_at->format('d M Y') }}</td>
            <td class="p-3">
              <div class="flex gap-2 justify-end">
                <a href="{{ route('articles.edit',$a) }}"
                   class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-sky-600 text-white hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500/50">
                  <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M4 21h16M12 4l8 8-8-8Zm0 0L5 11m7-7l7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                  Edit
                </a>
                <form method="POST" action="{{ route('articles.destroy',$a) }}" onsubmit="return confirm('Hapus artikel ini?')">
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
          <tr><td colspan="5" class="p-4 text-center text-slate-500">Belum ada artikel.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- Pagination --}}
<div class="mt-4">
  <div class="bg-white/60 rounded-xl p-2 flex justify-center md:justify-end">
    {{ $articles->links() }}
  </div>
</div>

{{-- Small helpers --}}
<style>
  .text-balance { text-wrap: balance; }
  .text-pretty  { text-wrap: pretty; }
</style>
@endsection
