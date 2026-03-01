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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('avatar', 250)->nullable();
            $table->string('name', 250)->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->integer('otp')->nullable()->unsigned()->check('otp BETWEEN 100000 AND 999999'); // 6-digit OTP
            $table->timestamp('otp_created_at')->nullable();
            $table->timestamp('otp_expires_at')->nullable();
            $table->string('reset_token')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('is_admin')->default(0);
            $table->boolean('privacy_policy')->default(0);
            $table->enum('role', ['user', 'admin', 'instructor', 'student'])->default('user');
            $table->string('joining_date')->nullable();
            
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
