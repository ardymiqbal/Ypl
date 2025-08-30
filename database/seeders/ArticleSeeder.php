<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        // Buat 24 artikel random
        Article::factory()->count(24)->create();

        // Tambahan beberapa artikel "spesifik" untuk uji hashtag multi-kata
        $samples = [
            [
                'title'    => 'Gerakan Pantai Bersih di Desa Lukpanenteng',
                'summary'  => 'Kolaborasi warga membersihkan pesisir dan memilah sampah.',
                'content'  => '<p>Kegiatan bersih pantai disertai edukasi pilah sampah dan kampanye publik.</p>',
                'author'   => 'Tim YPL',
                'hashtags' => 'pantai bersih, konservasi pesisir, kampanye publik',
                'thumbnail'=> 'https://picsum.photos/seed/pantai-bersih/1200/675',
                'status'   => 'published',
                'documentation' => [
                    'https://picsum.photos/seed/doc-1/800/600',
                    'https://picsum.photos/seed/doc-2/800/600',
                ],
            ],
            [
                'title'    => 'Edukasi Iklim untuk Remaja',
                'summary'  => 'Pengenalan perubahan iklim dan aksi sederhana di rumah.',
                'content'  => '<p>Materi meliputi hemat listrik, daur ulang, dan menanam pohon.</p>',
                'author'   => 'Divisi Edukasi',
                'hashtags' => 'edukasi iklim, lingkungan, kampanye publik',
                'thumbnail'=> 'https://picsum.photos/seed/edukasi-iklim/1200/675',
                'status'   => 'published',
                'documentation' => null,
            ],
        ];

        foreach ($samples as $row) {
            Article::create($row);
        }
    }
}
