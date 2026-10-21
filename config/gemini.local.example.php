<?php
/**
 * 1. Copy this file to:  config/gemini.local.php
 * 2. Paste your Google AI Studio Gemini API key.
 * 3. Never commit gemini.local.php (it is gitignored).
 *
 * Key: https://aistudio.google.com/apikey
 * Models: gemini-3.8-flash (current default)
 */
define('GEMINI_API_KEY', 'paste_gemini_api_key_here');
define('GEMINI_MODEL', 'gemini-3.8-flash');
// Optional: define('GEMINI_CACHE_TTL', 14 * 86400);
