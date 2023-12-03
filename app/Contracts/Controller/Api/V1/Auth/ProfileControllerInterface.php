<?php

namespace App\Contracts\Controller\Api\V1\Auth;


interface ProfileControllerInterface
{
    /**
     * get-me.
     *
     * @return \Illuminate\Http\Response
     * @OA\Get(
     *     path="/api/v1/auth/get-me",
     *     operationId="get-me",
     *     tags={"Profile"},
     *     summary="get-me",
     *     description="get-me",
     *
     *     security={{"bearerAuth":{}}},
     *      @OA\Response(response=200,description="Successful operation"),
     *      @OA\Response(response=201,description="Successful operation"),
     *      @OA\Response(response=202,description="Successful operation"),
     *      @OA\Response(response=204,description="Successful operation"),
     *      @OA\Response(response=400,description="Bad Request"),
     *      @OA\Response(response=401,description="Unauthenticated"),
     *      @OA\Response(response=403,description="Forbidden"),
     *      @OA\Response(response=404,description="Resource Not Found")
     * )
     */
    public function getMe();
}
