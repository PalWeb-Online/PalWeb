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
        Schema::rename('missing_terms', 'feedback_comments');

        Schema::table('feedback_comments', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->renameColumn('translit', 'comment');
            $table->dropColumn('category');
        });

        DB::table('feedback_comments')->update(['user_id' => 1]);

        Schema::table('feedback_comments', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feedback_comments', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->renameColumn('comment', 'translit');
            $table->string('category')->nullable();
        });

        Schema::rename('feedback_comments', 'missing_terms');
    }
};
