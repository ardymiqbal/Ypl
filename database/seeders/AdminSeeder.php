<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $name  = (string) config('admin.name');
        $email = (string) config('admin.email');
        $pass  = (string) config('admin.password');

        if ($pass === '') {
            throw new RuntimeException('ADMIN_PASSWORD belum di-set di .env');
        }

        Admin::updateOrCreate(
            ['email' => $email],
            ['name' => $name, 'password' => Hash::make($pass)]
        );
    }
}
