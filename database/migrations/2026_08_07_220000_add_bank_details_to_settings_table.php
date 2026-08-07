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
        Schema::table('settings', function (Blueprint $table) {
            $table->string('beneficiary_name')->nullable()->after('gstin');
            $table->string('bank_name')->nullable()->after('beneficiary_name');
            $table->string('account_type')->nullable()->after('bank_name');
            $table->string('account_number')->nullable()->after('account_type');
            $table->string('ifsc_code')->nullable()->after('account_number');
            $table->string('swift_bic_code')->nullable()->after('ifsc_code');
            $table->string('branch')->nullable()->after('swift_bic_code');
            $table->string('stamp')->nullable()->after('branch');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'beneficiary_name',
                'bank_name',
                'account_type',
                'account_number',
                'ifsc_code',
                'swift_bic_code',
                'branch',
                'stamp',
            ]);
        });
    }
};
