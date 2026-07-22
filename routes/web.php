<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\CongeController;
use App\Http\Controllers\AbsenceController;
use App\Http\Controllers\JourFerieController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RapportController;
use App\Http\Controllers\EmployeController;
use App\Http\Controllers\PasswordController;

// Redirection racine vers login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentification
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Changement du mot de passe provisoire (obligatoire à la 1re connexion)
Route::middleware('auth')->group(function () {
    Route::get('/changer-mot-de-passe', [PasswordController::class, 'edit'])->name('password.change');
    Route::post('/changer-mot-de-passe', [PasswordController::class, 'update'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| BACK-OFFICE (admin / gestionnaire) — inchangé
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'must.change.password', 'role:admin,gestionnaire'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Agents
    Route::resource('agents', AgentController::class);

    // Congés
    Route::resource('conges', CongeController::class);
    Route::patch('conges/{conge}/approuver', [CongeController::class, 'approuver'])->name('conges.approuver');
    Route::patch('conges/{conge}/refuser', [CongeController::class, 'refuser'])->name('conges.refuser');

    // Absences
    Route::resource('absences', AbsenceController::class);
    Route::patch('absences/{absence}/approuver', [AbsenceController::class, 'approuver'])->name('absences.approuver');
    Route::patch('absences/{absence}/refuser', [AbsenceController::class, 'refuser'])->name('absences.refuser');

    // Jours fériés
    Route::resource('jours-feries', JourFerieController::class);

    // Rapports
    Route::get('/rapports', [RapportController::class, 'index'])->name('rapports.index');
    Route::get('/rapports/generer', [RapportController::class, 'generer'])->name('rapports.generer');
    Route::get('/rapports/export-pdf', [RapportController::class, 'exportPdf'])->name('rapports.export-pdf');

    // Créer un utilisateur (admin seulement)
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| ESPACE EMPLOYÉ
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'must.change.password', 'role:employe'])->prefix('employe')->name('employe.')->group(function () {

    // Mon profil (lecture seule)
    Route::get('/mon-profil', [EmployeController::class, 'profil'])->name('profil');

    // Règlement intérieur
    Route::get('/code-du-travail', [EmployeController::class, 'reglement'])->name('reglement');

    // Mes congés
    Route::get('/mes-conges', [EmployeController::class, 'mesConges'])->name('conges');
    Route::get('/mes-conges/nouvelle', [EmployeController::class, 'createConge'])->name('conges.create');
    Route::post('/mes-conges', [EmployeController::class, 'storeConge'])->name('conges.store');

    // Mes absences
    Route::get('/mes-absences', [EmployeController::class, 'mesAbsences'])->name('absences');
    Route::get('/mes-absences/nouvelle', [EmployeController::class, 'createAbsence'])->name('absences.create');
    Route::post('/mes-absences', [EmployeController::class, 'storeAbsence'])->name('absences.store');
});
