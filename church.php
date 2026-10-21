<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();
$church = seoLoadEntityByRequest($conn, 'churches', 'church', 'churches.php');
closeDBConnection($conn);

$pageTitle = $church['name'] . ', Palakkad';
$pageDescription = $church['description'] ? substr(strip_tags($church['description']), 0, 160) : "Visit " . $church['name'] . " church in Palakkad, Kerala. Explore this beautiful place of worship.";
$pageKeywords = $church['name'] . ", church Palakkad, " . $church['location'] . ", Palakkad churches, Christian church Kerala";
if (!empty($church['image_url'])) {
    $pageOgImage = $church['image_url'];
}

// Breadcrumbs for SEO
$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'Churches', 'url' => BASE_PATH . '/churches.php'],
    ['title' => $church['name'], 'url' => '']
];

require_once __DIR__ . '/includes/header.php';
?>
<!-- Church-specific Structured Data -->
<?php
$_address = [
    '@type'           => 'PostalAddress',
    'addressLocality' => $church['location'] ?? 'Palakkad',
    'addressRegion'   => 'Kerala',
    'addressCountry'  => 'IN',
];
if (!empty($church['address'])) $_address['streetAddress'] = $church['address'];
$_churchSchema = [
    '@context'    => 'https://schema.org',
    '@type'       => 'PlaceOfWorship',
    'name'        => $church['name'],
    'description' => strip_tags($church['description'] ?? ''),
    'url'         => $seoCanonicalUrl,
    'address'     => $_address,
];
if (!empty($church['image_url'])) $_churchSchema['image']        = $church['image_url'];
if (!empty($church['timings']))   $_churchSchema['openingHours'] = $church['timings'];
if (!empty($church['contact']))   $_churchSchema['telephone']    = $church['contact'];
?>
<script type="application/ld+json">
<?php echo json_encode($_churchSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT); ?>
</script>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h1 class="display-4 mb-4"><?php echo htmlspecialchars($church['name']); ?></h1>
                
                <?php displayAdSense('content-header', 'max-width: 728px;'); ?>
                
                <?php if ($church['image_url']): ?>
                <div class="mb-4">
                    <img src="<?php echo htmlspecialchars($church['image_url']); ?>" 
                         class="img-fluid rounded shadow" 
                         alt="<?php echo htmlspecialchars($church['name'] . ' - Church in Palakkad, Kerala'); ?>"
                         style="width: 100%; max-height: 500px; object-fit: cover;">
                </div>
                <?php endif; ?>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">About</h2>
                        <p class="lead"><?php echo nl2br(htmlspecialchars($church['description'])); ?></p>
                    </div>
                </div>

                <?php if ($church['history']): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">History</h2>
                        <p><?php echo nl2br(htmlspecialchars($church['history'])); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($church['special_features']): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Special Features</h2>
                        <p><?php echo nl2br(htmlspecialchars($church['special_features'])); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <div class="row g-4 mb-4">
                    <?php if ($church['location']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-geo-alt-fill text-success"></i> Location
                                </h5>
                                <p class="card-text"><?php echo htmlspecialchars($church['location']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($church['address']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-house text-success"></i> Address
                                </h5>
                                <p class="card-text"><?php echo htmlspecialchars($church['address']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($church['timings']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-clock text-warning"></i> Timings
                                </h5>
                                <p class="card-text"><?php echo htmlspecialchars($church['timings']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if ($church['contact']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-telephone text-danger"></i> Contact
                                </h5>
                                <p class="card-text">
                                    <?php if (preg_match('/\d/', $church['contact'])): ?>
                                        <a href="tel:<?php echo htmlspecialchars($church['contact']); ?>"><?php echo htmlspecialchars($church['contact']); ?></a>
                                    <?php else: ?>
                                        <?php echo htmlspecialchars($church['contact']); ?>
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="text-center mt-4">
                    <a href="<?php echo BASE_PATH; ?>/churches.php" class="btn btn-success btn-lg">
                        <i class="bi bi-arrow-left"></i> Back to All Churches
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

