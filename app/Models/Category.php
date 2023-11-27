<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    public $timestamps = false;

    protected $table = 'categories';

    protected $fillable = [
        'vcard',
        'type',
        'name',
    ];

    public function responsible()
    {
        return $this->belongsTo(User::class, 'vcard');
    }
}
