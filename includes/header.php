<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/url_helper.php';

$seoCanonicalPath = seoCanonicalRequestUri();
$seoCanonicalUrl = seoCanonicalUrl();
$seoIsHomepage = (basename($_SERVER['PHP_SELF'] ?? '') === 'index.php');
$seoTitle = seoDocumentTitle(isset($pageTitle) ? $pageTitle : '', $seoIsHomepage);
$seoDescription = seoMetaDescription(isset($pageDescription) ? $pageDescription : '');
$seoRobots = (isset($pageRobots) && is_string($pageRobots) && trim($pageRobots) !== '')
    ? trim($pageRobots)
    : 'index, follow';
$seoPageSlug = basename($_SERVER['PHP_SELF'] ?? 'index', '.php');

$scriptFile = isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : '';
$seoArticleFileMtime = ($scriptFile !== '' && is_readable($scriptFile)) ? @filemtime($scriptFile) : false;
$seoArticleDateFromFile = $seoArticleFileMtime ? date('Y-m-d', $seoArticleFileMtime) : date('Y-m-d');

$seoArticlePublished = (isset($articleDatePublished) && is_string($articleDatePublished) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $articleDatePublished))
    ? $articleDatePublished
    : $seoArticleDateFromFile;
$seoArticleModified = (isset($articleDateModified) && is_string($articleDateModified) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $articleDateModified))
    ? $articleDateModified
    : $seoArticleDateFromFile;

$defaultOgImage = seoAbsoluteAssetUrl(seoDefaultOgImage($seoPageSlug));
$seoOgImage = (isset($pageOgImage) && is_string($pageOgImage) && trim($pageOgImage) !== '')
    ? seoAbsoluteAssetUrl(trim($pageOgImage))
    : $defaultOgImage;

