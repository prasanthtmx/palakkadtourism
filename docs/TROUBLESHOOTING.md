# Troubleshooting .htaccess Redirect Rules

## Issue: Redirects not working locally

### Step 1: Enable mod_rewrite

**For Apache:**
```bash
# Check if mod_rewrite is enabled
sudo a2enmod rewrite

# Restart Apache
sudo systemctl restart apache2
# OR
sudo service apache2 restart
```

**For XAMPP (Windows):**
- Open `httpd.conf` (usually in `C:\xampp\apache\conf\`)
- Find: `#LoadModule rewrite_module modules/mod_rewrite.so`
- Remove the `#` to uncomment it
- Restart Apache from XAMPP Control Panel

**For XAMPP (Linux/Mac):**
- Edit `/opt/lampp/etc/httpd.conf`
- Uncomment: `LoadModule rewrite_module modules/mod_rewrite.so`
- Restart XAMPP

### Step 2: Check Apache AllowOverride

Your Apache virtual host or `.htaccess` directory must allow overrides.

**Check your Apache config** (usually in `/etc/apache2/sites-available/000-default.conf` or similar):

```apache
<Directory /var/www/html>
    Options Indexes FollowSymLinks
    AllowOverride All    # <-- This must be "All", not "None"
    Require all granted
</Directory>
```

**For XAMPP:**
- Edit `httpd.conf`
- Find `<Directory "/opt/lampp/htdocs">` (or your path)
- Change `AllowOverride None` to `AllowOverride All`
- Restart Apache

### Step 3: Check Your Local URL Structure

**If accessing via `http://localhost/palakkad/`:**
- Your site is in a subdirectory
- The redirect rules should still work, but you might need to adjust `RewriteBase`

**If accessing via `http://localhost/` (root):**
- Your site is at root
- Current configuration should work

### Step 4: Test the .htaccess File

Create a test file to verify .htaccess is being read:

```bash
# Create test file
echo "<?php phpinfo(); ?>" > /var/www/html/palakkad/test.php
```

Visit `http://localhost/palakkad/test.php` (or your URL) and check if it loads.

### Step 5: Check Apache Error Logs

```bash
# View Apache error log
sudo tail -f /var/log/apache2/error.log

# OR for XAMPP
tail -f /opt/lampp/logs/error_log
```

Try accessing `/palakkad/` and see if any errors appear.

### Step 6: Test Redirect Manually

Try accessing these URLs in your browser:
- `http://localhost/palakkad/` → Should redirect to `http://localhost/`
- `http://localhost/palakkad/index.php` → Should redirect to `http://localhost/index.php`

### Step 7: Alternative Configuration for Local Development

If you're accessing via `http://localhost/palakkad/` and the redirects still don't work, you might need to adjust the RewriteBase in `.htaccess`:

**For root installation (production):**
```apache
RewriteBase /
```

**For subdirectory installation (local dev):**
```apache
RewriteBase /palakkad/
```

### Step 8: Verify File Permissions

```bash
# Ensure .htaccess is readable
chmod 644 /var/www/html/palakkad/.htaccess

# Ensure directory is readable
chmod 755 /var/www/html/palakkad
```

### Common Issues:

1. **"Internal Server Error"** → Usually means mod_rewrite syntax error or AllowOverride is None
2. **"404 Not Found"** → RewriteBase might be wrong, or file doesn't exist
3. **No redirect happens** → mod_rewrite not enabled or AllowOverride is None
4. **Redirect loop** → Check your redirect rules for circular references

### Quick Test Commands:

```bash
# Check if mod_rewrite is loaded
apache2ctl -M | grep rewrite

# Check Apache configuration syntax
apache2ctl configtest

# Restart Apache
sudo systemctl restart apache2
```
