<?php

use App\Enums\VerificationRequestProviderEnum;
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
        Schema::create('verification_request', function (Blueprint $table) {
            $table->id();
            $table->enum('provider', VerificationRequestProviderEnum::values());
            $table->integer('code');
            $table->integer('attempts')->nullable()->default(0);
            $table->string('receiver');
            $table->date('veriffication_at')->nullable();
            $table->date('expire_at');
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
