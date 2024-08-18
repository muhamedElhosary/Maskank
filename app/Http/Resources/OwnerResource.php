<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OwnerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'owner_id'=>$this->id,
            'username'=>$this->username,
            'owner_name'=>$this->owner_name,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'status'=>$this->status,
            'national id'=>$this->national_id,
            'image'=>url('OwnerPhoto/'.$this->photo),
          ];
    }
}
