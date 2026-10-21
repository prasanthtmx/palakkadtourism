<?php
$pageTitle = "Palakkad Tourism | Kerala travel guide";
$pageDescription = "Travel guide to Palakkad (Palghat), Kerala — temples, dams, hill stations, festivals, and the Rice Bowl of Kerala.";
$pageKeywords = "Palakkad, Palghat, Kerala tourism, Palakkad attractions, Palakkad temples, Palakkad festivals, Kerala travel guide, Palakkad tourist places";
$pageOgImage = 'assets/images/banner.jpg';
$pageHeroPreload = 'assets/images/banner.jpg';
require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();

// Get featured places (limit to 5, excluding Malampuzha)
$featuredPlaces = $conn->query("SELECT * FROM places WHERE status='active' AND name NOT LIKE '%Malampuzha%' ORDER BY id DESC LIMIT 5")->fetch_all(MYSQLI_ASSOC);

// Get upcoming festivals/events
$upcomingEvents = $conn->query("SELECT * FROM festivals WHERE status='active' AND start_date >= CURDATE() ORDER BY start_date ASC LIMIT 5")->fetch_all(MYSQLI_ASSOC);

closeDBConnection($conn);

/**
 * Map a place row to its display image URL. Uses a curated override map
 * (so famous attractions get the prettiest local asset) and falls back to
 * the row's stored image_url.
 */
function home_resolve_place_image(array $place): string {
    $overrides = [
        'Nelliyampathy'   => '/assets/images/seethargundu.jpg',
        'Palakkad Fort'   => '/assets/images/fort.png',
        'Fort'            => '/assets/images/fort.png',
        'Kollengode'      => '/assets/images/kollangodu.jpg',
        'Kollangodu'      => '/assets/images/kollangodu.jpg',
        'Parambikulam'    => '/assets/images/parambikulam-dam.jpg',
        'Silent Valley'   => '/assets/images/silent-valley-national.jpg',
    ];
    $name = (string) ($place['name'] ?? '');
    foreach ($overrides as $needle => $path) {
        if (stripos($name, $needle) !== false) {
            return BASE_PATH . $path;
        }
    }
    return (string) ($place['image_url'] ?? '');
}
?>

<main class="home-page" id="main-content">
<!-- Hero Section -->
<section class="hero-section home-hero home-o-hero">
    <img src="<?php echo BASE_PATH; ?>/assets/images/banner.jpg"
         alt=""
         class="hero-image"
         width="1010"
         height="386"
         decoding="async"
         fetchpriority="high"
         loading="eager"
         aria-hidden="true">
    <div class="hero-overlay"></div>
    <div class="hero-shine" aria-hidden="true"></div>

    <div class="container position-relative">
        <div class="row align-items-center justify-content-center py-3 py-lg-4 home-hero-row">
            <div class="col-lg-9 col-xl-8 mx-auto text-center text-white hero-content">
                <p class="home-a-kicker home-a-kicker--light home-hero-kicker text-uppercase small fw-semibold letter-spacing-wide mb-3">Kerala, India · പാലക്കാട്</p>
                <h1 class="display-3 fw-bold mb-3 hero-title">Palakkad</h1>
                <p class="lead mb-4 hero-subtitle px-md-3">Travel guide to Palakkad — temples, dams, hill stations, festivals, and the Rice Bowl of Kerala.</p>
                <div class="home-hero-ctas d-flex flex-wrap justify-content-center gap-2 gap-md-3">
                    <a href="<?php echo BASE_PATH; ?>/places.php" class="btn btn-light btn-lg hero-button px-4 shadow">
                        <i class="bi bi-compass"></i> Explore Places
                    </a>
                    <a href="<?php echo BASE_PATH; ?>/yellow-pages.php" class="btn btn-outline-light btn-lg hero-button-secondary px-4">
                        <i class="bi bi-shop-window"></i> Yellow Pages
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Visitor notice (moved out of hero for cleaner first impression) -->
<aside class="home-visitor-notice" aria-label="Visitor notice">
    <div class="container d-flex align-items-center justify-content-center gap-2 py-2 small">
        <i class="bi bi-info-circle-fill" aria-hidden="true"></i>
        <span>Please don’t throw plastic bottles or covers on paddy fields or the roadside — let’s keep Palakkad clean.</span>
    </div>
</aside>

