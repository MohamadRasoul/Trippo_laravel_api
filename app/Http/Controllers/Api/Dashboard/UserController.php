<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Requests\ApproveHostRequest;
use App\Http\Requests\RejectHostRequest;
use App\Models\User;

use App\Services\ImageService;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\Auth\UserResource;
use App\Http\Resources\Dashboard\UserResource as DashboardUserResource;

class UserController extends Controller
{
    // /**
    //  * @OA\Get(
    //  *    path="/api/dashboard/user/index",
    //  *    operationId="IndexUser",
    //  *    tags={"User"},
    //  *    summary="Get All Users",
    //  *    description="",
    //  *    security={{"bearerToken":{}}},
    //  *
    //  *
    //  *
    //  *    @OA\Parameter(
    //  *       name="perPage",
    //  *       example=10,
    //  *       in="query",
    //  *       description="Number of item per page",
    //  *       required=false,
    //  *       @OA\Schema(
    //  *           type="integer",
    //  *       )
    //  *    ),
    //  *    @OA\Parameter(
    //  *        name="page",
    //  *        example=1,
    //  *        in="query",
    //  *        description="Page number",
    //  *        required=false,
    //  *        @OA\Schema(
    //  *            type="integer",
    //  *        )
    //  *    ),
    //  *
    //  *
    //  *
    //  *    @OA\Response(
    //  *        response=200,
    //  *        description="Successful operation",
    //  *        @OA\JsonContent(
    //  *           @OA\Property(
    //  *              property="success",
    //  *              type="boolean",
    //  *              example="true"
    //  *           ),
    //  *           @OA\Property(
    //  *              property="message",
    //  *              type="string",
    //  *              example="this is all users"
    //  *           ),
    //  *           @OA\Property(
    //  *              property="data",
    //  *              @OA\Property(
    //  *                 property="users",
    //  *                 type="object",
    //  *                 ref="#/components/schemas/UserResource"
    //  *              ),
    //  *           )
    //  *        ),
    //  *     ),
    //  *
    //  *     @OA\Response(
    //  *        response=401,
    //  *        description="Error: Unauthorized",
    //  *        @OA\Property(
    //  *           property="message",
    //  *           type="string",
    //  *           example="Unauthenticated."
    //  *        ),
    //  *     )
    //  * )
    //  */
    public function index()
    {
        $users = User::orderBy('id');

        return response()->success(
            'this is all Users',
            [
                "users" => UserResource::collection($users->paginate(request()->perPage ?? $users->count())),
            ]
        );
    }


    // /**
    //  * @OA\Post(
    //  *    path="/api/dashboard/user/store",
    //  *    operationId="StoreUser",
    //  *    tags={"User"},
    //  *    summary="Add User",
    //  *    description="",
    //  *    security={{"bearerToken":{}}},
    //  *
    //  *
    //  *
    //  *    @OA\RequestBody(
    //  *        required=true,
    //  *        @OA\MediaType(mediaType="application/json",
    //  *           @OA\Schema(ref="#/components/schemas/StoreUserRequest")
    //  *       )
    //  *    ),
    //  *
    //  *
    //  *
    //  *    @OA\Response(
    //  *        response=200,
    //  *        description="Successful operation",
    //  *        @OA\JsonContent(
    //  *           @OA\Property(
    //  *              property="success",
    //  *              type="boolean",
    //  *              example="true"
    //  *           ),
    //  *           @OA\Property(
    //  *              property="message",
    //  *              type="string",
    //  *              example="user is added success"
    //  *           ),
    //  *           @OA\Property(
    //  *              property="data",
    //  *                 @OA\Property(
    //  *                 property="user",
    //  *                 type="object",
    //  *                 ref="#/components/schemas/UserResource"
    //  *              ),
    //  *           )
    //  *        ),
    //  *     ),
    //  *
    //  *     @OA\Response(
    //  *        response=401,
    //  *        description="Error: Unauthorized",
    //  *        @OA\Property(
    //  *           property="message",
    //  *           type="string",
    //  *           example="Unauthenticated."
    //  *        ),
    //  *     )
    //  * )
    //  */
    public function store(StoreUserRequest $request)
    {
        $user = User::create($request->validated());

        (new ImageService)->storeImage(
            model: $user,
            image: $request->image,
            collection: 'user'
        );

        return response()->success(
            'user is added success',
            [
                "user" => new UserResource($user),
            ]
        );
    }


