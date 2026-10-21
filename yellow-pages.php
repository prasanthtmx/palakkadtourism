<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/security.php';

$pageTitle = 'Palakkad Yellow Pages';
$pageDescription = 'Free Palakkad business directory. Find shops, services, and restaurants, or submit a listing at no cost.';

$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'Yellow Pages', 'url' => ''],
];

require_once __DIR__ . '/includes/header.php';

$perPage = 24;
$pageNum = sanitizeInt($_GET['page'] ?? 1, 1) ?: 1;
if ($pageNum > 5000) {
    $pageNum = 5000;
}
$qRaw = isset($_GET['q']) ? trim((string) $_GET['q']) : '';
if (mb_strlen($qRaw, 'UTF-8') > 120) {
    $qRaw = mb_substr($qRaw, 0, 120, 'UTF-8');
}
$catSlugFilter = isset($_GET['category']) ? trim((string) $_GET['category']) : '';
if (strlen($catSlugFilter) > 180) {
    $catSlugFilter = '';
}

$categories = [];
$entries = [];
$total = 0;
$dbUnavailable = false;
$invalidCategory = false;

require_once __DIR__ . '/config/database.php';

try {
    $conn = getDBConnection();
    $res = $conn->query("SELECT id, name, slug FROM directory_categories WHERE status='active' ORDER BY sort_order ASC, name ASC");
    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $categories[] = $row;
        }
        $res->free();
    }

    $categoryIdFilter = null;
    if ($catSlugFilter !== '') {
        $st = $conn->prepare("SELECT id FROM directory_categories WHERE slug = ? AND status='active' LIMIT 1");
        $st->bind_param('s', $catSlugFilter);
        $st->execute();
        $cr = $st->get_result();
        if ($cr && ($rowCat = $cr->fetch_assoc())) {
            $categoryIdFilter = (int) $rowCat['id'];
        } else {
            $invalidCategory = true;
        }
        $st->close();
    }

    $where = ["e.status='active'"];
    $types = '';
    $params = [];

    if ($categoryIdFilter !== null) {
        $where[] = 'e.category_id = ?';
        $types .= 'i';
        $params[] = $categoryIdFilter;
    } elseif ($catSlugFilter !== '' && $invalidCategory) {
        $where[] = '1=0';
    }

    if ($qRaw !== '') {
        $where[] = '(e.name LIKE ? OR e.description LIKE ? OR e.area LIKE ? OR e.address LIKE ?)';
        $types .= 'ssss';
        $like = '%' . $qRaw . '%';
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
        $params[] = $like;
    }

    $whereSql = implode(' AND ', $where);

    $countSql = "SELECT COUNT(*) AS c FROM directory_entries e WHERE {$whereSql}";
    $cstmt = $conn->prepare($countSql);
    if ($cstmt === false) {
        throw new RuntimeException($conn->error);
    }
    if ($types !== '') {
        $refs = [];
        $refs[] = &$types;
        foreach ($params as $k => $v) {
            $refs[] = &$params[$k];
        }
        call_user_func_array([$cstmt, 'bind_param'], $refs);
    }
    $cstmt->execute();
    $total = (int) ($cstmt->get_result()->fetch_assoc()['c'] ?? 0);
    $cstmt->close();

    $totalPages = max(1, (int) ceil($total / $perPage));
    if ($pageNum > $totalPages) {
        $pageNum = $totalPages;
    }

    $offset = ($pageNum - 1) * $perPage;
    $listSql = "SELECT e.id, e.name, e.slug, e.description, e.area, e.phone, e.featured, c.name AS category_name, c.slug AS category_slug
        FROM directory_entries e
        INNER JOIN directory_categories c ON c.id = e.category_id AND c.status = 'active'
        WHERE {$whereSql}
        ORDER BY e.featured DESC, e.name ASC
        LIMIT " . (int) $perPage . " OFFSET " . (int) $offset;

    $stmt = $conn->prepare($listSql);
    if ($stmt === false) {
        throw new RuntimeException($conn->error);
    }
    if ($types !== '') {
        $refs = [];
        $refs[] = &$types;
        foreach ($params as $k => $v) {
            $refs[] = &$params[$k];
        }
        call_user_func_array([$stmt, 'bind_param'], $refs);
    }
    $stmt->execute();
    $lr = $stmt->get_result();
    if ($lr) {
        while ($row = $lr->fetch_assoc()) {
            $entries[] = $row;
        }
    }
    $stmt->close();
    closeDBConnection($conn);
} catch (Throwable $e) {
    error_log('[yellow-pages] ' . $e->getMessage());
    $dbUnavailable = true;
    if (isset($conn) && $conn instanceof mysqli) {
        closeDBConnection($conn);
    }
}

