<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Recipe;
use Illuminate\Auth\Access\HandlesAuthorization;

class RecipePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Determina si el usuario puede ver la receta.
     * 
     * @param  \App\Models\User  $user
     * @param  \App\Models\Recipe  $recipe
     * @return bool
     */
    public function pass(User $user, Recipe $recipe) : bool
    {
        // Verifica si el usuario es el propietario de la receta
        return $user->id === $recipe->user_id;
    }
}
