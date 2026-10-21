<?php
header('Content-Type: application/xml; charset=utf-8');
require_once __DIR__ . '/config/config.php';

// Must match canonical URLs (SITE_URL + BASE_PATH); never hardcode a stale path — wrong <loc> entries hurt indexing.
$baseUrl = rtrim(SITE_URL, '/');
$currentDate = date('Y-m-d');

// Returns the file's real last-modified date, falling back to $currentDate.
function palakkad_lm($file, $fallback) {
    $m = @filemtime(__DIR__ . '/' . ltrim($file, '/'));
    return $m ? date('Y-m-d', $m) : $fallback;
}

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/config/url_helper.php';

header('Cache-Control: public, max-age=3600');

/**
 * @return string XML fragments for active detail pages (places, temples, etc.)
 */
function palakkad_sitemap_build_detail_xml(mysqli $conn, $baseUrl, $fallbackDate) {
    $out = '';
    $detailPages = [
        ['table' => 'places', 'kind' => 'place', 'changefreq' => 'monthly', 'priority' => '0.75'],
        ['table' => 'temples', 'kind' => 'temple', 'changefreq' => 'monthly', 'priority' => '0.75'],
        ['table' => 'churches', 'kind' => 'church', 'changefreq' => 'monthly', 'priority' => '0.75'],
        ['table' => 'mosques', 'kind' => 'mosque', 'changefreq' => 'monthly', 'priority' => '0.75'],
        ['table' => 'festivals', 'kind' => 'festival', 'changefreq' => 'weekly', 'priority' => '0.8'],
        ['table' => 'heritage_villages', 'kind' => 'heritage-village', 'changefreq' => 'monthly', 'priority' => '0.72'],
        ['table' => 'institutions', 'kind' => 'institution', 'changefreq' => 'monthly', 'priority' => '0.72'],
    ];
    foreach ($detailPages as $def) {
        $table = $def['table'];
        $sql = "SELECT id, name, DATE(COALESCE(updated_at, created_at)) AS lm FROM `{$table}` WHERE status = 'active' ORDER BY id";
        $result = $conn->query($sql);
        if (!$result) {
            continue;
        }
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
        $slugs = seoSlugMap($rows);
        foreach ($rows as $row) {
            $id = (int) $row['id'];
            if ($def['kind'] === 'temple' && $id === 1) {
                continue;
            }
            $lm = !empty($row['lm']) ? $row['lm'] : $fallbackDate;
            $rel = seoEntityUrl($def['kind'], $id, $row['name'], $slugs[$id] ?? null);
            $loc = htmlspecialchars(seoPublicUrl($rel), ENT_XML1 | ENT_QUOTES, 'UTF-8');
            $lmEsc = htmlspecialchars($lm, ENT_XML1 | ENT_QUOTES, 'UTF-8');
            $cf = htmlspecialchars($def['changefreq'], ENT_XML1 | ENT_QUOTES, 'UTF-8');
            $pr = htmlspecialchars($def['priority'], ENT_XML1 | ENT_QUOTES, 'UTF-8');
            $out .= "    <url>\n        <loc>{$loc}</loc>\n        <lastmod>{$lmEsc}</lastmod>\n        <changefreq>{$cf}</changefreq>\n        <priority>{$pr}</priority>\n    </url>\n";
        }
    }
    return $out;
}

/**
 * Detail URLs for active Yellow Pages listings (requires directory_entries table).
 */
