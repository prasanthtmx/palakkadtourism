<?php
require_once __DIR__ . '/config/config.php';

$pageTitle       = 'History of Palakkad — From Ancient Dynasties to Modern Kerala';
$pageDescription = 'Explore the rich history of Palakkad, Kerala. Learn about Pallava dynasty, Mysore rule, British era, formation of Palakkad district, and the famous Anju Vilakku (Five-Headed Lamp).';
$pageKeywords    = 'Palakkad history, Palakkad historical facts, Pallava dynasty, Hyder Ali Palakkad, Tipu Sultan, Anju Vilakku, Palakkad district formation';

$breadcrumbs = [
    ['title' => 'Home',    'url' => BASE_PATH . '/'],
    ['title' => 'History', 'url' => ''],
];

require_once __DIR__ . '/includes/header.php';
?>

<!-- ── Historic page header ── -->
<div class="hst-header">
    <div class="hst-header-texture" aria-hidden="true"></div>
    <div class="container position-relative">
        <span class="hst-kicker">Palakkad · Kerala, India</span>
        <h1 class="hst-title">History of Palakkad</h1>
        <p class="hst-lead">From the mist of Pallava invasions to the ink of colonial treaties — the story of a land shaped by dynasties, rivers, and its own indomitable people.</p>
        <!-- Era timeline strip -->
        <div class="hst-era-strip d-flex flex-wrap gap-2 mt-4">
            <a href="#early-history" class="hst-era-chip">2nd–3rd c. · Ancient</a>
            <span class="hst-era-sep" aria-hidden="true">→</span>
            <a href="#mysore" class="hst-era-chip">1757 · Mysore Rule</a>
            <span class="hst-era-sep" aria-hidden="true">→</span>
            <a href="#british" class="hst-era-chip">1792 · British Era</a>
            <span class="hst-era-sep" aria-hidden="true">→</span>
            <a href="#district" class="hst-era-chip">1957 · District formed</a>
            <span class="hst-era-sep" aria-hidden="true">→</span>
            <a href="#anju-vilakku" class="hst-era-chip">1882 · Anju Vilakku</a>
        </div>
    </div>
</div>

