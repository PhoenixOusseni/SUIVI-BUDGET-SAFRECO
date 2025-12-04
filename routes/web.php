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


Route::get('/', [PageController::class, 'auth_admin'])->name('auth_admin');
Route::get('/login', [PageController::class, 'auth_admin'])->name('login');

Route::post('login_admin', [AuthController::class, 'login_admin'])->name('login_admin');
Route::post('deconnexion', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', [PageController::class, 'home'])->name('home');

// Routes for Budget Monitoring
Route::get('/suivi-budgetaire/suivi_budget', [BudgetController::class, 'index'])->name('budget.index');
Route::get('/suivi-budgetaire/suivi_taux_execution_budgetaire', [BudgetController::class, 'execution'])->name('budget.execution');
Route::get('/suivi-budgetaire/suivi_taux_consommation_subventions', [BudgetController::class, 'consommation'])->name('budget.consommation');

// Routes for tresorery Monitoring
Route::get('/suivi-tresorerie/suivi_depenses_rations', [TresorerieController::class, 'suivi_depenses_rations'])->name('tresorerie.depenses_rations');
Route::get('/suivi-tresorerie/suivi_situation_financiere', [TresorerieController::class, 'suivi_situation_financiere'])->name('tresorerie.situation_financiere');

// Route for engagement Monitoring
Route::get('/suivi-engagement/suivi_engagements', [EngagementController::class, 'suivi_fournisseurs'])->name('engagement.suivi_fournisseurs');
Route::get('/suivi-engagement/suivi_audits', [EngagementController::class, 'suivi_audits'])->name('engagement.suivi_audits');


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

// Routes for Realisation data entry
Route::get('/donnees/realisation/saisi_realisation', [RealisationController::class, 'saisi_realisation'])->name('data.realisation.saisi_realisation');
Route::resource('gestion_realisations', RealisationController::class);

// Routes for Operations data entry
Route::resource('gestion_operations', OperationController::class);
