<?php
/**
 * Slug helpers for directory categories and listings.
 */

function directory_slug_base(string $text): string {
    $text = strtolower(trim(preg_replace('/[^\p{L}\p{N}\s\-]+/u', '', $text)));
    $text = preg_replace('/[\s\-]+/', '-', $text);
    $text = trim($text, '-');
    $text = preg_replace('/-+/', '-', $text);
    return $text !== '' ? $text : 'listing';
}

/** @param 'directory_categories'|'directory_entries' $table */
function directory_unique_slug(mysqli $conn, string $baseName, string $table, ?int $excludeId = null): string {
    if (!in_array($table, ['directory_categories', 'directory_entries'], true)) {
        throw new InvalidArgumentException('Invalid directory slug table');
    }
    $slug = directory_slug_base($baseName);
    $candidate = $slug;
    $n = 2;
    while (true) {
        if ($excludeId !== null) {
            $stmt = $conn->prepare("SELECT id FROM `{$table}` WHERE slug = ? AND id <> ? LIMIT 1");
            $stmt->bind_param("si", $candidate, $excludeId);
        } else {
            $stmt = $conn->prepare("SELECT id FROM `{$table}` WHERE slug = ? LIMIT 1");
            $stmt->bind_param("s", $candidate);
        }
        $stmt->execute();
        $res = $stmt->get_result();
        $exists = $res && $res->num_rows > 0;
        $stmt->close();
        if (!$exists) {
            return $candidate;
        }
        $candidate = $slug . '-' . $n++;
    }
}
