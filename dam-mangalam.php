<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = 'Mangalam Dam';
$pageDescription = 'Mangalam Dam near Ottapalam and Alathur — scenic hills and a calm, less crowded outing in Palakkad district.';
$pageKeywords = 'Mangalam Dam, Ottapalam, Alathur, Palakkad dams, Kerala reservoirs';

$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'West Wide Way', 'url' => BASE_PATH . '/index.php#west-wide-way'],
    ['title' => 'Mangalam Dam', 'url' => ''],
];

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h1 class="display-5 mb-3">Mangalam Dam</h1>
                <p class="lead text-muted">Near Ottapalam / Alathur side · Scenic hills and calm atmosphere.</p>

                <?php displayAdSense('content-top', 'max-width: 728px;'); ?>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h5 mb-3">At a glance</h2>
                        <ul class="mb-0">
                            <li>Generally quieter than the busiest tourist dams</li>
                            <li>Good for relaxed drives and hillside views</li>
                        </ul>
                    </div>
                </div>

                <p class="mb-0">More detail will be added on this page soon.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
