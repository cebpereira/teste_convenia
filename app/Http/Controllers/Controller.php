<?php

namespace App\Http\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     version="1.0",
 *     title="API Documentation",
 *     description="Routes and models for the API",
 *     @OA\Contact(
 *         name="Carlos Elandro",
 *         email="c.elandro.bp@gmail.com"
 *     )
 * ),
 *     @OA\SecurityScheme(
 *         type="http",
 *         description="Login with email and password to get the authentication token",
 *         name="Token based Based",
 *         in="header",
 *         scheme="bearer",
 *         bearerFormat="JWT",
 *         securityScheme="apiAuth",
 *     )
 */
abstract class Controller
{
    //
}
