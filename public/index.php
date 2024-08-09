<?php

use Illuminate\Http\Request;
use Illuminate\Contracts\Http\Kernel;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}


$app = require_once __DIR__.'/../bootstrap/app.php';

try {
    $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
    if (!$kernel) {
        dd('Null kernel');
    }

    $request = Illuminate\Http\Request::capture();
    if (!$request) {
        dd('Request capture failed');
    }

    if (is_null($kernel)) {
        throw new \Exception('Kernel is null');
    }

    if (is_null($request)) {
        throw new \Exception('Request is null');
    }
    
    
    $response = $kernel->handle($request);

    if (!$response instanceof \Illuminate\Http\Response) {
        dd('Response not valid');
    }

    if (!$response) {
        dd('Null response');
    };

    $response->send();
    $kernel->terminate($request, $response);
    dd(debug_backtrace());

} catch (Exception $e) {
    dd(__LINE__,$e);
    echo "Error handling request: " . $e->getMessage();
    die();
}

