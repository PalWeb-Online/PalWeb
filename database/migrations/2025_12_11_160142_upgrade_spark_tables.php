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
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'receipt_emails') && !Schema::hasColumn('users', 'invoice_emails')) {
                $table->renameColumn('receipt_emails', 'invoice_emails');
            }
        });

        Schema::dropIfExists('receipts');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'invoice_emails')) {
                $table->renameColumn('invoice_emails', 'receipt_emails');
            }
        });
    }
};
