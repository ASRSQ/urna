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
            Schema::create('result_filters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id');
            $table->string('label');
            $table->dateTime('start'); // 🔥 trocar
            $table->dateTime('end');   // 🔥 trocar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('result_filters');
    }
};
