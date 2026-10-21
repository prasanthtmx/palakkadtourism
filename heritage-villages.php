<?php
require_once __DIR__ . '/config/config.php';

$pageTitle = "Heritage Villages in Palakkad";
$pageDescription = "Explore heritage villages in Palakkad, Kerala. Discover traditional settlements like Kalpathy with rich cultural heritage, ancient architecture, and unique traditions.";
$pageKeywords = "heritage villages Palakkad, Kalpathy, traditional villages Kerala, Palakkad heritage, cultural heritage, Tamil Brahmin settlement";

// Breadcrumbs for SEO
$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'Heritage Villages', 'url' => '']
];

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();
$heritageVillages = $conn->query("SELECT * FROM heritage_villages WHERE status='active' ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
$villageSlugs = seoSlugMap($heritageVillages);
closeDBConnection($conn);
?>
<!-- Heritage Villages Listing Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "Heritage Villages in Palakkad",
    "description": "List of heritage villages and traditional settlements in Palakkad, Kerala",
    "url": "<?php echo  $seoCanonicalUrl ; ?>",
    "numberOfItems": <?php echo count($heritageVillages); ?>,
    "itemListElement": [
        <?php 
        $villageItems = [];
        foreach ($heritageVillages as $index => $village) {
            $villageItems[] = '{
                "@type": "ListItem",
                "position": ' . ($index + 1) . ',
                "item": {
                    "@type": "Place",
                    "@id": "' . seoPublicUrl(getHeritageVillageUrl($village['id'], $village['name'], $villageSlugs[(int)$village['id']] ?? null)) . '",
                    "name": "' . htmlspecialchars($village['name'], ENT_QUOTES) . '",
                    "description": "' . htmlspecialchars(substr($village['description'], 0, 200), ENT_QUOTES) . '",
                    "address": {
                        "@type": "PostalAddress",
                        "addressLocality": "' . htmlspecialchars($village['location'] ?? 'Palakkad', ENT_QUOTES) . '",
                        "addressRegion": "Kerala",
                        "addressCountry": "IN"
                    }
                }
            }';
        }
        echo implode(",\n        ", $villageItems);
        ?>
    ]
}
</script>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="display-4 mb-3">Heritage Villages of Palakkad</h1>
                <p class="lead">Discover the traditional heritage villages of Palakkad, each with its own unique cultural identity, ancient architecture, and rich history. These villages preserve the traditional way of life and showcase Kerala's diverse cultural heritage.</p>
            </div>
        </div>
        
        <?php displayAdSense('content-top', 'max-width: 728px;'); ?>

        <?php if (empty($heritageVillages)): ?>
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> No heritage villages found. Please check back later.
        </div>
        <?php else: ?>
        <div class="row g-4">
            <?php foreach ($heritageVillages as $village): ?>
            <div class="col-md-6 col-lg-4" id="village-<?php echo $village['id']; ?>">
                <div class="card h-100 shadow-sm">
                    <?php if ($village['image_url']): ?>
                    <img src="<?php echo htmlspecialchars($village['image_url']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($village['name'] . ' - Heritage Village in Palakkad, Kerala'); ?>" style="height: 250px; object-fit: cover;" loading="lazy">
                    <?php else: ?>
                    <div class="card-img-top bg-warning d-flex align-items-center justify-content-center" style="height: 250px;">
                        <i class="bi bi-house-heart fs-1 text-white"></i>
                    </div>
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($village['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($village['description']); ?></p>
                        
                        <?php if ($village['location']): ?>
                        <p class="text-muted small mb-1">
                            <i class="bi bi-geo-alt-fill"></i> <strong>Location:</strong> <?php echo htmlspecialchars($village['location']); ?>
                        </p>
                        <?php endif; ?>
                        
                        <?php if ($village['best_time_to_visit']): ?>
                        <p class="text-muted small mb-1">
                            <i class="bi bi-calendar-check"></i> <strong>Best Time to Visit:</strong> <?php echo htmlspecialchars($village['best_time_to_visit']); ?>
                        </p>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer bg-white">
                        <a href="<?php echo htmlspecialchars(getHeritageVillageUrl($village['id'], $village['name'], $villageSlugs[(int)$village['id']] ?? null)); ?>" class="btn btn-sm btn-warning">
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>




