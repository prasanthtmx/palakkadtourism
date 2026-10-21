# Palakkad Tourism Website

A modern, mobile-friendly tourism website for Palakkad, Kerala, built with PHP, Bootstrap 5, and MySQL.

## Features

- **Responsive Design**: Fully mobile-friendly using Bootstrap 5
- **Dynamic Content Management**: 
  - Temples section
  - Churches section
  - Mosques section
  - Festivals section
  - Places to Visit section
- **Admin Panel**: Easy-to-use admin interface for managing all content
- **Modern UI**: Clean, modern design with smooth animations
- **SEO Friendly**: Proper meta tags and semantic HTML

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache web server with mod_rewrite enabled
- Web browser

## Installation

### 1. Database Setup

1. Create a MySQL database:
```sql
CREATE DATABASE palakkad_tourism CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Import the live dump (recommended) or the modular schema:
```bash
mysql -u your_username -p palakkad_palakkad < database/dumps/palakkad_palakkad.sql
# or, for a fresh install:
mysql -u your_username -p palakkad_tourism < database/schema.sql
```

3. (Optional) Import sample data:
```bash
mysql -u your_username -p palakkad_tourism < database/sample_data.sql
```

### 2. Configuration

1. Edit `config/database.php` and update the database credentials:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'palakkad_tourism');
```

2. Edit `config/config.php` if needed to update site settings.

### 3. File Permissions

Ensure the web server has read permissions for all files:
```bash
chmod -R 755 /var/www/html/paalakkad
```

### 4. Access the Website

- **Frontend**: Navigate to `http://your-domain/paalakkad/`
- **Admin Panel**: Navigate to `http://your-domain/paalakkad/admin/`

### 5. Default Admin Credentials

- **Username**: `admin`
- **Password**: `admin123`

**⚠️ IMPORTANT: Change the admin password immediately after first login!**

To change the password, you can update it directly in the database:
```sql
UPDATE admin_users SET password = PASSWORD('your_new_password') WHERE username = 'admin';
```

Or use PHP's password_hash function:
```php
$hashed = password_hash('your_new_password', PASSWORD_DEFAULT);
```

## File Structure

```
palakkadtourism/
├── index.php, temples.php, about.php, ...   # Public pages (URLs unchanged)
├── admin/              # Admin panel (URL /admin/ unchanged)
├── assets/             # CSS, JS, images (URLs unchanged)
├── config/             # Site and database configuration
├── includes/           # Frontend includes
├── database/           # SQL scripts
│   ├── schema.sql
│   ├── sample_data.sql
│   └── dumps/          # phpMyAdmin / production dumps
├── docs/               # All project documentation
├── logs/               # PHP error log
├── ads.txt
├── robots.txt
└── sitemap.php
```

See also: [Troubleshooting](TROUBLESHOOTING.md), [Security](SECURITY_IMPROVEMENTS.md), [Temples import](TEMPLES_IMPORT.md), [Institutions setup](INSTITUTIONS_SETUP.md), [Gemini chatbot](GEMINI_CHATBOT.md), [Messenger chat](MESSENGER_CHAT_SETUP.md).

## Admin Panel Usage

1. Login to the admin panel at `/paalakkad/admin/`
2. Use the sidebar to navigate to different sections
3. Click "Add New" to add content
4. Click "Edit" to modify existing content
5. Click "Delete" to remove content (with confirmation)

## Adding Images

Currently, the system supports image URLs. You can:
- Upload images to your server and use the full URL
- Use external image hosting services
- Store images in an `uploads/` directory and reference them

To enable file uploads, you would need to:
1. Create an `uploads/` directory with write permissions
2. Modify the admin forms to include file upload fields
3. Add image upload handling code

## Security Notes

1. **Change default admin password** immediately
2. Keep PHP and MySQL updated
3. Use strong passwords for database access
4. Consider implementing CSRF protection for forms
5. Regularly backup your database
6. Keep the admin panel URL secure (consider IP restrictions)

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

## Support

For issues or questions, please check:
- PHP error logs
- MySQL error logs
- Apache error logs

## License

This project is created for Palakkad Tourism.

## Credits

- Bootstrap 5: https://getbootstrap.com/
- Bootstrap Icons: https://icons.getbootstrap.com/




