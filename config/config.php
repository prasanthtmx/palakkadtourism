<?php
if (defined('PALAKKAD_CONFIG_LOADED')) {
    return;
}
define('PALAKKAD_CONFIG_LOADED', true);

// Site configuration
define('SITE_NAME', 'Palakkad Tourism');
define('SITE_URL', 'https://palakkadtourism.org');
define('SITE_EMAIL', 'support@palakkadtourism.org');

// Base path for internal links - automatically detected
// Automatically detects if site is in root or subdirectory
// To manually override, uncomment and set the value below:
// $manualBasePath = '/palakkad'; // For local development in subdirectory
// $manualBasePath = ''; // For production at root

if (!isset($manualBasePath)) {
    // Auto-detect base path using SCRIPT_NAME (more reliable than DOCUMENT_ROOT)
    if (isset($_SERVER['SCRIPT_NAME'])) {
        // Get the directory of the script (e.g., /palakkad/index.php -> /palakkad)
        $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
        $basePath = rtrim($scriptDir, '/');
        
        // If script is in root, basePath will be '.' or empty
        if ($basePath === '.' || $basePath === '') {
            $basePath = '';
        } else {
            // Ensure it starts with /
            if ($basePath[0] !== '/') {
                $basePath = '/' . $basePath;
            }
        }
    } else {
        // Fallback: use document root method (for CLI or when SCRIPT_NAME not available)
        $documentRoot = isset($_SERVER['DOCUMENT_ROOT']) ? str_replace('\\', '/', rtrim($_SERVER['DOCUMENT_ROOT'], '/')) : '';
        if (!empty($documentRoot)) {
            $configDir = str_replace('\\', '/', dirname(__DIR__));
            $relativePath = str_replace($documentRoot, '', $configDir);
            $basePath = trim($relativePath, '/');
            $basePath = empty($basePath) ? '' : '/' . $basePath;
        } else {
            // Default to empty (root) if we can't detect
            $basePath = '';
        }
    }
} else {
    $basePath = $manualBasePath;
}

define('BASE_PATH', $basePath);

// Set true only for first-time admin bootstrap via admin/create_admin.php, then set false again.
if (!defined('ALLOW_CREATE_ADMIN_SCRIPT')) {
    define('ALLOW_CREATE_ADMIN_SCRIPT', false);
}

// Cache-bust main stylesheet (?v=… on the link). Bump this when assets/css/style.css changes.
if (!defined('STYLE_CSS_V')) {
    define('STYLE_CSS_V', '1.6.5');
}

// Timezone
date_default_timezone_set('Asia/Kolkata');

// Error reporting (set to 0 in production)
error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('display_errors', 0); // Disable error display in production
ini_set('log_errors', 1); // Log errors instead
ini_set('error_log', __DIR__ . '/../logs/php_errors.log');

// Sessions only for admin (public pages stay cacheable for crawlers).
$scriptName = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME']) : '';
$needsSession = (strpos($scriptName, '/admin/') !== false);
if ($needsSession && session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1);
    ini_set('session.use_strict_mode', 1);
    ini_set('session.cookie_samesite', 'Strict');

    session_start();

    if (!isset($_SESSION['created'])) {
        $_SESSION['created'] = time();
    } else if (time() - $_SESSION['created'] > 1800) {
        session_regenerate_id(true);
        $_SESSION['created'] = time();
    }
}

require_once __DIR__ . '/url_helper.php';
?>




