<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSparkColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('stripe_id')->nullable()->index()->after('remember_token');
            $table->string('pm_type')->nullable()->after('stripe_id');
            $table->string('pm_last_four', 4)->nullable()->after('pm_type');
            $table->string('pm_expiration')->nullable()->after('pm_last_four');
            $table->text('extra_billing_information')->nullable()->after('pm_expiration');
            $table->timestamp('trial_ends_at')->nullable()->after('extra_billing_information');
            $table->string('billing_address')->nullable()->after('trial_ends_at');
            $table->string('billing_address_line_2')->nullable()->after('billing_address');
            $table->string('billing_city')->nullable()->after('billing_address_line_2');
            $table->string('billing_state')->nullable()->after('billing_city');
            $table->string('billing_postal_code', 25)->nullable()->after('billing_state');
            $table->string('billing_country', 2)->nullable()->after('billing_postal_code');
            $table->string('vat_id', 50)->nullable()->after('billing_postal_code');
            $table->text('receipt_emails')->nullable()->after('vat_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_id',
                'pm_type',
                'pm_last_four',
                'pm_expiration',
                'extra_billing_information',
                'trial_ends_at',
                'billing_address',
                'billing_address_line_2',
                'billing_city',
                'billing_state',
                'billing_postal_code',
                'billing_country',
                'vat_id',
                'receipt_emails',
            ]);
        });
    }
}
