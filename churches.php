<?php
require_once __DIR__ . '/config/config.php';

$pageTitle       = 'Churches in Palakkad';
$pageDescription = 'Historic churches in Palakkad, Kerala — St. Mary’s Orthodox Church, St. Thomas Cathedral, and more.';
$pageKeywords    = 'Palakkad churches, Kerala churches, Christian churches Palakkad, St. Mary\'s Church Palakkad, Palakkad Christian places';

$breadcrumbs = [
    ['title' => 'Home',            'url' => BASE_PATH . '/'],
    ['title' => 'Religious Places','url' => BASE_PATH . '/religious-places.php'],
    ['title' => 'Churches',        'url' => ''],
];

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/database.php';

$conn    = getDBConnection();
$churches = $conn->query("SELECT * FROM churches WHERE status='active' ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
closeDBConnection($conn);
$churchSlugs = seoSlugMap($churches);

$churchCount = count($churches);
?>
<!-- Churches Listing Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "Churches in Palakkad",
    "description": "List of beautiful churches and Christian places of worship in Palakkad, Kerala",
    "url": "<?php echo  $seoCanonicalUrl ; ?>",
    "numberOfItems": <?php echo $churchCount; ?>,
    "itemListElement": [
        <?php
        $items = [];
        foreach ($churches as $i => $c) {
            $items[] = '{
                "@type": "ListItem",
                "position": ' . ($i + 1) . ',
                "item": {
                    "@type": "PlaceOfWorship",
                    "@id": "' . seoPublicUrl(getChurchUrl($c['id'], $c['name'], $churchSlugs[(int)$c['id']] ?? null)) . '",
                    "name": "' . htmlspecialchars($c['name'], ENT_QUOTES) . '",
                    "description": "' . htmlspecialchars(substr(strip_tags($c['description']), 0, 200), ENT_QUOTES) . '",
                    "address": {
                        "@type": "PostalAddress",
                        "addressLocality": "' . htmlspecialchars($c['location'], ENT_QUOTES) . '",
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
<div class="chr-page-header">
    <div class="container">
        <span class="chr-kicker">Palakkad · Kerala</span>
        <h1 class="chr-title">Churches of Palakkad</h1>
        <p class="chr-lead">Graceful steeples, stained-glass light, and centuries of devotion — explore the Christian heritage that graces every corner of Palakkad.</p>
    </div>
</div>

<!-- ── Main content ── -->
<main id="main-content">
<section class="py-5">
    <div class="container">

        <?php if (empty($churches)): ?>
        <div class="alert alert-info rounded-3">
            <i class="bi bi-info-circle me-2"></i>No churches found. Please check back later.
        </div>
        <?php else: ?>

        <!-- Section intro -->
        <div class="chr-section-intro d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h2 class="chr-section-heading mb-1">All Churches</h2>
                <p class="text-muted small mb-0"><?php echo $churchCount; ?> places of worship listed</p>
            </div>
            <a href="<?php echo BASE_PATH; ?>/religious-places.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> All Religious Places
            </a>
        </div>

        <!-- Cards grid -->
        <div class="row g-4">
            <?php foreach ($churches as $church): ?>
            <?php
                $desc    = strip_tags($church['description'] ?? '');
                $excerpt = mb_strlen($desc) > 130 ? mb_substr($desc, 0, 130) . '…' : $desc;
                $imgUrl  = !empty($church['image_url']) ? htmlspecialchars($church['image_url']) : '';
            ?>
            <div class="col-sm-6 col-lg-4" id="church-<?php echo $church['id']; ?>">
                <article class="chr-card h-100">

                    <!-- Image -->
                    <a href="<?php echo htmlspecialchars(getChurchUrl($church['id'], $church['name'], $churchSlugs[(int)$church['id']] ?? null)); ?>" class="chr-card-img-wrap" tabindex="-1" aria-hidden="true">
                        <?php if ($imgUrl): ?>
                        <img src="<?php echo $imgUrl; ?>"
                             alt="<?php echo htmlspecialchars($church['name']); ?> — church in Palakkad"
                             class="chr-card-img"
                             loading="lazy"
                             width="480" height="270">
                        <?php else: ?>
                        <div class="chr-card-img chr-card-img--placeholder d-flex align-items-center justify-content-center">
                            <i class="bi bi-buildings" aria-hidden="true"></i>
                        </div>
                        <?php endif; ?>
                        <div class="chr-card-img-overlay" aria-hidden="true"></div>
                        <span class="chr-card-category-badge">
                            <i class="bi bi-heart-fill me-1"></i>Church
                        </span>
                    </a>

                    <!-- Body -->
                    <div class="chr-card-body">
                        <h2 class="chr-card-title">
                            <a href="<?php echo htmlspecialchars(getChurchUrl($church['id'], $church['name'], $churchSlugs[(int)$church['id']] ?? null)); ?>" class="chr-card-title-link">
                                <?php echo htmlspecialchars($church['name']); ?>
                            </a>
                        </h2>

                        <?php if ($church['location']): ?>
                        <p class="chr-card-location">
                            <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
                            <?php echo htmlspecialchars($church['location']); ?>
                        </p>
                        <?php endif; ?>

                        <?php if ($excerpt): ?>
                        <p class="chr-card-desc"><?php echo htmlspecialchars($excerpt); ?></p>
                        <?php endif; ?>

                        <!-- Meta chips -->
                        <div class="chr-card-meta d-flex flex-wrap gap-2">
                            <?php if ($church['timings']): ?>
                            <span class="chr-meta-chip">
                                <i class="bi bi-clock" aria-hidden="true"></i>
                                <?php echo htmlspecialchars($church['timings']); ?>
                            </span>
                            <?php endif; ?>
                            <?php if ($church['contact']): ?>
                            <span class="chr-meta-chip">
                                <i class="bi bi-telephone" aria-hidden="true"></i>
                                <?php echo htmlspecialchars($church['contact']); ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="chr-card-footer">
                        <a href="<?php echo htmlspecialchars(getChurchUrl($church['id'], $church['name'], $churchSlugs[(int)$church['id']] ?? null)); ?>" class="chr-card-link">
                            View details <i class="bi bi-arrow-right" aria-hidden="true"></i>
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
