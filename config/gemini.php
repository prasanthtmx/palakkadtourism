<?php
/**
 * Gemini chatbot settings. Real key lives in gemini.local.php (not in Git).
 */
if (defined('PALAKKAD_GEMINI_LOADED')) {
    return;
}
define('PALAKKAD_GEMINI_LOADED', true);

$__geminiLocal = __DIR__ . '/gemini.local.php';
if (is_readable($__geminiLocal)) {
    require_once $__geminiLocal;
}
unset($__geminiLocal);

if (!defined('GEMINI_API_KEY')) {
    define('GEMINI_API_KEY', '');
}
if (!defined('GEMINI_MODEL')) {
    define('GEMINI_MODEL', 'gemini-3.8-flash');
}
if (!defined('GEMINI_MAX_OUTPUT_TOKENS')) {
    define('GEMINI_MAX_OUTPUT_TOKENS', 512);
}
if (!defined('GEMINI_RATE_PER_HOUR')) {
    define('GEMINI_RATE_PER_HOUR', 20);
}
if (!defined('GEMINI_CACHE_TTL')) {
    define('GEMINI_CACHE_TTL', 14 * 86400);
}
if (!defined('GEMINI_CACHE_MAX')) {
    define('GEMINI_CACHE_MAX', 400);
}
