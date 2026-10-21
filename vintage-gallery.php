<?php
require_once __DIR__ . '/config/config.php';

$pageTitle       = 'Vintage Gallery';
$pageDescription = 'Heritage photographs of Palakkad, Kerala — gallery coming soon.';
$pageRobots      = 'noindex, follow';
$pageKeywords    = 'Palakkad vintage photos, Palakkad heritage images, Palakkad history gallery, Kerala vintage photography';

$breadcrumbs = [
    ['title' => 'Home',           'url' => BASE_PATH . '/'],
    ['title' => 'Vintage Gallery','url' => ''],
];

require_once __DIR__ . '/includes/header.php';
?>

<!-- ── Page header ── -->
<div class="vg-header">
    <div class="vg-header-texture" aria-hidden="true"></div>
    <div class="container position-relative">
        <div class="vg-header-inner">
            <div class="vg-header-icon" aria-hidden="true"><i class="bi bi-camera-fill"></i></div>
            <span class="vg-kicker">Palakkad · Heritage &amp; History</span>
            <h1 class="vg-title">Vintage Gallery</h1>
            <p class="vg-lead">A curated window into the forts, temples, valleys, and paddy fields that shaped Palakkad through the ages.</p>
        </div>
    </div>
    <div class="vg-filmstrip" aria-hidden="true">
        <?php for ($i = 0; $i < 28; $i++): ?><span class="vg-film-hole"></span><?php endfor; ?>
    </div>
</div>

<main id="main-content">
<section class="vg-body py-5">
    <div class="container">

        <!-- Coming soon placeholder -->
        <div class="vg-coming-soon">
            <div class="vg-cs-icon" aria-hidden="true"><i class="bi bi-hourglass-split"></i></div>
            <h2 class="vg-cs-title">More photographs coming soon</h2>
            <p class="vg-cs-desc">We are digitising and curating heritage photographs of Palakkad. Check back soon for a growing collection of historic images.</p>

            <div class="vg-cs-links">
                <a href="<?php echo BASE_PATH; ?>/dam-malampuzha.php#vintage-photos" class="vg-cs-card">
                    <span class="vg-cs-card-icon"><i class="bi bi-water"></i></span>
                    <span class="vg-cs-card-body">
                        <strong>Malampuzha Dam</strong>
                        <small>6 vintage photographs · 1949–1955</small>
                    </span>
                    <i class="bi bi-arrow-right vg-cs-card-arrow"></i>
                </a>
            </div>

            <div class="vg-contribute mt-4">
                <div class="vg-contribute-inner">
                    <i class="bi bi-envelope-paper-fill vg-contribute-icon"></i>
                    <div>
                        <div class="vg-contribute-title">Have an old photograph of Palakkad?</div>
                        <div class="vg-contribute-desc">We'd love to feature your heritage images. Send them to us and help preserve Palakkad's visual history.</div>
                    </div>
                    <a href="mailto:<?php echo htmlspecialchars(SITE_EMAIL); ?>?subject=Vintage%20Gallery%20Submission" class="vg-contribute-btn">
                        <i class="bi bi-send me-1"></i> Share a photo
                    </a>
                </div>
            </div>
        </div>

    </div>
</section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
