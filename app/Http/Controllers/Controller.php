<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Discount doc",
 *      description="Discount doc"
 * )
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Login with email and password to get the authentication token",
 *     name="Token based Based",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth",
 * )
 * @OA\PathItem(
 *     path="/"
 * )
 */
class Controller extends BaseController
{
    use ValidatesRequests;
    use AuthorizesRequests {
        resourceAbilityMap as private resourceAbilityMapOwn;
        resourceMethodsWithoutModels as private resourceMethodsWithoutModelsOwn;
    }

    /**
     * Get the map of resource methods to ability names.
     *
     * @return array
     */
    protected function resourceAbilityMap()
    {
        return array_merge($this->resourceAbilityMapOwn(), [
            'shopLogo' => 'shopLogo',
            'activateShop' => 'activateShop',
            'deactivateShop' => 'deactivateShop',
            'shopCount' => 'shopCount',
            'shopCountByCategory' => 'shopCountByCategory',
            'shopProducts' => 'shopProducts',
            'activateUser' => 'activateUser',
            'deactivateUser' => 'deactivateUser',
            'usersWithRoles' => 'usersWithRoles',
            'assignPermission' => 'assignPermission',
        ]);
    }

    /**
     * Get the list of resource methods which do not have model parameters.
     *
     * @return array
     */
    protected function resourceMethodsWithoutModels()
    {
        return array_merge($this->resourceMethodsWithoutModelsOwn(), [
            'shopCountByCategory',
            'shopCount'
        ]);
    }
}
