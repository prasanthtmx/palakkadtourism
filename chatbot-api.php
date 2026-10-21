<?php
/**
 * Homepage Gemini chat endpoint. Not for crawlers. Key stays on the server.
 */
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/gemini.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/gemini_chat.php';

header('Content-Type: application/json; charset=UTF-8');
header('X-Robots-Tag: noindex, nofollow');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'reply' => 'Use POST.']);
    exit;
}

if (!chatbotSameOrigin()) {
    http_response_code(403);
    echo json_encode(['ok' => false, 'reply' => 'This chat can only be used from Palakkad Tourism pages.']);
    exit;
}

$raw = file_get_contents('php://input');
$input = json_decode((string) $raw, true);
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'reply' => 'Invalid request.']);
    exit;
}

$message = trim((string) ($input['message'] ?? ''));
if ($message === '' || chatbotTextLen($message) < 2 || chatbotTextLen($message) > 500) {
    http_response_code(400);
    echo json_encode(['ok' => false, 'reply' => 'Please type a short question about Palakkad (2–500 characters).']);
    exit;
}

if (!chatbotRateAllow(GEMINI_RATE_PER_HOUR)) {
    http_response_code(429);
    echo json_encode(['ok' => false, 'reply' => 'Please wait a moment before asking another question.']);
    exit;
}

if (chatbotLooksOffTopic($message)) {
    echo json_encode(['ok' => true, 'reply' => chatbotOffTopicReply()]);
    exit;
}

$key = trim((string) GEMINI_API_KEY);
if ($key === '' || $key === 'paste_gemini_api_key_here') {
    http_response_code(503);
    echo json_encode(['ok' => false, 'reply' => 'The Palakkad guide is not connected yet. Please try again after the site owner adds the Gemini key.']);
    exit;
}

$notes = chatbotLookupContext($message);
$system = chatbotSystemInstruction($notes !== '' ? "\nMatching listings:\n" . $notes : '');
$history = chatbotSanitizeHistory($input['history'] ?? []);

list($ok, $reply) = chatbotCallGemini($key, GEMINI_MODEL, $system, $history, $message);
if (!$ok) {
    http_response_code(502);
}

echo json_encode(['ok' => $ok, 'reply' => $reply], JSON_UNESCAPED_UNICODE);
