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
      Schema::create('jour_feries', function (Blueprint $table) {
    $table->id();
    $table->string('nom');
    $table->date('date');
    $table->boolean('annuel')->default(true); // true = se répète chaque année
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jour_feries');
    }
};
