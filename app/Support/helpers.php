<?php
if (!function_exists('is_rtl')) {
    function is_rtl(?string $locale = null): bool {
        $locale = $locale ?: app()->getLocale();
        return in_array($locale, ['fa','ps']);
    }
}
