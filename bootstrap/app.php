<?php

use Starfish\Infrastructure\Connection;
use Starfish\Infrastructure\Database;

try {

    // Load .env if using vlucas/phpdotenv
    $dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__, 1));
    $dotenv->load();

} catch (Exception $e) {

    die("Error: " . $e->getMessage());
}
