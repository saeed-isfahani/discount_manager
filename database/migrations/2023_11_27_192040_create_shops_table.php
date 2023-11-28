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
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('mobile');
            $table->string('phone')->nullable();
            $table->string('licence_number');
            $table->string('shop_number');
            $table->string('address');
            $table->string('uuid')->unique();
            $table->string('logo')->nullable();
            $table->string('location');

            $table->unsignedBigInteger('owner_id');
            $table->foreign('owner_id')->on('users')->references('id')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('city_id');
            $table->foreign('city_id')->on('province_cities')->references('id')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('province_id');
            $table->foreign('province_id')->on('province_cities')->references('id')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->on('categories')->references('id')->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
