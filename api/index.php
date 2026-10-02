<?php

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Paksa tangkap Fatal Error jika PHP mati di tengah jalan
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        http_response_code(200);
        header('Content-Type: text/plain; charset=utf-8');
        echo "=== PHP FATAL ERROR ===\n";
        print_r($error);
    }
});

// 1. Buat folder storage & bootstrap/cache di /tmp (karena Vercel read-only)
$storagePath = '/tmp/storage';
foreach ([
    '/framework/views',
    '/framework/cache/data',
    '/framework/sessions',
    '/logs',
    '/bootstrap/cache',
] as $dir) {
    if (!is_dir($storagePath . $dir)) {
        mkdir($storagePath . $dir, 0777, true);
    }
}

// 2. Arahkan cache manifest Laravel ke /tmp SEBELUM bootstrap/app.php dipanggil
$_ENV['APP_SERVICES_CACHE'] = $storagePath . '/bootstrap/cache/services.php';
$_ENV['APP_PACKAGES_CACHE'] = $storagePath . '/bootstrap/cache/packages.php';
$_ENV['APP_CONFIG_CACHE']   = $storagePath . '/bootstrap/cache/config.php';
$_ENV['APP_ROUTES_CACHE']   = $storagePath . '/bootstrap/cache/routes.php';
$_ENV['APP_EVENTS_CACHE']   = $storagePath . '/bootstrap/cache/events.php';

putenv("APP_SERVICES_CACHE={$_ENV['APP_SERVICES_CACHE']}");
putenv("APP_PACKAGES_CACHE={$_ENV['APP_PACKAGES_CACHE']}");
putenv("APP_CONFIG_CACHE={$_ENV['APP_CONFIG_CACHE']}");
putenv("APP_ROUTES_CACHE={$_ENV['APP_ROUTES_CACHE']}");
putenv("APP_EVENTS_CACHE={$_ENV['APP_EVENTS_CACHE']}");

try {
    require __DIR__ . '/../vendor/autoload.php';

    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->useStoragePath($storagePath);

    $request = Illuminate\Http\Request::capture();
    $app->handleRequest($request);
} catch (\Throwable $e) {
    http_response_code(200);
    header('Content-Type: text/plain; charset=utf-8');
    echo "=== UNCAUGHT EXCEPTION ===\n";
    echo $e->getMessage() . "\n\n";
    echo "File: " . $e->getFile() . " (Line " . $e->getLine() . ")\n\n";
    echo $e->getTraceAsString();
}
