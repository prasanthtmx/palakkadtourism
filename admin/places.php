<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/security.php';

requireAdminAuth();

$conn = getDBConnection();
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $message = 'Invalid security token. Please try again.';
        $messageType = 'danger';
    } elseif (isset($_POST['action'])) {
        if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $category = $_POST['category'] ?? '';
            $location = $_POST['location'] ?? '';
            $address = $_POST['address'] ?? '';
            $image_url = $_POST['image_url'] ?? '';
            $best_time_to_visit = $_POST['best_time_to_visit'] ?? '';
            $entry_fee = $_POST['entry_fee'] ?? '';
            $timings = $_POST['timings'] ?? '';
            $contact = $_POST['contact'] ?? '';
            $status = $_POST['status'] ?? 'active';
            
            if ($_POST['action'] === 'add') {
                $stmt = $conn->prepare("INSERT INTO places (name, description, category, location, address, image_url, best_time_to_visit, entry_fee, timings, contact, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssssss", $name, $description, $category, $location, $address, $image_url, $best_time_to_visit, $entry_fee, $timings, $contact, $status);
                if ($stmt->execute()) {
                    $message = 'Place added successfully!';
                    $messageType = 'success';
                } else {
                    $message = 'Error adding place: ' . $conn->error;
                    $messageType = 'danger';
                }
                $stmt->close();
            } else {
                $id = sanitizeInt($_POST['id'] ?? 0, 1);
                if (!$id) {
                    $message = 'Invalid ID!';
                    $messageType = 'danger';
                } else {
                    $stmt = $conn->prepare("UPDATE places SET name=?, description=?, category=?, location=?, address=?, image_url=?, best_time_to_visit=?, entry_fee=?, timings=?, contact=?, status=? WHERE id=?");
                    $stmt->bind_param("sssssssssssi", $name, $description, $category, $location, $address, $image_url, $best_time_to_visit, $entry_fee, $timings, $contact, $status, $id);
                    if ($stmt->execute()) {
                        $message = 'Place updated successfully!';
                        $messageType = 'success';
                    } else {
                        $message = 'Error updating place: ' . $conn->error;
                        $messageType = 'danger';
                    }
                    $stmt->close();
                }
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = sanitizeInt($_POST['id'] ?? 0, 1);
            if (!$id) {
                $message = 'Invalid ID!';
                $messageType = 'danger';
            } else {
                $stmt = $conn->prepare("DELETE FROM places WHERE id=?");
                $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $message = 'Place deleted successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error deleting place: ' . $conn->error;
                $messageType = 'danger';
            }
            $stmt->close();
            }
        }
    }
}

$places = $conn->query("SELECT * FROM places ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);

$editPlace = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM places WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $editPlace = $result->fetch_assoc();
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
    <title>Manage Places - Admin</title>
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
                    <h1 class="h4">Manage Places</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#placeModal">
                        <i class="bi bi-plus-circle"></i> Add New Place
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
                                <th>Category</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($places)): ?>
                            <tr>
                                <td colspan="6" class="text-center">No places found. Add your first place!</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($places as $place): ?>
                            <tr>
                                <td><?php echo $place['id']; ?></td>
                                <td><?php echo htmlspecialchars($place['name']); ?></td>
                                <td><?php echo htmlspecialchars($place['category']); ?></td>
                                <td><?php echo htmlspecialchars($place['location']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $place['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                        <?php echo ucfirst($place['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="?edit=<?php echo $place['id']; ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this place?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken(), ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $place['id']; ?>">
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

    <!-- Place Modal -->
    <div class="modal fade" id="placeModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo $editPlace ? 'Edit Place' : 'Add New Place'; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken(), ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="action" value="<?php echo $editPlace ? 'edit' : 'add'; ?>">
                        <?php if ($editPlace): ?>
                        <input type="hidden" name="id" value="<?php echo $editPlace['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Name *</label>
                            <input type="text" class="form-control" name="name" value="<?php echo $editPlace ? htmlspecialchars($editPlace['name']) : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea class="form-control" name="description" rows="3" required><?php echo $editPlace ? htmlspecialchars($editPlace['description']) : ''; ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <input type="text" class="form-control" name="category" value="<?php echo $editPlace ? htmlspecialchars($editPlace['category']) : ''; ?>" placeholder="Historical, Nature & Wildlife, etc.">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Location</label>
                                <input type="text" class="form-control" name="location" value="<?php echo $editPlace ? htmlspecialchars($editPlace['location']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="2"><?php echo $editPlace ? htmlspecialchars($editPlace['address']) : ''; ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Image URL</label>
                            <input type="url" class="form-control" name="image_url" value="<?php echo $editPlace ? htmlspecialchars($editPlace['image_url']) : ''; ?>" placeholder="https://example.com/image.jpg">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Best Time to Visit</label>
                                <input type="text" class="form-control" name="best_time_to_visit" value="<?php echo $editPlace ? htmlspecialchars($editPlace['best_time_to_visit']) : ''; ?>" placeholder="October to March">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Entry Fee</label>
                                <input type="text" class="form-control" name="entry_fee" value="<?php echo $editPlace ? htmlspecialchars($editPlace['entry_fee']) : ''; ?>" placeholder="Rs. 50 or Free">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Timings</label>
                                <input type="text" class="form-control" name="timings" value="<?php echo $editPlace ? htmlspecialchars($editPlace['timings']) : ''; ?>" placeholder="8:00 AM - 6:00 PM">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact</label>
                                <input type="text" class="form-control" name="contact" value="<?php echo $editPlace ? htmlspecialchars($editPlace['contact']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="active" <?php echo ($editPlace && $editPlace['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo ($editPlace && $editPlace['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><?php echo $editPlace ? 'Update' : 'Add'; ?> Place</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if ($editPlace): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('placeModal'));
            modal.show();
        });
    </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


