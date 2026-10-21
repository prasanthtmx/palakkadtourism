<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = 'Chulliyar Dam';
$pageDescription = 'Chulliyar Dam near Muthalamada on the Tamil Nadu border — agriculture, storage, and road access in the Palakkad area.';
$pageKeywords = 'Chulliyar Dam, Muthalamada, Palakkad border dams, Kerala TN border';

$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'West Wide Way', 'url' => BASE_PATH . '/index.php#west-wide-way'],
    ['title' => 'Chulliyar Dam', 'url' => ''],
];

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h1 class="display-5 mb-3">Chulliyar Dam</h1>
                <p class="lead text-muted">Near Muthalamada (TN border) · Agriculture and water storage.</p>

                <?php displayAdSense('content-top', 'max-width: 728px;'); ?>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h5 mb-3">At a glance</h2>
                        <ul class="mb-0">
                            <li>Good road access for local visits</li>
                            <li>Mix of irrigation landscape and reservoir views</li>
                        </ul>
                    </div>
                </div>

                <p class="mb-0">More detail will be added on this page soon.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
