<?php
/**
 * Security Helper Functions
 * Provides security functions for the application
 */

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Sanitize input string
 */
function sanitizeInput($input) {
    if (is_array($input)) {
        return array_map('sanitizeInput', $input);
    }
    return htmlspecialchars(strip_tags(trim($input)), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate and sanitize integer
 */
function sanitizeInt($input, $min = null, $max = null) {
    $int = filter_var($input, FILTER_VALIDATE_INT);
    if ($int === false) {
        return null;
    }
    if ($min !== null && $int < $min) {
        return null;
    }
    if ($max !== null && $int > $max) {
        return null;
    }
    return $int;
}

/**
 * Validate and sanitize URL
 */
function sanitizeUrl($url) {
    return filter_var($url, FILTER_SANITIZE_URL);
}

/**
 * Validate and sanitize email
 */
function sanitizeEmail($email) {
    return filter_var($email, FILTER_SANITIZE_EMAIL);
}

/**
 * Rate limiting for login attempts
 */
function checkLoginRateLimit($username) {
    $key = 'login_attempts_' . md5($username);
    $attempts = $_SESSION[$key] ?? 0;
    $lastAttempt = $_SESSION[$key . '_time'] ?? 0;
    
    // Reset after 15 minutes
    if (time() - $lastAttempt > 900) {
        $attempts = 0;
    }
    
    // Block after 5 failed attempts
    if ($attempts >= 5) {
        return false;
    }
    
    return true;
}

/**
 * Record login attempt
 */
function recordLoginAttempt($username, $success = false) {
    $key = 'login_attempts_' . md5($username);
    
    if ($success) {
        // Reset on success
        unset($_SESSION[$key]);
        unset($_SESSION[$key . '_time']);
    } else {
        // Increment on failure
        $_SESSION[$key] = ($_SESSION[$key] ?? 0) + 1;
        $_SESSION[$key . '_time'] = time();
    }
}

/**
 * Secure session configuration
 */
function secureSession() {
    // Prevent session fixation
    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id(true);
        $_SESSION['initiated'] = true;
    }
    
    // Set secure session cookie parameters
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        session_set_cookie_params([
            'lifetime' => $params['lifetime'],
            'path' => $params['path'],
            'domain' => $params['domain'],
            'secure' => true, // HTTPS only
            'httponly' => true, // Prevent JavaScript access
            'samesite' => 'Strict' // CSRF protection
        ]);
    }
}

/**
 * Validate file path to prevent directory traversal
 */
function validateFilePath($path, $allowedDir) {
    $realPath = realpath($allowedDir);
    $userPath = realpath($path);
    
    if ($realPath === false || $userPath === false) {
        return false;
    }
    
    return strpos($userPath, $realPath) === 0;
}

/**
 * Escape output for HTML
 */
function escapeHtml($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Escape output for JavaScript
 */
function escapeJs($string) {
    return json_encode($string, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT);
}

/**
 * Check if user is authenticated (for admin pages)
 */
function requireAdminAuth() {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: ' . BASE_PATH . '/admin/login.php');
        exit;
    }
}

/**
 * Validate image URL
 */
function validateImageUrl($url) {
    if (empty($url)) {
        return true; // Empty is allowed
    }
    
    // Check if it's a valid URL
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return false;
    }
    
    // Check if it's an image URL (basic check)
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
    $path = parse_url($url, PHP_URL_PATH);
    $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
    
    return in_array($extension, $allowedExtensions) || empty($extension);
}

/**
 * Resolve a whitelisted SQL file under project database/ (realpath + directory containment).
 *
 * @param string $basename e.g. temples_bulk_insert.sql
 * @return string|null Absolute path or null if not allowed or missing
 */
function adminResolveBundledSqlFile($basename) {
    $allowed = ['temples_bulk_insert.sql', 'mosques_update.sql'];
    if (!in_array($basename, $allowed, true)) {
        return null;
    }
    $baseDir = realpath(__DIR__ . '/../database');
    if ($baseDir === false) {
        return null;
    }
    $path = $baseDir . DIRECTORY_SEPARATOR . $basename;
    $real = realpath($path);
    if ($real === false || strpos($real, $baseDir) !== 0) {
        return null;
    }
    return $real;
}
