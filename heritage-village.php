<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();
$village = seoLoadEntityByRequest($conn, 'heritage_villages', 'heritage-village', 'heritage-villages.php');
closeDBConnection($conn);

$pageTitle = $village['name'] . ', Palakkad';
$pageDescription = $village['description'] ? substr(strip_tags($village['description']), 0, 160) : "Visit " . $village['name'] . " heritage village in Palakkad, Kerala. Explore this traditional settlement with rich cultural heritage.";
$pageKeywords = $village['name'] . ", heritage village Palakkad, " . $village['location'] . ", Palakkad heritage, traditional village Kerala";
if (!empty($village['image_url'])) {
    $pageOgImage = $village['image_url'];
}

// Breadcrumbs for SEO
$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'Heritage Villages', 'url' => BASE_PATH . '/heritage-villages.php'],
    ['title' => $village['name'], 'url' => '']
];

require_once __DIR__ . '/includes/header.php';
?>
<!-- Heritage Village-specific Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Place",
    "name": "<?php echo htmlspecialchars($village['name']); ?>",
    "description": "<?php echo htmlspecialchars($village['description']); ?>",
    "url": <?php echo json_encode($seoCanonicalUrl, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>,
    <?php if ($village['image_url']): ?>
    "image": "<?php echo htmlspecialchars($village['image_url']); ?>",
    <?php endif; ?>
    "address": {
        "@type": "PostalAddress",
        "addressLocality": "<?php echo htmlspecialchars($village['location'] ?? 'Palakkad'); ?>",
        "addressRegion": "Kerala",
        "addressCountry": "IN"
        <?php if ($village['address']): ?>
        ,"streetAddress": "<?php echo htmlspecialchars($village['address']); ?>"
        <?php endif; ?>
    },
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": "10.7867",
        "longitude": "76.6548"
    }
    <?php if ($village['timings']): ?>
    ,"openingHours": "<?php echo htmlspecialchars($village['timings']); ?>"
    <?php endif; ?>
    <?php if ($village['contact']): ?>
    ,"telephone": "<?php echo htmlspecialchars($village['contact']); ?>"
    <?php endif; ?>
}
</script>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h1 class="display-4 mb-4"><?php echo htmlspecialchars($village['name']); ?></h1>
                
                <?php displayAdSense('content-header', 'max-width: 728px;'); ?>
                
                <?php if ($village['image_url']): ?>
                <div class="mb-4">
                    <img src="<?php echo htmlspecialchars($village['image_url']); ?>" 
                         class="img-fluid rounded shadow" 
                         alt="<?php echo htmlspecialchars($village['name'] . ' - Heritage Village in Palakkad, Kerala'); ?>"
                         style="width: 100%; max-height: 500px; object-fit: cover;">
                </div>
                <?php endif; ?>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">About</h2>
                        <p class="lead"><?php echo nl2br(htmlspecialchars($village['description'])); ?></p>
                    </div>
                </div>

                <?php if ($village['history']): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">History</h2>
                        <p><?php echo nl2br(htmlspecialchars($village['history'])); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($village['special_features']): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Special Features</h2>
                        <p><?php echo nl2br(htmlspecialchars($village['special_features'])); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row g-4 mb-4">
                    <?php if ($village['location']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-geo-alt-fill text-primary"></i> Location
                                </h5>
                                <p class="card-text"><?php echo htmlspecialchars($village['location']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($village['address']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-house text-primary"></i> Address
                                </h5>
                                <p class="card-text"><?php echo htmlspecialchars($village['address']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($village['best_time_to_visit']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-calendar-check text-success"></i> Best Time to Visit
                                </h5>
                                <p class="card-text"><?php echo htmlspecialchars($village['best_time_to_visit']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($village['timings']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-clock text-warning"></i> Timings
                                </h5>
                                <p class="card-text"><?php echo htmlspecialchars($village['timings']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($village['contact']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-telephone text-danger"></i> Contact
                                </h5>
                                <p class="card-text">
                                    <?php if (preg_match('/\d/', $village['contact'])): ?>
                                        <a href="tel:<?php echo htmlspecialchars($village['contact']); ?>"><?php echo htmlspecialchars($village['contact']); ?></a>
                                    <?php else: ?>
                                        <?php echo htmlspecialchars($village['contact']); ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="text-center mt-4">
                    <a href="<?php echo BASE_PATH; ?>/heritage-villages.php" class="btn btn-primary btn-lg">
                        <i class="bi bi-arrow-left"></i> Back to All Heritage Villages
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

