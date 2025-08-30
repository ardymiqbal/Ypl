<?php

namespace Database\Seeders;

use App\Models\Gallery;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan folder storage publik tersedia
        Storage::disk('public')->makeDirectory('galleries');

        // Siapkan satu file image PNG 1x1 (dummy) untuk dikopi
        $dummy = 'galleries/_seed_dummy.png';
        if (!Storage::disk('public')->exists($dummy)) {
            // PNG 1x1 transparan
            $onePxPng = base64_decode(
                'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO8p1XkAAAAASUVORK5CYII='
            );
            Storage::disk('public')->put($dummy, $onePxPng);
        }

        // Contoh sumber video kecil (pakai URL agar tidak menyimpan file besar)
        $videoSamples = [
            'https://www.w3schools.com/html/mov_bbb.mp4',
            'https://samplelib.com/lib/preview/mp4/sample-5s.mp4',
            'https://sample-videos.com/video321/mp4/240/big_buck_bunny_240p_1mb.mp4',
        ];

        // Buat 24 item campuran image/video
        for ($i = 1; $i <= 24; $i++) {
            $isImage = (rand(1, 100) <= 70); // ~70% image, sisanya video
            $mediaType = $isImage ? 'image' : 'video';

            if ($isImage) {
                // Copy dummy → path unik, supaya tiap record punya file berbeda
                $path = 'galleries/seed-'.Str::uuid().'.png';
                Storage::disk('public')->copy($dummy, $path);
                $mediaPath = $path; // local path (akan dirender via asset('storage/...'))
            } else {
                // Ambil URL video sample
                $mediaPath = $videoSamples[array_rand($videoSamples)];
            }

            $title = fake()->sentence(rand(3, 6));
            $desc  = fake()->paragraph(rand(2, 4));

            Gallery::create([
                'title'        => $title,
                // slug akan diisi otomatis di model boot()
                'description'  => $desc,
                'media_type'   => $mediaType,                 // 'image' | 'video'
                'media_path'   => $mediaPath,                 // lokal (storage) atau URL
                'is_published' => (rand(1, 100) <= 85),       // ~85% published
            ]);
        }
    }
}
