<?php
if (!isset($adminPageTitle) || $adminPageTitle === '') {
    $adminPageTitle = 'Admin';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($adminPageTitle); ?> · Palakkad Tourism Admin</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/png" href="<?php echo htmlspecialchars(BASE_PATH); ?>/assets/images/palakkad-logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?php echo htmlspecialchars(BASE_PATH); ?>/assets/css/admin.css?v=<?php echo rawurlencode(ADMIN_CSS_V); ?>">
</head>
