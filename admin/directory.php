<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/security.php';
require_once __DIR__ . '/../includes/directory_helpers.php';

requireAdminAuth();

$conn = getDBConnection();
$message = '';
$messageType = '';
$activeTab = (isset($_GET['tab']) && $_GET['tab'] === 'categories') ? 'categories' : 'entries';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $message = 'Invalid security token. Please try again.';
        $messageType = 'danger';
    } elseif (isset($_POST['form'])) {
        $form = $_POST['form'];

        if ($form === 'category_save') {
            $action = $_POST['cat_action'] ?? 'add';
            $cname = trim((string) ($_POST['cat_name'] ?? ''));
            $cslugIn = trim((string) ($_POST['cat_slug'] ?? ''));
            $sort = sanitizeInt($_POST['cat_sort'] ?? 0, 0) ?? 0;
            $cstatus = in_array($_POST['cat_status'] ?? '', ['active', 'inactive'], true) ? $_POST['cat_status'] : 'active';
            if ($cname === '') {
                $message = 'Category name is required.';
                $messageType = 'danger';
            } else {
                if ($action === 'add') {
                    $cslug = $cslugIn !== '' ? directory_slug_base($cslugIn) : directory_slug_base($cname);
                    $cslug = directory_unique_slug($conn, $cslug, 'directory_categories');
                    $st = $conn->prepare('INSERT INTO directory_categories (name, slug, sort_order, status) VALUES (?,?,?,?)');
                    $st->bind_param('ssis', $cname, $cslug, $sort, $cstatus);
                    if ($st->execute()) {
                        $message = 'Category added.';
                        $messageType = 'success';
                    } else {
                        $message = 'Error: ' . $conn->error;
                        $messageType = 'danger';
                    }
                    $st->close();
                } else {
                    $cid = sanitizeInt($_POST['cat_id'] ?? 0, 1);
                    if (!$cid) {
                        $message = 'Invalid category.';
                        $messageType = 'danger';
                    } else {
                        $base = $cslugIn !== '' ? $cslugIn : $cname;
                        $cslug = directory_unique_slug($conn, $base, 'directory_categories', $cid);
                        $st = $conn->prepare('UPDATE directory_categories SET name=?, slug=?, sort_order=?, status=? WHERE id=?');
                        $st->bind_param('ssisi', $cname, $cslug, $sort, $cstatus, $cid);
                        if ($st->execute()) {
                            $message = 'Category updated.';
                            $messageType = 'success';
                        } else {
                            $message = 'Error: ' . $conn->error;
                            $messageType = 'danger';
                        }
                        $st->close();
                    }
                }
            }
            $activeTab = 'categories';
        } elseif ($form === 'category_delete') {
            $cid = sanitizeInt($_POST['cat_id'] ?? 0, 1);
            if ($cid) {
                $chk = $conn->prepare('SELECT COUNT(*) AS c FROM directory_entries WHERE category_id = ?');
                $chk->bind_param('i', $cid);
                $chk->execute();
                $cr = $chk->get_result()->fetch_assoc();
                $chk->close();
                if ((int) ($cr['c'] ?? 0) > 0) {
                    $message = 'Cannot delete: move or delete listings in this category first.';
                    $messageType = 'danger';
                } else {
                    $st = $conn->prepare('DELETE FROM directory_categories WHERE id = ?');
                    $st->bind_param('i', $cid);
                    if ($st->execute()) {
                        $message = 'Category deleted.';
                        $messageType = 'success';
                    } else {
                        $message = 'Error: ' . $conn->error;
                        $messageType = 'danger';
                    }
                    $st->close();
                }
            }
            $activeTab = 'categories';
        } elseif ($form === 'entry_save') {
            $action = $_POST['entry_action'] ?? 'add';
            $category_id = sanitizeInt($_POST['category_id'] ?? 0, 1);
            $name = trim((string) ($_POST['name'] ?? ''));
            $slugIn = trim((string) ($_POST['slug'] ?? ''));
            $description = trim((string) ($_POST['description'] ?? ''));
            $address = trim((string) ($_POST['address'] ?? ''));
            $area = trim((string) ($_POST['area'] ?? ''));
            $phone = trim((string) ($_POST['phone'] ?? ''));
            $email = trim((string) ($_POST['email'] ?? ''));
            $website = trim((string) ($_POST['website'] ?? ''));
            $contact_person = trim((string) ($_POST['contact_person'] ?? ''));
            $submitter_name = trim((string) ($_POST['submitter_name'] ?? 'Admin'));
            $submitter_email = trim((string) ($_POST['submitter_email'] ?? SITE_EMAIL));
            $meta_title = trim((string) ($_POST['meta_title'] ?? ''));
            $meta_description = trim((string) ($_POST['meta_description'] ?? ''));
            $status = in_array($_POST['status'] ?? '', ['pending', 'active', 'rejected', 'inactive'], true) ? $_POST['status'] : 'pending';
            $featured = !empty($_POST['featured']) ? 1 : 0;

            if (!$category_id || $name === '') {
                $message = 'Category and name are required.';
                $messageType = 'danger';
            } elseif (!filter_var($submitter_email, FILTER_VALIDATE_EMAIL)) {
                $message = 'Submitter email is invalid.';
                $messageType = 'danger';
            } else {
                $editId = ($action === 'edit') ? sanitizeInt($_POST['entry_id'] ?? 0, 1) : null;
                $currentSlugHold = trim((string) ($_POST['current_slug'] ?? ''));
                if ($slugIn !== '') {
                    $slugBase = $slugIn;
                } elseif ($action === 'edit' && $currentSlugHold !== '') {
                    $slugBase = $currentSlugHold;
                } else {
                    $slugBase = $name;
                }
                $slug = directory_unique_slug($conn, $slugBase, 'directory_entries', $editId);

                $typesIns = 'i' . str_repeat('s', 14) . 'i';
                if ($action === 'add') {
                    $st = $conn->prepare(
                        'INSERT INTO directory_entries (
                            category_id, name, slug, description, address, area, phone, email, website, contact_person,
                            submitter_name, submitter_email, meta_title, meta_description, status, featured
                        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)'
                    );
                    $st->bind_param(
                        $typesIns,
                        $category_id,
                        $name,
                        $slug,
                        $description,
                        $address,
                        $area,
                        $phone,
                        $email,
                        $website,
                        $contact_person,
                        $submitter_name,
                        $submitter_email,
                        $meta_title,
                        $meta_description,
                        $status,
                        $featured
                    );
                    if ($st->execute()) {
                        $message = 'Listing added.';
                        $messageType = 'success';
                    } else {
                        $message = 'Error: ' . $conn->error;
                        $messageType = 'danger';
                    }
                    $st->close();
                } elseif ($editId) {
                    $typesUp = 'i' . str_repeat('s', 14) . 'ii';
                    $st = $conn->prepare(
                        'UPDATE directory_entries SET
                            category_id=?, name=?, slug=?, description=?, address=?, area=?, phone=?, email=?, website=?, contact_person=?,
                            submitter_name=?, submitter_email=?, meta_title=?, meta_description=?, status=?, featured=?
                         WHERE id=?'
                    );
                    $st->bind_param(
                        $typesUp,
                        $category_id,
                        $name,
                        $slug,
                        $description,
                        $address,
                        $area,
                        $phone,
                        $email,
                        $website,
                        $contact_person,
                        $submitter_name,
                        $submitter_email,
                        $meta_title,
                        $meta_description,
                        $status,
                        $featured,
                        $editId
                    );
                    if ($st->execute()) {
                        $message = 'Listing updated.';
                        $messageType = 'success';
                    } else {
                        $message = 'Error: ' . $conn->error;
                        $messageType = 'danger';
                    }
                    $st->close();
                }
            }
            $activeTab = 'entries';
        } elseif ($form === 'entry_delete') {
            $eid = sanitizeInt($_POST['entry_id'] ?? 0, 1);
            if ($eid) {
                $st = $conn->prepare('DELETE FROM directory_entries WHERE id = ?');
                $st->bind_param('i', $eid);
                if ($st->execute()) {
                    $message = 'Listing deleted.';
                    $messageType = 'success';
                } else {
                    $message = 'Error: ' . htmlspecialchars($conn->error);
                    $messageType = 'danger';
                }
                $st->close();
            }
            $activeTab = 'entries';
        }
    }
}

