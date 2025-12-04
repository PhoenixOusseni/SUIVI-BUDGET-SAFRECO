<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\CodeBudget;
use App\Models\Rubrique;
use App\Models\LigneBudget;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Lors de la création d'un CodeBudget, remplir automatiquement le champ `code`
        CodeBudget::creating(function (CodeBudget $codeBudget) {
            // Si l'utilisateur n'a pas fourni de code mais a renseigné rubrique_id
            if (empty($codeBudget->code) && !empty($codeBudget->rubrique_id)) {
                $rubrique = Rubrique::find($codeBudget->rubrique_id);
                if ($rubrique) {
                    $codeBudget->code = $this->generateCodeBudgetForRubrique($rubrique);
                }
            }
        });

        // Lors de la création d'un LigneBudget, remplir automatiquement le champ `code`
        LigneBudget::creating(function (LigneBudget $ligneBudget) {
            // Si l'utilisateur n'a pas fourni de code mais a renseigné code_budget_id
            if (empty($ligneBudget->code) && !empty($ligneBudget->code_budget_id)) {
                $codeBudget = CodeBudget::find($ligneBudget->code_budget_id);
                if ($codeBudget) {
                    $ligneBudget->code = $this->generateCodeBudgetForCodeBudget($codeBudget);
                }
            }
        });
    }

    /**
     * Génère le prochain code pour un Rubrique donné.
     *
     * Ex: si Rubrique::code == "A" et qu'il existe déjà A.1, A.2 -> renvoie "A.3"
     * Si aucun CodeBudget n'existe pour la rubrique -> renvoie "A.1"
     *
     * @param  \App\Models\Rubrique  $rubrique
     * @return string
     */
    protected function generateCodeBudgetForRubrique(Rubrique $rubrique): string
    {
        $base = trim($rubrique->code);

        // Récupère tous les codes existants qui commencent par "A."
        $codes = CodeBudget::where('code', 'like', $base . '.%')->pluck('code');

        // Extrait les suffixes numériques et calcule le max
        $max = 0;
        foreach ($codes as $code) {
            // On suppose le format "BASE.NUM" ; on prend la partie après le dernier '.'
            $parts = explode('.', $code);
            $suffix = intval(end($parts));
            if ($suffix > $max) {
                $max = $suffix;
            }
        }

        $next = $max + 1;

        return $base . '.' . $next;
    }

    /**
     * Génère le prochain code pour un CodeBudget donné.
     *
     * Ex: si CodeBudget::code == "A.1" et qu'il existe déjà A.1.1, A.1.2 -> renvoie "A.1.3"
     * Si aucun LigneBudget n'existe pour le code budget -> renvoie "A.1.1"
     *
     * @param  \App\Models\CodeBudget  $codeBudget
     * @return string
     */
    protected function generateCodeBudgetForCodeBudget(CodeBudget $codeBudget): string
    {
        $base = trim($codeBudget->code);
        // Récupère tous les codes existants qui commencent par "A.1."
        $codes = LigneBudget::where('code', 'like', $base . '.%')->pluck('code');

        // Extrait les suffixes numériques et calcule le max
        $max = 0;
        foreach ($codes as $code) {
            // On suppose le format "BASE.NUM" ; on prend la partie après le dernier '.'
            $parts = explode('.', $code);
            $suffix = intval(end($parts));
            if ($suffix > $max) {
                $max = $suffix;
            }
        }

        $next = $max + 1;

        return $base . '.' . $next;
    }
}
