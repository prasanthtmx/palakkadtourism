<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/security.php';
require_once __DIR__ . '/config/database.php';

// ── Helpers ───────────────────────────────────────────────────────────────
function esc($s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function ypUrl(int $page, string $q, string $cat): string {
    $qs = [];
    if ($page > 1)  $qs['page']     = $page;
    if ($q !== '')   $qs['q']        = $q;
    if ($cat !== '') $qs['category'] = $cat;
    return BASE_PATH . '/yellow-pages.php' . ($qs ? '?' . http_build_query($qs, '', '&', PHP_QUERY_RFC3986) : '');
}

function ypIcon(string $slug): string {
    static $map = [
        'restaurant' => 'bi-cup-hot',   'food' => 'bi-cup-hot',       'cafe'    => 'bi-cup-hot',
        'hotel'      => 'bi-building',  'lodge' => 'bi-building',      'resort'  => 'bi-building',
        'hospital'   => 'bi-hospital',  'medical' => 'bi-capsule',     'clinic'  => 'bi-clipboard2-pulse',
        'pharmacy'   => 'bi-prescription2',
        'school'     => 'bi-mortarboard','college' => 'bi-mortarboard','education' => 'bi-mortarboard',
        'shop'       => 'bi-shop',      'store' => 'bi-shop',          'mart'    => 'bi-shop',
        'bank'       => 'bi-bank',      'atm'   => 'bi-credit-card',   'finance' => 'bi-piggy-bank',
        'transport'  => 'bi-truck',     'travel' => 'bi-airplane',     'taxi'    => 'bi-taxi-front',
        'real-estate'=> 'bi-house',     'property' => 'bi-house',
        'beauty'     => 'bi-scissors',  'salon' => 'bi-scissors',
        'electronics'=> 'bi-phone',     'mobile' => 'bi-phone',        'computer'=> 'bi-laptop',
        'it'         => 'bi-code-square',
        'grocery'    => 'bi-basket3',   'bakery' => 'bi-egg-fried',    'vegetable'=> 'bi-basket3',
        'jewel'      => 'bi-gem',       'gold'  => 'bi-gem',
        'cloth'      => 'bi-bag',       'textile'=> 'bi-bag',          'fashion' => 'bi-bag',
        'automobile' => 'bi-car-front', 'petrol' => 'bi-fuel-pump',
        'gym'        => 'bi-trophy',    'sport' => 'bi-trophy',        'fitness' => 'bi-trophy',
        'service'    => 'bi-gear',      'repair'=> 'bi-tools',
        'printing'   => 'bi-printer',   'photo' => 'bi-camera',
    ];
    foreach ($map as $k => $icon) {
        if (stripos($slug, $k) !== false) return $icon;
    }
    return 'bi-grid';
}

// ── Input ─────────────────────────────────────────────────────────────────
$perPage = 20;
$pageNum = max(1, min(5000, sanitizeInt($_GET['page'] ?? 1, 1) ?: 1));
$qRaw    = isset($_GET['q'])        ? trim((string)$_GET['q'])        : '';
if (mb_strlen($qRaw, 'UTF-8') > 120) $qRaw = mb_substr($qRaw, 0, 120, 'UTF-8');
$catSlug = isset($_GET['category']) ? trim((string)$_GET['category']) : '';
if (strlen($catSlug) > 180) $catSlug = '';

// ── DB ────────────────────────────────────────────────────────────────────
$categories    = [];
$entries       = [];
$total         = 0;
$totalPages    = 1;
$dbUnavailable = false;
$invalidCat    = false;
$activeCatName = '';
$catIdFilter   = null;

try {
    $conn = getDBConnection();

    $res = $conn->query("SELECT id, name, slug FROM directory_categories WHERE status='active' ORDER BY sort_order ASC, name ASC");
    if ($res) { while ($r = $res->fetch_assoc()) $categories[] = $r; $res->free(); }

    if ($catSlug !== '') {
        $st = $conn->prepare("SELECT id, name FROM directory_categories WHERE slug=? AND status='active' LIMIT 1");
        $st->bind_param('s', $catSlug); $st->execute();
        $cr = $st->get_result();
        if ($cr && ($rc = $cr->fetch_assoc())) {
            $catIdFilter   = (int)$rc['id'];
            $activeCatName = (string)$rc['name'];
        } else {
            $invalidCat = true;
        }
        $st->close();
    }

    $where = ["e.status='active'"]; $types = ''; $params = [];
    if ($catIdFilter !== null)                    { $where[] = 'e.category_id=?'; $types .= 'i'; $params[] = $catIdFilter; }
    elseif ($catSlug !== '' && $invalidCat)         $where[] = '1=0';
    if ($qRaw !== '') {
        $where[] = '(e.name LIKE ? OR e.description LIKE ? OR e.area LIKE ? OR e.address LIKE ?)';
        $types  .= 'ssss'; $like = '%' . $qRaw . '%';
        array_push($params, $like, $like, $like, $like);
    }
    $wSql = implode(' AND ', $where);

    $cs = $conn->prepare("SELECT COUNT(*) AS c FROM directory_entries e WHERE {$wSql}");
    if (!$cs) throw new RuntimeException($conn->error);
    if ($types !== '') { $r = [&$types]; foreach ($params as $k => $_) $r[] = &$params[$k]; call_user_func_array([$cs, 'bind_param'], $r); }
    $cs->execute();
    $total      = (int)($cs->get_result()->fetch_assoc()['c'] ?? 0);
    $totalPages = max(1, (int)ceil($total / $perPage));
    if ($pageNum > $totalPages) $pageNum = $totalPages;
    $cs->close();

    $off = ($pageNum - 1) * $perPage;
    $ls  = $conn->prepare(
        "SELECT e.id, e.name, e.slug, e.description, e.area, e.phone, e.featured,
                c.name AS cat, c.slug AS cat_slug
         FROM directory_entries e
         INNER JOIN directory_categories c ON c.id = e.category_id AND c.status = 'active'
         WHERE {$wSql}
         ORDER BY e.featured DESC, e.name ASC
         LIMIT " . (int)$perPage . " OFFSET " . (int)$off
    );
    if (!$ls) throw new RuntimeException($conn->error);
    if ($types !== '') { $r = [&$types]; foreach ($params as $k => $_) $r[] = &$params[$k]; call_user_func_array([$ls, 'bind_param'], $r); }
    $ls->execute();
    $lr = $ls->get_result();
    if ($lr) { while ($r = $lr->fetch_assoc()) $entries[] = $r; }
    $ls->close();
    closeDBConnection($conn);
} catch (Throwable $ex) {
    error_log('[yellow-pages] ' . $ex->getMessage()); $dbUnavailable = true;
    if (isset($conn) && $conn instanceof mysqli) closeDBConnection($conn);
}

// ── Dynamic SEO ──────────────────────────────────────────────────────────
$breadcrumbs = [
    ['title' => 'Home',        'url' => BASE_PATH . '/'],
    ['title' => 'Yellow Pages','url' => $activeCatName !== '' ? ypUrl(1, '', '') : ''],
];

if ($activeCatName !== '') {
    $pageTitle       = $activeCatName . ' in Palakkad — Yellow Pages';
    $pageDescription = 'Find ' . strtolower($activeCatName) . ' businesses and services in Palakkad, Kerala. Free local directory with contact details.';
    $breadcrumbs[]   = ['title' => $activeCatName, 'url' => ''];
} elseif ($qRaw !== '') {
    $pageTitle       = 'Search: ' . $qRaw . ' — Palakkad Yellow Pages';
    $pageDescription = 'Palakkad Yellow Pages search results for "' . $qRaw . '".';
    $pageRobots      = 'noindex,follow';
} else {
    $pageTitle       = 'Palakkad Yellow Pages — Local Business Directory';
    $pageDescription = 'Free Palakkad business directory — find shops, restaurants, hospitals, hotels, services and more, or add your own free listing today.';
}
if ($pageNum > 1) $pageTitle .= ' — Page ' . (int)$pageNum;

// Cache non-search pages at the edge/proxy level
if ($qRaw === '' && !headers_sent()) {
    header('Cache-Control: public, max-age=300, s-maxage=600');
}

// Prev/next link tags + page-specific CSS via $pageHeadExtra
$pageHeadExtra = '<style>
.yp-hero{background:linear-gradient(135deg,#1a6b4a 0%,#0f4a33 100%);color:#fff;padding:2.25rem 0 1.75rem}
.yp-hero h1{font-size:clamp(1.35rem,3.5vw,2rem);font-weight:800;letter-spacing:-.02em;margin:0 0 .35rem}
.yp-hero-lead{opacity:.82;font-size:.9rem;margin-bottom:1.1rem}
.yp-cats-wrap{overflow-x:auto;white-space:nowrap;-webkit-overflow-scrolling:touch;scrollbar-width:none;padding:.65rem 0;border-bottom:1px solid #e4e4e0}
.yp-cats-wrap::-webkit-scrollbar{display:none}
.yp-chip{display:inline-flex;align-items:center;gap:.3rem;padding:.28rem .8rem;border-radius:20px;border:1.5px solid #ddddd8;color:#4a4a44;background:#fff;font-size:.76rem;font-weight:600;text-decoration:none;margin-right:.3rem;transition:border-color .14s,color .14s,background .14s}
.yp-chip:hover{border-color:#1a6b4a;color:#1a6b4a}
.yp-chip.yp-active{border-color:#1a6b4a;background:#1a6b4a;color:#fff}
.yp-search-section{background:#f5f5f2;border-bottom:1px solid #e4e4e0;padding:.7rem 0}
.yp-card{background:#fff;border:1px solid #e4e4e0;border-radius:10px;overflow:hidden;height:100%;position:relative;transition:box-shadow .14s,transform .1s;display:flex;flex-direction:column}
.yp-card:hover{box-shadow:0 4px 20px rgba(0,0,0,.09);transform:translateY(-2px)}
.yp-card--featured{border-color:#d4a40a;border-width:2px}
.yp-card-top{padding:.9rem 1rem .45rem;display:flex;align-items:flex-start;gap:.7rem}
.yp-icon{width:40px;height:40px;border-radius:9px;background:#e8f4ee;color:#1a6b4a;display:flex;align-items:center;justify-content:center;font-size:1.05rem;flex-shrink:0}
.yp-card--featured .yp-icon{background:#fef6e4;color:#c09508}
.yp-name-wrap{min-width:0;flex:1}
.yp-card-name{font-weight:700;font-size:.88rem;color:#1a1a18;text-decoration:none;line-height:1.35;display:block;margin-bottom:.3rem}
.yp-card-name:hover{color:#1a6b4a}
.yp-cat-link{display:inline-flex;align-items:center;gap:.2rem;font-size:.68rem;font-weight:600;text-transform:uppercase;letter-spacing:.07em;color:#6b6b63;background:#f0efec;border-radius:4px;padding:2px 6px;text-decoration:none}
.yp-cat-link:hover{background:#e8f4ee;color:#1a6b4a}
.yp-card-body{padding:0 1rem .85rem;flex:1;display:flex;flex-direction:column}
.yp-meta{font-size:.75rem;color:#6b6b63;display:flex;align-items:center;gap:.3rem;margin-bottom:.18rem}
.yp-meta a{color:#1a6b4a;text-decoration:none}
.yp-meta a:hover{text-decoration:underline}
.yp-desc{font-size:.77rem;color:#555;line-height:1.5;margin-top:.35rem;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;flex:1}
.yp-crown{background:#d4a40a;color:#fff;font-size:.58rem;font-weight:700;text-transform:uppercase;letter-spacing:.08em;padding:2px 10px 2px 7px;position:absolute;top:0;left:0;border-radius:0 0 6px 0}
.yp-empty{text-align:center;padding:3.5rem 1rem}
.yp-empty i{font-size:2.5rem;color:#bbb;display:block;margin-bottom:1rem}
@media(prefers-color-scheme:dark){
.yp-card{background:#1e1e1b;border-color:#2c2c28}.yp-card--featured{border-color:#b89008}
.yp-card-name{color:#e4e4dc}.yp-icon{background:#0e2e1e;color:#3ab87a}
.yp-card--featured .yp-icon{background:#2a1e04;color:#d4a40a}
.yp-cat-link{background:#252520;color:#9a9a8e}.yp-meta{color:#8a8a7e}
.yp-desc{color:#6e6e64}.yp-chip{border-color:#2c2c28;color:#9a9a8e;background:#1e1e1b}
.yp-chip:hover{border-color:#3ab87a;color:#3ab87a}
.yp-chip.yp-active{border-color:#3ab87a;background:#3ab87a;color:#141412}
.yp-search-section{background:#1a1a18;border-bottom-color:#2c2c28}
.yp-cats-wrap{border-bottom-color:#2c2c28}
}
</style>';
if ($pageNum > 1)           $pageHeadExtra .= '<link rel="prev" href="' . esc(seoPublicUrl(ypUrl($pageNum - 1, $qRaw, $catSlug))) . '">';
if ($pageNum < $totalPages)  $pageHeadExtra .= '<link rel="next" href="' . esc(seoPublicUrl(ypUrl($pageNum + 1, $qRaw, $catSlug))) . '">';

require_once __DIR__ . '/includes/header.php';
?>
<?php if (!$dbUnavailable && !empty($entries)): ?>
<script type="application/ld+json">
<?php
$ldItems = [];
foreach ($entries as $i => $e) {
    $pos  = $i + 1 + (($pageNum - 1) * $perPage);
    $eUrl = SITE_URL . BASE_PATH . '/yellow-page.php?slug=' . rawurlencode($e['slug']);
    $addr = ['@type' => 'PostalAddress', 'addressLocality' => ($e['area'] ?: 'Palakkad'), 'addressRegion' => 'Kerala', 'addressCountry' => 'IN'];
    $biz  = ['@type' => 'LocalBusiness', 'name' => $e['name'], 'url' => $eUrl, 'address' => $addr];
    if (!empty($e['phone'])) $biz['telephone']  = $e['phone'];
    if (!empty($e['area']))  $biz['areaServed'] = $e['area'];
    $ldItems[] = ['@type' => 'ListItem', 'position' => $pos, 'item' => $biz];
}
echo json_encode([
    '@context'        => 'https://schema.org',
    '@type'           => 'ItemList',
    'name'            => $pageTitle,
    'description'     => $pageDescription,
    'url'             => $seoCanonicalUrl,
    'numberOfItems'   => (int)$total,
    'itemListElement' => $ldItems,
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
?>
</script>
<?php endif; ?>

<main id="main-content">

<!-- Hero header -->
<div class="yp-hero">
    <div class="container">
        <div class="d-flex flex-wrap align-items-start justify-content-between gap-3">
            <div>
                <h1>
                    <?php if ($activeCatName !== ''): ?>
                        <i class="bi <?php echo esc(ypIcon($catSlug)); ?>" aria-hidden="true"></i>
                        <?php echo esc($activeCatName); ?> — Palakkad
                    <?php elseif ($qRaw !== ''): ?>
                        <i class="bi bi-search" aria-hidden="true"></i> Search results
                    <?php else: ?>
                        Yellow Pages — Palakkad
                    <?php endif; ?>
                </h1>
                <p class="yp-hero-lead mb-0">
                    <?php if ($activeCatName !== ''): ?>
                        Local <?php echo esc(strtolower($activeCatName)); ?> businesses in Palakkad, Kerala.
                    <?php elseif ($qRaw !== ''): ?>
                        Results for <strong><?php echo esc($qRaw); ?></strong>
                        &nbsp;<a href="<?php echo esc(BASE_PATH . '/yellow-pages.php'); ?>" class="text-white-50 small">Clear</a>
                    <?php else: ?>
                        Free local business directory &mdash; shops, services, restaurants, and more.
                    <?php endif; ?>
                </p>
            </div>
            <a href="<?php echo esc(BASE_PATH . '/yellow-pages-submit.php'); ?>"
               class="btn btn-light btn-sm px-3 flex-shrink-0 mt-1">
                <i class="bi bi-plus-circle me-1"></i> Add free listing
            </a>
        </div>
    </div>
</div>

<?php if (!$dbUnavailable): ?>

<!-- Search bar -->
<div class="yp-search-section">
    <div class="container">
        <form method="get" action="<?php echo esc(BASE_PATH . '/yellow-pages.php'); ?>"
              class="d-flex flex-wrap gap-2 align-items-center" role="search">
            <div class="input-group" style="max-width:300px">
                <span class="input-group-text bg-white border-end-0 pe-1">
                    <i class="bi bi-search text-muted" aria-hidden="true"></i>
                </span>
                <input type="search" name="q" id="q" value="<?php echo esc($qRaw); ?>"
                       class="form-control border-start-0 ps-1"
                       placeholder="Name, area, keywords…" maxlength="120"
                       aria-label="Search listings">
            </div>
            <select name="category" id="category" class="form-select"
                    style="max-width:190px" aria-label="Filter by category">
                <option value="">All categories</option>
                <?php foreach ($categories as $c): ?>
                    <option value="<?php echo esc($c['slug']); ?>"
                        <?php echo $catSlug === $c['slug'] ? 'selected' : ''; ?>>
                        <?php echo esc($c['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-primary btn-sm px-3">Apply</button>
            <?php if ($qRaw !== '' || $catSlug !== ''): ?>
                <a href="<?php echo esc(BASE_PATH . '/yellow-pages.php'); ?>"
                   class="btn btn-link btn-sm text-muted p-0">Clear all</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Category chips -->
<?php if (!empty($categories)): ?>
<div class="container">
    <nav class="yp-cats-wrap" aria-label="Browse by category">
        <a href="<?php echo esc(ypUrl(1, $qRaw, '')); ?>"
           class="yp-chip <?php echo $catSlug === '' ? 'yp-active' : ''; ?>">
            <i class="bi bi-grid" aria-hidden="true"></i> All
        </a>
        <?php foreach ($categories as $c): ?>
            <a href="<?php echo esc(ypUrl(1, $qRaw, $c['slug'])); ?>"
               class="yp-chip <?php echo $catSlug === $c['slug'] ? 'yp-active' : ''; ?>">
                <i class="bi <?php echo esc(ypIcon($c['slug'])); ?>" aria-hidden="true"></i>
                <?php echo esc($c['name']); ?>
            </a>
        <?php endforeach; ?>
    </nav>
</div>
<?php endif; ?>

<section class="py-4">
    <div class="container">
        <?php if (function_exists('displayAdSense')) displayAdSense('content-top', 'max-width: 728px;'); ?>

        <?php if ($invalidCat && $catSlug !== ''): ?>
            <div class="alert alert-info mb-3 py-2 small">
                <i class="bi bi-info-circle"></i> That category wasn't found — showing all listings.
            </div>
        <?php endif; ?>

        <?php if ($total === 0): ?>
            <div class="yp-empty">
                <i class="bi bi-search"></i>
                <h2 class="h5 mb-2">No listings found</h2>
                <p class="text-muted mb-4">
                    <?php if ($qRaw !== ''): ?>
                        No results for <strong><?php echo esc($qRaw); ?></strong>.
                    <?php elseif ($activeCatName !== ''): ?>
                        No <strong><?php echo esc(strtolower($activeCatName)); ?></strong> listings yet.
                    <?php else: ?>
                        The directory is empty.
                    <?php endif; ?>
                </p>
                <?php if ($qRaw !== '' || $catSlug !== ''): ?>
                    <a href="<?php echo esc(BASE_PATH . '/yellow-pages.php'); ?>"
                       class="btn btn-outline-secondary btn-sm me-2">Clear filters</a>
                <?php endif; ?>
                <a href="<?php echo esc(BASE_PATH . '/yellow-pages-submit.php'); ?>"
                   class="btn btn-primary btn-sm">
                    <i class="bi bi-plus-circle me-1"></i> Submit the first listing
                </a>
            </div>

        <?php else: ?>
            <!-- Results count bar -->
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">
                <p class="text-muted small mb-0">
                    <strong><?php echo number_format($total); ?></strong>
                    listing<?php echo $total !== 1 ? 's' : ''; ?>
                    <?php if ($activeCatName !== ''): ?>
                        in <strong><?php echo esc($activeCatName); ?></strong>
                    <?php endif; ?>
                    <?php if ($qRaw !== ''): ?>
                        matching <strong>&ldquo;<?php echo esc($qRaw); ?>&rdquo;</strong>
                    <?php endif; ?>
                    <?php if ($totalPages > 1): ?>
                        &mdash; page <?php echo (int)$pageNum; ?> of <?php echo (int)$totalPages; ?>
                    <?php endif; ?>
                </p>
                <?php if ($qRaw !== '' || $catSlug !== ''): ?>
                    <a href="<?php echo esc(BASE_PATH . '/yellow-pages.php'); ?>"
                       class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-lg me-1"></i> Clear filters
                    </a>
                <?php endif; ?>
            </div>

            <!-- Listing grid -->
            <div class="row g-3">
                <?php foreach ($entries as $e):
                    $isFeatured = !empty((int)$e['featured']);
                    $entryUrl   = BASE_PATH . '/yellow-page.php?slug=' . rawurlencode($e['slug']);
                    $teaser     = '';
                    if (!empty($e['description'])) {
                        $teaser = strip_tags($e['description']);
                        if (mb_strlen($teaser, 'UTF-8') > 120)
                            $teaser = mb_substr($teaser, 0, 117, 'UTF-8') . '…';
                    }
                    $icon = ypIcon($e['cat_slug'] ?? '');
                ?>
                <div class="col-md-6 col-lg-4">
                    <article class="yp-card<?php echo $isFeatured ? ' yp-card--featured' : ''; ?>">
                        <?php if ($isFeatured): ?>
                            <span class="yp-crown" aria-label="Featured listing">★ Featured</span>
                        <?php endif; ?>
                        <div class="yp-card-top">
                            <div class="yp-icon" aria-hidden="true">
                                <i class="bi <?php echo esc($icon); ?>"></i>
                            </div>
                            <div class="yp-name-wrap">
                                <a href="<?php echo esc($entryUrl); ?>"
                                   class="yp-card-name stretched-link">
                                    <?php echo esc($e['name']); ?>
                                </a>
                                <a href="<?php echo esc(ypUrl(1, $qRaw, $e['cat_slug'] ?? '')); ?>"
                                   class="yp-cat-link" style="position:relative;z-index:1">
                                    <i class="bi <?php echo esc($icon); ?>" aria-hidden="true"></i>
                                    <?php echo esc($e['cat'] ?? ''); ?>
                                </a>
                            </div>
                        </div>
                        <div class="yp-card-body">
                            <?php if (!empty($e['area'])): ?>
                                <p class="yp-meta">
                                    <i class="bi bi-geo-alt" aria-hidden="true"></i>
                                    <?php echo esc($e['area']); ?>
                                </p>
                            <?php endif; ?>
                            <?php if (!empty($e['phone'])): ?>
                                <p class="yp-meta">
                                    <i class="bi bi-telephone" aria-hidden="true"></i>
                                    <a href="tel:<?php echo preg_replace('/[^\d+\-\s()]/', '', $e['phone']); ?>"
                                       style="position:relative;z-index:1">
                                        <?php echo esc($e['phone']); ?>
                                    </a>
                                </p>
                            <?php endif; ?>
                            <?php if ($teaser !== ''): ?>
                                <p class="yp-desc"><?php echo esc($teaser); ?></p>
                            <?php endif; ?>
                        </div>
                    </article>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
            <nav class="mt-4 pt-2" aria-label="Directory pagination">
                <ul class="pagination flex-wrap justify-content-center gap-1">
                    <?php if ($pageNum > 1): ?>
                        <li class="page-item">
                            <a class="page-link rounded" href="<?php echo esc(ypUrl($pageNum - 1, $qRaw, $catSlug)); ?>">
                                <i class="bi bi-chevron-left" aria-hidden="true"></i> Prev
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php
                    $from = max(1, $pageNum - 2);
                    $to   = min($totalPages, $pageNum + 2);
                    if ($from > 1)
                        echo '<li class="page-item disabled"><span class="page-link px-2">…</span></li>';
                    for ($p = $from; $p <= $to; $p++) {
                        $cls = $p === $pageNum ? ' active' : '';
                        echo '<li class="page-item' . $cls . '">'
                            . '<a class="page-link rounded" href="' . esc(ypUrl($p, $qRaw, $catSlug)) . '">' . $p . '</a>'
                            . '</li>';
                    }
                    if ($to < $totalPages)
                        echo '<li class="page-item disabled"><span class="page-link px-2">…</span></li>';
                    ?>
                    <?php if ($pageNum < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link rounded" href="<?php echo esc(ypUrl($pageNum + 1, $qRaw, $catSlug)); ?>">
                                Next <i class="bi bi-chevron-right" aria-hidden="true"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
            <?php endif; ?>

        <?php endif; // $total === 0 ?>
    </div>
</section>

<?php else: // $dbUnavailable ?>
<section class="py-5">
    <div class="container">
        <div class="alert alert-warning">
            <i class="bi bi-database-x"></i>
            The directory tables are not ready yet.
            Import <code>database/directory_schema.sql</code> into your database, then reload.
        </div>
    </div>
</section>
<?php endif; ?>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
