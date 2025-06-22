<?php

namespace App\Http\Resources\Plan;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AllPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'planId' => $this->planId,
            'name' => $this->name,
            'articles' => $this->articles,
            'amount' => $this->amount,
        ];
    }
}
