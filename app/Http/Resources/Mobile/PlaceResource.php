<?php

namespace App\Http\Resources\Mobile;

use App\Http\Resources\ImageResource;
use App\Models\FavouritePlace;
use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

/**
 * @OA\Schema(
 *      title="PlaceResource",
 *      description="PlaceResource body data",
 *      type="object",
 *
 *
 *
 *      @OA\Property(
 *          property="id",
 *          type="string",
 *          example=1
 *      ),
 *      @OA\Property(
 *          property="name",
 *          type="string",
 *          example="Green Table"
 *      ),
 *      @OA\Property(
 *          property="about",
 *          type="string",
 *          example="best coffe to enjoy with your friends"
 *      ),
 *      @OA\Property(
 *          property="address",
 *          type="string",
 *          example="address - address - address"
 *      ),
 *      @OA\Property(
 *          property="latitude",
 *          type="string",
 *          example="33.65166"
 *      ),
 *      @OA\Property(
 *          property="longitude",
 *          type="string",
 *          example="36.5165"
 *      ),
 *      @OA\Property(
 *          property="ratting",
 *          type="string",
 *          example=5
 *      ),
 *      @OA\Property(
 *          property="views",
 *          type="string",
 *          example=59
 *      ),
 *      @OA\Property(
 *          property="web_site",
 *          type="string",
 *          example="www.web_site.com"
 *      ),
 *      @OA\Property(
 *          property="phone_number",
 *          type="string",
 *          example="0958600569"
 *      ),
 *      @OA\Property(
 *          property="email",
 *          type="string",
 *          example="email@email.com"
 *      ),
 *      @OA\Property(
 *          property="open_at",
 *          type="string",
 *          example="10:00:00"
 *      ),
 *      @OA\Property(
 *          property="close_at",
 *          type="string",
 *          example="22:00:00"
 *      ),
 *      @OA\Property(
 *          property="created_at",
 *          type="string",
 *          example="2022-06-06 17:38:54"
 *      ),
 *
 *      @OA\Property(
 *         property="images",
 *         type="array",
 *         @OA\Items(
 *            type="object",
 *            ref="#/components/schemas/ImageResource"
 *         )
 *      ),
 *
 * )
 */
class PlaceResource extends JsonResource
{

    public function toArray($request)
    {

        $placeImage = $this->getMedia('place')->flatten();
        $placeImageAdmin = $this->getMedia('place_admin')->flatten();
        $images = $placeImage->merge($placeImageAdmin);
        $time_now = Carbon::now()->format('H:i:s');
        if ($time_now >= $this->open_at && $time_now <= $this->close_at) {
            $is_open = true;
        } else {
            $is_open = false;
        }
        $favourite = FavouritePlace::where('place_id',$this->id)->count();
        if ($favourite > 0) {
           $is_favourite = true;
        }
        else {
            $is_favourite = false;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'about' => $this->about,
            'address' => $this->address,
            'latitude' => (double)$this->latitude,
            'longitude' => (double)$this->longitude,
            'ratting' => $this->ratting,
            // Todo
            'ratting_count' => rand(70, 2000),
            'views' => $this->views,
            'web_site' => $this->web_site,
            'phone_number' => $this->phone_number,
            'email' => $this->email,
            'is_open' => $is_open,
            'is_favourite' => $is_favourite,
            'open_at' => $this->open_at,
            'close_at' => $this->close_at,
            'created_at' => $this->created_at,
            'city'      => $this->city->name,
            'type'      => $this->type->name,
            "images"       => ImageResource::collection($images),
            "awards"       => AwardResource::collection($this->awards),
        ];
    }
}
