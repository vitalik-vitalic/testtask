<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class AuthController extends Controller
{
    //  Login API
    /**
     * @OA\Post(
     *     path="/api/login",
     *     operationId="loginUser",
     *     tags={"Login"},
     *     summary="Login a user",
     *     description="User Login Endpoint",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"email","password"},
     *                 @OA\Property(property="email",type="text"),
     *                 @OA\Property(property="password",type="password"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="User Login Successfully",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *       response="200",
     *       description="Login Successfull",
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
    public function login(Request $request)
    {
        if(Auth::attempt($request->only('email', 'password'))){
            $user = Auth::user();

            $token = $user->createToken('admin')->accessToken;

            $cookie = \cookie('jwt', $token, 3600);

            return response([
                'token' => $token,
            ])->withCookie($cookie);
        }

        return response([
            'error' => 'Invalid Credentials'
        ], Response::HTTP_UNAUTHORIZED);
    }

    public function logout()
    {
        $cookie = Cookie::forget('jwt');

        return \response([
            'message' => 'success'
        ])->withCookie($cookie);
    }

    /**
     * @OA\Post(
     *     path="/api/register",
     *     operationId="registerUser",
     *     tags={"Register"},
     *     summary="Register a new user",
     *     description="User Registration Endpoint",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"name","email","password","password_confirmation"},
     *                 @OA\Property(property="name",type="text"),
     *                 @OA\Property(property="email",type="text"),
     *                 @OA\Property(property="password",type="password"),
     *                 @OA\Property(property="password_confirmation",type="password"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *         response="201",
     *         description="User Registered Successfully",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *       response="200",
     *       description="Registered Successfull",
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
    public function register(RegisterRequest $request)
    {
        $user = User::create(
            $request->only('name', 'email')
            + [
                'password' => Hash::make($request->input('password')),
            ]
        );

        return response($user, ResponseAlias::HTTP_CREATED);
    }
}
