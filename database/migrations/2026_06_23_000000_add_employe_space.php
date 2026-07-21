<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1) Rôle sur les comptes (admin / gestionnaire / employe)
        if (!Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('role')->nullable()->after('password');
            });
            // Tous les comptes déjà créés sont des comptes back-office -> admin
            DB::table('users')->update(['role' => 'admin']);
        }

        // 2) Lien compte de connexion <-> fiche agent
        if (!Schema::hasColumn('agents', 'user_id')) {
            Schema::table('agents', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id')
                      ->constrained('users')->nullOnDelete();
            });
        }

        // 3) Statut sur les absences (pour les demandes envoyées par l'employé)
        if (!Schema::hasColumn('absences', 'statut')) {
            Schema::table('absences', function (Blueprint $table) {
                $table->string('statut')->default('en_attente')->after('agent_id');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('agents', 'user_id')) {
            Schema::table('agents', function (Blueprint $table) {
                $table->dropConstrainedForeignId('user_id');
            });
        }
        if (Schema::hasColumn('absences', 'statut')) {
            Schema::table('absences', function (Blueprint $table) {
                $table->dropColumn('statut');
            });
        }
        if (Schema::hasColumn('users', 'role')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('role');
            });
        }
    }
};
