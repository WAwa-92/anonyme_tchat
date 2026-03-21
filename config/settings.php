<?php

declare(strict_types=1);

define('DB_HOST', 'localhost');
define('DB_PORT', 8889);
define('DB_NAME', 'anonyme_tchat');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_CHARSET', 'utf8mb4');

define('ROUTES', [
    'home' => ['controller' => App\Controllers\ChatController::class, 'action' => 'home'],
    'salon' => ['controller' => App\Controllers\ChatController::class, 'action' => 'salon'],
    'create-salon' => ['controller' => App\Controllers\ChatController::class, 'action' => 'createSalon'],
    'send-message' => ['controller' => App\Controllers\ChatController::class, 'action' => 'sendMessage'],
    'pin-message' => ['controller' => App\Controllers\ChatController::class, 'action' => 'pinMessage'],
    'about' => ['controller' => App\Controllers\ChatController::class, 'action' => 'about'],
]);
