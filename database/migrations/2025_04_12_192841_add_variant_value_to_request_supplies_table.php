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
        Schema::table('request_supplies', function (Blueprint $table) {
            $table->string('variant_value')->nullable(); // Optional, can be fetched via relationship

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_supplies', function (Blueprint $table) {
            //
        });
    }
};
