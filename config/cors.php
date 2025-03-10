<?php

return [
'paths' => ['api/*', 'auth/*', 'sanctum/csrf-cookie'],
'allowed_methods' => ['*'],
'allowed_origins' => ['http://localhost'], 
'allowed_origins_patterns' => ['*'],
'allowed_headers' => ['*'],
'supports_credentials' => true,
];