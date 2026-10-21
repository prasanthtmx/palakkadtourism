<?php
/**
 * SEO URL and metadata helpers.
 */
if (defined('PALAKKAD_URL_HELPER_LOADED')) {
    return;
}
define('PALAKKAD_URL_HELPER_LOADED', true);

function generateSlug($string) {
    $string = strtolower(trim((string) $string));
    $string = str_replace("'", '', $string);
    $string = preg_replace('/[^a-z0-9]+/', '-', $string);
    return trim((string) $string, '-');
}

function seoSlugMap(array $rows) {
    $used = [];
    $map = [];
    usort($rows, static function ($a, $b) {
        return (int) $a['id'] <=> (int) $b['id'];
    });
    foreach ($rows as $row) {
        $id = (int) ($row['id'] ?? 0);
        $base = generateSlug($row['name'] ?? '');
        if ($base === '') {
            $base = 'item-' . $id;
        }
        $slug = isset($used[$base]) ? $base . '-' . $id : $base;
        $used[$base] = true;
        $used[$slug] = true;
        $map[$id] = $slug;
    }
    return $map;
}

function seoEntityFile($kind) {
    $files = [
        'temple' => 'temple.php',
        'church' => 'church.php',
        'mosque' => 'mosque.php',
        'festival' => 'festival.php',
        'place' => 'place.php',
        'heritage-village' => 'heritage-village.php',
        'institution' => 'institution.php',
    ];
    return $files[$kind] ?? '';
}

function seoEntityUrl($kind, $id, $name, $slug = null) {
    $id = (int) $id;
    $slug = $slug !== null && $slug !== '' ? generateSlug($slug) : generateSlug($name);
    if ($kind === 'temple' && ($id === 1 || $slug === 'jain-temple')) {
        return rtrim(BASE_PATH, '/') . '/jain-temple.php';
    }
    $file = seoEntityFile($kind);
    if ($file === '') {
        return rtrim(BASE_PATH, '/') . '/';
    }
    return rtrim(BASE_PATH, '/') . '/' . $file . '?slug=' . rawurlencode($slug);
}

function getPlaceUrl($id, $name, $slug = null) {
    return seoEntityUrl('place', $id, $name, $slug);
}

function getTempleUrl($id, $name, $slug = null) {
    return seoEntityUrl('temple', $id, $name, $slug);
}

function getChurchUrl($id, $name, $slug = null) {
    return seoEntityUrl('church', $id, $name, $slug);
}

function getMosqueUrl($id, $name, $slug = null) {
    return seoEntityUrl('mosque', $id, $name, $slug);
}

function getFestivalUrl($id, $name, $slug = null) {
    return seoEntityUrl('festival', $id, $name, $slug);
}

function getHeritageVillageUrl($id, $name, $slug = null) {
    return seoEntityUrl('heritage-village', $id, $name, $slug);
}

function getInstitutionUrl($id, $name, $slug = null) {
    return seoEntityUrl('institution', $id, $name, $slug);
}

function seoPublicUrl($pathWithQuery) {
    $pathWithQuery = trim((string) $pathWithQuery);
    if ($pathWithQuery === '') {
        return rtrim(SITE_URL, '/') . '/';
    }
    if (preg_match('#^https?://#i', $pathWithQuery)) {
        $path = parse_url($pathWithQuery, PHP_URL_PATH);
        $query = parse_url($pathWithQuery, PHP_URL_QUERY);
        $pathWithQuery = ($path ?: '/') . ($query ? '?' . $query : '');
    }
    $base = defined('BASE_PATH') ? BASE_PATH : '';
    if ($base !== '' && strpos($pathWithQuery, $base) === 0) {
        $pathWithQuery = substr($pathWithQuery, strlen($base));
    }
    if ($pathWithQuery === '' || $pathWithQuery[0] !== '/') {
        $pathWithQuery = '/' . ltrim($pathWithQuery, '/');
    }
    return rtrim(SITE_URL, '/') . $pathWithQuery;
}

function seoCanonicalRequestUri() {
    $raw = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/';
    $path = parse_url($raw, PHP_URL_PATH);
    if ($path === null || $path === '' || $path === false) {
        $path = '/';
    }
    $base = defined('BASE_PATH') ? BASE_PATH : '';
    if ($base !== '' && strpos($path, $base) === 0) {
        $path = substr($path, strlen($base));
        if ($path === '' || $path[0] !== '/') {
            $path = '/' . ltrim($path, '/');
        }
    }
    $queryStr = parse_url($raw, PHP_URL_QUERY);
    $params = [];
    if ($queryStr !== null && $queryStr !== '' && $queryStr !== false) {
        parse_str($queryStr, $params);
    }
    $stripKeys = [
        'utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content',
        'gclid', 'fbclid', 'mc_cid', 'mc_eid', '_ga', 'yclid', 'msclkid',
    ];
    foreach ($stripKeys as $k) {
        unset($params[$k]);
    }
    if (isset($params['slug'])) {
        unset($params['id']);
    }
    $qs = http_build_query($params, '', '&', PHP_QUERY_RFC3986);
    return $qs === '' ? $path : $path . '?' . $qs;
}

function seoCanonicalUrl() {
    return rtrim(SITE_URL, '/') . seoCanonicalRequestUri();
}

