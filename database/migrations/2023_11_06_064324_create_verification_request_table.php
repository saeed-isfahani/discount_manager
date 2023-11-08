<?php

use App\Enums\VerificationRequest\VerificationRequestProviderEnum;
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
        Schema::create('verification_requests', function (Blueprint $table) {
            $table->id();
            $table->enum('provider', VerificationRequestProviderEnum::values());
            $table->integer('code');
            $table->integer('attempts')->nullable()->default(0);
            $table->string('receiver');
            $table->dateTime('veriffication_at')->nullable();
            $table->dateTime('expire_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verification_request');
    }
};
