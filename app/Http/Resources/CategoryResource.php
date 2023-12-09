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
            'type' => $this->type,
            'name' => $this->name,
        ];

        if ($this->custom_data !== null) {
            $data['icon'] = json_decode($this->custom_data)->icon;
        }

        if ($this->vcard !== null) {
            $data['vcard'] = $this->vcard;
        }

        return $data;
    }
}
