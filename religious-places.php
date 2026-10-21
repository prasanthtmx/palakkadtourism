<?php
require_once __DIR__ . '/config/config.php';

$pageTitle = 'Religious places in Palakkad';
$pageDescription = 'Temples, churches, mosques, and the Jain temple of Palakkad, Kerala — listings and visitor guides.';

$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'Religious Places', 'url' => ''],
];

require_once __DIR__ . '/includes/header.php';
?>
<main id="main-content">
<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h1 class="display-4 mb-4">Religious places in Palakkad</h1>
                <p class="lead">Palakkad’s spiritual map spans Hindu temples, Christian churches, mosques, and a historic Jain shrine at Jainimedu.</p>
                <div class="row g-4 mt-2">
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h2 class="h4">Temples</h2>
                                <p>Ancient shrines, village kavus, and festival temples across the district.</p>
                                <a href="<?php echo BASE_PATH; ?>/temples.php" class="btn btn-primary">Browse temples</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h2 class="h4">Churches</h2>
                                <p>Historic and parish churches serving Palakkad’s Christian communities.</p>
                                <a href="<?php echo BASE_PATH; ?>/churches.php" class="btn btn-primary">Browse churches</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h2 class="h4">Mosques</h2>
                                <p>Juma masjids and neighbourhood mosques with local history.</p>
                                <a href="<?php echo BASE_PATH; ?>/mosques.php" class="btn btn-primary">Browse mosques</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <h2 class="h4">Jain Temple</h2>
                                <p>Shri Digambar Jain Lai Mandir at Jainimedu — a 15th-century Digambar shrine.</p>
                                <a href="<?php echo BASE_PATH; ?>/jain-temple.php" class="btn btn-primary">Jain temple guide</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
