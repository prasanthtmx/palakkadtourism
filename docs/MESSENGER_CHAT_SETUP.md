# Meta Messenger Chat Plugin Setup Guide

This guide will help you set up the Meta (Facebook) Messenger chat widget on your Palakkad Tourism website.

## Features

- **Floating Chat Widget** - Appears in the bottom-right corner of your website
- **Mobile Responsive** - Works on all devices
- **Custom Greetings** - Personalized messages for visitors
- **Direct Integration** - Connects to your Facebook Page inbox
- **Free to Use** - No additional costs

## Step 1: Get Your Facebook Page ID

1. Go to your Facebook Page: https://www.facebook.com/palakkadtourism
2. Click on **About** in the left sidebar
3. Scroll down to find **Page ID** (it's a long number)
4. **OR** use this method:
   - Go to: https://www.facebook.com/help/1503421039731588
   - Enter your page URL to find your Page ID
   - **OR** visit: https://findmyfbid.com/
   - Enter your page URL: `https://www.facebook.com/palakkadtourism`
   - Copy the Page ID

## Step 2: Update the Page ID in Code

1. Open `/var/www/html/palakkad/includes/footer.php`
2. Find this line (around line 78):
   ```php
   initMessengerChat('palakkadtourism'); // Change 'palakkadtourism' to your actual Facebook Page ID
   ```
3. Replace `'palakkadtourism'` with your actual **Page ID** (the numeric ID, not the username)
   ```php
   initMessengerChat('123456789012345'); // Your actual Page ID
   ```

## Step 3: Configure Your Facebook Page

1. Go to your Facebook Page: https://www.facebook.com/palakkadtourism
2. Click **Settings** in the left sidebar
3. Click **Messaging** in the left menu
4. Under **Add Messenger to your website**, click **Get Started**
5. Follow the setup wizard:
   - Choose your website domain: `palakkadtourism.org`
   - Customize the chat widget appearance
   - Set up automated responses (optional)
6. Copy the code provided (we've already added it, but you can customize)

## Step 4: Customize Greetings (Optional)

You can customize the chat greetings by editing `footer.php`:

```php
displayMessengerChat(
    '123456789012345', // Your Page ID
    '', // App ID (optional, leave empty)
    'en_US', // Language
    'Hi! How can we help you with Palakkad Tourism?', // Logged in greeting
    'Hello! Ask us anything about Palakkad Tourism.' // Logged out greeting
);
```

## Step 5: Test the Chat Widget

1. Visit your website: https://palakkadtourism.org
2. Look for the Messenger chat icon in the bottom-right corner
3. Click it to open the chat window
4. Send a test message
5. Check your Facebook Page inbox to see the message

## Advanced Configuration

### Customize Colors

Edit `includes/messenger_chat.php` and change:
```php
theme_color="#0084ff"  // Change to your brand color
```

### Customize Appearance

You can modify these attributes in `messenger_chat.php`:
- `greeting_dialog_display="fade"` - Animation style (fade, show, hide)
- `greeting_dialog_delay="3"` - Delay in seconds before showing greeting

### Add Facebook App ID (Optional)

For advanced features like analytics:
1. Create a Facebook App at: https://developers.facebook.com/apps/
2. Get your App ID
3. Update the code:
   ```php
   displayMessengerChat('YOUR_PAGE_ID', 'YOUR_APP_ID');
   ```

## Troubleshooting

### Chat Widget Not Appearing

1. **Check Page ID** - Make sure you're using the numeric Page ID, not the username
2. **Check Domain** - Ensure your domain is added in Facebook Page Settings > Messaging
3. **Clear Cache** - Clear browser cache and try again
4. **Check Console** - Open browser console (F12) and look for JavaScript errors

### Messages Not Receiving

1. **Check Page Settings** - Go to Page Settings > Messaging
2. **Enable Messaging** - Make sure messaging is enabled for your page
3. **Check Inbox** - Messages appear in your Facebook Page inbox
4. **Response Time** - Set up automated responses for better engagement

### Widget Not Responsive

- The widget is automatically responsive
- If issues occur, check that Bootstrap CSS is loaded
- The widget adapts to mobile screens automatically

## Features

✅ **Floating Widget** - Always visible, doesn't interfere with content  
✅ **Mobile Friendly** - Works perfectly on phones and tablets  
✅ **Custom Greetings** - Personalized welcome messages  
✅ **Direct Integration** - Messages go to your Facebook Page inbox  
✅ **Free** - No additional costs  
✅ **Easy Setup** - Just add your Page ID  

## Managing Messages

1. Go to your Facebook Page
2. Click **Inbox** in the left sidebar
3. All website messages will appear here
4. You can respond directly from Facebook
5. Set up automated responses for common questions

## Best Practices

1. **Quick Response** - Respond to messages within 24 hours
2. **Automated Responses** - Set up quick replies for common questions
3. **Business Hours** - Set your availability in Page Settings
4. **Welcome Message** - Create a friendly welcome message
5. **FAQ Setup** - Use automated responses for frequently asked questions

## Support

- **Facebook Help**: https://www.facebook.com/help/1503421039731588
- **Messenger Platform Docs**: https://developers.facebook.com/docs/messenger-platform
- **Page Settings**: https://www.facebook.com/palakkadtourism/settings

## Notes

- The chat widget works on all pages automatically (it's in the footer)
- Messages are free to receive and send
- You can manage all conversations from your Facebook Page inbox
- The widget is GDPR compliant when used with Facebook's standard setup
- No additional server resources required
