-- Hospitals table
CREATE TABLE IF NOT EXISTS hospitals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('government', 'private', 'ayurveda', 'homeopathy') NOT NULL,
    description TEXT,
    location VARCHAR(255),
    address TEXT,
    contact VARCHAR(100),
    phone VARCHAR(50),
    email VARCHAR(255),
    services TEXT,
    timings VARCHAR(100),
    image_url VARCHAR(500),
    website VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('active', 'inactive') DEFAULT 'active',
    INDEX idx_type (type),
    INDEX idx_location (location),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