    // /**
    //  * @OA\Get(
    //  *    path="/api/dashboard/user/{id}/show",
    //  *    operationId="ShowUser",
    //  *    tags={"User"},
    //  *    summary="Get User By ID",
    //  *    description="",
    //  *    security={{"bearerToken":{}}},
    //  *
    //  *
    //  *
    //  *    @OA\Parameter(
    //  *        name="id",
    //  *        example=1,
    //  *        in="path",
    //  *        description="User ID",
    //  *        required=true,
    //  *        @OA\Schema(
    //  *           type="integer"
    //  *        )
    //  *    ),
    //  *
    //  *
    //  *
    //  *    @OA\Response(
    //  *        response=200,
    //  *        description="Successful operation",
    //  *        @OA\JsonContent(
    //  *           @OA\Property(
    //  *              property="success",
    //  *              type="boolean",
    //  *              example="true"
    //  *           ),
    //  *           @OA\Property(
    //  *              property="message",
    //  *              type="string",
    //  *              example="this is your user"
    //  *           ),
    //  *           @OA\Property(
    //  *              property="data",
    //  *                 @OA\Property(
    //  *                 property="user",
    //  *                 type="object",
    //  *                 ref="#/components/schemas/UserResource"
    //  *              ),
    //  *           )
    //  *        ),
    //  *     ),
    //  *
    //  *     @OA\Response(
    //  *        response=401,
    //  *        description="Error: Unauthorized",
    //  *        @OA\Property(
    //  *           property="message",
    //  *           type="string",
    //  *           example="Unauthenticated."
    //  *        ),
    //  *     )
    //  * )
    //  */
    public function show(User $user)
    {
        return response()->success(
            'this is your user',
            [
                "user" => new UserResource($user),
            ]
        );
    }


    // /**
    //  * @OA\Post(
    //  *    path="/api/dashboard/user/{id}/update",
    //  *    operationId="UpdateUser",
    //  *    tags={"User"},
    //  *    summary="Edit User",
    //  *    description="",
    //  *    security={{"bearerToken":{}}},
    //  *
    //  *
    //  *
    //  *    @OA\Parameter(
    //  *       name="id",
    //  *       example=1,
    //  *       in="path",
    //  *       description="User ID",
    //  *       required=true,
    //  *       @OA\Schema(
    //  *           type="integer"
    //  *       )
    //  *    ),
    //  *
    //  *
    //  *
    //  *    @OA\RequestBody(
    //  *        required=true,
    //  *        @OA\MediaType(mediaType="application/json",
    //  *           @OA\Schema(ref="#/components/schemas/UpdateUserRequest")
    //  *       )
    //  *    ),
    //  *
    //  *
    //  *
    //  *    @OA\Response(
    //  *        response=200,
    //  *        description="Successful operation",
    //  *        @OA\JsonContent(
    //  *           @OA\Property(
    //  *              property="success",
    //  *              type="boolean",
    //  *              example="true"
    //  *           ),
    //  *           @OA\Property(
    //  *              property="message",
    //  *              type="string",
    //  *              example="user is updated success"
    //  *           ),
    //  *           @OA\Property(
    //  *              property="data",
    //  *              @OA\Property(
    //  *                 property="user",
    //  *                 type="object",
    //  *                 ref="#/components/schemas/UserResource"
    //  *              ),
    //  *           )
    //  *        ),
    //  *     ),
    //  *     @OA\Response(
    //  *        response=401,
    //  *        description="Error: Unauthorized",
    //  *        @OA\Property(
    //  *           property="message",
    //  *           type="string",
    //  *           example="Unauthenticated."
    //  *        ),
    //  *     )
    //  * )
    //  */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        (new ImageService)->storeImage(
            model: $user,
            image: $request->image,
            collection: 'user'
        );

        return response()->success(
            'user is updated success',
            [
                "user" => new UserResource($user),
            ]
        );
    }

    // /**
    //  * @OA\Delete(
    //  *    path="/api/dashboard/user/{id}/delete",
    //  *    operationId="DeleteUser",
    //  *    tags={"User"},
    //  *    summary="Delete User By ID",
    //  *    description="",
    //  *    security={{"bearerToken":{}}},
    //  *
    //  *
    //  *
    //  *    @OA\Parameter(
    //  *        name="id",
    //  *        example=1,
    //  *        in="path",
    //  *        description="User ID",
    //  *        required=true,
    //  *        @OA\Schema(
    //  *            type="integer"
    //  *        )
    //  *    ),
    //  *
    //  *
    //  *
    //  *    @OA\Response(
    //  *        response=200,
    //  *        description="Successful operation",
    //  *        @OA\JsonContent(
    //  *           @OA\Property(
    //  *              property="success",
    //  *              type="boolean",
    //  *              example="true"
    //  *           ),
    //  *           @OA\Property(
    //  *              property="message",
    //  *              type="string",
    //  *              example="user is deleted success"
    //  *           ),
    //  *        ),
    //  *     ),
    //  *
    //  *     @OA\Response(
    //  *        response=401,
    //  *        description="Error: Unauthorized",
    //  *        @OA\Property(
    //  *           property="message",
    //  *           type="string",
    //  *           example="Unauthenticated."
    //  *        ),
    //  *     )
    //  * )
    //  */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->success('user is deleted success');
    }

    // TODO : add Swagger
    public function getAllRequestHost()
    {
        $users = User::where('is_host', 1)->orderBy('id');
        return response()->success(
            'this is all requests users for host',
            [
                "users" => DashboardUserResource::collection($users->paginate(request()->perPage ?? $users->count())),
            ]
        );
    }

    // TODO : add Swagger
    public function approveRequestHost(ApproveHostRequest $request, User $user)
    {
        $user->update($request->validated());
        return response()->success(
            'approve request host success',
        );
    }


    public function rejectRequestHost(RejectHostRequest $request, User $user)
    {
        $user->update($request->validated());
        return response()->success(
            'reject request host success',
        );
    }
}
