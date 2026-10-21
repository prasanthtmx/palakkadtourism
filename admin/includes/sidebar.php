<nav class="col-md-3 col-lg-2 admin-sidebar px-0">
    <div class="position-sticky pt-2">
        <h5 class="text-white text-center mb-2">Admin Panel</h5>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>" href="<?php echo BASE_PATH; ?>/admin/">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'temples.php' ? 'active' : ''; ?>" href="<?php echo BASE_PATH; ?>/admin/temples.php">
                    <i class="bi bi-building"></i> Temples
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'import_temples.php' ? 'active' : ''; ?>" href="<?php echo BASE_PATH; ?>/admin/import_temples.php">
                    <i class="bi bi-upload"></i> Import Temples
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'fetch_temple_images.php' ? 'active' : ''; ?>" href="<?php echo BASE_PATH; ?>/admin/fetch_temple_images.php">
                    <i class="bi bi-image"></i> Fetch Temple Images
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'churches.php' ? 'active' : ''; ?>" href="<?php echo BASE_PATH; ?>/admin/churches.php">
                    <i class="bi bi-church"></i> Churches
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'mosques.php' ? 'active' : ''; ?>" href="<?php echo BASE_PATH; ?>/admin/mosques.php">
                    <i class="bi bi-building"></i> Mosques
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'update_mosques.php' ? 'active' : ''; ?>" href="<?php echo BASE_PATH; ?>/admin/update_mosques.php">
                    <i class="bi bi-arrow-repeat"></i> Update Mosques
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'festivals.php' ? 'active' : ''; ?>" href="<?php echo BASE_PATH; ?>/admin/festivals.php">
                    <i class="bi bi-calendar-event"></i> Festivals
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'places.php' ? 'active' : ''; ?>" href="<?php echo BASE_PATH; ?>/admin/places.php">
                    <i class="bi bi-geo-alt"></i> Places
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'hospitals.php' ? 'active' : ''; ?>" href="<?php echo BASE_PATH; ?>/admin/hospitals.php">
                    <i class="bi bi-hospital"></i> Hospitals
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'heritage-villages.php' ? 'active' : ''; ?>" href="<?php echo BASE_PATH; ?>/admin/heritage-villages.php">
                    <i class="bi bi-house-heart"></i> Heritage Villages
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'institutions.php' ? 'active' : ''; ?>" href="<?php echo BASE_PATH; ?>/admin/institutions.php">
                    <i class="bi bi-mortarboard"></i> Institutions
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'directory.php' ? 'active' : ''; ?>" href="<?php echo BASE_PATH; ?>/admin/directory.php">
                    <i class="bi bi-journal-text"></i> Yellow Pages
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

