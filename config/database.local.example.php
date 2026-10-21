<?php
/**
 * 1. Copy this entire file to:  config/database.local.php
 * 2. Replace the four values with YOUR Plesk (or host) database details.
 * 3. Never commit database.local.php to git.
 *
 * Plesk: Websites → your domain → Databases → click the database →
 * use the listed "Database name" and "Database user" (+ that user's password).
 *
 * The error "Access denied for user 'palakkad_app'" means the password did not match
 * — use the password Plesk generated or you set, not the word "change-me".
 */
define('DB_HOST', 'localhost');
define('DB_USER', 'paste_database_user_from_plesk_here');
define('DB_PASS', 'paste_database_password_here');
define('DB_NAME', 'paste_database_name_here');
