<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();
$mosque = seoLoadEntityByRequest($conn, 'mosques', 'mosque', 'mosques.php');
closeDBConnection($conn);

$pageTitle = $mosque['name'] . ', Palakkad';
$pageDescription = $mosque['description'] ? substr(strip_tags($mosque['description']), 0, 160) : "Visit " . $mosque['name'] . " mosque in Palakkad, Kerala. Explore this beautiful place of worship.";
$pageKeywords = $mosque['name'] . ", mosque Palakkad, " . $mosque['location'] . ", Palakkad mosques, Islamic mosque Kerala";
if (!empty($mosque['image_url'])) {
    $pageOgImage = $mosque['image_url'];
}

// Breadcrumbs for SEO
$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'Mosques', 'url' => BASE_PATH . '/mosques.php'],
    ['title' => $mosque['name'], 'url' => '']
];

require_once __DIR__ . '/includes/header.php';
?>
<!-- Mosque-specific Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "PlaceOfWorship",
    "name": "<?php echo htmlspecialchars($mosque['name']); ?>",
    "description": "<?php echo htmlspecialchars($mosque['description']); ?>",
    "url": <?php echo json_encode($seoCanonicalUrl, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>,
    <?php if ($mosque['image_url']): ?>
    "image": "<?php echo htmlspecialchars($mosque['image_url']); ?>",
    <?php endif; ?>
    "address": {
        "@type": "PostalAddress",
        "addressLocality": "<?php echo htmlspecialchars($mosque['location'] ?? 'Palakkad'); ?>",
        "addressRegion": "Kerala",
        "addressCountry": "IN"
        <?php if ($mosque['address']): ?>
        ,"streetAddress": "<?php echo htmlspecialchars($mosque['address']); ?>"
        <?php endif; ?>
    },
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": "10.7867",
        "longitude": "76.6548"
    }
    <?php if ($mosque['timings']): ?>
    ,"openingHours": "<?php echo htmlspecialchars($mosque['timings']); ?>"
    <?php endif; ?>
    <?php if ($mosque['contact']): ?>
    ,"telephone": "<?php echo htmlspecialchars($mosque['contact']); ?>"
    <?php endif; ?>
}
</script>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h1 class="display-4 mb-4"><?php echo htmlspecialchars($mosque['name']); ?></h1>
                
                <?php displayAdSense('content-header', 'max-width: 728px;'); ?>
                
                <?php if ($mosque['image_url']): ?>
                <div class="mb-4">
                    <img src="<?php echo htmlspecialchars($mosque['image_url']); ?>" 
                         class="img-fluid rounded shadow" 
                         alt="<?php echo htmlspecialchars($mosque['name'] . ' - Mosque in Palakkad, Kerala'); ?>"
                         style="width: 100%; max-height: 500px; object-fit: cover;">
                </div>
                <?php endif; ?>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">About</h2>
                        <p class="lead"><?php echo nl2br(htmlspecialchars($mosque['description'])); ?></p>
                    </div>
                </div>

                <?php if ($mosque['history']): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">History</h2>
                        <p><?php echo nl2br(htmlspecialchars($mosque['history'])); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($mosque['special_features']): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Special Features</h2>
                        <p><?php echo nl2br(htmlspecialchars($mosque['special_features'])); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row g-4 mb-4">
                    <?php if ($mosque['location']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-geo-alt-fill text-info"></i> Location
                                </h5>
                                <p class="card-text"><?php echo htmlspecialchars($mosque['location']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($mosque['address']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-house text-info"></i> Address
                                </h5>
                                <p class="card-text"><?php echo htmlspecialchars($mosque['address']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($mosque['timings']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-clock text-warning"></i> Timings
                                </h5>
                                <p class="card-text"><?php echo htmlspecialchars($mosque['timings']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($mosque['contact']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-telephone text-danger"></i> Contact
                                </h5>
                                <p class="card-text">
                                    <?php if (preg_match('/\d/', $mosque['contact'])): ?>
                                        <a href="tel:<?php echo htmlspecialchars($mosque['contact']); ?>"><?php echo htmlspecialchars($mosque['contact']); ?></a>
                                    <?php else: ?>
                                        <?php echo htmlspecialchars($mosque['contact']); ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="text-center mt-4">
                    <a href="<?php echo BASE_PATH; ?>/mosques.php" class="btn btn-info btn-lg">
                        <i class="bi bi-arrow-left"></i> Back to All Mosques
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

