<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = 'Walayar Dam';
$pageDescription = 'Walayar Dam on the Kerala–Tamil Nadu border — quick facts and travel context for visitors exploring Palakkad district.';
$pageKeywords = 'Walayar Dam, Palakkad border, Kerala Tamil Nadu dam';

$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'West Wide Way', 'url' => BASE_PATH . '/index.php#west-wide-way'],
    ['title' => 'Walayar Dam', 'url' => ''],
];

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h1 class="display-5 mb-3">Walayar Dam</h1>
                <p class="lead text-muted">Border area · Often visited in passing when crossing between states.</p>

                <?php displayAdSense('content-top', 'max-width: 728px;'); ?>

                <p class="mb-0">Full visitor notes and access details will be added on this page soon.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
