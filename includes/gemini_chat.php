<?php
/**
 * Palakkad-only Gemini chat helpers (PHP + Gemini REST, no Composer).
 */

function chatbotClientIp() {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '0.0.0.0';
}

function chatbotTextLen($text) {
    return function_exists('mb_strlen') ? mb_strlen($text) : strlen($text);
}

function chatbotSameOrigin() {
    $fetchSite = strtolower((string) ($_SERVER['HTTP_SEC_FETCH_SITE'] ?? ''));
    if ($fetchSite === 'same-origin') {
        return true;
    }
    $host = strtolower((string) ($_SERVER['HTTP_HOST'] ?? ''));
    if ($host === '') {
        return false;
    }
    $host = preg_replace('/:\d+$/', '', $host);
    foreach (['HTTP_ORIGIN', 'HTTP_REFERER'] as $header) {
        $value = (string) ($_SERVER[$header] ?? '');
        if ($value === '') {
            continue;
        }
        $parsedHost = parse_url($value, PHP_URL_HOST);
        if (!is_string($parsedHost) || $parsedHost === '') {
            continue;
        }
        if (strcasecmp($parsedHost, $host) === 0) {
            return true;
        }
    }
    return false;
}

function chatbotRateAllow($limitPerHour) {
    $dir = dirname(__DIR__) . '/logs';
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
    $file = $dir . '/chat_rate.json';
    $now = time();
    $key = hash('sha256', chatbotClientIp());
    $data = [];
    if (is_readable($file)) {
        $raw = file_get_contents($file);
        $decoded = json_decode((string) $raw, true);
        if (is_array($decoded)) {
            $data = $decoded;
        }
    }
    $hits = isset($data[$key]) && is_array($data[$key]) ? $data[$key] : [];
    $hits = array_values(array_filter($hits, static function ($t) use ($now) {
        return is_int($t) && $t > ($now - 3600);
    }));
    if (count($hits) >= $limitPerHour) {
        return false;
    }
    $last = $hits ? (int) end($hits) : 0;
    if ($last > 0 && ($now - $last) < 2) {
        return false;
    }
    $hits[] = $now;
    $data[$key] = $hits;
    if (count($data) > 2000) {
        $data = array_slice($data, -1000, null, true);
    }
    @file_put_contents($file, json_encode($data), LOCK_EX);
    return true;
}

function chatbotLooksOffTopic($message) {
    $onTopic = '/palakkad|palghat|\x{0d2a}\x{0d3e}\x{0d32}\x{0d15}\x{0d4d}\x{0d15}\x{0d3e}\x{0d1f}\x{0d4d}|kerala|malampuzha|nelliyampathy|nelliyampathi|silent\s*valley|parambikulam|kollengode|kollangode|ottapalam|ottappalam|chittur|mannarkkad|pattambi|shoranur|cherpulassery|kalpathy|kalpathi|nemmara|malabar|temple|church|mosque|jain|festival|dam|hospital|heritage|paddy|rice\s*bowl|how\s*to\s*reach|tourist|tourism|fort|river|pass|ayurveda|yellow\s*pages/iu';
    if (preg_match($onTopic, $message)) {
        return false;
    }
    $offTopic = '/\b(python|javascript|typescript|php\s+code|bitcoin|crypto|stock\s+market|iphone|android\s+app|windows\s+11|chatgpt|homework|essay|recipe|trump|biden|election\s+result|netflix|football\s+score|cricket\s+score)\b/i';
    return (bool) preg_match($offTopic, $message);
}

function chatbotOffTopicReply() {
    return 'I only answer questions about Palakkad district — places, temples, travel, festivals, and local tips. Try asking how to reach Palakkad, or about Silent Valley, Palakkad Fort, or Kalpathy.';
}

function chatbotExtractTerms($message) {
    $stop = ['the', 'and', 'for', 'are', 'but', 'not', 'you', 'all', 'can', 'how', 'what', 'where', 'when', 'which', 'this', 'that', 'with', 'from', 'about', 'please', 'tell', 'give', 'best', 'near', 'visit', 'there', 'have', 'some', 'any'];
    $parts = preg_split('/\s+/u', $message) ?: [];
    $terms = [];
    foreach ($parts as $part) {
        $word = strtolower((string) preg_replace('/[^\p{L}\p{N}]+/u', '', $part));
        if (strlen($word) < 3 || in_array($word, $stop, true)) {
            continue;
        }
        $terms[$word] = $word;
        if (count($terms) >= 4) {
            break;
        }
    }
    return array_values($terms);
}

