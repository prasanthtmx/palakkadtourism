<?php
require_once __DIR__ . '/config/config.php';
$pageTitle = 'Kerala Legislative Assembly Election 2026';
$pageDescription = 'Pre-Poll Survey Analysis & Seat Projections for the 2026 Kerala Legislative Assembly election: UDF, LDF, and NDA trends, vote share estimates, and regional dynamics across Kerala.';
$pageKeywords = 'Kerala election 2026, Kerala assembly polls, UDF LDF NDA, seat projections, pre-poll survey Kerala, Palakkad';

$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'Kerala Assembly Election 2026', 'url' => ''],
];

require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5 mt-5 election-2026-page">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <p class="text-muted small mb-2">Pre-Poll Survey Analysis &amp; Seat Projections</p>
                <h1 class="display-5 fw-bold mb-3">Kerala Legislative Assembly Election 2026</h1>
                <p class="lead text-secondary mb-4">
                    Polling Date: 9 April 2026 &nbsp;|&nbsp; Result Date: 4 May 2026 &nbsp;|&nbsp; Total Seats: 140 &nbsp;|&nbsp; Majority: 71
                </p>

                <div class="alert alert-secondary" role="alert">
                    <i class="bi bi-info-circle"></i> Survey figures and seat ranges are compiled from publicly available pre-poll data gathered from different online sources.
                </div>

                <?php displayAdSense('content-top', 'max-width: 728px;'); ?>

                <h2 class="h3 mt-5 mb-3">1. Election Overview</h2>
                <p>The 2026 Kerala Legislative Assembly election was held on 9 April 2026 to elect all 140 members. A total of 2,71,42,952 voters were eligible, including 1,32,20,811 male, 1,39,21,868 female, and 273 transgender voters. The election is a closely fought battle between the ruling LDF seeking an unprecedented third consecutive term and the UDF aiming to restore Kerala’s traditional pattern of alternating governments.</p>

                <h3 class="h4 mt-4 mb-3">Key Alliances &amp; Parties</h3>
                <ul class="mb-0">
                    <li><strong>UDF (United Democratic Front):</strong> Led by Indian National Congress with IUML, Kerala Congress, and allies. CM candidate: V.D. Satheesan. Campaign slogan: “Keralam Jayikkum, UDF Nayikkum.”</li>
                    <li><strong>LDF (Left Democratic Front):</strong> Led by CPI(M) with CPI, RSP, KC(M), NCP(SP), and allies. CM: Pinarayi Vijayan seeking a historic third term. Slogan: “Mattarund LDF Allathe?”</li>
                    <li><strong>NDA (National Democratic Alliance):</strong> Led by BJP under state president Rajeev Chandrasekhar. Slogan: “Marathathu Ini Marum.” PM Modi launched campaign from Kochi on 11 March 2026.</li>
                </ul>

                <h2 class="h3 mt-5 mb-3">2. Pre-Poll Survey Seat Projections</h2>
                <p>Multiple opinion polls and surveys present a range of projections. While most surveys give the UDF an edge, the race is extremely tight, with the LDF remaining competitive. The NDA’s projected improvement from zero seats in 2021 to potentially 5–17 seats could play a kingmaker role in a hung assembly scenario.</p>

                <div class="table-responsive mt-4">
                    <table class="table table-striped table-bordered align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th scope="col">Survey</th>
                                <th scope="col" class="text-center">UDF</th>
                                <th scope="col" class="text-center">LDF</th>
                                <th scope="col" class="text-center">NDA</th>
                                <th scope="col" class="text-center">Others</th>
                                <th scope="col">Likely Winner</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>C-Voter / Manorama</td>
                                <td class="text-center">69–81</td>
                                <td class="text-center">57–69</td>
                                <td class="text-center">1–5</td>
                                <td class="text-center">0–1</td>
                                <td>UDF</td>
                            </tr>
                            <tr>
                                <td>Lokpoll (Revised)</td>
                                <td class="text-center">77–81</td>
                                <td class="text-center">58–62</td>
                                <td class="text-center">1–2</td>
                                <td class="text-center">0</td>
                                <td>UDF</td>
                            </tr>
                            <tr>
                                <td>Matrize / India TV</td>
                                <td class="text-center">67–73</td>
                                <td class="text-center">62–68</td>
                                <td class="text-center">5–8</td>
                                <td class="text-center">0–3</td>
                                <td>Too close</td>
                            </tr>
                            <tr>
                                <td>Political Vibe</td>
                                <td class="text-center">49–69</td>
                                <td class="text-center">59–78</td>
                                <td class="text-center">8–17</td>
                                <td class="text-center">0</td>
                                <td>Too close</td>
                            </tr>
                            <tr>
                                <td>Poll Tracker</td>
                                <td class="text-center">89–96</td>
                                <td class="text-center">43–49</td>
                                <td class="text-center">0–2</td>
                                <td class="text-center">0</td>
                                <td>UDF</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h3 class="h5 mt-4 mb-2">Seat ranges by survey (chart)</h3>
                <p class="text-muted small mb-3">Each bar shows the low–high seat projection for that survey (140 total seats; majority 71). Same numbers as the table above.</p>
                <div class="card border shadow-sm mb-2">
                    <div class="card-body p-3">
                        <div class="position-relative election-seat-chart-wrap">
                            <canvas id="electionSeatRangeChart" width="800" height="360" aria-label="Bar chart of seat projection ranges by survey"></canvas>
                        </div>
                    </div>
                </div>

                <div class="alert alert-light border mt-3" role="alert">
                    <i class="bi bi-info-circle text-secondary"></i>
                    <strong>Note:</strong> Seat ranges reflect the survey’s confidence interval. “Too close” indicates no alliance clearly crosses the 71-seat majority mark within the projected range. Projections are based on publicly available survey data released before the silence period.
                </div>

                <h2 class="h3 mt-5 mb-3">3. Estimated Vote Share</h2>
                <p>Vote share estimates show a narrow gap between UDF and LDF, with margins as thin as 3–4 percentage points. Kerala’s electoral history suggests that crossing the low-40s in vote share typically translates to winning power. The UDF appears close to that threshold, with the LDF not far behind.</p>

                <div class="table-responsive mt-3">
                    <table class="table table-bordered w-auto">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" class="text-center">UDF</th>
                                <th scope="col" class="text-center">LDF</th>
                                <th scope="col" class="text-center">NDA</th>
                                <th scope="col" class="text-center">Others</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="text-center">~42%</td>
                                <td class="text-center">~39%</td>
                                <td class="text-center">~16%</td>
                                <td class="text-center">~3%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h2 class="h3 mt-5 mb-3">4. Regional Dynamics</h2>

                <h3 class="h5 mt-4 mb-2">Malabar (North Kerala)</h3>
                <p>Surveys predict a strong UDF resurgence in Malabar, with the alliance projected to win 25–34 out of 48 seats. The IUML’s dominance in Malappuram and UDF’s strength in Wayanad and Kasaragod drive this advantage. The LDF retains its hold in Kannur and Kozhikode.</p>

                <h3 class="h5 mt-4 mb-2">Central Kerala</h3>
                <p>The LDF has a clear lead in Palakkad (8–10 of 12 seats) and Thrissur (9–11 of 13 seats). The UDF dominates Ernakulam (12–14 of 14 seats) and Kottayam (7–9 seats). The NDA has a chance of winning one seat in Thrissur.</p>

                <h3 class="h5 mt-4 mb-2">South Kerala</h3>
                <p>The LDF retains its upper hand in southern districts, particularly Alappuzha (6–8 seats) and Kollam (5–7 seats). However, the UDF is gaining ground in Pathanamthitta, where it could flip 3–5 seats from LDF. Nemom in Thiruvananthapuram is the NDA’s strongest seat with a projected 43.6% vote share.</p>

                <h2 class="h3 mt-5 mb-3">5. Key Takeaways</h2>
                <ul>
                    <li>UDF holds a narrow edge in most surveys with projected seat ranges of 59–96. The traditional pattern of alternating governments favours them.</li>
                    <li>LDF is fighting for a historic third term but faces anti-incumbency. The Sabarimala gold heist case has significantly eroded LDF credibility according to Lokpoll.</li>
                    <li>NDA’s rise from 0 seats in 2021 to a potential 5–17 seats is structurally disruptive. Nemom, Malampuzha, and Manjeswaram are strong NDA seats, and the alliance could play kingmaker in a hung assembly.</li>
                    <li>Leadership preference is fragmented: V.D. Satheesan leads at ~25%, followed by Pinarayi Vijayan at ~21%, and Shashi Tharoor at ~17%.</li>
                    <li>The election is no longer a straightforward two-front contest. The NDA’s growing presence in constituencies like Kasaragod, Thrissur, Irinjalakkuda, and Attingal is turning several seats into three-cornered fights, making margins razor-thin.</li>
                    <li><strong>2021 context:</strong> The LDF won 99 seats in 2021, its best-ever performance, while the UDF managed only 41 seats. Any result in the 60–80 range for either front would represent a dramatic shift.</li>
                </ul>

                <div class="mt-5">
                    <a href="<?php echo BASE_PATH; ?>/" class="btn btn-primary">
                        <i class="bi bi-arrow-left"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
