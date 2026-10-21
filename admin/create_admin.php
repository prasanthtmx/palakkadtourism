<?php
/**
 * Admin bootstrap / password reset — locked by default.
 *
 * First install: set ALLOW_CREATE_ADMIN_SCRIPT to true in config/config.php, open this page once,
 * create admin, then set ALLOW_CREATE_ADMIN_SCRIPT back to false (or remove from production).
 *
 * Logged-in admins can always use this page to reset the default admin password (with CSRF).
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/security.php';

$adminLoggedIn = isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
if (!$adminLoggedIn && !ALLOW_CREATE_ADMIN_SCRIPT) {
    http_response_code(404);
    exit;
}

$conn = getDBConnection();
$message = '';
$messageType = '';

// Default admin credentials
$defaultUsername = 'admin';
$defaultPassword = 'admin123';
$defaultEmail = 'admin@palakkadtourism.org';
$defaultFullName = 'Administrator';

// Check if admin user exists
$checkStmt = $conn->prepare('SELECT id, username FROM admin_users WHERE username = ?');
$checkStmt->bind_param('s', $defaultUsername);
$checkStmt->execute();
$result = $checkStmt->get_result();
$existingUser = $result->fetch_assoc();
$checkStmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $message = 'Invalid security token. Please try again.';
        $messageType = 'danger';
    } elseif ($_POST['action'] === 'create') {
        $password = $_POST['password'] ?? $defaultPassword;
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        if ($existingUser) {
            $stmt = $conn->prepare('UPDATE admin_users SET password = ?, email = ?, full_name = ?, status = \'active\' WHERE username = ?');
            $stmt->bind_param('ssss', $hashedPassword, $defaultEmail, $defaultFullName, $defaultUsername);
            if ($stmt->execute()) {
                $message = 'Admin user password has been reset successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error updating admin user: ' . $conn->error;
                $messageType = 'danger';
            }
            $stmt->close();
        } else {
            $stmt = $conn->prepare("INSERT INTO admin_users (username, email, password, full_name, status) VALUES (?, ?, ?, ?, 'active')");
            $stmt->bind_param('ssss', $defaultUsername, $defaultEmail, $hashedPassword, $defaultFullName);
            if ($stmt->execute()) {
                $message = 'Admin user created successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error creating admin user: ' . $conn->error;
                $messageType = 'danger';
            }
            $stmt->close();
        }
    }
}

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Admin User - Palakkad Tourism</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .card {
            max-width: 500px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h2><i class="bi bi-person-plus"></i> Create/Reset Admin User</h2>
                            <p class="text-muted">Palakkad Tourism Admin Panel</p>
                        </div>

                        <?php if (!$adminLoggedIn && ALLOW_CREATE_ADMIN_SCRIPT): ?>
                        <div class="alert alert-warning">
                            <i class="bi bi-shield-lock"></i> Bootstrap mode is enabled (<code>ALLOW_CREATE_ADMIN_SCRIPT</code>). Disable it in <code>config/config.php</code> after you finish.
                        </div>
                        <?php endif; ?>

                        <?php if ($message): ?>
                        <div class="alert alert-<?php echo htmlspecialchars($messageType); ?> alert-dismissible fade show">
                            <i class="bi bi-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-triangle'; ?>"></i>
                            <?php echo htmlspecialchars($message); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php endif; ?>

                        <?php if ($existingUser): ?>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle"></i> Admin user already exists. This will reset the password.
                        </div>
                        <?php else: ?>
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i> No admin user found. This will create a new admin user.
                        </div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <input type="hidden" name="action" value="create">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken(), ENT_QUOTES, 'UTF-8'); ?>">

                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($defaultUsername); ?>" disabled>
                                <small class="text-muted">Default username: admin</small>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="<?php echo htmlspecialchars($defaultEmail); ?>" disabled>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">New Password *</label>
                                <input type="password" class="form-control" name="password" value="<?php echo htmlspecialchars($defaultPassword); ?>" required>
                                <small class="text-muted">Change the default before use in production.</small>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-check-circle"></i>
                                <?php echo $existingUser ? 'Reset Password' : 'Create Admin User'; ?>
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <a href="<?php echo BASE_PATH; ?>/admin/login.php" class="text-decoration-none">
                                <i class="bi bi-arrow-left"></i> Back to Login
                            </a>
                        </div>

                        <hr class="my-4">

                        <div class="alert alert-warning">
                            <strong><i class="bi bi-shield-exclamation"></i> Security</strong><br>
                            <ul class="mb-0 mt-2">
                                <li>Turn off <code>ALLOW_CREATE_ADMIN_SCRIPT</code> after first install.</li>
                                <li>Use <code>config/database.local.php</code> or env <code>PALAKKAD_DB_*</code> for DB credentials.</li>
                                <li>Use a strong admin password.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
