<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $imageList = [];


        $imagesPost = explode(',', $this->images );
        // array_pop($imagesPost);

        foreach ($imagesPost as $image) {

            $imageList[] = url('imagesPosts/' .$image);
        }

        return [
            'id'=>$this->id,
            'images'=>$imageList,
            'description'=>$this->description,
            'price'=>$this->price,
            'size'=>$this->size,
            'purpose'=>$this->purpose,
            'bedrooms'=>$this->bedrooms,
            'bathrooms'=>$this->bathrooms,
            'region'=>$this->region,
            'city'=>$this->city,
            'floor'=>$this->floor,
            'condition'=>$this->condition,
            'status'=>$this->status,
            'booked' => $this->booked,
            // 'ownerId' => $this->owner_id,
            'owner'=>   $this->owner ? new OwnerResource($this->owner) : null,

        ];
    }
}
