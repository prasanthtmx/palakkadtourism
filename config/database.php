<?php
/**
 * Database configuration.
 *
 * Local/dev: defaults below (root/root) work on many XAMPP setups.
 * Production (Plesk): create config/database.local.php with credentials from
 * Plesk → Databases (MySQL "root" is usually blocked for PHP — use the DB user shown there).
 */

$__dbLocalFiles = [
    __DIR__ . '/database.local.php',
    dirname(__DIR__) . '/database.local.php',
];
foreach ($__dbLocalFiles as $__dbLocal) {
    if (is_readable($__dbLocal)) {
        require_once $__dbLocal;
        break;
    }
}
unset($__dbLocalFiles, $__dbLocal);

if (!defined('DB_HOST')) {
    define('DB_HOST', 'localhost');
}
if (!defined('DB_USER')) {
    define('DB_USER', 'root');
}
if (!defined('DB_PASS')) {
    define('DB_PASS', 'root');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', 'palakkad_tourism');
}

/**
 * @return mysqli
 */
function getDBConnection() {
    try {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    } catch (mysqli_sql_exception $e) {
        error_log('[DB] Connection failed: ' . $e->getMessage() . ' (user=' . DB_USER . ', host=' . DB_HOST . ')');
        if (PHP_SAPI !== 'cli') {
            if (!headers_sent()) {
                header('HTTP/1.1 503 Service Temporarily Unavailable');
                header('Content-Type: text/html; charset=UTF-8');
            }
            echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Database unavailable</title></head><body style="font-family:system-ui;margin:2rem;">'
                . '<h1>Database unavailable</h1>'
                . '<p>The site cannot connect to MySQL.</p>'
                . '<p><strong>Admin:</strong> On Plesk, use the database username and password from '
                . '<em>Databases</em> — not <code>root</code> unless your host allows it. Put them in '
                . '<code>config/database.local.php</code> (see <code>config/database.local.example.php</code>).</p>'
                . '</body></html>';
            exit;
        }
        throw $e;
    }

    $conn->set_charset('utf8');
    return $conn;
}

function closeDBConnection($conn) {
    $conn->close();
}
