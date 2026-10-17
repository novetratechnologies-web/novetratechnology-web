<?php

namespace App\Support;

use Illuminate\Support\Facades\Storage;

/** Resolves uploaded file paths to URLs served from the active application host. */
class PublicFileUrl
{
    public function resolve(?string $file): string
    {
        if ($file === null || trim($file) === '') {
            return '';
        }

        $file = trim(str_replace('\\', '/', $file));
        if (preg_match('/^(https?:)?\/\//i', $file)) {
            $parsedUrl = parse_url($file);
            $configuredHost = parse_url((string) config('app.url'), PHP_URL_HOST);
            $path = $parsedUrl['path'] ?? '';
            $isLocalAppUrl = isset($parsedUrl['host']) && (
                in_array(strtolower($parsedUrl['host']), ['localhost', '127.0.0.1', '::1'], true)
                || strtolower($parsedUrl['host']) === strtolower((string) $configuredHost)
            );

            if ($isLocalAppUrl && (str_starts_with($path, '/storage/') || str_starts_with($path, '/uploads/'))) {
                $file = ltrim($path, '/');
            } else {
                return $file;
            }
        } elseif (preg_match('/^(data|blob):/i', $file)) {
            return $file;
        }

        $relativePath = ltrim($file, '/');
        if (str_starts_with($relativePath, 'storage/')) {
            return url($relativePath);
        }

        try {
            if (Storage::disk('public')->exists($relativePath)) {
                return url('storage/' . $relativePath);
            }

            // Some older uploads were written directly under public/uploads.
            if (str_starts_with($relativePath, 'uploads/') && file_exists(public_path($relativePath))) {
                return asset($relativePath);
            }
        } catch (\Throwable $exception) {
            report($exception);
        }

        return url('storage/' . $relativePath);
    }
}
