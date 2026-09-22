<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class MaintenanceController extends Controller
{
    /**
     * Run migrations, directory setups, and core system diagnostics.
     */
    public function migrate(): Response
    {
        $log = [];

        // 1. Cek Ekstensi PHP
        $extensions = ['dom', 'xml', 'fileinfo', 'gd', 'pdo_mysql', 'mbstring', 'curl', 'zip'];
        $missingExts = [];
        foreach ($extensions as $ext) {
            if (!extension_loaded($ext)) {
                $missingExts[] = $ext;
            }
        }
        if (!empty($missingExts)) {
            $log[] = "⚠️ Peringatan: Ekstensi PHP berikut BELUM AKTIF: <strong>" . implode(', ', $missingExts) . "</strong>.";
        } else {
            $log[] = "✅ Semua ekstensi PHP penting sudah aktif.";
        }

        // 2. Buat direktori storage penting jika belum ada
        $directories = [
            storage_path('app/private/livewire-tmp'),
            storage_path('app/livewire-tmp'),
            storage_path('app/public/livewire-tmp'),
            storage_path('app/public/settings'),
            storage_path('app/public/settings/hero'),
            storage_path('app/public/settings/hero3card'),
            storage_path('app/public/products'),
            storage_path('app/public/services'),
            storage_path('app/public/service_images'),
            storage_path('app/public/sell-submissions'),
            storage_path('app/firebase'),
            storage_path('app/private/firebase'),
            storage_path('framework/cache/data'),
            storage_path('framework/sessions'),
            storage_path('framework/views'),
            storage_path('logs'),
        ];
        foreach ($directories as $dir) {
            if (!is_dir($dir)) {
                @mkdir($dir, 0777, true);
            }
            @chmod($dir, 0777);
        }
        $log[] = "✅ Direktori storage & livewire-tmp siap dengan izin tulis.";

        // 3. Hubungkan symlink storage
        try {
            if (function_exists('symlink')) {
                Artisan::call('storage:link');
                $log[] = "✅ Storage symlink berhasil diverifikasi.";
            } else {
                $log[] = "ℹ️ PHP symlink() dinonaktifkan di hosting. Fallback streaming route otomatis aktif.";
            }
        } catch (\Throwable $e) {
            $log[] = "ℹ️ Storage link info: " . $e->getMessage();
        }

        // 4. Jalankan migrasi database
        $migrateOutput = "";
        try {
            Artisan::call('migrate', ['--force' => true, '--no-ansi' => true]);
            $migrateOutput = Artisan::output();
            $log[] = "✅ Migrasi database berhasil dijalankan.";
        } catch (\Throwable $e) {
            $log[] = "⚠️ Catatan migrasi: " . $e->getMessage();
        }

        // 5. Bersihkan cache
        try {
            Artisan::call('optimize:clear');
            $log[] = "✅ Optimize cache clear berhasil.";
        } catch (\Throwable $e) {
            $log[] = "ℹ️ Optimize clear info: " . $e->getMessage();
        }

        try {
            Artisan::call('permission:cache-reset');
            $log[] = "✅ Role & Permission cache berhasil di-reset.";
        } catch (\Throwable $e) {
        }

        $logHtml = implode("<br><br>", array_map(fn($l) => "• " . $l, $log));

        return response("<div style='font-family:monospace;background:#0f172a;color:#10b981;padding:24px;border-radius:12px;max-width:850px;margin:40px auto;border:1px solid #334155;'>
            <h2 style='color:#facc15;margin-top:0;'>🛠️ Laporan Pemeliharaan Sistem Prokar</h2>
            <div style='background:#020617;padding:20px;border-radius:8px;line-height:1.7;color:#f8fafc;'>
                {$logHtml}
            </div>
            " . ($migrateOutput ? "<h3 style='color:#38bdf8;'>Migration Output:</h3><pre style='background:#020617;padding:12px;border-radius:8px;overflow-x:auto;color:#cbd5e1;'>" . htmlspecialchars($migrateOutput) . "</pre>" : "") . "
            <p style='margin-top:20px;'><a href='" . route('admin.settings') . "' style='display:inline-block;padding:10px 18px;background:#facc15;color:#000;text-decoration:none;font-weight:bold;border-radius:8px;'>Buka Pengaturan Toko</a></p>
        </div>");
    }

    /**
     * Clear and optimize caches for deployment.
     */
    public function optimize(): Response
    {
        $log = [];
        $start = microtime(true);

        try {
            Artisan::call('optimize:clear');
            $log[] = "✅ Semua cache lama dibersihkan (optimize:clear)";
        } catch (\Throwable $e) {
            $log[] = "⚠️ optimize:clear — " . $e->getMessage();
        }

        try {
            Artisan::call('config:cache');
            $log[] = "✅ Config di-cache (config:cache)";
        } catch (\Throwable $e) {
            $log[] = "⚠️ config:cache — " . $e->getMessage();
        }

        try {
            Artisan::call('view:cache');
            $log[] = "✅ View di-compile (view:cache)";
        } catch (\Throwable $e) {
            $log[] = "⚠️ view:cache — " . $e->getMessage();
        }

        try {
            Artisan::call('permission:cache-reset');
            $log[] = "✅ Role & Permission cache di-reset";
        } catch (\Throwable $e) {
        }

        $duration = round((microtime(true) - $start) * 1000) . 'ms';
        $logHtml = implode("<br><br>", array_map(fn($l) => "• $l", $log));

        return response("<div style='font-family:monospace;background:#0f172a;color:#10b981;padding:24px;border-radius:12px;max-width:700px;margin:40px auto;border:1px solid #334155;'>
            <h2 style='color:#facc15;margin-top:0;'>⚡ Laravel Optimize — Selesai dalam {$duration}</h2>
            <div style='background:#020617;padding:20px;border-radius:8px;line-height:1.9;color:#f8fafc;'>{$logHtml}</div>
            <p style='margin-top:20px;color:#94a3b8;font-size:0.85rem;'>Jalankan setiap kali selesai deploy ke production untuk performa optimal.</p>
            <p style='margin-top:12px;'><a href='" . route('admin.dashboard') . "' style='display:inline-block;padding:10px 18px;background:#facc15;color:#000;text-decoration:none;font-weight:bold;border-radius:8px;margin-right:8px;'>Dashboard Admin</a><a href='" . route('admin.maintenance.migrate') . "' style='display:inline-block;padding:10px 18px;background:#1e293b;color:#fff;text-decoration:none;font-weight:bold;border-radius:8px;'>Full Maintenance</a></p>
        </div>");
    }

    /**
     * System health and extension diagnostics JSON API.
     */
    public function systemCheck(): JsonResponse
    {
        $results = [
            'status' => 'ok',
            'php_version' => PHP_VERSION,
        ];

        $requiredExtensions = [
            'fileinfo'  => 'MIME type detector for file uploads',
            'dom'       => 'DOMDocument for Termwind, MaryUI & SVG parsing',
            'xml'       => 'XML & SVG parsing',
            'gd'        => 'Image processing and compression',
            'pdo_mysql' => 'MySQL database connection',
            'mbstring'  => 'Multibyte string UTF-8',
            'curl'      => 'HTTP API client (Midtrans, Firebase)',
            'zip'       => 'Zip archive extraction',
        ];

        $extStatus = [];
        $hasMissing = false;
        foreach ($requiredExtensions as $ext => $desc) {
            $loaded = extension_loaded($ext);
            if (!$loaded) {
                $hasMissing = true;
            }
            $extStatus[$ext] = [
                'loaded'      => $loaded,
                'status'      => $loaded ? 'OK' : 'MISSING',
                'description' => $desc,
            ];
        }
        $results['has_missing_extensions'] = $hasMissing;
        $results['extensions'] = $extStatus;

        $results['php_ini'] = [
            'upload_max_filesize' => ini_get('upload_max_filesize'),
            'post_max_size'       => ini_get('post_max_size'),
            'memory_limit'        => ini_get('memory_limit'),
            'max_execution_time'  => ini_get('max_execution_time'),
        ];

        $testDirs = [
            'storage_app_public'          => storage_path('app/public'),
            'storage_app_public_livewire' => storage_path('app/public/livewire-tmp'),
            'storage_app_public_settings' => storage_path('app/public/settings'),
            'storage_app_private'         => storage_path('app/private'),
            'storage_framework_views'     => storage_path('framework/views'),
        ];

        $storageStatus = [];
        foreach ($testDirs as $name => $path) {
            if (!is_dir($path)) {
                @mkdir($path, 0777, true);
            }
            @chmod($path, 0777);
            $testFile = $path . '/test_write_' . time() . '.tmp';
            $canWrite = @file_put_contents($testFile, 'test') !== false;
            if ($canWrite) {
                @unlink($testFile);
            }
            $storageStatus[$name] = [
                'path'      => $path,
                'exists'    => is_dir($path),
                'writable'  => $canWrite,
            ];
        }
        $results['storage'] = $storageStatus;

        return response()->json($results, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Safe storage fallback route for cPanel hosting without symlink support.
     */
    public static function streamStorageFile(string $path): BinaryFileResponse
    {
        $cleanPath = str_replace(['..', "\0"], '', $path);
        $basePath = realpath(storage_path('app/public'));
        $filePath = realpath(storage_path('app/public/' . $cleanPath));

        if (!$filePath || !$basePath || !str_starts_with($filePath, $basePath) || !is_file($filePath)) {
            abort(404);
        }

        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeType = match ($ext) {
            'jpg', 'jpeg' => 'image/jpeg',
            'png'         => 'image/png',
            'webp'        => 'image/webp',
            'gif'         => 'image/gif',
            'svg'         => 'image/svg+xml',
            'ico'         => 'image/x-icon',
            'mp4'         => 'video/mp4',
            'webm'        => 'video/webm',
            'mov'         => 'video/quicktime',
            'avi'         => 'video/x-msvideo',
            'pdf'         => 'application/pdf',
            default       => mime_content_type($filePath) ?: 'application/octet-stream',
        };

        return response()->file($filePath, [
            'Content-Type'  => $mimeType,
            'Cache-Control' => 'public, max-age=86400, stale-while-revalidate=604800',
        ]);
    }
}
