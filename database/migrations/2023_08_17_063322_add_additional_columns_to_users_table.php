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
            $table->string('slug')->nullable();
            $table->integer('referred_by')->nullable();
            $table->string('provider')->nullable();
            $table->string('provider_id')->nullable();
            $table->string('user_type')->default('customer');
            $table->string('new_email_verification_code')->nullable();
            $table->string('avatar')->nullable();
            $table->string('address')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('phone')->nullable();
            $table->double('balance')->default(0.00);
            $table->tinyInteger('banned')->default(0);
            $table->string('referral_code')->nullable();
            $table->integer('customer_package_id')->nullable();
            $table->integer('remaining_uploads')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'referred_by', 'provider', 'provider_id', 'user_type',
                'new_email_verification_code', 'avatar', 'address', 'country',
                'state', 'city', 'postal_code', 'phone', 'balance', 'banned',
                'referral_code', 'customer_package_id', 'remaining_uploads',
            ]);
        });
    }
};