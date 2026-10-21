<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$pageTitle = "Hospitals in Palakkad";
$pageDescription = "Find hospitals in Palakkad district, Kerala. Browse government hospitals, private hospitals, Ayurveda hospitals, and Homeopathy hospitals in Palakkad.";
$pageKeywords = "Palakkad hospitals, government hospitals Palakkad, private hospitals Palakkad, Ayurveda hospitals, Homeopathy hospitals, healthcare Palakkad";

$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'Hospitals', 'url' => ''],
];

$counts = [
    'government' => 0,
    'private' => 0,
    'ayurveda' => 0,
    'homeopathy' => 0,
];
try {
    $conn = getDBConnection();
    $result = $conn->query("SELECT type, COUNT(*) AS n FROM hospitals WHERE status = 'active' GROUP BY type");
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $type = (string) ($row['type'] ?? '');
            if (isset($counts[$type])) {
                $counts[$type] = (int) $row['n'];
            }
        }
    }
    closeDBConnection($conn);
} catch (Throwable $e) {
    // Keep zeros if the directory is unavailable.
}

$totalHospitals = array_sum($counts);
$typeCount = count(array_filter($counts));

$categories = [
    [
        'key' => 'government',
        'title' => 'Government Hospitals',
        'text' => 'District hospitals, taluk hospitals, and primary health centres serving the public.',
        'href' => BASE_PATH . '/hospitals-government.php',
        'cta' => 'View government hospitals',
        'icon' => 'bi-building',
        'mod' => 'gov',
    ],
    [
        'key' => 'private',
        'title' => 'Private Hospitals',
        'text' => 'Nursing homes and multi-speciality hospitals with clinics and inpatient care.',
        'href' => BASE_PATH . '/hospitals-private.php',
        'cta' => 'View private hospitals',
        'icon' => 'bi-hospital',
        'mod' => 'pvt',
    ],
    [
        'key' => 'ayurveda',
        'title' => 'Ayurveda Hospitals',
        'text' => 'Panchakarma and traditional Kerala Ayurveda centres across the district.',
        'href' => BASE_PATH . '/hospitals-ayurveda.php',
        'cta' => 'View Ayurveda hospitals',
        'icon' => 'bi-flower1',
        'mod' => 'ayu',
    ],
    [
        'key' => 'homeopathy',
        'title' => 'Homeopathy Hospitals',
        'text' => 'Government and private homeopathy clinics and hospitals for outpatient care.',
        'href' => BASE_PATH . '/hospitals-homeopathy.php',
        'cta' => 'View homeopathy hospitals',
        'icon' => 'bi-heart-pulse',
        'mod' => 'hom',
    ],
];

require_once __DIR__ . '/includes/header.php';
?>
<main id="main-content" class="hsp-page">

    <header class="hsp-page-header">
        <div class="hsp-header-bg" aria-hidden="true"></div>
        <div class="container position-relative">
            <p class="hsp-kicker">Healthcare · Palakkad district</p>
            <h1 class="hsp-title">Hospitals in Palakkad</h1>
            <p class="hsp-lead">Government, private, Ayurveda, and homeopathy facilities across the Rice Bowl of Kerala — a directory to help you find care, not a booking service.</p>
            <div class="hsp-stat-row">
                <div class="hsp-stat">
                    <span class="hsp-stat-num"><?php echo (int) $totalHospitals; ?></span>
                    <span class="hsp-stat-label">Listed hospitals</span>
                </div>
                <div class="hsp-stat">
                    <span class="hsp-stat-num"><?php echo (int) $typeCount; ?></span>
                    <span class="hsp-stat-label">Care types</span>
                </div>
                <div class="hsp-stat">
                    <span class="hsp-stat-num">108</span>
                    <span class="hsp-stat-label">Ambulance</span>
                </div>
            </div>
        </div>
    </header>

    <section class="hsp-body">
        <div class="container">
            <aside class="hsp-notice" role="note">
                <div class="hsp-notice-icon" aria-hidden="true"><i class="bi bi-info-circle-fill"></i></div>
                <div>
                    <p class="hsp-notice-title">Confirm before you travel</p>
                    <p class="hsp-notice-text">Call the hospital for current services, OP timings, and bed availability. Listings here are a guide only.</p>
                </div>
            </aside>

            <div class="hsp-section-intro">
                <h2 class="hsp-section-heading">Browse by type</h2>
                <p class="hsp-section-sub">Choose a category to see names, locations, and contact details.</p>
            </div>

            <div class="row g-4">
                <?php foreach ($categories as $cat):
                    $n = (int) $counts[$cat['key']];
                    ?>
                <div class="col-md-6">
                    <a href="<?php echo htmlspecialchars($cat['href'], ENT_QUOTES, 'UTF-8'); ?>" class="hsp-card hsp-card--<?php echo htmlspecialchars($cat['mod'], ENT_QUOTES, 'UTF-8'); ?>">
                        <span class="hsp-card-icon" aria-hidden="true"><i class="bi <?php echo htmlspecialchars($cat['icon'], ENT_QUOTES, 'UTF-8'); ?>"></i></span>
                        <span class="hsp-card-copy">
                            <span class="hsp-card-meta"><?php echo $n; ?> listed</span>
                            <span class="hsp-card-title"><?php echo htmlspecialchars($cat['title'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <span class="hsp-card-text"><?php echo htmlspecialchars($cat['text'], ENT_QUOTES, 'UTF-8'); ?></span>
                            <span class="hsp-card-cta"><?php echo htmlspecialchars($cat['cta'], ENT_QUOTES, 'UTF-8'); ?> <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
                        </span>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="hsp-help-row">
                <div class="hsp-help-card">
                    <i class="bi bi-telephone-forward" aria-hidden="true"></i>
                    <div>
                        <p class="hsp-help-title">Emergency ambulance</p>
                        <p class="hsp-help-text">Dial <strong>108</strong> for the national ambulance service. For a planned visit, use the hospital phone number on its listing.</p>
                    </div>
                </div>
                <a href="<?php echo BASE_PATH; ?>/donate-blood.php" class="hsp-help-card hsp-help-card--link">
                    <i class="bi bi-droplet-half" aria-hidden="true"></i>
                    <div>
                        <p class="hsp-help-title">Donate blood</p>
                        <p class="hsp-help-text">Blood banks and donation notes for Palakkad. <span class="hsp-help-more">Open guide</span></p>
                    </div>
                </a>
            </div>
        </div>
    </section>

</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
