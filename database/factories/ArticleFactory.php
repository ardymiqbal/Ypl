<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        $faker = $this->faker;

        // contoh hashtag (sebagian multi-kata untuk menguji pencarian)
        $tagPool = [
            'lingkungan', 'konservasi pesisir', 'edukasi iklim', 'bank sampah',
            'ekonomi sirkular', 'energi terbarukan', 'pantai bersih',
            'rehabilitasi mangrove', 'daur ulang', 'kampanye publik',
        ];
        // ambil 2–4 tag acak
        $pick = $faker->randomElements($tagPool, rand(2, 4));
        $hashtags = implode(', ', $pick); // simpan sebagai string, dipisah koma

        // konten HTML ringan
        $paragraphs = $faker->paragraphs(rand(4, 7));
        $content = '<h2>'.$faker->sentence().'</h2>';
        foreach ($paragraphs as $p) {
            $content .= '<p>'.e($p).'</p>';
        }

        // thumbnail & dokumentasi (gunakan picsum)
        $seed = Str::uuid()->toString();
        $thumb = "https://picsum.photos/seed/{$seed}/1200/675";

        $docCount = rand(0, 3);
        $docs = [];
        for ($i=0; $i<$docCount; $i++) {
            $dseed = Str::uuid()->toString();
            $docs[] = "https://picsum.photos/seed/{$dseed}/800/600";
        }

        return [
            'title'         => $faker->unique()->sentence(6),
            // slug dibiarkan kosong; akan diisi otomatis oleh boot() -> uniqueSlug()
            'slug'          => null,
            'summary'       => $faker->paragraphs(2, true),
            'content'       => $content,                 // HTML ringan
            'documentation' => $docs ?: null,            // cast array → JSON string
            'author'        => $faker->name(),
            'hashtags'      => $hashtags,                // string: "tag1, tag dua, ..."
            'thumbnail'     => $thumb,                   // URL langsung
            'status'        => $faker->boolean(80) ? 'published' : 'draft',
            'created_at'    => $faker->dateTimeBetween('-8 months', 'now'),
            'updated_at'    => now(),
        ];
    }
}
