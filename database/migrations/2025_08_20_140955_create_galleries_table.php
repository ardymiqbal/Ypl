<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');                      // required
            $table->string('slug')->unique();            // required, unique
            $table->text('description')->nullable();     // nullable
            $table->enum('media_type',['image','video']); // required
            $table->string('media_path');                 // required (path or URL)
            $table->boolean('is_published')->default(false); // required
            $table->timestamps();                         // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
