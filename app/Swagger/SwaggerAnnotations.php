<?php
// app/Swagger/SwaggerAnnotations.php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *   title="SI Manajemen Karyawan API",
 *   version="1.0.0",
 *   description="Dokumentasi API Sistem Manajemen Karyawan",
 *   @OA\Contact(
 *     name="Support Team",
 *     email="support@yourdomain.com"
 *   )
 * )
 *
 * @OA\Server(
 *   url=L5_SWAGGER_CONST_HOST,
 *   description="Base URL (sesuaikan di .env SWAGGER_HOST)"
 * )
 */
class SwaggerAnnotations
{
    // kosong, hanya untuk menampung anotasi
}
