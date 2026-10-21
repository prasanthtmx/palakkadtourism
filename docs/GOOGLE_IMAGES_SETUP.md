# Google Images Setup Guide

This guide explains how to automatically fetch temple images from Google Images and display them on your website.

## Overview

The system uses **Google Custom Search API** to fetch images from Google Images for each temple. The images are stored as URLs in the database and automatically displayed on the frontend.

## Setup Steps

### 1. Get Google Custom Search API Key

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Enable the **Custom Search API**:
   - Go to "APIs & Services" → "Library"
   - Search for "Custom Search API"
   - Click "Enable"

### 2. Create API Credentials

1. Go to "APIs & Services" → "Credentials"
2. Click "Create Credentials" → "API Key"
3. Copy your API key
4. (Optional) Restrict the API key to "Custom Search API" for security

### 3. Create Custom Search Engine

1. Go to [Programmable Search Engine](https://programmablesearchengine.google.com/)
2. Click "Add" to create a new search engine
3. **Sites to search**: Enter `*` (asterisk) to search the entire web
4. Click "Create"
5. Go to "Setup" → "Basics"
6. Enable **"Image search"** in the settings
7. Copy your **Search Engine ID** (also called CX)

### 4. Configure the Script

1. Open `/paalakkad/admin/fetch_temple_images.php`
2. Find these lines (around line 12-13):
   ```php
   $googleApiKey = ''; // Add your Google Custom Search API key here
   $searchEngineId = ''; // Add your Custom Search Engine ID here
   ```
3. Add your API key and Search Engine ID:
   ```php
   $googleApiKey = 'YOUR_API_KEY_HERE';
   $searchEngineId = 'YOUR_SEARCH_ENGINE_ID_HERE';
   ```

### 5. Fetch Images

1. Log in to admin panel
2. Go to "Fetch Temple Images" from the sidebar
3. Click "Fetch Images from Google"
4. Wait for the process to complete (may take several minutes)

## How It Works

1. The script searches Google Images for each temple using: `[Temple Name] [Location] Palakkad Kerala temple`
2. It fetches the first relevant image result
3. Stores the image URL in the database
4. Images automatically appear on `/paalakkad/temples.php` and individual temple pages

## API Limits

- **Free Tier**: 100 search queries per day
- **Paid Tier**: $5 per 1,000 queries after free tier
- The script adds a 0.1 second delay between requests to respect rate limits

## Important Notes

- Images are stored as **URLs** (not downloaded to your server)
- If a Google Images URL becomes unavailable, the image won't display
- You can manually update image URLs in the admin panel
- The script only fetches images for temples that don't already have images

## Troubleshooting

### "Configuration Required" Error
- Make sure you've added both API key and Search Engine ID
- Verify the API key is enabled for Custom Search API

### No Images Found
- Check if the API key has proper permissions
- Verify the Search Engine has "Image search" enabled
- Check your API quota (100 free queries per day)

### Images Not Displaying
- Some Google Images URLs may require HTTPS
- Check browser console for CORS errors
- You may need to download and host images locally instead

## Alternative: Manual Image Upload

If you prefer to upload images manually:
1. Go to Admin Panel → Temples
2. Edit each temple
3. Add image URL or upload image directly
4. Save changes

## Display on Frontend

Images automatically appear on:
- `/paalakkad/temples.php` - Temple listing page
- `/paalakkad/temple.php?id=X` - Individual temple pages

The system is already configured to display images if they exist in the database.

