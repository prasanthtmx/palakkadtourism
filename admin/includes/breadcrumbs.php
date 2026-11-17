<?php
/**
 * Admin breadcrumb under the page title.
 * Uses $adminPageTitle. Optional $adminBreadcrumbs = [['label' => '', 'url' => '']].
 */
$adminHome = rtrim((string) BASE_PATH, '/') . '/admin/';
$adminTitle = (isset($adminPageTitle) && is_string($adminPageTitle) && $adminPageTitle !== '')
    ? $adminPageTitle
    : 'Admin';
$adminCrumbItems = [
    ['label' => 'Dashboard', 'url' => $adminHome],
];
if (!empty($adminBreadcrumbs) && is_array($adminBreadcrumbs)) {
    foreach ($adminBreadcrumbs as $adminCrumb) {
        if (!is_array($adminCrumb) || empty($adminCrumb['label'])) {
            continue;
        }
        $adminCrumbItems[] = [
            'label' => (string) $adminCrumb['label'],
            'url' => (string) ($adminCrumb['url'] ?? ''),
        ];
    }
} elseif (strcasecmp($adminTitle, 'Dashboard') !== 0) {
    $adminCrumbItems[] = ['label' => $adminTitle, 'url' => ''];
}
$adminCrumbLast = count($adminCrumbItems) - 1;
?>
<nav class="admin-breadcrumbs" aria-label="Breadcrumb">
    <ol>
        <?php foreach ($adminCrumbItems as $adminCrumbIndex => $adminCrumb): ?>
        <li>
            <?php if ($adminCrumbIndex < $adminCrumbLast && $adminCrumb['url'] !== ''): ?>
            <a href="<?php echo htmlspecialchars($adminCrumb['url']); ?>"><?php echo htmlspecialchars($adminCrumb['label']); ?></a>
            <?php else: ?>
            <span aria-current="page"><?php echo htmlspecialchars($adminCrumb['label']); ?></span>
            <?php endif; ?>
        </li>
        <?php endforeach; ?>
    </ol>
</nav>
