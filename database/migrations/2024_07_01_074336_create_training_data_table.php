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
        Schema::create('training_data', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 32);
            $table->string('membaca_not_angka', 20);
            $table->string('mengoperasikan_software', 20);
            $table->string('mengoperasikan_audio', 20);
            $table->string('bidang', 30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_data');
    }
};
