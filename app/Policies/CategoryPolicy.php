<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;
use App\Models\DefaultCategory;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{

    public function admin(User $user)
    {
        return $user->user_type == 'A';
    }

    public function update(User $user, Category $category)
    {
        return $user->id == $category->vcard;
    }

    public function delete(User $user, Category $category)
    {
        return $user->id == $category->vcard;
    }
}
