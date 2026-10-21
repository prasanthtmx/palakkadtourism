<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/security.php';

requireAdminAuth();

$conn = getDBConnection();
$message = '';
$messageType = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $message = 'Invalid security token. Please try again.';
        $messageType = 'danger';
    } elseif (isset($_POST['action'])) {
        if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $location = $_POST['location'] ?? '';
            $address = $_POST['address'] ?? '';
            $image_url = $_POST['image_url'] ?? '';
            $timings = $_POST['timings'] ?? '';
            $contact = $_POST['contact'] ?? '';
            $history = $_POST['history'] ?? '';
            $special_features = $_POST['special_features'] ?? '';
            $best_time_to_visit = $_POST['best_time_to_visit'] ?? '';
            $status = $_POST['status'] ?? 'active';
            
            if ($_POST['action'] === 'add') {
                $stmt = $conn->prepare("INSERT INTO heritage_villages (name, description, location, address, image_url, timings, contact, history, special_features, best_time_to_visit, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssssss", $name, $description, $location, $address, $image_url, $timings, $contact, $history, $special_features, $best_time_to_visit, $status);
                if ($stmt->execute()) {
                    $message = 'Heritage village added successfully!';
                    $messageType = 'success';
                } else {
                    $message = 'Error adding heritage village: ' . $conn->error;
                    $messageType = 'danger';
                }
            } else {
                $id = $_POST['id'] ?? 0;
                $stmt = $conn->prepare("UPDATE heritage_villages SET name=?, description=?, location=?, address=?, image_url=?, timings=?, contact=?, history=?, special_features=?, best_time_to_visit=?, status=? WHERE id=?");
                $stmt->bind_param("sssssssssssi", $name, $description, $location, $address, $image_url, $timings, $contact, $history, $special_features, $best_time_to_visit, $status, $id);
                if ($stmt->execute()) {
                    $message = 'Heritage village updated successfully!';
                    $messageType = 'success';
                } else {
                    $message = 'Error updating heritage village: ' . $conn->error;
                    $messageType = 'danger';
                }
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = $_POST['id'] ?? 0;
            $stmt = $conn->prepare("DELETE FROM heritage_villages WHERE id=?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $message = 'Heritage village deleted successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error deleting heritage village: ' . $conn->error;
                $messageType = 'danger';
            }
        }
    }
}

// Get all heritage villages
$heritageVillages = $conn->query("SELECT * FROM heritage_villages ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);

// Get heritage village for edit
$editVillage = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM heritage_villages WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $editVillage = $result->fetch_assoc();
    }
    $stmt->close();
}

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Heritage Villages - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/assets/css/style.css?v=<?php echo rawurlencode(STYLE_CSS_V); ?>">
</head>
<body class="admin-page">
    <div class="container-fluid">
        <div class="row">
            <?php include __DIR__ . '/includes/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-2 mb-2 border-bottom">
                    <h1 class="h4">Manage Heritage Villages</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#villageModal">
                        <i class="bi bi-plus-circle"></i> Add New Heritage Village
                    </button>
                </div>

                <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show">
                    <?php echo htmlspecialchars($message); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($heritageVillages)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No heritage villages found. Add your first heritage village!</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($heritageVillages as $village): ?>
                            <tr>
                                <td><?php echo $village['id']; ?></td>
                                <td><?php echo htmlspecialchars($village['name']); ?></td>
                                <td><?php echo htmlspecialchars($village['location']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $village['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                        <?php echo ucfirst($village['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="?edit=<?php echo $village['id']; ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this heritage village?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken(), ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $village['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </main>
        </div>
    </div>

    <!-- Heritage Village Modal -->
    <div class="modal fade" id="villageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo $editVillage ? 'Edit Heritage Village' : 'Add New Heritage Village'; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken(), ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="action" value="<?php echo $editVillage ? 'edit' : 'add'; ?>">
                        <?php if ($editVillage): ?>
                        <input type="hidden" name="id" value="<?php echo $editVillage['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Name *</label>
                            <input type="text" class="form-control" name="name" value="<?php echo $editVillage ? htmlspecialchars($editVillage['name']) : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea class="form-control" name="description" rows="3" required><?php echo $editVillage ? htmlspecialchars($editVillage['description']) : ''; ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Location</label>
                                <input type="text" class="form-control" name="location" value="<?php echo $editVillage ? htmlspecialchars($editVillage['location']) : ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact</label>
                                <input type="text" class="form-control" name="contact" value="<?php echo $editVillage ? htmlspecialchars($editVillage['contact']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="2"><?php echo $editVillage ? htmlspecialchars($editVillage['address']) : ''; ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Image URL</label>
                            <input type="url" class="form-control" name="image_url" value="<?php echo $editVillage ? htmlspecialchars($editVillage['image_url']) : ''; ?>" placeholder="https://example.com/image.jpg">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Timings</label>
                                <input type="text" class="form-control" name="timings" value="<?php echo $editVillage ? htmlspecialchars($editVillage['timings']) : ''; ?>" placeholder="6:00 AM - 8:00 PM">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Best Time to Visit</label>
                                <input type="text" class="form-control" name="best_time_to_visit" value="<?php echo $editVillage ? htmlspecialchars($editVillage['best_time_to_visit']) : ''; ?>" placeholder="October to March">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">History</label>
                            <textarea class="form-control" name="history" rows="3"><?php echo $editVillage ? htmlspecialchars($editVillage['history']) : ''; ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Special Features</label>
                            <textarea class="form-control" name="special_features" rows="2"><?php echo $editVillage ? htmlspecialchars($editVillage['special_features']) : ''; ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="active" <?php echo ($editVillage && $editVillage['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo ($editVillage && $editVillage['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><?php echo $editVillage ? 'Update' : 'Add'; ?> Heritage Village</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if ($editVillage): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('villageModal'));
            modal.show();
        });
    </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


