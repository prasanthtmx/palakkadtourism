<?php
require_once __DIR__ . '/config/config.php';

$pageTitle       = 'Palakkad Fort — Hyder Ali\'s 1766 Granite Citadel, Kerala';
$pageDescription = 'Explore Palakkad Fort, built by Hyder Ali of Mysore in 1766. Learn about its history, granite architecture, British era, and how to visit this ASI-protected monument in Palakkad town, Kerala.';
$pageKeywords    = 'Palakkad Fort, Hyder Ali Fort, Palakkad historical places, Palakkad Fort history, Tipu Sultan Fort, Palakkad monuments';

$fortImg = BASE_PATH . '/assets/images/fort.png';

$breadcrumbs = [
    ['title' => 'Home',    'url' => BASE_PATH . '/'],
    ['title' => 'History', 'url' => BASE_PATH . '/history.php'],
    ['title' => 'Palakkad Fort', 'url' => ''],
];

require_once __DIR__ . '/includes/header.php';
?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "LandmarksOrHistoricalBuildings",
    "name": "Palakkad Fort",
    "alternateName": ["Hyder Ali Fort", "Tipu's Fort"],
    "description": "18th-century granite fort built by Hyder Ali of Mysore in 1766, located in the heart of Palakkad town, Kerala. Protected monument under the Archaeological Survey of India.",
    "url": "<?php echo SITE_URL . BASE_PATH; ?>/fort.php",
    "image": "<?php echo SITE_URL . $fortImg; ?>",
    "address": {
        "@type": "PostalAddress",
        "addressLocality": "Palakkad",
        "addressRegion": "Kerala",
        "addressCountry": "IN"
    },
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": "10.7773",
        "longitude": "76.6558"
    },
    "openingHoursSpecification": {
        "@type": "OpeningHoursSpecification",
        "dayOfWeek": ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
        "opens": "08:00",
        "closes": "18:00"
    },
    "publicAccess": true,
    "isAccessibleForFree": true
}
</script>

<!-- ── Fort hero with real image ── -->
<div class="frt-hero" role="banner">
    <img src="<?php echo $fortImg; ?>"
         alt="Palakkad Fort — granite walls and gateway built by Hyder Ali, 1766"
         class="frt-hero-img"
         width="1200" height="600"
         loading="eager"
         fetchpriority="high"
         decoding="async">
    <div class="frt-hero-overlay" aria-hidden="true"></div>
    <div class="container frt-hero-content">
        <span class="frt-hero-kicker">Palakkad · Kerala · Est. 1766</span>
        <h1 class="frt-hero-title">Palakkad Fort</h1>
        <p class="frt-hero-sub">Built by Hyder Ali of Mysore · Protected by the Archaeological Survey of India</p>
        <div class="frt-hero-chips d-flex flex-wrap gap-2 mt-3">
            <span class="frt-chip"><i class="bi bi-clock me-1"></i>8 AM – 6 PM · Daily</span>
            <span class="frt-chip"><i class="bi bi-ticket-perforated me-1"></i>Free Entry</span>
            <span class="frt-chip"><i class="bi bi-shield-fill me-1"></i>ASI Protected</span>
        </div>
    </div>
</div>

