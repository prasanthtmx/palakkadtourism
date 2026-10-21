<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/security.php';
require_once __DIR__ . '/includes/directory_helpers.php';

$pageTitle = 'Submit a free Yellow Pages listing';
$pageDescription = 'Add your Palakkad business, shop, or service to our free directory. Listings are reviewed before they go live.';
$pageRobots = 'noindex, follow';

$breadcrumbs = [
    ['title' => 'Home', 'url' => BASE_PATH . '/'],
    ['title' => 'Yellow Pages', 'url' => BASE_PATH . '/yellow-pages.php'],
    ['title' => 'Submit listing', 'url' => ''],
];

$errors = [];
$successMsg = '';

$formDefaults = [
    'name' => '',
    'category_id' => '',
    'description' => '',
    'address' => '',
    'area' => '',
    'phone' => '',
    'email' => '',
    'website' => '',
    'contact_person' => '',
    'submitter_name' => '',
    'submitter_email' => '',
];
$form = $formDefaults;

$categories = [];

require_once __DIR__ . '/config/database.php';

try {
    $connCat = getDBConnection();
    $rc = @$connCat->query("SELECT id, name FROM directory_categories WHERE status='active' ORDER BY sort_order ASC, name ASC");
    if ($rc) {
        while ($row = $rc->fetch_assoc()) {
            $categories[] = $row;
        }
        $rc->free();
    }
    closeDBConnection($connCat);
} catch (Throwable $e) {
    error_log('[yellow-pages-submit] categories: ' . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Honeypot first (bots may not send a valid CSRF token).
    $hp = trim((string) ($_POST['website_url_leave_blank'] ?? ''));
    if ($hp !== '') {
        $errors = [];
        $successMsg = 'Thank you. Your submission will be reviewed shortly.';
        $form = $formDefaults;
    } elseif (!isset($_POST['csrf_token']) || !verifyCSRFToken($_POST['csrf_token'])) {
        $errors[] = 'Security check failed. Please reload the page and try again.';
    } else {
        $wait = isset($_SESSION['directory_last_submit']) ? (time() - (int) $_SESSION['directory_last_submit']) : 999;
        if ($wait < 50) {
            $errors[] = 'Please wait a minute before submitting again.';
        }

        $form['name'] = trim((string) ($_POST['name'] ?? ''));
        $form['category_id'] = (string) ($_POST['category_id'] ?? '');
        $form['description'] = trim((string) ($_POST['description'] ?? ''));
        $form['address'] = trim((string) ($_POST['address'] ?? ''));
        $form['area'] = trim((string) ($_POST['area'] ?? ''));
        $form['phone'] = trim((string) ($_POST['phone'] ?? ''));
        $form['email'] = trim((string) ($_POST['email'] ?? ''));
        $form['website'] = trim((string) ($_POST['website'] ?? ''));
        $form['contact_person'] = trim((string) ($_POST['contact_person'] ?? ''));
        $form['submitter_name'] = trim((string) ($_POST['submitter_name'] ?? ''));
        $form['submitter_email'] = trim((string) ($_POST['submitter_email'] ?? ''));

        if ($form['name'] === '' || mb_strlen($form['name'], 'UTF-8') > 200) {
            $errors[] = 'Please enter a valid business name (max 200 characters).';
        }
        $cid = sanitizeInt($form['category_id'], 1);
        if (!$cid) {
            $errors[] = 'Choose a category.';
        }
        if ($form['description'] === '') {
            $errors[] = 'Please enter a short description.';
        }
        if (mb_strlen($form['description'], 'UTF-8') > 8000) {
            $errors[] = 'Description is too long.';
        }
        if (mb_strlen($form['address'], 'UTF-8') > 500) {
            $errors[] = 'Address is too long.';
        }
        if (mb_strlen($form['area'], 'UTF-8') > 150) {
            $errors[] = 'Area/locality is too long.';
        }
        if (mb_strlen($form['phone'], 'UTF-8') > 75) {
            $errors[] = 'Phone number is too long.';
        }
        if ($form['email'] !== '' && !filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Public email looks invalid.';
        }
        if ($form['website'] !== '') {
            $wu = $form['website'];
            if (!preg_match('#^https?://#i', $wu)) {
                $wu = 'https://' . $wu;
            }
            if (!filter_var($wu, FILTER_VALIDATE_URL) || strlen($wu) > 500) {
                $errors[] = 'Website URL is invalid.';
            } else {
                $form['website'] = $wu;
            }
        }
        if (mb_strlen($form['contact_person'], 'UTF-8') > 150) {
            $errors[] = 'Contact person name is too long.';
        }
        if ($form['submitter_name'] === '' || mb_strlen($form['submitter_name'], 'UTF-8') > 150) {
            $errors[] = 'Please enter your name.';
        }
        if (!filter_var($form['submitter_email'], FILTER_VALIDATE_EMAIL) || strlen($form['submitter_email']) > 185) {
            $errors[] = 'Please enter a valid email we can reach you on.';
        }

        if (!$errors && empty($categories)) {
            $errors[] = 'Directory is not available yet. Ask the administrator to import database/directory_schema.sql.';
        }

        if (!$errors && $cid) {
            try {
                $conn = getDBConnection();

                $chk = $conn->prepare("SELECT id FROM directory_categories WHERE id = ? AND status = 'active' LIMIT 1");
                $chk->bind_param('i', $cid);
                $chk->execute();
                $ogr = $chk->get_result();
                $haveCat = $ogr && $ogr->num_rows > 0;
                $chk->close();

                if (!$haveCat) {
                    $errors[] = 'Selected category is not valid.';
                    closeDBConnection($conn);
                } else {
                    $slug = directory_unique_slug($conn, $form['name'], 'directory_entries');

                    $inst = $conn->prepare(
                        'INSERT INTO directory_entries (
                            category_id, name, slug, description, address, area, phone, email, website, contact_person,
                            submitter_name, submitter_email, status
                        ) VALUES (?,?,?,?,?,?,?,?,?,?,?,?, ?)'
                    );
                    $pending = 'pending';
                    $bp = 'i' . str_repeat('s', 12);
                    $inst->bind_param(
                        $bp,
                        $cid,
                        $form['name'],
                        $slug,
                        $form['description'],
                        $form['address'],
                        $form['area'],
                        $form['phone'],
                        $form['email'],
                        $form['website'],
                        $form['contact_person'],
                        $form['submitter_name'],
                        $form['submitter_email'],
                        $pending
                    );

                    if ($inst->execute()) {
                        $_SESSION['directory_last_submit'] = time();
                        $successMsg = 'Thank you! Your listing is pending review. It will appear in the Yellow Pages once approved.';
                        $form = $formDefaults;
                    } else {
                        $errors[] = 'Could not save your listing. Please try again later.';
                    }
                    $inst->close();
                    closeDBConnection($conn);
                }
            } catch (Throwable $e) {
                error_log('[yellow-pages-submit] insert ' . $e->getMessage());
                $errors[] = 'Could not save your listing. Try again later or contact support.';
                if (isset($conn) && $conn instanceof mysqli) {
                    closeDBConnection($conn);
                }
            }
        }
    }
}

