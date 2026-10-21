<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = "Hospitals in Palakkad";
$pageDescription = "Find hospitals in Palakkad district, Kerala. Browse government hospitals, private hospitals, Ayurveda hospitals, and Homeopathy hospitals in Palakkad.";
$pageKeywords = "Palakkad hospitals, government hospitals Palakkad, private hospitals Palakkad, Ayurveda hospitals, Homeopathy hospitals, healthcare Palakkad";

// Breadcrumbs for SEO
$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'Hospitals', 'url' => '']
];

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h1 class="display-4 mb-4">Hospitals in Palakkad</h1>
                
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-hospital"></i> <strong>Note:</strong> Please contact hospitals directly for accurate information about services, timings, and availability.
                </div>
                
                <?php displayAdSense('content-top', 'max-width: 728px;'); ?>

                <p class="lead">Palakkad district offers comprehensive healthcare services through various types of hospitals including government hospitals, private hospitals, Ayurveda hospitals, and Homeopathy hospitals.</p>

                <div class="row g-4 mt-4">
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-building fs-1 text-primary mb-3"></i>
                                <h3 class="card-title">Government Hospitals</h3>
                                <p class="card-text">Government hospitals providing quality healthcare services to the public.</p>
                                <a href="<?php echo BASE_PATH; ?>/hospitals-government.php" class="btn btn-primary">View Government Hospitals</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-hospital fs-1 text-success mb-3"></i>
                                <h3 class="card-title">Private Hospitals</h3>
                                <p class="card-text">Private hospitals offering advanced medical facilities and specialized care.</p>
                                <a href="<?php echo BASE_PATH; ?>/hospitals-private.php" class="btn btn-success">View Private Hospitals</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-flower1 fs-1 text-warning mb-3"></i>
                                <h3 class="card-title">Ayurveda Hospitals</h3>
                                <p class="card-text">Traditional Ayurveda hospitals providing holistic healthcare services.</p>
                                <a href="<?php echo BASE_PATH; ?>/hospitals-ayurveda.php" class="btn btn-warning">View Ayurveda Hospitals</a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body text-center">
                                <i class="bi bi-heart-pulse fs-1 text-info mb-3"></i>
                                <h3 class="card-title">Homeopathy Hospitals</h3>
                                <p class="card-text">Homeopathy hospitals offering alternative medicine treatments.</p>
                                <a href="<?php echo BASE_PATH; ?>/hospitals-homeopathy.php" class="btn btn-info">View Homeopathy Hospitals</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>




