<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/security.php';

// If already logged in, redirect to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: index.php');
    exit;
}

$error = '';
$username = '';
$publicBase = preg_replace('#/admin$#', '', (string) BASE_PATH);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = sanitizeInput($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Check rate limiting
    if (!checkLoginRateLimit($username)) {
        $error = 'Too many login attempts. Please try again in 15 minutes.';
    } elseif (!empty($username) && !empty($password)) {
        $conn = getDBConnection();
        $stmt = $conn->prepare("SELECT id, username, password, full_name, status FROM admin_users WHERE username = ? AND status = 'active'");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                // Successful login
                recordLoginAttempt($username, true);
                
                // Regenerate session ID on login
                session_regenerate_id(true);
                
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];
                $_SESSION['admin_name'] = $user['full_name'];
                $_SESSION['login_time'] = time();
                
                // Update last login
                $updateStmt = $conn->prepare("UPDATE admin_users SET last_login = NOW() WHERE id = ?");
                $updateStmt->bind_param("i", $user['id']);
                $updateStmt->execute();
                $updateStmt->close();
                
                header('Location: index.php');
                exit;
            } else {
                recordLoginAttempt($username, false);
                $error = 'Invalid username or password';
            }
        } else {
            recordLoginAttempt($username, false);
            $error = 'Invalid username or password';
        }
        $stmt->close();
        closeDBConnection($conn);
    } else {
        $error = 'Please enter both username and password';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Palakkad Tourism</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/png" href="<?php echo htmlspecialchars($publicBase); ?>/assets/images/palakkad-logo.png">
    <style>
        :root {
            --ink: #14202b;
            --muted: #5c6b76;
            --line: #d5ddd8;
            --bg: #f3f1ea;
            --card: #ffffff;
            --accent: #0a4d3f;
        }
        * { box-sizing: border-box; }
        html, body { min-height: 100%; }
        body {
            margin: 0;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            background: var(--bg);
            color: var(--ink);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .login {
            width: 100%;
            max-width: 22rem;
        }
        .login-brand {
            text-align: center;
            margin-bottom: 1.25rem;
        }
        .login-brand img {
            display: block;
            height: 52px;
            width: auto;
            margin: 0 auto 0.85rem;
        }
        h1 {
            margin: 0 0 0.2rem;
            font-size: 1.25rem;
            font-weight: 650;
            letter-spacing: -0.02em;
        }
        .login-brand p {
            margin: 0;
            color: var(--muted);
            font-size: 0.9rem;
        }
        .login-card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 1.4rem 1.3rem 1.3rem;
        }
        label {
            display: block;
            font-size: 0.84rem;
            font-weight: 600;
            margin: 0 0 0.35rem;
        }
        input {
            width: 100%;
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 0.65rem 0.75rem;
            font: inherit;
            color: var(--ink);
            background: #fff;
            margin-bottom: 0.95rem;
        }
        input:focus {
            outline: 2px solid var(--accent);
            outline-offset: 1px;
            border-color: var(--accent);
        }
        button {
            width: 100%;
            border: 0;
            border-radius: 8px;
            background: var(--accent);
            color: #fff;
            font: inherit;
            font-weight: 600;
            padding: 0.7rem 1rem;
            cursor: pointer;
        }
        button:hover { background: #083d32; }
        .login-error {
            background: #fdecea;
            color: #8a1f1f;
            border-radius: 8px;
            padding: 0.65rem 0.75rem;
            font-size: 0.9rem;
            margin-bottom: 0.95rem;
        }
        .login-back {
            display: block;
            text-align: center;
            margin-top: 1.05rem;
            color: var(--muted);
            text-decoration: none;
            font-size: 0.9rem;
        }
        .login-back:hover { color: var(--ink); }
    </style>
</head>
<body>
    <main class="login">
        <div class="login-brand">
            <img src="<?php echo htmlspecialchars($publicBase); ?>/assets/images/palakkad-logo.png" alt="Palakkad Tourism">
            <h1>Admin login</h1>
            <p>Palakkad Tourism</p>
        </div>
        <div class="login-card">
            <?php if ($error): ?>
            <div class="login-error" role="alert"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" autocomplete="username" required autofocus>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" autocomplete="current-password" required>
                <button type="submit">Sign in</button>
            </form>
        </div>
        <a class="login-back" href="<?php echo htmlspecialchars($publicBase); ?>/">Back to website</a>
    </main>
</body>
</html>
