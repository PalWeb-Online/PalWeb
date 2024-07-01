<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('snapshots', function (Blueprint $table) {
            $table->id();
            $table->uuid('aggregate_uuid');
            $table->unsignedInteger('aggregate_version');
            $table->jsonb('state');

            $table->timestamps();

            $table->index('aggregate_uuid');
        });
    }
};