require_once __DIR__ . '/includes/header.php';
$csrf = generateCSRFToken();
?>
<section class="py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h1 class="display-5 mb-3">Submit a free listing</h1>
                <p class="text-muted mb-4">There is no charge. Listings are moderated to reduce spam.</p>

                <?php foreach ($errors as $err): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($err); ?></div>
                <?php endforeach; ?>
                <?php if ($successMsg): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($successMsg); ?></div>
                    <p><a href="<?php echo htmlspecialchars(BASE_PATH . '/yellow-pages.php'); ?>" class="btn btn-primary">Browse directory</a></p>
                <?php endif; ?>

                <?php if (!$successMsg && empty($categories) && !$errors): ?>
                    <div class="alert alert-warning mb-4">Categories are loading from the database. If this message persists, run <code>database/directory_schema.sql</code> on your MySQL database.</div>
                <?php endif; ?>

                <?php if (!$successMsg && !empty($categories)): ?>
                <form method="post" action="<?php echo htmlspecialchars(BASE_PATH . '/yellow-pages-submit.php'); ?>" class="needs-validation" novalidate>
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf); ?>">
                    <!-- Honeypot -->
                    <div class="directory-hp visually-hidden" aria-hidden="true" style="position:absolute;left:-9999px;height:0;overflow:hidden;">
                        <label for="website_url_leave_blank">Website</label>
                        <input type="text" name="website_url_leave_blank" id="website_url_leave_blank" value="" tabindex="-1" autocomplete="off">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="name">Business / listing name <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" id="name" name="name" required maxlength="200" value="<?php echo htmlspecialchars($form['name']); ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="category_id">Category <span class="text-danger">*</span></label>
                        <select class="form-select" id="category_id" name="category_id" required>
                            <option value="">— Select —</option>
                            <?php foreach ($categories as $c): ?>
                                <option value="<?php echo (int) $c['id']; ?>" <?php echo (string) $form['category_id'] === (string) $c['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="description">Description <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="description" name="description" rows="6" maxlength="8000" required><?php echo htmlspecialchars($form['description']); ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="area">Area / locality</label>
                            <input class="form-control" type="text" id="area" name="area" maxlength="150" value="<?php echo htmlspecialchars($form['area']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="phone">Phone</label>
                            <input class="form-control" type="text" id="phone" name="phone" maxlength="75" value="<?php echo htmlspecialchars($form['phone']); ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="address">Address</label>
                        <textarea class="form-control" id="address" name="address" rows="3" maxlength="500"><?php echo htmlspecialchars($form['address']); ?></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="email">Public email (shown on listing)</label>
                            <input class="form-control" type="email" id="email" name="email" maxlength="185" value="<?php echo htmlspecialchars($form['email']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="website">Website</label>
                            <input class="form-control" type="url" id="website" name="website" maxlength="500" placeholder="https://" value="<?php echo htmlspecialchars($form['website']); ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="contact_person">Contact person (optional, may be shown)</label>
                        <input class="form-control" type="text" id="contact_person" name="contact_person" maxlength="150" value="<?php echo htmlspecialchars($form['contact_person']); ?>">
                    </div>
                    <hr class="my-4">
                    <h2 class="h5">Your details (for moderation)</h2>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="submitter_name">Your name <span class="text-danger">*</span></label>
                            <input class="form-control" type="text" id="submitter_name" name="submitter_name" required maxlength="150" value="<?php echo htmlspecialchars($form['submitter_name']); ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="submitter_email">Your email <span class="text-danger">*</span></label>
                            <input class="form-control" type="email" id="submitter_email" name="submitter_email" required maxlength="185" value="<?php echo htmlspecialchars($form['submitter_email']); ?>">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit for review</button>
                </form>
                <?php elseif (!$successMsg && empty($categories)): ?>
                    <p class="text-muted">When the database is ready, the form will appear here.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
