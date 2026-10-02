<?php

// 1. Buat folder storage & bootstrap/cache di /tmp
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

// 2. Cek apakah sudah ada koneksi Postgres (Neon Vercel) atau MySQL eksternal
$pgHost = getenv('POSTGRES_HOST') ?: ($_ENV['POSTGRES_HOST'] ?? null);
$mysqlHost = getenv('MYSQL_HOST') ?: ($_ENV['MYSQL_HOST'] ?? null);

// 3. Bersihkan variabel kosong sisa .env.example
foreach ($_ENV as $k => $v) {
    if ($v === '' || $v === 'null') {
        unset($_ENV[$k], $_SERVER[$k]);
        putenv($k);
    }
}

$forcedEnv = [
    'APP_NAME'               => 'Kabinet Haruna',
    'APP_DEBUG'              => 'true',
    'APP_ENV'                => 'production',
    'APP_KEY'                => 'base64:8T9vK2mP5qR8wY1zB4nV7cX0lJ3hG6fD9sA2eW5uI8o=',
    'APP_MAINTENANCE_DRIVER' => 'file',
    'LOG_CHANNEL'            => 'stderr',
    'SESSION_DRIVER'         => 'cookie',
    'CACHE_STORE'            => 'array',
    'CACHE_DRIVER'           => 'array',
    'QUEUE_CONNECTION'       => 'sync',
    'BROADCAST_CONNECTION'   => 'log',
    'FILESYSTEM_DISK'        => 'local',
    'MAIL_MAILER'            => 'log',
    'VIEW_COMPILED_PATH'     => $storagePath . '/framework/views',
    'APP_SERVICES_CACHE'     => $storagePath . '/bootstrap/cache/services.php',
    'APP_PACKAGES_CACHE'     => $storagePath . '/bootstrap/cache/packages.php',
    'APP_CONFIG_CACHE'       => $storagePath . '/bootstrap/cache/config.php',
    'APP_ROUTES_CACHE'       => $storagePath . '/bootstrap/cache/routes.php',
    'APP_EVENTS_CACHE'       => $storagePath . '/bootstrap/cache/events.php',
];

if ($pgHost) {
    // Jika sudah connect ke Neon Postgres di Vercel Storage (PERMANEN)
    $forcedEnv['DB_CONNECTION'] = 'pgsql';
    $forcedEnv['DB_HOST']       = $pgHost;
    $forcedEnv['DB_PORT']       = '5432';
    $forcedEnv['DB_DATABASE']   = getenv('POSTGRES_DATABASE') ?: ($_ENV['POSTGRES_DATABASE'] ?? 'neondb');
    $forcedEnv['DB_USERNAME']   = getenv('POSTGRES_USER') ?: ($_ENV['POSTGRES_USER'] ?? '');
    $forcedEnv['DB_PASSWORD']   = getenv('POSTGRES_PASSWORD') ?: ($_ENV['POSTGRES_PASSWORD'] ?? '');
    $forcedEnv['DB_SSLMODE']    = 'require';
} else {
    // Fallback ke SQLite /tmp kalau belum bikin database di Storage
    $sqlitePath = '/tmp/database.sqlite';
    if (!file_exists($sqlitePath)) {
        touch($sqlitePath);
    }
    $forcedEnv['DB_CONNECTION'] = 'sqlite';
    $forcedEnv['DB_DATABASE']   = $sqlitePath;
}

foreach ($forcedEnv as $key => $val) {
    $_ENV[$key] = $val;
    $_SERVER[$key] = $val;
    putenv("$key=$val");
}

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->useStoragePath($storagePath);

$request = Illuminate\Http\Request::capture();

// 4. Pastikan tabel kegiatans otomatis dibuat di database permanen jika belum ada
try {
    $schema = $app->make('db')->connection()->getSchemaBuilder();
    if (!$schema->hasTable('kegiatans')) {
        $schema->create('kegiatans', function ($table) {
            $table->id();
            $table->string('nama_kegiatan')->nullable();
            $table->string('judul')->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('keterangan')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('waktu')->nullable();
            $table->string('tempat')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('divisi')->nullable();
            $table->string('status')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();
        });
    }
} catch (\Throwable $e) {
    // Abaikan jika sudah ada
}

$app->handleRequest($request);
