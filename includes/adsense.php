<?php
/**
 * AdSense helper — kept as a no-op so existing pages that call
 * displayAdSense(...) continue to work after ads were removed
 * from the site. Safe to delete this file once all callers are
 * cleaned up.
 */
if (!function_exists('displayAdSense')) {
    function displayAdSense($position = 'content', $style = '') {
        // Intentionally empty — AdSense has been removed from the site.
    }
}
