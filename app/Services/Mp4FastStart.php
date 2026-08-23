<?php

namespace App\Services;

class Mp4FastStart
{
    /**
     * Relocate moov atom to the beginning of the MP4 file for instant web streaming.
     */
    public static function process(string $filePath): bool
    {
        if (!file_exists($filePath) || !is_writable($filePath)) {
            return false;
        }

        $fp = @fopen($filePath, 'r+b');
        if (!$fp) return false;

        $fileSize = filesize($filePath);
        $ftypData = '';
        $moovData = '';
        $moovPos = -1;
        $moovSize = 0;
        $mdatPos = -1;

        // Parse top-level atoms
        while (!feof($fp) && ftell($fp) < $fileSize) {
            $pos = ftell($fp);
            $header = fread($fp, 8);
            if (strlen($header) < 8) break;

            $size = unpack('N', substr($header, 0, 4))[1];
            $type = substr($header, 4, 4);

            if ($size == 1) {
                $size64 = unpack('J', fread($fp, 8))[1];
                $actualSize = $size64;
                fseek($fp, $pos);
                $atomFull = fread($fp, $actualSize);
            } else {
                $actualSize = $size;
                fseek($fp, $pos);
                $atomFull = fread($fp, $actualSize);
            }

            if ($type === 'ftyp') {
                $ftypData = $atomFull;
            } elseif ($type === 'moov') {
                $moovPos = $pos;
                $moovSize = $actualSize;
                $moovData = $atomFull;
            } elseif ($type === 'mdat') {
                if ($mdatPos === -1) {
                    $mdatPos = $pos;
                }
            }

            fseek($fp, $pos + $actualSize);
        }

        fclose($fp);

        // If moov is already before mdat, it is already web-optimized
        if ($moovPos !== -1 && $mdatPos !== -1 && $moovPos < $mdatPos) {
            return true;
        }

        if ($moovPos === -1 || empty($moovData) || empty($ftypData)) {
            return false;
        }

        // We need to shift chunk offsets inside moov (stco / co64) by moovSize
        $moovData = self::adjustOffsets($moovData, $moovSize);

        // Create new faststart file
        $tempFile = $filePath . '.faststart.tmp';
        $out = fopen($tempFile, 'wb');
        if (!$out) return false;

        fwrite($out, $ftypData);
        fwrite($out, $moovData);

        // Copy everything else except ftyp and moov
        $in = fopen($filePath, 'rb');
        $pos = 0;
        while (!feof($in) && $pos < $fileSize) {
            fseek($in, $pos);
            $header = fread($in, 8);
            if (strlen($header) < 8) break;

            $size = unpack('N', substr($header, 0, 4))[1];
            $type = substr($header, 4, 4);

            if ($size == 1) {
                $actualSize = unpack('J', fread($in, 8))[1];
            } else {
                $actualSize = $size;
            }

            if ($type !== 'ftyp' && $type !== 'moov') {
                fseek($in, $pos);
                // Stream chunk by chunk
                $remaining = $actualSize;
                while ($remaining > 0 && !feof($in)) {
                    $chunk = fread($in, min($remaining, 1048576)); // 1MB buffer
                    fwrite($out, $chunk);
                    $remaining -= strlen($chunk);
                }
            }

            $pos += $actualSize;
        }

        fclose($in);
        fclose($out);

        // Replace original file with faststart file safely on Windows
        if (file_exists($tempFile) && filesize($tempFile) > 0) {
            @unlink($filePath);
            if (!@rename($tempFile, $filePath)) {
                @copy($tempFile, $filePath);
                @unlink($tempFile);
            }
            return true;
        }

        return false;
    }

    private static function adjustOffsets(string $moovData, int $offsetShift): string
    {
        $len = strlen($moovData);
        $pos = 0;

        // Recursive or iterative search for stco and co64 atoms within moovData
        while ($pos < $len - 8) {
            $type = substr($moovData, $pos + 4, 4);
            $size = unpack('N', substr($moovData, $pos, 4))[1];

            if ($size < 8 || $pos + $size > $len) {
                $pos++;
                continue;
            }

            if ($type === 'stco') {
                // stco structure: 4 bytes size, 4 bytes type, 4 bytes version/flags, 4 bytes entry count, then array of 4-byte offsets
                $entryCount = unpack('N', substr($moovData, $pos + 12, 4))[1];
                for ($i = 0; $i < $entryCount; $i++) {
                    $entryPos = $pos + 16 + ($i * 4);
                    if ($entryPos + 4 <= $len) {
                        $currentOffset = unpack('N', substr($moovData, $entryPos, 4))[1];
                        $newOffset = $currentOffset + $offsetShift;
                        $moovData = substr_replace($moovData, pack('N', $newOffset), $entryPos, 4);
                    }
                }
            } elseif ($type === 'co64') {
                // co64 structure: 4 bytes size, 4 bytes type, 4 bytes version/flags, 4 bytes entry count, then array of 8-byte offsets
                $entryCount = unpack('N', substr($moovData, $pos + 12, 4))[1];
                for ($i = 0; $i < $entryCount; $i++) {
                    $entryPos = $pos + 16 + ($i * 8);
                    if ($entryPos + 8 <= $len) {
                        $currentOffset = unpack('J', substr($moovData, $entryPos, 8))[1];
                        $newOffset = $currentOffset + $offsetShift;
                        $moovData = substr_replace($moovData, pack('J', $newOffset), $entryPos, 8);
                    }
                }
            }

            $pos++;
        }

        return $moovData;
    }
}
