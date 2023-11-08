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
        Schema::table('users', function ($table) {
            $table->string('first_name');
            $table->string('last_name');
            DB::statement('ALTER TABLE `users` CHANGE `name` `full_name` varchar(255);');
            $table->string('mobile')->unique();
            $table->string('password')->nullable()->change();
            $table->string('email')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function ($table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            DB::statement('ALTER TABLE `users` CHANGE `full_name` `name` varchar(255);');
            $table->string('mobile')->unique(false)->change();
            $table->dropColumn('mobile');
            $table->string('password')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
        });
    }
};
