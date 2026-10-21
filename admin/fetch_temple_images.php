<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/security.php';

requireAdminAuth();

$conn = getDBConnection();
$message = '';
$messageType = '';

// Google Custom Search API Configuration
// Get your API key and Search Engine ID from: https://developers.google.com/custom-search/v1/overview
$googleApiKey = ''; // Add your Google Custom Search API key here
$searchEngineId = ''; // Add your Custom Search Engine ID here

// Function to fetch images from Google Custom Search
function fetchTempleImageFromGoogle($templeName, $location, $apiKey, $searchEngineId) {
    if (empty($apiKey) || empty($searchEngineId)) {
        return null;
    }
    
    // Create search query
    $query = urlencode($templeName . ' ' . $location . ' Palakkad Kerala temple');
    $url = "https://www.googleapis.com/customsearch/v1?key={$apiKey}&cx={$searchEngineId}&q={$query}&searchType=image&num=1&safe=active";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode === 200) {
        $data = json_decode($response, true);
        
        if (isset($data['items'][0]['link'])) {
            return $data['items'][0]['link'];
        }
    }
    
    return null;
}

// Fetch images for all temples
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['fetch_images'])) {
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $message = 'Invalid security token. Please try again.';
        $messageType = 'danger';
    } else {
    $updated = 0;
    $failed = 0;
    $skipped = 0;
    
    // Get all temples without images
    $temples = $conn->query("SELECT id, name, location FROM temples WHERE (image_url IS NULL OR image_url = '') AND status='active' ORDER BY name ASC")->fetch_all(MYSQLI_ASSOC);
    
    if (empty($googleApiKey) || empty($searchEngineId)) {
        $message = 'Please configure Google API Key and Search Engine ID first!';
        $messageType = 'warning';
    } else {
        foreach ($temples as $temple) {
            // Skip if already has image
            if (!empty($temple['image_url'])) {
                $skipped++;
                continue;
            }
            
            $imageUrl = fetchTempleImageFromGoogle($temple['name'], $temple['location'], $googleApiKey, $searchEngineId);
            
            if ($imageUrl) {
                $stmt = $conn->prepare("UPDATE temples SET image_url = ? WHERE id = ?");
                $stmt->bind_param("si", $imageUrl, $temple['id']);
                if ($stmt->execute()) {
                    $updated++;
                } else {
                    $failed++;
                }
                $stmt->close();
                
                // Add delay to respect API rate limits (100 requests per day for free tier)
                usleep(100000); // 0.1 second delay
            } else {
                $failed++;
            }
        }
        
        $message = "Image fetching completed! Updated: {$updated}, Failed: {$failed}, Skipped: {$skipped}";
        $messageType = $updated > 0 ? 'success' : 'warning';
    }
    }
}

// Get statistics
$totalTemples = $conn->query("SELECT COUNT(*) as count FROM temples WHERE status='active'")->fetch_assoc()['count'];
$templesWithImages = $conn->query("SELECT COUNT(*) as count FROM temples WHERE image_url IS NOT NULL AND image_url != '' AND status='active'")->fetch_assoc()['count'];
$templesWithoutImages = $totalTemples - $templesWithImages;

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fetch Temple Images - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .admin-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 0;
            margin-bottom: 2rem;
        }
        .stat-card {
            border-left: 4px solid #667eea;
        }
    </style>
</head>
<body class="admin-page">
    <?php require_once __DIR__ . '/includes/sidebar.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="admin-header">
                    <div class="container">
                        <h2><i class="bi bi-image"></i> Fetch Temple Images from Google</h2>
                    </div>
                </div>
                
                <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <!-- Statistics -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card stat-card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-building"></i> Total Temples</h5>
                                <h2 class="text-primary"><?php echo $totalTemples; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stat-card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-check-circle text-success"></i> With Images</h5>
                                <h2 class="text-success"><?php echo $templesWithImages; ?></h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card stat-card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><i class="bi bi-x-circle text-warning"></i> Without Images</h5>
                                <h2 class="text-warning"><?php echo $templesWithoutImages; ?></h2>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Configuration -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-gear"></i> Google Custom Search API Configuration</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <h6><i class="bi bi-info-circle"></i> Setup Instructions:</h6>
                            <ol>
                                <li>Go to <a href="https://console.cloud.google.com/" target="_blank">Google Cloud Console</a></li>
                                <li>Create a new project or select an existing one</li>
                                <li>Enable the <strong>Custom Search API</strong></li>
                                <li>Create credentials (API Key) and copy it</li>
                                <li>Go to <a href="https://programmablesearchengine.google.com/" target="_blank">Programmable Search Engine</a></li>
                                <li>Create a new search engine (set to search the entire web)</li>
                                <li>Enable "Image search" in settings</li>
                                <li>Copy the Search Engine ID</li>
                                <li>Edit this file (<code>fetch_temple_images.php</code>) and add your API key and Search Engine ID</li>
                            </ol>
                        </div>
                        
                        <div class="alert alert-warning">
                            <strong>Important Notes:</strong>
                            <ul class="mb-0">
                                <li>Free tier: 100 search queries per day</li>
                                <li>After free tier: $5 per 1,000 queries</li>
                                <li>The script adds a 0.1 second delay between requests to respect rate limits</li>
                                <li>Images are fetched from Google Images and stored as URLs (not downloaded)</li>
                            </ul>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label"><strong>Current Configuration:</strong></label>
                            <div class="form-control">
                                <strong>API Key:</strong> <?php echo !empty($googleApiKey) ? '<span class="text-success">✓ Configured</span>' : '<span class="text-danger">✗ Not configured</span>'; ?><br>
                                <strong>Search Engine ID:</strong> <?php echo !empty($searchEngineId) ? '<span class="text-success">✓ Configured</span>' : '<span class="text-danger">✗ Not configured</span>'; ?>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Fetch Images -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-download"></i> Fetch Images</h5>
                    </div>
                    <div class="card-body">
                        <p class="lead">This will fetch images from Google Images for all temples that don't have images yet.</p>
                        <p><strong>Temples without images:</strong> <?php echo $templesWithoutImages; ?></p>
                        
                        <?php if (empty($googleApiKey) || empty($searchEngineId)): ?>
                        <div class="alert alert-danger">
                            <strong>Configuration Required!</strong> Please configure your Google API Key and Search Engine ID in the PHP file first.
                        </div>
                        <?php else: ?>
                        <form method="POST" onsubmit="return confirm('This will fetch images for <?php echo $templesWithoutImages; ?> temples. This may take several minutes. Continue?');">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken(), ENT_QUOTES, 'UTF-8'); ?>">
                            <button type="submit" name="fetch_images" class="btn btn-primary btn-lg">
                                <i class="bi bi-download"></i> Fetch Images from Google
                            </button>
                            <a href="temples.php" class="btn btn-secondary btn-lg">
                                <i class="bi bi-arrow-left"></i> Back to Temples
                            </a>
                        </form>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Preview -->
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="bi bi-eye"></i> Preview Temples with Images</h5>
                    </div>
                    <div class="card-body">
                        <p>View temples that already have images:</p>
                        <a href="temples.php" class="btn btn-success">
                            <i class="bi bi-building"></i> View All Temples
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


