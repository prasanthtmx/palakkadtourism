<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = 'Siruvani Dam';
$pageDescription = 'Siruvani Dam — important water source for the region; access is restricted. High-level information for Palakkad travellers.';
$pageKeywords = 'Siruvani Dam, Siruvani water, Kerala restricted dam';

$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'West Wide Way', 'url' => BASE_PATH . '/index.php#west-wide-way'],
    ['title' => 'Siruvani Dam', 'url' => ''],
];

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h1 class="display-5 mb-3">Siruvani Dam</h1>
                <p class="lead text-muted">Critical water source · Public access is limited or restricted.</p>

                <?php displayAdSense('content-top', 'max-width: 728px;'); ?>

                <div class="alert alert-info" role="alert">
                    Do not plan a casual visit without checking the latest official rules — many areas are not open to general tourism.
                </div>

                <p class="mb-0">Factual updates and official contact points will be added on this page when available.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
