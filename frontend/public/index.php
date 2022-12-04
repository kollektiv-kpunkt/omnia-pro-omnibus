<?php
require __DIR__ . "/../vendor/autoload.php";
use Pecee\SimpleRouter\SimpleRouter;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->safeLoad();

/* Load external routes file */
require_once __DIR__ . '/../routes/routes.php';

// Start the routing
SimpleRouter::start();