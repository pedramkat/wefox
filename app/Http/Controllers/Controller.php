<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      x={
 *          "logo": {
 *              "url": "https://theme.zdassets.com/theme_assets/9115960/ef5800cc529889d180b05b57e40dd50e5c7adb73.png"
 *          }
 *      },
 *      title="WEFOX",
 *      description="Api documentation",
 *
 *      @OA\Contact(
 *          email="peramkat@gmail.com"
 *      ),
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="sanctum",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="Token",
 *     description="Enter Sanctum Bearer token **_only_**"
 * )
 *
 *    @OA\Header(
 *     header="Accept",
 *     description="The content type the client is willing to accept in the response",
 *
 *     @OA\Schema(
 *         type="string",
 *         default="application/json",
 *         example="application/json"
 *     )
 * )
 *
 * @OA\Header(
 *     header="Content-Type",
 *     description="The content type of the request body",
 *
 *     @OA\Schema(
 *         type="string",
 *         default="application/vnd.api+json",
 *         example="application/vnd.api+json"
 *     )
 * )
 *
 * @OA\Header(
 *     header="Cache-Control",
 *     description="Specify 'no-store' to ensure responses are not cached",
 *
 *     @OA\Schema(
 *         type="string",
 *         default="no-store",
 *         example="no-store"
 *     )
 * )
 */
class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;

    /**
     * success response method.
     *
     * @param  array<mixed>  $result
     */
    public function sendResponse(array $result, string $message): JsonResponse
    {
        $response = [
            'success' => true,
            'data' => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    /**
     * return error response.
     *
     * @param  mixed  $error
     * @param  mixed  $errorMessages
     * @param  int  $code
     */
    public function sendError($error, $errorMessages = [], $code = 400): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (! empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
