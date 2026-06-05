<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;

/**
 * Derives file extension from the actual MIME type detected by finfo,
 * never from the client-supplied filename. Prevents extension-spoofing
 * attacks where a PHP file is renamed to shell.jpg then re-used after save.
 */
trait SecureFileUpload
{
    private static array $MIME_EXT_MAP = [
        'image/jpeg'               => 'jpg',
        'image/png'                => 'png',
        'image/webp'               => 'webp',
        'image/gif'                => 'gif',
        'image/svg+xml'            => 'svg',
        'image/x-icon'             => 'ico',
        'image/vnd.microsoft.icon' => 'ico',
        'application/pdf'          => 'pdf',
    ];

    /**
     * Return a whitelisted extension derived from the file's real MIME type.
     * Aborts 422 if the MIME maps to an extension not in $allowed.
     */
    protected function safeExtension(UploadedFile $file, array $allowed): string
    {
        $mime = $file->getMimeType();
        $ext  = self::$MIME_EXT_MAP[$mime] ?? null;

        abort_unless(
            $ext && in_array($ext, $allowed, true),
            422,
            'Invalid or disallowed file type.'
        );

        return $ext;
    }
}
