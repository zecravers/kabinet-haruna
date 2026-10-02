<?php

// 1. Paksa PHP menampilkan error asli ke layar browser (bukan layar kosong 500)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// 2. Buat folder storage sementara di /tmp (karena folder asli di Vercel read-only)
$storagePath = '/tmp/storage';
foreach ([
    '/framework/views',
    '/framework/cache/data',
    '/framework/sessions',
    '/logs',
] as $dir) {
    if (!is_dir($storagePath . $dir)) {
        mkdir($storagePath . $dir, 0777, true);
    }
}

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// 3. Arahkan storage Laravel ke /tmp/storage
$app->useStoragePath($storagePath);

$request = Illuminate\Http\Request::capture();
$app->handleRequest($request);
