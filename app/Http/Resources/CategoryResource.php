<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $data = [
            'id' => $this->id,
            'vcard' => $this->vcard,
            'name' => $this->name,
        ];

        if ($this->type !== null) {
            $data['type'] = $this->type;
        }

        return $data;
    }
}
