<?php
require_once __DIR__ . '/config/config.php';

$pageTitle       = 'Temples in Palakkad, Kerala';
$pageDescription = 'Explore ancient temples in Palakkad, Kerala — Kalpathy, Jainimedu, and village shrines. Plan a temple visit.';
$pageKeywords    = 'Palakkad temples, Kerala temples, Jain temple Palakkad, Kalpathi temple, Palakkad religious places, Hindu temples Kerala';

$breadcrumbs = [
    ['title' => 'Home',            'url' => BASE_PATH . '/'],
    ['title' => 'Religious Places','url' => BASE_PATH . '/religious-places.php'],
    ['title' => 'Temples',         'url' => ''],
];

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/database.php';

$conn    = getDBConnection();
$temples = $conn->query("SELECT * FROM temples WHERE status='active' ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
closeDBConnection($conn);
$templeSlugs = seoSlugMap($temples);
$templeCount = count($temples);
?>
<!-- Temples Listing Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "Temples in Palakkad",
    "description": "List of ancient and historical temples in Palakkad, Kerala",
    "url": "<?php echo  $seoCanonicalUrl ; ?>",
    "numberOfItems": <?php echo $templeCount; ?>,
    "itemListElement": [
        <?php
        $items = [];
        foreach ($temples as $i => $t) {
            $items[] = '{
                "@type": "ListItem",
                "position": ' . ($i + 1) . ',
                "item": {
                    "@type": "PlaceOfWorship",
                    "@id": "' . seoPublicUrl(getTempleUrl($t['id'], $t['name'], $templeSlugs[(int)$t['id']] ?? null)) . '",
                    "name": "' . htmlspecialchars($t['name'], ENT_QUOTES) . '",
                    "description": "' . htmlspecialchars(substr(strip_tags($t['description']), 0, 200), ENT_QUOTES) . '",
                    "address": {
                        "@type": "PostalAddress",
                        "addressLocality": "' . htmlspecialchars($t['location'], ENT_QUOTES) . '",
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
<div class="tpl-page-header">
    <div class="container">
        <span class="tpl-hero-kicker">Palakkad · Kerala</span>
        <h1 class="tpl-hero-title">Temples of Palakkad</h1>
        <p class="tpl-hero-lead">Ancient shrines, sacred rituals, and timeless architecture — discover the spiritual heartbeat of the district.</p>
    </div>
</div>

<!-- ── Main content ── -->
<main id="main-content">
<section class="py-5">
    <div class="container">

        <?php if (empty($temples)): ?>
        <div class="alert alert-info rounded-3">
            <i class="bi bi-info-circle me-2"></i>No temples found. Please check back later.
        </div>
        <?php else: ?>

        <!-- Section intro row -->
        <div class="tpl-section-intro d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h2 class="tpl-section-heading mb-1">All Temples</h2>
                <p class="text-muted small mb-0"><?php echo $templeCount; ?> places of worship listed</p>
            </div>
            <a href="<?php echo BASE_PATH; ?>/religious-places.php" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> All Religious Places
            </a>
        </div>

        <!-- Cards grid -->
        <div class="row g-4">
            <?php foreach ($temples as $temple): ?>
            <?php
                $desc    = strip_tags($temple['description'] ?? '');
                $excerpt = mb_strlen($desc) > 130 ? mb_substr($desc, 0, 130) . '…' : $desc;
                $imgUrl  = !empty($temple['image_url']) ? htmlspecialchars($temple['image_url']) : '';
            ?>
            <div class="col-sm-6 col-lg-4" id="temple-<?php echo $temple['id']; ?>">
                <article class="tpl-card h-100">

                    <!-- Image -->
                    <a href="<?php echo htmlspecialchars(getTempleUrl($temple['id'], $temple['name'], $templeSlugs[(int)$temple['id']] ?? null)); ?>" class="tpl-card-img-wrap" tabindex="-1" aria-hidden="true">
                        <?php if ($imgUrl): ?>
                        <img src="<?php echo $imgUrl; ?>"
                             alt="<?php echo htmlspecialchars($temple['name']); ?> — temple in Palakkad"
                             class="tpl-card-img"
                             loading="lazy"
                             width="480" height="270">
                        <?php else: ?>
                        <div class="tpl-card-img tpl-card-img--placeholder d-flex align-items-center justify-content-center">
                            <i class="bi bi-buildings" aria-hidden="true"></i>
                        </div>
                        <?php endif; ?>
                        <div class="tpl-card-img-overlay" aria-hidden="true"></div>
                        <span class="tpl-card-category-badge">
                            <i class="bi bi-building me-1"></i>Temple
                        </span>
                    </a>

                    <!-- Body -->
                    <div class="tpl-card-body">
                        <h2 class="tpl-card-title">
                            <a href="<?php echo htmlspecialchars(getTempleUrl($temple['id'], $temple['name'], $templeSlugs[(int)$temple['id']] ?? null)); ?>" class="tpl-card-title-link">
                                <?php echo htmlspecialchars($temple['name']); ?>
                            </a>
                        </h2>

                        <?php if ($temple['location']): ?>
                        <p class="tpl-card-location">
                            <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
                            <?php echo htmlspecialchars($temple['location']); ?>
                        </p>
                        <?php endif; ?>

                        <?php if ($excerpt): ?>
                        <p class="tpl-card-desc"><?php echo htmlspecialchars($excerpt); ?></p>
                        <?php endif; ?>

                        <!-- Meta chips -->
                        <div class="tpl-card-meta d-flex flex-wrap gap-2">
                            <?php if ($temple['timings']): ?>
                            <span class="tpl-meta-chip">
                                <i class="bi bi-clock" aria-hidden="true"></i>
                                <?php echo htmlspecialchars($temple['timings']); ?>
                            </span>
                            <?php endif; ?>
                            <?php if ($temple['contact']): ?>
                            <span class="tpl-meta-chip">
                                <i class="bi bi-telephone" aria-hidden="true"></i>
                                <?php echo htmlspecialchars($temple['contact']); ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="tpl-card-footer">
                        <a href="<?php echo htmlspecialchars(getTempleUrl($temple['id'], $temple['name'], $templeSlugs[(int)$temple['id']] ?? null)); ?>" class="tpl-card-link">
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
