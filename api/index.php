<?php

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

// 2. Paksa konfigurasi krusial Laravel langsung dari PHP supaya tidak tergantung dashboard Vercel
$forcedEnv = [
    'APP_DEBUG'          => 'true',
    'APP_ENV'            => 'local',
    'APP_KEY'            => 'base64:8T9vK2mP5qR8wY1zB4nV7cX0lJ3hG6fD9sA2eW5uI8o=',
    'LOG_CHANNEL'        => 'stderr',
    'SESSION_DRIVER'     => 'cookie',
    'CACHE_STORE'        => 'array',
    'VIEW_COMPILED_PATH' => $storagePath . '/framework/views',
    'APP_SERVICES_CACHE' => $storagePath . '/bootstrap/cache/services.php',
    'APP_PACKAGES_CACHE' => $storagePath . '/bootstrap/cache/packages.php',
    'APP_CONFIG_CACHE'   => $storagePath . '/bootstrap/cache/config.php',
    'APP_ROUTES_CACHE'   => $storagePath . '/bootstrap/cache/routes.php',
    'APP_EVENTS_CACHE'   => $storagePath . '/bootstrap/cache/events.php',
];

foreach ($forcedEnv as $key => $val) {
    $_ENV[$key] = $val;
    $_SERVER[$key] = $val;
    putenv("$key=$val");
}

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->useStoragePath($storagePath);

$request = Illuminate\Http\Request::capture();
$app->handleRequest($request);
