CREATE TABLE IF NOT EXISTS district_officials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    office_type VARCHAR(64) NOT NULL,
    name VARCHAR(255) NOT NULL,
    landline VARCHAR(80) DEFAULT NULL,
    address TEXT,
    area VARCHAR(255) DEFAULT NULL,
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    KEY idx_officials_type_status_id (office_type, status, id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
