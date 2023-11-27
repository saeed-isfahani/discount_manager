<?php

namespace App\Contracts\Controller\Api\V1\Auth;

use App\Http\Requests\Auth\RegisterCheckVerifyRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\RegisterSendVerifyRequest;

interface RegisterControllerInterface
{
    /**
     * Send a Mobile Verification to user
     *
     * @return \Illuminate\Http\Response
     * @OA\Post(
     *     path="/api/v1/auth/register/send-verify",
     *     operationId="send_verify_request",
     *     tags={"Send Verify Request"},
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
    public function sendVerify(RegisterSendVerifyRequest $request);

    /**
     * Check Mobile Verification code was sent to user
     *
     * @return \Illuminate\Http\Response
     * @OA\Post(
     *     path="/api/v1/auth/register/check-verify",
     *     operationId="check_verify_request",
     *     tags={"Check Verify Request"},
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
    public function checkVerify(RegisterCheckVerifyRequest $request);

    /**
     * Check Mobile Verification code was sent to user
     *
     * @return \Illuminate\Http\Response
     * @OA\Post(
     *     path="/api/v1/auth/register",
     *     operationId="register",
     *     tags={"Register"},
     *     summary="Register user",
     *     description="Register user",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *                  type="object",
     *                  required={"code","first_name","last_name","mobile"},
     *                  @OA\Property(property="code", type="text"),
     *                  @OA\Property(property="first_name", type="text"),
     *                  @OA\Property(property="last_name", type="text"),
     *                  @OA\Property(property="email", type="text"),
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
    public function register(RegisterRequest $request);
}
