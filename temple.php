<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();
$temple = seoLoadEntityByRequest($conn, 'temples', 'temple', 'temples.php');
closeDBConnection($conn);

$pageTitle = $temple['name'] . ', Palakkad';
$pageDescription = $temple['description'] ? substr(strip_tags($temple['description']), 0, 160) : "Visit " . $temple['name'] . " temple in Palakkad, Kerala. Explore this ancient place of worship.";
$pageKeywords = $temple['name'] . ", temple Palakkad, " . $temple['location'] . ", Palakkad temples, Hindu temple Kerala";
if (!empty($temple['image_url'])) {
    $pageOgImage = $temple['image_url'];
}

// Breadcrumbs for SEO
$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'Temples', 'url' => BASE_PATH . '/temples.php'],
    ['title' => $temple['name'], 'url' => '']
];

require_once __DIR__ . '/includes/header.php';
?>
<!-- Temple-specific Structured Data -->
<?php
$_address = [
    '@type'           => 'PostalAddress',
    'addressLocality' => $temple['location'] ?? 'Palakkad',
    'addressRegion'   => 'Kerala',
    'addressCountry'  => 'IN',
];
if (!empty($temple['address'])) $_address['streetAddress'] = $temple['address'];
$_templeSchema = [
    '@context'    => 'https://schema.org',
    '@type'       => 'PlaceOfWorship',
    'name'        => $temple['name'],
    'description' => strip_tags($temple['description'] ?? ''),
    'url'         => $seoCanonicalUrl,
    'address'     => $_address,
];
if (!empty($temple['image_url'])) $_templeSchema['image']        = $temple['image_url'];
if (!empty($temple['timings']))   $_templeSchema['openingHours'] = $temple['timings'];
if (!empty($temple['contact']))   $_templeSchema['telephone']    = $temple['contact'];
?>
<script type="application/ld+json">
<?php echo json_encode($_templeSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
</script>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h1 class="display-4 mb-4"><?php echo htmlspecialchars($temple['name']); ?></h1>
                
                <?php displayAdSense('content-header', 'max-width: 728px;'); ?>
                
                <?php if ($temple['image_url']): ?>
                <div class="mb-4">
                    <img src="<?php echo htmlspecialchars($temple['image_url']); ?>" 
                         class="img-fluid rounded shadow" 
                         alt="<?php echo htmlspecialchars($temple['name'] . ' - Temple in Palakkad, Kerala'); ?>"
                         style="width: 100%; max-height: 500px; object-fit: cover;">
                </div>
                <?php endif; ?>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">About</h2>
                        <p class="lead"><?php echo nl2br(htmlspecialchars($temple['description'])); ?></p>
                    </div>
                </div>

                <?php if ($temple['history']): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">History</h2>
                        <p><?php echo nl2br(htmlspecialchars($temple['history'])); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($temple['special_features']): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Special Features</h2>
                        <p><?php echo nl2br(htmlspecialchars($temple['special_features'])); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row g-4 mb-4">
                    <?php if ($temple['location']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-geo-alt-fill text-primary"></i> Location
                                </h5>
                                <p class="card-text"><?php echo htmlspecialchars($temple['location']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($temple['address']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-house text-primary"></i> Address
                                </h5>
                                <p class="card-text"><?php echo htmlspecialchars($temple['address']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($temple['timings']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-clock text-warning"></i> Timings
                                </h5>
                                <p class="card-text"><?php echo htmlspecialchars($temple['timings']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($temple['contact']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-telephone text-danger"></i> Contact
                                </h5>
                                <p class="card-text">
                                    <?php if (preg_match('/\d/', $temple['contact'])): ?>
                                        <a href="tel:<?php echo htmlspecialchars($temple['contact']); ?>"><?php echo htmlspecialchars($temple['contact']); ?></a>
                                    <?php else: ?>
                                        <?php echo htmlspecialchars($temple['contact']); ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="text-center mt-4">
                    <a href="<?php echo BASE_PATH; ?>/temples.php" class="btn btn-primary btn-lg">
                        <i class="bi bi-arrow-left"></i> Back to All Temples
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

