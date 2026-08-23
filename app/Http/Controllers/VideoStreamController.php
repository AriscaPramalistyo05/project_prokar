<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VideoStreamController extends Controller
{
    public function stream(Request $request, string $filename)
    {
        // Sanitize filename
        $cleanFilename = basename($filename);
        $path = storage_path('app/public/products/' . $cleanFilename);

        if (!file_exists($path)) {
            abort(404, 'Video tidak ditemukan');
        }

        $size = filesize($path);
        $start = 0;
        $end = $size - 1;
        $length = $size;
        $status = 200;
        $headers = [
            'Content-Type' => 'video/mp4',
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'public, max-age=2592000, immutable',
        ];

        // Handle HTTP Range header for smooth seeking and instant playback
        if ($request->header('Range')) {
            $range = $request->header('Range');
            if (preg_match('/bytes=(\d+)-(\d*)/', $range, $matches)) {
                $start = (int) $matches[1];
                if (!empty($matches[2])) {
                    $end = (int) $matches[2];
                } else {
                    // Limit buffer chunk size to 1MB to prevent blocking single-thread server
                    $end = min($start + (1024 * 1024) - 1, $size - 1);
                }
                $length = ($end - $start) + 1;
                $status = 206;
                $headers['Content-Range'] = "bytes {$start}-{$end}/{$size}";
            }
        }

        $headers['Content-Length'] = $length;

        return new StreamedResponse(function () use ($path, $start, $length) {
            $fp = fopen($path, 'rb');
            if ($fp) {
                fseek($fp, $start);
                $remaining = $length;
                $chunkSize = 65536; // 64KB per write

                while ($remaining > 0 && !feof($fp)) {
                    $bytesToRead = min($remaining, $chunkSize);
                    $buffer = fread($fp, $bytesToRead);
                    echo $buffer;
                    flush();
                    $remaining -= strlen($buffer);
                }
                fclose($fp);
            }
        }, $status, $headers);
    }
}
