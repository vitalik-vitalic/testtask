<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class UserController extends Controller
{
    //  User API
    /**
     * @OA\Get(
     *     path="/api/users",
     *     operationId="getUsers",
     *     tags={"Users"},
     *     summary="Get users",
     *     description="Get All Users",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *
     *             ),
     *         ),
     *     ),     *
     *     @OA\Response(
     *       response="200",
     *       description="Users",
     *       @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Unprocessable Entity",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad Request",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function index()
    {
        $users = User::paginate();

        return UserResource::collection($users);
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     operationId="getUser",
     *     tags={"Users"},
     *     summary="Get user",
     *     description="Get User",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *
     *             ),
     *         ),
     *     ),     *
     *     @OA\Response(
     *       response="200",
     *       description="Users",
     *       @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Unprocessable Entity",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad Request",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function show($id)
    {
        $user = User::find($id);

        return new UserResource($user);
    }

    /**
     * @OA\Post(
     *     path="/api/users",
     *     operationId="postUser",
     *     tags={"Users"},
     *     summary="Post user",
     *     description="Post User",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *
     *             ),
     *         ),
     *     ),     *
     *     @OA\Response(
     *       response="201",
     *       description="User created",
     *       @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Unprocessable Entity",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad Request",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function store(UserCreateRequest $request)
    {
        $user = User::create($request->only('name', 'email') +
                [
                    'password' => Hash::make(12345678),
                ]
                );
        return response(new UserResource($user), ResponseAlias::HTTP_CREATED);
    }

    /**
     * @OA\Put(
     *     path="/api/users/{id}",
     *     operationId="putUser",
     *     tags={"Users"},
     *     summary="Put user",
     *     description="Put User",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *
     *             ),
     *         ),
     *     ),     *
     *     @OA\Response(
     *       response="202",
     *       description="User updated",
     *       @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Unprocessable Entity",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad Request",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::find($id);

        $user->update($request->only('name', 'email'));

        return response(new UserResource($user), ResponseAlias::HTTP_ACCEPTED);
    }

    /**
     * @OA\Delete(
     *     path="/api/users/{id}",
     *     operationId="deleteUser",
     *     tags={"Users"},
     *     summary="Delete user",
     *     description="Delete User",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *
     *             ),
     *         ),
     *     ),     *
     *     @OA\Response(
     *       response="204",
     *       description="User deleted",
     *       @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Unprocessable Entity",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="400",
     *         description="Bad Request",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function destroy($id)
    {
        User::destroy($id);
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
