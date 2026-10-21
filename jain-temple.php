<?php
require_once __DIR__ . '/config/config.php';

$pageTitle       = 'Jain Temple, Jainimedu Palakkad';
$pageDescription = 'Shri Digambar Jain Lai Mandir in Jainimedu, Palakkad — 15th-century Digambar temple, address, and how to reach.';
$pageOgImage     = 'assets/images/jain-temple-shri-digambar-lal-mandir-palakkad.jpg';
$pageKeywords    = 'Shri Digambar Jain Lai Mandir, Digambar Jain Lal Mandir Palakkad, Jainimedu Jain temple, Chunnamputhara Jain temple, Vadakkanthara Palakkad, Jain temple Palakkad how to reach, Palakkad 678012';

$breadcrumbs = [
    ['title' => 'Home',            'url' => BASE_PATH . '/'],
    ['title' => 'Religious Places','url' => BASE_PATH . '/religious-places.php'],
    ['title' => 'Jain Temple',     'url' => ''],
];

$pageUrl   = SITE_URL . BASE_PATH . '/jain-temple.php';
$imagePath = BASE_PATH . '/assets/images/jain-temple-shri-digambar-lal-mandir-palakkad.jpg';
$imageUrl  = SITE_URL . $imagePath;

$structuredData = [
    '@context'      => 'https://schema.org',
    '@type'         => 'PlaceOfWorship',
    'name'          => 'Shri Digambar Jain Lai Mandir',
    'alternateName' => ['Shri Digambar Jain Lal Mandir', 'Jainimedu Jain temple', 'Jain temple Palakkad'],
    'description'   => '15th-century Digambar Jain temple in Jainimedu, Palakkad, Kerala, dedicated to Chandraprabha. Heritage site near the Kalpathy River and Chunnamputhara area.',
    'url'           => $pageUrl,
    'image'         => $imageUrl,
    'address'       => [
        '@type'          => 'PostalAddress',
        'streetAddress'  => 'Jainimedu, Vadakkanthara, near Chunnamputhara / Kalpathy River',
        'addressLocality'=> 'Palakkad',
        'postalCode'     => '678012',
        'addressRegion'  => 'Kerala',
        'addressCountry' => 'IN',
    ],
    'geo' => [
        '@type'     => 'GeoCoordinates',
        'latitude'  => '10.785667',
        'longitude' => '76.642556',
    ],
];

