<?php

namespace App;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "Documentación API Proyecto GrupJJ",
    version: "1.0.0",
    description: "API para la gestión de la tienda online e integración OAuth2",
    contact: new OA\Contact(email: "admin@projectegrupjj.es")
)]
#[OA\Server(
    url: "http://localhost:8000",
    description: "API Server Principal"
)]
#[OA\SecurityScheme(
    securityScheme: "bearerAuth",
    type: "http",
    scheme: "bearer"
)]
class SwaggerMetadata
{
}
