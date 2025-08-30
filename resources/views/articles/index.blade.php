@extends('layouts.app')

{{-- Mobile nyaman: padding kecil di HP, lebar di desktop --}}
@section('container_class','w-full max-w-none px-4 md:px-[60px]')

@section('title','Kabar Terbaru')

@section('content')
@php use Illuminate\Support\Str; @endphp

<section class="mb-3 md:mb-6">
  <h1 class="font-extrabold text-slate-900 text-balance
             text-[clamp(20px,6vw,28px)] md:text-[clamp(24px,3vw,36px)]">
    Kabar Terbaru
  </h1>
</section>

{{-- Form pencarian tunggal (stacked di mobile) --}}
<section class="bg-white rounded-xl shadow ring-1 ring-black/5 p-4 md:p-5">
  <form method="GET" action="{{ route('articles.public.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
    <div class="sm:col-span-9">
      <label class="block text-xs uppercase tracking-wide text-slate-500">Cari judul / ringkasan / penulis / #hashtag</label>
      <input
        type="text" name="q" value="{{ e($q ?? '') }}"
        class="mt-1 w-full rounded-lg border-slate-300 focus:border-sky-400 focus:ring-sky-400 text-[15px] py-2.5"
        placeholder="misal: konservasi, edukasi, #kampanye lingkungan…">
    </div>
    <div class="sm:col-span-3 flex items-end">
      <button type="submit"
        class="w-full rounded-lg bg-sky-600 text-white px-4 py-2.5 hover:bg-sky-700 focus:outline-none focus:ring-2 focus:ring-sky-500/50">
        Cari
      </button>
    </div>
  </form>
</section>

{{-- Daftar artikel --}}
<section class="mt-5 md:mt-6">
  @if($articles->count())
    <div class="space-y-4 md:space-y-6">
      @foreach($articles as $a)
        @php
          // Pakai URL absolut jika sudah http/https; jika tidak, ambil via route streaming supaya aman di shared hosting
          $thumbUrl = $a->thumbnail
            ? (Str::startsWith($a->thumbnail, ['http://','https://'])
                ? $a->thumbnail
                : route('articles.thumb', $a))
            : null;
        @endphp

        {{-- Kartu artikel: vertikal di mobile, dua kolom di desktop --}}
        <article class="rounded-2xl bg-white ring-1 ring-slate-200 shadow-sm p-4 md:p-0 md:bg-transparent md:shadow-none md:ring-0 md:rounded-none md:border-b md:pb-6">
          <div class="grid gap-4 md:gap-5 md:grid-cols-12">
            {{-- Thumbnail --}}
            <a href="{{ route('articles.show',$a->slug) }}" class="block md:col-span-4">
              @if($thumbUrl)
                <img
                  class="w-full aspect-[16/10] md:aspect-[16/9] object-cover rounded-xl md:rounded-lg shadow md:shadow-none"
                  loading="lazy" decoding="async"
                  src="{{ $thumbUrl }}" alt="{{ e($a->title) }}">
              @else
                <div class="w-full aspect-[16/10] md:aspect-[16/9] rounded-xl md:rounded-lg bg-slate-100 grid place-items-center text-slate-400 text-sm">
                  Tanpa gambar
                </div>
              @endif
            </a>

            {{-- Teks --}}
            <div class="md:col-span-8">
              {{-- Hashtag --}}
              <div class="flex flex-wrap gap-2 mb-1 -mx-1">
                @foreach($a->hashtag_array as $t)
                  <a href="{{ route('articles.public.index',['q'=>$t]) }}"
                     class="mx-1 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] md:text-xs bg-sky-50 text-sky-700 hover:bg-sky-100">
                    #{{ e($t) }}
                  </a>
                @endforeach
              </div>

              <h3 class="font-extrabold leading-snug text-slate-900 text-balance
                         text-[clamp(16px,5.5vw,20px)] md:text-2xl">
                <a href="{{ route('articles.show',$a->slug) }}" class="hover:underline">
                  {{ $a->title }}
                </a>
              </h3>

              <p class="mt-2 text-slate-700 text-[15px] md:text-base leading-relaxed line-clamp-3">
                {{ $a->summary }}
              </p>

              <p class="mt-2 text-[11px] md:text-xs text-slate-500">
                {{ $a->author }} · {{ $a->created_at->format('d M Y') }}
              </p>
            </div>
          </div>
        </article>
      @endforeach
    </div>

    <div class="mt-5 md:mt-6">
      <div class="bg-white/60 rounded-xl p-2 flex justify-center md:justify-end">
        {{ $articles->onEachSide(1)->links() }}
      </div>
    </div>
  @else
    <div class="bg-white rounded-xl shadow p-6 text-slate-600">
      Tidak ada artikel yang cocok dengan filter.
    </div>
  @endif
</section>

<style>
  .text-balance { text-wrap: balance; }
  /* Hilangkan “glitch” saat tap di iOS */
  img { -webkit-tap-highlight-color: transparent; }
</style>
@endsection
