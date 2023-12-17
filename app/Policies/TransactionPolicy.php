<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Transaction;

class TransactionPolicy
{

    public function admin(User $user)
    {
        return $user->user_type == 'A';
    }

    public function check(User $user)
    {
        return $user->user_type == 'V';
    }

    public function view(User $user, Transaction $transaction)
    {
        return $user->id == $transaction->vcard;
    }

    public function update(User $user, Transaction $transaction)
    {
        return $user->id == $transaction->vcard;
    }

    public function create(User $user)
    {
        return $user->user_type == 'A';
    }
}
