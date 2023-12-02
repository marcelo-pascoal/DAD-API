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

        // Check if 'type' is not null before adding it to the response
        if ($this->type !== null) {
            $data['type'] = $this->type;
        }

        return $data;
    }
}
