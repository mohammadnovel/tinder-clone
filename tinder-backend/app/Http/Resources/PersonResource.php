<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PersonResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Calculate distance if user coordinates are provided
        $distance = null;
        if ($request->has('latitude') && $request->has('longitude')) {
            $distance = $this->distanceFrom(
                (float) $request->latitude,
                (float) $request->longitude
            );
        } else {
            // Random distance for demo
            $distance = rand(1, 50);
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'age' => $this->age,
            'pictures' => $this->pictures,
            'location' => $this->location,
            'bio' => $this->bio,
            'distance' => $distance,
            'created_at' => $this->created_at?->toISOString(),
        ];
    }
}
