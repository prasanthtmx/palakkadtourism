<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';

$conn = getDBConnection();

// Pagination settings
$itemsPerPage = 6;
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($currentPage - 1) * $itemsPerPage;

// Search functionality - using prepared statements for security
require_once __DIR__ . '/config/security.php';
$search = sanitizeInput($_GET['search'] ?? '');
$whereClause = "WHERE type = 'ayurveda' AND status = 'active'";
$searchParam = null;

if (!empty($search)) {
    $searchParam = "%{$search}%";
    $whereClause .= " AND (name LIKE ? OR location LIKE ? OR address LIKE ? OR services LIKE ?)";
}

// Get total count
if (!empty($search) && $searchParam) {
    $stmt = $conn->prepare("SELECT COUNT(*) as total FROM hospitals $whereClause");
    $stmt->bind_param("ssss", $searchParam, $searchParam, $searchParam, $searchParam);
    $stmt->execute();
    $totalCountResult = $stmt->get_result();
    $totalCount = $totalCountResult->fetch_assoc()['total'];
    $stmt->close();
} else {
    $totalCountResult = $conn->query("SELECT COUNT(*) as total FROM hospitals $whereClause");
    $totalCount = $totalCountResult->fetch_assoc()['total'];
}
$totalPages = ceil($totalCount / $itemsPerPage);

// Get hospitals with pagination
if (!empty($search) && $searchParam) {
    $stmt = $conn->prepare("SELECT * FROM hospitals $whereClause ORDER BY name ASC LIMIT ? OFFSET ?");
    $stmt->bind_param("ssssii", $searchParam, $searchParam, $searchParam, $searchParam, $itemsPerPage, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $hospitals = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
} else {
    $stmt = $conn->prepare("SELECT * FROM hospitals $whereClause ORDER BY name ASC LIMIT ? OFFSET ?");
    $stmt->bind_param("ii", $itemsPerPage, $offset);
    $stmt->execute();
    $result = $stmt->get_result();
    $hospitals = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}
closeDBConnection($conn);

$pageTitle = "Ayurveda Hospitals in Palakkad";
$pageDescription = "List of Ayurveda hospitals and treatment centers in Palakkad district, Kerala. Find traditional Ayurveda healthcare facilities offering holistic treatments in Palakkad.";
$pageKeywords = "Ayurveda hospitals Palakkad, Ayurveda treatment, traditional medicine Palakkad, Ayurveda centers, Kerala Ayurveda, holistic healthcare";
require_once __DIR__ . '/includes/header.php';
?>

<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <h1 class="display-4 mb-4">Ayurveda Hospitals in Palakkad</h1>
                
                <div class="alert alert-info" role="alert">
                    <i class="bi bi-flower1"></i> <strong>Note:</strong> Please contact Ayurveda hospitals directly for accurate information about treatments, timings, packages, and availability. Contact numbers and addresses may change.
                </div>
                
                <?php displayAdSense('content-top', 'max-width: 728px;'); ?>

                <!-- Search Bar -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <form method="GET" action="" class="row g-3">
                            <div class="col-md-10">
                                <input type="text" class="form-control form-control-lg" name="search" 
                                       value="<?php echo htmlspecialchars($search); ?>" 
                                       placeholder="Search by hospital name, location, address, or services...">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-warning btn-lg w-100">
                                    <i class="bi bi-search"></i> Search
                                </button>
                            </div>
                            <?php if (!empty($search)): ?>
                            <div class="col-12">
                                <a href="hospitals-ayurveda.php" class="btn btn-outline-secondary btn-sm">
                                    <i class="bi bi-x-circle"></i> Clear Search
                                </a>
                                <span class="ms-2 text-muted">Found <?php echo $totalCount; ?> result(s) for "<?php echo htmlspecialchars($search); ?>"</span>
                            </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <?php if (empty($hospitals)): ?>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle"></i> No Ayurveda hospitals found<?php echo !empty($search) ? ' matching your search.' : '.'; ?>
                </div>
                <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($hospitals as $hospital): ?>
                    <div class="col-md-6">
                        <div class="card h-100 shadow-sm">
                            <?php if ($hospital['image_url']): ?>
                            <img src="<?php echo htmlspecialchars($hospital['image_url']); ?>" 
                                 class="card-img-top" alt="<?php echo htmlspecialchars($hospital['name']); ?>" 
                                 style="height: 200px; object-fit: cover;">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <i class="bi bi-flower1 text-warning"></i> <?php echo htmlspecialchars($hospital['name']); ?>
                                </h5>
                                <?php if ($hospital['description']): ?>
                                <p class="card-text"><?php echo htmlspecialchars($hospital['description']); ?></p>
                                <?php endif; ?>
                                
                                <ul class="list-unstyled">
                                    <?php if ($hospital['location']): ?>
                                    <li class="mb-2">
                                        <i class="bi bi-geo-alt text-muted"></i> 
                                        <strong>Location:</strong> <?php echo htmlspecialchars($hospital['location']); ?>
                                    </li>
                                    <?php endif; ?>
                                    
                                    <?php if ($hospital['address']): ?>
                                    <li class="mb-2">
                                        <i class="bi bi-map text-muted"></i> 
                                        <strong>Address:</strong> <?php echo htmlspecialchars($hospital['address']); ?>
                                    </li>
                                    <?php endif; ?>
                                    
                                    <?php if ($hospital['phone']): ?>
                                    <li class="mb-2">
                                        <i class="bi bi-telephone text-muted"></i> 
                                        <strong>Phone:</strong> <a href="tel:<?php echo htmlspecialchars($hospital['phone']); ?>"><?php echo htmlspecialchars($hospital['phone']); ?></a>
                                    </li>
                                    <?php endif; ?>
                                    
                                    <?php if ($hospital['contact']): ?>
                                    <li class="mb-2">
                                        <i class="bi bi-person text-muted"></i> 
                                        <strong>Contact:</strong> <?php echo htmlspecialchars($hospital['contact']); ?>
                                    </li>
                                    <?php endif; ?>
                                    
                                    <?php if ($hospital['email']): ?>
                                    <li class="mb-2">
                                        <i class="bi bi-envelope text-muted"></i> 
                                        <strong>Email:</strong> <a href="mailto:<?php echo htmlspecialchars($hospital['email']); ?>"><?php echo htmlspecialchars($hospital['email']); ?></a>
                                    </li>
                                    <?php endif; ?>
                                    
                                    <?php if ($hospital['timings']): ?>
                                    <li class="mb-2">
                                        <i class="bi bi-clock text-muted"></i> 
                                        <strong>Timings:</strong> <?php echo htmlspecialchars($hospital['timings']); ?>
                                    </li>
                                    <?php endif; ?>
                                    
                                    <?php if ($hospital['services']): ?>
                                    <li class="mb-2">
                                        <i class="bi bi-list-check text-muted"></i> 
                                        <strong>Services:</strong> <?php echo htmlspecialchars($hospital['services']); ?>
                                    </li>
                                    <?php endif; ?>
                                    
                                    <?php if ($hospital['website']): ?>
                                    <li class="mb-2">
                                        <i class="bi bi-globe text-muted"></i> 
                                        <strong>Website:</strong> <a href="<?php echo htmlspecialchars($hospital['website']); ?>" target="_blank">Visit Website</a>
                                    </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <nav aria-label="Hospital pagination" class="mt-5">
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
                    Showing <?php echo count($hospitals); ?> of <?php echo $totalCount; ?> hospital(s) - Page <?php echo $currentPage; ?> of <?php echo $totalPages; ?>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
