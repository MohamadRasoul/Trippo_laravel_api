<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;

use App\Models\Plan;

use App\Services\ImageService;
use App\Http\Requests\StorePlanRequest;
use App\Http\Requests\UpdatePlanRequest;
use App\Http\Resources\Mobile\PlanResource;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PlanController extends Controller
{
    /**
     * @OA\Get(
     *    path="/api/mobile/plan/index",
     *    operationId="IndexPlan",
     *    tags={"Plan"},
     *    summary="Get All Plans",
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
     *    @OA\Parameter(
     *        name="filter[city_id]",
     *        example=1,
     *        in="query",
     *        description="filter by featureTitle",
     *        required=false,
     *        @OA\Schema(
     *            type="integer",
     *        )
     *    ),
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
     *              example="this is all plans"
     *           ),
     *           @OA\Property(
     *              property="data",
     *              @OA\Property(
     *                 property="plans",
     *                 type="array",
     *                 @OA\Items(
     *                    type="object",
     *                    ref="#/components/schemas/PlanResource"
     *                 )
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
        $plans = QueryBuilder::for(Plan::public()->latest())
            ->allowedFilters([
                AllowedFilter::exact('city_id'),
            ]);

        return response()->success(
            'this is all Plans',
            [
                "plans" => PlanResource::collection($plans->paginate(request()->perPage ?? $plans->count())),
            ]
        );
    }

    /**
     * @OA\Get(
     *    path="/api/mobile/plan/user/index",
     *    operationId="IndexPlanByUser",
     *    tags={"Plan"},
     *    summary="Get All Plans By User",
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
     *              example="this is all plans"
     *           ),
     *           @OA\Property(
     *              property="data",
     *              @OA\Property(
     *                 property="plans",
     *                 type="object",
     *                 ref="#/components/schemas/PlanResource"
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
    public function indexByUser(Request $request)
    {
        $user = auth('user_api')->user();

        $plans = $user->plans()
            ->when($request->city_id, function ($query) use ($request) {
                $query->where('city_id', $request->city_id);
            })->latest();

        return response()->success(
            'this is all Plans',
            [
                "plans" => PlanResource::collection($plans->paginate(request()->perPage ?? $plans->count())),
            ]
        );
    }

    /**
     * @OA\Post(
     *    path="/api/mobile/plan/store",
     *    operationId="StorePlan",
     *    tags={"Plan"},
     *    summary="Add Plan",
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
     *    @OA\RequestBody(
     *        required=true,
     *        @OA\MediaType(mediaType="application/json",
     *           @OA\Schema(ref="#/components/schemas/StorePlanRequest")
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
     *              example="plan is added success"
     *           ),
     *           @OA\Property(
     *              property="data",
     *                 @OA\Property(
     *                 property="plan",
     *                 type="object",
     *                 ref="#/components/schemas/PlanResource"
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
    public function store(StorePlanRequest $request)
    {
        $plan = Plan::create($request->validated());

        $request->image ?
            (new ImageService)->storeImage(
                model: $plan,
                image: $request->image,
                collection: 'plan',
                customProperties: ['from-user' => true]
            )
            : (new ImageService)->storeStaticImage(
                model: $plan,
                image: 'default.jpg',
                collection: 'plan',
                folderName: 'fallback-images'
            );

        return response()->success(
            'plan is added success',
            [
                "plan" => new PlanResource($plan),
            ]
        );
    }

    /**
     * @OA\Post(
     *    path="/api/mobile/plan/{id}/update",
     *    operationId="UpdatePlan",
     *    tags={"Plan"},
     *    summary="Edit Plan",
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
     *       name="id",
     *       example=1,
     *       in="path",
     *       description="Plan ID",
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
     *           @OA\Schema(ref="#/components/schemas/UpdatePlanRequest")
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
     *              example="plan is updated success"
     *           ),
     *           @OA\Property(
     *              property="data",
     *              @OA\Property(
     *                 property="plan",
     *                 type="object",
     *                 ref="#/components/schemas/PlanResource"
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
    public function update(UpdatePlanRequest $request, Plan $plan)
    {
        $plan->update($request->validated());

        (new ImageService)->storeImage(
            model: $plan,
            image: $request->image,
            collection: 'plan'
        );

        return response()->success(
            'plan is updated success',
            [
                "plan" => new PlanResource($plan),
            ]
        );
    }

    /**
     * @OA\Delete(
     *    path="/api/mobile/plan/{id}/delete",
     *    operationId="DeletePlan",
     *    tags={"Plan"},
     *    summary="Delete Plan By ID",
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
     *        description="Plan ID",
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
     *              example="plan is deleted success"
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
    public function destroy(Plan $plan)
    {
        $plan->delete();

        return response()->success('plan is deleted success');
    }
}
