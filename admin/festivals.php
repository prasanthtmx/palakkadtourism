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
            $type = $_POST['type'] ?? '';
            $start_date = $_POST['start_date'] ?? '';
            $end_date = $_POST['end_date'] ?? '';
            $location = $_POST['location'] ?? '';
            $image_url = $_POST['image_url'] ?? '';
            $highlights = $_POST['highlights'] ?? '';
            $cultural_significance = $_POST['cultural_significance'] ?? '';
            $status = $_POST['status'] ?? 'active';
            
            if ($_POST['action'] === 'add') {
                $stmt = $conn->prepare("INSERT INTO festivals (name, description, type, start_date, end_date, location, image_url, highlights, cultural_significance, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssssss", $name, $description, $type, $start_date, $end_date, $location, $image_url, $highlights, $cultural_significance, $status);
                if ($stmt->execute()) {
                    $message = 'Festival added successfully!';
                    $messageType = 'success';
                } else {
                    $message = 'Error adding festival: ' . $conn->error;
                    $messageType = 'danger';
                }
            } else {
                $id = $_POST['id'] ?? 0;
                $stmt = $conn->prepare("UPDATE festivals SET name=?, description=?, type=?, start_date=?, end_date=?, location=?, image_url=?, highlights=?, cultural_significance=?, status=? WHERE id=?");
                $stmt->bind_param("ssssssssssi", $name, $description, $type, $start_date, $end_date, $location, $image_url, $highlights, $cultural_significance, $status, $id);
                if ($stmt->execute()) {
                    $message = 'Festival updated successfully!';
                    $messageType = 'success';
                } else {
                    $message = 'Error updating festival: ' . $conn->error;
                    $messageType = 'danger';
                }
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = $_POST['id'] ?? 0;
            $stmt = $conn->prepare("DELETE FROM festivals WHERE id=?");
            $stmt->bind_param("i", $id);
            if ($stmt->execute()) {
                $message = 'Festival deleted successfully!';
                $messageType = 'success';
            } else {
                $message = 'Error deleting festival: ' . $conn->error;
                $messageType = 'danger';
            }
        }
    }
}

$festivals = $conn->query("SELECT * FROM festivals ORDER BY start_date ASC")->fetch_all(MYSQLI_ASSOC);

$editFestival = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $stmt = $conn->prepare("SELECT * FROM festivals WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $editFestival = $result->fetch_assoc();
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
    <title>Manage Festivals - Admin</title>
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
                    <h1 class="h4">Manage Festivals</h1>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#festivalModal">
                        <i class="bi bi-plus-circle"></i> Add New Festival
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
                                <th>Date</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($festivals)): ?>
                            <tr>
                                <td colspan="7" class="text-center">No festivals found. Add your first festival!</td>
                            </tr>
                            <?php else: ?>
                            <?php foreach ($festivals as $festival): ?>
                            <tr>
                                <td><?php echo $festival['id']; ?></td>
                                <td><?php echo htmlspecialchars($festival['name']); ?></td>
                                <td><?php echo htmlspecialchars($festival['type']); ?></td>
                                <td>
                                    <?php 
                                    $startDate = new DateTime($festival['start_date']);
                                    echo $startDate->format('M d, Y');
                                    if ($festival['start_date'] != $festival['end_date']) {
                                        $endDate = new DateTime($festival['end_date']);
                                        echo ' - ' . $endDate->format('M d, Y');
                                    }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($festival['location']); ?></td>
                                <td>
                                    <span class="badge bg-<?php echo $festival['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                        <?php echo ucfirst($festival['status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="?edit=<?php echo $festival['id']; ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this festival?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken(), ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo $festival['id']; ?>">
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

    <!-- Festival Modal -->
    <div class="modal fade" id="festivalModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo $editFestival ? 'Edit Festival' : 'Add New Festival'; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken(), ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="action" value="<?php echo $editFestival ? 'edit' : 'add'; ?>">
                        <?php if ($editFestival): ?>
                        <input type="hidden" name="id" value="<?php echo $editFestival['id']; ?>">
                        <?php endif; ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Name *</label>
                            <input type="text" class="form-control" name="name" value="<?php echo $editFestival ? htmlspecialchars($editFestival['name']) : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Description *</label>
                            <textarea class="form-control" name="description" rows="3" required><?php echo $editFestival ? htmlspecialchars($editFestival['description']) : ''; ?></textarea>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Type</label>
                                <input type="text" class="form-control" name="type" value="<?php echo $editFestival ? htmlspecialchars($editFestival['type']) : ''; ?>" placeholder="Religious, Harvest Festival, etc.">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Location</label>
                                <input type="text" class="form-control" name="location" value="<?php echo $editFestival ? htmlspecialchars($editFestival['location']) : ''; ?>">
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Start Date *</label>
                                <input type="date" class="form-control" name="start_date" value="<?php echo $editFestival ? $editFestival['start_date'] : ''; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">End Date *</label>
                                <input type="date" class="form-control" name="end_date" value="<?php echo $editFestival ? $editFestival['end_date'] : ''; ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Image URL</label>
                            <input type="url" class="form-control" name="image_url" value="<?php echo $editFestival ? htmlspecialchars($editFestival['image_url']) : ''; ?>" placeholder="https://example.com/image.jpg">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Highlights</label>
                            <textarea class="form-control" name="highlights" rows="2"><?php echo $editFestival ? htmlspecialchars($editFestival['highlights']) : ''; ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Cultural Significance</label>
                            <textarea class="form-control" name="cultural_significance" rows="3"><?php echo $editFestival ? htmlspecialchars($editFestival['cultural_significance']) : ''; ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="active" <?php echo ($editFestival && $editFestival['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo ($editFestival && $editFestival['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-warning"><?php echo $editFestival ? 'Update' : 'Add'; ?> Festival</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if ($editFestival): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('festivalModal'));
            modal.show();
        });
    </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>