function palakkad_sitemap_directory_xml(mysqli $conn, $baseUrl, $fallbackDate) {
    $out = '';
    $sql = "SELECT slug, DATE(COALESCE(updated_at, created_at)) AS lm FROM directory_entries WHERE status = 'active' AND slug <> '' ORDER BY id";
    $result = @$conn->query($sql);
    if (!$result) {
        return $out;
    }
    while ($row = $result->fetch_assoc()) {
        $slug = (string) $row['slug'];
        if ($slug === '') {
            continue;
        }
        $lm = !empty($row['lm']) ? $row['lm'] : $fallbackDate;
        $qs = http_build_query(['slug' => $slug], '', '&amp;', PHP_QUERY_RFC3986);
        $loc = htmlspecialchars($baseUrl . '/yellow-page.php?' . $qs, ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $lmEsc = htmlspecialchars($lm, ENT_XML1 | ENT_QUOTES, 'UTF-8');
        $out .= "    <url>\n        <loc>{$loc}</loc>\n        <lastmod>{$lmEsc}</lastmod>\n        <changefreq>weekly</changefreq>\n        <priority>0.72</priority>\n    </url>\n";
    }
    $result->free();
    return $out;
}

$sitemapDetailXml = '';
$conn = getDBConnection();
if ($conn && !$conn->connect_error) {
    $sitemapDetailXml = palakkad_sitemap_build_detail_xml($conn, $baseUrl, $currentDate);
    $sitemapDetailXml .= palakkad_sitemap_directory_xml($conn, $baseUrl, $currentDate);
    closeDBConnection($conn);
}
?>
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
        http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
    
    <!-- Homepage -->
    <url>
        <loc><?php echo $baseUrl; ?>/</loc>
        <lastmod><?php echo palakkad_lm('index.php', $currentDate); ?></lastmod>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>

    <!-- About Page -->
    <url>
        <loc><?php echo $baseUrl; ?>/about.php</loc>
        <lastmod><?php echo palakkad_lm('about.php', $currentDate); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    <!-- How to Reach Page -->
    <url>
        <loc><?php echo $baseUrl; ?>/how-to-reach.php</loc>
        <lastmod><?php echo palakkad_lm('how-to-reach.php', $currentDate); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.82</priority>
    </url>

    <!-- Art and Culture Page -->
    <url>
        <loc><?php echo $baseUrl; ?>/art-culture.php</loc>
        <lastmod><?php echo palakkad_lm('art-culture.php', $currentDate); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    <!-- Customs Page -->
    <url>
        <loc><?php echo $baseUrl; ?>/customs.php</loc>
        <lastmod><?php echo palakkad_lm('customs.php', $currentDate); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    <!-- Villages and Taluks Page -->
    <url>
        <loc><?php echo $baseUrl; ?>/villages-taluks.php</loc>
        <lastmod><?php echo palakkad_lm('villages-taluks.php', $currentDate); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    <!-- History Page -->
    <url>
        <loc><?php echo $baseUrl; ?>/history.php</loc>
        <lastmod><?php echo palakkad_lm('history.php', $currentDate); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    <!-- Fort Page -->
    <url>
        <loc><?php echo $baseUrl; ?>/fort.php</loc>
        <lastmod><?php echo palakkad_lm('fort.php', $currentDate); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    
    <!-- West Wide Way — Dams -->
    <url>
        <loc><?php echo $baseUrl; ?>/dam-malampuzha.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc><?php echo $baseUrl; ?>/dam-parambikulam.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc><?php echo $baseUrl; ?>/dam-mangalam.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc><?php echo $baseUrl; ?>/dam-meenkara.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc><?php echo $baseUrl; ?>/dam-chulliyar.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc><?php echo $baseUrl; ?>/dam-walayar.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc><?php echo $baseUrl; ?>/dam-pothundy.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    <url>
        <loc><?php echo $baseUrl; ?>/dam-siruvani.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    
    <!-- Rivers Page -->
    <url>
        <loc><?php echo $baseUrl; ?>/rivers.php</loc>
        <lastmod><?php echo palakkad_lm('rivers.php', $currentDate); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>

    <!-- Mountain Ranges & Passes Page -->
    <url>
        <loc><?php echo $baseUrl; ?>/mountain-ranges-passes.php</loc>
        <lastmod><?php echo palakkad_lm('mountain-ranges-passes.php', $currentDate); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    
    <!-- Hospitals Pages -->
    <url>
        <loc><?php echo $baseUrl; ?>/hospitals.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    
    <url>
        <loc><?php echo $baseUrl; ?>/hospitals-government.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    
    <url>
        <loc><?php echo $baseUrl; ?>/hospitals-private.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    
    <url>
        <loc><?php echo $baseUrl; ?>/hospitals-ayurveda.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    
    <url>
        <loc><?php echo $baseUrl; ?>/hospitals-homeopathy.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    
    <!-- Temples -->
    <url>
        <loc><?php echo $baseUrl; ?>/temples.php</loc>
        <lastmod><?php echo palakkad_lm('temples.php', $currentDate); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>

    <!-- Churches -->
    <url>
        <loc><?php echo $baseUrl; ?>/churches.php</loc>
        <lastmod><?php echo palakkad_lm('churches.php', $currentDate); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>

    <!-- Mosques -->
    <url>
        <loc><?php echo $baseUrl; ?>/mosques.php</loc>
        <lastmod><?php echo palakkad_lm('mosques.php', $currentDate); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>

    <!-- Jain Temple -->
    <url>
        <loc><?php echo $baseUrl; ?>/jain-temple.php</loc>
        <lastmod><?php echo palakkad_lm('jain-temple.php', $currentDate); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.85</priority>
    </url>

    <!-- Festivals -->
    <url>
        <loc><?php echo $baseUrl; ?>/festivals.php</loc>
        <lastmod><?php echo palakkad_lm('festivals.php', $currentDate); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>

    <!-- Places to Visit -->
    <url>
        <loc><?php echo $baseUrl; ?>/places.php</loc>
        <lastmod><?php echo palakkad_lm('places.php', $currentDate); ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.9</priority>
    </url>

    <!-- Heritage Villages Page -->
    <url>
        <loc><?php echo $baseUrl; ?>/heritage-villages.php</loc>
        <lastmod><?php echo palakkad_lm('heritage-villages.php', $currentDate); ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
    </url>
    
    <!-- Municipalities -->
    <url>
        <loc><?php echo $baseUrl; ?>/municipality-palakkad.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    
    <url>
        <loc><?php echo $baseUrl; ?>/municipality-ottappalam.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    
    <url>
        <loc><?php echo $baseUrl; ?>/municipality-shoranur.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    
    <url>
        <loc><?php echo $baseUrl; ?>/municipality-chittur.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    
    <url>
        <loc><?php echo $baseUrl; ?>/municipality-pattambi.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    
    <url>
        <loc><?php echo $baseUrl; ?>/municipality-mannarkkad.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    
    <url>
        <loc><?php echo $baseUrl; ?>/municipality-cherpulassery.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.7</priority>
    </url>
    
    <url>
        <loc><?php echo $baseUrl; ?>/institutions.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.85</priority>
    </url>
    
    <url>
        <loc><?php echo $baseUrl; ?>/kerala-assembly-election-2026.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.75</priority>
    </url>
    
    <url>
        <loc><?php echo $baseUrl; ?>/palakkad-assembly-constituencies-2026.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.75</priority>
    </url>
    
    <url>
        <loc><?php echo $baseUrl; ?>/donate-blood.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    
    <!-- Business directory -->
    <url>
        <loc><?php echo $baseUrl; ?>/yellow-pages.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.78</priority>
    </url>
    <url>
        <loc><?php echo $baseUrl; ?>/religious-places.php</loc>
        <lastmod><?php echo $currentDate; ?></lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.82</priority>
    </url>
    
<?php echo $sitemapDetailXml; ?>
</urlset>

