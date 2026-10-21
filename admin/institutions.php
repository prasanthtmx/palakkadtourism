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
            $type = in_array($_POST['type'] ?? '', ['school', 'college']) ? $_POST['type'] : 'school';
            $category = in_array($_POST['category'] ?? '', ['government', 'private', 'aided']) ? $_POST['category'] : 'government';
            $description = sanitizeInput($_POST['description'] ?? '');
            $location = sanitizeInput($_POST['location'] ?? '');
            $address = sanitizeInput($_POST['address'] ?? '');
            $contact = sanitizeInput($_POST['contact'] ?? '');
            $phone = sanitizeInput($_POST['phone'] ?? '');
            $email = sanitizeEmail($_POST['email'] ?? '');
            $website = sanitizeUrl($_POST['website'] ?? '');
            $established_year = sanitizeInt($_POST['established_year'] ?? null, 1800, date('Y'));
            $affiliation = sanitizeInput($_POST['affiliation'] ?? '');
            $courses = sanitizeInput($_POST['courses'] ?? '');
            $facilities = sanitizeInput($_POST['facilities'] ?? '');
            $image_url = sanitizeUrl($_POST['image_url'] ?? '');
            $timings = sanitizeInput($_POST['timings'] ?? '');
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
                    $stmt = $conn->prepare("INSERT INTO institutions (name, type, category, description, location, address, contact, phone, email, website, established_year, affiliation, courses, facilities, image_url, timings, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("ssssssssssissssss", $name, $type, $category, $description, $location, $address, $contact, $phone, $email, $website, $established_year, $affiliation, $courses, $facilities, $image_url, $timings, $status);
                    if ($stmt->execute()) {
                        $message = 'Institution added successfully!';
                        $messageType = 'success';
                    } else {
                        $message = 'Error adding institution: ' . $conn->error;
                        $messageType = 'danger';
                    }
                    $stmt->close();
                } else {
                    $id = sanitizeInt($_POST['id'] ?? 0, 1);
                    if (!$id) {
                        $message = 'Invalid ID!';
                        $messageType = 'danger';
                    } else {
                        $stmt = $conn->prepare("UPDATE institutions SET name=?, type=?, category=?, description=?, location=?, address=?, contact=?, phone=?, email=?, website=?, established_year=?, affiliation=?, courses=?, facilities=?, image_url=?, timings=?, status=? WHERE id=?");
                        $stmt->bind_param("ssssssssssissssssi", $name, $type, $category, $description, $location, $address, $contact, $phone, $email, $website, $established_year, $affiliation, $courses, $facilities, $image_url, $timings, $status, $id);
                        if ($stmt->execute()) {
                            $message = 'Institution updated successfully!';
                            $messageType = 'success';
                        } else {
                            $message = 'Error updating institution: ' . $conn->error;
                            $messageType = 'danger';
                        }
                        $stmt->close();
                    }
                }
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = sanitizeInt($_POST['id'] ?? 0, 1);
            if (!$id) {
                $message = 'Invalid ID!';
                $messageType = 'danger';
            } else {
                $stmt = $conn->prepare("DELETE FROM institutions WHERE id=?");
                $stmt->bind_param("i", $id);
                if ($stmt->execute()) {
                    $message = 'Institution deleted successfully!';
                    $messageType = 'success';
                } else {
                    $message = 'Error deleting institution: ' . $conn->error;
                    $messageType = 'danger';
                }
                $stmt->close();
            }
        }
    }
}

// Get all institutions
$institutions = $conn->query("SELECT * FROM institutions ORDER BY type, category, name ASC")->fetch_all(MYSQLI_ASSOC);

// Get institution for edit
$editInstitution = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM institutions WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $editInstitution = $result->fetch_assoc();
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
    <title>Manage Institutions - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/assets/css/style.css">
