<?php
require_once __DIR__ . '/config/config.php';

$pageTitle = 'How to Reach Palakkad';
$pageDescription = 'Complete guide to reaching Palakkad (Palghat), Kerala — by train, road, air, and local transport. Nearest airports, railway stations, bus routes, and taxi tips for visitors.';
$pageOgImage = 'assets/images/banner.jpg';

$breadcrumbs = [
    ['title' => 'Home',           'url' => BASE_PATH . '/'],
    ['title' => 'Plan your trip', 'url' => BASE_PATH . '/about.php'],
    ['title' => 'How to Reach',   'url' => ''],
];

$articleDatePublished = '2026-05-13';

require_once __DIR__ . '/includes/header.php';
?>

<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "TouristDestination",
    "name": "Palakkad",
    "alternateName": "Palghat",
    "url": "<?php echo htmlspecialchars(rtrim(SITE_URL, '/') . BASE_PATH . '/how-to-reach.php'); ?>",
    "description": "<?php echo htmlspecialchars($pageDescription); ?>",
    "geo": {
        "@type": "GeoCoordinates",
        "latitude": 10.7867,
        "longitude": 76.6548
    },
    "address": {
        "@type": "PostalAddress",
        "addressLocality": "Palakkad",
        "addressRegion": "Kerala",
        "addressCountry": "IN"
    }
}
</script>

