@extends('layouts.app')

@section('title','Magang')

{{-- Desktop lebar, HP rapat --}}
@section('container_class','w-full max-w-none px-4 md:px-[60px]')
@section('container_padding','py-6 md:py-8')

@section('content')
@php
  // Ganti ke gambar milikmu di public/images/action/magang-hero.jpg
  $hero = asset('images/action/magang-hero.jpg');
@endphp

{{-- ================= HERO ================= --}}
<section class="bg-white rounded-2xl shadow p-4 sm:p-6 md:p-8">
  <div class="grid md:grid-cols-12 gap-6 md:gap-8 items-center">
    {{-- Teks kiri --}}
    <div class="md:col-span-7">
    

      <h1 class="mt-3 font-extrabold leading-tight text-green-900 text-balance
                 text-[clamp(22px,6vw,40px)] md:text-[clamp(28px,3.8vw,52px)]">
        Bergerak Bersama <br class="hidden md:block"/> Magang di Yayasan Pemerhati Lingkungan
      </h1>

      <p class="mt-2 text-blue-700 font-semibold text-[14px] sm:text-base">
        Jadilah Bagian dari Perubahan
      </p>

      <p class="mt-3 text-gray-700 text-pretty
                text-[15px] leading-7 md:text-[18px] md:leading-8">
        Lingkungan yang sehat bukan hanya tanggung jawab satu pihak, tetapi hasil kerja bersama.
        Kami mengajak Anda untuk bergabung sebagai relawan lingkungan — menjadi bagian dari
        gerakan yang peduli, bergerak, dan berdampak nyata. Langkah kecil Anda berarti.
      </p>

      <a href="#form-relawan"
         class="inline-flex items-center gap-2 mt-5 px-5 py-2.5 rounded-lg bg-green-600 text-white font-semibold
                hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500/50">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none"><path d="M5 12h14M13 5l7 7-7 7" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
        Pendaftaran Di Sini
      </a>
    </div>

    {{-- Gambar kanan --}}
    <div class="md:col-span-5 flex justify-center md:justify-end">
      <div class="w-full max-w-[680px] overflow-hidden rounded-xl ring-1 ring-black/5 shadow">
        <img src="{{ $hero }}" alt="Magang"
             class="w-full aspect-[4/5] sm:aspect-[3/4] md:aspect-[4/3] lg:aspect-[16/10] object-cover">
      </div>
    </div>
  </div>
</section>

{{-- ================= LANGKAH SELANJUTNYA ================= --}}
<section class="mt-8 bg-white rounded-2xl shadow p-4 sm:p-6 md:p-8">
  <h2 class="text-gray-900 font-extrabold text-balance
             text-[18px] sm:text-xl md:text-2xl">
    Langkah Selanjutnya!
  </h2>

  <div class="mt-4 grid sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
    {{-- Card 1 --}}
    <div class="rounded-xl ring-1 ring-gray-200 bg-white p-4 sm:p-5 h-full">
      <div class="flex items-center gap-2 text-green-700 font-bold text-sm">
        <span class="grid place-items-center w-6 h-6 rounded-full bg-green-50 ring-1 ring-green-200">1</span>
        Pendaftaran
      </div>
      <p class="mt-2 text-gray-700 text-[13px] sm:text-sm md:text-[15px] leading-6">
        Catat minatmu menjadi relawan dan isi data singkat di formulir. Tim kami akan menghubungi.
      </p>
    </div>

    {{-- Card 2 --}}
    <div class="rounded-xl ring-1 ring-gray-200 bg-white p-4 sm:p-5 h-full">
      <div class="flex items-center gap-2 text-green-700 font-bold text-sm">
        <span class="grid place-items-center w-6 h-6 rounded-full bg-green-50 ring-1 ring-green-200">2</span>
        Seleksi
      </div>
      <p class="mt-2 text-gray-700 text-[13px] sm:text-sm md:text-[15px] leading-6">
        Tim meninjau kesesuaian dan ketersediaan. Seleksi tetap ramah, cepat, dan komunikatif.
      </p>
    </div>

    {{-- Card 3 --}}
    <div class="rounded-xl ring-1 ring-gray-200 bg-white p-4 sm:p-5 h-full">
      <div class="flex items-center gap-2 text-green-700 font-bold text-sm">
        <span class="grid place-items-center w-6 h-6 rounded-full bg-green-50 ring-1 ring-green-200">3</span>
        Orientasi
      </div>
      <p class="mt-2 text-gray-700 text-[13px] sm:text-sm md:text-[15px] leading-6">
        Peserta terpilih ikut sesi pengenalan program, peran relawan, dan etika lapangan.
      </p>
    </div>

    {{-- Card 4 --}}
    <div class="rounded-xl ring-1 ring-gray-200 bg-white p-4 sm:p-5 h-full">
      <div class="flex items-center gap-2 text-green-700 font-bold text-sm">
        <span class="grid place-items-center w-6 h-6 rounded-full bg-green-50 ring-1 ring-green-200">4</span>
        Aksi Lingkungan
      </div>
      <p class="mt-2 text-gray-700 text-[13px] sm:text-sm md:text-[15px] leading-6">
        Terlibat di kegiatan: edukasi, penghijauan, pengelolaan sampah, dan lainnya.
      </p>
    </div>
  </div>
</section>

{{-- ================= TESTIMONI (placeholder sesuai UI) ================= --}}
<section class="mt-8">
  <h2 class="text-center font-semibold text-gray-700 text-[13px] sm:text-sm md:text-base">
    Kata Mereka Tentang Menjadi Relawan YPL
  </h2>

  {{-- Placeholder area: tinggi lebih proporsional di HP --}}
  <div class="mt-3 rounded-2xl bg-sky-100/70 ring-1 ring-sky-200 min-h-[180px] sm:min-h-[220px] md:min-h-[260px]"></div>
</section>

{{-- Tipografi & preferensi animasi --}}
<style>
  .text-balance { text-wrap: balance; }
  .text-pretty  { text-wrap: pretty; }
  @media (max-width: 480px) { h1, h2, h3 { line-height: 1.2; } }
</style>
@endsection
