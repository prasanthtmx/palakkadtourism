<?php
// Breadcrumbs for SEO
if (!isset($breadcrumbs)) {
    $breadcrumbs = [];
}

if (empty($breadcrumbs)) {
    // Auto-generate breadcrumbs based on current page (avoid $currentPage — used for pagination on some pages)
    $breadcrumbScriptSlug = basename($_SERVER['PHP_SELF'], '.php');
    $breadcrumbs = [
        ['title' => 'Home', 'url' => BASE_PATH . '/']
    ];
    
    if ($breadcrumbScriptSlug !== 'index') {
        $pageTitles = [
            'temples' => 'Temples',
            'churches' => 'Churches',
            'mosques' => 'Mosques',
            'festivals' => 'Festivals',
            'places' => 'Places to Visit',
            'about' => 'About',
            'history' => 'History',
            'hospitals' => 'Hospitals',
            'institutions' => 'Institutions',
            'heritage-villages' => 'Heritage Villages',
            'yellow-pages' => 'Yellow Pages',
            'religious-places' => 'Religious Places',
            'how-to-reach' => 'How to Reach',
            'art-culture' => 'Art and Culture',
            'customs' => 'Customs',
            'villages-taluks' => 'Villages and Taluks',
            'rivers' => 'Rivers',
            'mountain-ranges-passes' => 'Mountain Ranges',
            'fort' => 'Fort',
            'donate-blood' => 'Donate Blood',
            'vintage-gallery' => 'Vintage Gallery',
        ];
        
        if (isset($pageTitles[$breadcrumbScriptSlug])) {
            $breadcrumbs[] = ['title' => $pageTitles[$breadcrumbScriptSlug], 'url' => ''];
        }
    }
}
?>
<nav aria-label="breadcrumb" class="breadcrumb-nav">
    <div class="container">
        <ol class="breadcrumb mb-0 py-2">
            <?php foreach ($breadcrumbs as $index => $crumb): ?>
                <?php if ($index === count($breadcrumbs) - 1): ?>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo $crumb['title']; ?></li>
                <?php else: ?>
                    <li class="breadcrumb-item">
                        <a href="<?php echo htmlspecialchars($crumb['url']); ?>"><?php echo htmlspecialchars($crumb['title']); ?></a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        </ol>
    </div>
</nav>




