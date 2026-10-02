<?php

// Set timezone ke WIB (Asia/Jakarta)
date_default_timezone_set('Asia/Jakarta');

// 1. Buat folder storage & bootstrap/cache di /tmp (karena Vercel read-only)
$storagePath = '/tmp/storage';
foreach ([
    '/framework/views',
    '/framework/cache/data',
    '/framework/sessions',
    '/logs',
    '/bootstrap/cache',
] as $dir) {
    $fullDir = $storagePath . $dir;
    if (!is_dir($fullDir)) {
        mkdir($fullDir, 0777, true);
    }
}

// 2. Siapkan file SQLite cadangan di /tmp
$sqlitePath = '/tmp/database.sqlite';
if (!file_exists($sqlitePath)) {
    touch($sqlitePath);
}

// 3. Ambil kredensial Neon Postgres dari Environment Variables Vercel
$pgHost = getenv('POSTGRES_HOST') ?: ($_ENV['POSTGRES_HOST'] ?? ($_SERVER['POSTGRES_HOST'] ?? null));
$pgDb   = getenv('POSTGRES_DATABASE') ?: ($_ENV['POSTGRES_DATABASE'] ?? ($_SERVER['POSTGRES_DATABASE'] ?? 'neondb'));
$pgUser = getenv('POSTGRES_USER') ?: ($_ENV['POSTGRES_USER'] ?? ($_SERVER['POSTGRES_USER'] ?? ''));
$pgPass = getenv('POSTGRES_PASSWORD') ?: ($_ENV['POSTGRES_PASSWORD'] ?? ($_SERVER['POSTGRES_PASSWORD'] ?? ''));

$rawDbUrl = getenv('POSTGRES_URL') ?: (getenv('DATABASE_URL') ?: ($_ENV['POSTGRES_URL'] ?? ($_ENV['DATABASE_URL'] ?? null)));
if (empty($pgHost) && !empty($rawDbUrl)) {
    $parsed = parse_url($rawDbUrl);
    if (!empty($parsed['host'])) {
        $pgHost = $parsed['host'];
        $pgUser = $parsed['user'] ?? $pgUser;
        $pgPass = $parsed['pass'] ?? $pgPass;
        $pgDb   = isset($parsed['path']) ? ltrim($parsed['path'], '/') : $pgDb;
    }
}

// 4. Bersihkan variabel kosong sisa .env.example di Vercel
foreach ($_ENV as $k => $v) {
    if (in_array($v, ['', 'null'], true)) {
        unset($_ENV[$k], $_SERVER[$k]);
        putenv($k);
    }
}

