<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class Movie
 * @package App\Http\Resources
 *
 * @property integer id
 * @property integer stock
 * @property string title
 * @property string description
 * @property boolean availability
 * @property float sale_price
 * @property float rental_price
 */
class Movie extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /**
         * @var App\Models\User  $user
         */
        $user = auth()->user();
        $showAvailability = empty($user)?false: $user->hasRole('admin');
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'rental_price' => $this->rental_price,
            'sale_price' => $this->sale_price,
            'availability' => $this->when($showAvailability,$this->availability),
            'likes_count'  => $this->likes_count,
            'images' => MovieImage::collection($this->whenLoaded("images"))
        ];
    }
}