function seoDocumentTitle($pageTitle, $isHomepage = false) {
    $pageTitle = trim((string) $pageTitle);
    if ($isHomepage || $pageTitle === '' || strcasecmp($pageTitle, 'Home') === 0) {
        return 'Palakkad Tourism | Kerala travel guide';
    }
    if (stripos($pageTitle, 'Palakkad Tourism') !== false) {
        return $pageTitle;
    }
    return $pageTitle . ' | Palakkad Tourism';
}

function seoMetaDescription($text, $max = 155) {
    $text = trim(preg_replace('/\s+/', ' ', strip_tags((string) $text)));
    if ($text === '') {
        return 'Discover Palakkad (Palghat), Kerala — temples, dams, festivals, and travel tips.';
    }
    if (function_exists('mb_strlen') && mb_strlen($text, 'UTF-8') > $max) {
        return rtrim(mb_substr($text, 0, $max - 1, 'UTF-8'), " \t\n\r.,;:-") . '…';
    }
    if (strlen($text) > $max) {
        return rtrim(substr($text, 0, $max - 1), " \t\n\r.,;:-") . '…';
    }
    return $text;
}

function seoAbsoluteAssetUrl($pathOrUrl) {
    $pathOrUrl = trim((string) $pathOrUrl);
    if ($pathOrUrl === '') {
        return '';
    }
    if (preg_match('#^https?://#i', $pathOrUrl)) {
        return $pathOrUrl;
    }
    $pathOrUrl = preg_replace('#^/(paalakkad|palakkad|palakkadtourism)/#i', '/', $pathOrUrl);
    if ($pathOrUrl !== '' && $pathOrUrl[0] === '/') {
        return seoPublicUrl($pathOrUrl);
    }
    return seoPublicUrl(rtrim(BASE_PATH, '/') . '/' . ltrim($pathOrUrl, '/'));
}

function seoAssetSrc($pathOrUrl) {
    $pathOrUrl = trim((string) $pathOrUrl);
    if ($pathOrUrl === '') {
        return '';
    }
    if (preg_match('#^https?://#i', $pathOrUrl)) {
        return $pathOrUrl;
    }
    $pathOrUrl = preg_replace('#^/(paalakkad|palakkad|palakkadtourism)/#i', '/', $pathOrUrl);
    if ($pathOrUrl !== '' && $pathOrUrl[0] === '/') {
        return rtrim(BASE_PATH, '/') . $pathOrUrl;
    }
    return rtrim(BASE_PATH, '/') . '/' . ltrim($pathOrUrl, '/');
}

function seoDefaultOgImage($pageSlug) {
    $map = [
        'index' => 'assets/images/banner.jpg',
        'jain-temple' => 'assets/images/jain-temple-shri-digambar-lal-mandir-palakkad.jpg',
        'fort' => 'assets/images/fort.png',
        'places' => 'assets/images/banner.jpg',
        'temples' => 'assets/images/jain-temple-shri-digambar-lal-mandir-palakkad.jpg',
        'municipality-palakkad' => 'assets/images/palakkad_municipality.webp',
        'dam-parambikulam' => 'assets/images/parambikulam-dam.jpg',
        'dam-malampuzha' => 'assets/images/banner.jpg',
    ];
    return $map[$pageSlug] ?? 'assets/images/palakkad-logo.png';
}

function seoLoadEntityByRequest(mysqli $conn, $table, $kind, $listFile) {
    $allowed = [
        'temples', 'churches', 'mosques', 'festivals', 'places',
        'heritage_villages', 'institutions',
    ];
    if (!in_array($table, $allowed, true)) {
        header('Location: ' . rtrim(BASE_PATH, '/') . '/' . ltrim($listFile, '/'), true, 302);
        exit;
    }

    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    $slugIn = isset($_GET['slug']) ? generateSlug((string) $_GET['slug']) : '';

    $result = $conn->query('SELECT * FROM `' . $table . '` WHERE status = \'active\' ORDER BY id ASC');
    $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    $slugMap = seoSlugMap($rows);

    $row = null;
    if ($id > 0) {
        foreach ($rows as $candidate) {
            if ((int) $candidate['id'] === $id) {
                $row = $candidate;
                break;
            }
        }
    } elseif ($slugIn !== '') {
        foreach ($slugMap as $rid => $slug) {
            if ($slug === $slugIn) {
                foreach ($rows as $candidate) {
                    if ((int) $candidate['id'] === (int) $rid) {
                        $row = $candidate;
                        break 2;
                    }
                }
            }
        }
    }

    $listUrl = rtrim(BASE_PATH, '/') . '/' . ltrim($listFile, '/');
    if (!$row) {
        header('Location: ' . $listUrl, true, 302);
        exit;
    }

    $canonicalSlug = $slugMap[(int) $row['id']];
    $pretty = seoEntityUrl($kind, (int) $row['id'], $row['name'], $canonicalSlug);
    $row['_slug'] = $canonicalSlug;

    $currentFile = basename($_SERVER['PHP_SELF'] ?? '');
    $prettyFile = basename(parse_url($pretty, PHP_URL_PATH) ?: '');
    $prettyQuery = [];
    parse_str((string) parse_url($pretty, PHP_URL_QUERY), $prettyQuery);
    $wantSlug = $prettyQuery['slug'] ?? '';

    $needsRedirect = false;
    if ($currentFile !== $prettyFile) {
        $needsRedirect = true;
    } elseif ($wantSlug !== '' && $slugIn !== $wantSlug) {
        $needsRedirect = true;
    } elseif ($id > 0) {
        $needsRedirect = true;
    }

    if ($needsRedirect && !headers_sent()) {
        header('Location: ' . $pretty, true, 301);
        exit;
    }

    return $row;
}
