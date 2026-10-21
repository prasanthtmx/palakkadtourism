<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = 'Palakkad District Assembly Constituencies 2026';
$pageDescription = 'Palakkad district constituency details for the Kerala Legislative Assembly Election 2026: 12 ACs, candidates, turnout, key battles, and Lok Sabha mapping (Palakkad & Alathur).';
$pageKeywords = 'Palakkad assembly election 2026, Palakkad constituencies, Kerala election Palakkad, Thrithala, Palakkad AC, Malampuzha, Alathur';

$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'Palakkad Constituencies 2026', 'url' => ''],
];

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5 mt-5 election-2026-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-11 mx-auto">
                <p class="text-muted small mb-2 text-uppercase fw-semibold letter-spacing-wide">Palakkad district — constituency details</p>
                <h1 class="display-5 fw-bold mb-3">Kerala Legislative Assembly Election 2026</h1>
                <p class="lead text-secondary mb-4">
                    12 constituencies &nbsp;|&nbsp; 2 Lok Sabha seats (Palakkad &amp; Alathur) &nbsp;|&nbsp; 3 SC reserved &nbsp;|&nbsp; Polled: 9 Apr 2026 &nbsp;|&nbsp; Results: 4 May 2026
                </p>

                <div class="alert alert-secondary" role="alert">
                    <i class="bi bi-info-circle"></i> <strong>Note:</strong> ★ marks key battles to watch. <strong>SC</strong> = Scheduled Caste reserved constituency. Candidate and turnout figures reflect publicly reported election data and media summaries.
                </div>

                <?php displayAdSense('content-top', 'max-width: 728px;'); ?>

                <h2 class="h3 mt-5 mb-3">1. District overview</h2>
                <p>Palakkad district has <strong>12 assembly constituencies</strong> (numbered 49 to 60) spread across two Lok Sabha seats — <strong>Palakkad</strong> and <strong>Alathur</strong>. Three constituencies (<strong>Kongad</strong>, <strong>Tarur</strong>, and <strong>Alathur</strong>) are reserved for Scheduled Castes. In the <strong>2021</strong> elections, the LDF dominated with <strong>10 seats</strong> while the UDF won <strong>2 seats</strong> (Mannarkad and Palakkad).</p>
                <p>Palakkad is considered a <strong>swing district</strong> in Kerala. The <strong>2024 Palakkad bypoll</strong>, where Congress won with a margin of <strong>18,840 votes</strong> in a three-way contest, set the tone for the 2026 battle. The district recorded the <strong>2nd highest voter turnout</strong> in Kerala at <strong>80.36%</strong>, behind only Kozhikode (81.35%). <strong>Chittur</strong> constituency had the highest turnout at <strong>84.63%</strong>.</p>

                <h3 class="h5 mt-4 mb-2">2021 vs 2026 comparison</h3>
                <ul>
                    <li><strong>2021 result:</strong> LDF 10 seats, UDF 2 seats, NDA 0 seats.</li>
                    <li><strong>2026 turnout:</strong> 80.36% (up roughly 6 percentage points from 2021’s ~74%).</li>
                    <li><strong>Survey prediction (C-Voter):</strong> LDF likely to retain <strong>8–10 of 12</strong> seats in Palakkad district.</li>
                </ul>

                <h2 class="h3 mt-5 mb-3">2. Constituency-wise candidates</h2>
                <p class="text-muted small mb-3">★ = Key battle to watch &nbsp;|&nbsp; SC = Scheduled Caste reserved constituency</p>

                <div class="table-responsive mt-3">
                    <table class="table table-striped table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col">Constituency</th>
                                <th scope="col">LDF candidate</th>
                                <th scope="col">UDF candidate</th>
                                <th scope="col">NDA candidate</th>
                                <th scope="col" class="text-center">Turnout</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">49</td>
                                <td>Thrithala ★</td>
                                <td>M. B. Rajesh (CPI(M))</td>
                                <td>V. T. Balram (INC)</td>
                                <td>V. Unnikrishnan Master (BJP)</td>
                                <td class="text-center">79.45%</td>
                            </tr>
                            <tr>
                                <td class="text-center">50</td>
                                <td>Pattambi</td>
                                <td>Muhammed Muhsin (CPI)</td>
                                <td>T. P. Shaji (INC)</td>
                                <td>P. Manoj (BJP)</td>
                                <td class="text-center">78.54%</td>
                            </tr>
                            <tr>
                                <td class="text-center">51</td>
                                <td>Shornur</td>
                                <td>P. Mammikutty (CPI(M))</td>
                                <td>P. Harigovindan Master (INC)</td>
                                <td>Sanku T. Das (BJP)</td>
                                <td class="text-center">78.87%</td>
                            </tr>
                            <tr>
                                <td class="text-center">52</td>
                                <td>Ottapalam ★</td>
                                <td>K. Premkumar (CPI(M))</td>
                                <td>P. K. Sasi (INC)</td>
                                <td>Major Ravi (BJP)</td>
                                <td class="text-center">79.12%</td>
                            </tr>
                            <tr>
                                <td class="text-center">53</td>
                                <td>Kongad (SC)</td>
                                <td>K. Shanthakumari (CPI(M))</td>
                                <td>K. A. Thulasi (INC)</td>
                                <td class="text-center">—</td>
                                <td class="text-center">80.16%</td>
                            </tr>
                            <tr>
                                <td class="text-center">54</td>
                                <td>Mannarkad</td>
                                <td>E. Prabhakaran (CPI(M))</td>
                                <td>N. Samsudheen (IUML)</td>
                                <td class="text-center">—</td>
                                <td class="text-center">80.42%</td>
                            </tr>
                            <tr>
                                <td class="text-center">55</td>
                                <td>Malampuzha ★</td>
                                <td>A. Prabhakaran (CPI(M))</td>
                                <td>S. K. Anandakrishnan (INC)</td>
                                <td>C. Krishnakumar (BJP)</td>
                                <td class="text-center">81.81%</td>
                            </tr>
                            <tr>
                                <td class="text-center">56</td>
                                <td>Palakkad ★</td>
                                <td>N. M. R. Rasakh (Independent–LDF)</td>
                                <td>Ramesh Pisharody (INC)</td>
                                <td>Sobha Surendran (BJP)</td>
                                <td class="text-center">82.33%</td>
                            </tr>
                            <tr>
                                <td class="text-center">57</td>
                                <td>Tarur (SC)</td>
                                <td>P. P. Sumod (CPI(M))</td>
                                <td>K. C. Subramanian (INC)</td>
                                <td>Suresh Babu (BJP)</td>
                                <td class="text-center">78.21%</td>
                            </tr>
                            <tr>
                                <td class="text-center">58</td>
                                <td>Chittur ★</td>
                                <td>Adv. V. Murugadas (ISJD)</td>
                                <td>Sumesh Achuthan (INC)</td>
                                <td>Pranesh Rajendran (BJP)</td>
                                <td class="text-center">84.63%</td>
                            </tr>
                            <tr>
                                <td class="text-center">59</td>
                                <td>Nenmara</td>
                                <td>K. Preman (CPI(M))</td>
                                <td>A. Thankappan (INC)</td>
                                <td>A. N. Anurag (BDJS)</td>
                                <td class="text-center">80.61%</td>
                            </tr>
                            <tr>
                                <td class="text-center">60</td>
                                <td>Alathur (SC)</td>
                                <td>T. M. Sasi (CPI(M))</td>
                                <td>K. N. Febin (INC)</td>
                                <td>K. V. Prasanna Kumar (BJP)</td>
                                <td class="text-center">77.89%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h2 class="h3 mt-5 mb-3">3. Key battles to watch</h2>

                <h3 class="h5 mt-4 mb-2">#56 Palakkad — the marquee three-way contest</h3>
                <p>The most high-profile seat in the district. <strong>N. M. R. Rasakh</strong> contests as an LDF-backed Independent, facing <strong>Ramesh Pisharody</strong> (UDF–INC) and <strong>Sobha Surendran</strong> (NDA–BJP). This is BJP’s best shot at winning a Palakkad district seat. In the <strong>2024 bypoll</strong>, Congress’ <strong>Rahul Mamkootathil</strong> won with an <strong>18,840-vote</strong> margin. Turnout was a high <strong>82.33%</strong>.</p>

                <h3 class="h5 mt-4 mb-2">#49 Thrithala — Rajesh vs Balram rematch</h3>
                <p>Former Speaker <strong>M. B. Rajesh</strong> (CPI(M)) faces <strong>V. T. Balram</strong> (INC) in a rematch of their razor-thin <strong>2021</strong> contest decided by just <strong>3,016 votes</strong>. Both are heavyweight leaders and eloquent speakers, making this one of the most-watched battles in Kerala.</p>

                <h3 class="h5 mt-4 mb-2">#52 Ottapalam — star power meets political intrigue</h3>
                <p><strong>K. Premkumar</strong> (CPI(M)) faces <strong>P. K. Sasi</strong> (INC, former CPI(M) leader) and <strong>Major Ravi</strong> (BJP, the well-known film director). Sasi’s switch from CPI(M) to Congress adds a dramatic subplot to this contest.</p>

                <h3 class="h5 mt-4 mb-2">#55 Malampuzha — CPI(M) fortress under siege</h3>
                <p>A CPI(M) bastion since 1970, this seat has been represented by two former Chief Ministers (E. K. Nayanar and V. S. Achuthanandan). <strong>A. Prabhakaran</strong> (CPI(M)) defends against <strong>S. K. Anandakrishnan</strong> (INC) and <strong>C. Krishnakumar</strong> (BJP). NDA’s projected <strong>40.98%</strong> vote share here makes it a potential upset seat.</p>

                <h3 class="h5 mt-4 mb-2">#58 Chittur — highest turnout in the district</h3>
                <p>At <strong>84.63%</strong> turnout, Chittur drew the heaviest participation in Palakkad district. <strong>Adv. V. Murugadas</strong> (ISJD–LDF) faces <strong>Sumesh Achuthan</strong> (INC) and <strong>Pranesh Rajendran</strong> (BJP) in a three-way fight. The JD(S)–LDF combination is unique to this seat.</p>

                <h2 class="h3 mt-5 mb-3">4. Constituency summary</h2>
                <div class="table-responsive mt-3">
                    <table class="table table-striped table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col">Constituency</th>
                                <th scope="col">2021 winner</th>
                                <th scope="col">LS seat</th>
                                <th scope="col" class="text-center">SC reserved</th>
                                <th scope="col" class="text-center">2026 turnout</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td class="text-center">49</td><td>Thrithala</td><td>LDF</td><td>Ponnani</td><td class="text-center">No</td><td class="text-center">79.45%</td><td>★ Key</td></tr>
                            <tr><td class="text-center">50</td><td>Pattambi</td><td>LDF</td><td>Ponnani</td><td class="text-center">No</td><td class="text-center">78.54%</td><td></td></tr>
                            <tr><td class="text-center">51</td><td>Shornur</td><td>LDF</td><td>Palakkad</td><td class="text-center">No</td><td class="text-center">78.87%</td><td></td></tr>
                            <tr><td class="text-center">52</td><td>Ottapalam</td><td>LDF</td><td>Palakkad</td><td class="text-center">No</td><td class="text-center">79.12%</td><td>★ Key</td></tr>
                            <tr><td class="text-center">53</td><td>Kongad (SC)</td><td>LDF</td><td>Palakkad</td><td class="text-center">Yes</td><td class="text-center">80.16%</td><td></td></tr>
                            <tr><td class="text-center">54</td><td>Mannarkad</td><td>UDF</td><td>Palakkad</td><td class="text-center">No</td><td class="text-center">80.42%</td><td></td></tr>
                            <tr><td class="text-center">55</td><td>Malampuzha</td><td>LDF</td><td>Palakkad</td><td class="text-center">No</td><td class="text-center">81.81%</td><td>★ Key</td></tr>
                            <tr><td class="text-center">56</td><td>Palakkad</td><td>UDF</td><td>Palakkad</td><td class="text-center">No</td><td class="text-center">82.33%</td><td>★ Key</td></tr>
                            <tr><td class="text-center">57</td><td>Tarur (SC)</td><td>LDF</td><td>Alathur</td><td class="text-center">Yes</td><td class="text-center">78.21%</td><td></td></tr>
                            <tr><td class="text-center">58</td><td>Chittur</td><td>LDF</td><td>Alathur</td><td class="text-center">No</td><td class="text-center">84.63%</td><td>★ Key</td></tr>
                            <tr><td class="text-center">59</td><td>Nenmara</td><td>LDF</td><td>Alathur</td><td class="text-center">No</td><td class="text-center">80.61%</td><td></td></tr>
                            <tr><td class="text-center">60</td><td>Alathur (SC)</td><td>LDF</td><td>Alathur</td><td class="text-center">Yes</td><td class="text-center">77.89%</td><td></td></tr>
                        </tbody>
                    </table>
                </div>

                <h2 class="h3 mt-5 mb-3">5. Lok Sabha–wise distribution</h2>
                <p><strong>Palakkad Lok Sabha</strong> (7 constituencies): Shornur, Ottapalam, Kongad (SC), Mannarkad, Malampuzha, Palakkad, Tarur (SC). <strong>MP:</strong> V. K. Sreekandan (INC).</p>
                <p><strong>Alathur Lok Sabha</strong> (5 constituencies in this district): Thrithala, Pattambi, Chittur, Nenmara, Alathur (SC). Remaining seats under the Alathur LS umbrella lie in Thrissur district.</p>

                <div class="mt-5 d-flex flex-wrap gap-2">
                    <a href="<?php echo BASE_PATH; ?>/kerala-assembly-election-2026.php" class="btn btn-outline-primary">
                        <i class="bi bi-bar-chart-line me-1"></i> Kerala-wide survey &amp; seats
                    </a>
                    <a href="<?php echo BASE_PATH; ?>/" class="btn btn-primary">
                        <i class="bi bi-arrow-left me-1"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
