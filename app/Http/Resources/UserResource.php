<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request)
    {
        $userToArray = [
            'id' => $this->id,
            'user_type' => $this->user_type,
            'name' => $this->name,
            'email' => $this->email,
        ];

        if ($this->user_type == 'V') {
            $userToArray['photo_url'] = $this->photo_url;
            if ($this->vcard->blocked) $userToArray['blocked'] = $this->vcard->blocked;
        }

        return $userToArray;
    }
}
