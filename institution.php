<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();
$institution = seoLoadEntityByRequest($conn, 'institutions', 'institution', 'institutions.php');
closeDBConnection($conn);

$pageTitle = $institution['name'] . ', Palakkad';
$pageDescription = $institution['description'] ? substr(strip_tags($institution['description']), 0, 160) : "Learn about " . $institution['name'] . " in Palakkad, Kerala. " . ucfirst($institution['type']) . " offering quality education.";
$pageKeywords = $institution['name'] . ", " . $institution['type'] . " Palakkad, " . $institution['location'] . ", " . $institution['category'] . " institution, Palakkad education";
if (!empty($institution['image_url'])) {
    $pageOgImage = $institution['image_url'];
}

// Breadcrumbs for SEO
$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'Community', 'url' => '#'],
    ['title' => 'Institutions', 'url' => BASE_PATH . '/institutions.php'],
    ['title' => $institution['name'], 'url' => '']
];

require_once __DIR__ . '/includes/header.php';
?>
<!-- Institution-specific Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "EducationalOrganization",
    "name": "<?php echo htmlspecialchars($institution['name'], ENT_QUOTES); ?>",
    "description": "<?php echo htmlspecialchars($institution['description'] ?? '', ENT_QUOTES); ?>",
    "url": <?php echo json_encode($seoCanonicalUrl, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>,
    <?php if ($institution['image_url']): ?>
    "image": "<?php echo htmlspecialchars($institution['image_url'], ENT_QUOTES); ?>",
    <?php endif; ?>
    "address": {
        "@type": "PostalAddress",
        "addressLocality": "<?php echo htmlspecialchars($institution['location'] ?? '', ENT_QUOTES); ?>",
        "addressRegion": "Kerala",
        "addressCountry": "IN"<?php if ($institution['address']): ?>,
        "streetAddress": "<?php echo htmlspecialchars($institution['address'], ENT_QUOTES); ?>"<?php endif; ?>
    }<?php if ($institution['phone']): ?>,
    "telephone": "<?php echo htmlspecialchars($institution['phone'], ENT_QUOTES); ?>"<?php endif; ?><?php if ($institution['email']): ?>,
    "email": "<?php echo htmlspecialchars($institution['email'], ENT_QUOTES); ?>"<?php endif; ?><?php if ($institution['website']): ?>,
    "sameAs": "<?php echo htmlspecialchars($institution['website'], ENT_QUOTES); ?>"<?php endif; ?>
}
</script>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h1 class="display-4 mb-4"><?php echo htmlspecialchars($institution['name']); ?></h1>
                
                <?php displayAdSense('content-header', 'max-width: 728px;'); ?>
                
                <?php if ($institution['image_url']): ?>
                <div class="mb-4">
                    <img src="<?php echo htmlspecialchars($institution['image_url']); ?>" 
                         class="img-fluid rounded shadow" 
                         alt="<?php echo htmlspecialchars($institution['name'] . ' - ' . ucfirst($institution['type']) . ' in Palakkad, Kerala'); ?>"
                         style="width: 100%; max-height: 500px; object-fit: cover;">
                </div>
                <?php endif; ?>

                <div class="row mb-4">
                    <div class="col-md-12">
                        <span class="badge bg-info fs-6 mb-2"><?php echo ucfirst($institution['type']); ?></span>
                        <span class="badge bg-secondary fs-6 mb-2"><?php echo ucfirst($institution['category']); ?></span>
                    </div>
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">About</h2>
                        <p class="lead"><?php echo nl2br(htmlspecialchars($institution['description'] ?? 'No description available.')); ?></p>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <?php if ($institution['location']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-geo-alt-fill text-primary"></i> Location
                                </h5>
                                <p class="card-text"><?php echo htmlspecialchars($institution['location']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($institution['address']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-map-fill text-success"></i> Address
                                </h5>
                                <p class="card-text"><?php echo nl2br(htmlspecialchars($institution['address'])); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($institution['phone']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-telephone-fill text-info"></i> Phone
                                </h5>
                                <p class="card-text">
                                    <a href="tel:<?php echo htmlspecialchars($institution['phone']); ?>">
                                        <?php echo htmlspecialchars($institution['phone']); ?>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($institution['email']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-envelope-fill text-warning"></i> Email
                                </h5>
                                <p class="card-text">
                                    <a href="mailto:<?php echo htmlspecialchars($institution['email']); ?>">
                                        <?php echo htmlspecialchars($institution['email']); ?>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($institution['website']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-globe text-primary"></i> Website
                                </h5>
                                <p class="card-text">
                                    <a href="<?php echo htmlspecialchars($institution['website']); ?>" target="_blank" rel="noopener">
                                        Visit Website <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($institution['established_year']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-calendar-check text-danger"></i> Established
                                </h5>
                                <p class="card-text"><?php echo $institution['established_year']; ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($institution['affiliation']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-award text-success"></i> Affiliation
                                </h5>
                                <p class="card-text"><?php echo htmlspecialchars($institution['affiliation']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <?php if ($institution['timings']): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-clock-fill text-info"></i> Timings
                                </h5>
                                <p class="card-text"><?php echo htmlspecialchars($institution['timings']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if ($institution['courses']): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Courses Offered</h2>
                        <p><?php echo nl2br(htmlspecialchars($institution['courses'])); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <?php if ($institution['facilities']): ?>
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Facilities</h2>
                        <p><?php echo nl2br(htmlspecialchars($institution['facilities'])); ?></p>
                    </div>
                </div>
                <?php endif; ?>

                <div class="mt-4">
                    <a href="<?php echo BASE_PATH; ?>/institutions.php" class="btn btn-outline-primary">
                        <i class="bi bi-arrow-left"></i> Back to Institutions
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

