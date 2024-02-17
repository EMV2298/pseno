<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class UserProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'image' => $this->image,
            'amount_subscribers' => count($this->subscribers),
            'amount_posts' => count($this->posts),
            'subscribe' => count($this->subscribers->where('follower_id', Auth::id())) ? true : false,
            'created_at' => $this->created_at,
            'last_online' => $this->updated_at,            
        ];
    }
}
