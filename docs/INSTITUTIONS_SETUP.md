# Institutions Database Setup

This guide will help you set up the institutions database for the Palakkad Tourism website.

## Database Table

The `institutions` table stores information about schools and colleges in Palakkad.

## Setup Instructions

### Option 1: Using phpMyAdmin or MySQL Client

1. Open phpMyAdmin or your MySQL client
2. Select your database
3. Import the SQL file: `database/institutions_table.sql`
4. (Optional) Import sample data: `database/institutions_sample_data.sql`

### Option 2: Using Command Line

```bash
# Navigate to the database directory
cd /var/www/html/paalakkad/database

# Import the table structure
mysql -u your_username -p your_database_name < institutions_table.sql

# (Optional) Import sample data
mysql -u your_username -p your_database_name < institutions_sample_data.sql
```

### Option 3: Using Admin Panel

1. Log in to the admin panel
2. Go to "Institutions" in the sidebar
3. Click "Add Institution" to manually add institutions

## Table Structure

The `institutions` table includes:
- **id**: Primary key
- **name**: Institution name
- **type**: 'school' or 'college'
- **category**: 'government', 'private', or 'aided'
- **description**: Detailed description
- **location**: City/town name
- **address**: Full address
- **contact**: Contact person name
- **phone**: Phone number
- **email**: Email address
- **website**: Website URL
- **established_year**: Year established
- **affiliation**: Board/University affiliation
- **courses**: Courses offered
- **facilities**: Available facilities
- **image_url**: Institution image URL
- **timings**: Operating hours
- **status**: 'active' or 'inactive'

## Frontend Pages

- **Listing Page**: `/paalakkad/institutions.php`
  - Shows all institutions with filters (type, category)
  - Search functionality
  - Pagination
  
- **Detail Page**: `/paalakkad/institution.php?id=X`
  - Shows individual institution details
  - SEO-friendly with structured data

## Admin Panel

Access the admin panel at `/paalakkad/admin/institutions.php` to:
- Add new institutions
- Edit existing institutions
- Delete institutions
- Manage institution status

## Features

✅ Dynamic content management
✅ Search functionality
✅ Filter by type (school/college) and category (government/private/aided)
✅ Pagination
✅ SEO-friendly URLs and structured data
✅ Responsive design
✅ Security (prepared statements, input validation, CSRF protection)

