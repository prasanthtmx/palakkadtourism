-- Heritage Villages Table
CREATE TABLE IF NOT EXISTS heritage_villages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    location VARCHAR(255),
    description TEXT,
    history TEXT,
    special_features TEXT,
    image_url VARCHAR(500),
    address TEXT,
    timings VARCHAR(100),
    contact VARCHAR(50),
    best_time_to_visit VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('active', 'inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;




