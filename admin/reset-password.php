<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/security.php';

requireAdminAuth();

$adminId = (int) ($_SESSION['admin_id'] ?? 0);
$adminUsername = (string) ($_SESSION['admin_username'] ?? '');
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $message = 'Invalid security token. Please try again.';
        $messageType = 'danger';
    } else {
        $current = (string) ($_POST['current_password'] ?? '');
        $new = (string) ($_POST['new_password'] ?? '');
        $confirm = (string) ($_POST['confirm_password'] ?? '');

        if ($current === '' || $new === '' || $confirm === '') {
            $message = 'Please fill in all password fields.';
            $messageType = 'danger';
        } elseif ($new !== $confirm) {
            $message = 'New password and confirmation do not match.';
            $messageType = 'danger';
        } elseif (strlen($new) < 8) {
            $message = 'New password must be at least 8 characters.';
            $messageType = 'danger';
        } elseif ($new === $current) {
            $message = 'New password must be different from the current password.';
            $messageType = 'danger';
        } elseif ($adminId < 1) {
            $message = 'Your session is invalid. Please sign in again.';
            $messageType = 'danger';
        } else {
            $conn = getDBConnection();
            $stmt = $conn->prepare("SELECT id, password FROM admin_users WHERE id = ? AND status = 'active' LIMIT 1");
            $stmt->bind_param('i', $adminId);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if (!$user || !password_verify($current, $user['password'])) {
                $message = 'Current password is incorrect.';
                $messageType = 'danger';
            } else {
                $hash = password_hash($new, PASSWORD_DEFAULT);
                $update = $conn->prepare('UPDATE admin_users SET password = ? WHERE id = ?');
                $update->bind_param('si', $hash, $adminId);
                if ($update->execute()) {
                    session_regenerate_id(true);
                    $message = 'Password updated. Use the new password the next time you sign in.';
                    $messageType = 'success';
                } else {
                    $message = 'Could not update the password. Please try again.';
                    $messageType = 'danger';
                }
                $update->close();
            }
            closeDBConnection($conn);
        }
    }
}

$adminPageTitle = 'Reset password';
require __DIR__ . '/includes/head.php';
?>
<body class="admin-page">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <main class="admin-main">
        <div class="admin-hero">
            <div>
                <h1>Reset password</h1>
                <p>Change the password for <?php echo htmlspecialchars($adminUsername !== '' ? $adminUsername : 'this admin account'); ?>.</p>
            </div>
        </div>

        <div class="adm-panel adm-password-panel">
            <?php if ($message): ?>
            <div class="alert alert-<?php echo htmlspecialchars($messageType); ?> alert-dismissible fade show">
                <?php echo htmlspecialchars($message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <form method="POST" action="" autocomplete="off">
                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken(), ENT_QUOTES, 'UTF-8'); ?>">

                <div class="mb-3">
                    <label class="form-label" for="current_password">Current password</label>
                    <input class="form-control" type="password" id="current_password" name="current_password" required autocomplete="current-password">
                </div>
                <div class="mb-3">
                    <label class="form-label" for="new_password">New password</label>
                    <input class="form-control" type="password" id="new_password" name="new_password" required minlength="8" autocomplete="new-password">
                    <div class="form-text">At least 8 characters.</div>
                </div>
                <div class="mb-4">
                    <label class="form-label" for="confirm_password">Confirm new password</label>
                    <input class="form-control" type="password" id="confirm_password" name="confirm_password" required minlength="8" autocomplete="new-password">
                </div>
                <button class="btn btn-primary" type="submit">Update password</button>
            </form>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
