# Temples Bulk Import Guide

This guide explains how to import 58 temples into the Palakkad Tourism database.

## Files Created

1. **`temples_bulk_insert.sql`** - SQL file containing INSERT statements for all 58 temples
2. **`admin/import_temples.php`** - Web interface to import temples (requires admin login)

## Method 1: Using Web Interface (Recommended)

1. Log in to the admin panel: `/paalakkad/admin/login.php`
2. Navigate to "Import Temples" from the sidebar menu
3. Click the "Import Temples" button
4. All 58 temples will be added to the database

## Method 2: Using MySQL Command Line

```bash
mysql -u root -p palakkad_tourism < database/temples_bulk_insert.sql
```

## Method 3: Using phpMyAdmin

1. Open phpMyAdmin
2. Select the `palakkad_tourism` database
3. Go to the "Import" tab
4. Choose the file: `temples_bulk_insert.sql`
5. Click "Go"

## Temple Information Included

Each temple entry includes:
- **Name**: Full temple name
- **Description**: Brief description with festival/event information
- **Location**: Extracted from the temple name/description
- **Address**: Full address with location and Kerala, India
- **Status**: Set to 'active' by default

## Adding More Details

After importing, you can:
1. Go to Admin Panel → Temples
2. Edit each temple individually to add:
   - Images (image_url)
   - Timings
   - Contact information
   - History
   - Special features

## Google Location Integration (Optional)

To fetch precise coordinates from Google Maps:

1. Get a Google Maps API key from [Google Cloud Console](https://console.cloud.google.com/)
2. Enable the **Geocoding API** in your project
3. Edit `/paalakkad/admin/import_temples.php` and add your API key:
   ```php
   $googleApiKey = 'YOUR_API_KEY_HERE';
   ```
4. The script can then fetch latitude/longitude for each temple address

**Note**: Google Maps API has usage limits and may require billing setup.

## List of Temples Included

1. Manappully Kavu Temple
2. Vadakkanthara temple
3. KarnakiyAmman Temple
4. Kalpathy Agrahara and Viswanathaswamy Temple
5. Kumarapuram Venkatachalapathy Temple
... (and 53 more)

All temples are ready to be displayed on the frontend `/paalakkad/temples.php` page.