function chatbotLookupContext($message) {
    if (!function_exists('getDBConnection')) {
        return '';
    }
    $terms = chatbotExtractTerms($message);
    if (!$terms) {
        return '';
    }
    try {
        $conn = getDBConnection();
    } catch (Throwable $e) {
        return '';
    }

    $tables = [
        ['temples', 'temple'],
        ['places', 'place'],
        ['festivals', 'festival'],
        ['hospitals', 'hospital'],
        ['heritage_villages', 'heritage village'],
        ['churches', 'church'],
        ['mosques', 'mosque'],
    ];
    $lines = [];
    foreach ($tables as $pair) {
        if (count($lines) >= 8) {
            break;
        }
        list($table, $label) = $pair;
        $like = [];
        $types = '';
        $values = [];
        foreach ($terms as $term) {
            $like[] = '(name LIKE ? OR description LIKE ?)';
            $types .= 'ss';
            $needle = '%' . $term . '%';
            $values[] = $needle;
            $values[] = $needle;
        }
        $sql = 'SELECT name, LEFT(description, 180) AS blurb FROM `' . $table . '` WHERE status = \'active\' AND (' . implode(' OR ', $like) . ') LIMIT 3';
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            continue;
        }
        $stmt->bind_param($types, ...$values);
        if (!$stmt->execute()) {
            $stmt->close();
            continue;
        }
        $result = $stmt->get_result();
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $name = trim((string) ($row['name'] ?? ''));
                $blurb = trim((string) ($row['blurb'] ?? ''));
                if ($name === '') {
                    continue;
                }
                $lines[] = '- ' . $label . ': ' . $name . ($blurb !== '' ? ' — ' . $blurb : '');
            }
        }
        $stmt->close();
    }
    closeDBConnection($conn);
    return implode("\n", array_slice($lines, 0, 8));
}

function chatbotSystemInstruction($siteNotes) {
    return <<<TXT
You are the Palakkad Tourism guide on palakkadtourism.org.
Answer ONLY questions about Palakkad district, Kerala, India (also called Palghat / പാലക്കാട്): places, temples, churches, mosques, the Jain temple, dams, Silent Valley, Parambikulam, Nelliyampathy, Malampuzha, festivals, food, how to reach, hospitals, heritage, culture, weather for visiting, and trip planning inside the district.

If the visitor asks about anything outside Palakkad district, refuse in 1–2 sentences and suggest a Palakkad topic. Do not answer general knowledge, other cities (unless comparing a route into Palakkad), politics, medical advice, bookings, prices you do not know, or programming.

Use SITE NOTES when they match. Be concise (under 120 words). Prefer English; reply in Malayalam if the visitor writes Malayalam. Do not invent opening hours, fees, or phone numbers. If unsure, say so and point to a page on the site.

SITE NOTES:
- Palakkad is the Rice Bowl of Kerala and the gateway through the Palakkad Gap in the Western Ghats.
- How to reach: Palakkad Junction railway; Coimbatore (CJB) is the nearest major airport; road via NH544.
- Useful pages: /places.php, /temples.php, /festivals.php, /how-to-reach.php, /hospitals.php, /jain-temple.php, /heritage-villages.php
{$siteNotes}
TXT;
}

function chatbotSanitizeHistory($history) {
    if (!is_array($history)) {
        return [];
    }
    $clean = [];
    foreach (array_slice($history, -8) as $item) {
        if (!is_array($item)) {
            continue;
        }
        $role = ($item['role'] ?? '') === 'model' ? 'model' : 'user';
        $text = trim((string) ($item['text'] ?? ''));
        if ($text === '' || chatbotTextLen($text) > 500) {
            continue;
        }
        $clean[] = ['role' => $role, 'text' => $text];
    }
    return $clean;
}

