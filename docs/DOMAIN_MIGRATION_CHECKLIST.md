# Domain Migration Checklist: Moving to palakkadtourism.org

## ✅ Already Configured (No Changes Needed)

1. **SITE_URL** - Already set to `https://palakkadtourism.org` in `config/config.php`
2. **BASE_PATH** - Already set to empty string `''` (correct for root domain installation)
3. **SITE_EMAIL** - Already set to `info@palakkadtourism.org`
4. **Session Security** - HTTPS-only cookies already configured

## ⚙️ Required Configuration Changes

### 1. Update `.htaccess` File

**File:** `/var/www/html/paalakkad/.htaccess`

**Change Line 6:**
```apache
RewriteBase /paalakkad/
```
**To:**
```apache
RewriteBase /
```

**Enable HTTPS Redirect (Uncomment lines 8-10):**
```apache
# Force HTTPS (uncomment if you have SSL)
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### 2. Database Configuration

**File:** `/var/www/html/paalakkad/config/database.php`

**Current settings (usually stay the same on production server):**
- `DB_HOST`: `localhost` (or your database server hostname)
- `DB_USER`: Update with production database username
- `DB_PASS`: Update with production database password
- `DB_NAME`: `palakkad_tourism` (or your production database name)

**Note:** If your database is on a different server, update `DB_HOST` accordingly.

### 3. Error Reporting (Production)

**File:** `/var/www/html/paalakkad/config/config.php`

**Already configured correctly:**
- `error_reporting(E_ALL);` - Logs all errors
- `ini_set('display_errors', 0);` - Hides errors from users
- `ini_set('log_errors', 1);` - Logs errors to file

**Ensure log directory exists and is writable:**
```bash
mkdir -p /var/www/html/paalakkad/logs
chmod 755 /var/www/html/paalakkad/logs
```

### 4. File Permissions

Set proper file permissions on production:

```bash
# Set directory permissions
find /var/www/html/paalakkad -type d -exec chmod 755 {} \;

# Set file permissions
find /var/www/html/paalakkad -type f -exec chmod 644 {} \;

# Make logs directory writable
chmod 755 /var/www/html/paalakkad/logs
chmod 644 /var/www/html/paalakkad/logs/*.log 2>/dev/null || true

# If you have upload directories, make them writable
# chmod 755 /var/www/html/paalakkad/uploads
```

### 5. SSL Certificate

**Required:** Since `session.cookie_secure` is set to `1`, you MUST have an SSL certificate installed.

**Options:**
- Let's Encrypt (Free): `certbot --apache -d palakkadtourism.org -d www.palakkadtourism.org`
- Commercial SSL certificate from your hosting provider

**After SSL installation:**
- Uncomment HTTPS redirect in `.htaccess` (see step 1)
- Test that `https://palakkadtourism.org` works correctly

### 6. Apache Virtual Host Configuration

**Example Virtual Host Configuration:**

```apache
<VirtualHost *:80>
    ServerName palakkadtourism.org
    ServerAlias www.palakkadtourism.org
    Redirect permanent / https://palakkadtourism.org/
</VirtualHost>

<VirtualHost *:443>
    ServerName palakkadtourism.org
    ServerAlias www.palakkadtourism.org
    
    DocumentRoot /var/www/html/paalakkad
    
    <Directory /var/www/html/paalakkad>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    # SSL Configuration
    SSLEngine on
    SSLCertificateFile /path/to/your/certificate.crt
    SSLCertificateKeyFile /path/to/your/private.key
    SSLCertificateChainFile /path/to/your/chain.crt
    
    # Logging
    ErrorLog ${APACHE_LOG_DIR}/palakkadtourism_error.log
    CustomLog ${APACHE_LOG_DIR}/palakkadtourism_access.log combined
</VirtualHost>
```

### 7. PHP Configuration

**Check PHP settings in `php.ini` or `.htaccess`:**

- `upload_max_filesize`: 10M (already set in `.htaccess`)
- `post_max_size`: 10M (already set in `.htaccess`)
- `max_execution_time`: 300 (already set in `.htaccess`)
- `memory_limit`: Should be at least 128M

### 8. Google Services (Already Configured)

✅ **Google Analytics:** Already configured with ID `G-RLBH00600Y`
✅ **Google AdSense:** Already configured with client ID `ca-pub-8043296707059876`

No changes needed - these will work automatically on the new domain.

### 9. DNS Configuration

**Point your domain to your server:**

- **A Record:** `palakkadtourism.org` → Your server IP address
- **A Record:** `www.palakkadtourism.org` → Your server IP address
- **Or CNAME:** `www` → `palakkadtourism.org`

### 10. Testing Checklist

After migration, test the following:

- [ ] Homepage loads: `https://palakkadtourism.org`
- [ ] HTTPS redirect works: `http://palakkadtourism.org` → `https://palakkadtourism.org`
- [ ] All menu links work correctly
- [ ] Database connections work
- [ ] Admin login works: `https://palakkadtourism.org/admin/login.php`
- [ ] Image assets load correctly
- [ ] Google Analytics tracking works
- [ ] Google AdSense ads display
- [ ] Contact forms work (if any)
- [ ] Search functionality works
- [ ] Pagination works on listing pages

### 11. Backup Before Migration

**Before making changes, backup everything:**

```bash
# Backup files
tar -czf palakkad_backup_$(date +%Y%m%d).tar.gz /var/www/html/paalakkad

# Backup database
mysqldump -u root -p palakkad_tourism > palakkad_db_backup_$(date +%Y%m%d).sql
```

## Quick Summary

**Minimum changes required:**

1. ✅ Update `.htaccess` - Change `RewriteBase /paalakkad/` to `RewriteBase /`
2. ✅ Enable HTTPS redirect in `.htaccess`
3. ✅ Update database credentials in `config/database.php` (if different)
4. ✅ Install SSL certificate
5. ✅ Configure Apache virtual host
6. ✅ Set proper file permissions
7. ✅ Test all functionality

**Everything else is already configured correctly!**