<!-- ── Main content ── -->
<main id="main-content">
<article class="hst-body py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 mx-auto">

                <!-- Chapter 1 – Early History -->
                <section class="hst-chapter" id="early-history">
                    <div class="hst-chapter-marker">
                        <span class="hst-chapter-era">2nd – 15th century</span>
                        <div class="hst-chapter-line" aria-hidden="true"></div>
                    </div>
                    <h2 class="hst-chapter-title">Early History</h2>
                    <div class="hst-chapter-body">
                        <p>William Logan, the author of the <em>Malabar Manual</em>, posits that the <strong>Pallava dynasty of Kanchi</strong> potentially invaded Malabar during the 2nd or 3rd century. It is theorised that Palakkad, in modern times, might have been among their principal centres. Numerous indigenous dynasties from South India historically ventured into Malabar. This period saw the ascendancy of the Malabar Perumaks, with various potent regional leaders governing distinct areas.</p>
                        <p>Following the reign of the Perumaks, control of the Malabar region shifted to leaders such as <strong>Valluvakonathiri of Valluvanad</strong>, the King of Kollakode in Vengunadu, and <strong>King Sekharivarma of Palakkad</strong> — each leaving distinct imprints on the cultural and political landscape of the region.</p>
                    </div>
                </section>

                <!-- Chapter 2 – Mysore Invasion & British Rule -->
                <section class="hst-chapter" id="mysore">
                    <div class="hst-chapter-marker">
                        <span class="hst-chapter-era">1757 – 1792</span>
                        <div class="hst-chapter-line" aria-hidden="true"></div>
                    </div>
                    <h2 class="hst-chapter-title">Mysore Invasion &amp; British Rule</h2>
                    <div class="hst-chapter-body">
                        <p>In <strong>1757</strong>, the Samuthiri king of Kozhikode's incursion into Palakkad led the local king to seek assistance from <strong>Hyder Ali of Mysore</strong>. The Samuthiri had to retreat from Malabar due to Hyder Ali's intervention. Subsequently, Hyder Ali conquered all Palakkad territories — including those held by the Samuthiri — bringing the once-independent kingdom of Palakkad under Mysore's dominion, a rule that passed to his son <strong>Tipu Sultan</strong>.</p>

                        <!-- Pull quote -->
                        <blockquote class="hst-blockquote" id="british">
                            <p>"The aftermath of the Third Mysore War saw the Treaty of Srirangapatna in 1792, which transferred the entire Malabar area, previously under Tipu's dominion, to British control — drawing Palakkad into the vast canvas of the Madras Presidency."</p>
                        </blockquote>

                        <p>This led to the annexation of the Malabar region into the <strong>Madras Presidency</strong>, beginning a new chapter of colonial administration that would last until Independence in 1947.</p>
                    </div>
                </section>

                <!-- Chapter 3 – District Formation -->
                <section class="hst-chapter" id="district">
                    <div class="hst-chapter-marker">
                        <span class="hst-chapter-era">1947 – 1969</span>
                        <div class="hst-chapter-line" aria-hidden="true"></div>
                    </div>
                    <h2 class="hst-chapter-title">Formation of Palakkad District</h2>
                    <div class="hst-chapter-body">
                        <p>After India gained independence in <strong>1947</strong> and the formation of United Kerala in <strong>1956</strong>, the linguistically compatible Malabar region, previously under the Madras Presidency, became part of Kerala. On <strong>January 1, 1957</strong>, the Palakkad district was formally established by merging Palakkad, Perinthalmanna, Ponnani, Ottappalam, Alathur, and Chittoor taluks.</p>

                        <!-- Key fact card -->
                        <div class="hst-fact-card">
                            <div class="hst-fact-card-icon"><i class="bi bi-calendar2-event-fill"></i></div>
                            <div>
                                <div class="hst-fact-card-label">District Formation</div>
                                <div class="hst-fact-card-value">1 January 1957</div>
                                <div class="hst-fact-card-note">Merging 6 taluks under the newly formed Kerala state</div>
                            </div>
                        </div>

                        <p>On <strong>June 16, 1969</strong>, when the Malappuram district was carved out, Trithala Farka in the Ponnani taluk was incorporated into Palakkad. Most of the area was added to the Malappuram district, except the Chethalloor section. <strong>Mannarkkad</strong> became a new taluk in Palakkad district by amalgamating 19 villages from Karkidakamkunnu and Chethalloor.</p>
                        <p>Vadakkekkad, Punnayoor, and Punnayoorkulam villages in Trithala Farka were included in Chavakkad taluk (Thrissur district). Paruthur village in the Tirur taluk became part of the Ottapalam taluk. In <strong>2013</strong>, the Ottapalam taluk was divided, with a new taluk having Pattambi as its headquarters.</p>
                    </div>
                </section>

                <!-- Chapter 4 – Anju Vilakku -->
                <section class="hst-chapter hst-chapter--last" id="anju-vilakku">
                    <div class="hst-chapter-marker">
                        <span class="hst-chapter-era">1881 – 1882</span>
                        <div class="hst-chapter-line" aria-hidden="true"></div>
                    </div>
                    <h2 class="hst-chapter-title">Anju Vilakku — The Five-Headed Lamp <span class="hst-malayalam">(അഞ്ചുവിളക്ക്)</span></h2>
                    <div class="hst-chapter-body">
                        <p>The <strong>"Anju Vilakku"</strong> — the Five-Headed Lamp — stands as one of Palakkad's most significant historical and cultural emblems. Erected in <strong>1882</strong>, this five-branched lamp commemorates <strong>Sri Pulikkat S. Ratnavelu Chettiar</strong>, an eminent philanthropist and the pioneering Indian Vice Chairman of the Palakkad Municipality — a role that invited scepticism from the British-dominated council of the era.</p>

                        <div class="hst-fact-card">
                            <div class="hst-fact-card-icon"><i class="bi bi-lamp-fill"></i></div>
                            <div>
                                <div class="hst-fact-card-label">First illuminated</div>
                                <div class="hst-fact-card-value">28 September 1881</div>
                                <div class="hst-fact-card-note">15th anniversary of the Palakkad Municipality · attended by the Collector of Calicut</div>
                            </div>
                        </div>

                        <p>Following the passing of Sri Ratnavelu Chettiar a year later, townsfolk erected a memorial without obtaining approval from the Chairman. This act met opposition from the municipal council, culminating in heated exchanges between collectors. Though V. R. Bradshaw, an officer of British origin, sided with the council, the Chettiar community expressed indignation and resistance. A European-style monument graced with the remarkable five-headed lamp was ultimately erected — the Governor of Madras, <strong>Sir George Stanley</strong>, extended personal recognition to the philanthropist's enduring legacy.</p>

                        <blockquote class="hst-blockquote hst-blockquote--gold">
                            <p>Presently, the Anju Vilakku monument remains a focal point for social and political processions in Palakkad town. It is illuminated on special occasions, and its maintenance is under the purview of the <strong>Vadakkunnatha Temple authorities</strong>.</p>
                        </blockquote>
                    </div>
                </section>

                <!-- Related links -->
                <nav class="hst-related" aria-label="Related pages">
                    <h2 class="hst-related-heading">Explore More</h2>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="<?php echo BASE_PATH; ?>/fort.php"        class="hst-related-btn"><i class="bi bi-shield-fill me-1"></i> Palakkad Fort</a>
                        <a href="<?php echo BASE_PATH; ?>/about.php"       class="hst-related-btn"><i class="bi bi-book-half me-1"></i> About Palakkad</a>
                        <a href="<?php echo BASE_PATH; ?>/art-culture.php" class="hst-related-btn"><i class="bi bi-palette me-1"></i> Art &amp; Culture</a>
                        <a href="<?php echo BASE_PATH; ?>/places.php"      class="hst-related-btn"><i class="bi bi-compass me-1"></i> Places to Visit</a>
                    </div>
                </nav>

            </div>
        </div>
    </div>
</article>
</main>

<!-- ── Journey Through Time timeline band ── -->
<section class="hst-timeline-band" aria-labelledby="hst-timeline-heading">
    <div class="container">
        <header class="hst-timeline-band-header text-center">
            <span class="hst-timeline-eyebrow">At a glance</span>
            <h2 id="hst-timeline-heading" class="hst-timeline-band-title">
                <i class="bi bi-clock-history me-2" aria-hidden="true"></i>Journey Through Time
            </h2>
            <p class="hst-timeline-band-lead">From ancient Tamilakam and the Palakkad chieftains through Mysore, the British era, and state formation to today's Kerala.</p>
        </header>

        <div class="hst-timeline">

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
                    <div class="hst-tl-desc">Treaty of Seringapatam · Palakkad joins Malabar District under Madras Presidency</div>
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
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
