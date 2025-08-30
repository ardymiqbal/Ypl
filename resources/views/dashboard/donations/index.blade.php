@extends('layouts.app')

@section('title','Donasi — Dashboard')

@section('content')
@php use Illuminate\Support\Str; @endphp

<h1 class="text-xl sm:text-2xl md:text-3xl font-extrabold mb-4 text-slate-900">Donasi</h1>

{{-- Filter --}}
<form method="GET" class="mb-4 grid grid-cols-1 sm:grid-cols-3 gap-3">
  <input type="text" name="q" value="{{ $q }}" placeholder="Cari nama / email / pesan…"
         class="rounded-lg border-slate-300 focus:border-sky-400 focus:ring-sky-400" />
  <select name="status" class="rounded-lg border-slate-300 focus:border-sky-400 focus:ring-sky-400">
    <option value="">Semua Status</option>
    @foreach(['pending'=>'Pending','verified'=>'Verified','rejected'=>'Rejected'] as $k=>$v)
      <option value="{{ $k }}" @selected($status===$k)>{{ $v }}</option>
    @endforeach
  </select>
  <div class="flex gap-2">
    <button class="flex-1 rounded-lg bg-sky-600 text-white px-3 py-2 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500/50">
      Filter
    </button>
    @if(request()->hasAny(['q','status']) && (filled($q) || filled($status)))
      <a href="{{ route('donations.index') }}"
         class="hidden sm:inline-flex items-center justify-center rounded-lg px-3 py-2 ring-1 ring-slate-300 text-slate-700 hover:bg-slate-50">
        Reset
      </a>
    @endif
  </div>
</form>

{{-- ======= MOBILE: CARD LIST (md:hidden) ======= --}}
<div class="md:hidden space-y-3">
  @forelse($donations as $d)
    @php
      $isImg = Str::endsWith(strtolower($d->proof_path ?? ''), ['.jpg','.jpeg','.png','.webp']);
      $badge = [
        'pending'  => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
        'verified' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200',
        'rejected' => 'bg-rose-50 text-rose-700 ring-1 ring-rose-200',
      ][$d->status] ?? 'bg-slate-100 text-slate-700 ring-1 ring-slate-200';
      $dot = [
        'pending'  => 'bg-amber-500',
        'verified' => 'bg-emerald-500',
        'rejected' => 'bg-rose-500',
      ][$d->status] ?? 'bg-slate-400';
    @endphp

    <article class="rounded-xl bg-white ring-1 ring-slate-200 shadow-sm overflow-hidden">
      {{-- Bukti / preview --}}
      <div class="bg-slate-50">
        @if($isImg)
          <img src="{{ asset('storage/'.$d->proof_path) }}" alt="Bukti transfer" class="w-full aspect-[16/10] object-cover">
        @else
          <div class="w-full aspect-[16/10] grid place-items-center text-slate-500 text-sm">
            <a href="{{ asset('storage/'.$d->proof_path) }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg ring-1 ring-slate-300 hover:bg-slate-100">
              Lihat File
            </a>
          </div>
        @endif
      </div>

      <div class="p-4">
        <div class="flex items-start justify-between gap-2">
          <div class="min-w-0">
            <div class="font-semibold text-slate-900">{{ $d->name }}</div>
            <div class="text-xs text-slate-500 break-all">{{ $d->email }}</div>
          </div>
          <span class="shrink-0 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] {{ $badge }}">
            <span class="w-1.5 h-1.5 rounded-full {{ $dot }}"></span>
            {{ ucfirst($d->status) }}
          </span>
        </div>

        <div class="mt-2 text-xs text-slate-500">
          {{ $d->created_at->format('d M Y H:i') }}
        </div>

        {{-- Aksi --}}
        <div class="mt-3 grid grid-cols-3 gap-2">
          <a href="{{ route('donations.show',$d) }}"
             class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg bg-white ring-1 ring-slate-300 text-slate-700 hover:bg-slate-50">
            Detail
          </a>
          <a href="{{ route('donations.edit',$d) }}"
             class="inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg bg-sky-600 text-white hover:bg-sky-700">
            Edit
          </a>
          <form action="{{ route('donations.destroy',$d) }}" method="POST"
                onsubmit="return confirm('Hapus donasi ini?')">
            @csrf @method('DELETE')
            <button
              class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg bg-rose-600 text-white hover:bg-rose-700">
              Hapus
            </button>
          </form>
        </div>
      </div>
    </article>
  @empty
    <div class="rounded-xl bg-white ring-1 ring-slate-200 p-4 text-center text-slate-500">Belum ada data.</div>
  @endforelse
