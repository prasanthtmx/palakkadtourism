<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/security.php';

requireAdminAuth();

$conn = getDBConnection();
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_mosques'])) {
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $message = 'Invalid security token. Please try again.';
        $messageType = 'danger';
    } else {
        $sqlResolved = adminResolveBundledSqlFile('mosques_update.sql');
        if ($sqlResolved === null) {
            $message = 'Bundled SQL file is missing or not allowed.';
            $messageType = 'danger';
        } else {
            $sql = file_get_contents($sqlResolved);
            if ($sql === false || $sql === '') {
                $message = 'Could not read SQL file.';
                $messageType = 'danger';
            } elseif ($conn->multi_query($sql)) {
                do {
                    if ($result = $conn->store_result()) {
                        $result->free();
                    }
                } while ($conn->next_result());

                $message = 'Mosques updated successfully! All existing mosques removed and 3 new mosques added.';
                $messageType = 'success';
            } else {
                $message = 'Error updating mosques: ' . $conn->error;
                $messageType = 'danger';
            }
        }
    }
}

// Get current mosque count
$mosqueCount = $conn->query("SELECT COUNT(*) as count FROM mosques")->fetch_assoc()['count'];
$currentMosques = $conn->query("SELECT name, location FROM mosques ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Mosques - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 0;
            margin-bottom: 2rem;
        }
    </style>
</head>
<body class="admin-page">
    <?php require_once __DIR__ . '/includes/sidebar.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="admin-header">
                    <div class="container">
                        <h2><i class="bi bi-building"></i> Update Mosques</h2>
                    </div>
                </div>
                
                <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="bi bi-exclamation-triangle"></i> Important Notice</h5>
                    </div>
                    <div class="card-body">
                        <p class="lead"><strong>This will remove ALL existing mosques and add 3 new mosques:</strong></p>
                        <ol>
                            <li><strong>Parakunnam Mosque</strong> - Near Govt. Victoria College, Palakkad</li>
                            <li><strong>Mount Seena School Mosque</strong> - 14th mile near to Pathiripala</li>
                            <li><strong>Daira Street Urdu Juma Masjid</strong> - Palakkad</li>
                        </ol>
                        <div class="alert alert-info">
                            <strong>Note:</strong> To add more mosques near your place, please send an email to the website administrator.
                        </div>
                    </div>
                </div>
                
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-info-circle"></i> Current Status</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Current mosques in database:</strong> <?php echo $mosqueCount; ?></p>
                        <?php if (!empty($currentMosques)): ?>
                        <h6>Existing Mosques:</h6>
                        <ul>
                            <?php foreach ($currentMosques as $mosque): ?>
                            <li><?php echo htmlspecialchars($mosque['name']); ?> - <?php echo htmlspecialchars($mosque['location']); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="bi bi-arrow-repeat"></i> Update Mosques</h5>
                    </div>
                    <div class="card-body">
                        <p class="lead">Click the button below to remove all existing mosques and add the 3 new mosques.</p>
                        <form method="POST" onsubmit="return confirm('WARNING: This will DELETE all existing mosques and add 3 new ones. Are you sure you want to continue?');">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken(), ENT_QUOTES, 'UTF-8'); ?>">
                            <button type="submit" name="update_mosques" class="btn btn-danger btn-lg">
                                <i class="bi bi-arrow-repeat"></i> Update Mosques (Remove All & Add New)
                            </button>
                            <a href="mosques.php" class="btn btn-secondary btn-lg">
                                <i class="bi bi-arrow-left"></i> Back to Mosques
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


