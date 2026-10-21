<?php
require_once __DIR__ . '/config/config.php';

$pageTitle       = 'Malampuzha Dam & Gardens, Palakkad — History, Engineering & Tourism';
$pageDescription = 'Malampuzha Dam, Palakkad: foundation 1949, inaugurated 1955 by Jawaharlal Nehru. History, dam specifications, Rice Bowl agriculture, Malampuzha Garden, Fantasy Park, Yakshi sculpture and vintage photographs.';
$pageKeywords    = 'Malampuzha Dam, Malampuzha Garden, Palakkad dam history, Kerala tourism, Rice Bowl of Kerala, Fantasy Park Malampuzha, Yakshi statue Kanayi Kunhiraman, Bharathappuzha irrigation';

$breadcrumbs = [
    ['title' => 'Home',          'url' => BASE_PATH . '/'],
    ['title' => 'Places',        'url' => BASE_PATH . '/places.php'],
    ['title' => 'Malampuzha Dam','url' => ''],
];

$canonicalPath = '/dam-malampuzha.php';
$pageUrl       = SITE_URL . BASE_PATH . $canonicalPath;

$structuredData = [
    '@context' => 'https://schema.org',
    '@type'    => 'TouristAttraction',
    'name'     => 'Malampuzha Dam',
    'description' => 'Multi-purpose dam and reservoir near Palakkad town, Kerala, with Malampuzha Garden, boating, and major irrigation role in the district known as the Rice Bowl of Kerala.',
    'url'      => $pageUrl,
    'address'  => [
        '@type'           => 'PostalAddress',
        'addressLocality' => 'Malampuzha, Palakkad',
        'addressRegion'   => 'Kerala',
        'addressCountry'  => 'IN',
    ],
    'geo' => [
        '@type'     => 'GeoCoordinates',
        'latitude'  => '10.8257',
        'longitude' => '76.6984',
    ],
];

$damPhotos = [
    [
        'file'    => 'malampuzhadam.png',
        'caption' => 'The foundation stone of Malampuzha Dam — laid in March 1949 by Minister for Public Works K. Bhaktavatsalam, marking the beginning of one of Kerala\'s most ambitious irrigation projects.',
    ],
    [
        'file'    => '20108270_1359808394055376_6518543373145703285_n.jpg',
        'caption' => 'An early view of the Malampuzha Dam — the engineering milestone that transformed Palakkad\'s water landscape.',
    ],
    [
        'file'    => '20140117_1359808367388712_3333219153017955115_n.jpg',
        'caption' => 'The Malampuzha reservoir in its early years, built by workers drawn from six states of India.',
    ],
    [
        'file'    => '20155649_1359808340722048_1428106743341389566_n.jpg',
        'caption' => 'A timeless frame of the dam — inaugurated by Prime Minister Jawaharlal Nehru on 25 November 1955.',
    ],
    [
        'file'    => 'IMG-20190701-WA0006.jpg',
        'caption' => 'Malampuzha Dam, the primary source of drinking water and irrigation for Palakkad city and its suburbs.',
    ],
    [
        'file'    => 'IMG-20190701-WA0007.jpg',
        'caption' => 'The Malampuzha Gardens — a tourism landmark that flourished alongside the dam from the mid-20th century.',
    ],
    [
        'file'    => 'IMG-20190701-WA0009.jpg',
        'caption' => 'Panoramic view of the Malampuzha reservoir — the lifeline of Palakkad and the Kanjikode Industrial Belt.',
    ],
];

