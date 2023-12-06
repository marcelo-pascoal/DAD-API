<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//use Laravel\Sanctum\HasApiTokens;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'view_auth_users';

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function save(array $options = [])
    {
        $this->setTable('users');
        return parent::save($options);
    }

    public function delete()
    {
        $this->setTable('users');
        $deleted = parent::delete();
        return $deleted;
    }

    public function findForPassport(string $username): User
    {
        return $this->where('username', $username)->first();
    }

    public function vcard()
    {
        return $this->hasOne(Vcard::class, 'phone_number', 'id');
    }
}
