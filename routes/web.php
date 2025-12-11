<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\RubriqueController;
use App\Http\Controllers\CodeBudgetController;
use App\Http\Controllers\LigneBudgetController;
use App\Http\Controllers\PrevisionController;
use App\Http\Controllers\RealisationController;
use App\Http\Controllers\TresorerieController;
use App\Http\Controllers\EngagementController;
use App\Http\Controllers\OperationController;
use App\Http\Controllers\TacheController;


// Routes publiques (sans authentification)
Route::get('/', [PageController::class, 'auth_admin'])->name('login');
Route::post('login_admin', [AuthController::class, 'login_admin'])->name('login_admin');

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {

    // Déconnexion
    Route::post('deconnexion', [AuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/dashboard', [PageController::class, 'home'])->name('home');

    // Routes for Budget Monitoring
    Route::get('/suivi-budgetaire/suivi_budget', [BudgetController::class, 'index'])->name('budget.index');
    Route::get('/suivi-budgetaire/suivi_taux_execution_budgetaire', [BudgetController::class, 'execution'])->name('budget.execution');
    Route::get('/suivi-budgetaire/suivi_taux_consommation_subventions', [BudgetController::class, 'consommation'])->name('budget.consommation');
    // print suivi budget
    Route::get('/suivi-budgetaire/print', [BudgetController::class, 'print'])->name('budget.print');

    // Routes for tresorery Monitoring
    Route::get('/suivi-tresorerie/suivi_depenses_rations', [TresorerieController::class, 'suivi_depenses_rations'])->name('tresorerie.depenses_rations');
    Route::get('/suivi-tresorerie/suivi_situation_financiere', [TresorerieController::class, 'suivi_situation_financiere'])->name('tresorerie.situation_financiere');
    Route::get('/suivi-tresorerie/export_excel', [TresorerieController::class, 'export_excel'])->name('tresorerie.export_excel');
    Route::get('/suivi-tresorerie/print_ration', [TresorerieController::class, 'print_ration'])->name('tresorerie.print_ration');
    Route::get('/suivi-tresorerie/print_situation_financiere', [TresorerieController::class, 'print_situation_financiere'])->name('tresorerie.print_situation_financiere');

    // Route for engagement Monitoring
    Route::get('/suivi-engagement/suivi_engagements', [EngagementController::class, 'suivi_fournisseurs'])->name('engagement.suivi_fournisseurs');
    Route::get('/suivi-engagement/suivi_audits', [EngagementController::class, 'suivi_audits'])->name('engagement.suivi_audits');

    // Configuration et données
    Route::get('/configurations', [PageController::class, 'config'])->name('config.page');
    Route::get('/donnees', [PageController::class, 'data'])->name('data.page');

    // Routes for Rubrique, CodeBudget, and LigneBudget management
    Route::resource('gestion_rubriques', RubriqueController::class);
    Route::resource('gestion_code_budgets', CodeBudgetController::class);
    Route::resource('gestion_ligne_budgets', LigneBudgetController::class);

    // Routes for Budget data entry
    Route::get('/donnees/prevision/saisi_prevision', [PrevisionController::class, 'saisi_prevision'])->name('data.prevision.saisi_prevision');
    Route::resource('gestion_previsions', PrevisionController::class);
    // import form POST
    Route::post('/previsions/import', [PrevisionController::class, 'import'])->name('previsions.import');
    // print previsions
    Route::get('/previsions/print', [PrevisionController::class, 'print'])->name('previsions.print');

    // Routes for Realisation data entry
    Route::get('/donnees/realisation/saisi_realisation', [RealisationController::class, 'saisi_realisation'])->name('data.realisation.saisi_realisation');
    // print realisations
    Route::get('/realisations/print', [RealisationController::class, 'print'])->name('realisations.print');
    Route::resource('gestion_realisations', RealisationController::class);

    // Routes for Operations data entry
    Route::resource('gestion_operations', OperationController::class);

    // Routes for Taches management
    Route::resource('gestion_taches', TacheController::class);

});
