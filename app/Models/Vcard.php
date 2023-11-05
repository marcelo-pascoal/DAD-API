<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vcard extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $primaryKey = 'phone_number';

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