function chatbotCacheStopwords() {
    return [
        'the', 'and', 'for', 'are', 'but', 'not', 'you', 'all', 'can', 'how', 'what',
        'where', 'when', 'which', 'this', 'that', 'with', 'from', 'about', 'please',
        'tell', 'give', 'best', 'near', 'visit', 'there', 'have', 'some', 'any',
        'does', 'did', 'could', 'would', 'should', 'will', 'just', 'also', 'into',
        'your', 'our', 'their', 'them', 'they', 'who', 'why', 'was', 'were',
        'is', 'its', 'let', 'know', 'need', 'want', 'help', 'info', 'information',
        'me', 'my', 'we', 'do', 'to', 'in', 'on', 'of', 'at', 'or', 'an', 'a',
    ];
}

function chatbotCacheTokens($message) {
    $lower = function_exists('mb_strtolower') ? mb_strtolower($message, 'UTF-8') : strtolower($message);
    $parts = preg_split('/\s+/u', (string) preg_replace('/[^\p{L}\p{N}\s]+/u', ' ', $lower)) ?: [];
    $stop = chatbotCacheStopwords();
    $tokens = [];
    foreach ($parts as $part) {
        if (strlen($part) < 3 || in_array($part, $stop, true)) {
            continue;
        }
        $tokens[$part] = $part;
    }
    return array_values($tokens);
}

function chatbotCacheKey(array $tokens) {
    $sorted = $tokens;
    sort($sorted, SORT_STRING);
    return sha1(implode('|', $sorted));
}

function chatbotCacheDir() {
    $dir = dirname(__DIR__) . '/logs/chat_cache';
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
        @chmod($dir, 0777);
    }
    return $dir;
}

function chatbotCacheReadIndex() {
    $file = chatbotCacheDir() . '/index.json';
    if (!is_readable($file)) {
        return [];
    }
    $decoded = json_decode((string) file_get_contents($file), true);
    return is_array($decoded) ? $decoded : [];
}

function chatbotCacheWriteIndex(array $index) {
    $file = chatbotCacheDir() . '/index.json';
    @file_put_contents($file, json_encode(array_values($index)), LOCK_EX);
    @chmod($file, 0666);
}

function chatbotCacheFresh($row, $now) {
    $ts = (int) ($row['ts'] ?? 0);
    return $ts > 0 && ($now - $ts) < GEMINI_CACHE_TTL;
}

function chatbotCacheReply($key) {
    $file = chatbotCacheDir() . '/' . preg_replace('/[^a-f0-9]/', '', $key) . '.json';
    if (!is_readable($file)) {
        return null;
    }
    $data = json_decode((string) file_get_contents($file), true);
    $reply = trim((string) ($data['reply'] ?? ''));
    return $reply !== '' ? $reply : null;
}

function chatbotCacheSimilarEnough(array $a, array $b) {
    if (count($a) < 2 || count($b) < 2) {
        return 0.0;
    }
    $mapA = array_fill_keys($a, true);
    $mapB = array_fill_keys($b, true);
    $intersect = array_intersect_key($mapA, $mapB);
    $overlap = count($intersect);
    if ($overlap < 2) {
        return 0.0;
    }
    $union = count($mapA) + count($mapB) - $overlap;
    $jaccard = $union > 0 ? ($overlap / $union) : 0.0;
    $smaller = count($a) <= count($b) ? $a : $b;
    $larger = count($a) <= count($b) ? $b : $a;
    $missing = count(array_diff($smaller, $larger));
    $ratio = count($larger) / max(1, count($smaller));
    if ($jaccard >= 0.75 || ($missing <= 1 && $ratio <= 2.0)) {
        return $jaccard > 0 ? $jaccard : 0.76;
    }
    return 0.0;
}

function chatbotCacheFind($message) {
    $tokens = chatbotCacheTokens($message);
    if (count($tokens) < 2) {
        return null;
    }
    $now = time();
    $exactKey = chatbotCacheKey($tokens);
    $index = chatbotCacheReadIndex();
    foreach ($index as $row) {
        if (!chatbotCacheFresh($row, $now)) {
            continue;
        }
        if (($row['key'] ?? '') === $exactKey) {
            $reply = chatbotCacheReply($exactKey);
            if ($reply !== null) {
                return $reply;
            }
        }
    }
    $bestScore = 0.0;
    $bestKey = '';
    foreach ($index as $row) {
        if (!chatbotCacheFresh($row, $now)) {
            continue;
        }
        $rowTokens = isset($row['tokens']) && is_array($row['tokens']) ? $row['tokens'] : [];
        $score = chatbotCacheSimilarEnough($tokens, $rowTokens);
        if ($score > $bestScore) {
            $bestScore = $score;
            $bestKey = (string) ($row['key'] ?? '');
        }
    }
    if ($bestKey === '') {
        return null;
    }
    return chatbotCacheReply($bestKey);
}

