<?php
require_once __DIR__ . '/config/config.php';

$pageTitle       = 'Festivals of Palakkad';
$pageDescription = 'Palakkad festivals — Onam, Ratholsavam, Vishu, Christmas, and Eid. Dates and places to celebrate.';
$pageKeywords    = 'Palakkad festivals, Kerala festivals, Onam Palakkad, Ratholsavam, Palakkad cultural events, Kerala celebrations';

$breadcrumbs = [
    ['title' => 'Home',      'url' => BASE_PATH . '/'],
    ['title' => 'Festivals', 'url' => ''],
];

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/database.php';

$conn     = getDBConnection();
$festivals = $conn->query("SELECT * FROM festivals WHERE status='active' ORDER BY start_date ASC")->fetch_all(MYSQLI_ASSOC);
$festivalSlugs = seoSlugMap($festivals);
closeDBConnection($conn);

$festCount = count($festivals);

/* Cycle of festive accent colours for cards */
$accentCycle = ['magenta','gold','teal','orange','violet','rose'];
?>
<!-- Festivals Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "Festivals in Palakkad",
    "description": "List of vibrant festivals and cultural celebrations in Palakkad, Kerala",
    "url": "<?php echo  $seoCanonicalUrl ; ?>",
    "numberOfItems": <?php echo $festCount; ?>,
    "itemListElement": [
        <?php
        $items = [];
        foreach ($festivals as $i => $f) {
            $items[] = '{
                "@type": "ListItem",
                "position": ' . ($i + 1) . ',
                "item": {
                    "@type": "Event",
                    "@id": "' . seoPublicUrl(getFestivalUrl($f['id'], $f['name'], $festivalSlugs[(int)$f['id']] ?? null)) . '",
                    "name": "' . htmlspecialchars($f['name'], ENT_QUOTES) . '",
                    "description": "' . htmlspecialchars(substr(strip_tags($f['description']), 0, 200), ENT_QUOTES) . '",
                    "location": {
                        "@type": "Place",
                        "name": "' . htmlspecialchars($f['location'], ENT_QUOTES) . '",
                        "address": {
                            "@type": "PostalAddress",
                            "addressLocality": "Palakkad",
                            "addressRegion": "Kerala",
                            "addressCountry": "IN"
                        }
                    }' .
                    ($f['start_date'] ? ', "startDate": "' . $f['start_date'] . '"' : '') .
                    ($f['end_date']   ? ', "endDate": "'   . $f['end_date']   . '"' : '') . '
                }
            }';
        }
        echo implode(",\n        ", $items);
        ?>
    ]
}
</script>

<!-- ── Festive page header ── -->
<div class="fst-header">
    <!-- Decorative confetti dots -->
    <div class="fst-header-decor" aria-hidden="true">
        <span class="fst-dot fst-dot--1"></span>
        <span class="fst-dot fst-dot--2"></span>
        <span class="fst-dot fst-dot--3"></span>
        <span class="fst-dot fst-dot--4"></span>
        <span class="fst-dot fst-dot--5"></span>
        <span class="fst-dot fst-dot--6"></span>
        <span class="fst-dot fst-dot--7"></span>
        <span class="fst-dot fst-dot--8"></span>
    </div>
    <div class="container position-relative">
        <span class="fst-kicker">Palakkad · Kerala</span>
        <h1 class="fst-title">Festivals of Palakkad</h1>
        <p class="fst-lead">Colours, drums, lights and devotion — experience the celebrations that bring Palakkad alive through every season.</p>
    </div>
</div>

