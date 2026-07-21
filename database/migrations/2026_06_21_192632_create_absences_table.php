<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained('agents')->onDelete('cascade');
            $table->integer('nombre_jours');
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->enum('motif', [
                'maladie',
                'mariage',           // 3 jours, non déductible
                'naissance',         // 1 jour, non déductible
                'bapteme',           // 1 jour, non déductible
                'deces_ascendant',   // 2 jours, non déductible
                'deces_descendant',  // 4 jours, non déductible
                'grossesse',         // 6 sem avant + 8 sem après, non déductible
                'accident_travail',  // variable, validé médecin
                'autorisation_personnelle', // rappelable à tout moment
                'autre',
            ]);
            $table->boolean('deductible')->default(true);
            $table->boolean('valide_par_medecin')->default(false); // accident_travail
            $table->boolean('rappelable')->default(false);         // autorisation_personnelle
            $table->string('document_justificatif')->nullable();   // grossesse si dépassement
            $table->text('commentaire')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absences');
    }
};
