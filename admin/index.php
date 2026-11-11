<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/security.php';

requireAdminAuth();

$conn = getDBConnection();

$adminCount = static function ($conn, $table) {
    $table = preg_replace('/[^a-z0-9_]/i', '', (string) $table);
    if ($table === '') {
        return 0;
    }
    $res = $conn->query('SELECT COUNT(*) AS c FROM `' . $table . '`');
    if (!$res) {
        return 0;
    }
    $row = $res->fetch_assoc();
    return (int) ($row['c'] ?? 0);
};

$counts = [
    'temples' => $adminCount($conn, 'temples'),
    'churches' => $adminCount($conn, 'churches'),
    'mosques' => $adminCount($conn, 'mosques'),
    'festivals' => $adminCount($conn, 'festivals'),
    'places' => $adminCount($conn, 'places'),
    'hospitals' => $adminCount($conn, 'hospitals'),
    'heritage' => $adminCount($conn, 'heritage_villages'),
    'institutions' => $adminCount($conn, 'institutions'),
    'directory' => $adminCount($conn, 'directory_entries'),
];

closeDBConnection($conn);

$adminName = trim((string) ($_SESSION['admin_name'] ?? $_SESSION['admin_username'] ?? 'Admin'));
$adminPageTitle = 'Dashboard';
require __DIR__ . '/includes/head.php';
?>
<body class="admin-page">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <main class="admin-main">
        <div class="admin-hero">
            <div>
                <h1>Dashboard</h1>
                <p>Welcome back, <?php echo htmlspecialchars($adminName); ?>.</p>
            </div>
            <a href="<?php echo htmlspecialchars(BASE_PATH); ?>/" class="btn btn-outline-secondary btn-sm" target="_blank" rel="noopener">
                <i class="bi bi-box-arrow-up-right"></i> View website
            </a>
        </div>

        <div class="adm-stats">
            <a class="adm-stat" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/temples.php">
                <div>
                    <span>Temples</span>
                    <strong><?php echo $counts['temples']; ?></strong>
                    <small>Manage</small>
                </div>
                <div class="adm-stat-icon"><i class="bi bi-building"></i></div>
            </a>
            <a class="adm-stat" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/churches.php">
                <div>
                    <span>Churches</span>
                    <strong><?php echo $counts['churches']; ?></strong>
                    <small>Manage</small>
                </div>
                <div class="adm-stat-icon is-gold"><i class="bi bi-church"></i></div>
            </a>
            <a class="adm-stat" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/mosques.php">
                <div>
                    <span>Mosques</span>
                    <strong><?php echo $counts['mosques']; ?></strong>
                    <small>Manage</small>
                </div>
                <div class="adm-stat-icon is-sky"><i class="bi bi-moon-stars"></i></div>
            </a>
            <a class="adm-stat" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/festivals.php">
                <div>
                    <span>Festivals</span>
                    <strong><?php echo $counts['festivals']; ?></strong>
                    <small>Manage</small>
                </div>
                <div class="adm-stat-icon is-clay"><i class="bi bi-calendar-event"></i></div>
            </a>
            <a class="adm-stat" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/places.php">
                <div>
                    <span>Places</span>
                    <strong><?php echo $counts['places']; ?></strong>
                    <small>Manage</small>
                </div>
                <div class="adm-stat-icon"><i class="bi bi-geo-alt"></i></div>
            </a>
            <a class="adm-stat" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/hospitals.php">
                <div>
                    <span>Hospitals</span>
                    <strong><?php echo $counts['hospitals']; ?></strong>
                    <small>Manage</small>
                </div>
                <div class="adm-stat-icon is-sky"><i class="bi bi-hospital"></i></div>
            </a>
            <a class="adm-stat" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/heritage-villages.php">
                <div>
                    <span>Heritage villages</span>
                    <strong><?php echo $counts['heritage']; ?></strong>
                    <small>Manage</small>
                </div>
                <div class="adm-stat-icon is-gold"><i class="bi bi-house-heart"></i></div>
            </a>
            <a class="adm-stat" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/institutions.php">
                <div>
                    <span>Institutions</span>
                    <strong><?php echo $counts['institutions']; ?></strong>
                    <small>Manage</small>
                </div>
                <div class="adm-stat-icon"><i class="bi bi-mortarboard"></i></div>
            </a>
            <a class="adm-stat" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/directory.php">
                <div>
                    <span>Yellow Pages</span>
                    <strong><?php echo $counts['directory']; ?></strong>
                    <small>Manage</small>
                </div>
                <div class="adm-stat-icon is-clay"><i class="bi bi-journal-text"></i></div>
            </a>
        </div>

        <div class="adm-panel">
            <h2>Quick actions</h2>
            <div class="adm-quick">
                <a href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/temples.php">Add a temple</a>
                <a href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/places.php">Add a place</a>
                <a href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/festivals.php">Add a festival</a>
                <a href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/directory.php">Add a listing</a>
                <a href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/hospitals.php">Add a hospital</a>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
