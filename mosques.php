<?php
require_once __DIR__ . '/config/config.php';

$pageTitle       = 'Mosques in Palakkad';
$pageDescription = 'Historic mosques in Palakkad, Kerala — Juma Masjid and other Islamic places of worship.';
$pageKeywords    = 'Palakkad mosques, Kerala mosques, Juma Masjid Palakkad, Islamic places Palakkad, Palakkad Muslim heritage';

$breadcrumbs = [
    ['title' => 'Home',            'url' => BASE_PATH . '/'],
    ['title' => 'Religious Places','url' => ''],
    ['title' => 'Mosques',         'url' => ''],
];

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/database.php';

$conn    = getDBConnection();
$mosques = $conn->query("SELECT * FROM mosques WHERE status='active' ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
closeDBConnection($conn);
$mosqueSlugs = seoSlugMap($mosques);

$mosqueCount = count($mosques);
?>
<!-- Mosques Listing Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "Mosques in Palakkad",
    "description": "List of beautiful mosques and Islamic places of worship in Palakkad, Kerala",
    "url": "<?php echo  $seoCanonicalUrl ; ?>",
    "numberOfItems": <?php echo $mosqueCount; ?>,
    "itemListElement": [
        <?php
        $items = [];
        foreach ($mosques as $i => $m) {
            $items[] = '{
                "@type": "ListItem",
                "position": ' . ($i + 1) . ',
                "item": {
                    "@type": "PlaceOfWorship",
                    "@id": "' . seoPublicUrl(getMosqueUrl($m['id'], $m['name'], $mosqueSlugs[(int)$m['id']] ?? null)) . '",
                    "name": "' . htmlspecialchars($m['name'], ENT_QUOTES) . '",
                    "description": "' . htmlspecialchars(substr(strip_tags($m['description']), 0, 200), ENT_QUOTES) . '",
                    "address": {
                        "@type": "PostalAddress",
                        "addressLocality": "' . htmlspecialchars($m['location'], ENT_QUOTES) . '",
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
<div class="msq-page-header">
    <div class="container">
        <span class="msq-kicker">Palakkad · Kerala</span>
        <h1 class="msq-title">Mosques of Palakkad</h1>
        <p class="msq-lead">Elegant minarets, serene courtyards, and centuries of faith — explore the Islamic heritage woven into the heart of Palakkad.</p>
    </div>
</div>

<!-- ── Main content ── -->
<main id="main-content">
<section class="py-5">
    <div class="container">

        <?php if (empty($mosques)): ?>
        <div class="alert alert-info rounded-3">
            <i class="bi bi-info-circle me-2"></i>No mosques found. Please check back later.
        </div>
        <?php else: ?>

        <!-- Section intro -->
        <div class="msq-section-intro d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h2 class="msq-section-heading mb-1">All Mosques</h2>
                <p class="text-muted small mb-0"><?php echo $mosqueCount; ?> places of worship listed</p>
            </div>
            <a href="mailto:<?php echo SITE_EMAIL; ?>" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                <i class="bi bi-plus-circle me-1"></i> Add a Mosque
            </a>
        </div>

        <!-- Cards grid -->
        <div class="row g-4">
            <?php foreach ($mosques as $mosque): ?>
            <?php
                $desc    = strip_tags($mosque['description'] ?? '');
                $excerpt = mb_strlen($desc) > 130 ? mb_substr($desc, 0, 130) . '…' : $desc;
                $imgUrl  = !empty($mosque['image_url']) ? htmlspecialchars($mosque['image_url']) : '';
            ?>
            <div class="col-sm-6 col-lg-4" id="mosque-<?php echo $mosque['id']; ?>">
                <article class="msq-card h-100">

                    <!-- Image -->
                    <a href="<?php echo htmlspecialchars(getMosqueUrl($mosque['id'], $mosque['name'], $mosqueSlugs[(int)$mosque['id']] ?? null)); ?>" class="msq-card-img-wrap" tabindex="-1" aria-hidden="true">
                        <?php if ($imgUrl): ?>
                        <img src="<?php echo $imgUrl; ?>"
                             alt="<?php echo htmlspecialchars($mosque['name']); ?> — mosque in Palakkad"
                             class="msq-card-img"
                             loading="lazy"
                             width="480" height="270">
                        <?php else: ?>
                        <div class="msq-card-img msq-card-img--placeholder d-flex align-items-center justify-content-center">
                            <i class="bi bi-buildings" aria-hidden="true"></i>
                        </div>
                        <?php endif; ?>
                        <div class="msq-card-img-overlay" aria-hidden="true"></div>
                        <span class="msq-card-category-badge">
                            <i class="bi bi-moon-stars-fill me-1"></i>Mosque
                        </span>
                    </a>

                    <!-- Body -->
                    <div class="msq-card-body">
                        <h2 class="msq-card-title">
                            <a href="<?php echo htmlspecialchars(getMosqueUrl($mosque['id'], $mosque['name'], $mosqueSlugs[(int)$mosque['id']] ?? null)); ?>" class="msq-card-title-link">
                                <?php echo htmlspecialchars($mosque['name']); ?>
                            </a>
                        </h2>

                        <?php if ($mosque['location']): ?>
                        <p class="msq-card-location">
                            <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
                            <?php echo htmlspecialchars($mosque['location']); ?>
                        </p>
                        <?php endif; ?>

                        <?php if ($excerpt): ?>
                        <p class="msq-card-desc"><?php echo htmlspecialchars($excerpt); ?></p>
                        <?php endif; ?>

                        <!-- Meta chips -->
                        <div class="msq-card-meta d-flex flex-wrap gap-2">
                            <?php if ($mosque['timings']): ?>
                            <span class="msq-meta-chip">
                                <i class="bi bi-clock" aria-hidden="true"></i>
                                <?php echo htmlspecialchars($mosque['timings']); ?>
                            </span>
                            <?php endif; ?>
                            <?php if ($mosque['contact']): ?>
                            <span class="msq-meta-chip">
                                <i class="bi bi-telephone" aria-hidden="true"></i>
                                <?php echo htmlspecialchars($mosque['contact']); ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="msq-card-footer">
                        <a href="<?php echo htmlspecialchars(getMosqueUrl($mosque['id'], $mosque['name'], $mosqueSlugs[(int)$mosque['id']] ?? null)); ?>" class="msq-card-link">
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
