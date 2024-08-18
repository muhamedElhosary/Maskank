<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FavoriteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            // 'postId'=> $this->post_id,
            // 'renterId'=> $this->renter_id,
            'post' => $this->post ? new PostResource($this->post) : null,
            'renter' => $this->renter ? new RenterResource($this->renter) : null,

        ];
    }
}