<!-- Structured data for the home page -->
<script type="application/ld+json">
<?php
$homeUrl = rtrim(SITE_URL, '/') . BASE_PATH . '/';
$homeImage = rtrim(SITE_URL, '/') . BASE_PATH . '/assets/images/banner.jpg';
$touristDestination = [
    '@context' => 'https://schema.org',
    '@type' => 'TouristDestination',
    'name' => 'Palakkad',
    'alternateName' => ['Palghat', 'പാലക്കാട്'],
    'url' => $homeUrl,
    'image' => $homeImage,
    'description' => 'Palakkad (Palghat) is a district in Kerala, India — known for the Palakkad Gap, paddy fields, dams, temples, and hill stations.',
    'address' => [
        '@type' => 'PostalAddress',
        'addressLocality' => 'Palakkad',
        'addressRegion' => 'Kerala',
        'addressCountry' => 'IN',
    ],
    'geo' => [
        '@type' => 'GeoCoordinates',
        'latitude' => 10.7867,
        'longitude' => 76.6548,
    ],
    'touristType' => ['Cultural tourist', 'Nature lover', 'Pilgrim', 'Family traveler'],
    'sameAs' => [
        'https://en.wikipedia.org/wiki/Palakkad_district',
        'https://en.wikipedia.org/wiki/Palakkad',
    ],
];
echo json_encode($touristDestination, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
</script>
<script type="application/ld+json">
<?php
$faqPage = [
    '@context' => 'https://schema.org',
    '@type' => 'FAQPage',
    'mainEntity' => [
        [
            '@type' => 'Question',
            'name' => 'When is the best time to visit Palakkad?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'October to March is the most pleasant window — cooler weather, clearer skies, and most major festivals fall in this period. April–May is very hot; June–September brings the south-west monsoon.',
            ],
        ],
        [
            '@type' => 'Question',
            'name' => 'How do I reach Palakkad?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'By rail via Palakkad Junction and Shoranur Junction; by road via NH 544 (Salem–Kochi corridor). Nearest airports are Coimbatore International (CJB, about 55 km) and Kochi International (COK, about 145 km).',
            ],
        ],
        [
            '@type' => 'Question',
            'name' => 'What are the must-see places in Palakkad?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Palakkad Fort, Malampuzha Dam and gardens, Nelliyampathy Hills, Silent Valley National Park, Parambikulam Tiger Reserve, Dhoni Waterfalls, and the Kalpathy heritage village.',
            ],
        ],
        [
            '@type' => 'Question',
            'name' => 'Is Palakkad good for a weekend trip?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Yes. A 2-day itinerary easily covers Palakkad town, Malampuzha, and a hill add-on (Nelliyampathy or Parambikulam). For wildlife and trekking, 3 days is more comfortable.',
            ],
        ],
        [
            '@type' => 'Question',
            'name' => 'How can I list my business on Palakkad Tourism?',
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => 'Use the Yellow Pages submission form. Listings are free and reviewed by a moderator before going live.',
            ],
        ],
    ],
];
echo json_encode($faqPage, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
</script>

<!-- About Palakkad and Upcoming Events Section -->
<section class="home-o-section home-intro" aria-label="About Palakkad and upcoming events">
    <div class="container">
        <?php displayAdSense('homepage-top', 'max-width: 728px;'); ?>
        <div class="row g-4 align-items-start home-intro-row">
            <!-- About Palakkad -->
            <div class="col-lg-8">
                <article class="info-box info-box--home info-box--about shadow-lg rounded-4 p-4 p-lg-5">
                    <header class="home-intro-card-header">
                        <span class="home-intro-card-icon home-intro-card-icon--about" aria-hidden="true"><i class="bi bi-book-half"></i></span>
                        <div>
                            <h2 class="mb-0">About Palakkad</h2>
                        </div>
                    </header>
                    <p class="lead home-intro-lead">Palakkad, also known as Palghat, is a district in Kerala with distinctive historical significance.</p>
                    <ul class="list-unstyled home-about-stats" aria-label="Palakkad at a glance">
                        <li>
                            <span class="home-about-stat-label">Area</span>
                            <span class="home-about-stat-value">≈ 4,480 km²</span>
                        </li>
                        <li>
                            <span class="home-about-stat-label">Formed</span>
                            <span class="home-about-stat-value">1 Jan 1957</span>
                        </li>
                        <li>
                            <span class="home-about-stat-label">Headquarters</span>
                            <span class="home-about-stat-value">Palakkad town</span>
                        </li>
                        <li>
                            <span class="home-about-stat-label">Languages</span>
                            <span class="home-about-stat-value">Malayalam · Tamil</span>
                        </li>
                    </ul>
                    <p class="home-intro-text">The fort of Hyder Ali narrates the tale of Mysore invasions and the arrival of the British in this region. In 1886, the inception of Victoria College marked the commencement of higher education in Malabar. The Jain temple near Chunnamputhara stands as a testament to the benevolence of the king of Palakkad, who offered refuge to those fleeing religious persecutions.</p>
                    <p class="home-intro-text">Palakkad district was officially formed on November 1, 1956, as part of the reorganization of states in India. Since then, it has flourished as an integral part of Kerala, with its own unique cultural and historical significance.</p>
                    <a href="<?php echo BASE_PATH; ?>/about.php" class="btn btn-primary rounded-pill px-4 mt-3 home-intro-btn">
                        <i class="bi bi-arrow-right me-1"></i> Read more
                    </a>

                    <div class="home-election-embed pt-4 mt-4" aria-labelledby="home-election-2026-title">
                        <header class="home-intro-card-header home-election-embed-header">
                            <span class="home-intro-card-icon home-intro-card-icon--election" aria-hidden="true"><i class="bi bi-clipboard-check"></i></span>
                            <div>
                                <h3 id="home-election-2026-title" class="home-election-embed-title mb-0">Kerala Assembly 2026 — Palakkad</h3>
                                <p class="home-election-eyebrow text-uppercase small fw-semibold letter-spacing-wide mt-2 mb-0">Legislative election results</p>
                            </div>
                        </header>
                        <p class="home-election-intro text-muted small mb-3">Winners in all 12 Palakkad district assembly constituencies (Kerala Legislative Assembly Election 2026).</p>
                        <ul class="list-unstyled home-election-list home-election-list--cols-2 mb-0">
                            <li class="home-election-item">
                                <div class="home-election-const">Alathur</div>
                                <div class="home-election-body">
                                    <span class="home-election-name">T. M. Sasi</span>
                                    <span class="home-election-badge home-election-badge--ldf" title="Left Democratic Front">LDF</span>
                                </div>
                            </li>
                            <li class="home-election-item">
                                <div class="home-election-const">Chittur</div>
                                <div class="home-election-body">
                                    <span class="home-election-name">Sumesh Achuthan</span>
                                    <span class="home-election-badge home-election-badge--udf" title="United Democratic Front">UDF</span>
                                </div>
                            </li>
                            <li class="home-election-item">
                                <div class="home-election-const">Kongad</div>
                                <div class="home-election-body">
                                    <span class="home-election-name">K. A. Thulasi</span>
                                    <span class="home-election-badge home-election-badge--udf" title="United Democratic Front">UDF</span>
                                </div>
                            </li>
                            <li class="home-election-item">
                                <div class="home-election-const">Malampuzha</div>
                                <div class="home-election-body">
                                    <span class="home-election-name">A. Prabhakaran</span>
                                    <span class="home-election-badge home-election-badge--ldf" title="Left Democratic Front">LDF</span>
                                </div>
                            </li>
                            <li class="home-election-item">
                                <div class="home-election-const">Mannarkkad</div>
                                <div class="home-election-body">
                                    <span class="home-election-name">N. Samssudheen</span>
                                    <span class="home-election-badge home-election-badge--udf" title="United Democratic Front">UDF</span>
                                </div>
                            </li>
                            <li class="home-election-item">
                                <div class="home-election-const">Nemmara</div>
                                <div class="home-election-body">
                                    <span class="home-election-name">K. Preman</span>
                                    <span class="home-election-badge home-election-badge--ldf" title="Left Democratic Front">LDF</span>
                                </div>
                            </li>
                            <li class="home-election-item">
                                <div class="home-election-const">Ottapalam</div>
                                <div class="home-election-body">
                                    <span class="home-election-name">K. Premkumar</span>
                                    <span class="home-election-badge home-election-badge--ldf" title="Left Democratic Front">LDF</span>
                                </div>
                            </li>
                            <li class="home-election-item">
                                <div class="home-election-const">Palakkad</div>
                                <div class="home-election-body">
                                    <span class="home-election-name">Ramesh Pisharody</span>
                                    <span class="home-election-badge home-election-badge--udf" title="United Democratic Front">UDF</span>
                                </div>
                            </li>
                            <li class="home-election-item">
                                <div class="home-election-const">Pattambi</div>
                                <div class="home-election-body">
                                    <span class="home-election-name">Muhammed Mushassin</span>
                                    <span class="home-election-badge home-election-badge--ldf" title="Left Democratic Front">LDF</span>
                                </div>
                            </li>
                            <li class="home-election-item">
                                <div class="home-election-const">Shoranur</div>
                                <div class="home-election-body">
                                    <span class="home-election-name">P. Mammikutty</span>
                                    <span class="home-election-badge home-election-badge--ldf" title="Left Democratic Front">LDF</span>
                                </div>
                            </li>
                            <li class="home-election-item">
                                <div class="home-election-const">Tarur</div>
                                <div class="home-election-body">
                                    <span class="home-election-name">P. P. Sumod</span>
                                    <span class="home-election-badge home-election-badge--ldf" title="Left Democratic Front">LDF</span>
                                </div>
                            </li>
                            <li class="home-election-item">
                                <div class="home-election-const">Thrithala</div>
                                <div class="home-election-body">
                                    <span class="home-election-name">V. T. Balram</span>
                                    <span class="home-election-badge home-election-badge--udf" title="United Democratic Front">UDF</span>
                                </div>
                            </li>
                        </ul>
                        <a href="<?php echo BASE_PATH; ?>/palakkad-assembly-constituencies-2026.php" class="btn home-intro-events-btn rounded-pill px-4 w-100 mt-4">
                            <i class="bi bi-map me-1"></i> All Palakkad constituencies &amp; details
                        </a>
                    </div>
                </article>

                <aside class="home-plan-trip info-box info-box--home info-box--plan shadow-lg rounded-4 p-4 p-lg-5 mt-4" aria-labelledby="plan-trip-heading">
                    <header class="home-intro-card-header home-plan-trip-header mb-3">
                        <span class="home-intro-card-icon home-plan-trip-icon" aria-hidden="true"><i class="bi bi-suitcase-lg"></i></span>
                        <div>
                            <h2 id="plan-trip-heading" class="mb-0 home-plan-trip-title">Plan your trip</h2>
                            <p class="home-plan-trip-sub text-muted small mb-0 mt-1">Quick picks to plan Palakkad in minutes</p>
                        </div>
                    </header>
                    <ul class="list-unstyled home-plan-trip-list home-plan-trip-list--cols-2 mb-0">
                        <li class="home-plan-trip-row">
                            <a href="<?php echo BASE_PATH; ?>/about.php" class="home-plan-trip-link">
                                <span class="home-plan-trip-emoji" aria-hidden="true">📅</span>
                                <span class="home-plan-trip-body">
                                    <span class="home-plan-trip-label">Best time to visit</span>
                                    <span class="home-plan-trip-hint">Oct–Mar · pleasant days &amp; festivals</span>
                                </span>
                                <i class="bi bi-chevron-right home-plan-trip-chevron" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="home-plan-trip-row">
                            <a href="<?php echo BASE_PATH; ?>/places.php" class="home-plan-trip-link">
                                <span class="home-plan-trip-emoji" aria-hidden="true">🗺️</span>
                                <span class="home-plan-trip-body">
                                    <span class="home-plan-trip-label">1-day / 2-day itinerary</span>
                                    <span class="home-plan-trip-hint">Must-see places &amp; day-trip ideas</span>
                                </span>
                                <i class="bi bi-chevron-right home-plan-trip-chevron" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="home-plan-trip-row">
                            <a href="<?php echo BASE_PATH; ?>/how-to-reach.php" class="home-plan-trip-link">
                                <span class="home-plan-trip-emoji" aria-hidden="true">🚗</span>
                                <span class="home-plan-trip-body">
                                    <span class="home-plan-trip-label">Travel routes</span>
                                    <span class="home-plan-trip-hint">How to reach · passes &amp; NH links</span>
                                </span>
                                <i class="bi bi-chevron-right home-plan-trip-chevron" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="home-plan-trip-row">
                            <a href="<?php echo BASE_PATH; ?>/municipality-palakkad.php" class="home-plan-trip-link">
                                <span class="home-plan-trip-emoji" aria-hidden="true">🏨</span>
                                <span class="home-plan-trip-body">
                                    <span class="home-plan-trip-label">Stay options</span>
                                    <span class="home-plan-trip-hint">Town hubs · expand from Palakkad HQ</span>
                                </span>
                                <i class="bi bi-chevron-right home-plan-trip-chevron" aria-hidden="true"></i>
                            </a>
                        </li>
                    </ul>
                </aside>
            </div>

            <!-- Upcoming Events -->
            <div class="col-lg-4">
                <!-- How to Reach button above events box -->
                <div class="home-htr-btn-wrap">
                    <a href="<?php echo BASE_PATH; ?>/how-to-reach.php" class="home-htr-btn">
                        <i class="bi bi-signpost-2-fill home-htr-btn-icon" aria-hidden="true"></i>
                        <span class="home-htr-btn-text">
                            How to Reach Palakkad
                            <small>Train · Road · Air · Local tips</small>
                        </span>
                        <i class="bi bi-chevron-right home-htr-btn-arrow" aria-hidden="true"></i>
                    </a>
                </div>
                <article class="info-box info-box--home info-box--events shadow-lg rounded-4 p-4">
                    <header class="home-intro-card-header">
                        <span class="home-intro-card-icon home-intro-card-icon--events" aria-hidden="true"><i class="bi bi-calendar-event"></i></span>
                        <div>
                            <h2 class="mb-0">Upcoming Events</h2>
                        </div>
                    </header>
                    <?php if (!empty($upcomingEvents)): ?>
                        <div class="events-list home-intro-events-list">
                            <?php foreach ($upcomingEvents as $event): ?>
                            <div class="event-item home-intro-event">
                                <h5 class="home-intro-event-title"><?php echo htmlspecialchars($event['name']); ?></h5>
                                <p class="home-intro-event-meta mb-1">
                                    <span class="home-intro-event-date"><i class="bi bi-calendar3" aria-hidden="true"></i>
                                    <?php
                                    $startDate = new DateTime($event['start_date']);
                                    $endDate = new DateTime($event['end_date']);
                                    echo $startDate->format('M j, Y');
                                    if ($event['start_date'] != $event['end_date']) {
                                        echo ' – ' . $endDate->format('M j, Y');
                                    }
                                    ?></span>
                                </p>
                                <?php if ($event['location']): ?>
                                <p class="home-intro-event-location mb-0">
                                    <i class="bi bi-geo-alt" aria-hidden="true"></i> <?php echo htmlspecialchars($event['location']); ?>
                                </p>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <a href="<?php echo BASE_PATH; ?>/festivals.php" class="btn home-intro-events-btn rounded-pill px-4 w-100 mt-3">
                            <i class="bi bi-arrow-right me-1"></i> View all events
                        </a>
                    <?php else: ?>
                        <p class="home-intro-empty text-muted mb-3">No upcoming events right now. Browse festivals and cultural dates anytime.</p>
                        <a href="<?php echo BASE_PATH; ?>/festivals.php" class="btn home-intro-events-btn rounded-pill px-4 w-100 mt-0">
                            <i class="bi bi-calendar3 me-1"></i> View all festivals
                        </a>
                    <?php endif; ?>

                    <div class="home-intro-weather" aria-labelledby="home-weather-label">
                        <p id="home-weather-label" class="home-intro-weather-label mb-2">Palakkad weather</p>
                        <div class="weather-widget-container">
                            <div class="weather-widget" id="weatherWidget">
                                <div class="weather-loading">
                                    <i class="bi bi-cloud-sun"></i> Loading…
                                </div>
                            </div>
                        </div>
                    </div>
                </article>

                <aside class="home-trending info-box info-box--home info-box--trending shadow-lg rounded-4 p-4 mt-4" aria-labelledby="trending-heading">
                    <header class="home-intro-card-header home-trending-header mb-3">
                        <span class="home-intro-card-icon home-trending-icon" aria-hidden="true"><i class="bi bi-graph-up-arrow"></i></span>
                        <div>
                            <h2 id="trending-heading" class="mb-0 home-plan-trip-title">Trending now</h2>
                            <p class="home-plan-trip-sub text-muted small mb-0 mt-1">What’s hot right now on Palakkad Tourism</p>
                        </div>
                    </header>
                    <ul class="list-unstyled home-trending-list mb-0">
                        <li class="home-plan-trip-row">
                            <a href="<?php echo BASE_PATH; ?>/festivals.php" class="home-plan-trip-link">
                                <span class="home-plan-trip-emoji" aria-hidden="true">🔥</span>
                                <span class="home-plan-trip-body">
                                    <span class="home-plan-trip-label">Trending this week</span>
                                    <span class="home-plan-trip-hint">Festivals, fairs &amp; seasonal highlights</span>
                                </span>
                                <i class="bi bi-chevron-right home-plan-trip-chevron" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="home-plan-trip-row">
                            <a href="https://www.instagram.com/explore/tags/palakkad/" class="home-plan-trip-link" target="_blank" rel="noopener noreferrer">
                                <span class="home-plan-trip-emoji" aria-hidden="true">📸</span>
                                <span class="home-plan-trip-body">
                                    <span class="home-plan-trip-label">Instagram popular</span>
                                    <span class="home-plan-trip-hint">#palakkad — opens in a new tab</span>
                                </span>
                                <i class="bi bi-box-arrow-up-right home-plan-trip-chevron home-trending-external" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="home-plan-trip-row">
                            <a href="<?php echo BASE_PATH; ?>/places.php" class="home-plan-trip-link">
                                <span class="home-plan-trip-emoji" aria-hidden="true">⭐</span>
                                <span class="home-plan-trip-body">
                                    <span class="home-plan-trip-label">Top rated places</span>
                                    <span class="home-plan-trip-hint">Must-see spots across the district</span>
                                </span>
                                <i class="bi bi-chevron-right home-plan-trip-chevron" aria-hidden="true"></i>
                            </a>
                        </li>
                    </ul>
                </aside>

                <aside class="home-explore-palakkad info-box info-box--home info-box--explore shadow-lg rounded-4 p-4 mt-4" aria-labelledby="explore-palakkad-heading">
                    <header class="home-intro-card-header home-explore-header mb-0">
                        <span class="home-intro-card-icon home-explore-icon" aria-hidden="true"><i class="bi bi-shop-window"></i></span>
                        <div>
                            <h2 id="explore-palakkad-heading" class="mb-0 home-plan-trip-title">Explore Palakkad</h2>
                            <p class="home-plan-trip-sub text-muted small mb-0 mt-1">
                                <a href="<?php echo BASE_PATH; ?>/yellow-pages.php" class="home-explore-palakkad-anchor text-decoration-none fw-semibold">Browse the business directory; free listings; find shops &amp; services near you; add your business.</a>
                            </p>
                        </div>
                    </header>
                </aside>

                <!-- Vintage Gallery quick-link -->
                <a href="<?php echo BASE_PATH; ?>/vintage-gallery.php" class="home-vg-btn mt-3 d-flex align-items-center gap-3">
                    <span class="home-vg-btn-icon" aria-hidden="true"><i class="bi bi-camera-fill"></i></span>
                    <span class="home-vg-btn-text">
                        Vintage Gallery
                        <small>Heritage photographs of Palakkad through the ages</small>
                    </span>
                    <i class="bi bi-chevron-right home-vg-btn-arrow ms-auto" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- West Wide Way — Dams & reservoirs -->
<section class="home-o-section home-west-wide-way" id="west-wide-way" aria-labelledby="west-wide-way-heading">
    <div class="container">
        <header class="home-m-head home-m-head--narrow">
            <p class="home-a-kicker home-section-eyebrow text-secondary text-uppercase small fw-semibold letter-spacing-wide">Explore the district</p>
            <h2 id="west-wide-way-heading" class="home-west-wide-way-title mb-2">West Wide Way — Dams &amp; reservoirs</h2>
            <p class="lead text-muted mb-0 mx-auto home-west-wide-way-lead">Major dams and water bodies around Palakkad — quick facts and links for deeper guides.</p>
        </header>

        <div class="row g-4">
            <div class="col-lg-6">
                <article class="home-dam-item card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                    <div class="card-body p-4">
                        <h3 class="h5 mb-3"><a href="<?php echo BASE_PATH; ?>/dam-malampuzha.php" class="stretched-link text-decoration-none home-dam-title">1. Malampuzha Dam</a></h3>
                        <ul class="list-unstyled home-dam-notes mb-0 small">
                            <li><span class="home-dam-icon" aria-hidden="true">📍</span> Near Palakkad town</li>
                            <li><span class="home-dam-icon" aria-hidden="true">⭐</span> Most popular tourist spot</li>
                            <li><span class="home-dam-icon" aria-hidden="true">🎡</span> Garden, ropeway, boating available</li>
                        </ul>
                    </div>
                </article>
            </div>
            <div class="col-lg-6">
                <article class="home-dam-item card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                    <div class="card-body p-4">
                        <h3 class="h5 mb-3"><a href="<?php echo BASE_PATH; ?>/dam-parambikulam.php" class="stretched-link text-decoration-none home-dam-title">2. Parambikulam Dam</a></h3>
                        <ul class="list-unstyled home-dam-notes mb-0 small">
                            <li><span class="home-dam-icon" aria-hidden="true">📍</span> Inside Parambikulam Tiger Reserve</li>
                            <li><span class="home-dam-icon" aria-hidden="true">🌿</span> Dense forest + wildlife</li>
                            <li><span class="home-dam-icon" aria-hidden="true">🚫</span> Entry requires forest permission</li>
                        </ul>
                    </div>
                </article>
            </div>
            <div class="col-lg-6">
                <article class="home-dam-item card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                    <div class="card-body p-4">
                        <h3 class="h5 mb-3"><a href="<?php echo BASE_PATH; ?>/dam-mangalam.php" class="stretched-link text-decoration-none home-dam-title">3. Mangalam Dam</a></h3>
                        <ul class="list-unstyled home-dam-notes mb-0 small">
                            <li><span class="home-dam-icon" aria-hidden="true">📍</span> Near Ottapalam / Alathur side</li>
                            <li><span class="home-dam-icon" aria-hidden="true">🌄</span> Scenic hills + calm atmosphere</li>
                            <li><span class="home-dam-icon" aria-hidden="true">🧘</span> Less crowded, peaceful</li>
                        </ul>
                    </div>
                </article>
            </div>
            <div class="col-lg-6">
                <article class="home-dam-item card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                    <div class="card-body p-4">
                        <h3 class="h5 mb-3"><a href="<?php echo BASE_PATH; ?>/dam-meenkara.php" class="stretched-link text-decoration-none home-dam-title">4. Meenkara Dam</a></h3>
                        <ul class="list-unstyled home-dam-notes mb-0 small">
                            <li><span class="home-dam-icon" aria-hidden="true">📍</span> Near Kollengode</li>
                            <li><span class="home-dam-icon" aria-hidden="true">🌴</span> Surrounded by coconut farms</li>
                            <li><span class="home-dam-icon" aria-hidden="true">📸</span> Good for photography</li>
                        </ul>
                    </div>
                </article>
            </div>
            <div class="col-lg-6">
                <article class="home-dam-item card border-0 shadow-sm h-100 rounded-4 overflow-hidden">
                    <div class="card-body p-4">
                        <h3 class="h5 mb-3"><a href="<?php echo BASE_PATH; ?>/dam-chulliyar.php" class="stretched-link text-decoration-none home-dam-title">5. Chulliyar Dam</a></h3>
                        <ul class="list-unstyled home-dam-notes mb-0 small">
                            <li><span class="home-dam-icon" aria-hidden="true">📍</span> Near Muthalamada (TN border)</li>
                            <li><span class="home-dam-icon" aria-hidden="true">🌾</span> Agriculture + water storage</li>
                            <li><span class="home-dam-icon" aria-hidden="true">🛣️</span> Good road access</li>
                        </ul>
                    </div>
                </article>
            </div>
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100 rounded-4 home-dam-item home-dam-item--alt">
                    <div class="card-body p-4">
                        <h3 class="h6 text-uppercase letter-spacing-wide text-muted mb-3">Other dams &amp; reservoirs</h3>
                        <ul class="list-unstyled home-dam-notes mb-0 small">
                            <li class="mb-2"><a href="<?php echo BASE_PATH; ?>/dam-walayar.php" class="fw-semibold text-decoration-none">Walayar Dam</a> — border area, quick visits</li>
                            <li class="mb-2"><a href="<?php echo BASE_PATH; ?>/dam-pothundy.php" class="fw-semibold text-decoration-none">Pothundy Dam</a> — border area; sometimes grouped with Palakkad tourism</li>
                            <li class="mb-0"><a href="<?php echo BASE_PATH; ?>/dam-siruvani.php" class="fw-semibold text-decoration-none">Siruvani Dam</a> — water source; <span class="text-muted">restricted access</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <p class="text-center text-muted small mt-4 mb-0">Each link opens a dedicated page — add timings, maps, and visitor tips there.</p>
    </div>
</section>

<!-- Municipalities Section -->
<section class="home-o-section municipalities-section home-municipalities" aria-labelledby="home-municipalities-heading">
    <div class="container">
        <header class="home-m-head home-municipalities-head">
            <p class="home-a-kicker home-a-kicker--light home-section-eyebrow text-center text-white-50 text-uppercase small fw-semibold letter-spacing-wide">Explore the district</p>
            <h2 id="home-municipalities-heading" class="text-center mb-2 mb-md-3 text-white home-section-title home-municipalities-title">Municipalities of Palakkad</h2>
            <p class="text-center lead text-white-50 mx-auto home-section-lead home-municipalities-lead mb-4 mb-lg-5">Seven urban local bodies—each with its own character and role in the district.</p>
        </header>
        <div class="row g-3 g-lg-4 home-municipalities-grid">
            <div class="col-md-6 col-lg-4">
                <a href="<?php echo BASE_PATH; ?>/municipality-palakkad.php" class="home-municipality-anchor text-decoration-none d-block h-100">
                    <div class="municipality-card municipality-card-1 h-100">
                        <h5 class="municipality-name">Palakkad (Palghat)</h5>
                        <p class="municipality-desc">The district headquarters and largest municipality, known for its administrative significance and cultural heritage.</p>
                        <span class="municipality-link">Learn More <i class="bi bi-arrow-right"></i></span>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-4">
                <a href="<?php echo BASE_PATH; ?>/municipality-ottappalam.php" class="home-municipality-anchor text-decoration-none d-block h-100">
                    <div class="municipality-card municipality-card-2 h-100">
                        <h5 class="municipality-name">Ottappalam</h5>
                        <p class="municipality-desc">A prominent municipality known for its cultural activities and historical significance.</p>
                        <span class="municipality-link">Learn More <i class="bi bi-arrow-right"></i></span>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-4">
                <a href="<?php echo BASE_PATH; ?>/municipality-shoranur.php" class="home-municipality-anchor text-decoration-none d-block h-100">
                    <div class="municipality-card municipality-card-3 h-100">
                        <h5 class="municipality-name">Shoranur</h5>
                        <p class="municipality-desc">An important railway junction serving as a key transportation hub in the region.</p>
                        <span class="municipality-link">Learn More <i class="bi bi-arrow-right"></i></span>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-4">
                <a href="<?php echo BASE_PATH; ?>/municipality-chittur.php" class="home-municipality-anchor text-decoration-none d-block h-100">
                    <div class="municipality-card municipality-card-4 h-100">
                        <h5 class="municipality-name">Chittur</h5>
                        <p class="municipality-desc">Known for its agricultural importance and educational institutions in Palakkad district.</p>
                        <span class="municipality-link">Learn More <i class="bi bi-arrow-right"></i></span>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-4">
                <a href="<?php echo BASE_PATH; ?>/municipality-mannarkkad.php" class="home-municipality-anchor text-decoration-none d-block h-100">
                    <div class="municipality-card municipality-card-5 h-100">
                        <h5 class="municipality-name">Mannarkkad</h5>
                        <p class="municipality-desc">Known for its scenic beauty and proximity to wildlife sanctuaries.</p>
                        <span class="municipality-link">Learn More <i class="bi bi-arrow-right"></i></span>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-4">
                <a href="<?php echo BASE_PATH; ?>/municipality-cherpulassery.php" class="home-municipality-anchor text-decoration-none d-block h-100">
                    <div class="municipality-card municipality-card-6 h-100">
                        <h5 class="municipality-name">Cherpulassery</h5>
                        <p class="municipality-desc">Contributing to the administrative and economic development of the region.</p>
                        <span class="municipality-link">Learn More <i class="bi bi-arrow-right"></i></span>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-lg-4">
                <a href="<?php echo BASE_PATH; ?>/municipality-pattambi.php" class="home-municipality-anchor text-decoration-none d-block h-100">
                    <div class="municipality-card municipality-card-7 h-100">
                        <h5 class="municipality-name">Pattambi</h5>
                        <p class="municipality-desc">A municipality with rich cultural heritage and historical importance in the Palakkad region.</p>
                        <span class="municipality-link">Learn More <i class="bi bi-arrow-right"></i></span>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Places Section -->
<?php if (!empty($featuredPlaces)): ?>
<section class="home-o-section home-o-section--featured featured-places-section home-featured">
    <div class="container">
        <header class="home-m-head home-m-head--tight home-featured-header text-center">
            <p class="home-a-kicker home-section-eyebrow text-secondary text-uppercase small fw-semibold letter-spacing-wide mb-1 mb-md-2">Must-see destinations</p>
            <h2 class="section-title home-featured-title mb-0">Featured Places to Visit</h2>
        </header>
        <div class="row g-2 g-md-3 home-featured-grid">
            <?php 
            $placeCount = count($featuredPlaces);
            for ($i = 0; $i < $placeCount; $i++): 
                $place = $featuredPlaces[$i];
            ?>
                <?php
                $colCls = ($i === 1) ? 'col-md-8 col-lg-8' : 'col-md-4 col-lg-4';
                $wrapCls = ($i === 1) ? 'destination-image-wrapper destination-image-merged' : 'destination-image-wrapper';
                $imageUrl = home_resolve_place_image($place);
                $isHeroCard = ($i === 0);
                ?>
                <div class="<?php echo $colCls; ?>">
                    <a href="<?php echo htmlspecialchars(getPlaceUrl((int)$place['id'], $place['name'])); ?>"
                       class="destination-card"
                       aria-label="<?php echo htmlspecialchars('Open guide for ' . $place['name']); ?>">
                        <div class="<?php echo $wrapCls; ?>">
                            <?php if ($imageUrl): ?>
                                <img src="<?php echo htmlspecialchars($imageUrl); ?>"
                                     class="destination-image"
                                     alt="<?php echo htmlspecialchars($place['name'] . ' — tourist destination in Palakkad, Kerala'); ?>"
                                     width="800"
                                     height="600"
                                     decoding="async"
                                     <?php if ($isHeroCard): ?>fetchpriority="high" loading="eager"<?php else: ?>loading="lazy"<?php endif; ?>>
                            <?php else: ?>
                                <div class="destination-image bg-secondary d-flex align-items-center justify-content-center">
                                    <i class="bi bi-image fs-1 text-white" aria-hidden="true"></i>
                                </div>
                            <?php endif; ?>
                            <div class="destination-overlay"></div>
                            <div class="destination-info">
                                <h3 class="destination-name"><?php echo htmlspecialchars($place['name']); ?></h3>
                                <p class="destination-location">
                                    <i class="bi bi-geo-alt" aria-hidden="true"></i> <?php echo htmlspecialchars($place['location']); ?>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endfor; ?>
        </div>
        <div class="text-center home-featured-cta">
            <a href="<?php echo BASE_PATH; ?>/places.php" class="btn btn-primary btn-lg px-4 rounded-pill shadow-sm">View All Places</a>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Palakkad History Journey Section -->
<section class="home-o-section home-o-section--history history-journey-section home-history" aria-labelledby="home-history-heading">
    <div class="container">
        <header class="home-m-head home-history-header text-center mx-auto">
            <p class="home-a-kicker home-a-kicker--light home-section-eyebrow text-white-50 text-uppercase small fw-semibold letter-spacing-wide mb-2">Heritage</p>
            <div class="home-history-title-row d-flex flex-column flex-md-row align-items-center justify-content-center gap-2 gap-md-3 mb-3">
                <span class="home-history-title-icon" aria-hidden="true"><i class="bi bi-clock-history"></i></span>
                <h2 id="home-history-heading" class="home-history-title mb-0">Journey Through Time: Palakkad’s History</h2>
            </div>
            <p class="home-history-lead text-white-50 mx-auto mb-4 mb-md-5">From ancient Tamilakam and the Palakkad chieftains through Mysore, the British era, and state formation to today’s Kerala — a quick path through the district’s past.</p>
        </header>
        <!-- Horizontal scrollable timeline -->
        <div class="hst-timeline home-hst-timeline">

            <div class="hst-tl-item">
                <div class="hst-tl-marker"></div>
                <div class="hst-tl-content">
                    <div class="hst-tl-title">Part of Chera Kingdom</div>
                    <div class="hst-tl-desc">Ancient Tamilakam</div>
                </div>
            </div>

            <div class="hst-tl-item">
                <div class="hst-tl-marker"></div>
                <div class="hst-tl-content">
                    <div class="hst-tl-title">Rule of Local Chieftains</div>
                    <div class="hst-tl-desc">Palakkad Rajas · Zamorins of Calicut</div>
                </div>
            </div>

            <div class="hst-tl-item">
                <div class="hst-tl-marker"></div>
                <div class="hst-tl-content">
                    <div class="hst-tl-title">Invasion of Mysore Kings</div>
                    <div class="hst-tl-desc">Hyder Ali &amp; Tipu Sultan · 18th Century</div>
                </div>
            </div>

            <div class="hst-tl-item">
                <div class="hst-tl-marker"></div>
                <div class="hst-tl-content">
                    <div class="hst-tl-title">Mysore–British Conflicts</div>
                    <div class="hst-tl-desc">Three Anglo-Mysore Wars</div>
                </div>
            </div>

            <div class="hst-tl-item hst-tl-item--major">
                <div class="hst-tl-marker hst-tl-marker--major"></div>
                <div class="hst-tl-content">
                    <div class="hst-tl-era">1792</div>
                    <div class="hst-tl-title">British Take Control</div>
                    <div class="hst-tl-desc">Treaty of Seringapatam · Palakkad joins Malabar District, Madras Presidency</div>
                </div>
            </div>

            <div class="hst-tl-item hst-tl-item--major">
                <div class="hst-tl-marker hst-tl-marker--major"></div>
                <div class="hst-tl-content">
                    <div class="hst-tl-era">1947</div>
                    <div class="hst-tl-title">Post-Independence India</div>
                    <div class="hst-tl-desc">Still part of Malabar District under Madras State</div>
                </div>
            </div>

            <div class="hst-tl-item hst-tl-item--major">
                <div class="hst-tl-marker hst-tl-marker--major"></div>
                <div class="hst-tl-content">
                    <div class="hst-tl-era">1 Nov 1956</div>
                    <div class="hst-tl-title">Formation of Kerala State</div>
                    <div class="hst-tl-desc">States Reorganisation Act — Malabar merges into Kerala</div>
                </div>
            </div>

            <div class="hst-tl-item hst-tl-item--major">
                <div class="hst-tl-marker hst-tl-marker--major"></div>
                <div class="hst-tl-content">
                    <div class="hst-tl-era">1 Jan 1957</div>
                    <div class="hst-tl-title">Palakkad District Formed</div>
                    <div class="hst-tl-desc">Merger of 6 taluks into the new Palakkad district</div>
                </div>
            </div>

            <div class="hst-tl-item hst-tl-item--final">
                <div class="hst-tl-marker hst-tl-marker--final"></div>
                <div class="hst-tl-content">
                    <div class="hst-tl-title">Modern Palakkad</div>
                    <div class="hst-tl-desc">"Rice Bowl of Kerala" · Gateway through the Western Ghats</div>
                </div>
            </div>

        </div>
        <p class="hst-swipe-hint" aria-hidden="true">← swipe to explore →</p>

        <p class="text-center home-history-cta mt-4 mb-0">
            <a href="<?php echo BASE_PATH; ?>/history.php" class="btn btn-outline-light btn-sm rounded-pill px-4 py-2">
                <i class="bi bi-book-half me-1"></i> Read the full history of Palakkad
            </a>
        </p>
    </div>
</section>

<!-- Donate Blood Section -->
<section class="home-o-section home-o-section--compact donate-blood-section home-donate mb-0">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h3 class="text-white mb-2">
                    <i class="bi bi-heart-pulse-fill me-2"></i>Donate Blood and Give Life
                </h3>
                <p class="text-white mb-0">Your single donation can save up to three lives. Blood donation is a noble act that helps those in need during emergencies, surgeries, and medical treatments. Join us in making a difference and become a life-saver today.</p>
            </div>
            <div class="col-md-4 text-md-end text-center mt-3 mt-md-0">
                <a href="<?php echo BASE_PATH; ?>/donate-blood.php" class="btn btn-light btn-lg rounded-pill px-4 shadow">
                    <i class="bi bi-heart-fill me-2"></i>Donate Blood
                </a>
            </div>
        </div>
    </div>
</section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