function yellow_pages_query_url(int $page, string $q, string $catSlug): string {
    $p = [BASE_PATH . '/yellow-pages.php'];
    $qs = [];
    if ($page > 1) {
        $qs['page'] = $page;
    }
    if ($q !== '') {
        $qs['q'] = $q;
    }
    if ($catSlug !== '') {
        $qs['category'] = $catSlug;
    }
    if ($qs) {
        $p[] = '?' . http_build_query($qs, '', '&', PHP_QUERY_RFC3986);
    }
    return implode('', $p);
}
?>
<?php if (!$dbUnavailable && !empty($entries)): ?>
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "Palakkad Yellow Pages — Business Directory",
    "description": <?php echo json_encode($pageDescription, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>,
    "url": "<?php echo htmlspecialchars( $seoCanonicalUrl , ENT_QUOTES, 'UTF-8'); ?>",
    "numberOfItems": <?php echo (int) $total; ?>,
    "itemListElement": [
        <?php
        $items = [];
        foreach ($entries as $i => $e) {
            $pos = $i + 1 + (($pageNum - 1) * $perPage);
            $url = SITE_URL . BASE_PATH . '/yellow-page.php?slug=' . rawurlencode($e['slug']);
            $items[] = json_encode([
                '@type' => 'ListItem',
                'position' => $pos,
                'url' => $url,
                'name' => $e['name'],
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        }
        echo implode(",\n        ", $items);
        ?>
    ]
}
</script>
<?php endif; ?>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-lg-9">
                <h1 class="display-5 mb-2">Yellow Pages — Palakkad Directory</h1>
                <p class="lead text-muted mb-0">Browse free local listings for shops, services, and professionals. Anyone can submit a listing; new entries appear after moderator review.</p>
            </div>
            <div class="col-lg-3 text-lg-end mt-3 mt-lg-0">
                <a class="btn btn-primary" href="<?php echo htmlspecialchars(BASE_PATH . '/yellow-pages-submit.php'); ?>"><i class="bi bi-plus-circle"></i> Add free listing</a>
            </div>
        </div>

        <?php if (function_exists('displayAdSense')) {
            displayAdSense('content-top', 'max-width: 728px;');
        } ?>

        <?php if ($dbUnavailable): ?>
            <div class="alert alert-warning"><i class="bi bi-database-x"></i> The directory tables are not ready yet. Import <code>database/directory_schema.sql</code> into your Palakkad database, then reload this page.</div>
        <?php else: ?>
            <form method="get" action="<?php echo htmlspecialchars(BASE_PATH . '/yellow-pages.php'); ?>" class="row g-2 align-items-end mb-4 p-3 bg-light rounded shadow-sm">
                <div class="col-md-4">
                    <label class="form-label small mb-0" for="q">Search</label>
                    <input type="search" id="q" name="q" value="<?php echo htmlspecialchars($qRaw); ?>" class="form-control" placeholder="Name, area, keywords…" maxlength="120">
                </div>
                <div class="col-md-4">
                    <label class="form-label small mb-0" for="category">Category</label>
                    <select name="category" id="category" class="form-select">
                        <option value="">All categories</option>
                        <?php foreach ($categories as $c): ?>
                            <option value="<?php echo htmlspecialchars($c['slug']); ?>" <?php echo $catSlugFilter === $c['slug'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-outline-primary"><i class="bi bi-search"></i> Apply</button>
                    <a href="<?php echo htmlspecialchars(BASE_PATH . '/yellow-pages.php'); ?>" class="btn btn-link">Reset</a>
                </div>
            </form>

            <?php if ($invalidCategory && $catSlugFilter !== ''): ?>
                <div class="alert alert-info mb-4">That category slug is not in the directory; category filters are ignored.</div>
            <?php endif; ?>

            <?php if ($total === 0): ?>
                <div class="alert alert-info mb-4"><i class="bi bi-info-circle"></i> No listings match your filters yet. Be the first: <a href="<?php echo htmlspecialchars(BASE_PATH . '/yellow-pages-submit.php'); ?>">submit a free listing</a>.</div>
            <?php else: ?>
                <p class="text-muted small mb-3"><?php echo (int) $total; ?> listing<?php echo $total !== 1 ? 's' : ''; ?> <?php echo $totalPages > 1 ? '(page ' . (int) $pageNum . ' of ' . (int) $totalPages . ')' : ''; ?></p>
                <div class="row g-4">
                    <?php foreach ($entries as $e): ?>
                        <div class="col-md-6 col-lg-4">
                            <article class="card h-100 shadow-sm">
                                <div class="card-body">
                                    <?php if (!empty((int) $e['featured'])): ?>
                                        <span class="badge bg-warning text-dark mb-2">Featured</span>
                                    <?php endif; ?>
                                    <span class="badge bg-secondary mb-2"><?php echo htmlspecialchars($e['category_name']); ?></span>
                                    <h2 class="h5 card-title"><a href="<?php echo htmlspecialchars(BASE_PATH . '/yellow-page.php?slug=' . rawurlencode($e['slug'])); ?>" class="stretched-link text-decoration-none text-dark"><?php echo htmlspecialchars($e['name']); ?></a></h2>
                                    <?php if (!empty($e['area'])): ?>
                                        <p class="small text-muted mb-1"><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($e['area']); ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($e['phone'])): ?>
                                        <p class="small text-muted mb-1"><i class="bi bi-telephone"></i> <?php echo htmlspecialchars($e['phone']); ?></p>
                                    <?php endif; ?>
                                    <?php
                                    $teaser = $e['description'] ?? '';
                                    if ($teaser !== '') {
                                        $teaser = strip_tags($teaser);
                                        if (mb_strlen($teaser, 'UTF-8') > 160) {
                                            $teaser = mb_substr($teaser, 0, 157, 'UTF-8') . '…';
                                        }
                                        echo '<p class="card-text small">' . htmlspecialchars($teaser) . '</p>';
                                    }
                                    ?>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($totalPages > 1): ?>
                    <nav class="mt-4" aria-label="Directory pagination">
                        <ul class="pagination flex-wrap justify-content-center">
                            <?php if ($pageNum > 1): ?>
                                <li class="page-item"><a class="page-link" href="<?php echo htmlspecialchars(yellow_pages_query_url($pageNum - 1, $qRaw, $catSlugFilter)); ?>">Previous</a></li>
                            <?php endif; ?>
                            <?php
                            $from = max(1, $pageNum - 2);
                            $to = min($totalPages, $pageNum + 2);
                            for ($p = $from; $p <= $to; $p++) {
                                $active = $p === $pageNum ? ' active' : '';
                                echo '<li class="page-item' . $active . '"><a class="page-link" href="' . htmlspecialchars(yellow_pages_query_url($p, $qRaw, $catSlugFilter)) . '">' . $p . '</a></li>';
                            }
                            ?>
                            <?php if ($pageNum < $totalPages): ?>
                                <li class="page-item"><a class="page-link" href="<?php echo htmlspecialchars(yellow_pages_query_url($pageNum + 1, $qRaw, $catSlugFilter)); ?>">Next</a></li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