<!-- ── Main content ── -->
<main id="main-content">
<section class="fst-body py-5">
    <div class="container">

        <?php if (empty($festivals)): ?>
        <div class="alert alert-info rounded-3">
            <i class="bi bi-info-circle me-2"></i>No festivals found. Please check back later.
        </div>
        <?php else: ?>

        <!-- Section intro -->
        <div class="fst-section-intro d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
            <div>
                <h2 class="fst-section-heading mb-1">All Festivals</h2>
                <p class="text-muted small mb-0"><?php echo $festCount; ?> celebrations listed · ordered by date</p>
            </div>
        </div>

        <!-- Cards grid -->
        <div class="row g-4">
            <?php foreach ($festivals as $idx => $festival):
                $accent  = $accentCycle[$idx % count($accentCycle)];
                $desc    = strip_tags($festival['description'] ?? '');
                $excerpt = mb_strlen($desc) > 140 ? mb_substr($desc, 0, 140) . '…' : $desc;
                $imgUrl  = !empty($festival['image_url']) ? htmlspecialchars($festival['image_url']) : '';

                /* Date formatting */
                $dateLabel = '';
                try {
                    $sd = new DateTime($festival['start_date']);
                    $ed = new DateTime($festival['end_date']);
                    if ($festival['start_date'] === $festival['end_date']) {
                        $dateLabel = $sd->format('d M Y');
                    } else {
                        $dateLabel = $sd->format('d M') . ' – ' . $ed->format('d M Y');
                    }
                } catch (Exception $e) {
                    $dateLabel = '';
                }
            ?>
            <div class="col-sm-6 col-lg-4" id="festival-<?php echo $festival['id']; ?>">
                <article class="fst-card fst-card--<?php echo $accent; ?> h-100">

                    <!-- Image -->
                    <a href="<?php echo htmlspecialchars(getFestivalUrl($festival['id'], $festival['name'], $festivalSlugs[(int)$festival['id']] ?? null)); ?>" class="fst-card-img-wrap" tabindex="-1" aria-hidden="true">
                        <?php if ($imgUrl): ?>
                        <img src="<?php echo $imgUrl; ?>"
                             alt="<?php echo htmlspecialchars($festival['name']); ?> — festival in Palakkad"
                             class="fst-card-img"
                             loading="lazy"
                             width="480" height="270">
                        <?php else: ?>
                        <div class="fst-card-img fst-card-img--placeholder fst-placeholder--<?php echo $accent; ?> d-flex align-items-center justify-content-center">
                            <i class="bi bi-stars" aria-hidden="true"></i>
                        </div>
                        <?php endif; ?>
                        <div class="fst-card-img-overlay" aria-hidden="true"></div>

                        <?php if ($festival['type']): ?>
                        <span class="fst-card-type-badge fst-badge--<?php echo $accent; ?>">
                            <?php echo htmlspecialchars($festival['type']); ?>
                        </span>
                        <?php endif; ?>

                        <?php if ($dateLabel): ?>
                        <span class="fst-card-date-badge">
                            <i class="bi bi-calendar3 me-1"></i><?php echo htmlspecialchars($dateLabel); ?>
                        </span>
                        <?php endif; ?>
                    </a>

                    <!-- Body -->
                    <div class="fst-card-body">
                        <h2 class="fst-card-title">
                            <a href="<?php echo htmlspecialchars(getFestivalUrl($festival['id'], $festival['name'], $festivalSlugs[(int)$festival['id']] ?? null)); ?>" class="fst-card-title-link fst-link--<?php echo $accent; ?>">
                                <?php echo htmlspecialchars($festival['name']); ?>
                            </a>
                        </h2>

                        <?php if ($festival['location']): ?>
                        <p class="fst-card-location fst-location--<?php echo $accent; ?>">
                            <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
                            <?php echo htmlspecialchars($festival['location']); ?>
                        </p>
                        <?php endif; ?>

                        <?php if ($excerpt): ?>
                        <p class="fst-card-desc"><?php echo htmlspecialchars($excerpt); ?></p>
                        <?php endif; ?>
                    </div>

                    <!-- Footer -->
                    <div class="fst-card-footer fst-footer--<?php echo $accent; ?>">
                        <a href="<?php echo htmlspecialchars(getFestivalUrl($festival['id'], $festival['name'], $festivalSlugs[(int)$festival['id']] ?? null)); ?>" class="fst-card-link">
                            Explore festival <i class="bi bi-arrow-right" aria-hidden="true"></i>
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
