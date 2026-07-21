<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conges', function (Blueprint $table) {
            // Ajout du type de congé (par défaut annuel)
            $table->string('type_conge')->default('Annuel')->after('agent_id');

            // Ajout du justificatif (optionnel car tous les congés n'en demandent pas)
            $table->string('justificatif_path')->nullable()->after('commentaire');
        });
    }

    public function down(): void
    {
        Schema::table('conges', function (Blueprint $table) {
            $table->dropColumn(['type_conge', 'justificatif_path']);
        });
    }
};
