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
        Schema::create('predict_data', function (Blueprint $table) {
            $table->id();
            $table->string('epoch');
            $table->string('nama');
            $table->string('x1');
            $table->string('x2');
            $table->string('x3');
            $table->string('net_choir');
            $table->string('net_multimedia');
            $table->string('net_soundman');
            $table->string('output_choir');
            $table->string('output_multimedia');
            $table->string('output_soundman');
            $table->string('target_choir');
            $table->string('target_multimedia');
            $table->string('target_soundman');
            $table->string('error_choir');
            $table->string('error_multimedia');
            $table->string('error_soundman');
            $table->string('w1_choir');
            $table->string('w2_choir');
            $table->string('w3_choir');
            $table->string('w1_multimedia');
            $table->string('w2_multimedia');
            $table->string('w3_multimedia');
            $table->string('w1_soundman');
            $table->string('w2_soundman');
            $table->string('w3_soundman');
            $table->string('bias_choir');
            $table->string('bias_multimedia');
            $table->string('bias_soundman');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('predict_data');
    }
};
