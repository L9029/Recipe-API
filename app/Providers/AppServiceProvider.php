<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate; // Se Importa la clase Gate para definir políticas de autorización
use App\Models\Recipe;
use App\Policies\RecipePolicy;


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
        // Se define la política de autorización para el modelo Recipe
        Gate::policy(Recipe::class, RecipePolicy::class);
    }
}
