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
            $type = $_POST['type'] ?? 'private';
            $description = $_POST['description'] ?? '';
            $location = $_POST['location'] ?? '';
            $address = $_POST['address'] ?? '';
            $contact = $_POST['contact'] ?? '';
            $phone = $_POST['phone'] ?? '';
            $email = $_POST['email'] ?? '';
            $services = $_POST['services'] ?? '';
            $timings = $_POST['timings'] ?? '';
            $image_url = $_POST['image_url'] ?? '';
            $website = $_POST['website'] ?? '';
            $status = $_POST['status'] ?? 'active';
            
            if ($_POST['action'] === 'add') {
                $stmt = $conn->prepare("INSERT INTO hospitals (name, type, description, location, address, contact, phone, email, services, timings, image_url, website, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("sssssssssssss", $name, $type, $description, $location, $address, $contact, $phone, $email, $services, $timings, $image_url, $website, $status);
                if ($stmt->execute()) {
                    $message = 'Hospital added successfully!';
                    $messageType = 'success';
                } else {
                    $message = 'Error adding hospital: ' . $conn->error;
                    $messageType = 'danger';
                }
            } else {
                $id = $_POST['id'] ?? 0;
                $stmt = $conn->prepare("UPDATE hospitals SET name=?, type=?, description=?, location=?, address=?, contact=?, phone=?, email=?, services=?, timings=?, image_url=?, website=?, status=? WHERE id=?");
                $stmt->bind_param("sssssssssssssi", $name, $type, $description, $location, $address, $contact, $phone, $email, $services, $timings, $image_url, $website, $status, $id);
                if ($stmt->execute()) {
                    $message = 'Hospital updated successfully!';
                    $messageType = 'success';
                } else {
                    $message = 'Error updating hospital: ' . $conn->error;
                    $messageType = 'danger';
                }
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = $_POST['id'] ?? 0;
            $stmt = $conn->prepare("DELETE FROM hospitals WHERE id=?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $message = 'Hospital deleted successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error deleting hospital: ' . $conn->error;
                $messageType = 'danger';
            }
        }
    }
}

// Get all hospitals
$hospitals = $conn->query("SELECT * FROM hospitals ORDER BY type, name ASC")->fetch_all(MYSQLI_ASSOC);

// Get hospital for edit
$editHospital = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM hospitals WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $editHospital = $result->fetch_assoc();
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
    <title>Manage Hospitals - Admin</title>
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
                    <h1 class="h4">Manage Hospitals</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#hospitalModal">
                        <i class="bi bi-plus-circle"></i> Add New Hospital
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
                                <th>Type</th>
                                <th>Location</th>
                                <th>Contact</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($hospitals)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No hospitals found. Add your first hospital!</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($hospitals as $hospital): ?>
                            <tr>
                                <td><?php echo $hospital['id']; ?></td>
                                <td><?php echo htmlspecialchars($hospital['name']); ?></td>
                                <td><span class="badge bg-info"><?php echo ucfirst($hospital['type']); ?></span></td>
                                <td><?php echo htmlspecialchars($hospital['location']); ?></td>
                                <td><?php echo htmlspecialchars($hospital['phone'] ?: $hospital['contact']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $hospital['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                        <?php echo ucfirst($hospital['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="?edit=<?php echo $hospital['id']; ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this hospital?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken(), ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $hospital['id']; ?>">
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

    <!-- Hospital Modal -->
    <div class="modal fade" id="hospitalModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo $editHospital ? 'Edit Hospital' : 'Add New Hospital'; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken(), ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="action" value="<?php echo $editHospital ? 'edit' : 'add'; ?>">
                        <?php if ($editHospital): ?>
                        <input type="hidden" name="id" value="<?php echo $editHospital['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label">Hospital Name *</label>
                                <input type="text" class="form-control" name="name" value="<?php echo $editHospital ? htmlspecialchars($editHospital['name']) : ''; ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Type *</label>
                                <select class="form-select" name="type" required>
                                    <option value="government" <?php echo ($editHospital && $editHospital['type'] === 'government') ? 'selected' : ''; ?>>Government</option>
                                    <option value="private" <?php echo ($editHospital && $editHospital['type'] === 'private') ? 'selected' : ''; ?>>Private</option>
                                    <option value="ayurveda" <?php echo ($editHospital && $editHospital['type'] === 'ayurveda') ? 'selected' : ''; ?>>Ayurveda</option>
                                    <option value="homeopathy" <?php echo ($editHospital && $editHospital['type'] === 'homeopathy') ? 'selected' : ''; ?>>Homeopathy</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"><?php echo $editHospital ? htmlspecialchars($editHospital['description']) : ''; ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Location</label>
                                <input type="text" class="form-control" name="location" value="<?php echo $editHospital ? htmlspecialchars($editHospital['location']) : ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone" value="<?php echo $editHospital ? htmlspecialchars($editHospital['phone']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="2"><?php echo $editHospital ? htmlspecialchars($editHospital['address']) : ''; ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Person</label>
                                <input type="text" class="form-control" name="contact" value="<?php echo $editHospital ? htmlspecialchars($editHospital['contact']) : ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="<?php echo $editHospital ? htmlspecialchars($editHospital['email']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Services</label>
                            <textarea class="form-control" name="services" rows="3" placeholder="List services offered (one per line or comma separated)"><?php echo $editHospital ? htmlspecialchars($editHospital['services']) : ''; ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Timings</label>
                                <input type="text" class="form-control" name="timings" value="<?php echo $editHospital ? htmlspecialchars($editHospital['timings']) : ''; ?>" placeholder="24/7 or 9:00 AM - 5:00 PM">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Website</label>
                                <input type="url" class="form-control" name="website" value="<?php echo $editHospital ? htmlspecialchars($editHospital['website']) : ''; ?>" placeholder="https://example.com">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Image URL</label>
                            <input type="url" class="form-control" name="image_url" value="<?php echo $editHospital ? htmlspecialchars($editHospital['image_url']) : ''; ?>" placeholder="https://example.com/image.jpg">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="active" <?php echo ($editHospital && $editHospital['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo ($editHospital && $editHospital['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><?php echo $editHospital ? 'Update' : 'Add'; ?> Hospital</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if ($editHospital): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('hospitalModal'));
            modal.show();
        });
    </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


