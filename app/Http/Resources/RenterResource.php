<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RenterResource extends JsonResource

{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
      
        return [
            'renter_id'=>$this->id,
            'username'=>$this->username,
            'renter_name'=>$this->renter_name,
            'email'=>$this->email,
            'phone'=>$this->phone,
            'status'=>$this->status,
            'image'=>url('RenterPhoto/' .$this->photo),
        ];
    }
}
