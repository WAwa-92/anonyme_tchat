<?php

use Controllers\ChatController;

define('DB_HOST', 'localhost');
define('DB_PORT', 8889);
define('DB_NAME', 'anonyme_tchat');
define('DB_USER', 'root');
define('DB_PASS', 'root');

define('AVAILABLE_ROUTES', [
    'home'         => ['controller' => ChatController::class, 'method' => 'home'],
    'salon'        => ['controller' => ChatController::class, 'method' => 'salon'],
    'create-salon' => ['controller' => ChatController::class, 'method' => 'createSalon'],
    'send-message' => ['controller' => ChatController::class, 'method' => 'sendMessage'],
    'pin-message'  => ['controller' => ChatController::class, 'method' => 'pinMessage'],
    'about'        => ['controller' => ChatController::class, 'method' => 'about'],
]);
