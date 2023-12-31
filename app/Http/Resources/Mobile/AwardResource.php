<?php

namespace App\Http\Resources\Mobile;

use App\Http\Resources\ImageResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Stichoza\GoogleTranslate\GoogleTranslate;

/**
 * @OA\Schema(
 *      title="AwardResource",
 *      description="AwardResource body data",
 *      type="object",
 *
 *
 *      @OA\Property(
 *          property="id",
 *          type="string",
 *          example= 1
 *      ),
 *      @OA\Property(
 *          property="name",
 *          type="string",
 *          example= "award name"
 *      ),
 *      @OA\Property(
 *          property="description",
 *          type="string",
 *          example= "award description"
 *      ),
 *      @OA\Property(
 *          property="donor",
 *          type="string",
 *          example= "award donor"
 *      ),
 *      @OA\Property(
 *          property="image",
 *          ref="#/components/schemas/ImageResource"
 *          )
 *       ),
 * )
 */
class AwardResource extends JsonResource
{


    public function toArray($request)
    {
        //        return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            // 'description' => GoogleTranslate::trans($this->description, app()->getLocale()),
            'donor' => $this->donor,
            'image' => new ImageResource($this->getFirstMedia('award')),
        ];
    }
}
