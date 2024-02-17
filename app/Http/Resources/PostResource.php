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
        return [
            'id' => $this->id,
            'parent_post_id' => $this->repost,
            'type' => $this->type_id,
            'title' => $this->header,
            'content' => $this->getContent(),
            'quote_author' => $this->quote_author,
            'views' => $this->view_count,
            'likes' => count($this->likes),
            'reposts'=> count($this->reposts),
            'comments' => count($this->comments),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'author' => new UserResource($this->author),
            'original_author' => new UserResource($this->originalAuthor),
            
        ];
    }

    private function getContent()
    {
        $rules = [
            1 => $this->text_content,
            2 => $this->text_content,
            3 => $this->photo_content,
            4 => $this->video_content,
            5 => $this->link_content  
        ];

        return $rules[$this->type_id];
    }
    
}