<main id="main-content">
<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">

                <!-- Page header -->
                <div class="htr-page-header mb-5">
                    <h1 class="htr-page-title">How to Reach Palakkad</h1>
                    <p class="lead text-muted mb-0">Palakkad (Palghat) sits at the famous <strong>Palakkad Gap</strong> — the only major break in the Western Ghats — making it one of the best-connected towns in Kerala by all modes of transport.</p>
                </div>

                <!-- Quick summary cards -->
                <div class="row g-3 htr-summary-row mb-5">
                    <div class="col-6 col-md-3">
                        <a href="#by-train" class="htr-summary-card text-decoration-none d-flex flex-column align-items-center justify-content-center text-center rounded-4 py-4 px-2 h-100">
                            <span class="htr-summary-icon mb-2"><i class="bi bi-train-front-fill"></i></span>
                            <span class="htr-summary-label fw-semibold">By Train</span>
                            <span class="htr-summary-hint small text-muted mt-1">Palakkad Jn</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="#by-road" class="htr-summary-card text-decoration-none d-flex flex-column align-items-center justify-content-center text-center rounded-4 py-4 px-2 h-100">
                            <span class="htr-summary-icon mb-2"><i class="bi bi-bus-front-fill"></i></span>
                            <span class="htr-summary-label fw-semibold">By Road</span>
                            <span class="htr-summary-hint small text-muted mt-1">NH 544 · KSRTC</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="#by-air" class="htr-summary-card text-decoration-none d-flex flex-column align-items-center justify-content-center text-center rounded-4 py-4 px-2 h-100">
                            <span class="htr-summary-icon mb-2"><i class="bi bi-airplane-fill"></i></span>
                            <span class="htr-summary-label fw-semibold">By Air</span>
                            <span class="htr-summary-hint small text-muted mt-1">Coimbatore · Kochi</span>
                        </a>
                    </div>
                    <div class="col-6 col-md-3">
                        <a href="#local-transport" class="htr-summary-card text-decoration-none d-flex flex-column align-items-center justify-content-center text-center rounded-4 py-4 px-2 h-100">
                            <span class="htr-summary-icon mb-2"><i class="bi bi-taxi-front-fill"></i></span>
                            <span class="htr-summary-label fw-semibold">Local Transport</span>
                            <span class="htr-summary-hint small text-muted mt-1">Auto · Taxi · Bus</span>
                        </a>
                    </div>
                </div>

                <!-- BY TRAIN -->
                <section id="by-train" class="htr-section mb-5">
                    <div class="htr-section-header d-flex align-items-center gap-3 mb-4">
                        <span class="htr-section-icon htr-section-icon--train"><i class="bi bi-train-front-fill"></i></span>
                        <div>
                            <h2 class="htr-section-title mb-0">By Train</h2>
                            <p class="text-muted small mb-0">Easiest and most popular way — direct trains from major cities</p>
                        </div>
                    </div>

                    <p>Rail is the most convenient way to reach Palakkad. The town has two major railway stations on the Shoranur–Coimbatore main line.</p>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="htr-info-card h-100">
                                <div class="htr-info-card-header">
                                    <i class="bi bi-geo-alt-fill me-2"></i>Palakkad Junction (PGT)
                                    <span class="badge bg-success ms-2">Main station</span>
                                </div>
                                <div class="htr-info-card-body">
                                    <p class="small mb-2">The primary station serving Palakkad town. Well connected to:</p>
                                    <ul class="small mb-0">
                                        <li><strong>Chennai</strong> — ~6–8 hrs (multiple daily trains)</li>
                                        <li><strong>Bangalore (Bengaluru)</strong> — ~5–6 hrs</li>
                                        <li><strong>Coimbatore</strong> — ~2 hrs</li>
                                        <li><strong>Kochi (Ernakulam)</strong> — ~3–4 hrs</li>
                                        <li><strong>Thrissur</strong> — ~1.5 hrs</li>
                                        <li><strong>Mumbai (via Konkan)</strong> — ~26–30 hrs</li>
                                        <li><strong>Delhi</strong> — ~40–48 hrs</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="htr-info-card h-100">
                                <div class="htr-info-card-header">
                                    <i class="bi bi-geo-alt-fill me-2"></i>Shoranur Junction (SRR)
                                    <span class="badge bg-secondary ms-2">Junction hub</span>
                                </div>
                                <div class="htr-info-card-body">
                                    <p class="small mb-2">About 35 km from Palakkad town. An important junction connecting:</p>
                                    <ul class="small mb-0">
                                        <li><strong>Thiruvananthapuram</strong> — intercity expresses via coastal line</li>
                                        <li><strong>Calicut (Kozhikode)</strong> — ~2 hrs</li>
                                        <li><strong>Mangalore</strong> — ~4–5 hrs</li>
                                        <li>Many long-distance expresses stop here before reaching Palakkad Jn</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="htr-tip-box">
                        <i class="bi bi-lightbulb-fill me-2 text-warning"></i>
                        <strong>Tip:</strong> Book tickets on <a href="https://www.irctc.co.in" target="_blank" rel="noopener noreferrer">IRCTC.co.in</a>. Popular trains include <em>Intercity Express</em> (Chennai–Palakkad), <em>Parasuram Express</em> (Mangalore–Thiruvananthapuram), and the <em>Ernad Express</em>.
                    </div>
                </section>

                <!-- BY ROAD -->
                <section id="by-road" class="htr-section mb-5">
                    <div class="htr-section-header d-flex align-items-center gap-3 mb-4">
                        <span class="htr-section-icon htr-section-icon--road"><i class="bi bi-bus-front-fill"></i></span>
                        <div>
                            <h2 class="htr-section-title mb-0">By Road</h2>
                            <p class="text-muted small mb-0">Excellent highway connectivity — KSRTC buses and private cabs available</p>
                        </div>
                    </div>

                    <h3 class="htr-sub-heading">National Highway</h3>
                    <p><strong>NH 544</strong> (formerly NH 47, Salem–Kochi corridor) passes directly through Palakkad, connecting the town to Coimbatore (Tamil Nadu) in the east and Thrissur/Kochi in the west. This is the primary road route for visitors from:</p>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="htr-distance-table">
                                <div class="htr-distance-table-header"><i class="bi bi-signpost-2-fill me-2"></i>Road distances to Palakkad</div>
                                <table class="table table-sm mb-0">
                                    <thead><tr><th>From</th><th>Distance</th><th>Drive time</th></tr></thead>
                                    <tbody>
                                        <tr><td>Coimbatore</td><td>~55 km</td><td>~1.5 hrs</td></tr>
                                        <tr><td>Thrissur</td><td>~75 km</td><td>~2 hrs</td></tr>
                                        <tr><td>Kochi</td><td>~145 km</td><td>~3 hrs</td></tr>
                                        <tr><td>Calicut</td><td>~130 km</td><td>~3 hrs</td></tr>
                                        <tr><td>Bangalore</td><td>~360 km</td><td>~6 hrs</td></tr>
                                        <tr><td>Chennai</td><td>~490 km</td><td>~7–8 hrs</td></tr>
                                        <tr><td>Thiruvananthapuram</td><td>~310 km</td><td>~5–6 hrs</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6 d-flex flex-column gap-3">
                            <div class="htr-info-card">
                                <div class="htr-info-card-header"><i class="bi bi-bus-front me-2"></i>KSRTC Buses</div>
                                <div class="htr-info-card-body small">
                                    Kerala KSRTC operates regular services from Palakkad KSRTC bus stand to Thrissur, Kochi, Calicut, Thiruvananthapuram, and Coimbatore. Super-fast and Volvo AC services run on major routes. Book online at <a href="https://www.keralartc.com" target="_blank" rel="noopener noreferrer">keralartc.com</a>.
                                </div>
                            </div>
                            <div class="htr-info-card">
                                <div class="htr-info-card-header"><i class="bi bi-car-front me-2"></i>Private Buses &amp; Cabs</div>
                                <div class="htr-info-card-body small">
                                    Private operators run overnight sleeper buses from Bangalore, Chennai, and Hyderabad. Taxi aggregators (Ola, Uber) are available in Coimbatore and Kochi for the final leg. Self-drive rentals are available at Coimbatore airport.
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="htr-tip-box">
                        <i class="bi bi-lightbulb-fill me-2 text-warning"></i>
                        <strong>Tip:</strong> If driving from Bangalore, use the Mysore–Gudalur–Kalpetta–Mannarkkad route for a scenic ghat drive. If coming from Chennai, NH 544 via Coimbatore is the fastest option.
                    </div>
                </section>

                <!-- BY AIR -->
                <section id="by-air" class="htr-section mb-5">
                    <div class="htr-section-header d-flex align-items-center gap-3 mb-4">
                        <span class="htr-section-icon htr-section-icon--air"><i class="bi bi-airplane-fill"></i></span>
                        <div>
                            <h2 class="htr-section-title mb-0">By Air</h2>
                            <p class="text-muted small mb-0">Palakkad has no airport — use Coimbatore or Kochi</p>
                        </div>
                    </div>

                    <p>Palakkad does not have its own airport. The two most convenient airports are:</p>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="htr-info-card htr-info-card--featured h-100">
                                <div class="htr-info-card-header">
                                    <i class="bi bi-airplane-fill me-2"></i>Coimbatore International Airport (CJB)
                                    <span class="badge bg-success ms-2">Nearest · ~55 km</span>
                                </div>
                                <div class="htr-info-card-body">
                                    <ul class="small mb-2">
                                        <li>Distance from Palakkad: <strong>~55 km</strong> · drive ~1.5 hrs</li>
                                        <li>Direct flights from: Chennai, Bangalore, Mumbai, Hyderabad, Delhi, Dubai, Singapore, Sharjah, Doha</li>
                                        <li>Airlines: Air India, IndiGo, SpiceJet, Air Arabia, Emirates (codeshare)</li>
                                        <li>Taxis and cabs (Ola/Uber) available from airport to Palakkad</li>
                                    </ul>
                                    <a href="https://www.coimbatoreairport.com" target="_blank" rel="noopener noreferrer" class="small">coimbatoreairport.com →</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="htr-info-card h-100">
                                <div class="htr-info-card-header">
                                    <i class="bi bi-airplane-fill me-2"></i>Kochi International Airport (COK)
                                    <span class="badge bg-secondary ms-2">~145 km</span>
                                </div>
                                <div class="htr-info-card-body">
                                    <ul class="small mb-2">
                                        <li>Distance from Palakkad: <strong>~145 km</strong> · drive ~3 hrs</li>
                                        <li>Largest airport in Kerala — wider international connectivity (Gulf, Europe, SE Asia)</li>
                                        <li>Frequent KSRTC buses and private taxis to Palakkad</li>
                                        <li>Train from Ernakulam Jn to Palakkad Jn (~3 hrs) is a comfortable option from Kochi</li>
                                    </ul>
                                    <a href="https://www.cial.aero" target="_blank" rel="noopener noreferrer" class="small">cial.aero →</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="htr-tip-box">
                        <i class="bi bi-lightbulb-fill me-2 text-warning"></i>
                        <strong>Tip:</strong> For international travellers from the Gulf, Kochi (COK) has more direct connections. For domestic travel from South India, Coimbatore (CJB) is the quickest gateway to Palakkad.
                    </div>
                </section>

                <!-- LOCAL TRANSPORT -->
                <section id="local-transport" class="htr-section mb-5">
                    <div class="htr-section-header d-flex align-items-center gap-3 mb-4">
                        <span class="htr-section-icon htr-section-icon--local"><i class="bi bi-taxi-front-fill"></i></span>
                        <div>
                            <h2 class="htr-section-title mb-0">Getting Around Palakkad</h2>
                            <p class="text-muted small mb-0">Local options once you arrive</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-sm-6 col-lg-3">
                            <div class="htr-local-card h-100 text-center">
                                <div class="htr-local-card-icon mb-2"><i class="bi bi-taxi-front-fill"></i></div>
                                <h3 class="htr-local-card-title">Auto-Rickshaw</h3>
                                <p class="small text-muted mb-0">Best for short distances within Palakkad town and nearby areas. Metered or negotiated fare. Available throughout the town.</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="htr-local-card h-100 text-center">
                                <div class="htr-local-card-icon mb-2"><i class="bi bi-car-front-fill"></i></div>
                                <h3 class="htr-local-card-title">Taxi &amp; Cab</h3>
                                <p class="small text-muted mb-0">Ola and Uber operate in Palakkad. Local taxis available from the railway station and bus stand for day trips to Nelliyampathy, Malampuzha, and other places.</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="htr-local-card h-100 text-center">
                                <div class="htr-local-card-icon mb-2"><i class="bi bi-bus-front-fill"></i></div>
                                <h3 class="htr-local-card-title">Local KSRTC Buses</h3>
                                <p class="small text-muted mb-0">Regular services connect Palakkad town to Ottapalam, Mannarkkad, Chittur, Alathur, Shoranur, and most taluks. Very affordable option.</p>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="htr-local-card h-100 text-center">
                                <div class="htr-local-card-icon mb-2"><i class="bi bi-bicycle"></i></div>
                                <h3 class="htr-local-card-title">Two-Wheeler Rental</h3>
                                <p class="small text-muted mb-0">Local rental shops near the railway station offer scooters and motorcycles — ideal for exploring Malampuzha, the fort area, and countryside roads at your own pace.</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- USEFUL CONTACTS -->
                <section class="htr-section mb-4">
                    <div class="htr-section-header d-flex align-items-center gap-3 mb-4">
                        <span class="htr-section-icon htr-section-icon--contacts"><i class="bi bi-telephone-fill"></i></span>
                        <div>
                            <h2 class="htr-section-title mb-0">Useful Contacts</h2>
                            <p class="text-muted small mb-0">Quick numbers for transport enquiries</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6 col-md-4">
                            <div class="htr-contact-card">
                                <div class="htr-contact-label"><i class="bi bi-train-front me-2"></i>Railway Enquiry</div>
                                <div class="htr-contact-value"><a href="tel:139">139</a></div>
                                <div class="htr-contact-sub small text-muted">All India Rail helpline</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="htr-contact-card">
                                <div class="htr-contact-label"><i class="bi bi-bus-front me-2"></i>KSRTC Enquiry</div>
                                <div class="htr-contact-value"><a href="tel:+914912505806">0491-2505806</a></div>
                                <div class="htr-contact-sub small text-muted">Palakkad KSRTC bus stand</div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-4">
                            <div class="htr-contact-card">
                                <div class="htr-contact-label"><i class="bi bi-airplane me-2"></i>Coimbatore Airport</div>
                                <div class="htr-contact-value"><a href="tel:+914222572222">0422-2572222</a></div>
                                <div class="htr-contact-sub small text-muted">CJB airport information</div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- CTA -->
                <div class="htr-cta-row d-flex flex-wrap gap-3 mt-5">
                    <a href="<?php echo BASE_PATH; ?>/places.php" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-compass me-1"></i> Explore places to visit
                    </a>
                    <a href="<?php echo BASE_PATH; ?>/about.php" class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="bi bi-book-half me-1"></i> About Palakkad
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
