<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'], // asegura que tus rutas estén cubiertas

    'allowed_methods' => ['*'],

    'allowed_origins' => ['http://localhost:3000'], // Nuxt frontend

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // importante si usas cookies o tokens
];