(function () {
    var canvas = document.getElementById('electionSeatRangeChart');
    if (!canvas || typeof Chart === 'undefined') return;

    var majorityLine = {
        id: 'majorityLine71',
        afterDraw: function (chart) {
            var yScale = chart.scales.y;
            if (!yScale) return;
            var y = yScale.getPixelForValue(71);
            var left = chart.chartArea.left;
            var right = chart.chartArea.right;
            var ctx = chart.ctx;
            ctx.save();
            ctx.strokeStyle = 'rgba(25, 135, 84, 0.9)';
            ctx.setLineDash([6, 4]);
            ctx.lineWidth = 2;
            ctx.beginPath();
            ctx.moveTo(left, y);
            ctx.lineTo(right, y);
            ctx.stroke();
            ctx.setLineDash([]);
            ctx.fillStyle = '#146c43';
            ctx.font = '600 11px system-ui, sans-serif';
            ctx.textAlign = 'end';
            ctx.textBaseline = 'bottom';
            ctx.fillText('71 majority', right, y - 3);
            ctx.restore();
        }
    };

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: [
                'C-Voter / Manorama',
                'Lokpoll (Revised)',
                'Matrize / India TV',
                'Political Vibe',
                'Poll Tracker'
            ],
            datasets: [
                {
                    label: 'UDF',
                    backgroundColor: 'rgba(13, 110, 253, 0.72)',
                    borderColor: 'rgba(13, 110, 253, 1)',
                    borderWidth: 1,
                    data: [[69, 81], [77, 81], [67, 73], [49, 69], [89, 96]]
                },
                {
                    label: 'LDF',
                    backgroundColor: 'rgba(220, 53, 69, 0.72)',
                    borderColor: 'rgba(220, 53, 69, 1)',
                    borderWidth: 1,
                    data: [[57, 69], [58, 62], [62, 68], [59, 78], [43, 49]]
                },
                {
                    label: 'NDA',
                    backgroundColor: 'rgba(253, 126, 20, 0.82)',
                    borderColor: 'rgba(214, 109, 18, 1)',
                    borderWidth: 1,
                    data: [[1, 5], [1, 2], [5, 8], [8, 17], [0, 2]]
                },
                {
                    label: 'Others',
                    backgroundColor: 'rgba(108, 117, 125, 0.72)',
                    borderColor: 'rgba(73, 80, 87, 1)',
                    borderWidth: 1,
                    data: [[0, 1], null, [0, 3], null, null]
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1.35,
            layout: { padding: { top: 8, right: 4 } },
            datasets: {
                bar: {
                    categoryPercentage: 0.72,
                    barPercentage: 0.88
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        maxRotation: 40,
                        minRotation: 0,
                        font: { size: 10 }
                    }
                },
                y: {
                    min: 0,
                    max: 100,
                    title: { display: true, text: 'Seats (min–max per survey)' },
                    grid: { color: 'rgba(0, 0, 0, 0.06)' },
                    ticks: { stepSize: 10 }
                }
            },
            plugins: {
                legend: { position: 'bottom' },
                tooltip: {
                    callbacks: {
                        label: function (ctx) {
                            var v = ctx.raw;
                            if (v == null || (Array.isArray(v) && v[0] == null)) {
                                return ctx.dataset.label + ': —';
                            }
                            if (Array.isArray(v)) {
                                return ctx.dataset.label + ': ' + v[0] + '–' + v[1] + ' seats';
                            }
                            return ctx.dataset.label + ': ' + ctx.formattedValue;
                        }
                    }
                }
            }
        },
        plugins: [majorityLine]
    });
})();
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
