<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocImageController extends Controller
{
    /**
     * Handle TinyMCE image upload for documentation.
     *
     * Security: MIME validation, size limit, safe filename generation, executable blocking.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'file' => [
                'required',
                'file',
                'image',
                'mimes:jpeg,jpg,png,gif,webp,svg',
                'max:2048', // 2MB max
            ],
        ]);

        $file = $request->file('file');

        // Double-check: block any executable extensions
        $dangerousExtensions = ['php', 'phtml', 'php3', 'php4', 'php5', 'php7', 'phps', 'pht', 'phar', 'exe', 'bat', 'cmd', 'sh', 'cgi', 'pl', 'py', 'rb', 'jsp', 'asp', 'aspx', 'htaccess'];
        $ext = strtolower($file->getClientOriginalExtension());

        if (in_array($ext, $dangerousExtensions)) {
            return response()->json(['error' => 'Tipe file tidak diizinkan.'], 422);
        }

        // Verify actual MIME type (not just extension)
        $realMime = $file->getMimeType();
        $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml'];

        if (!in_array($realMime, $allowedMimes)) {
            return response()->json(['error' => 'MIME type file tidak valid.'], 422);
        }

        // Generate safe filename: hash + original extension
        $safeFilename = Str::random(32) . '.' . $ext;
        $path = $file->storeAs('docs/images', $safeFilename, 'public');

        if (!$path) {
            return response()->json(['error' => 'Gagal mengupload file.'], 500);
        }

        return response()->json([
            'location' => asset('storage/' . $path),
        ]);
    }
}
