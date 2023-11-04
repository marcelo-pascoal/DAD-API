<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vcard extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'phone_number',
        'name',
        'email',
        'photo_url',
        'blocked',
        'balance',
        'max_debit',
    ];

    protected $hidden = [
        'password',
        'confirmation_code',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'remember_token' => 'hashed',
    ];
}