$forcedEnv = [
    'APP_NAME'               => 'Kabinet Haruna',
    'APP_DEBUG'              => 'true',
    'APP_ENV'                => 'production',
    'APP_TIMEZONE'           => 'Asia/Jakarta',
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

// 5. Konfigurasi Koneksi Permanen Neon Postgres via sslmode DSN injection
$activeDbDriver = 'sqlite-tmp';
$sslModeWithEndpoint = 'require';

if (!empty($pgHost)) {
    $hostParts = explode('.', $pgHost);
    $endpointId = $hostParts[0]; // ep-lingering-frog-b8m4icez-pooler

    // Hapus DATABASE_URL & DB_URL agar Laravel tidak menimpa sslmode kita
    unset($_ENV['DATABASE_URL'], $_SERVER['DATABASE_URL'], $_ENV['DB_URL'], $_SERVER['DB_URL'], $_ENV['POSTGRES_URL'], $_SERVER['POSTGRES_URL']);
    putenv('DATABASE_URL');
    putenv('DB_URL');
    putenv('POSTGRES_URL');

    // Di PostgresConnector Laravel, sslmode ditempel langsung tanpa tanda kutip: ;sslmode={$sslmode}
    $sslModeWithEndpoint = "require;options='--endpoint={$endpointId}'";
    $testDsn = "pgsql:host='{$pgHost}';dbname='{$pgDb}';port=5432;sslmode={$sslModeWithEndpoint}";

    try {
        $testPdo = new PDO($testDsn, $pgUser, $pgPass, [PDO::ATTR_TIMEOUT => 5]);
        $forcedEnv['DB_CONNECTION'] = 'pgsql';
        $forcedEnv['DB_HOST']       = $pgHost;
        $forcedEnv['DB_PORT']       = '5432';
        $forcedEnv['DB_DATABASE']   = $pgDb;
        $forcedEnv['DB_USERNAME']   = $pgUser;
        $forcedEnv['DB_PASSWORD']   = $pgPass;
        $forcedEnv['DB_SSLMODE']    = $sslModeWithEndpoint;
        $activeDbDriver = 'neon-pgsql-permanent';
    } catch (\Throwable $e) {
        $activeDbDriver = 'sqlite-fallback';
    }
}

if ($activeDbDriver !== 'neon-pgsql-permanent') {
    $forcedEnv['DB_CONNECTION'] = 'sqlite';
    $forcedEnv['DB_DATABASE']   = $sqlitePath;
}

foreach ($forcedEnv as $key => $val) {
    $_ENV[$key] = $val;
    $_SERVER[$key] = $val;
    putenv($key . '=' . $val);
}

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->useStoragePath($storagePath);

// 6. Jalankan pembuatan tabel, isi 49 data awal, & AUTO-UPDATE status sesuai tanggal sekarang (WIB)
$app->booted(function ($app) use ($forcedEnv, $sslModeWithEndpoint) {
    if ($forcedEnv['DB_CONNECTION'] === 'pgsql') {
        $app['config']->set('database.default', 'pgsql');
        $app['config']->set('database.connections.pgsql.url', null);
        $app['config']->set('database.connections.pgsql.host', $forcedEnv['DB_HOST']);
        $app['config']->set('database.connections.pgsql.port', '5432');
        $app['config']->set('database.connections.pgsql.database', $forcedEnv['DB_DATABASE']);
        $app['config']->set('database.connections.pgsql.username', $forcedEnv['DB_USERNAME']);
        $app['config']->set('database.connections.pgsql.password', $forcedEnv['DB_PASSWORD']);
        $app['config']->set('database.connections.pgsql.sslmode', $sslModeWithEndpoint);
    }

    try {
        $db = $app->make('db')->connection();
        $schema = $db->getSchemaBuilder();

        if (!$schema->hasTable('kegiatans')) {
            $schema->create('kegiatans', function ($table) {
                $table->id();
                $table->string('nama_kegiatan', 255);
                $table->text('deskripsi')->nullable();
                $table->date('tanggal');
                $table->time('waktu');
                $table->string('lokasi', 255);
                $table->string('status', 100);
                $table->timestamps();
            });

            $initialData = [
                ['nama_kegiatan' => 'TUMISS', 'deskripsi' => null, 'tanggal' => '2026-04-15', 'waktu' => '15:00:00', 'lokasi' => 'Polimedia Gedung E, Lt 2.9 & 2.10', 'status' => 'selesai', 'created_at' => '2026-04-19 00:33:02', 'updated_at' => '2026-04-19 00:33:02'],
                ['nama_kegiatan' => 'BANK ASPIRASI 1', 'deskripsi' => null, 'tanggal' => '2026-02-10', 'waktu' => '15:00:00', 'lokasi' => 'Whats App', 'status' => 'selesai', 'created_at' => '2026-04-19 01:29:09', 'updated_at' => '2026-04-19 01:29:09'],
                ['nama_kegiatan' => 'FOTO KABINET', 'deskripsi' => null, 'tanggal' => '2026-02-21', 'waktu' => '10:00:00', 'lokasi' => 'Polimedia Pusgiwa Lt.2', 'status' => 'selesai', 'created_at' => '2026-04-19 01:29:41', 'updated_at' => '2026-04-19 01:29:41'],
                ['nama_kegiatan' => 'STUDI BANDING', 'deskripsi' => null, 'tanggal' => '2026-02-25', 'waktu' => '16:00:00', 'lokasi' => 'Polimedia Hall Gedung E', 'status' => 'selesai', 'created_at' => '2026-04-19 01:30:45', 'updated_at' => '2026-04-19 01:30:45'],
                ['nama_kegiatan' => 'TNT 3', 'deskripsi' => null, 'tanggal' => '2026-04-19', 'waktu' => '16:00:00', 'lokasi' => 'Instagram @himedia.jkt', 'status' => 'selesai', 'created_at' => '2026-04-19 01:33:29', 'updated_at' => '2026-04-19 01:33:29'],
                ['nama_kegiatan' => 'TRIVIA 2', 'deskripsi' => null, 'tanggal' => '2026-04-20', 'waktu' => '16:00:00', 'lokasi' => 'Instagram @himedia.jkt', 'status' => 'akan datang', 'created_at' => '2026-04-19 01:34:12', 'updated_at' => '2026-04-19 01:34:12'],
                ['nama_kegiatan' => 'KOMIK 1', 'deskripsi' => null, 'tanggal' => '2026-03-02', 'waktu' => '10:00:00', 'lokasi' => 'Polimedia Gedung E, Kelas', 'status' => 'selesai', 'created_at' => '2026-04-19 02:13:00', 'updated_at' => '2026-04-19 02:13:00'],
                ['nama_kegiatan' => 'AMUBA 13', 'deskripsi' => null, 'tanggal' => '2026-05-09', 'waktu' => '08:00:00', 'lokasi' => 'Panti Asuhan', 'status' => 'akan datang', 'created_at' => '2026-04-19 03:40:04', 'updated_at' => '2026-05-02 03:55:15'],
                ['nama_kegiatan' => 'RGB', 'deskripsi' => null, 'tanggal' => '2026-02-26', 'waktu' => '15:00:00', 'lokasi' => 'Polimedia Kantin Baru', 'status' => 'selesai', 'created_at' => '2026-04-20 20:44:57', 'updated_at' => '2026-04-20 20:44:57'],
                ['nama_kegiatan' => 'MUJAJIL', 'deskripsi' => null, 'tanggal' => '2026-03-03', 'waktu' => '16:00:00', 'lokasi' => 'Polimedia Jakarta, Srengseng Sawah', 'status' => 'selesai', 'created_at' => '2026-04-30 20:42:44', 'updated_at' => '2026-04-30 20:42:44'],
                ['nama_kegiatan' => 'MUJAJIL', 'deskripsi' => null, 'tanggal' => '2026-03-04', 'waktu' => '16:00:00', 'lokasi' => 'Polimedia Jakarta, Srengseng Sawah', 'status' => 'selesai', 'created_at' => '2026-04-30 20:43:26', 'updated_at' => '2026-04-30 20:43:26'],
                ['nama_kegiatan' => 'MUGJIL', 'deskripsi' => null, 'tanggal' => '2026-03-05', 'waktu' => '16:00:00', 'lokasi' => 'Polimedia Jakarta, Srengseng Sawah', 'status' => 'selesai', 'created_at' => '2026-04-30 20:43:51', 'updated_at' => '2026-04-30 20:43:51'],
                ['nama_kegiatan' => 'IMAJI', 'deskripsi' => null, 'tanggal' => '2026-03-07', 'waktu' => '16:00:00', 'lokasi' => 'Teras Atas Depok', 'status' => 'selesai', 'created_at' => '2026-04-30 20:44:51', 'updated_at' => '2026-04-30 20:44:51'],
                ['nama_kegiatan' => 'BANK ASPIRASI 2', 'deskripsi' => null, 'tanggal' => '2026-03-10', 'waktu' => '12:00:00', 'lokasi' => 'Whats App', 'status' => 'selesai', 'created_at' => '2026-04-30 20:45:38', 'updated_at' => '2026-04-30 20:45:38'],
                ['nama_kegiatan' => 'STUBAN (Teknik)', 'deskripsi' => null, 'tanggal' => '2026-03-12', 'waktu' => '16:00:00', 'lokasi' => 'Polimedia Pusgiwa Lt.2', 'status' => 'selesai', 'created_at' => '2026-04-30 20:46:10', 'updated_at' => '2026-04-30 20:46:10'],
                ['nama_kegiatan' => 'UPGRADING 1', 'deskripsi' => null, 'tanggal' => '2026-03-13', 'waktu' => '13:00:00', 'lokasi' => 'Polimedia Gedung E, Lt 2.9 & 2.10', 'status' => 'selesai', 'created_at' => '2026-04-30 20:46:52', 'updated_at' => '2026-04-30 20:46:52'],
                ['nama_kegiatan' => 'FRAME 1', 'deskripsi' => null, 'tanggal' => '2026-03-14', 'waktu' => '16:00:00', 'lokasi' => 'Instagram @himedia.jkt', 'status' => 'selesai', 'created_at' => '2026-04-30 20:47:18', 'updated_at' => '2026-04-30 20:47:18'],
                ['nama_kegiatan' => 'KEMASAN 1', 'deskripsi' => null, 'tanggal' => '2026-03-15', 'waktu' => '16:00:00', 'lokasi' => 'Instagram @himedia.jkt', 'status' => 'selesai', 'created_at' => '2026-04-30 20:47:48', 'updated_at' => '2026-04-30 20:47:48'],
                ['nama_kegiatan' => 'TNT 1', 'deskripsi' => null, 'tanggal' => '2026-03-19', 'waktu' => '16:00:00', 'lokasi' => 'Instagram @himedia.jkt', 'status' => 'selesai', 'created_at' => '2026-04-30 20:48:12', 'updated_at' => '2026-04-30 20:48:12'],
                ['nama_kegiatan' => 'TRIVIA 1', 'deskripsi' => null, 'tanggal' => '2026-03-20', 'waktu' => '16:00:00', 'lokasi' => 'Instagram @himedia.jkt', 'status' => 'selesai', 'created_at' => '2026-04-30 20:48:34', 'updated_at' => '2026-04-30 20:48:34'],
                ['nama_kegiatan' => 'AOTM 1', 'deskripsi' => null, 'tanggal' => '2026-03-23', 'waktu' => '16:00:00', 'lokasi' => 'Instagram @himedia.jkt', 'status' => 'selesai', 'created_at' => '2026-04-30 20:48:59', 'updated_at' => '2026-04-30 20:48:59'],
                ['nama_kegiatan' => 'TNT 2', 'deskripsi' => null, 'tanggal' => '2026-03-25', 'waktu' => '16:00:00', 'lokasi' => 'Instagram @himedia.jkt', 'status' => 'selesai', 'created_at' => '2026-04-30 20:49:22', 'updated_at' => '2026-04-30 20:49:22'],
                ['nama_kegiatan' => 'OBAMA 1', 'deskripsi' => null, 'tanggal' => '2026-03-31', 'waktu' => '16:00:00', 'lokasi' => 'Kolam Renang Batoe 54', 'status' => 'selesai', 'created_at' => '2026-04-30 20:49:50', 'updated_at' => '2026-04-30 20:49:50'],
                ['nama_kegiatan' => 'ALAM 1', 'deskripsi' => null, 'tanggal' => '2026-03-31', 'waktu' => '16:00:00', 'lokasi' => 'Instagram @himedia.jkt', 'status' => 'selesai', 'created_at' => '2026-04-30 20:50:10', 'updated_at' => '2026-04-30 20:50:10'],
                ['nama_kegiatan' => 'KOMIK 2', 'deskripsi' => null, 'tanggal' => '2026-04-01', 'waktu' => '12:00:00', 'lokasi' => 'Polimedia Jakarta, Srengseng Sawah', 'status' => 'selesai', 'created_at' => '2026-04-30 20:54:05', 'updated_at' => '2026-04-30 20:54:05'],
                ['nama_kegiatan' => 'MUTER 1', 'deskripsi' => null, 'tanggal' => '2026-04-09', 'waktu' => '12:00:00', 'lokasi' => 'Polimedia Jakarta, Srengseng Sawah', 'status' => 'selesai', 'created_at' => '2026-04-30 20:54:33', 'updated_at' => '2026-04-30 20:54:33'],
                ['nama_kegiatan' => 'BANK ASPIRASI 3', 'deskripsi' => null, 'tanggal' => '2026-04-10', 'waktu' => '12:00:00', 'lokasi' => 'Whats App', 'status' => 'selesai', 'created_at' => '2026-04-30 20:58:16', 'updated_at' => '2026-04-30 20:58:16'],
                ['nama_kegiatan' => 'BESAN 1', 'deskripsi' => null, 'tanggal' => '2026-04-12', 'waktu' => '16:00:00', 'lokasi' => 'Instagram @himedia.jkt', 'status' => 'selesai', 'created_at' => '2026-04-30 20:58:43', 'updated_at' => '2026-04-30 20:58:43'],
                ['nama_kegiatan' => 'FRAME 2', 'deskripsi' => null, 'tanggal' => '2026-04-14', 'waktu' => '16:00:00', 'lokasi' => 'Instagram @himedia.jkt', 'status' => 'selesai', 'created_at' => '2026-04-30 20:59:12', 'updated_at' => '2026-04-30 20:59:12'],
                ['nama_kegiatan' => 'KEMASAN 2', 'deskripsi' => null, 'tanggal' => '2026-04-15', 'waktu' => '16:00:00', 'lokasi' => 'Instagram @himedia.jkt', 'status' => 'selesai', 'created_at' => '2026-04-30 20:59:40', 'updated_at' => '2026-04-30 20:59:40'],
                ['nama_kegiatan' => 'KOMED', 'deskripsi' => null, 'tanggal' => '2026-04-23', 'waktu' => '16:00:00', 'lokasi' => 'Polimedia Jakarta, Srengseng Sawah', 'status' => 'selesai', 'created_at' => '2026-04-30 21:00:34', 'updated_at' => '2026-04-30 21:00:34'],
                ['nama_kegiatan' => 'AOTM 2', 'deskripsi' => null, 'tanggal' => '2026-04-23', 'waktu' => '16:00:00', 'lokasi' => 'Instagram @himedia.jkt', 'status' => 'selesai', 'created_at' => '2026-04-30 21:01:08', 'updated_at' => '2026-04-30 21:01:08'],
                ['nama_kegiatan' => 'TNT 4', 'deskripsi' => null, 'tanggal' => '2026-04-25', 'waktu' => '16:00:00', 'lokasi' => 'Instagram @himedia.jkt', 'status' => 'selesai', 'created_at' => '2026-04-30 21:01:39', 'updated_at' => '2026-04-30 21:01:39'],
                ['nama_kegiatan' => 'MARJAN 1', 'deskripsi' => null, 'tanggal' => '2026-05-04', 'waktu' => '16:00:00', 'lokasi' => 'YouTube @himedia.jkt', 'status' => 'akan datang', 'created_at' => '2026-04-30 21:02:21', 'updated_at' => '2026-04-30 21:02:21'],
                ['nama_kegiatan' => 'MUTER 2', 'deskripsi' => null, 'tanggal' => '2026-05-09', 'waktu' => '12:00:00', 'lokasi' => 'Polimedia Jakarta, Srengseng Sawah', 'status' => 'akan datang', 'created_at' => '2026-04-30 21:02:51', 'updated_at' => '2026-04-30 21:02:51'],
                ['nama_kegiatan' => 'BANK ASPIRASI 4', 'deskripsi' => null, 'tanggal' => '2026-05-10', 'waktu' => '16:00:00', 'lokasi' => 'Whats App', 'status' => 'akan datang', 'created_at' => '2026-04-30 21:08:00', 'updated_at' => '2026-04-30 21:08:00'],
                ['nama_kegiatan' => 'KOMIK 3', 'deskripsi' => null, 'tanggal' => '2026-05-12', 'waktu' => '12:00:00', 'lokasi' => 'Polimedia Jakarta, Srengseng Sawah', 'status' => 'akan datang', 'created_at' => '2026-04-30 21:08:34', 'updated_at' => '2026-04-30 21:08:34'],
                ['nama_kegiatan' => 'HIMEDIA PLAYBOOK', 'deskripsi' => null, 'tanggal' => '2026-05-14', 'waktu' => '16:00:00', 'lokasi' => 'Website @himediajkt', 'status' => 'akan datang', 'created_at' => '2026-04-30 21:09:21', 'updated_at' => '2026-04-30 21:09:21'],
                ['nama_kegiatan' => 'FRAME 3', 'deskripsi' => null, 'tanggal' => '2026-05-14', 'waktu' => '16:00:00', 'lokasi' => 'Instagram @himedia.jkt', 'status' => 'akan datang', 'created_at' => '2026-04-30 21:09:50', 'updated_at' => '2026-04-30 21:09:50'],
                ['nama_kegiatan' => 'KEMASAN 3', 'deskripsi' => null, 'tanggal' => '2026-05-15', 'waktu' => '16:00:00', 'lokasi' => 'Instagram @himedia.jkt', 'status' => 'akan datang', 'created_at' => '2026-04-30 21:10:17', 'updated_at' => '2026-04-30 21:10:17'],
                ['nama_kegiatan' => 'MENTION 1', 'deskripsi' => null, 'tanggal' => '2026-05-16', 'waktu' => '16:00:00', 'lokasi' => 'Polimedia Pusgiwa Lt.2', 'status' => 'akan datang', 'created_at' => '2026-04-30 21:10:37', 'updated_at' => '2026-04-30 21:10:37'],
                ['nama_kegiatan' => 'TNT 5', 'deskripsi' => null, 'tanggal' => '2026-05-19', 'waktu' => '16:00:00', 'lokasi' => 'Instagram @himedia.jkt', 'status' => 'akan datang', 'created_at' => '2026-04-30 21:11:03', 'updated_at' => '2026-04-30 21:11:03'],
                ['nama_kegiatan' => 'UPGRADING 2', 'deskripsi' => null, 'tanggal' => '2026-05-21', 'waktu' => '16:00:00', 'lokasi' => 'Polimedia Gedung E, Lt 2.9 & 2.10', 'status' => 'akan datang', 'created_at' => '2026-04-30 21:11:27', 'updated_at' => '2026-04-30 21:11:27'],
                ['nama_kegiatan' => 'AOTM 3', 'deskripsi' => null, 'tanggal' => '2026-05-23', 'waktu' => '16:00:00', 'lokasi' => 'Instagram @himedia.jkt', 'status' => 'akan datang', 'created_at' => '2026-04-30 21:11:48', 'updated_at' => '2026-04-30 21:11:48'],
                ['nama_kegiatan' => 'TNT 6', 'deskripsi' => null, 'tanggal' => '2026-05-25', 'waktu' => '16:00:00', 'lokasi' => 'Instagram @himedia.jkt', 'status' => 'akan datang', 'created_at' => '2026-04-30 21:12:22', 'updated_at' => '2026-04-30 21:12:22'],
                ['nama_kegiatan' => 'OBAMA 2', 'deskripsi' => null, 'tanggal' => '2026-05-29', 'waktu' => '16:00:00', 'lokasi' => 'TBA', 'status' => 'akan datang', 'created_at' => '2026-04-30 21:13:03', 'updated_at' => '2026-04-30 21:13:03'],
                ['nama_kegiatan' => 'KEMUL 1', 'deskripsi' => null, 'tanggal' => '2026-05-30', 'waktu' => '16:00:00', 'lokasi' => 'Instagram @himedia.jkt', 'status' => 'akan datang', 'created_at' => '2026-04-30 21:13:30', 'updated_at' => '2026-04-30 21:13:30'],
                ['nama_kegiatan' => 'ALAM 2', 'deskripsi' => null, 'tanggal' => '2026-05-31', 'waktu' => '16:00:00', 'lokasi' => 'Instagram @himedia.jkt', 'status' => 'akan datang', 'created_at' => '2026-04-30 21:14:02', 'updated_at' => '2026-04-30 21:14:02'],
                ['nama_kegiatan' => 'KOMIK 4', 'deskripsi' => null, 'tanggal' => '2026-06-02', 'waktu' => '12:00:00', 'lokasi' => 'Polimedia Jakarta, Srengseng Sawah', 'status' => 'akan datang', 'created_at' => '2026-05-11 17:44:59', 'updated_at' => '2026-05-11 17:44:59'],
                ['nama_kegiatan' => 'TUMISS', 'deskripsi' => null, 'tanggal' => '2026-05-14', 'waktu' => '08:44:00', 'lokasi' => 'Polimedia Jakarta, Srengseng Sawah', 'status' => 'akan datang', 'created_at' => '2026-05-11 18:45:18', 'updated_at' => '2026-05-11 18:45:18'],
            ];

            $db->table('kegiatans')->insert($initialData);
        }

        // AUTO-UPDATE STATUS: Ubah kegiatan yang tanggal/waktunya sudah lewat dari waktu sekarang (WIB) menjadi 'selesai'
        $today = date('Y-m-d');
        $nowTime = date('H:i:s');

        $db->table('kegiatans')
            ->where('status', 'akan datang')
            ->where(function ($query) use ($today, $nowTime) {
                $query->where('tanggal', '<', $today)
                      ->orWhere(function ($q) use ($today, $nowTime) {
                          $q->where('tanggal', '=', $today)
                            ->where('waktu', '<=', $nowTime);
                      });
            })
            ->update(['status' => 'selesai', 'updated_at' => date('Y-m-d H:i:s')]);

    } catch (\Throwable $e) {
        // Abaikan jika gagal inisialisasi
    }
});

$request = Illuminate\Http\Request::capture();

if (in_array($request->getPathInfo(), ['/favicon.ico', '/favicon.png'], true)) {
    $logoFile = __DIR__ . '/../public/logo/logo1.png';
    if (file_exists($logoFile)) {
        header('Content-Type: image/png');
        header('Cache-Control: public, max-age=86400');
        readfile($logoFile);
        exit;
    }
}

$response = $app->handle($request);
$response->headers->set('X-DB-Mode', $activeDbDriver);

$content = $response->getContent();
if (is_string($content) && stripos($content, '</head>') !== false) {
    $faviconTags = '<link rel="icon" type="image/png" href="/logo/logo1.png?v=2">'
                 . '<link rel="shortcut icon" type="image/png" href="/logo/logo1.png?v=2">';
    $content = preg_replace('/<link[^>]*rel=["\'](?:shortcut )?icon["\'][^>]*>/i', '', $content);
    $content = str_ireplace('</head>', $faviconTags . "\n</head>", $content);
    $response->setContent($content);
}

$response->send();
$app->terminate($request, $response);
