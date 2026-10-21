# Facebook Integration Guide

This guide explains how to display Facebook posts and content on your Palakkad Tourism website pages.

## Features

1. **Facebook Page Plugin** - Display your entire Facebook page feed
2. **Individual Post Embed** - Embed specific Facebook posts
3. **Facebook Video Embed** - Embed Facebook videos

## Setup

The Facebook SDK is already included in the header, so you can use Facebook embeds on any page.

## Usage Examples

### 1. Display Facebook Page Feed (Full Timeline)

Add this to any PHP page where you want to show your Facebook feed:

```php
<?php
require_once __DIR__ . '/includes/facebook_feed.php';

// Display Facebook Page Plugin with default settings
displayFacebookPagePlugin('https://www.facebook.com/palakkadtourism');
?>
```

**Custom Options:**
```php
displayFacebookPagePlugin(
    'https://www.facebook.com/palakkadtourism',  // Your Facebook page URL
    500,      // Width (pixels)
    500,      // Height (pixels)
    true,     // Show timeline posts
    false,    // Show events
    false     // Show messages button
);
```

### 2. Display Individual Facebook Post

To embed a specific Facebook post:

```php
<?php
require_once __DIR__ . '/includes/facebook_feed.php';

// Get the post URL from Facebook (right-click post > Copy Link)
$postUrl = 'https://www.facebook.com/palakkadtourism/posts/1234567890';
displayFacebookPost($postUrl, 500);
?>
```

### 3. Display Facebook Video

To embed a Facebook video:

```php
<?php
require_once __DIR__ . '/includes/facebook_feed.php';

// Get the video URL from Facebook
$videoUrl = 'https://www.facebook.com/palakkadtourism/videos/1234567890';
displayFacebookVideo($videoUrl, 500);
?>
```

## Adding to Homepage

The Facebook feed is already added to the homepage (`index.php`). You can customize it by editing the section around line 430.

## Adding to Other Pages

### Example: Add to About Page

1. Open `about.php`
2. Before the footer include, add:

```php
<!-- Facebook Feed Section -->
<section class="py-5" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="display-5 fw-bold mb-3">Follow Us on Facebook</h2>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <?php
                require_once __DIR__ . '/includes/facebook_feed.php';
                displayFacebookPagePlugin('https://www.facebook.com/palakkadtourism');
                ?>
            </div>
        </div>
    </div>
</section>
```

### Example: Add Individual Post to Any Page

```php
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <h3>Latest Update</h3>
                <?php
                require_once __DIR__ . '/includes/facebook_feed.php';
                // Replace with your actual post URL
                displayFacebookPost('https://www.facebook.com/palakkadtourism/posts/YOUR_POST_ID');
                ?>
            </div>
        </div>
    </div>
</section>
```

## Getting Facebook Post URLs

1. Go to your Facebook page
2. Find the post you want to embed
3. Click the timestamp (e.g., "2 hours ago")
4. Copy the URL from the address bar
5. Use that URL in `displayFacebookPost()`

## Customization

### Change Facebook Page URL

If your Facebook page URL is different, update it in:
- `index.php` (line ~435)
- Any other pages where you use `displayFacebookPagePlugin()`

### Styling

The Facebook embeds are responsive and will adapt to container width. You can add custom CSS in `assets/css/style.css`:

```css
.facebook-feed-container {
    margin: 20px 0;
}

.facebook-post-container {
    margin: 20px 0;
}
```

## Troubleshooting

### Facebook Feed Not Showing

1. **Check Facebook Page URL** - Make sure it's correct and the page is public
2. **Check Browser Console** - Look for JavaScript errors
3. **Clear Cache** - Clear browser cache and try again
4. **Check Privacy Settings** - Make sure your Facebook page posts are public

### Posts Not Loading

- Facebook embeds require the posts to be **public**
- Private posts cannot be embedded
- Make sure the Facebook SDK is loaded (check header.php)

### Width/Height Issues

- Facebook embeds are responsive by default
- Set `data-adapt-container-width="true"` (already set)
- The width parameter is a maximum width

## Advanced: Using Facebook Graph API

For more advanced features (like fetching posts programmatically), you would need:
1. Facebook App ID
2. Facebook App Secret
3. Access Token

This requires additional setup. The current implementation uses public embeds which don't require authentication.

## Notes

- Facebook embeds work best with public posts
- The feed updates automatically when you post on Facebook
- No need to manually update the website when you post on Facebook
- All embeds are responsive and mobile-friendly
