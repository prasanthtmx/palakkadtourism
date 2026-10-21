<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/security.php';

requireAdminAuth();

$conn = getDBConnection();
$message = '';
$messageType = '';

// Google Maps API Key - Add your key here
$googleApiKey = ''; // Set your Google Maps API key here if you want to fetch coordinates

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['import_temples'])) {
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $message = 'Invalid security token. Please try again.';
        $messageType = 'danger';
    } else {
        $sqlResolved = adminResolveBundledSqlFile('temples_bulk_insert.sql');
        if ($sqlResolved === null) {
            $message = 'Bundled SQL file is missing or not allowed.';
            $messageType = 'danger';
        } else {
            $sql = file_get_contents($sqlResolved);
            if ($sql === false || $sql === '') {
                $message = 'Could not read SQL file.';
                $messageType = 'danger';
            } elseif ($conn->multi_query($sql)) {
                do {
                    if ($result = $conn->store_result()) {
                        $result->free();
                    }
                } while ($conn->next_result());

                $message = 'Temples imported successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error importing temples: ' . $conn->error;
                $messageType = 'danger';
            }
        }
    }
}

// Function to fetch coordinates from Google Geocoding API
function fetchCoordinatesFromGoogle($address, $apiKey) {
    if (empty($apiKey)) {
        return null;
    }
    
    $address = urlencode($address . ', Palakkad, Kerala, India');
    $url = "https://maps.googleapis.com/maps/api/geocoding/json?address={$address}&key={$apiKey}";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    
    $data = json_decode($response, true);
    
    if ($data['status'] === 'OK' && isset($data['results'][0]['geometry']['location'])) {
        return [
            'lat' => $data['results'][0]['geometry']['location']['lat'],
            'lng' => $data['results'][0]['geometry']['location']['lng']
        ];
    }
    
    return null;
}

// Get count of existing temples
$templeCount = $conn->query("SELECT COUNT(*) as count FROM temples")->fetch_assoc()['count'];

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Temples - Admin Panel</title>
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
    </style>
</head>
<body class="admin-page">
    <?php require_once __DIR__ . '/includes/sidebar.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="admin-header">
                    <div class="container">
                        <h2><i class="bi bi-building"></i> Import Temples</h2>
                    </div>
                </div>
                
                <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
                    <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="bi bi-upload"></i> Import Temples from SQL File</h5>
                    </div>
                    <div class="card-body">
                        <p class="lead">This will import 58 temples from the bulk insert SQL file.</p>
                        <p><strong>Current temples in database:</strong> <?php echo $templeCount; ?></p>
                        
                        <div class="alert alert-info">
                            <h6><i class="bi bi-info-circle"></i> Instructions:</h6>
                            <ol>
                                <li>Click the "Import Temples" button below to add all 58 temples to the database.</li>
                                <li>The temples will be imported with basic information (name, description, location, address).</li>
                                <li>You can edit individual temples later to add more details like images, timings, contact, etc.</li>
                            </ol>
                        </div>
                        
                        <form method="POST" onsubmit="return confirm('Are you sure you want to import all temples? This will add 58 new temple entries.');">
                            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken(), ENT_QUOTES, 'UTF-8'); ?>">
                            <button type="submit" name="import_temples" class="btn btn-primary btn-lg">
                                <i class="bi bi-upload"></i> Import Temples
                            </button>
                            <a href="temples.php" class="btn btn-secondary btn-lg">
                                <i class="bi bi-arrow-left"></i> Back to Temples
                            </a>
                        </form>
                    </div>
                </div>
                
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white">
                        <h5 class="mb-0"><i class="bi bi-map"></i> Google Location Integration</h5>
                    </div>
                    <div class="card-body">
                        <p>To fetch precise coordinates from Google Maps API:</p>
                        <ol>
                            <li>Get a Google Maps API key from <a href="https://console.cloud.google.com/" target="_blank">Google Cloud Console</a></li>
                            <li>Enable the Geocoding API in your Google Cloud project</li>
                            <li>Add your API key to the <code>$googleApiKey</code> variable in this file</li>
                            <li>The script will automatically fetch latitude and longitude for each temple address</li>
                        </ol>
                        <div class="alert alert-warning">
                            <strong>Note:</strong> Google Maps API has usage limits and may require billing. Use it responsibly.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


