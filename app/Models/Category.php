<?php

namespace App\Models;

class Category extends DefaultCategory
{

    protected $table = 'categories';

    protected $fillable = [
        'vcard',
        'type',
        'name',
    ];

    public function responsible()
    {
        return $this->belongsTo(Vcard::class, 'vcard');
    }
}