$catsRows = @$conn->query('SELECT * FROM directory_categories ORDER BY sort_order ASC, name ASC');
$categoriesList = ($catsRows) ? $catsRows->fetch_all(MYSQLI_ASSOC) : [];

$entriesRows = @$conn->query(
    'SELECT e.*, c.name AS category_name FROM directory_entries e
     LEFT JOIN directory_categories c ON c.id = e.category_id
     ORDER BY FIELD(e.status,\'pending\',\'active\',\'inactive\',\'rejected\'), e.id DESC'
);
$entriesList = ($entriesRows) ? $entriesRows->fetch_all(MYSQLI_ASSOC) : [];

$editEntry = null;
if (isset($_GET['edit_entry'])) {
    $eid = sanitizeInt($_GET['edit_entry'], 1);
    if ($eid) {
        $st = $conn->prepare('SELECT * FROM directory_entries WHERE id = ?');
        $st->bind_param('i', $eid);
        $st->execute();
        $editEntry = $st->get_result()->fetch_assoc() ?: null;
        $st->close();
    }
}

$editCat = null;
if (isset($_GET['edit_cat'])) {
    $cid = sanitizeInt($_GET['edit_cat'], 1);
    if ($cid) {
        $st = $conn->prepare('SELECT * FROM directory_categories WHERE id = ?');
        $st->bind_param('i', $cid);
        $st->execute();
        $editCat = $st->get_result()->fetch_assoc() ?: null;
        $st->close();
    }
}

closeDBConnection($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yellow Pages Directory - Admin</title>
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
                    <h1 class="h4">Yellow Pages directory</h1>
                    <a href="<?php echo htmlspecialchars(BASE_PATH . '/yellow-pages.php'); ?>" class="btn btn-outline-secondary btn-sm" target="_blank" rel="noopener">View public site</a>
                </div>

                <?php if ($message): ?>
                    <div class="alert alert-<?php echo $messageType; ?> alert-dismissible fade show">
                        <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <ul class="nav nav-tabs mb-3">
                    <li class="nav-item">
                        <a class="nav-link <?php echo $activeTab === 'entries' ? 'active' : ''; ?>" href="?tab=entries">Listings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo $activeTab === 'categories' ? 'active' : ''; ?>" href="?tab=categories">Categories</a>
                    </li>
                </ul>

                <?php if ($activeTab === 'entries'): ?>
                    <div class="d-flex justify-content-end mb-2">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#entryModal">
                            <i class="bi bi-plus-circle"></i> Add listing
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Slug</th>
                                    <th>Status</th>
                                    <th>Submitter</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($entriesList)): ?>
                                    <tr><td colspan="7" class="text-center py-4">No listings yet. Import <code>database/directory_schema.sql</code> if tables are missing, or add listings above.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($entriesList as $row): ?>
                                        <tr>
                                            <td><?php echo (int) $row['id']; ?></td>
                                            <td><?php echo htmlspecialchars($row['name']); ?><?php echo !empty($row['featured']) ? ' <span class="badge bg-warning text-dark">Feat</span>' : ''; ?></td>
                                            <td><?php echo htmlspecialchars($row['category_name'] ?? '—'); ?></td>
                                            <td><code class="small"><?php echo htmlspecialchars($row['slug']); ?></code></td>
                                            <td><span class="badge bg-<?php
                                                echo $row['status'] === 'active' ? 'success' : ($row['status'] === 'pending' ? 'warning text-dark' : 'secondary');
                                                ?>"><?php echo htmlspecialchars($row['status']); ?></span></td>
                                            <td class="small"><?php echo htmlspecialchars($row['submitter_email']); ?></td>
                                            <td class="text-nowrap">
                                                <a class="btn btn-sm btn-outline-primary" href="<?php echo htmlspecialchars(BASE_PATH . '/yellow-page.php?slug=' . rawurlencode($row['slug'])); ?>" target="_blank" rel="noopener">View</a>
                                                <a class="btn btn-sm btn-warning" href="?tab=entries&amp;edit_entry=<?php echo (int) $row['id']; ?>">Edit</a>
                                                <form method="post" class="d-inline" onsubmit="return confirm('Delete this listing?');">
                                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken(), ENT_QUOTES, 'UTF-8'); ?>">
                                                    <input type="hidden" name="form" value="entry_delete">
                                                    <input type="hidden" name="entry_id" value="<?php echo (int) $row['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="d-flex justify-content-end mb-2">
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#catModal">
                            <i class="bi bi-plus-circle"></i> Add category
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Sort</th>
                                    <th>Status</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($categoriesList)): ?>
                                    <tr><td colspan="6" class="text-center py-4">No categories. Run <code>database/directory_schema.sql</code>.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($categoriesList as $c): ?>
                                        <tr>
                                            <td><?php echo (int) $c['id']; ?></td>
                                            <td><?php echo htmlspecialchars($c['name']); ?></td>
                                            <td><code class="small"><?php echo htmlspecialchars($c['slug']); ?></code></td>
                                            <td><?php echo (int) $c['sort_order']; ?></td>
                                            <td><span class="badge bg-<?php echo $c['status'] === 'active' ? 'success' : 'secondary'; ?>"><?php echo htmlspecialchars($c['status']); ?></span></td>
                                            <td class="text-nowrap">
                                                <a class="btn btn-sm btn-warning" href="?tab=categories&amp;edit_cat=<?php echo (int) $c['id']; ?>">Edit</a>
                                                <form method="post" class="d-inline" onsubmit="return confirm('Delete category?');">
                                                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken(), ENT_QUOTES, 'UTF-8'); ?>">
                                                    <input type="hidden" name="form" value="category_delete">
                                                    <input type="hidden" name="cat_id" value="<?php echo (int) $c['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </main>
        </div>
    </div>

    <!-- Entry modal -->
    <div class="modal fade" id="entryModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header">
                        <h5 class="modal-title"><?php echo $editEntry ? 'Edit listing' : 'Add listing'; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken(), ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="form" value="entry_save">
                        <input type="hidden" name="entry_action" value="<?php echo $editEntry ? 'edit' : 'add'; ?>">
                        <?php if ($editEntry): ?>
                            <input type="hidden" name="entry_id" value="<?php echo (int) $editEntry['id']; ?>">
                            <input type="hidden" name="current_slug" value="<?php echo htmlspecialchars($editEntry['slug'], ENT_QUOTES, 'UTF-8'); ?>">
                        <?php endif; ?>

                        <div class="row g-2">
                            <div class="col-md-6">
                                <label class="form-label">Category *</label>
                                <select name="category_id" class="form-select" required>
                                    <?php foreach ($categoriesList as $c): ?>
                                        <option value="<?php echo (int) $c['id']; ?>" <?php echo ($editEntry && (int) $editEntry['category_id'] === (int) $c['id']) ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['name']); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <select name="status" class="form-select">
                                    <?php
                                    foreach (['pending', 'active', 'rejected', 'inactive'] as $st) {
                                        $sel = '';
                                        if ($editEntry) {
                                            if ($editEntry['status'] === $st) {
                                                $sel = ' selected';
                                            }
                                        } elseif ($st === 'active') {
                                            $sel = ' selected';
                                        }
                                        echo '<option value="' . htmlspecialchars($st) . '"' . $sel . '>' . htmlspecialchars($st) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Name *</label>
                                <input type="text" name="name" class="form-control" required maxlength="200" value="<?php echo $editEntry ? htmlspecialchars($editEntry['name']) : ''; ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label">URL slug (optional; auto from name if empty)</label>
                                <input type="text" name="slug" class="form-control" maxlength="220" value="<?php echo $editEntry ? htmlspecialchars($editEntry['slug']) : ''; ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="4"><?php echo $editEntry ? htmlspecialchars($editEntry['description']) : ''; ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Area</label>
                                <input type="text" name="area" class="form-control" maxlength="160" value="<?php echo $editEntry ? htmlspecialchars($editEntry['area']) : ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" name="phone" class="form-control" maxlength="80" value="<?php echo $editEntry ? htmlspecialchars($editEntry['phone']) : ''; ?>">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <textarea name="address" class="form-control" rows="2"><?php echo $editEntry ? htmlspecialchars($editEntry['address']) : ''; ?></textarea>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Public email</label>
                                <input type="email" name="email" class="form-control" maxlength="190" value="<?php echo $editEntry ? htmlspecialchars($editEntry['email']) : ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Website</label>
                                <input type="url" name="website" class="form-control" maxlength="512" value="<?php echo $editEntry ? htmlspecialchars($editEntry['website']) : ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Contact person</label>
                                <input type="text" name="contact_person" class="form-control" maxlength="160" value="<?php echo $editEntry ? htmlspecialchars($editEntry['contact_person']) : ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" name="featured" value="1" id="featured" <?php echo ($editEntry && !empty($editEntry['featured'])) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="featured">Featured on listing cards</label>
                                </div>
                            </div>
                            <div class="col-12"><hr><p class="small text-muted mb-2">Submitter / moderation contact</p></div>
                            <div class="col-md-6">
                                <label class="form-label">Submitter name</label>
                                <input type="text" name="submitter_name" class="form-control" maxlength="160" value="<?php echo $editEntry ? htmlspecialchars($editEntry['submitter_name']) : 'Admin'; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Submitter email</label>
                                <input type="email" name="submitter_email" class="form-control" maxlength="190" value="<?php echo $editEntry ? htmlspecialchars($editEntry['submitter_email']) : htmlspecialchars(SITE_EMAIL); ?>">
                            </div>
                            <div class="col-12"><hr><p class="small text-muted mb-2">SEO overrides (optional)</p></div>
                            <div class="col-md-6">
                                <label class="form-label">Meta title</label>
                                <input type="text" name="meta_title" class="form-control" maxlength="200" value="<?php echo $editEntry ? htmlspecialchars($editEntry['meta_title']) : ''; ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Meta description</label>
                                <input type="text" name="meta_description" class="form-control" maxlength="320" value="<?php echo $editEntry ? htmlspecialchars($editEntry['meta_description']) : ''; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Category modal -->
    <div class="modal fade" id="catModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post">
                    <div class="modal-header">
                        <h5 class="modal-title"><?php echo $editCat ? 'Edit category' : 'Add category'; ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCSRFToken(), ENT_QUOTES, 'UTF-8'); ?>">
                        <input type="hidden" name="form" value="category_save">
                        <input type="hidden" name="cat_action" value="<?php echo $editCat ? 'edit' : 'add'; ?>">
                        <?php if ($editCat): ?>
                            <input type="hidden" name="cat_id" value="<?php echo (int) $editCat['id']; ?>">
                        <?php endif; ?>
                        <div class="mb-3">
                            <label class="form-label">Name *</label>
                            <input type="text" name="cat_name" class="form-control" required maxlength="160" value="<?php echo $editCat ? htmlspecialchars($editCat['name']) : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Slug override (optional)</label>
                            <input type="text" name="cat_slug" class="form-control" maxlength="180" value="<?php echo $editCat ? htmlspecialchars($editCat['slug']) : ''; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Sort order</label>
                            <input type="number" name="cat_sort" class="form-control" min="0" value="<?php echo $editCat ? (int) $editCat['sort_order'] : 0; ?>">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="cat_status" class="form-select">
                                <?php foreach (['active', 'inactive'] as $cs):
                                    $sel = $editCat && $editCat['status'] === $cs ? ' selected' : ''; ?>
                                    <option value="<?php echo htmlspecialchars($cs); ?>"<?php echo $sel; ?>><?php echo htmlspecialchars($cs); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    (function () {
        const entryModal = document.getElementById('entryModal');
        <?php if ($editEntry): ?>
        if (entryModal) new bootstrap.Modal(entryModal).show();
        <?php endif; ?>
        const catModal = document.getElementById('catModal');
        <?php if ($editCat): ?>
        if (catModal) new bootstrap.Modal(catModal).show();
        <?php endif; ?>
    })();
    </script>
</body>
</html>
