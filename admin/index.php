<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

$conn = getDBConnection();

// Get statistics
$templeCount = $conn->query("SELECT COUNT(*) as count FROM temples")->fetch_assoc()['count'];
$churchCount = $conn->query("SELECT COUNT(*) as count FROM churches")->fetch_assoc()['count'];
$mosqueCount = $conn->query("SELECT COUNT(*) as count FROM mosques")->fetch_assoc()['count'];
$festivalCount = $conn->query("SELECT COUNT(*) as count FROM festivals")->fetch_assoc()['count'];
$placeCount = $conn->query("SELECT COUNT(*) as count FROM places")->fetch_assoc()['count'];

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Palakkad Tourism</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/assets/css/style.css">
</head>
<body class="admin-page">
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 admin-sidebar px-0">
                <div class="position-sticky pt-3">
                    <h4 class="text-white text-center mb-4">Admin Panel</h4>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="<?php echo BASE_PATH; ?>/admin/">
                                <i class="bi bi-speedometer2"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_PATH; ?>/admin/temples.php">
                                <i class="bi bi-building"></i> Temples
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_PATH; ?>/admin/churches.php">
                                <i class="bi bi-church"></i> Churches
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_PATH; ?>/admin/mosques.php">
                                <i class="bi bi-building"></i> Mosques
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_PATH; ?>/admin/festivals.php">
                                <i class="bi bi-calendar-event"></i> Festivals
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo BASE_PATH; ?>/admin/places.php">
                                <i class="bi bi-geo-alt"></i> Places
                            </a>
                        </li>
                        <li class="nav-item mt-3">
                            <a class="nav-link" href="<?php echo BASE_PATH; ?>/admin/logout.php">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-2 mb-2 border-bottom">
                    <div class="d-flex align-items-center">
                        <img src="<?php echo BASE_PATH; ?>/assets/images/palakkad-logo.png" alt="Palakkad Tourism" class="me-2" style="max-height: 40px; width: auto;">
                        <h1 class="h4 mb-0">Dashboard</h1>
                    </div>
                    <div class="btn-toolbar mb-0">
                        <a href="<?php echo BASE_PATH; ?>/" class="btn btn-sm btn-outline-secondary" target="_blank">
                            <i class="bi bi-eye"></i> View Website
                        </a>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-4 mb-3">
                        <div class="card text-white bg-primary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title">Temples</h5>
                                        <h2 class="mb-0"><?php echo $templeCount; ?></h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-building fs-1"></i>
                                    </div>
                                </div>
                                <a href="<?php echo BASE_PATH; ?>/admin/temples.php" class="text-white text-decoration-none">Manage <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card text-white bg-success">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title">Churches</h5>
                                        <h2 class="mb-0"><?php echo $churchCount; ?></h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-church fs-1"></i>
                                    </div>
                                </div>
                                <a href="<?php echo BASE_PATH; ?>/admin/churches.php" class="text-white text-decoration-none">Manage <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card text-white bg-info">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title">Mosques</h5>
                                        <h2 class="mb-0"><?php echo $mosqueCount; ?></h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-building fs-1"></i>
                                    </div>
                                </div>
                                <a href="<?php echo BASE_PATH; ?>/admin/mosques.php" class="text-white text-decoration-none">Manage <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card text-white bg-warning">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title">Festivals</h5>
                                        <h2 class="mb-0"><?php echo $festivalCount; ?></h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-calendar-event fs-1"></i>
                                    </div>
                                </div>
                                <a href="<?php echo BASE_PATH; ?>/admin/festivals.php" class="text-white text-decoration-none">Manage <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card text-white bg-secondary">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h5 class="card-title">Places</h5>
                                        <h2 class="mb-0"><?php echo $placeCount; ?></h2>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="bi bi-geo-alt fs-1"></i>
                                    </div>
                                </div>
                                <a href="<?php echo BASE_PATH; ?>/admin/places.php" class="text-white text-decoration-none">Manage <i class="bi bi-arrow-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Welcome to the Admin Panel. Use the sidebar to manage temples, churches, mosques, festivals, and places.
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?php echo BASE_PATH; ?>/assets/js/main.js"></script>
</body>
</html>

