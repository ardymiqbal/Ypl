<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('name',150);
            $table->string('email',150);
            $table->text('message')->nullable();
            $table->string('proof_path'); // simpan path bukti transfer
            $table->enum('status',['pending','verified','rejected'])->default('pending')->index();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('donations');
    }
};
