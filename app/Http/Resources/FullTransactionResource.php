<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FullTransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
		    'vcard' => $this->vcard,
            'id' => $this->id,
            'datetime' => $this->datetime,
            'type' => $this->type,
            'value' => $this->value,
            'old_balance' => $this->old_balance,
            'new_balance' => $this->new_balance,
            'payment_type' => $this->payment_type,
            'payment_reference' => $this->payment_reference,
            'category_id' => $this->category_id,
            'description' => $this->description
        ];
    }
}
