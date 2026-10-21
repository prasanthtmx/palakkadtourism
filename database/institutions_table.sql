CREATE TABLE IF NOT EXISTS institutions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    type ENUM('school', 'college') NOT NULL,
    category ENUM('government', 'private', 'aided') NOT NULL,
    description TEXT,
    location VARCHAR(255),
    address TEXT,
    contact VARCHAR(255),
    phone VARCHAR(50),
    email VARCHAR(255),
    website VARCHAR(255),
    established_year INT,
    affiliation VARCHAR(255),
    courses TEXT,
    facilities TEXT,
    image_url VARCHAR(500),
    timings VARCHAR(255),
    status ENUM('active', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

