<?php
require_once __DIR__ . '/config/config.php';
header('Location: ' . rtrim(BASE_PATH, '/') . '/donate-blood.php', true, 301);
exit;