</div>

{{-- ======= DESKTOP: TABLE (hidden md:block) ======= --}}
<div class="hidden md:block bg-white rounded-xl shadow ring-1 ring-black/5 overflow-hidden">
  <div class="overflow-x-auto">
    <table class="min-w-full text-sm">
      <thead class="bg-slate-50">
        <tr class="text-left text-slate-700 border-b border-slate-200">
          <th class="px-4 py-3">Nama</th>
          <th class="px-4 py-3">Email</th>
          <th class="px-4 py-3">Status</th>
          <th class="px-4 py-3">Bukti</th>
          <th class="px-4 py-3">Tanggal</th>
          <th class="px-4 py-3 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-slate-100">
        @forelse($donations as $d)
          @php
            $colors = [
              'pending'  => ['wrap'=>'bg-amber-50 text-amber-700 ring-1 ring-amber-200','dot'=>'bg-amber-500'],
              'verified' => ['wrap'=>'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200','dot'=>'bg-emerald-500'],
              'rejected' => ['wrap'=>'bg-rose-50 text-rose-700 ring-1 ring-rose-200','dot'=>'bg-rose-500'],
            ][$d->status] ?? ['wrap'=>'bg-slate-100 text-slate-700 ring-1 ring-slate-200','dot'=>'bg-slate-400'];
          @endphp
          <tr>
            <td class="px-4 py-3 font-medium text-slate-900">{{ $d->name }}</td>
            <td class="px-4 py-3 text-slate-700">{{ $d->email }}</td>
            <td class="px-4 py-3">
              <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] {{ $colors['wrap'] }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $colors['dot'] }}"></span>
                {{ ucfirst($d->status) }}
              </span>
            </td>
            <td class="px-4 py-3">
              @if(Str::endsWith(strtolower($d->proof_path ?? ''), ['.jpg','.jpeg','.png','.webp']))
                <img src="{{ asset('storage/'.$d->proof_path) }}" class="h-10 w-16 object-cover rounded ring-1 ring-black/5" alt="">
              @else
                <a href="{{ asset('storage/'.$d->proof_path) }}" class="text-sky-600 hover:underline" target="_blank">Lihat File</a>
              @endif
            </td>
            <td class="px-4 py-3 text-slate-600">{{ $d->created_at->format('d M Y H:i') }}</td>
            <td class="px-4 py-3 text-right space-x-2">
              <a href="{{ route('donations.show',$d) }}" class="px-2 py-1.5 rounded-lg bg-white ring-1 ring-slate-300 hover:bg-slate-50">Detail</a>
              <a href="{{ route('donations.edit',$d) }}" class="px-2 py-1.5 rounded-lg bg-sky-600 text-white hover:bg-sky-700">Edit</a>
              <form action="{{ route('donations.destroy',$d) }}" method="POST" class="inline"
                    onsubmit="return confirm('Hapus donasi ini?')">
                @csrf @method('DELETE')
                <button class="px-2 py-1.5 rounded-lg bg-rose-600 text-white hover:bg-rose-700">Hapus</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="px-4 py-6 text-center text-slate-500">Belum ada data.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="px-4 py-3 border-t">
    {{ $donations->links() }}
  </div>
</div>

{{-- Helpers --}}
<style>
  .text-balance { text-wrap: balance; }
  .text-pretty  { text-wrap: pretty; }
</style>
@endsection