</head>
<body class="admin-page">
    <div class="container-fluid">
        <div class="row">
            <?php require_once __DIR__ . '/includes/sidebar.php'; ?>
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-2 pb-2 mb-2 border-bottom">
                    <h1 class="h3">Manage Institutions</h1>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#institutionModal">
                        <i class="bi bi-plus-circle"></i> Add Institution
                    </button>
                </div>

                <?php if ($message): ?>
                <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show" role="alert">
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
                                <th>Category</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($institutions)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No institutions found. Add your first institution!</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($institutions as $institution): ?>
                            <tr>
                                <td><?php echo $institution['id']; ?></td>
                                <td><?php echo htmlspecialchars($institution['name']); ?></td>
                                <td><span class="badge bg-info"><?php echo ucfirst($institution['type']); ?></span></td>
                                <td><span class="badge bg-secondary"><?php echo ucfirst($institution['category']); ?></span></td>
                                <td><?php echo htmlspecialchars($institution['location']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $institution['status'] === 'active' ? 'success' : 'danger'; ?>">
                                        <?php echo ucfirst($institution['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning" onclick="editInstitution(<?php echo htmlspecialchars(json_encode($institution)); ?>)">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this institution?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $institution['id']; ?>">
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

    <!-- Institution Modal -->
    <div class="modal fade" id="institutionModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo $editInstitution ? 'Edit' : 'Add'; ?> Institution</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
                        <input type="hidden" name="action" value="<?php echo $editInstitution ? 'edit' : 'add'; ?>">
                        <?php if ($editInstitution): ?>
                        <input type="hidden" name="id" value="<?php echo $editInstitution['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Name *</label>
                            <input type="text" class="form-control" name="name" value="<?php echo $editInstitution ? htmlspecialchars($editInstitution['name']) : ''; ?>" required>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Type *</label>
                                <select class="form-control" name="type" required>
                                    <option value="school" <?php echo ($editInstitution && $editInstitution['type'] === 'school') ? 'selected' : ''; ?>>School</option>
                                    <option value="college" <?php echo ($editInstitution && $editInstitution['type'] === 'college') ? 'selected' : ''; ?>>College</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category *</label>
                                <select class="form-control" name="category" required>
                                    <option value="government" <?php echo ($editInstitution && $editInstitution['category'] === 'government') ? 'selected' : ''; ?>>Government</option>
                                    <option value="private" <?php echo ($editInstitution && $editInstitution['category'] === 'private') ? 'selected' : ''; ?>>Private</option>
                                    <option value="aided" <?php echo ($editInstitution && $editInstitution['category'] === 'aided') ? 'selected' : ''; ?>>Aided</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"><?php echo $editInstitution ? htmlspecialchars($editInstitution['description']) : ''; ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Location</label>
                                <input type="text" class="form-control" name="location" value="<?php echo $editInstitution ? htmlspecialchars($editInstitution['location']) : ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Established Year</label>
                                <input type="number" class="form-control" name="established_year" value="<?php echo $editInstitution ? $editInstitution['established_year'] : ''; ?>" min="1800" max="<?php echo date('Y'); ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="2"><?php echo $editInstitution ? htmlspecialchars($editInstitution['address'] ?? '') : ''; ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Contact Person</label>
                                <input type="text" class="form-control" name="contact" value="<?php echo $editInstitution ? htmlspecialchars($editInstitution['contact']) : ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" name="phone" value="<?php echo $editInstitution ? htmlspecialchars($editInstitution['phone']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" name="email" value="<?php echo $editInstitution ? htmlspecialchars($editInstitution['email']) : ''; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Website</label>
                                <input type="url" class="form-control" name="website" value="<?php echo $editInstitution ? htmlspecialchars($editInstitution['website']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Affiliation</label>
                            <input type="text" class="form-control" name="affiliation" value="<?php echo $editInstitution ? htmlspecialchars($editInstitution['affiliation']) : ''; ?>" placeholder="e.g., University of Calicut, CBSE">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Courses</label>
                            <textarea class="form-control" name="courses" rows="2" placeholder="e.g., BA, BSc, BCom, MBBS"><?php echo $editInstitution ? htmlspecialchars($editInstitution['courses']) : ''; ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Facilities</label>
                            <textarea class="form-control" name="facilities" rows="2" placeholder="e.g., Library, Laboratory, Sports, Hostel"><?php echo $editInstitution ? htmlspecialchars($editInstitution['facilities']) : ''; ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Image URL</label>
                            <input type="url" class="form-control" name="image_url" value="<?php echo $editInstitution ? htmlspecialchars($editInstitution['image_url']) : ''; ?>" placeholder="https://example.com/image.jpg">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Timings</label>
                                <input type="text" class="form-control" name="timings" value="<?php echo $editInstitution ? htmlspecialchars($editInstitution['timings']) : ''; ?>" placeholder="e.g., 9:00 AM - 4:00 PM">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Status</label>
                                <select class="form-control" name="status">
                                    <option value="active" <?php echo (!$editInstitution || $editInstitution['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                                    <option value="inactive" <?php echo ($editInstitution && $editInstitution['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><?php echo $editInstitution ? 'Update' : 'Add'; ?> Institution</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editInstitution(institution) {
            const modal = new bootstrap.Modal(document.getElementById('institutionModal'));
            const form = document.querySelector('#institutionModal form');
            form.querySelector('input[name="action"]').value = 'edit';
            form.querySelector('input[name="id"]').value = institution.id;
            form.querySelector('input[name="name"]').value = institution.name || '';
            form.querySelector('select[name="type"]').value = institution.type || 'school';
            form.querySelector('select[name="category"]').value = institution.category || 'government';
            form.querySelector('textarea[name="description"]').value = institution.description || '';
            form.querySelector('input[name="location"]').value = institution.location || '';
            form.querySelector('input[name="address"]').value = institution.address || '';
            form.querySelector('input[name="contact"]').value = institution.contact || '';
            form.querySelector('input[name="phone"]').value = institution.phone || '';
            form.querySelector('input[name="email"]').value = institution.email || '';
            form.querySelector('input[name="website"]').value = institution.website || '';
            form.querySelector('input[name="established_year"]').value = institution.established_year || '';
            form.querySelector('input[name="affiliation"]').value = institution.affiliation || '';
            form.querySelector('textarea[name="courses"]').value = institution.courses || '';
            form.querySelector('textarea[name="facilities"]').value = institution.facilities || '';
            form.querySelector('input[name="image_url"]').value = institution.image_url || '';
            form.querySelector('input[name="timings"]').value = institution.timings || '';
            form.querySelector('select[name="status"]').value = institution.status || 'active';
            form.querySelector('.modal-title').textContent = 'Edit Institution';
            form.querySelector('button[type="submit"]').textContent = 'Update Institution';
            modal.show();
        }
        
        // Reset form when modal is closed
        document.getElementById('institutionModal').addEventListener('hidden.bs.modal', function () {
            const form = this.querySelector('form');
            form.reset();
            form.querySelector('input[name="action"]').value = 'add';
            form.querySelector('.modal-title').textContent = 'Add Institution';
            form.querySelector('button[type="submit"]').textContent = 'Add Institution';
            if (form.querySelector('input[name="id"]')) {
                form.querySelector('input[name="id"]').remove();
            }
        });
    </script>
</body>
</html>

