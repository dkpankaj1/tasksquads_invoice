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
        Schema::create('bank_details', function (Blueprint $table) {
            $table->id();
            $table->string('beneficiary_name');
            $table->string('bank_name');
            $table->string('account_type');
            $table->string('account_number');
            $table->string('ifsc_code');
            $table->string('swift_bic_code')->nullable();
            $table->string('branch');
            $table->string('stamp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_details');
    }
};
