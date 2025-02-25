<?php

@error_reporting(E_ALL);
@ini_set('display_errors', 1);

use App\Container;
use App\Http\JsonRequest;
use App\Http\NotFoundException;
use App\Kernel;

function sendResponse($code, $ex)
{
    http_response_code($code);
    $acceptHeader = $_SERVER['HTTP_ACCEPT'] ?? '';
    $isXHttp = strpos($acceptHeader, 'application/json') !== false;

    if ($isXHttp) {
        header('Content-Type: application/json');
    } else {
        header('Content-Type: text/html');
    }

    if ($code === 404) {
        if ($isXHttp) {
            echo json_encode([
                'success' => false,
                'code' => 404,
                'message' => 'Not Found',
            ]);
        } else {
            @include '404.html';
        }
    } else {
        if ($isXHttp) {
            echo json_encode([
                'success' => false,
                'code' => $ex->getCode(),
                'message' => $ex->getMessage(),
            ]);
        } else {
            @include "500.html";
        }
    }
}

try {
    require_once __DIR__ . '/../vendor/autoload.php';
    $routes  = require_once __DIR__ . '/../config/routes.php';

    $factories = require_once __DIR__ . '/../config/services.php';
    $container = new Container($factories);
    $httpRequest = JsonRequest::fromGlobals();

    $kernel = new Kernel($routes, $container);
    $result = $kernel->handle($httpRequest);
    echo json_encode($result);
    exit;
} catch (NotFoundException $ex) {
    sendResponse(404, $ex);
} catch (\Exception $ex) {
    sendResponse(500, $ex);
}

exit;
