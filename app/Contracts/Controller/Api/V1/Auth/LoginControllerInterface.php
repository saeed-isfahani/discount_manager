<?php

namespace App\Contracts\Controller\Api\V1\Auth;

use App\Http\Requests\Auth\LoginCheckVerifyRequest;
use App\Http\Requests\Auth\LoginSendVerifyRequest;

interface LoginControllerInterface
{
    /**
     * Send a Mobile Verification to user
     *
     * @return \Illuminate\Http\Response
     * @OA\Post(
     *     path="/api/v1/auth/login/send-verify",
     *     operationId="login_send_verify_request",
     *     tags={"Login"},
     *     summary="Send verification request code",
     *     description="Send verification request code",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *                  type="object",
     *                  required={"mobile"},
     *                  @OA\Property(property="mobile", type="text"),
     *            ),
     *        ),
     *    ),
     *
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200,description="Successful operation"),
     *      @OA\Response(response=201,description="Successful operation"),
     *      @OA\Response(response=202,description="Successful operation"),
     *      @OA\Response(response=204,description="Successful operation"),
     *      @OA\Response(response=400,description="Bad Request"),
     *      @OA\Response(response=401,description="MobileUnauthenticated"),
     *      @OA\Response(response=403,description="Forbidden"),
     *      @OA\Response(response=404,description="Resource Not Found")
     * )
     */
    public function sendVerify(LoginSendVerifyRequest $request);

    /**
     * Check Mobile Verification code was sent to user
     *
     * @return \Illuminate\Http\Response
     * @OA\Post(
     *     path="/api/v1/auth/login/check-verify",
     *     operationId="login_check_verify_request",
     *     tags={"Login"},
     *     summary="Check verification request code was sent to user",
     *     description="Check verification request code was sent to user",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *                  type="object",
     *                  required={"mobile","code"},
     *                  @OA\Property(property="mobile", type="text"),
     *                  @OA\Property(property="code", type="text"),
     *            ),
     *        ),
     *    ),
     *
     *      security={{"bearerAuth":{}}},
     *      @OA\Response(response=200,description="Successful operation"),
     *      @OA\Response(response=201,description="Successful operation"),
     *      @OA\Response(response=202,description="Successful operation"),
     *      @OA\Response(response=204,description="Successful operation"),
     *      @OA\Response(response=400,description="Bad Request"),
     *      @OA\Response(response=401,description="MobileUnauthenticated"),
     *      @OA\Response(response=403,description="Forbidden"),
     *      @OA\Response(response=404,description="Resource Not Found")
     * )
     */
    public function checkVerify(LoginCheckVerifyRequest $request);
}
