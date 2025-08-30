<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');                         // required
            $table->string('slug')->unique();               // required, unique
            $table->text('summary');                        // required
            $table->text('content');                        // required
            $table->string('documentation')->nullable();    // JSON string array paths (max 3) - optional
            $table->string('author');                       // required
            $table->string('hashtags');                     // required, comma separated
            $table->string('thumbnail');                    // required (path or URL)
            $table->enum('status',['draft','published'])->default('draft'); // required
            $table->timestamps();                           // created_at, updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
