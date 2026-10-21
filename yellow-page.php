<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$slugGet = isset($_GET['slug']) ? trim((string) $_GET['slug']) : '';
$idGet = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($slugGet !== '' && strlen($slugGet) > 240) {
    $slugGet = '';
}

$conn = getDBConnection();
$entry = null;

try {
    if ($slugGet !== '') {
        $stmt = $conn->prepare(
            'SELECT e.*, c.name AS category_name, c.slug AS category_slug
             FROM directory_entries e
             INNER JOIN directory_categories c ON c.id = e.category_id
             WHERE e.slug = ? AND e.status = ? LIMIT 1'
        );
        $active = 'active';
        $stmt->bind_param('ss', $slugGet, $active);
        $stmt->execute();
        $rs = $stmt->get_result();
        if ($rs && ($entry = $rs->fetch_assoc())) {
            //
        }
        $stmt->close();
    } elseif ($idGet > 0) {
        $stmt = $conn->prepare(
            'SELECT e.*, c.name AS category_name, c.slug AS category_slug
             FROM directory_entries e
             INNER JOIN directory_categories c ON c.id = e.category_id
             WHERE e.id = ? AND e.status = ? LIMIT 1'
        );
        $active = 'active';
        $stmt->bind_param('is', $idGet, $active);
        $stmt->execute();
        $rs = $stmt->get_result();
        if ($rs && ($entry = $rs->fetch_assoc())) {
            if (!headers_sent()) {
                header('Location: ' . rtrim(BASE_PATH, '/') . '/yellow-page.php?slug=' . rawurlencode($entry['slug']), true, 301);
                exit;
            }
        }
        $stmt->close();
    }
} catch (Throwable $e) {
    error_log('[yellow-page] ' . $e->getMessage());
    $entry = null;
}

closeDBConnection($conn);

if (!$entry) {
    if (!headers_sent()) {
        header('Location: ' . BASE_PATH . '/yellow-pages.php', true, 302);
    }
    exit;
}

$pageTitle = !empty($entry['meta_title']) ? $entry['meta_title'] : ($entry['name'] . ' — Palakkad Yellow Pages');
$descBase = !empty($entry['meta_description']) ? $entry['meta_description'] : strip_tags((string) $entry['description']);
if ($descBase !== '' && mb_strlen($descBase, 'UTF-8') > 165) {
    $descBase = mb_substr($descBase, 0, 162, 'UTF-8') . '…';
}
if ($descBase === '') {
    $descBase = $entry['name'] . ' — business listing in Palakkad directory. ' . ($entry['category_name'] ?? '');
}
$pageDescription = $descBase;

$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'Yellow Pages', 'url' => BASE_PATH . '/yellow-pages.php'],
    ['title' => $entry['name'], 'url' => ''],
];

$articleDatePublished = null;
if (!empty($entry['created_at'])) {
    $ts = strtotime($entry['created_at']);
    if ($ts) {
        $articleDatePublished = date('Y-m-d', $ts);
    }
}
$articleDateModified = $articleDatePublished;
if (!empty($entry['updated_at'])) {
    $tsM = strtotime($entry['updated_at']);
    if ($tsM) {
        $articleDateModified = date('Y-m-d', $tsM);
    }
}

require_once __DIR__ . '/includes/header.php';

