<?php
/**
 * Palakkad district officials shown on the homepage (latest active per office).
 */
if (defined('PALAKKAD_DISTRICT_OFFICIALS_LOADED')) {
    return;
}
define('PALAKKAD_DISTRICT_OFFICIALS_LOADED', true);

function districtOfficialOfficeTypes() {
    return [
        'collector' => 'Collector of Palakkad',
        'mla' => 'MLA',
        'mp' => 'MP',
        'district_police_chief' => 'District Police Chief',
        'deputy_collector_general' => 'Deputy Collector General (ADM)',
    ];
}

function ensureDistrictOfficialsTable($conn) {
    if (!$conn instanceof mysqli) {
        return;
    }
    $sql = "CREATE TABLE IF NOT EXISTS district_officials (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    $conn->query($sql);
}

function fetchLatestDistrictOfficials($conn) {
    $types = districtOfficialOfficeTypes();
    $latest = [];
    if (!$conn instanceof mysqli) {
        return $latest;
    }
    ensureDistrictOfficialsTable($conn);
    $stmt = $conn->prepare(
        "SELECT id, office_type, name, landline, address, area
         FROM district_officials
         WHERE status = 'active' AND office_type = ?
         ORDER BY id DESC
         LIMIT 1"
    );
    if (!$stmt) {
        return $latest;
    }
    foreach (array_keys($types) as $type) {
        $stmt->bind_param('s', $type);
        $stmt->execute();
        $row = $stmt->get_result()->fetch_assoc();
        if ($row) {
            $row['office_label'] = $types[$type];
            $latest[] = $row;
        }
    }
    $stmt->close();
    return $latest;
}
