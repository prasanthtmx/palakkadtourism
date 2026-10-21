<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();
$festival = seoLoadEntityByRequest($conn, 'festivals', 'festival', 'festivals.php');
closeDBConnection($conn);

$pageTitle = $festival['name'] . ', Palakkad';
$pageDescription = $festival['description'] ? substr(strip_tags($festival['description']), 0, 160) : "Celebrate " . $festival['name'] . " festival in Palakkad, Kerala. Experience this vibrant cultural celebration.";
$pageKeywords = $festival['name'] . ", festival Palakkad, " . $festival['location'] . ", Palakkad festivals, Kerala celebrations";
if (!empty($festival['image_url'])) {
    $pageOgImage = $festival['image_url'];
}

// Breadcrumbs for SEO
$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'Festivals', 'url' => BASE_PATH . '/festivals.php'],
    ['title' => $festival['name'], 'url' => '']
];

require_once __DIR__ . '/includes/header.php';

// Format dates
$startDate = new DateTime($festival['start_date']);
$endDate = new DateTime($festival['end_date']);
?>
<!-- Festival-specific Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Event",
    "name": "<?php echo htmlspecialchars($festival['name']); ?>",
    "description": "<?php echo htmlspecialchars($festival['description']); ?>",
    "url": <?php echo json_encode($seoCanonicalUrl, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>,
    <?php if ($festival['image_url']): ?>
    "image": "<?php echo htmlspecialchars($festival['image_url']); ?>",
    <?php endif; ?>
    "startDate": "<?php echo $festival['start_date']; ?>",
    <?php if ($festival['end_date']): ?>
    "endDate": "<?php echo $festival['end_date']; ?>",
    <?php endif; ?>
    "location": {
        "@type": "Place",
        "name": "<?php echo htmlspecialchars($festival['location'] ?? 'Palakkad'); ?>",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "<?php echo htmlspecialchars($festival['location'] ?? 'Palakkad'); ?>",
            "addressRegion": "Kerala",
            "addressCountry": "IN"
        }
    }
    <?php if ($festival['type']): ?>
    ,"eventAttendanceMode": "https://schema.org/OfflineEventAttendanceMode"
    ,"eventStatus": "https://schema.org/EventScheduled"
    <?php endif; ?>
}
</script>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h1 class="display-4 mb-4"><?php echo htmlspecialchars($festival['name']); ?></h1>
                
                <?php displayAdSense('content-header', 'max-width: 728px;'); ?>
                
                <?php if ($festival['image_url']): ?>
                <div class="mb-4">
                    <img src="<?php echo htmlspecialchars($festival['image_url']); ?>" 
                         class="img-fluid rounded shadow" 
                         alt="<?php echo htmlspecialchars($festival['name'] . ' - Festival in Palakkad, Kerala'); ?>"
                         style="width: 100%; max-height: 500px; object-fit: cover;">
                </div>
                <?php endif; ?>

                <?php if ($festival['type']): ?>
                <div class="mb-3">
                    <span class="badge bg-primary fs-6"><?php echo htmlspecialchars($festival['type']); ?></span>
                </div>
                <?php endif; ?>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">About</h2>
                        <p class="lead"><?php echo nl2br(htmlspecialchars($festival['description'])); ?></p>
                    </div>
                </div>

                <?php if ($festival['cultural_significance']): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Cultural Significance</h2>
                        <p><?php echo nl2br(htmlspecialchars($festival['cultural_significance'])); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($festival['highlights']): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Highlights</h2>
                        <p><?php echo nl2br(htmlspecialchars($festival['highlights'])); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-calendar text-primary"></i> Date
                                </h5>
                                <p class="card-text">
                                    <?php 
                                    echo $startDate->format('F d, Y');
                                    if ($festival['start_date'] != $festival['end_date']) {
                                        echo ' - ' . $endDate->format('F d, Y');
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    <?php if ($festival['location']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-geo-alt-fill text-success"></i> Location
                                </h5>
                                <p class="card-text"><?php echo htmlspecialchars($festival['location']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="text-center mt-4">
                    <a href="<?php echo BASE_PATH; ?>/festivals.php" class="btn btn-primary btn-lg">
                        <i class="bi bi-arrow-left"></i> Back to All Festivals
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

