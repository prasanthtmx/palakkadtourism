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
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $message = 'Invalid security token. Please try again.';
        $messageType = 'danger';
    } elseif (isset($_POST['action'])) {
        if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
            $name = sanitizeInput($_POST['name'] ?? '');
            $description = sanitizeInput($_POST['description'] ?? '');
            $location = sanitizeInput($_POST['location'] ?? '');
            $address = sanitizeInput($_POST['address'] ?? '');
            $image_url = sanitizeUrl($_POST['image_url'] ?? '');
            $timings = sanitizeInput($_POST['timings'] ?? '');
            $contact = sanitizeInput($_POST['contact'] ?? '');
            $history = sanitizeInput($_POST['history'] ?? '');
            $special_features = sanitizeInput($_POST['special_features'] ?? '');
            $status = in_array($_POST['status'] ?? 'active', ['active', 'inactive']) ? $_POST['status'] : 'active';
            
            // Validate required fields
            if (empty($name)) {
                $message = 'Name is required!';
                $messageType = 'danger';
            } elseif (!empty($image_url) && !validateImageUrl($image_url)) {
                $message = 'Invalid image URL format!';
                $messageType = 'danger';
            } else {
                if ($_POST['action'] === 'add') {
                    $stmt = $conn->prepare("INSERT INTO temples (name, description, location, address, image_url, timings, contact, history, special_features, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssssssss", $name, $description, $location, $address, $image_url, $timings, $contact, $history, $special_features, $status);
                    if ($stmt->execute()) {
                        $message = 'Temple added successfully!';
                        $messageType = 'success';
                    } else {
                        $message = 'Error adding temple: ' . $conn->error;
                        $messageType = 'danger';
                    }
                    $stmt->close();
                } else {
                    $id = sanitizeInt($_POST['id'] ?? 0, 1);
                    if (!$id) {
                        $message = 'Invalid ID!';
                        $messageType = 'danger';
                    } else {
                        $stmt = $conn->prepare("UPDATE temples SET name=?, description=?, location=?, address=?, image_url=?, timings=?, contact=?, history=?, special_features=?, status=? WHERE id=?");
                        $stmt->bind_param("ssssssssssi", $name, $description, $location, $address, $image_url, $timings, $contact, $history, $special_features, $status, $id);
                        if ($stmt->execute()) {
                            $message = 'Temple updated successfully!';
                            $messageType = 'success';
                        } else {
                            $message = 'Error updating temple: ' . $conn->error;
                            $messageType = 'danger';
                        }
                        $stmt->close();
                    }
                }
            }
        }
        if ($_POST['action'] === 'delete') {
            $id = sanitizeInt($_POST['id'] ?? 0, 1);
            if (!$id) {
                $message = 'Invalid ID!';
                $messageType = 'danger';
            } else {
                $stmt = $conn->prepare("DELETE FROM temples WHERE id=?");
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    $message = 'Temple deleted successfully!';
                    $messageType = 'success';
                } else {
                    $message = 'Error deleting temple: ' . $conn->error;
                    $messageType = 'danger';
                }
                $stmt->close();
            }
    }
}

// Get all temples
$temples = $conn->query("SELECT * FROM temples ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);

// Get temple for edit
$editTemple = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM temples WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $editTemple = $result->fetch_assoc();
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
    <title>Manage Temples - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/assets/css/style.css">
</head>
<body class="admin-page">
    <div class="container-fluid">
        <div class="row">
            <?php include __DIR__ . '/includes/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-2 mb-2 border-bottom">
                    <h1 class="h4">Manage Temples</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#templeModal">
                        <i class="bi bi-plus-circle"></i> Add New Temple
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
                            <?php if (empty($temples)): ?>
                            <tr>
                                <td colspan="5" class="text-center">No temples found. Add your first temple!</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($temples as $temple): ?>
                            <tr>
                                <td><?php echo $temple['id']; ?></td>
                                <td><?php echo htmlspecialchars($temple['name']); ?></td>
                                <td><?php echo htmlspecialchars($temple['location']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $temple['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                        <?php echo ucfirst($temple['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="?edit=<?php echo $temple['id']; ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this temple?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $temple['id']; ?>">
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

    <!-- Temple Modal -->
    <div class="modal fade" id="templeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo $editTemple ? 'Edit Temple' : 'Add New Temple'; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <input type="hidden" name="action" value="<?php echo $editTemple ? 'edit' : 'add'; ?>">
                        <?php if ($editTemple): ?>
                        <input type="hidden" name="id" value="<?php echo $editTemple['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Name *</label>
                            <input type="text" class="form-control" name="name" value="<?php echo $editTemple ? htmlspecialchars($editTemple['name']) : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea class="form-control" name="description" rows="3" required><?php echo $editTemple ? htmlspecialchars($editTemple['description']) : ''; ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Location</label>
                                <input type="text" class="form-control" name="location" value="<?php echo $editTemple ? htmlspecialchars($editTemple['location']) : ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact</label>
                                <input type="text" class="form-control" name="contact" value="<?php echo $editTemple ? htmlspecialchars($editTemple['contact']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="2"><?php echo $editTemple ? htmlspecialchars($editTemple['address']) : ''; ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Image URL</label>
                            <input type="url" class="form-control" name="image_url" value="<?php echo $editTemple ? htmlspecialchars($editTemple['image_url']) : ''; ?>" placeholder="https://example.com/image.jpg">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Timings</label>
                            <input type="text" class="form-control" name="timings" value="<?php echo $editTemple ? htmlspecialchars($editTemple['timings']) : ''; ?>" placeholder="6:00 AM - 8:00 PM">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">History</label>
                            <textarea class="form-control" name="history" rows="3"><?php echo $editTemple ? htmlspecialchars($editTemple['history']) : ''; ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Special Features</label>
                            <textarea class="form-control" name="special_features" rows="2"><?php echo $editTemple ? htmlspecialchars($editTemple['special_features']) : ''; ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="active" <?php echo ($editTemple && $editTemple['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo ($editTemple && $editTemple['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><?php echo $editTemple ? 'Update' : 'Add'; ?> Temple</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if ($editTemple): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('templeModal'));
            modal.show();
        });
    </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


