<?php

@error_reporting(E_ALL);
@ini_set('display_errors', 1);

use App\Container;
use App\Http\JsonRequest;
use App\Kernel;

require_once __DIR__ . '/../vendor/autoload.php';
$routes  = require_once __DIR__ . '/../config/routes.php';

$factories = require_once __DIR__ . '/../config/services.php';
$container = new Container($factories);
$httpRequest = JsonRequest::fromGlobals();

$kernel = new Kernel($routes, $container);
header('Content-Type: application/json');
try {
    $result = $kernel->handle($httpRequest);
    echo json_encode($result);
    exit;
} catch(\Exception $ex){
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'code' => $ex->getCode(),
        'message' => $ex->getMessage(),
    ]);
}
exit;
