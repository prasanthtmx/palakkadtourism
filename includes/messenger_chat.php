<?php
/**
 * Meta Messenger Chat Plugin Integration
 * Adds Facebook Messenger chat widget to the website
 */

/**
 * Display Meta Messenger Chat Plugin
 * 
 * @param string $pageId - Your Facebook Page ID (e.g., 'palakkadtourism')
 * @param string $appId - Your Facebook App ID (optional, for advanced features)
 * @param string $locale - Language locale (default: 'en_US')
 * @param bool $loggedInGreeting - Custom greeting when user is logged in
 * @param bool $loggedOutGreeting - Custom greeting when user is logged out
 */
function displayMessengerChat($pageId = 'palakkadtourism', $appId = '', $locale = 'en_US', $loggedInGreeting = '', $loggedOutGreeting = '') {
    // Default greetings
    if (empty($loggedInGreeting)) {
        $loggedInGreeting = 'Hi! How can we help you with Palakkad Tourism?';
    }
    if (empty($loggedOutGreeting)) {
        $loggedOutGreeting = 'Hi! We\'re here to answer any questions about Palakkad Tourism.';
    }
    ?>
    <!-- Messenger Chat Plugin Code -->
    <!-- Note: fb-root div is already in header, so we don't need to create it again -->
    
    <!-- Your Chat Plugin code -->
    <div id="fb-customer-chat" class="fb-customerchat"
         page_id="<?php echo htmlspecialchars($pageId); ?>"
         <?php if (!empty($appId)): ?>
         app_id="<?php echo htmlspecialchars($appId); ?>"
         <?php endif; ?>
         theme_color="#0084ff"
         logged_in_greeting="<?php echo htmlspecialchars($loggedInGreeting); ?>"
         logged_out_greeting="<?php echo htmlspecialchars($loggedOutGreeting); ?>"
         greeting_dialog_display="fade"
         greeting_dialog_delay="3">
    </div>
    
    <script>
        var chatbox = document.getElementById('fb-customer-chat');
        chatbox.setAttribute("page_id", "<?php echo htmlspecialchars($pageId); ?>");
        chatbox.setAttribute("attribution", "biz_inbox");
        
        // Load Messenger Chat Plugin SDK
        (function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = 'https://connect.facebook.net/<?php echo htmlspecialchars($locale); ?>/sdk/xfbml.customerchat.js';
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk-customerchat'));
    </script>
    <?php
}

/**
 * Initialize Messenger Chat (Simple version)
 * Use this if you just want to add the basic chat widget
 */
function initMessengerChat($pageId = 'palakkadtourism') {
    displayMessengerChat($pageId);
}
?>
