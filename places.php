<?php
require_once __DIR__ . '/config/config.php';

$pageTitle       = 'Places to visit in Palakkad';
$pageDescription = 'Top Palakkad attractions: Palakkad Fort, Malampuzha Dam, Silent Valley, Nelliyampathy Hills, and more.';
$pageKeywords    = 'Palakkad tourist places, Palakkad attractions, Palakkad Fort, Malampuzha Dam, Silent Valley, Nelliyampathy, Palakkad sightseeing';

$breadcrumbs = [
    ['title' => 'Home',           'url' => BASE_PATH . '/'],
    ['title' => 'Places to Visit','url' => ''],
];

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/database.php';

$conn   = getDBConnection();
$places = $conn->query("SELECT * FROM places WHERE status='active' ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
closeDBConnection($conn);
$placeSlugs = seoSlugMap($places);

$placeCount = count($places);

/* Collect unique categories for reference */
$categories = array_values(array_unique(array_filter(array_column($places, 'category'))));
sort($categories);
?>
<!-- Places Listing Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "Places to Visit in Palakkad",
    "description": "List of top tourist attractions and places to visit in Palakkad, Kerala",
    "url": "<?php echo  $seoCanonicalUrl ; ?>",
    "numberOfItems": <?php echo $placeCount; ?>,
    "itemListElement": [
        <?php
        $items = [];
        foreach ($places as $i => $p) {
            $items[] = '{
                "@type": "ListItem",
                "position": ' . ($i + 1) . ',
                "item": {
                    "@type": "TouristAttraction",
                    "@id": "' . seoPublicUrl(getPlaceUrl($p['id'], $p['name'], $placeSlugs[(int)$p['id']] ?? null)) . '",
                    "name": "' . htmlspecialchars($p['name'], ENT_QUOTES) . '",
                    "description": "' . htmlspecialchars(substr(strip_tags($p['description']), 0, 200), ENT_QUOTES) . '",
                    "address": {
                        "@type": "PostalAddress",
                        "addressLocality": "' . htmlspecialchars($p['location'], ENT_QUOTES) . '",
                        "addressRegion": "Kerala",
                        "addressCountry": "IN"
                    }
                }
            }';
        }
        echo implode(",\n        ", $items);
        ?>
    ]
}
</script>

<!-- ── Page header ── -->
<div class="plc-page-header">
    <div class="plc-header-bg" aria-hidden="true"></div>
    <div class="container position-relative">
        <span class="plc-kicker">Palakkad · Kerala, India</span>
        <h1 class="plc-title">Places to Visit</h1>
        <p class="plc-lead">From mist-wrapped hill stations and ancient forts to shimmering dams and wildlife sanctuaries — discover Palakkad's finest destinations.</p>
        <div class="plc-stat-row d-flex flex-wrap gap-3 mt-4">
            <div class="plc-stat">
                <span class="plc-stat-num"><?php echo $placeCount; ?></span>
                <span class="plc-stat-label">Destinations</span>
            </div>
            <?php if (!empty($categories)): ?>
            <div class="plc-stat">
                <span class="plc-stat-num"><?php echo count($categories); ?></span>
                <span class="plc-stat-label">Categories</span>
            </div>
            <?php endif; ?>
            <div class="plc-stat">
                <span class="plc-stat-num">Oct–Mar</span>
                <span class="plc-stat-label">Best season</span>
            </div>
        </div>
    </div>
</div>

<!-- ── Main content ── -->
<main id="main-content">
<section class="plc-body py-5">
    <div class="container">

        <?php if (empty($places)): ?>
        <div class="alert alert-info rounded-3">
            <i class="bi bi-info-circle me-2"></i>No places found. Please check back later.
        </div>
        <?php else: ?>

        <!-- Section intro -->
        <div class="plc-section-intro d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h2 class="plc-section-heading mb-1">All Destinations</h2>
                <p class="text-muted small mb-0"><?php echo $placeCount; ?> places listed</p>
            </div>
            <a href="<?php echo BASE_PATH; ?>/how-to-reach.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="bi bi-signpost-2 me-1"></i> How to Reach Palakkad
            </a>
        </div>

        <!-- Cards grid -->
        <div class="row g-4">
            <?php foreach ($places as $place):
                $desc    = strip_tags($place['description'] ?? '');
                $excerpt = mb_strlen($desc) > 130 ? mb_substr($desc, 0, 130) . '…' : $desc;
                $imgUrl  = !empty($place['image_url']) ? htmlspecialchars(seoAssetSrc($place['image_url'])) : '';
            ?>
            <div class="col-sm-6 col-lg-4" id="place-<?php echo $place['id']; ?>">
                <article class="plc-card h-100">

                    <!-- Image -->
                    <a href="<?php echo htmlspecialchars(getPlaceUrl($place['id'], $place['name'], $placeSlugs[(int)$place['id']] ?? null)); ?>" class="plc-card-img-wrap" tabindex="-1" aria-hidden="true">
                        <?php if ($imgUrl): ?>
                        <img src="<?php echo $imgUrl; ?>"
                             alt="<?php echo htmlspecialchars($place['name']); ?> — tourist place in Palakkad"
                             class="plc-card-img"
                             loading="lazy"
                             width="480" height="270">
                        <?php else: ?>
                        <div class="plc-card-img plc-card-img--placeholder d-flex align-items-center justify-content-center">
                            <i class="bi bi-image" aria-hidden="true"></i>
                        </div>
                        <?php endif; ?>
                        <div class="plc-card-img-overlay" aria-hidden="true"></div>

                        <?php if ($place['category']): ?>
                        <span class="plc-card-cat-badge">
                            <i class="bi bi-tag me-1"></i><?php echo htmlspecialchars($place['category']); ?>
                        </span>
                        <?php endif; ?>
                    </a>

                    <!-- Body -->
                    <div class="plc-card-body">
                        <h2 class="plc-card-title">
                            <a href="<?php echo htmlspecialchars(getPlaceUrl($place['id'], $place['name'], $placeSlugs[(int)$place['id']] ?? null)); ?>" class="plc-card-title-link">
                                <?php echo htmlspecialchars($place['name']); ?>
                            </a>
                        </h2>

                        <?php if ($place['location']): ?>
                        <p class="plc-card-location">
                            <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
                            <?php echo htmlspecialchars($place['location']); ?>
                        </p>
                        <?php endif; ?>

                        <?php if ($excerpt): ?>
                        <p class="plc-card-desc"><?php echo htmlspecialchars($excerpt); ?></p>
                        <?php endif; ?>

                        <?php if ($place['best_time_to_visit']): ?>
                        <div class="plc-card-meta mt-auto">
                            <span class="plc-meta-chip">
                                <i class="bi bi-sun" aria-hidden="true"></i>
                                Best time: <?php echo htmlspecialchars($place['best_time_to_visit']); ?>
                            </span>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Footer -->
                    <div class="plc-card-footer">
                        <a href="<?php echo htmlspecialchars(getPlaceUrl($place['id'], $place['name'], $placeSlugs[(int)$place['id']] ?? null)); ?>" class="plc-card-link">
                            Explore <i class="bi bi-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>

                </article>
            </div>
            <?php endforeach; ?>
        </div>

        <?php endif; ?>
    </div>
</section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
