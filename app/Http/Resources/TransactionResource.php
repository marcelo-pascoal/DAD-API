<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'datetime' => $this->datetime,
            'type' => $this->type,
            'value' => $this->value,
            'new_balance' => $this->new_balance,
			'payment_type' => $this->payment_type,
			'category_id' => $this->category_id,
			'pair_vcard' => $this->pair_vcard,
        ];
    }
}
