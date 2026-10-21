<?php
require_once __DIR__ . '/config/config.php';

$pageTitle = "Schools and colleges in Palakkad";
$pageDescription = "Schools and colleges in Palakkad — government, private, and aided institutions.";
$pageKeywords = "Palakkad schools, Palakkad colleges, educational institutions Palakkad, government schools, private schools, Palakkad universities, Palakkad education";

// Breadcrumbs for SEO
$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'Community', 'url' => '#'],
    ['title' => 'Institutions', 'url' => '']
];

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/security.php';

// Pagination settings
$itemsPerPage = 9;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

// Filter and search
$typeFilter = sanitizeInput($_GET['type'] ?? '');
$categoryFilter = sanitizeInput($_GET['category'] ?? '');
$search = sanitizeInput($_GET['search'] ?? '');

// Build WHERE clause
$whereClause = "WHERE status = 'active'";
$params = [];
$paramTypes = '';

if (!empty($typeFilter) && in_array($typeFilter, ['school', 'college'])) {
    $whereClause .= " AND type = ?";
    $params[] = $typeFilter;
    $paramTypes .= 's';
}

if (!empty($categoryFilter) && in_array($categoryFilter, ['government', 'private', 'aided'])) {
    $whereClause .= " AND category = ?";
    $params[] = $categoryFilter;
    $paramTypes .= 's';
}

$searchParam = null;
if (!empty($search)) {
    $searchParam = "%{$search}%";
    $whereClause .= " AND (name LIKE ? OR location LIKE ? OR address LIKE ? OR description LIKE ? OR courses LIKE ?)";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
    $paramTypes .= 'sssss';
}

$institutions = [];
$totalCount = 0;
$totalPages = 0;
$institutionsLoadError = false;

