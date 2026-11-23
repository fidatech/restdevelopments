<?php
if (!function_exists('is_rtl')) {
    function is_rtl(?string $locale = null): bool {
        $locale = $locale ?: app()->getLocale();
        return in_array($locale, ['fa','ps']);
    }
}

if (!function_exists('media_url')) {
    /**
     * The public address of an uploaded file, whichever way its path was saved:
     * "storage/uploads/x.webp", "uploads/x.webp", "/storage/…" or a full URL.
     */
    function media_url(?string $path): ?string {
        if ($path === null || trim($path) === '') {
            return null;
        }
        $path = trim($path);
        if (preg_match('#^(https?:)?//#i', $path) || str_starts_with($path, 'data:')) {
            return $path;
        }
        $path = preg_replace('#^/?(public/)?#', '', $path);
        if (!preg_match('#^(storage|assets|admin-ui|app-assets)/#', $path)) {
            $path = 'storage/'.$path;
        }
        return asset($path);
    }
}
