<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../includes/district_officials.php';

requireAdminAuth();

$conn = getDBConnection();
ensureDistrictOfficialsTable($conn);

$officeTypes = districtOfficialOfficeTypes();
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $message = 'Invalid security token. Please try again.';
        $messageType = 'danger';
    } elseif (isset($_POST['action'])) {
        if ($_POST['action'] === 'add' || $_POST['action'] === 'edit') {
            $name = trim((string) ($_POST['name'] ?? ''));
            $officeType = (string) ($_POST['office_type'] ?? '');
            $landline = trim((string) ($_POST['landline'] ?? ''));
            $address = trim((string) ($_POST['address'] ?? ''));
            $area = trim((string) ($_POST['area'] ?? ''));
            $status = (($_POST['status'] ?? 'active') === 'inactive') ? 'inactive' : 'active';

            if ($name === '' || !isset($officeTypes[$officeType])) {
                $message = 'Please enter a name and choose an office type.';
                $messageType = 'danger';
            } elseif ($_POST['action'] === 'add') {
                $stmt = $conn->prepare('INSERT INTO district_officials (office_type, name, landline, address, area, status) VALUES (?, ?, ?, ?, ?, ?)');
                $stmt->bind_param('ssssss', $officeType, $name, $landline, $address, $area, $status);
                if ($stmt->execute()) {
                    $message = 'Official added. The latest record of this type is shown on the homepage.';
                    $messageType = 'success';
                } else {
                    $message = 'Could not add the official. Please try again.';
                    $messageType = 'danger';
                }
                $stmt->close();
            } else {
                $id = (int) ($_POST['id'] ?? 0);
                $stmt = $conn->prepare('UPDATE district_officials SET office_type=?, name=?, landline=?, address=?, area=?, status=? WHERE id=?');
                $stmt->bind_param('ssssssi', $officeType, $name, $landline, $address, $area, $status, $id);
                if ($id > 0 && $stmt->execute()) {
                    $message = 'Official updated.';
                    $messageType = 'success';
                } else {
                    $message = 'Could not update the official. Please try again.';
                    $messageType = 'danger';
                }
                $stmt->close();
            }
        } elseif ($_POST['action'] === 'delete') {
            $id = (int) ($_POST['id'] ?? 0);
            $stmt = $conn->prepare('DELETE FROM district_officials WHERE id=?');
            $stmt->bind_param('i', $id);
            if ($id > 0 && $stmt->execute()) {
                $message = 'Official deleted.';
                $messageType = 'success';
            } else {
                $message = 'Could not delete the official.';
                $messageType = 'danger';
            }
            $stmt->close();
        }
    }
}

$officials = [];
$result = $conn->query('SELECT * FROM district_officials ORDER BY id DESC');
if ($result) {
    $officials = $result->fetch_all(MYSQLI_ASSOC);
}

$editOfficial = null;
if (isset($_GET['edit'])) {
    $id = (int) $_GET['edit'];
    $stmt = $conn->prepare('SELECT * FROM district_officials WHERE id = ?');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    if ($row) {
        $editOfficial = $row;
    }
    $stmt->close();
}

closeDBConnection($conn);

$adminPageTitle = 'District officials';
require __DIR__ . '/includes/head.php';
?>
<body class="admin-page">
    <?php include __DIR__ . '/includes/sidebar.php'; ?>
    <main class="admin-main">
        <div class="admin-hero">
            <div>
                <h1>District officials</h1>
                <?php require __DIR__ . '/includes/breadcrumbs.php'; ?>
                <p>Collector, MLA, MP, District Police Chief, and Deputy Collector General (ADM). The newest active record of each type appears on the homepage.</p>
            </div>
            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#officialModal">
                <i class="bi bi-plus-circle"></i> Add official
            </button>
        </div>

        <?php if ($message): ?>
        <div class="alert alert-<?php echo htmlspecialchars($messageType); ?> alert-dismissible fade show">
            <?php echo htmlspecialchars($message); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Office</th>
                            <th>Name</th>
                            <th>Landline</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($officials)): ?>
                        <tr>
                            <td colspan="7" class="text-center">No officials yet. Add the first record.</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($officials as $official): ?>
                        <tr>
                            <td><?php echo (int) $official['id']; ?></td>
                            <td><?php echo htmlspecialchars($officeTypes[$official['office_type']] ?? $official['office_type']); ?></td>
                            <td>
                                <?php echo htmlspecialchars($official['name']); ?>
                                <?php if (!empty($official['area'])): ?>
                                <div class="text-muted small"><?php echo htmlspecialchars($official['area']); ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($official['landline'] ?: '—'); ?></td>
                            <td class="admin-td-address"><?php echo nl2br(htmlspecialchars($official['address'] ?: '—')); ?></td>
                            <td>
                                <span class="badge bg-<?php echo $official['status'] === 'active' ? 'success' : 'secondary'; ?>">
                                    <?php echo htmlspecialchars($official['status']); ?>
                                </span>
                            </td>
                            <td>
                                <div class="admin-table-actions">
                                    <a href="?edit=<?php echo (int) $official['id']; ?>" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <form method="POST" onsubmit="return confirm('Delete this official?');">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken(), ENT_QUOTES, 'UTF-8'); ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id" value="<?php echo (int) $official['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
    </main>

    <div class="modal fade" id="officialModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><?php echo $editOfficial ? 'Edit official' : 'Add official'; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken(), ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="action" value="<?php echo $editOfficial ? 'edit' : 'add'; ?>">
                        <?php if ($editOfficial): ?>
                        <input type="hidden" name="id" value="<?php echo (int) $editOfficial['id']; ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label" for="office_type">Office type</label>
                            <select class="form-select" id="office_type" name="office_type" required>
                                <?php
                                $selectedType = $editOfficial['office_type'] ?? 'collector';
                                foreach ($officeTypes as $typeKey => $typeLabel):
                                ?>
                                <option value="<?php echo htmlspecialchars($typeKey); ?>" <?php echo $selectedType === $typeKey ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($typeLabel); ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="official_name">Name</label>
                            <input class="form-control" type="text" id="official_name" name="name" required
                                   value="<?php echo $editOfficial ? htmlspecialchars($editOfficial['name']) : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="official_area">Constituency / jurisdiction (optional)</label>
                            <input class="form-control" type="text" id="official_area" name="area"
                                   value="<?php echo $editOfficial ? htmlspecialchars((string) $editOfficial['area']) : ''; ?>"
                                   placeholder="e.g. Palakkad Lok Sabha, Palakkad Assembly">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="official_landline">Landline</label>
                            <input class="form-control" type="text" id="official_landline" name="landline"
                                   value="<?php echo $editOfficial ? htmlspecialchars((string) $editOfficial['landline']) : ''; ?>"
                                   placeholder="0491-2505266">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="official_address">Address</label>
                            <textarea class="form-control" id="official_address" name="address" rows="3"><?php echo $editOfficial ? htmlspecialchars((string) $editOfficial['address']) : ''; ?></textarea>
                        </div>
                        <div class="mb-0">
                            <label class="form-label" for="official_status">Status</label>
                            <select class="form-select" id="official_status" name="status">
                                <option value="active" <?php echo (!$editOfficial || $editOfficial['status'] === 'active') ? 'selected' : ''; ?>>Active</option>
                                <option value="inactive" <?php echo ($editOfficial && $editOfficial['status'] === 'inactive') ? 'selected' : ''; ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary"><?php echo $editOfficial ? 'Update' : 'Add'; ?> official</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php if ($editOfficial): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new bootstrap.Modal(document.getElementById('officialModal')).show();
        });
    </script>
    <?php endif; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