$conn = getDBConnection();
try {
    // Get total count
    if (!empty($params)) {
        $stmt = $conn->prepare("SELECT COUNT(*) as total FROM institutions $whereClause");
        if (!empty($paramTypes)) {
            $stmt->bind_param($paramTypes, ...$params);
        }
        $stmt->execute();
        $totalCountResult = $stmt->get_result();
        $totalCount = (int) $totalCountResult->fetch_assoc()['total'];
        $stmt->close();
    } else {
        $totalCountResult = $conn->query("SELECT COUNT(*) as total FROM institutions $whereClause");
        $totalCount = (int) $totalCountResult->fetch_assoc()['total'];
    }
    $totalPages = (int) ceil($totalCount / $itemsPerPage);

    // Get institutions with pagination
    $paginationParams = array_merge($params, [$itemsPerPage, $offset]);
    $paginationTypes = $paramTypes . 'ii';

    if (!empty($params)) {
        $stmt = $conn->prepare("SELECT * FROM institutions $whereClause ORDER BY type, category, name ASC LIMIT ? OFFSET ?");
        $stmt->bind_param($paginationTypes, ...$paginationParams);
        $stmt->execute();
        $result = $stmt->get_result();
        $institutions = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        $stmt = $conn->prepare("SELECT * FROM institutions $whereClause ORDER BY type, category, name ASC LIMIT ? OFFSET ?");
        $stmt->bind_param("ii", $itemsPerPage, $offset);
        $stmt->execute();
        $result = $stmt->get_result();
        $institutions = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    }
    $institutionSlugs = seoSlugMap($institutions);
} catch (mysqli_sql_exception $e) {
    error_log('institutions.php: ' . $e->getMessage());
    $institutions = [];
    $institutionSlugs = [];
    $totalCount = 0;
    $totalPages = 0;
    $institutionsLoadError = true;
} finally {
    closeDBConnection($conn);
}
?>
<!-- Institutions Listing Structured Data -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "ItemList",
    "name": "Institutions in Palakkad",
    "description": "List of schools and colleges in Palakkad, Kerala",
    "url": "<?php echo  $seoCanonicalUrl ; ?>",
    "numberOfItems": <?php echo count($institutions); ?>,
    "itemListElement": [
        <?php 
        $institutionItems = [];
        foreach ($institutions as $index => $institution) {
            $institutionItems[] = '{
                "@type": "ListItem",
                "position": ' . ($index + 1) . ',
                "item": {
                    "@type": "EducationalOrganization",
                    "@id": "' . seoPublicUrl(getInstitutionUrl($institution['id'], $institution['name'], $institutionSlugs[(int)$institution['id']] ?? null)) . '",
                    "name": "' . htmlspecialchars($institution['name'], ENT_QUOTES) . '",
                    "description": "' . htmlspecialchars(substr($institution['description'] ?? '', 0, 200), ENT_QUOTES) . '",
                    "address": {
                        "@type": "PostalAddress",
                        "addressLocality": "' . htmlspecialchars($institution['location'] ?? '', ENT_QUOTES) . '",
                        "addressRegion": "Kerala",
                        "addressCountry": "IN"
                    }
                }
            }';
        }
        echo implode(",\n        ", $institutionItems);
        ?>
    ]
}
</script>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h1 class="display-4 mb-4">Institutions in Palakkad</h1>
                
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-mortarboard"></i> <strong>Note:</strong> Find schools and colleges in Palakkad district. Contact institutions directly for admission details, fees, and course information.
                </div>
                
                <?php displayAdSense('content-top', 'max-width: 728px;'); ?>

                <!-- Filters and Search -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="" class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Type</label>
                                <select class="form-select" name="type">
                                    <option value="">All Types</option>
                                    <option value="school" <?php echo $typeFilter === 'school' ? 'selected' : ''; ?>>Schools</option>
                                    <option value="college" <?php echo $typeFilter === 'college' ? 'selected' : ''; ?>>Colleges</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category">
                                    <option value="">All Categories</option>
                                    <option value="government" <?php echo $categoryFilter === 'government' ? 'selected' : ''; ?>>Government</option>
                                    <option value="private" <?php echo $categoryFilter === 'private' ? 'selected' : ''; ?>>Private</option>
                                    <option value="aided" <?php echo $categoryFilter === 'aided' ? 'selected' : ''; ?>>Aided</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Search</label>
                                <input type="text" class="form-control" name="search" 
                                       value="<?php echo htmlspecialchars($search); ?>" 
                                       placeholder="Search by name, location...">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Filter & Search
                                </button>
                                <a href="institutions.php" class="btn btn-outline-secondary">
                                    <i class="bi bi-x-circle"></i> Clear
                                </a>
                                <?php if (!empty($search) || !empty($typeFilter) || !empty($categoryFilter)): ?>
                                <span class="ms-2 text-muted">Found <?php echo $totalCount; ?> result(s)</span>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>
                </div>

                <?php if (!empty($institutionsLoadError)): ?>
                <div class="alert alert-danger" role="alert">
                    <i class="bi bi-database-exclamation"></i> The institutions directory could not be loaded. If this continues, please try again later.
                </div>
                <?php elseif (empty($institutions)): ?>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> No institutions found<?php echo (!empty($search) || !empty($typeFilter) || !empty($categoryFilter)) ? ' matching your criteria.' : '.'; ?>
                </div>
                <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($institutions as $institution): ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 shadow-sm">
                            <?php if ($institution['image_url']): ?>
                            <img src="<?php echo htmlspecialchars($institution['image_url']); ?>" 
                                 class="card-img-top" alt="<?php echo htmlspecialchars($institution['name']); ?>" 
                                 style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="bi bi-mortarboard text-muted" style="font-size: 3rem;"></i>
                            </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="card-title mb-0"><?php echo htmlspecialchars($institution['name']); ?></h5>
                                    <span class="badge bg-info"><?php echo ucfirst($institution['type']); ?></span>
                                </div>
                                <p class="text-muted small mb-2">
                                    <span class="badge bg-secondary"><?php echo ucfirst($institution['category']); ?></span>
                                    <?php if ($institution['location']): ?>
                                    <span class="ms-2"><i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($institution['location']); ?></span>
                                    <?php endif; ?>
                                </p>
                                <?php if ($institution['description']): ?>
                                <p class="card-text"><?php echo htmlspecialchars(substr($institution['description'], 0, 100)); ?><?php echo strlen($institution['description']) > 100 ? '...' : ''; ?></p>
                                <?php endif; ?>
                                <a href="<?php echo htmlspecialchars(getInstitutionUrl($institution['id'], $institution['name'], $institutionSlugs[(int)$institution['id']] ?? null)); ?>" class="btn btn-sm btn-primary">
                                    <i class="bi bi-arrow-right"></i> View Details
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <nav aria-label="Institution pagination" class="mt-5">
                    <ul class="pagination justify-content-center">
                        <?php
                        $queryParams = $_GET;
                        // Previous button
                        if ($currentPage > 1):
                            $queryParams['page'] = $currentPage - 1;
                            $prevUrl = '?' . http_build_query($queryParams);
                        ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo $prevUrl; ?>">
                                <i class="bi bi-chevron-left"></i> Previous
                            </a>
                        </li>
                        <?php else: ?>
                        <li class="page-item disabled">
                            <span class="page-link"><i class="bi bi-chevron-left"></i> Previous</span>
                        </li>
                        <?php endif; ?>
                        
                        <?php
                        // Page numbers
                        $startPage = max(1, $currentPage - 2);
                        $endPage = min($totalPages, $currentPage + 2);
                        
                        if ($startPage > 1):
                            $queryParams['page'] = 1;
                            $firstUrl = '?' . http_build_query($queryParams);
                        ?>
                        <li class="page-item"><a class="page-link" href="<?php echo $firstUrl; ?>">1</a></li>
                        <?php if ($startPage > 2): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php endif; ?>
                        <?php endif; ?>
                        
                        <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <?php
                            $queryParams['page'] = $i;
                            $pageUrl = '?' . http_build_query($queryParams);
                        ?>
                        <li class="page-item <?php echo $i == $currentPage ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo $pageUrl; ?>"><?php echo $i; ?></a>
                        </li>
                        <?php endfor; ?>
                        
                        <?php
                        if ($endPage < $totalPages):
                            $queryParams['page'] = $totalPages;
                            $lastUrl = '?' . http_build_query($queryParams);
                        ?>
                        <?php if ($endPage < $totalPages - 1): ?>
                        <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php endif; ?>
                        <li class="page-item"><a class="page-link" href="<?php echo $lastUrl; ?>"><?php echo $totalPages; ?></a></li>
                        <?php endif; ?>
                        
                        <?php
                        // Next button
                        if ($currentPage < $totalPages):
                            $queryParams['page'] = $currentPage + 1;
                            $nextUrl = '?' . http_build_query($queryParams);
                        ?>
                        <li class="page-item">
                            <a class="page-link" href="<?php echo $nextUrl; ?>">
                                Next <i class="bi bi-chevron-right"></i>
                            </a>
                        </li>
                        <?php else: ?>
                        <li class="page-item disabled">
                            <span class="page-link">Next <i class="bi bi-chevron-right"></i></span>
                        </li>
                        <?php endif; ?>
                    </ul>
                </nav>
                <div class="text-center mt-3 text-muted">
                    Showing <?php echo count($institutions); ?> of <?php echo $totalCount; ?> institution(s) - Page <?php echo $currentPage; ?> of <?php echo $totalPages; ?>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

