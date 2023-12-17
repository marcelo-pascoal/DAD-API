<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vcard;
use Illuminate\Auth\Access\Response;

class VcardPolicy
{

    public function admin(User $user)
    {
        return $user->user_type == 'A';
    }

    public function view(User $user, Vcard $vcard)
    {
        return $user->id == $vcard->phone_number;
    }

    public function update(User $user, Vcard $vcard)
    {
        return $user->id == $vcard->phone_number;
    }

    public function delete(User $user, Vcard $vcard)
    {
        return ($user->id == $vcard->phone_number || $user->user_type == 'A') && $vcard->balance == 0;
    }
}