function chatbotCacheStore($message, $reply) {
    $reply = trim((string) $reply);
    $tokens = chatbotCacheTokens($message);
    if ($reply === '' || count($tokens) < 2) {
        return;
    }
    $key = chatbotCacheKey($tokens);
    $now = time();
    $payload = [
        'q' => $message,
        'reply' => $reply,
        'ts' => $now,
    ];
    $file = chatbotCacheDir() . '/' . $key . '.json';
    @file_put_contents($file, json_encode($payload, JSON_UNESCAPED_UNICODE), LOCK_EX);
    @chmod($file, 0666);

    $index = chatbotCacheReadIndex();
    $kept = [];
    $seen = [$key => true];
    $kept[] = ['key' => $key, 'tokens' => $tokens, 'ts' => $now];
    foreach ($index as $row) {
        $rowKey = (string) ($row['key'] ?? '');
        if ($rowKey === '' || isset($seen[$rowKey]) || !chatbotCacheFresh($row, $now)) {
            continue;
        }
        $seen[$rowKey] = true;
        $kept[] = $row;
    }
    if (count($kept) > GEMINI_CACHE_MAX) {
        $kept = array_slice($kept, 0, GEMINI_CACHE_MAX);
    }
    chatbotCacheWriteIndex($kept);
}

function chatbotCallGemini($apiKey, $model, $system, $history, $message) {
    $model = preg_replace('#^models/#', '', trim($model));
    if ($model === '') {
        $model = 'gemini-3.8-flash';
    }
    $url = 'https://generativelanguage.googleapis.com/v1beta/models/'
        . rawurlencode($model)
        . ':generateContent?key=' . rawurlencode($apiKey);

    $contents = [];
    foreach ($history as $turn) {
        $contents[] = [
            'role' => $turn['role'],
            'parts' => [['text' => $turn['text']]],
        ];
    }
    $contents[] = [
        'role' => 'user',
        'parts' => [['text' => $message]],
    ];

    $payload = [
        'systemInstruction' => [
            'parts' => [['text' => $system]],
        ],
        'contents' => $contents,
        'generationConfig' => [
            'temperature' => 0.4,
            'maxOutputTokens' => GEMINI_MAX_OUTPUT_TOKENS,
        ],
    ];

    $body = json_encode($payload);
    $response = '';
    $httpCode = 0;

    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => $body,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 20,
            CURLOPT_CONNECTTIMEOUT => 8,
        ]);
        $response = (string) curl_exec($ch);
        $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $err = curl_error($ch);
        curl_close($ch);
        if ($response === '' && $err !== '') {
            return [false, 'The guide is temporarily unavailable. Please try again in a moment.'];
        }
    } else {
        $ctx = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/json\r\n",
                'content' => $body,
                'timeout' => 20,
                'ignore_errors' => true,
            ],
        ]);
        $response = (string) @file_get_contents($url, false, $ctx);
        if (isset($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $m)) {
            $httpCode = (int) $m[1];
        }
    }

    $data = json_decode($response, true);
    if (!is_array($data)) {
        return [false, 'The guide could not read a reply. Please try again.'];
    }
    if ($httpCode >= 400) {
        $apiMessage = (string) ($data['error']['message'] ?? '');
        if (stripos($apiMessage, 'API key') !== false) {
            return [false, 'Gemini is not configured yet. Add your key in config/gemini.local.php.'];
        }
        return [false, 'The guide is busy right now. Please try again shortly.'];
    }

    $text = '';
    $parts = $data['candidates'][0]['content']['parts'] ?? [];
    if (is_array($parts)) {
        foreach ($parts as $part) {
            if (isset($part['text'])) {
                $text .= (string) $part['text'];
            }
        }
    }
    $text = trim($text);
    if ($text === '') {
        return [false, 'I could not form an answer. Please ask again about Palakkad.'];
    }
    return [true, $text];
}
