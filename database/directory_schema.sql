-- Yellow Pages / business directory (run once on palakkad_tourism DB).
-- InnoDB UTF8 defaults come from server; explicit charset/collation avoids surprises.

CREATE TABLE IF NOT EXISTS `directory_categories` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(160) NOT NULL,
  `slug` VARCHAR(180) NOT NULL,
  `sort_order` INT NOT NULL DEFAULT 0,
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_directory_cat_slug` (`slug`),
  KEY `idx_directory_cat_status` (`status`,`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `directory_entries` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` INT UNSIGNED NOT NULL,
  `name` VARCHAR(200) NOT NULL,
  `slug` VARCHAR(220) NOT NULL,
  `description` TEXT NULL,
  `address` VARCHAR(512) NULL,
  `area` VARCHAR(160) NULL,
  `phone` VARCHAR(80) NULL,
  `email` VARCHAR(190) NULL,
  `website` VARCHAR(512) NULL,
  `contact_person` VARCHAR(160) NULL,
  `submitter_name` VARCHAR(160) NOT NULL DEFAULT '',
  `submitter_email` VARCHAR(190) NOT NULL DEFAULT '',
  `meta_title` VARCHAR(200) NULL,
  `meta_description` VARCHAR(320) NULL,
  `status` ENUM('pending','active','rejected','inactive') NOT NULL DEFAULT 'pending',
  `featured` TINYINT(1) NOT NULL DEFAULT 0,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_directory_entry_slug` (`slug`),
  KEY `idx_directory_entry_cat` (`category_id`),
  KEY `idx_directory_entry_status` (`status`,`featured`,`name`),
  CONSTRAINT `fk_directory_entry_category`
    FOREIGN KEY (`category_id`) REFERENCES `directory_categories` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `directory_categories` (`name`, `slug`, `sort_order`, `status`) VALUES
  ('Retail & Shops', 'retail-shops', 10, 'active'),
  ('Restaurants & Catering', 'restaurants-catering', 20, 'active'),
  ('Professional Services', 'professional-services', 30, 'active'),
  ('Health & Wellness', 'health-wellness', 40, 'active'),
  ('Education', 'education', 50, 'active'),
  ('Automotive', 'automotive', 60, 'active'),
  ('Home Services', 'home-services', 70, 'active'),
  ('Other', 'other', 990, 'active')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`), `sort_order` = VALUES(`sort_order`);
