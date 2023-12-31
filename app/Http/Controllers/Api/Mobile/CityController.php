<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Enums\ModelEnum;
use App\Events\showModel;
use App\Http\Controllers\Controller;
use App\Http\Resources\ImageResource;
use App\Http\Resources\Mobile\CityResource;
use App\Models\City;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CityController extends Controller
{
    /**
     * @OA\Get(
     *    path="/api/mobile/city/index",
     *    operationId="IndexCity",
     *    tags={"City"},
     *    summary="Get All Cities",
     *    description="",
     *    security={{"bearerToken":{}}},
     *
     *    @OA\Parameter(
     *        name="language",
     *        example="en",
     *        in="header",
     *        description="app language",
     *        required=false,
     *        @OA\Schema(
     *            type="string",
     *        )
     *    ),
     *    
     *    @OA\Parameter(
     *        name="fcmtoken",
     *        example="14265416154646",
     *        in="header",
     *        description="add fcm token to user",
     *        required=false,
     *        @OA\Schema(
     *            type="string",
     *        )
     *    ),
     *
     *
     *
     *    @OA\Parameter(
     *       name="perPage",
     *       example=10,
     *       in="query",
     *       description="Number of item per page",
     *       required=false,
     *       @OA\Schema(
     *           type="integer",
     *       )
     *    ),
     *    @OA\Parameter(
     *        name="page",
     *        example=1,
     *        in="query",
     *        description="Page number",
     *        required=false,
     *        @OA\Schema(
     *            type="integer",
     *        )
     *    ),
     *
     *
     *
     *    @OA\Response(
     *        response=200,
     *        description="Successful operation",
     *        @OA\JsonContent(
     *           @OA\Property(
     *              property="success",
     *              type="boolean",
     *              example="true"
     *           ),
     *           @OA\Property(
     *              property="message",
     *              type="string",
     *              example="this is all cities"
     *           ),
     *           @OA\Property(
     *              property="data",
     *              @OA\Property(
     *                 property="cities",
     *                 type="array",
     *                 @OA\Items(
     *                    type="object",
     *                    ref="#/components/schemas/CityResource"
     *                 ),
     *              ),
     *           )
     *        ),
     *     ),
     *
     *     @OA\Response(
     *        response=401,
     *        description="Error: Unauthorized",
     *        @OA\Property(
     *           property="message",
     *           type="string",
     *           example="Unauthenticated."
     *        ),
     *     )
     * )
     */
    public function index()
    {
        $cities = City::orderBy('id');

        return response()->success(
            'this is all Cities',
            [
                "cities" => CityResource::collection($cities->paginate(request()->perPage ?? $cities->count())),
            ]
        );
    }


    /**
     * @OA\Get(
     *    path="/api/mobile/city/indexTrending",
     *    operationId="IndexTrendingCity",
     *    tags={"City"},
     *    summary="Get All Cities Sort By Views",
     *    description="",
     *    security={{"bearerToken":{}}},
     *
     *    @OA\Parameter(
     *        name="language",
     *        example="en",
     *        in="header",
     *        description="app language",
     *        required=false,
     *        @OA\Schema(
     *            type="string",
     *        )
     *    ),
     *    
     *    @OA\Parameter(
     *        name="fcmtoken",
     *        example="14265416154646",
     *        in="header",
     *        description="add fcm token to user",
     *        required=false,
     *        @OA\Schema(
     *            type="string",
     *        )
     *    ),
     *
     *
     *
     *    @OA\Parameter(
     *       name="perPage",
     *       example=10,
     *       in="query",
     *       description="Number of item per page",
     *       required=false,
     *       @OA\Schema(
     *           type="integer",
     *       )
     *    ),
     *    @OA\Parameter(
     *        name="page",
     *        example=1,
     *        in="query",
     *        description="Page number",
     *        required=false,
     *        @OA\Schema(
     *            type="integer",
     *        )
     *    ),
     *
     *
     *
     *    @OA\Response(
     *        response=200,
     *        description="Successful operation",
     *        @OA\JsonContent(
     *           @OA\Property(
     *              property="success",
     *              type="boolean",
     *              example="true"
     *           ),
     *           @OA\Property(
     *              property="message",
     *              type="string",
     *              example="this is all cities"
     *           ),
     *           @OA\Property(
     *              property="data",
     *              @OA\Property(
     *                 property="cities",
     *                 type="array",
     *                 @OA\Items(
     *                    type="object",
     *                    ref="#/components/schemas/CityResource"
     *                 ),
     *              ),
     *           )
     *        ),
     *     ),
     *
     *     @OA\Response(
     *        response=401,
     *        description="Error: Unauthorized",
     *        @OA\Property(
     *           property="message",
     *           type="string",
     *           example="Unauthenticated."
     *        ),
     *     )
     * )
     */
    public function indexTrending()
    {
        $cities = City::orderBy('views', 'desc');

        return response()->success(
            'this is all Cities Sort By Views',
            [
                "cities" => CityResource::collection($cities->paginate(request()->perPage ?? $cities->count())),
            ]
        );
    }


    /**
     * @OA\Get(
     *    path="/api/mobile/city/{id}/image/index",
     *    operationId="indexCityImage",
     *    tags={"City"},
     *    summary="Get All City Image",
     *    description="",
     *    security={{"bearerToken":{}}},
     *
     *    @OA\Parameter(
     *        name="language",
     *        example="en",
     *        in="header",
     *        description="app language",
     *        required=false,
     *        @OA\Schema(
     *            type="string",
     *        )
     *    ),
     *    
     *    @OA\Parameter(
     *        name="fcmtoken",
     *        example="14265416154646",
     *        in="header",
     *        description="add fcm token to user",
     *        required=false,
     *        @OA\Schema(
     *            type="string",
     *        )
     *    ),
     *
     *
     *
     *    @OA\Parameter(
     *        name="id",
     *        example=1,
     *        in="path",
     *        description="City ID",
     *        required=true,
     *        @OA\Schema(
     *           type="integer"
     *        )
     *    ),
     *
     *
     *
     *    @OA\Response(
     *        response=200,
     *        description="Successful operation",
     *        @OA\JsonContent(
     *           @OA\Property(
     *              property="success",
     *              type="boolean",
     *              example="true"
     *           ),
     *           @OA\Property(
     *              property="message",
     *              type="string",
     *              example="this is all images for city"
     *           ),
     *          @OA\Property(
     *              property="data",
     *              @OA\Property(
     *                 property="images",
     *                 type="array",
     *                 @OA\Items(
     *                    type="object",
     *                    ref="#/components/schemas/ImageResource"
     *                 ),
     *              ),
     *           )
     *           
     *        ),
     *     ),
     *
     *     @OA\Response(
     *        response=401,
     *        description="Error: Unauthorized",
     *        @OA\Property(
     *           property="message",
     *           type="string",
     *           example="Unauthenticated."
     *        ),
     *     )
     * )
     */
    public function indexImage(City $city)
    {
        $cityImage = $city->getMedia('city')->flatten();
        $cityImageAdmin = $city->getMedia('city_admin')->flatten();
        $cityImageUser = count($city->getMedia('city_user', ['isAccept' => true])) > 0 ? $city->getMedia('city_user', ['isAccept' => true])->flatten() : [];
        $images = $cityImage->merge($cityImageAdmin)->merge($cityImageUser);

        return response()->success(
            'this is all images for city',
            [
                "images" => ImageResource::collection($images),
            ]
        );
    }


    /**
     * @OA\Get(
     *    path="/api/mobile/city/{id}/show",
     *    operationId="ShowCity",
     *    tags={"City"},
     *    summary="Get City By ID",
     *    description="",
     *    security={{"bearerToken":{}}},
     *
     *    @OA\Parameter(
     *        name="language",
     *        example="en",
     *        in="header",
     *        description="app language",
     *        required=false,
     *        @OA\Schema(
     *            type="string",
     *        )
     *    ),
     *    
     *    @OA\Parameter(
     *        name="fcmtoken",
     *        example="14265416154646",
     *        in="header",
     *        description="add fcm token to user",
     *        required=false,
     *        @OA\Schema(
     *            type="string",
     *        )
     *    ),
     *
     *
     *
     *    @OA\Parameter(
     *        name="id",
     *        example=1,
     *        in="path",
     *        description="City ID",
     *        required=true,
     *        @OA\Schema(
     *           type="integer"
     *        )
     *    ),
     *
     *
     *
     *    @OA\Response(
     *        response=200,
     *        description="Successful operation",
     *        @OA\JsonContent(
     *           @OA\Property(
     *              property="success",
     *              type="boolean",
     *              example="true"
     *           ),
     *           @OA\Property(
     *              property="message",
     *              type="string",
     *              example="this is your city"
     *           ),
     *           @OA\Property(
     *              property="data",
     *                 @OA\Property(
     *                 property="city",
     *                 type="object",
     *                 ref="#/components/schemas/CityResource"
     *              ),
     *           )
     *        ),
     *     ),
     *
     *     @OA\Response(
     *        response=401,
     *        description="Error: Unauthorized",
     *        @OA\Property(
     *           property="message",
     *           type="string",
     *           example="Unauthenticated."
     *        ),
     *     )
     * )
     */
    public function show(City $city)
    {
        event(new showModel($city, ModelEnum::City));

        return response()->success(
            'this is your city',
            [
                "city" => new CityResource($city),
            ]
        );
    }


    /**
     * @OA\Post(
     *    path="/api/mobile/city/{cityId}/image/store",
     *    operationId="AddImageToCity",
     *    tags={"City"},
     *    summary="Add Image To City",
     *    description="",
     *    security={{"bearerToken":{}}},
     *
     *    @OA\Parameter(
     *        name="language",
     *        example="en",
     *        in="header",
     *        description="app language",
     *        required=false,
     *        @OA\Schema(
     *            type="string",
     *        )
     *    ),
     *    
     *    @OA\Parameter(
     *        name="fcmtoken",
     *        example="14265416154646",
     *        in="header",
     *        description="add fcm token to user",
     *        required=false,
     *        @OA\Schema(
     *            type="string",
     *        )
     *    ),
     *
     *
     *
     *    @OA\Parameter(
     *        name="cityId",
     *        example=1,
     *        in="path",
     *        description="City ID",
     *        required=true,
     *        @OA\Schema(
     *           type="integer"
     *        )
     *    ),
     *
     *
     *    @OA\RequestBody(
     *        required=true,
     *        @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                required={"image"},
     *                 @OA\Property(
     *                     description="image to upload",
     *                     property="image",
     *                     type="string",
     *                     example="image.png",
     *                ),
     *             )
     *         )
     *    ),
     *
     *
     *
     *    @OA\Response(
     *        response=200,
     *        description="Successful operation",
     *        @OA\JsonContent(
     *           @OA\Property(
     *              property="success",
     *              type="boolean",
     *              example="true"
     *           ),
     *           @OA\Property(
     *              property="message",
     *              type="string",
     *              example="city is added success"
     *           ),
     *           @OA\Property(
     *              property="data",
     *                 @OA\Property(
     *                 property="city",
     *                 type="object",
     *                 ref="#/components/schemas/CityResource"
     *              ),
     *           )
     *        ),
     *     ),
     *
     *     @OA\Response(
     *        response=401,
     *        description="Error: Unauthorized",
     *        @OA\Property(
     *           property="message",
     *           type="string",
     *           example="Unauthenticated."
     *        ),
     *     )
     * )
     */
    public function addImage(Request $request, City $city)
    {

        (new ImageService)->storeImage(
            model: $city,
            image: $request->image,
            collection: 'city_user',
            customProperties: ['isAccept' => false]
        );



        return response()->success(
            'image for city is added success',
            [
                "city" => new CityResource($city),
            ]
        );
    }
}
