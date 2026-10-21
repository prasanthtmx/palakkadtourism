<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = 'Pothundy Dam';
$pageDescription = 'Pothundy Dam near the border — sometimes grouped with Palakkad tourism. Overview for trip planning.';
$pageKeywords = 'Pothundy Dam, Palakkad tourism, Kerala reservoir';

$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'West Wide Way', 'url' => BASE_PATH . '/index.php#west-wide-way'],
    ['title' => 'Pothundy Dam', 'url' => ''],
];

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h1 class="display-5 mb-3">Pothundy Dam</h1>
                <p class="lead text-muted">Border area · Sometimes counted with Palakkad tourism circuits.</p>

                <?php displayAdSense('content-top', 'max-width: 728px;'); ?>

                <p class="mb-0">Expanded route ideas and visitor notes will be added on this page soon.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
