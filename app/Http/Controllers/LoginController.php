<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="User login",
     *     description="Authenticate user and generate a bearer token",
     *     tags={"Authentication"},
     *     operationId="authLogin",
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(
     *
     *                 @OA\Property(
     *                     property="email",
     *                     description="User's email",
     *                     type="string",
     *                     example="admin@wefox.it"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     description="User's password",
     *                     type="string",
     *                     example="admin"
     *                 )
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="User login successfully.",
     *
     *         @OA\JsonContent(
     *             type="object",
     *
     *             @OA\Property(
     *                 property="success",
     *                 type="boolean",
     *                 example=true
     *             ),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(
     *                     property="token",
     *                     type="string",
     *                     example="6|yiaRHtOKorrGkmREN1sFYcAc3OUKwypijISPT9w612e62d47"
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     example="Admin"
     *                 ),
     *                 @OA\Property(
     *                     property="email_verified_at",
     *                     type="string",
     *                     format="date-time",
     *                     example="2024-03-17T14:29:50.000000Z"
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 example="User login successfully."
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *
     *         @OA\MediaType(
     *             mediaType="application/json",
     *
     *             @OA\Schema(
     *
     *                 @OA\Property(
     *                     property="success",
     *                     description="Error status",
     *                     type="boolean",
     *                     example="true"
     *                 ),
     *                 @OA\Property(
     *                     property="message",
     *                     description="Error message",
     *                     type="string",
     *                     example="The provided credentials are incorrect."
     *                 )
     *             )
     *         )
     *     )
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string'],
            ]);

            if (Auth::attempt($request->only('email', 'password'))) {
                $user = Auth::user();
                $success['token'] = $user->createToken('access_token')->plainTextToken;
                $success['name'] = $user->name;
                $success['email_verified_at'] = $user->email_verified_at;

                return $this->sendResponse($success, 'User login successfully.');
            }

            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        } catch (Exception $e) {
            return $this->sendError($e->getMessage(), [], 422);
        }
    }
}