$detailUrl = rtrim(SITE_URL, '/') . BASE_PATH . '/yellow-page.php?slug=' . rawurlencode($entry['slug']);
$phoneSchema = preg_replace('/\s+/', '', (string) $entry['phone']);
$websiteOut = '';
if (!empty($entry['website'])) {
    $w = trim((string) $entry['website']);
    if ($w !== '' && !preg_match('#^https?://#i', $w)) {
        $w = 'https://' . $w;
    }
    if (filter_var($w, FILTER_VALIDATE_URL)) {
        $websiteOut = $w;
    }
}
?>
<script type="application/ld+json">
<?php
$ld = [
    '@context' => 'https://schema.org',
    '@type' => 'LocalBusiness',
    'name' => $entry['name'],
    'url' => $detailUrl,
];
$ldDesc = strip_tags((string) $entry['description']);
if ($ldDesc !== '') {
    $ld['description'] = $ldDesc;
}
if ($websiteOut !== '') {
    $ld['sameAs'] = [$websiteOut];
}
$addr = [
    '@type' => 'PostalAddress',
    'addressLocality' => $entry['area'] ?: 'Palakkad',
    'addressRegion' => 'Kerala',
    'addressCountry' => 'IN',
];
if (!empty($entry['address'])) {
    $addr['streetAddress'] = $entry['address'];
}
$ld['address'] = $addr;
if ($phoneSchema !== '') {
    $ld['telephone'] = $phoneSchema;
}
if (!empty($entry['email']) && filter_var($entry['email'], FILTER_VALIDATE_EMAIL)) {
    $ld['email'] = $entry['email'];
}
echo json_encode($ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
?>
</script>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <p class="text-muted mb-2"><a href="<?php echo htmlspecialchars(BASE_PATH . '/yellow-pages.php'); ?>">Yellow Pages</a><?php if (!empty($entry['category_slug'])): ?> · <a href="<?php echo htmlspecialchars(BASE_PATH . '/yellow-pages.php?category=' . rawurlencode($entry['category_slug'])); ?>"><?php echo htmlspecialchars($entry['category_name']); ?></a><?php endif; ?></p>
                <h1 class="display-5 mb-3"><?php echo htmlspecialchars($entry['name']); ?></h1>
                <?php if (!empty((int) $entry['featured'])): ?>
                    <span class="badge bg-warning text-dark mb-3">Featured listing</span>
                <?php endif; ?>

                <?php if (!empty($entry['description'])): ?>
                    <div class="mb-4 content-block"><?php echo nl2br(htmlspecialchars($entry['description'])); ?></div>
                <?php endif; ?>

                <dl class="row mb-0">
                    <?php if (!empty($entry['address'])): ?>
                        <dt class="col-sm-3">Address</dt>
                        <dd class="col-sm-9"><?php echo nl2br(htmlspecialchars($entry['address'])); ?></dd>
                    <?php endif; ?>
                    <?php if (!empty($entry['area'])): ?>
                        <dt class="col-sm-3">Area</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($entry['area']); ?></dd>
                    <?php endif; ?>
                    <?php if (!empty($entry['phone'])): ?>
                        <dt class="col-sm-3">Phone</dt>
                        <dd class="col-sm-9"><a href="tel:<?php echo htmlspecialchars(preg_replace('/\s+/', '', $entry['phone'])); ?>"><?php echo htmlspecialchars($entry['phone']); ?></a></dd>
                    <?php endif; ?>
                    <?php if (!empty($entry['email'])): ?>
                        <dt class="col-sm-3">Email</dt>
                        <dd class="col-sm-9"><a href="mailto:<?php echo htmlspecialchars($entry['email']); ?>"><?php echo htmlspecialchars($entry['email']); ?></a></dd>
                    <?php endif; ?>
                    <?php if ($websiteOut !== ''): ?>
                        <dt class="col-sm-3">Website</dt>
                        <dd class="col-sm-9"><a href="<?php echo htmlspecialchars($websiteOut); ?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($websiteOut); ?></a></dd>
                    <?php endif; ?>
                    <?php if (!empty($entry['contact_person'])): ?>
                        <dt class="col-sm-3">Contact</dt>
                        <dd class="col-sm-9"><?php echo htmlspecialchars($entry['contact_person']); ?></dd>
                    <?php endif; ?>
                </dl>

                <p class="mt-4 mb-0">
                    <a class="btn btn-outline-primary" href="<?php echo htmlspecialchars(BASE_PATH . '/yellow-pages.php'); ?>"><i class="bi bi-arrow-left"></i> Back to directory</a>
                    <a class="btn btn-primary" href="<?php echo htmlspecialchars(BASE_PATH . '/yellow-pages-submit.php'); ?>">List your business</a>
                </p>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
