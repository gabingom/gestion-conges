<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('agents')->onDelete('cascade');
            $table->integer('jours_a_prendre');
            $table->date('date_cessation');
            $table->date('date_reprise')->nullable();
            $table->string('statut')->default('en_attente'); // en_attente, approuve, refuse
            $table->text('commentaire')->nullable();
            $table->boolean('exceptionnel')->default(false);
            $table->boolean('deductible')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conges');
    }
};
