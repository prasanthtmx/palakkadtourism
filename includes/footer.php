    <!-- Welcome Message Bar -->
    <section class="welcome-message-bar py-4" aria-label="Visitor notice">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-12">
                    <p class="mb-0 welcome-message-text">
                        <span class="welcome-message-icon" aria-hidden="true"><i class="bi bi-heart-fill"></i></span>
                        Welcome to Palakkad! Thank you for appreciating the beauty of Palakkad everywhere. While exploring this wonderful place, kindly refrain from throwing plastic bottles or covers on the paddy fields or roadside. Let’s keep Palakkad clean and preserve its natural charm.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <footer class="site-footer text-light mt-0" role="contentinfo">
        <div class="site-footer-accent" aria-hidden="true"></div>
        <div class="container position-relative">
            <div class="row gx-lg-5 pt-4 pt-md-5 pb-2">
                <div class="col-md-12 col-lg-4 mb-4 mb-lg-0">
                    <h5 class="site-footer-heading">Palakkad Tourism</h5>
                    <p class="site-footer-tagline text-uppercase">Kerala’s heritage gateway</p>
                    <p class="site-footer-lead">Discover the beauty and heritage of Palakkad, Kerala. Explore temples, churches, mosques, festivals, and tourist attractions.</p>
                    <p class="site-footer-notice">
                        <i class="bi bi-leaf me-2" aria-hidden="true"></i>Please keep paddy fields and roadsides free of plastic. Let’s preserve Palakkad’s natural charm together.
                    </p>
                </div>
                <div class="col-md-7 col-lg-5 mb-4 mb-lg-0">
                    <h5 class="site-footer-heading">Quick links</h5>
                    <ul class="list-unstyled site-footer-nav">
                        <li><a href="<?php echo BASE_PATH; ?>/temples.php" class="site-footer-link">Temples</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>/churches.php" class="site-footer-link">Churches</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>/mosques.php" class="site-footer-link">Mosques</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>/jain-temple.php" class="site-footer-link">Jain Temple</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>/festivals.php" class="site-footer-link">Festivals</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>/places.php" class="site-footer-link">Places to Visit</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>/heritage-villages.php" class="site-footer-link">Heritage Village</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>/about.php" class="site-footer-link">About Palakkad</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>/how-to-reach.php" class="site-footer-link">How to Reach</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>/vintage-gallery.php" class="site-footer-link">Vintage Gallery</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>/art-culture.php" class="site-footer-link">Art and Culture</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>/customs.php" class="site-footer-link">Customs</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>/villages-taluks.php" class="site-footer-link">Villages and Taluks</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>/history.php" class="site-footer-link">History</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>/fort.php" class="site-footer-link">Fort</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>/rivers.php" class="site-footer-link">Rivers</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>/mountain-ranges-passes.php" class="site-footer-link">Mountain Ranges &amp; Passes</a></li>
                        <li><a href="<?php echo BASE_PATH; ?>/hospitals.php" class="site-footer-link">Hospitals</a></li>
                    </ul>
                </div>
                <div class="col-md-5 col-lg-3 mb-0">
                    <h5 class="site-footer-heading">Contact</h5>
                    <p class="site-footer-contact"><i class="bi bi-envelope me-2" aria-hidden="true"></i><a href="mailto:<?php echo htmlspecialchars(SITE_EMAIL); ?>" class="site-footer-link site-footer-link-inline"><?php echo htmlspecialchars(SITE_EMAIL); ?></a></p>
                    <p class="site-footer-contact"><i class="bi bi-globe me-2" aria-hidden="true"></i><a href="<?php echo htmlspecialchars(SITE_URL); ?>" class="site-footer-link site-footer-link-inline"><?php echo htmlspecialchars(SITE_URL); ?></a></p>
                    <div class="site-footer-social d-flex gap-2 mt-4">
                        <a href="https://www.facebook.com/palakkadtourism" target="_blank" rel="noopener noreferrer" class="site-footer-social-btn" aria-label="Facebook"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="https://x.com/palakkadtourism" target="_blank" rel="noopener noreferrer" class="site-footer-social-btn" aria-label="X (formerly Twitter)"><i class="bi bi-x-lg fs-5"></i></a>
                    </div>
                </div>
            </div>
            <div class="site-footer-divider" role="presentation"></div>
            <div class="row pb-4 pb-md-5">
                <div class="col-12 text-center">
                    <p class="site-footer-copyright mb-0">&copy; 2004&ndash;<?php echo date('Y'); ?> Palakkad Tourism. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="<?php echo BASE_PATH; ?>/assets/js/main.js"></script>
    
    <!-- Meta Messenger Chat Plugin (optional file — avoids fatal if missing on server) -->
    <?php
    $messengerFile = __DIR__ . '/messenger_chat.php';
    if (is_readable($messengerFile)) {
        require_once $messengerFile;
        if (function_exists('initMessengerChat')) {
            initMessengerChat('palakkadtourism');
        }
    }
    ?>
</body>
</html>

