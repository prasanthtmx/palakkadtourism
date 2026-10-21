<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();
$place = seoLoadEntityByRequest($conn, 'places', 'place', 'places.php');
closeDBConnection($conn);

$pageTitle = $place['name'] . ', Palakkad';
$pageDescription = $place['description'] ? substr(strip_tags($place['description']), 0, 160) : "Visit " . $place['name'] . " in Palakkad, Kerala. Explore this beautiful tourist destination.";
$pageKeywords = $place['name'] . ", Palakkad tourist places, " . $place['location'] . ", Palakkad attractions";
if (!empty($place['image_url'])) {
    $pageOgImage = $place['image_url'];
}

// Breadcrumbs for SEO
$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'Places to Visit', 'url' => BASE_PATH . '/places.php'],
    ['title' => $place['name'], 'url' => '']
];

require_once __DIR__ . '/includes/header.php';
?>
<!-- Place-specific Structured Data -->
<?php
$_address = [
    '@type'           => 'PostalAddress',
    'addressLocality' => $place['location'] ?? 'Palakkad',
    'addressRegion'   => 'Kerala',
    'addressCountry'  => 'IN',
];
if (!empty($place['address'])) $_address['streetAddress'] = $place['address'];
$_placeSchema = [
    '@context'    => 'https://schema.org',
    '@type'       => 'TouristAttraction',
    'name'        => $place['name'],
    'description' => strip_tags($place['description'] ?? ''),
    'url'         => $seoCanonicalUrl,
    'address'     => $_address,
];
if (!empty($place['image_url'])) $_placeSchema['image']        = $place['image_url'];
if (!empty($place['timings']))   $_placeSchema['openingHours'] = $place['timings'];
if (!empty($place['contact']))   $_placeSchema['telephone']    = $place['contact'];
?>
<script type="application/ld+json">
<?php echo json_encode($_placeSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
</script>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h1 class="display-4 mb-4"><?php echo htmlspecialchars($place['name']); ?></h1>
                
                <?php displayAdSense('content-header', 'max-width: 728px;'); ?>
                
                <?php if ($place['image_url']): ?>
                <div class="mb-4">
                    <img src="<?php echo htmlspecialchars($place['image_url']); ?>" 
                         class="img-fluid rounded shadow" 
                         alt="<?php echo htmlspecialchars($place['name'] . ' - Tourist Place in Palakkad, Kerala'); ?>"
                         style="width: 100%; max-height: 500px; object-fit: cover;">
                </div>
                <?php endif; ?>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <?php if ($place['category']): ?>
                        <span class="badge bg-info fs-6 mb-2"><?php echo htmlspecialchars($place['category']); ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">About</h2>
                        <p class="lead"><?php echo nl2br(htmlspecialchars($place['description'])); ?></p>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <?php if ($place['location']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-geo-alt-fill text-primary"></i> Location
                                </h5>
                                <p class="card-text"><?php echo htmlspecialchars($place['location']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($place['address']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-house text-primary"></i> Address
                                </h5>
                                <p class="card-text"><?php echo htmlspecialchars($place['address']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($place['best_time_to_visit']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-calendar-check text-success"></i> Best Time to Visit
                                </h5>
                                <p class="card-text"><?php echo htmlspecialchars($place['best_time_to_visit']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($place['timings']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-clock text-warning"></i> Timings
                                </h5>
                                <p class="card-text"><?php echo htmlspecialchars($place['timings']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($place['entry_fee']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-currency-rupee text-info"></i> Entry Fee
                                </h5>
                                <p class="card-text"><?php echo htmlspecialchars($place['entry_fee']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($place['contact']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-telephone text-danger"></i> Contact
                                </h5>
                                <p class="card-text">
                                    <?php if (preg_match('/\d/', $place['contact'])): ?>
                                        <a href="tel:<?php echo htmlspecialchars($place['contact']); ?>"><?php echo htmlspecialchars($place['contact']); ?></a>
                                    <?php else: ?>
                                        <?php echo htmlspecialchars($place['contact']); ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="text-center mt-4">
                    <a href="<?php echo BASE_PATH; ?>/places.php" class="btn btn-primary btn-lg">
                        <i class="bi bi-arrow-left"></i> Back to All Places
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

