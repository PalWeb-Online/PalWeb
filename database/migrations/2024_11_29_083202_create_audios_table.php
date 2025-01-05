<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audios', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->foreignId('speaker_id')->constrained('speakers')->cascadeOnDelete();
            $table->foreignId('pronunciation_id')->constrained('pronunciations');
            $table->unique(['speaker_id', 'pronunciation_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audios');
    }
};