if (!headers_sent() && session_status() !== PHP_SESSION_ACTIVE) {
    header('Cache-Control: public, max-age=300');
}
?>
<!DOCTYPE html>
<html lang="en-IN"<?php echo $seoIsHomepage ? ' itemscope itemtype="https://schema.org/TouristDestination"' : ''; ?>>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Primary Meta Tags -->
    <title><?php echo htmlspecialchars($seoTitle, ENT_QUOTES, 'UTF-8'); ?></title>
    <meta name="title" content="<?php echo htmlspecialchars($seoTitle, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="description" content="<?php echo htmlspecialchars($seoDescription, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="author" content="Palakkad Tourism">
    <meta name="robots" content="<?php echo htmlspecialchars($seoRobots, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="language" content="en-IN">
    <meta name="geo.region" content="IN-KL">
    <meta name="geo.placename" content="Palakkad">
    <meta name="geo.position" content="10.7867;76.6548">
    <meta name="ICBM" content="10.7867, 76.6548">
    
    <!-- Canonical URL (tracking query params stripped) -->
    <link rel="canonical" href="<?php echo htmlspecialchars($seoCanonicalUrl, ENT_QUOTES, 'UTF-8'); ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo htmlspecialchars($seoCanonicalUrl, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($seoTitle, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($seoDescription, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($seoOgImage, ENT_QUOTES, 'UTF-8'); ?>">
    <meta property="og:site_name" content="Palakkad Tourism">
    <meta property="og:locale" content="en_IN">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?php echo htmlspecialchars($seoCanonicalUrl, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($seoTitle, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($seoDescription, ENT_QUOTES, 'UTF-8'); ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($seoOgImage, ENT_QUOTES, 'UTF-8'); ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo BASE_PATH; ?>/assets/images/palakkad-logo.png">

    <?php if (isset($pageHeroPreload) && is_string($pageHeroPreload) && trim($pageHeroPreload) !== ''): ?>
    <!-- Hero image preload (LCP) -->
    <link rel="preload" as="image"
          href="<?php echo htmlspecialchars(seoAbsoluteAssetUrl(trim($pageHeroPreload)), ENT_QUOTES, 'UTF-8'); ?>"
          fetchpriority="high">
    <?php endif; ?>
    
    <!-- Google Fonts - Literata -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Literata:opsz,wght@7..72,200;7..72,300;7..72,400;7..72,500;7..72,600;7..72,700;7..72,800;7..72,900&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/assets/css/style.css?v=<?php echo rawurlencode(STYLE_CSS_V); ?>">
    <?php if (!empty($showGeminiChat)): ?>
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/assets/css/chatbot.css?v=1">
    <?php endif; ?>
    
    <?php
    $gaPageType = $seoPageSlug;
    $gaContentGroups = [
        'index' => 'Home',
        'temples' => 'Religious Places',
        'temple' => 'Religious Places',
        'churches' => 'Religious Places',
        'church' => 'Religious Places',
        'mosques' => 'Religious Places',
        'mosque' => 'Religious Places',
        'jain-temple' => 'Religious Places',
        'religious-places' => 'Religious Places',
        'places' => 'Attractions',
        'place' => 'Attractions',
        'festivals' => 'Attractions',
        'festival' => 'Attractions',
        'heritage-villages' => 'Attractions',
        'heritage-village' => 'Attractions',
        'institutions' => 'Attractions',
        'institution' => 'Attractions',
        'fort' => 'Attractions',
        'rivers' => 'Attractions',
        'mountain-ranges-passes' => 'Attractions',
        'vintage-gallery' => 'Attractions',
        'villages-taluks' => 'Attractions',
        'how-to-reach' => 'Information',
        'yellow-pages' => 'Directory',
        'yellow-page' => 'Directory',
        'yellow-pages-submit' => 'Directory',
        'hospitals' => 'Healthcare',
        'hospitals-government' => 'Healthcare',
        'hospitals-private' => 'Healthcare',
        'hospitals-ayurveda' => 'Healthcare',
        'hospitals-homeopathy' => 'Healthcare',
        'donate-blood' => 'Healthcare',
        'about' => 'Information',
        'history' => 'Information',
        'art-culture' => 'Information',
        'customs' => 'Information',
        'cookies-policy' => 'Information',
        'kerala-assembly-election-2026' => 'Elections',
        'palakkad-assembly-constituencies-2026' => 'Elections',
    ];
    $gaContentGroup = $gaContentGroups[$gaPageType] ?? 'General';
    if ($gaContentGroup === 'General') {
        if (strpos($gaPageType, 'dam-') === 0) {
            $gaContentGroup = 'Attractions';
        } elseif (strpos($gaPageType, 'municipality-') === 0) {
            $gaContentGroup = 'Municipalities';
        }
    }
    $gaIdJs = json_encode(GA_MEASUREMENT_ID, JSON_UNESCAPED_SLASHES);
    $gaTitleJs = json_encode($seoTitle, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $gaPageTypeJs = json_encode($gaPageType, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $gaGroupJs = json_encode($gaContentGroup, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    ?>
    <!-- Google Analytics 4 (Consent Mode v2) -->
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('consent', 'default', {
            analytics_storage: 'granted',
            ad_storage: 'granted',
            ad_user_data: 'granted',
            ad_personalization: 'granted'
        });
        window.pkdHasAnalyticsConsent = true;
        try {
            if (localStorage.getItem('cookie_consent') === 'declined') {
                window.pkdHasAnalyticsConsent = false;
                gtag('consent', 'update', {
                    analytics_storage: 'denied',
                    ad_storage: 'denied',
                    ad_user_data: 'denied',
                    ad_personalization: 'denied'
                });
            }
        } catch (e) {}
        window.pkdTrack = function (name, params) {
            if (typeof gtag === 'function') {
                gtag('event', name, params || {});
            }
        };
        window.pkdSetConsent = function (accepted) {
            var state = accepted ? 'granted' : 'denied';
            window.pkdHasAnalyticsConsent = !!accepted;
            gtag('consent', 'update', {
                analytics_storage: state,
                ad_storage: state,
                ad_user_data: state,
                ad_personalization: state
            });
        };
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo htmlspecialchars(GA_MEASUREMENT_ID, ENT_QUOTES, 'UTF-8'); ?>"></script>
    <script>
        gtag('js', new Date());
        gtag('config', <?php echo $gaIdJs; ?>, {
            send_page_view: false,
            page_title: <?php echo $gaTitleJs; ?>
        });
        gtag('event', 'page_view', {
            page_title: <?php echo $gaTitleJs; ?>,
            content_group: <?php echo $gaGroupJs; ?>,
            page_type: <?php echo $gaPageTypeJs; ?>

        });
    </script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=<?php echo htmlspecialchars(ADSENSE_CLIENT_ID, ENT_QUOTES, 'UTF-8'); ?>"
         crossorigin="anonymous"></script>
    
    <!-- Schema.org Structured Data -->
    <script type="application/ld+json">
    [
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "Palakkad Tourism",
        "url": "<?php echo SITE_URL; ?>",
        "logo": "<?php echo SITE_URL . BASE_PATH; ?>/assets/images/palakkad-logo.png",
        "description": "Official tourism website for Palakkad, Kerala - Discover temples, churches, mosques, festivals, and tourist attractions",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Palakkad",
            "addressRegion": "Kerala",
            "addressCountry": "IN"
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "email": "<?php echo SITE_EMAIL; ?>",
            "contactType": "Tourism Information"
        },
        "sameAs": [
            "https://www.facebook.com/palakkadtourism",
            "https://x.com/palakkadtourism"
        ]
    }
    <?php if ($seoIsHomepage): ?>,
    <?php endif; ?>
    <?php if ($seoIsHomepage): ?>
    {
        "@context": "https://schema.org",
        "@type": "TouristDestination",
        "name": "Palakkad",
        "description": <?php echo json_encode($seoDescription, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>,
        "url": <?php echo json_encode($seoCanonicalUrl, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>,
        "image": <?php echo json_encode($seoOgImage, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE); ?>,
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Palakkad",
            "addressRegion": "Kerala",
            "addressCountry": "IN"
        },
        "geo": {
            "@type": "GeoCoordinates",
            "latitude": "10.7867",
            "longitude": "76.6548"
        }
    }
    <?php endif; ?>
    <?php
    // Add page-specific structured data (do not use $currentPage — listing pages use it for pagination)
    $schemaPageSlug = basename($_SERVER['PHP_SELF'], '.php');
    $pageSchema = '';
    
    // Breadcrumb Schema
    if (isset($breadcrumbs) && !empty($breadcrumbs)) {
        $breadcrumbItems = [];
        $position = 1;
        $crumbCount = count($breadcrumbs);
        foreach ($breadcrumbs as $crumb) {
            $crumbUrl = isset($crumb['url']) ? trim((string) $crumb['url']) : '';
            $itemUrl = ($crumbUrl === '' || $position === $crumbCount)
                ? $seoCanonicalUrl
                : seoPublicUrl($crumbUrl);
            $breadcrumbItems[] = [
                "@type" => "ListItem",
                "position" => $position++,
                "name" => $crumb['title'],
                "item" => $itemUrl
            ];
        }
        $pageSchema .= ',
    {
        "@context": "https://schema.org",
        "@type": "BreadcrumbList",
        "itemListElement": ' . json_encode($breadcrumbItems) . '
    }';
    }
    
    // Article Schema for content pages
    if (in_array($schemaPageSlug, ['about', 'history', 'rivers', 'mountain-ranges-passes', 'art-culture', 'customs', 'fort'])) {
        $articleSchema = [
            '@context'         => 'https://schema.org',
            '@type'            => 'Article',
            'headline'         => isset($pageTitle) ? $pageTitle : 'Palakkad Tourism',
            'description'      => isset($pageDescription) ? $pageDescription : '',
            'author'           => ['@type' => 'Organization', 'name' => 'Palakkad Tourism'],
            'publisher'        => [
                '@type' => 'Organization',
                'name'  => 'Palakkad Tourism',
                'logo'  => ['@type' => 'ImageObject', 'url' => SITE_URL . BASE_PATH . '/assets/images/palakkad-logo.png'],
            ],
            'datePublished'    => $seoArticlePublished,
            'dateModified'     => $seoArticleModified,
            'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $seoCanonicalUrl],
        ];
        $pageSchema .= ',' . json_encode($articleSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    }
    
    $webPageType = 'WebPage';
    if (isset($pageSchemaType) && is_string($pageSchemaType) && $pageSchemaType !== '') {
        $webPageType = $pageSchemaType;
    } elseif (in_array($schemaPageSlug, ['hospitals', 'hospitals-government', 'hospitals-private', 'hospitals-ayurveda', 'hospitals-homeopathy'], true)) {
        $webPageType = 'MedicalWebPage';
    } elseif (in_array($schemaPageSlug, ['places', 'place'], true)) {
        $webPageType = 'CollectionPage';
    }

    $pageSchema .= ',
    {
        "@context": "https://schema.org",
        "@type": ' . json_encode($webPageType) . ',
        "name": ' . json_encode($seoTitle, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . ',
        "description": ' . json_encode($seoDescription, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . ',
        "url": ' . json_encode($seoCanonicalUrl, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ',
        "inLanguage": "en-IN",
        "isPartOf": {
            "@type": "WebSite",
            "name": "Palakkad Tourism",
            "url": "' . SITE_URL . '"
        }
    }';
    
    echo $pageSchema;
    ?>
    ]
    </script>
    
    <?php if (!empty($pageHeadExtra)) echo $pageHeadExtra; ?>
</head>
<body>
    <a class="visually-hidden-focusable" href="#main-content">Skip to content</a>
    <!-- Logo Section -->
    <div class="logo-section fixed-top">
        <div class="container-fluid px-2 px-sm-3">
            <div class="logo-section-inner d-flex align-items-center justify-content-between">
                <aside class="logo-strip logo-strip--left d-none d-xl-flex" aria-label="Palakkad highlights">
                    <div class="logo-strip-content">
                        <p class="logo-strip-label">Palakkad district</p>
                        <p class="logo-strip-text">Gateway to <strong>Silent Valley</strong>, <strong>Malampuzha</strong> &amp; the <strong>Western Ghats</strong> — paddy fields, dams, and heritage towns across <strong>12 taluks</strong>.</p>
                    </div>
                </aside>
                <div class="logo-section-brand flex-grow-1 flex-xl-grow-0 text-center">
                    <a href="<?php echo BASE_PATH; ?>/" class="brand-link text-center text-decoration-none d-inline-block" aria-label="Palakkad Tourism Home">
                        <img src="<?php echo BASE_PATH; ?>/assets/images/palakkad-logo.png" alt="Palakkad Tourism Logo - Explore Kerala's Heritage" class="main-logo" width="250" height="150">
                        <span class="brand-tagline d-none d-md-block">Palakkad · Kerala’s heritage</span>
                    </a>
                </div>
                <aside class="logo-strip logo-strip--right d-none d-xl-flex" aria-label="Quick links">
                    <div class="logo-strip-content text-xl-end">
                        <p class="logo-strip-label">Explore</p>
                        <nav class="logo-strip-nav">
                            <a href="<?php echo BASE_PATH; ?>/places.php"><i class="bi bi-geo-alt" aria-hidden="true"></i> Places</a>
                            <span class="logo-strip-dot" aria-hidden="true">·</span>
                            <a href="<?php echo BASE_PATH; ?>/festivals.php"><i class="bi bi-calendar3" aria-hidden="true"></i> Festivals</a>
                            <span class="logo-strip-dot" aria-hidden="true">·</span>
                            <a href="<?php echo BASE_PATH; ?>/fort.php"><i class="bi bi-bank" aria-hidden="true"></i> Fort</a>
                            <span class="logo-strip-dot" aria-hidden="true">·</span>
                            <a href="<?php echo BASE_PATH; ?>/history.php"><i class="bi bi-clock-history" aria-hidden="true"></i> History</a>
                        </nav>
                        <p class="logo-strip-nickname d-none d-xxl-block mb-0">“Rice bowl of Kerala”</p>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    <!-- Main navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark menu-bar fixed-top" role="navigation" aria-label="Main navigation">
        <div class="container">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="<?php echo BASE_PATH; ?>/religious-places.php" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Religious Places
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/temples.php">Temples</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/churches.php">Churches</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/mosques.php">Mosques</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/jain-temple.php">Jain Temple</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_PATH; ?>/festivals.php">Festivals</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo BASE_PATH; ?>/places.php">Places to Visit</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="communityDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Community
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="communityDropdown">
                            <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/yellow-pages.php">Yellow Pages directory</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/vintage-gallery.php"><i class="bi bi-camera me-1"></i>Vintage Gallery</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/heritage-villages.php">Heritage Villages</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/institutions.php">Institutions</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="aboutDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            About
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="aboutDropdown">
                            <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/about.php">About Palakkad</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/how-to-reach.php">How to Reach</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/art-culture.php">Art and Culture</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/customs.php">Customs of Palakkad</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/villages-taluks.php">Villages and Taluks</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="historyDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            History
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="historyDropdown">
                            <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/history.php">History</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/fort.php">Fort</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="geographyDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Geography
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="geographyDropdown">
                            <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/rivers.php">Rivers</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/mountain-ranges-passes.php">Mountain Ranges & Passes</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="hospitalsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Hospitals
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="hospitalsDropdown">
                            <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/hospitals.php">All Hospitals</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/hospitals-government.php">Government Hospitals</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/hospitals-private.php">Private Hospitals</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/hospitals-ayurveda.php">Ayurveda Hospitals</a></li>
                            <li><a class="dropdown-item" href="<?php echo BASE_PATH; ?>/hospitals-homeopathy.php">Homeopathy Hospitals</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <?php 
    // Include AdSense helper
    require_once __DIR__ . '/adsense.php';
    
    // Include breadcrumbs for all pages except homepage
    // Note: Individual pages can set $breadcrumbs array before including header
    // or define breadcrumbs inline. This will only show if $breadcrumbs is set.
    if (basename($_SERVER['PHP_SELF']) !== 'index.php' && isset($breadcrumbs)) {
        require_once __DIR__ . '/breadcrumbs.php';
    }
    ?>

