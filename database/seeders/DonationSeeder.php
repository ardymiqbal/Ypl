<?php

namespace Database\Seeders;

use App\Models\Donation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan disk "public" siap (jalankan: php artisan storage:link)
        Storage::disk('public')->makeDirectory('donations');

        // Buat 1 file PNG 1x1 (dummy) untuk dipakai copy
        $dummyName = 'donations/_seed_dummy.png';
        if (!Storage::disk('public')->exists($dummyName)) {
            // PNG 1x1 transparan
            $onePxPng = base64_decode(
                'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mP8/x8AAwMCAO8p1XkAAAAASUVORK5CYII='
            );
            Storage::disk('public')->put($dummyName, $onePxPng);
        }

        $statuses = ['pending','verified','rejected'];
        $rows = [];
        $now = Carbon::now();

        for ($i = 1; $i <= 25; $i++) {
            // Copy dummy menjadi file unik agar path berbeda-beda
            $proof = 'donations/proof-'.Str::uuid().'.png';
            Storage::disk('public')->copy($dummyName, $proof);

            $created = $now->copy()->subDays(rand(0, 90))->subMinutes(rand(0, 1440));
            $rows[] = [
                'name'       => fake()->name(),
                'email'      => fake()->unique()->safeEmail(),
                'message'    => rand(0,1) ? fake()->sentence(rand(6,14)) : null,
                'proof_path' => $proof,
                'status'     => $statuses[array_rand($statuses)],
                'created_at' => $created,
                'updated_at' => $created->copy()->addMinutes(rand(0, 720)),
            ];
        }

        // Insert massal
        Donation::insert($rows);
    }
}
