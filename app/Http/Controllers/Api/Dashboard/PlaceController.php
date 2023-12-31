<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePlaceRequest;
use App\Http\Requests\UpdatePlaceRequest;
use App\Http\Resources\Dashboard\PlaceResource;
use App\Http\Resources\ImageResource;
use App\Models\Place;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class PlaceController extends Controller
{
    /**
     * @OA\Get(
     *    path="/api/dashboard/place/index",
     *    operationId="IndexPlace",
     *    tags={"Place"},
     *    summary="Get All Places",
     *    description="",
     *    security={{"bearerToken":{}}},
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
     *              example="this is all places"
     *           ),
     *           @OA\Property(
     *              property="data",
     *              @OA\Property(
     *                 property="places",
     *                 type="array",
     *                  @OA\Items(
     *                     type="object",
     *                     ref="#/components/schemas/PlaceResource"
     *                  )
     *              ),
     *              @OA\Property(
     *                 property="total",
     *                 type="string",
     *                 example="5"
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
        $places = Place::orderBy('id');


        $placePaginated = $places->paginate(request()->perPage ?? $places->count());

        return response()->success(
            'this is all Places',
            [
                "places" => PlaceResource::collection($placePaginated),
                "total" => $placePaginated->lastPage(),
            ]
        );
    }


    /**
     * @OA\Get(
     *    path="/api/dashboard/place/{id}/image/index",
     *    operationId="indexPlaceImage",
     *    tags={"Place"},
     *    summary="Get All Place Image",
     *    description="",
     *    security={{"bearerToken":{}}},
     *
     *
     *
     *    @OA\Parameter(
     *        name="id",
     *        example=1,
     *        in="path",
     *        description="Place ID",
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
     *              example="this is all images for place"
     *           ),
     *           @OA\Property(
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
    public function indexImage(Place $place)
    {
        $placeImage = $place->getMedia('place')->flatten();
        $placeImageAdmin = $place->getMedia('place_admin')->flatten();
        $placeImageUser = count($place->getMedia('place_user', ['isAccept' => true])) > 0 ? $place->getMedia('place_user', ['isAccept' => true])->random()->flatten() : collect();
        $images = $placeImage->merge($placeImageAdmin)->merge($placeImageUser);

        return response()->success(
            'this is all images for place',
            [
                "images" => ImageResource::collection($images),
            ]
        );
    }


    /**
     * @OA\Get(
     *    path="/api/dashboard/place/{id}/image/indexNotAccept",
     *    operationId="indexPlaceImageNotAccept",
     *    tags={"Place"},
     *    summary="Get All Place ImageNotAccept",
     *    description="",
     *    security={{"bearerToken":{}}},
     *
     *
     *
     *    @OA\Parameter(
     *        name="id",
     *        example=1,
     *        in="path",
     *        description="Place ID",
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
     *              example="this is all images for place"
     *           ),
     *           @OA\Property(
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
    public function indexImageNotAccept(Place $place)
    {

        $placeImageUserNotAccept = $place->getMedia('place_user', ['isAccept' => false])->flatten();

        return response()->success(
            'this is all images for place',
            [
                "images" => ImageResource::collection($placeImageUserNotAccept),
            ]
        );
    }


    /**
     * @OA\Get(
     *    path="/api/dashboard/place/{id}/show",
     *    operationId="ShowPlace",
     *    tags={"Place"},
     *    summary="Get Place By ID",
     *    description="",
     *    security={{"bearerToken":{}}},
     *
     *
     *
     *    @OA\Parameter(
     *        name="id",
     *        example=1,
     *        in="path",
     *        description="Place ID",
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
     *              example="this is your place"
     *           ),
     *           @OA\Property(
     *              property="data",
     *                 @OA\Property(
     *                 property="place",
     *                 type="object",
     *                 ref="#/components/schemas/PlaceResource"
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
    public function show(Place $place)
    {
        return response()->success(
            'this is your place',
            [
                "place" => new PlaceResource($place),
            ]
        );
    }


    /**
     * @OA\Post(
     *    path="/api/dashboard/place/store",
     *    operationId="StorePlace",
     *    tags={"Place"},
     *    summary="Add Place",
     *    description="",
     *    security={{"bearerToken":{}}},
     *
     *
     *
     *    @OA\RequestBody(
     *        required=true,
     *        @OA\MediaType(mediaType="application/json",
     *           @OA\Schema(ref="#/components/schemas/StorePlaceRequest"),
     *       )
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
     *              example="place is added success"
     *           ),
     *           @OA\Property(
     *              property="data",
     *                 @OA\Property(
     *                 property="place",
     *                 type="object",
     *                 ref="#/components/schemas/PlaceResource"
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
    public function store(StorePlaceRequest $request)
    {
        $place = Place::create($request->validated());

        $place->features()->sync(request()->features);
        $place->options()->sync(request()->options);
        $place->awards()->sync(request()->awards);

        (new ImageService)->storeImage(
            model: $place,
            image: $request->image,
            collection: 'place'
        );

        return response()->success(
            'place is added success',
            [
                "place" => new PlaceResource($place),
            ]
        );
    }


    /**
     * @OA\Post(
     *    path="/api/dashboard/place/{id}/image/store",
     *    operationId="AddImageToPlace",
     *    tags={"Place"},
     *    summary="Add Image To Place",
     *    description="",
     *    security={{"bearerToken":{}}},
     *
     *
     *
     *    @OA\Parameter(
     *        name="id",
     *        example=1,
     *        in="path",
     *        description="Place ID",
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
     *              example="image for place is added success"
     *           ),
     *           @OA\Property(
     *              property="data",
     *                 @OA\Property(
     *                 property="place",
     *                 type="object",
     *                 ref="#/components/schemas/PlaceResource"
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
    public function addImage(Request $request, Place $place)
    {

        (new ImageService)->storeImage(
            model: $place,
            image: $request->image,
            collection: 'place_admin',
        );

        return response()->success(
            'image for place is added success',
            [
                "place" => new PlaceResource($place),
            ]
        );
    }


    /**
     * @OA\Post(
     *    path="/api/dashboard/place/image/{imageId}/accept",
     *    operationId="AcceptPlaceImage",
     *    tags={"Place"},
     *    summary="Accept Place Image",
     *    description="",
     *    security={{"bearerToken":{}}},
     *
     * 
     *    @OA\Parameter(
     *        name="imageId",
     *        example=1,
     *        in="path",
     *        description="Image ID",
     *        required=true,
     *        @OA\Schema(
     *            type="integer"
     *        )
     *    ),
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
     *              example="image is accept success"
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
    public function acceptImage(Media $image)
    {
        // $image->forgetCustomProperty('isAccept');
        $image->setCustomProperty('isAccept', true);
        $image->save();

        return response()->success(
            "image is accept success"
        );
    }

    /**
     * @OA\Post(
     *    path="/api/dashboard/place/{id}/update",
     *    operationId="UpdatePlace",
     *    tags={"Place"},
     *    summary="Edit Place",
     *    description="",
     *    security={{"bearerToken":{}}},
     *
     *
     *
     *    @OA\Parameter(
     *       name="id",
     *       example=1,
     *       in="path",
     *       description="Place ID",
     *       required=true,
     *       @OA\Schema(
     *           type="integer"
     *       )
     *    ),
     *
     *
     *
     *    @OA\RequestBody(
     *        required=true,
     *        @OA\MediaType(mediaType="application/json",
     *           @OA\Schema(ref="#/components/schemas/UpdatePlaceRequest")
     *       )
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
     *              example="place is updated success"
     *           ),
     *           @OA\Property(
     *              property="data",
     *              @OA\Property(
     *                 property="place",
     *                 type="object",
     *                 ref="#/components/schemas/PlaceResource"
     *              ),
     *           )
     *        ),
     *     ),
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
    public function update(UpdatePlaceRequest $request, Place $place)
    {
        $place->update($request->validated());

        (new ImageService)->storeImage(
            model: $place,
            image: $request->image,
            collection: 'place'
        );

        return response()->success(
            'place is updated success',
            [
                "place" => new PlaceResource($place),
            ]
        );
    }

    /**
     * @OA\Delete(
     *    path="/api/dashboard/place/{id}/delete",
     *    operationId="DeletePlace",
     *    tags={"Place"},
     *    summary="Delete Place By ID",
     *    description="",
     *    security={{"bearerToken":{}}},
     *
     *
     *
     *    @OA\Parameter(
     *        name="id",
     *        example=1,
     *        in="path",
     *        description="Place ID",
     *        required=true,
     *        @OA\Schema(
     *            type="integer"
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
     *              example="place is deleted success"
     *           ),
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
    public function destroy(Place $place)
    {
        $place->delete();

        return response()->success('place is deleted success');
    }
}