<!-- ── Main content ── -->
<main id="main-content">
<article class="frt-body">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-9 mx-auto">

                <!-- Lead overview -->
                <p class="frt-lead-text">
                    Palakkad Fort — also known as <strong>Hyder Ali Fort</strong> or <strong>Tipu's Fort</strong> — stands in the heart of Palakkad town as one of Kerala's best-preserved 18th-century military structures. Constructed in <strong>1766</strong> from locally quarried granite, it has witnessed Mysore dominion, British colonial rule, and independent India's care, surviving each era with its massive walls largely intact.
                </p>

                <!-- Quick-facts strip -->
                <div class="frt-facts-strip">
                    <div class="frt-fact">
                        <span class="frt-fact-value">1766</span>
                        <span class="frt-fact-label">Year built</span>
                    </div>
                    <div class="frt-fact-divider" aria-hidden="true"></div>
                    <div class="frt-fact">
                        <span class="frt-fact-value">Hyder Ali</span>
                        <span class="frt-fact-label">Builder · Mysore</span>
                    </div>
                    <div class="frt-fact-divider" aria-hidden="true"></div>
                    <div class="frt-fact">
                        <span class="frt-fact-value">Granite</span>
                        <span class="frt-fact-label">Construction</span>
                    </div>
                    <div class="frt-fact-divider" aria-hidden="true"></div>
                    <div class="frt-fact">
                        <span class="frt-fact-value">ASI</span>
                        <span class="frt-fact-label">Protected since</span>
                    </div>
                </div>

                <!-- Section: Historical Significance -->
                <section class="frt-section" id="history">
                    <div class="frt-section-rule" aria-hidden="true"></div>
                    <h2 class="frt-section-title">Historical Significance</h2>
                    <p>In <strong>1757</strong>, when the Samuthiri (Zamorin) of Kozhikode launched an invasion of Palakkad, the local king sought assistance from Hyder Ali of Mysore. With Hyder Ali's support the Zamorin was compelled to retreat. Hyder Ali then conquered all territories previously held by the Zamorin and, to consolidate his hold on the Palakkad Gap — the only major break in the Western Ghats — he constructed the fort in <strong>1766</strong> as a strategic military outpost and administrative centre.</p>
                    <p>The fort served as the nerve centre of Mysore power in Malabar under both Hyder Ali and his son <strong>Tipu Sultan</strong>, controlling the critical trade and military corridor between the Deccan and the Malabar coast.</p>
                </section>

                <!-- Section: Architecture -->
                <section class="frt-section" id="architecture">
                    <div class="frt-section-rule" aria-hidden="true"></div>
                    <h2 class="frt-section-title">Construction &amp; Architecture</h2>
                    <p>The fort is a well-preserved example of 18th-century military architecture. Its key features:</p>
                    <div class="row g-3 mt-1">
                        <div class="col-sm-6">
                            <div class="frt-arch-card">
                                <i class="bi bi-bricks frt-arch-icon"></i>
                                <div>
                                    <div class="frt-arch-label">Granite Walls</div>
                                    <div class="frt-arch-desc">Massive locally quarried stone walls enclosing a large compound</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="frt-arch-card">
                                <i class="bi bi-circle frt-arch-icon"></i>
                                <div>
                                    <div class="frt-arch-label">Circular Bastions</div>
                                    <div class="frt-arch-desc">Rounded corner towers positioned for overlapping fields of fire</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="frt-arch-card">
                                <i class="bi bi-water frt-arch-icon"></i>
                                <div>
                                    <div class="frt-arch-label">Moat System</div>
                                    <div class="frt-arch-desc">A defensive moat once surrounded the perimeter for additional protection</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="frt-arch-card">
                                <i class="bi bi-door-open frt-arch-icon"></i>
                                <div>
                                    <div class="frt-arch-label">Main Gateway</div>
                                    <div class="frt-arch-desc">Imposing entrance gates that mark the historic threshold of the citadel</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Section: British Era -->
                <section class="frt-section" id="british-era">
                    <div class="frt-section-rule" aria-hidden="true"></div>
                    <h2 class="frt-section-title">British Era</h2>
                    <blockquote class="frt-blockquote">
                        The Treaty of Srirangapatna (1792) transferred Tipu Sultan's Malabar possessions to the British East India Company — and with them, Palakkad Fort.
                    </blockquote>
                    <p>The British used the fort as a military garrison and administrative centre. While some modifications were made to suit colonial needs, the core granite structure remained largely untouched — a testament to the quality of Hyder Ali's original construction.</p>
                </section>

                <!-- Section: Present Day -->
                <section class="frt-section" id="present-day">
                    <div class="frt-section-rule" aria-hidden="true"></div>
                    <h2 class="frt-section-title">Present Day</h2>
                    <p>Today the fort is an <strong>ASI-protected monument</strong> open to all visitors free of charge. The compound contains:</p>
                    <ul class="frt-list">
                        <li><i class="bi bi-check2 me-2"></i>The original fort walls and bastions</li>
                        <li><i class="bi bi-check2 me-2"></i>A small museum with artefacts and historical information</li>
                        <li><i class="bi bi-check2 me-2"></i>Well-maintained gardens and open lawns</li>
                        <li><i class="bi bi-check2 me-2"></i>An open-air auditorium for cultural programmes</li>
                    </ul>
                    <p>The fort hosts exhibitions, cultural events, and school visits throughout the year, keeping its heritage alive in the daily life of Palakkad.</p>
                </section>

                <!-- Section: Visitor info -->
                <section class="frt-section" id="visit">
                    <div class="frt-section-rule" aria-hidden="true"></div>
                    <h2 class="frt-section-title">Visitor Information</h2>
                    <div class="row g-3">
                        <div class="col-sm-6 col-md-3">
                            <div class="frt-info-card">
                                <i class="bi bi-clock-fill frt-info-icon"></i>
                                <div class="frt-info-label">Timings</div>
                                <div class="frt-info-value">8:00 AM – 6:00 PM</div>
                                <div class="frt-info-note">Open all days</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="frt-info-card">
                                <i class="bi bi-ticket-fill frt-info-icon"></i>
                                <div class="frt-info-label">Entry Fee</div>
                                <div class="frt-info-value">Free</div>
                                <div class="frt-info-note">No ticket required</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="frt-info-card">
                                <i class="bi bi-sun-fill frt-info-icon"></i>
                                <div class="frt-info-label">Best Time</div>
                                <div class="frt-info-value">Oct – Mar</div>
                                <div class="frt-info-note">Pleasant weather</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <div class="frt-info-card">
                                <i class="bi bi-geo-alt-fill frt-info-icon"></i>
                                <div class="frt-info-label">Location</div>
                                <div class="frt-info-value">Palakkad Town</div>
                                <div class="frt-info-note">Kerala, India</div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Section: Nearby -->
                <section class="frt-section" id="nearby">
                    <div class="frt-section-rule" aria-hidden="true"></div>
                    <h2 class="frt-section-title">Nearby Attractions</h2>
                    <div class="row g-3">
                        <div class="col-sm-6">
                            <a href="<?php echo BASE_PATH; ?>/jain-temple.php" class="frt-nearby-card">
                                <i class="bi bi-building me-2"></i>
                                <span>Jain Temple · Jainimedu</span>
                                <i class="bi bi-arrow-right ms-auto"></i>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="<?php echo BASE_PATH; ?>/history.php#anju-vilakku" class="frt-nearby-card">
                                <i class="bi bi-lamp me-2"></i>
                                <span>Anju Vilakku Monument</span>
                                <i class="bi bi-arrow-right ms-auto"></i>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="<?php echo BASE_PATH; ?>/temples.php" class="frt-nearby-card">
                                <i class="bi bi-buildings me-2"></i>
                                <span>Temples of Palakkad</span>
                                <i class="bi bi-arrow-right ms-auto"></i>
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="<?php echo BASE_PATH; ?>/places.php" class="frt-nearby-card">
                                <i class="bi bi-compass me-2"></i>
                                <span>All Tourist Places</span>
                                <i class="bi bi-arrow-right ms-auto"></i>
                            </a>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
</article>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
