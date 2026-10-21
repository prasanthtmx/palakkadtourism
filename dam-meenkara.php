<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = 'Meenkara Dam';
$pageDescription = 'Meenkara Dam near Kollengode — coconut country and photography-friendly landscapes in Palakkad district.';
$pageKeywords = 'Meenkara Dam, Kollengode, Palakkad photography, Kerala dams';

$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'West Wide Way', 'url' => BASE_PATH . '/index.php#west-wide-way'],
    ['title' => 'Meenkara Dam', 'url' => ''],
];

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h1 class="display-5 mb-3">Meenkara Dam</h1>
                <p class="lead text-muted">Near Kollengode · Surrounded by coconut farms.</p>

                <?php displayAdSense('content-top', 'max-width: 728px;'); ?>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h5 mb-3">At a glance</h2>
                        <ul class="mb-0">
                            <li>Popular with photographers and nature walkers</li>
                            <li>Rural Palakkad scenery</li>
                        </ul>
                    </div>
                </div>

                <p class="mb-0">More detail will be added on this page soon.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
