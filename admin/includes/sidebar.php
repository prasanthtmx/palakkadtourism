<?php
$adminCurrent = basename($_SERVER['PHP_SELF'] ?? '');
$adminName = trim((string) ($_SESSION['admin_name'] ?? $_SESSION['admin_username'] ?? 'Admin'));
$adminNavActive = static function (array $files) use ($adminCurrent): string {
    return in_array($adminCurrent, $files, true) ? ' active' : '';
};
?>
<button class="admin-menu-toggle" type="button" aria-label="Open menu" data-admin-menu>
    <i class="bi bi-list"></i>
</button>
<div class="admin-overlay" data-admin-overlay hidden></div>
<aside class="admin-sidebar">
    <div class="admin-brand">
        <a href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/">
            <img src="<?php echo htmlspecialchars(BASE_PATH); ?>/assets/images/palakkad-logo.png" alt="Palakkad Tourism">
            <span>
                <strong>Admin</strong>
                <small>Palakkad Tourism</small>
            </span>
        </a>
    </div>

    <nav class="admin-nav" aria-label="Admin">
        <p class="admin-nav-label">Overview</p>
        <a class="admin-nav-link<?php echo $adminNavActive(['index.php']); ?>" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/">
            <i class="bi bi-grid-1x2"></i> Dashboard
        </a>

        <p class="admin-nav-label">Content</p>
        <a class="admin-nav-link<?php echo $adminNavActive(['temples.php']); ?>" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/temples.php">
            <i class="bi bi-building"></i> Temples
        </a>
        <a class="admin-nav-link<?php echo $adminNavActive(['churches.php']); ?>" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/churches.php">
            <i class="bi bi-church"></i> Churches
        </a>
        <a class="admin-nav-link<?php echo $adminNavActive(['mosques.php']); ?>" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/mosques.php">
            <i class="bi bi-moon-stars"></i> Mosques
        </a>
        <a class="admin-nav-link<?php echo $adminNavActive(['festivals.php']); ?>" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/festivals.php">
            <i class="bi bi-calendar-event"></i> Festivals
        </a>
        <a class="admin-nav-link<?php echo $adminNavActive(['places.php']); ?>" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/places.php">
            <i class="bi bi-geo-alt"></i> Places
        </a>
        <a class="admin-nav-link<?php echo $adminNavActive(['hospitals.php']); ?>" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/hospitals.php">
            <i class="bi bi-hospital"></i> Hospitals
        </a>
        <a class="admin-nav-link<?php echo $adminNavActive(['heritage-villages.php']); ?>" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/heritage-villages.php">
            <i class="bi bi-house-heart"></i> Heritage villages
        </a>
        <a class="admin-nav-link<?php echo $adminNavActive(['institutions.php']); ?>" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/institutions.php">
            <i class="bi bi-mortarboard"></i> Institutions
        </a>
        <a class="admin-nav-link<?php echo $adminNavActive(['directory.php']); ?>" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/directory.php">
            <i class="bi bi-journal-text"></i> Yellow Pages
        </a>
        <a class="admin-nav-link<?php echo $adminNavActive(['officials.php']); ?>" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/officials.php">
            <i class="bi bi-person-badge"></i> District officials
        </a>

        <p class="admin-nav-label">Tools</p>
        <a class="admin-nav-link<?php echo $adminNavActive(['import_temples.php']); ?>" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/import_temples.php">
            <i class="bi bi-upload"></i> Import temples
        </a>
        <a class="admin-nav-link<?php echo $adminNavActive(['fetch_temple_images.php']); ?>" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/fetch_temple_images.php">
            <i class="bi bi-image"></i> Temple images
        </a>
        <a class="admin-nav-link<?php echo $adminNavActive(['update_mosques.php']); ?>" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/update_mosques.php">
            <i class="bi bi-arrow-repeat"></i> Update mosques
        </a>
    </nav>

    <div class="admin-sidebar-foot">
        <div class="admin-user">
            <span class="admin-user-avatar" aria-hidden="true"><?php echo htmlspecialchars(strtoupper(substr($adminName, 0, 1))); ?></span>
            <span class="admin-user-meta">
                <strong><?php echo htmlspecialchars($adminName); ?></strong>
                <small>Signed in</small>
            </span>
        </div>
        <a class="admin-nav-link<?php echo $adminNavActive(['reset-password.php']); ?>" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/reset-password.php">
            <i class="bi bi-key"></i> Reset password
        </a>
        <a class="admin-nav-link" href="<?php echo htmlspecialchars(BASE_PATH); ?>/" target="_blank" rel="noopener">
            <i class="bi bi-box-arrow-up-right"></i> View website
        </a>
        <a class="admin-nav-link admin-nav-link--out" href="<?php echo htmlspecialchars(BASE_PATH); ?>/admin/logout.php">
            <i class="bi bi-box-arrow-right"></i> Sign out
        </a>
    </div>
</aside>
<script>
(function () {
    var toggle = document.querySelector('[data-admin-menu]');
    var overlay = document.querySelector('[data-admin-overlay]');
    if (!toggle || !overlay) return;
    function closeMenu() {
        document.body.classList.remove('admin-nav-open');
        overlay.hidden = true;
    }
    toggle.addEventListener('click', function () {
        var open = document.body.classList.toggle('admin-nav-open');
        overlay.hidden = !open;
    });
    overlay.addEventListener('click', closeMenu);
})();
</script>
