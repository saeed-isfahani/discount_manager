<?php

use App\Enums\ProvincesCities\ProvincesCitiesTypeEnum;
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
        Schema::create('province_cities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ProvincesCitiesTypeEnum::values());
            $table->integer('parent_id')->nullable();
            $table->string('unique_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('province_cities');
    }
};
