<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title');
            $table->text('summary')->nullable();
            $table->json('document');
            $table->string('status')->default('draft');
            $table->string('locale', 10)->default('en');
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->foreignId('parent_id')->nullable()->constrained('pages')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
