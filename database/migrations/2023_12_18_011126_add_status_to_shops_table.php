<?php

use App\Enums\Shop\ShopStatusEnum;
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
        Schema::table('shops', function (Blueprint $table) {
            $table->enum('status', [
                ShopStatusEnum::ACTIVE->value,
                ShopStatusEnum::DEACTIVE->value,
                ShopStatusEnum::PENDING->value,
            ])->default(ShopStatusEnum::PENDING->value);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