require_once __DIR__ . '/includes/header.php';
?>
<script type="application/ld+json"><?php echo json_encode($structuredData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>

<!-- ── Jain flag 5-stripe bar ── -->
<div class="jain-flag-bar" aria-hidden="true">
    <span class="jain-flag-stripe jain-stripe--white"></span>
    <span class="jain-flag-stripe jain-stripe--red"></span>
    <span class="jain-flag-stripe jain-stripe--gold"></span>
    <span class="jain-flag-stripe jain-stripe--green"></span>
    <span class="jain-flag-stripe jain-stripe--navy"></span>
</div>

<!-- ── Page header ── -->
<div class="jain-page-header">
    <div class="container">
        <span class="jain-kicker">Palakkad · Kerala · Jainimedu</span>
        <h1 class="jain-title">Shri Digambar Jain Lai Mandir</h1>
        <p class="jain-lead">A 15th-century Digambar Jain heritage shrine on the banks of the Kalpathy River — one of the rare surviving Jain temples in Kerala.</p>
        <div class="jain-meta-pills d-flex flex-wrap gap-2 mt-3">
            <span class="jain-pill"><i class="bi bi-geo-alt-fill me-1"></i>Jainimedu, Vadakkanthara</span>
            <span class="jain-pill"><i class="bi bi-pin-map me-1"></i>PIN 678012</span>
            <span class="jain-pill"><i class="bi bi-calendar3 me-1"></i>Est. 15th century</span>
        </div>
    </div>
</div>

<!-- ── Main content ── -->
<main id="main-content">
<article class="jain-article py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">

                <!-- Intro -->
                <p class="jain-intro-text">
                    This <strong>Digambar Jain</strong> heritage mandir in <strong>Palakkad, Kerala</strong> is widely known as the <strong>Jainimedu Jain temple</strong> and is often listed in directories as <strong>Shri Digambar Jain Lal Mandir</strong>. The 15th-century shrine, dedicated to <strong>Chandraprabha</strong>, is a rare surviving Jain site in the state and is associated with the Chunnamputhara / Kalpathy bank area and the story of refuge under the Palakkad rajas.
                </p>

                <!-- Photo -->
                <figure class="jain-figure">
                    <div class="jain-figure-img-wrap">
                        <img
                            src="<?php echo htmlspecialchars($imagePath); ?>"
                            width="2393"
                            height="3354"
                            alt="Shri Digambar Jain Lai Mandir — Jainimedu Jain temple, Palakkad: historic stone temple and gateway"
                            class="jain-photo"
                            loading="eager"
                            fetchpriority="high"
                            decoding="async">
                    </div>
                    <figcaption class="jain-figcaption">
                        <i class="bi bi-camera me-1 opacity-50"></i>
                        Photo (Jainimedu, Palakkad): Shafeeq basheer ·
                        <a href="https://commons.wikimedia.org/wiki/File:Jain_temple_palakkad.jpg" rel="noopener noreferrer">Wikimedia Commons</a>
                        · <a href="https://creativecommons.org/licenses/by-sa/4.0/" rel="noopener noreferrer">CC BY-SA 4.0</a>
                    </figcaption>
                </figure>

                <!-- Location & address -->
                <section class="jain-section" id="location">
                    <div class="jain-section-header">
                        <span class="jain-section-dot jain-dot--gold"></span>
                        <h2 class="jain-section-title">Location &amp; Address</h2>
                    </div>
                    <p>The mandir stands in <strong>Jainimedu</strong>, a Palakkad suburb on the <strong>south bank of the Kalpathy River</strong>, just beyond the <strong>Chunnamputhara</strong> area — roughly <strong>3 km from the centre of Palakkad town</strong> and easy to combine with a visit to <a href="<?php echo BASE_PATH; ?>/municipality-palakkad.php" class="jain-link">Palakkad municipality</a> or <a href="<?php echo BASE_PATH; ?>/fort.php" class="jain-link">Palakkad Fort</a>.</p>

                    <div class="jain-info-card">
                        <div class="jain-info-card-stripe" aria-hidden="true">
                            <span class="jain-stripe--white"></span>
                            <span class="jain-stripe--red"></span>
                            <span class="jain-stripe--gold"></span>
                            <span class="jain-stripe--green"></span>
                            <span class="jain-stripe--navy"></span>
                        </div>
                        <dl class="jain-dl mb-0">
                            <div class="jain-dl-row">
                                <dt>Temple name</dt>
                                <dd>Shri Digambar Jain Lai Mandir / Shri Digambar Jain Lal Mandir</dd>
                            </div>
                            <div class="jain-dl-row">
                                <dt>Also known as</dt>
                                <dd>Jainimedu Jain temple · Jain temple Palakkad</dd>
                            </div>
                            <div class="jain-dl-row">
                                <dt>Area</dt>
                                <dd>Jainimedu, <strong>Vadakkanthara</strong>, Palakkad</dd>
                            </div>
                            <div class="jain-dl-row">
                                <dt>District / State</dt>
                                <dd>Palakkad, Kerala, India</dd>
                            </div>
                            <div class="jain-dl-row">
                                <dt>PIN code</dt>
                                <dd><strong>678012</strong></dd>
                            </div>
                            <div class="jain-dl-row jain-dl-row--last">
                                <dt>Coordinates</dt>
                                <dd>10.785667°N, 76.642556°E</dd>
                            </div>
                        </dl>
                    </div>
                </section>

                <!-- How to reach -->
                <section class="jain-section" id="how-to-reach">
                    <div class="jain-section-header">
                        <span class="jain-section-dot jain-dot--green"></span>
                        <h2 class="jain-section-title">How to Reach</h2>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="jain-reach-card">
                                <div class="jain-reach-icon jain-reach-icon--red"><i class="bi bi-car-front-fill"></i></div>
                                <h3 class="jain-reach-title">By Road &amp; Taxi</h3>
                                <p class="jain-reach-text">Follow local roads toward <strong>Jainimedu / Vadakkanthara</strong>. Auto-rickshaws and taxis are simplest. Search <strong>"Jainimedu Jain temple"</strong> or PIN <strong>678012</strong> in maps.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="jain-reach-card">
                                <div class="jain-reach-icon jain-reach-icon--navy"><i class="bi bi-train-front-fill"></i></div>
                                <h3 class="jain-reach-title">By Train</h3>
                                <p class="jain-reach-text">Nearest railheads: <strong>Palakkad Town</strong> or <strong>Palakkad Junction</strong>. Take an auto or taxi to Jainimedu — a short ride within the urban area.</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="jain-reach-card">
                                <div class="jain-reach-icon jain-reach-icon--gold"><i class="bi bi-bus-front-fill"></i></div>
                                <h3 class="jain-reach-title">By Bus</h3>
                                <p class="jain-reach-text">KSRTC and private buses to Palakkad town, then local auto toward <strong>Vadakkanthara / Jainimedu</strong> as per current schedules.</p>
                            </div>
                        </div>
                    </div>

                    <div class="jain-map-tip mt-3">
                        <i class="bi bi-map me-2"></i>
                        Open on
                        <a href="https://www.openstreetmap.org/?mlat=10.785667&mlon=76.642556#map=17/10.785667/76.642556" rel="noopener noreferrer" class="jain-link">OpenStreetMap</a>
                        or search <em>"Jainimedu Jain temple"</em> in Google Maps.
                    </div>
                </section>

                <!-- Heritage -->
                <section class="jain-section" id="heritage">
                    <div class="jain-section-header">
                        <span class="jain-section-dot jain-dot--navy"></span>
                        <h2 class="jain-section-title">Heritage &amp; Significance</h2>
                    </div>
                    <p>This granite temple is counted among the few places in Kerala where Jain heritage survives visibly. Tradition links it to merchant patronage and renewal efforts into the present, including major renovation work in recent years. Alongside Palakkad's Hindu temples, churches, and mosques, it reflects the district's diverse and harmonious religious history.</p>
                    <div class="jain-visitor-note">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <span><strong>Visitor note:</strong> Dress and behave respectfully as in any place of worship. Major occasions include <strong>Mahavir Jayanti</strong> — confirm timings with the managing trust before a special visit.</span>
                    </div>
                </section>

                <!-- Related places -->
                <nav class="jain-related" aria-label="Related religious places">
                    <h2 class="jain-related-heading">More Religious Places in Palakkad</h2>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?php echo BASE_PATH; ?>/temples.php" class="jain-related-btn jain-related-btn--saffron">
                            <i class="bi bi-building me-1"></i> Hindu Temples
                        </a>
                        <a href="<?php echo BASE_PATH; ?>/churches.php" class="jain-related-btn jain-related-btn--rose">
                            <i class="bi bi-heart me-1"></i> Churches
                        </a>
                        <a href="<?php echo BASE_PATH; ?>/mosques.php" class="jain-related-btn jain-related-btn--green">
                            <i class="bi bi-moon-stars me-1"></i> Mosques
                        </a>
                    </div>
                </nav>

            </div>
        </div>
    </div>
</article>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
