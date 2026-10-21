<?php
/**
 * Facebook Feed Integration
 * Displays Facebook posts on website pages
 */

/**
 * Display Facebook Page Plugin (Embedded Feed)
 * Shows recent posts from your Facebook page
 * 
 * @param string $pageUrl - Your Facebook page URL (e.g., 'https://www.facebook.com/palakkadtourism')
 * @param int $width - Width of the plugin (default: 500)
 * @param int $height - Height of the plugin (default: 500)
 * @param bool $showTimeline - Show timeline posts (default: true)
 * @param bool $showEvents - Show events (default: false)
 * @param bool $showMessages - Show messages button (default: false)
 */
function displayFacebookPagePlugin($pageUrl = 'https://www.facebook.com/palakkadtourism', $width = 500, $height = 500, $showTimeline = true, $showEvents = false, $showMessages = false) {
    ?>
    <div class="facebook-feed-container mb-4">
        <div class="fb-page" 
             data-href="<?php echo htmlspecialchars($pageUrl); ?>" 
             data-tabs="<?php echo $showTimeline ? 'timeline' : ''; ?><?php echo $showEvents ? ',events' : ''; ?>" 
             data-width="<?php echo $width; ?>" 
             data-height="<?php echo $height; ?>" 
             data-small-header="false" 
             data-adapt-container-width="true" 
             data-hide-cover="false" 
             data-show-facepile="true"
             data-hide-cta="<?php echo $showMessages ? 'false' : 'true'; ?>">
            <blockquote cite="<?php echo htmlspecialchars($pageUrl); ?>" class="fb-xfbml-parse-ignore">
                <a href="<?php echo htmlspecialchars($pageUrl); ?>">Palakkad Tourism</a>
            </blockquote>
        </div>
    </div>
    <?php
}

/**
 * Display Individual Facebook Post Embed
 * 
 * @param string $postUrl - Full URL to the Facebook post
 * @param int $width - Width of the embed (default: 500)
 */
function displayFacebookPost($postUrl, $width = 500) {
    ?>
    <div class="facebook-post-container mb-4">
        <div class="fb-post" 
             data-href="<?php echo htmlspecialchars($postUrl); ?>" 
             data-width="<?php echo $width; ?>">
        </div>
    </div>
    <?php
}

/**
 * Display Facebook Video Embed
 * 
 * @param string $videoUrl - Full URL to the Facebook video
 * @param int $width - Width of the embed (default: 500)
 */
function displayFacebookVideo($videoUrl, $width = 500) {
    ?>
    <div class="facebook-video-container mb-4">
        <div class="fb-video" 
             data-href="<?php echo htmlspecialchars($videoUrl); ?>" 
             data-width="<?php echo $width; ?>">
        </div>
    </div>
    <?php
}

/**
 * Initialize Facebook SDK
 * Call this function in the header or before using Facebook embeds
 */
function initFacebookSDK() {
    ?>
    <!-- Facebook SDK for JavaScript -->
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v18.0&appId=YOUR_APP_ID&autoLogAppEvents=1"></script>
    <?php
}

/**
 * Initialize Facebook SDK without App ID (for public embeds)
 * This works for public posts without requiring an app ID
 */
function initFacebookSDKPublic() {
    ?>
    <!-- Facebook SDK for JavaScript (Public Embeds) -->
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v18.0"></script>
    <?php
}
?>
