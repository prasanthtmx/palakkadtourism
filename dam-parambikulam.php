<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = 'Parambikulam Dam';
$pageDescription = 'Parambikulam Dam inside Parambikulam Tiger Reserve — forest, wildlife, and permit-based entry. Overview for visitors planning Palakkad district travel.';
$pageKeywords = 'Parambikulam Dam, Parambikulam Tiger Reserve, Palakkad wildlife, forest permission Kerala';

$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'West Wide Way', 'url' => BASE_PATH . '/index.php#west-wide-way'],
    ['title' => 'Parambikulam Dam', 'url' => ''],
];

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h1 class="display-5 mb-3">Parambikulam Dam</h1>
                <p class="lead text-muted">Inside Parambikulam Tiger Reserve · Dense forest and wildlife.</p>

                <?php displayAdSense('content-top', 'max-width: 728px;'); ?>

                <div class="alert alert-warning" role="alert">
                    <strong>Permits:</strong> Entry requires forest department permission — confirm current rules before travelling.
                </div>

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h5 mb-3">At a glance</h2>
                        <ul class="mb-0">
                            <li>Part of a protected tiger reserve</li>
                            <li>Strong focus on nature and wildlife experiences</li>
                        </ul>
                    </div>
                </div>

                <p class="mb-0">Expanded visitor information will be added on this page soon.</p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