require_once __DIR__ . '/includes/header.php';
?>
<script type="application/ld+json"><?php echo json_encode($structuredData, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?></script>

<!-- ── Page header ── -->
<div class="mdm-header">
    <div class="container">
        <div class="mdm-header-body">
            <div>
                <span class="mdm-kicker">Palakkad · Kerala · Est. 1955</span>
                <h1 class="mdm-title">Malampuzha Dam &amp; Gardens</h1>
                <p class="mdm-lead">One of Kerala's most iconic engineering landmarks — a multi-purpose dam that irrigates the Rice Bowl of Kerala, feeds an industrial belt, and draws thousands of visitors to its famous gardens every year.</p>
            </div>
            <div class="mdm-meta-pills">
                <span class="mdm-pill"><i class="bi bi-calendar3"></i> Founded 1949</span>
                <span class="mdm-pill"><i class="bi bi-check-circle"></i> Inaugurated 1955</span>
                <span class="mdm-pill"><i class="bi bi-rulers"></i> 2,069 m length</span>
                <span class="mdm-pill"><i class="bi bi-droplet-fill"></i> 236 million m³</span>
            </div>
        </div>
    </div>
</div>

<main id="main-content">
<div class="mdm-body">
    <div class="container">
        <div class="mdm-layout">

            <!-- ── Main content column ── -->
            <article class="mdm-main">

                <section class="mdm-section" id="history">
                    <h2 class="mdm-section-title">History &amp; Construction</h2>
                    <p>The idea of building a dam at Malampuzha was first proposed in <strong>1914</strong> by the <strong>Madras Government</strong>, when Palakkad was part of the <strong>Madras Presidency</strong>. The project took several decades to gain momentum, but once it did, it moved rapidly.</p>
                    <p>The <strong>foundation stone was laid in March 1949</strong> by the then Minister for Public Works, <strong>K. Bhaktavatsalam</strong>. Although the foundation was laid under Bhaktavatsalam, the project was completed during the tenure of <strong>Chief Minister K. Kamaraj</strong>. The dam was <strong>officially inaugurated on 25 November 1955</strong> by Prime Minister <strong>Pandit Jawaharlal Nehru</strong>.</p>
                    <p>Construction was a remarkable achievement — completed in just <strong>six years</strong> by a workforce drawn from six states: Kerala, Tamil Nadu, Andhra Pradesh, Karnataka, Odisha, and West Bengal. The project was led by a distinguished team of planners, architects, designers, supervisors, and engineers from across India.</p>
                </section>

                <section class="mdm-section" id="areas">
                    <h2 class="mdm-section-title">Localities Before the Reservoir</h2>
                    <p>Before impoundment, the area comprised many small hamlets and localities. After the reservoir was filled, the wider area became known collectively as <strong>Malampuzha</strong>. The earlier settlements included:</p>
                    <div class="mdm-two-col-list">
                        <ul>
                            <li>Punppara</li>
                            <li>Chovankadu</li>
                            <li>Iduppadi</li>
                            <li>Vadakkampadam</li>
                            <li>Thanjikapallam</li>
                            <li>Karakkad</li>
                            <li>Thooppallam</li>
                        </ul>
                        <ul>
                            <li>Varani</li>
                            <li>Akkarakad</li>
                            <li>Kadakkamkunnam</li>
                            <li>Anakuzhikkad</li>
                            <li>Thamburattipotta</li>
                            <li>Anamukkar</li>
                            <li>Pandipotta</li>
                            <li>Thekkumpadam</li>
                            <li>Koshavan Idukku</li>
                        </ul>
                    </div>
                </section>

                <section class="mdm-section" id="purpose">
                    <h2 class="mdm-section-title">Purpose of the Project</h2>
                    <p>Malampuzha was designed as a <strong>multi-purpose scheme</strong> from the outset. Its primary functions are:</p>
                    <ul class="mdm-purpose-list">
                        <li><strong>Irrigation</strong> — the backbone of farming across the Palakkad plains</li>
                        <li><strong>Drinking water supply</strong> — for Palakkad city and surrounding areas</li>
                        <li><strong>Industrial use</strong> — particularly for the Kanjikode Industrial Belt</li>
                        <li><strong>Power generation</strong></li>
                        <li><strong>Fisheries</strong></li>
                        <li><strong>Water transport</strong> — where operational policies permit</li>
                    </ul>
                </section>

                <section class="mdm-section" id="agriculture">
                    <h2 class="mdm-section-title">Agriculture — The Rice Bowl of Kerala</h2>
                    <p>Malampuzha Dam has been central to establishing Palakkad as Kerala's <strong>"Rice Bowl"</strong>. Approximately <strong>50,000 hectares</strong> of farmland are irrigated from the reservoir across <strong>two crop seasons</strong> each year.</p>
                    <p>Of Kerala's total <strong>3,10,521 hectares</strong> of paddy land, <strong>Palakkad alone accounts for 1,15,910 hectares — 37.33%</strong> of the entire state. This proportion reflects how vital reliable irrigation from projects like Malampuzha is to both the district and the state's food security.</p>
                </section>

                <section class="mdm-section" id="jubilee">
                    <h2 class="mdm-section-title">Golden Jubilee (2005)</h2>
                    <p>In <strong>October 2005</strong>, the dam celebrated its <strong>Golden Jubilee</strong>. The event was launched in conjunction with <strong>Onam</strong> and <strong>Tourism Week</strong>, and was organised by a committee led by the then District Collector, <strong>K. Ajayakumar</strong>.</p>
                </section>

                <section class="mdm-section" id="tourism">
                    <h2 class="mdm-section-title">Gardens &amp; Tourist Attractions</h2>
                    <p>Alongside the dam, the <strong>Malampuzha Garden</strong> is maintained by the <strong>Tourism Department</strong> and is widely referred to as the <strong>"Garden of Kerala"</strong> or the <strong>"Vrindavan of Kerala"</strong>. The landscaped grounds, Western Ghats backdrop, flower beds, and ponds make it one of Kerala's most popular day-trip destinations.</p>
                    <div class="mdm-attraction-grid">
                        <div class="mdm-attraction">
                            <div class="mdm-attraction-icon"><i class="bi bi-flower1"></i></div>
                            <div class="mdm-attraction-body">
                                <div class="mdm-attraction-name">Malampuzha Garden</div>
                                <div class="mdm-attraction-desc">Landscaped gardens, lawns, fountains and ponds maintained by the Tourism Department.</div>
                            </div>
                        </div>
                        <div class="mdm-attraction">
                            <div class="mdm-attraction-icon"><i class="bi bi-dribbble"></i></div>
                            <div class="mdm-attraction-body">
                                <div class="mdm-attraction-name">Fantasy Park</div>
                                <div class="mdm-attraction-desc">Kerala's first water theme park, adjacent to the garden complex.</div>
                            </div>
                        </div>
                        <div class="mdm-attraction">
                            <div class="mdm-attraction-icon"><i class="bi bi-person-raised-hand"></i></div>
                            <div class="mdm-attraction-body">
                                <div class="mdm-attraction-name">Yakshi Sculpture</div>
                                <div class="mdm-attraction-desc">The celebrated work of sculptor <strong>Kanayi Kunhiraman</strong> — an iconic landmark of the garden.</div>
                            </div>
                        </div>
                        <div class="mdm-attraction">
                            <div class="mdm-attraction-icon"><i class="bi bi-tsunami"></i></div>
                            <div class="mdm-attraction-body">
                                <div class="mdm-attraction-name">Boating</div>
                                <div class="mdm-attraction-desc">Boating on the reservoir, subject to local regulations and seasonal availability.</div>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="mdm-section" id="natural-setting">
                    <h2 class="mdm-section-title">Natural Setting</h2>
                    <p>The dam sits amid <strong>forest-clad hills</strong> with rivers feeding into the reservoir from the Western Ghats. Visitors enjoy green landscapes, diverse flowering plants, and quiet rest areas — a combination that keeps Malampuzha consistently among the <strong>most visited destinations in Kerala</strong>.</p>
                </section>

                <!-- Vintage Photographs -->
                <section class="mdm-section" id="vintage-photos" aria-labelledby="mdm-vp-heading">
                    <h2 class="mdm-section-title" id="mdm-vp-heading">
                        <i class="bi bi-camera me-2"></i>Vintage Photographs
                    </h2>
                    <p class="mdm-section-sub">Rare heritage images from the 1949–1955 construction era and early years of the dam</p>

                    <div class="mdm-photo-grid">
                        <?php foreach ($damPhotos as $idx => $p):
                            $src = BASE_PATH . '/assets/gallery/' . rawurlencode($p['file']);
                        ?>
                        <figure class="mdm-photo-item <?php echo $idx === 0 ? 'mdm-photo-item--wide' : ''; ?>">
                            <div class="mdm-photo-wrap">
                                <img src="<?php echo $src; ?>"
                                     alt="Malampuzha Dam vintage photograph — Palakkad heritage"
                                     class="mdm-photo-img"
                                     loading="lazy">
                                <span class="mdm-photo-stamp">c. 1949–1955</span>
                            </div>
                            <figcaption class="mdm-photo-caption"><?php echo htmlspecialchars($p['caption']); ?></figcaption>
                        </figure>
                        <?php endforeach; ?>
                    </div>
                </section>

                <!-- Related -->
                <nav class="mdm-related" aria-label="Related pages">
                    <span class="mdm-related-label">Related</span>
                    <a href="<?php echo BASE_PATH; ?>/places.php">Places to Visit in Palakkad</a>
                    <a href="<?php echo BASE_PATH; ?>/rivers.php">Rivers of Palakkad</a>
                    <a href="<?php echo BASE_PATH; ?>/how-to-reach.php">How to Reach Palakkad</a>
                </nav>

            </article>

            <!-- ── Sidebar ── -->
            <aside class="mdm-sidebar" aria-label="Quick facts">

                <div class="mdm-fact-card">
                    <div class="mdm-fact-card-title">Quick Facts</div>
                    <dl class="mdm-fact-list">
                        <dt>Type</dt>
                        <dd>Gravity dam (multi-purpose)</dd>
                        <dt>River</dt>
                        <dd>Malampuzha (tributary of Bharathapuzha)</dd>
                        <dt>Location</dt>
                        <dd>Malampuzha, Palakkad, Kerala</dd>
                        <dt>Foundation laid</dt>
                        <dd>March 1949 — K. Bhaktavatsalam</dd>
                        <dt>Inaugurated</dt>
                        <dd>25 November 1955 — PM Jawaharlal Nehru</dd>
                        <dt>Completed under</dt>
                        <dd>CM K. Kamaraj</dd>
                        <dt>Construction period</dt>
                        <dd>6 years</dd>
                        <dt>Total dam length</dt>
                        <dd>2,069 m</dd>
                        <dt>Man-made portion</dt>
                        <dd>1,849 m</dd>
                        <dt>Natural portion</dt>
                        <dd>220 m</dd>
                        <dt>Catchment area</dt>
                        <dd>147.39 km²</dd>
                        <dt>Storage capacity</dt>
                        <dd>236.69 million m³</dd>
                        <dt>Maximum water level</dt>
                        <dd>115.06 m</dd>
                        <dt>Area irrigated</dt>
                        <dd>~50,000 hectares</dd>
                        <dt>Golden Jubilee</dt>
                        <dd>October 2005</dd>
                    </dl>
                </div>

                <div class="mdm-fact-card mt-3">
                    <div class="mdm-fact-card-title">On this page</div>
                    <ul class="mdm-toc">
                        <li><a href="#history">History &amp; Construction</a></li>
                        <li><a href="#areas">Localities Before the Reservoir</a></li>
                        <li><a href="#purpose">Purpose of the Project</a></li>
                        <li><a href="#agriculture">Agriculture &amp; Rice Bowl</a></li>
                        <li><a href="#jubilee">Golden Jubilee</a></li>
                        <li><a href="#tourism">Gardens &amp; Tourism</a></li>
                        <li><a href="#natural-setting">Natural Setting</a></li>
                        <li><a href="#vintage-photos">Vintage Photographs</a></li>
                    </ul>
                </div>

                <div class="mdm-fact-card mt-3">
                    <div class="mdm-fact-card-title">Location</div>
                    <p class="mdm-map-note">Malampuzha, Palakkad — 10 km from Palakkad town on the NH 544 corridor.</p>
                    <a href="https://maps.google.com/?q=Malampuzha+Dam+Palakkad" target="_blank" rel="noopener" class="mdm-map-link">
                        <i class="bi bi-map me-1"></i> Open in Google Maps
                    </a>
                </div>

            </aside>

        </div>
    </div>
</div>
</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
