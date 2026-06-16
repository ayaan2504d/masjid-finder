<?php
/**
 * Database Configuration & Auto-Setup
 * Masjid Locator & Namaz Timings System
 * 
 * Auto-creates database and tables if they don't exist.
 */

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'masjid_locator');

// Set default timezone to Pakistan Standard Time
date_default_timezone_set('Asia/Karachi');

// Create connection without database first
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Auto-create database
$conn->query("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
$conn->select_db(DB_NAME);

// ── Table: masjids ──────────────────────────────────────────
$conn->query("CREATE TABLE IF NOT EXISTS `masjids` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `address` TEXT NOT NULL,
    `area` VARCHAR(100) DEFAULT NULL,
    `city` VARCHAR(100) NOT NULL DEFAULT 'Karachi',
    `sect` ENUM('Sunni','Shia') NOT NULL DEFAULT 'Sunni',
    `latitude` DECIMAL(10,8) NOT NULL,
    `longitude` DECIMAL(11,8) NOT NULL,
    `fajr` VARCHAR(10) DEFAULT NULL,
    `zuhr` VARCHAR(10) DEFAULT NULL,
    `asr` VARCHAR(10) DEFAULT NULL,
    `maghrib` VARCHAR(10) DEFAULT NULL,
    `isha` VARCHAR(10) DEFAULT NULL,
    `juma_time` VARCHAR(10) DEFAULT NULL,
    `eid_time` VARCHAR(10) DEFAULT NULL,
    `phone` VARCHAR(30) DEFAULT NULL,
    `description` TEXT DEFAULT NULL,
    `is_featured` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

$conn->query("CREATE TABLE IF NOT EXISTS `masjid_prayer_timings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `masjid_id` INT NOT NULL UNIQUE,
    `fajr` VARCHAR(10) NOT NULL,
    `zuhr` VARCHAR(10) NOT NULL,
    `asr` VARCHAR(10) NOT NULL,
    `maghrib` VARCHAR(10) NOT NULL,
    `isha` VARCHAR(10) NOT NULL,
    `juma_time` VARCHAR(10) NOT NULL,
    `eid_time` VARCHAR(10) DEFAULT NULL,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT `fk_masjid_prayer_timings_masjid`
        FOREIGN KEY (`masjid_id`) REFERENCES `masjids` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// ── Table: messages ─────────────────────────────────────────
$conn->query("CREATE TABLE IF NOT EXISTS `messages` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `subject` VARCHAR(255) DEFAULT NULL,
    `message` TEXT NOT NULL,
    `is_read` TINYINT(1) DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// ── Table: settings ─────────────────────────────────────────
$conn->query("CREATE TABLE IF NOT EXISTS `settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

// ── Insert default settings if empty ────────────────────────
$result = $conn->query("SELECT COUNT(*) as cnt FROM settings");
$row = $result->fetch_assoc();
if ($row['cnt'] == 0) {
    $defaults = [
        ['site_name', 'Masjid Locator'],
        ['site_tagline', 'Find Nearest Masjid & Prayer Timings'],
        ['default_city', 'Karachi'],
        ['contact_email', 'info@masjidlocator.com'],
        ['contact_phone', '+92 21 1234567'],
        ['contact_address', 'Karachi, Sindh, Pakistan'],
        ['default_lat', '24.8607'],
        ['default_lng', '67.0011'],
    ];
    $stmt = $conn->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?)");
    foreach ($defaults as $d) {
        $stmt->bind_param("ss", $d[0], $d[1]);
        $stmt->execute();
    }
    $stmt->close();
}

// ── Insert default Karachi masjids if empty ─────────────────
$result_m = $conn->query("SELECT COUNT(*) as cnt FROM masjids");
$row_m = $result_m->fetch_assoc();
if ($row_m['cnt'] == 0) {
    $default_masjids = [
        [
            'Baitul Mukarram Masjid Karachi',
            'Main University Road, Gulshan-e-Iqbal, Karachi, Pakistan',
            'Gulshan-e-Iqbal',
            'Karachi',
            'Sunni',
            24.89740000, 67.07680000,
            '04:42', '12:37', '16:58', '19:21', '20:46', '13:25', '07:00',
            '+92 21 34987654',
            'Baitul Mukarram Masjid is a well-known mosque located in Gulshan-e-Iqbal, Karachi. It is a large mosque with a religious educational institution on site.',
            1
        ],
        [
            'Tooba Masjid DHA Karachi',
            'Korangi Road, Phase 2, DHA, Karachi, Pakistan',
            'DHA Phase 2',
            'Karachi',
            'Sunni',
            24.84360000, 67.05040000,
            '04:44', '12:39', '17:00', '19:23', '20:48', '13:28', '07:02',
            '+92 21 35881234',
            'Also known as Gol Masjid due to its massive dome design. Built in 1969, it is a prominent landmark of Karachi built with white marble and is acoustically unique.',
            1
        ],
        [
            'Masjid-e-Tooba Phase 4 Karachi',
            'Commercial Area, Phase 4, DHA, Karachi, Pakistan',
            'DHA Phase 4',
            'Karachi',
            'Sunni',
            24.81930000, 67.06910000,
            '04:46', '12:41', '17:02', '19:25', '20:50', '13:18', '07:04',
            '+92 21 35894321',
            'A beautiful local mosque serving Phase 4 DHA residents with daily congregation services and Islamic classes.',
            0
        ],
        [
            'Jamia Banuri Town Karachi',
            'Banuri Town, Gurumandir, Karachi, Pakistan',
            'Gurumandir',
            'Karachi',
            'Sunni',
            24.87840000, 67.04090000,
            '04:40', '12:35', '16:55', '19:18', '20:43', '13:15', '06:45',
            '+92 21 34913579',
            'Jamia Banuri Town is a renowned Islamic seminary and mosque center, drawing students and worshippers from across the globe.',
            1
        ],
        [
            'Gulshan-e-Iqbal Central Masjid Karachi',
            'Block 6, Gulshan-e-Iqbal, Karachi, Pakistan',
            'Gulshan-e-Iqbal',
            'Karachi',
            'Sunni',
            24.90800000, 67.08500000,
            '04:43', '12:38', '16:59', '19:22', '20:47', '13:30', '07:01',
            '+92 21 34981234',
            'A spacious central mosque located in Gulshan-e-Iqbal, providing regular congregation facilities and community gatherings.',
            0
        ],
        [
            'Memon Masjid Kharadar Karachi',
            'Kharadar, Karachi, Pakistan',
            'Kharadar',
            'Karachi',
            'Sunni',
            24.84990000, 66.99840000,
            '04:47', '12:42', '17:03', '19:26', '20:51', '13:32', '07:05',
            '+92 21 32415678',
            'A historic Memon community mosque located in the old city area of Kharadar, famous for its massive congregations.',
            1
        ],
        [
            'Kanzul Iman Masjid Karachi',
            'Landhi, Karachi, Pakistan',
            'Landhi',
            'Karachi',
            'Sunni',
            24.82900000, 67.18600000,
            '04:41', '12:36', '16:56', '19:19', '20:44', '13:27', '07:03',
            '+92 21 36491283',
            'A beautiful Sunni mosque serving the Landhi industrial area community with daily and Juma prayers.',
            0
        ],
        [
            'Masjid-e-Yasrab DHA Karachi',
            'Phase 4, DHA, Karachi, Pakistan',
            'DHA Phase 4',
            'Karachi',
            'Shia',
            24.82220000, 67.07440000,
            '04:45', '12:40', '17:01', '19:24', '20:49', '13:35', '07:06',
            '+92 21 35891122',
            'A prominent Shia mosque and community center in DHA Phase 4, providing regular congregations and majalis.',
            1
        ],
        [
            'Imambargah Shah-e-Karbala Karachi',
            'Clifton, Karachi, Pakistan',
            'Clifton',
            'Karachi',
            'Shia',
            24.81450000, 67.03150000,
            '04:48', '12:43', '17:04', '19:27', '20:52', '13:36', '07:07',
            '+92 21 35823456',
            'A large Imambargah and mosque in Clifton serving the Shia community with daily prayers and special events.',
            1
        ],
        [
            'Mehfil-e-Murtaza Karachi',
            'PECHS, Karachi, Pakistan',
            'PECHS',
            'Karachi',
            'Shia',
            24.86900000, 67.06100000,
            '04:49', '12:44', '17:05', '19:28', '20:53', '13:34', '07:08',
            '+92 21 34551122',
            'A well-established Shia community hub in PECHS, regularly conducting religious classes and congregations.',
            0
        ],
        [
            'Shah-e-Khurasan Masjid Karachi',
            'Soldier Bazar, Karachi, Pakistan',
            'Soldier Bazar',
            'Karachi',
            'Shia',
            24.87670000, 67.03100000,
            '04:50', '12:45', '17:06', '19:29', '20:54', '13:37', '07:09',
            '+92 21 32256789',
            'A historic Shia mosque and Imambargah situated in Soldier Bazar, known for its central location and large congregations.',
            1
        ],
        [
            'Babul Ilm Masjid Karachi',
            'North Nazimabad, Karachi, Pakistan',
            'North Nazimabad',
            'Karachi',
            'Shia',
            24.93950000, 67.03700000,
            '04:51', '12:46', '17:07', '19:30', '20:55', '13:38', '07:10',
            '+92 21 36631234',
            'A beautifully designed Shia mosque in Block H, North Nazimabad, serving the local Shia community.',
            0
        ],
        [
            'Masjid-e-Ali Karachi',
            'Gulshan-e-Iqbal, Karachi, Pakistan',
            'Gulshan-e-Iqbal',
            'Karachi',
            'Shia',
            24.91850000, 67.09820000,
            '04:52', '12:47', '17:08', '19:31', '20:56', '13:39', '07:11',
            '+92 21 34971212',
            'A Shia mosque serving Gulshan-e-Iqbal and neighboring areas with daily congregation facilities.',
            0
        ],
        [
            'Masjid-e-Yasrab Soldier Bazar Karachi',
            'Soldier Bazar, Karachi, Pakistan',
            'Soldier Bazar',
            'Karachi',
            'Shia',
            24.87800000, 67.02800000,
            '04:53', '12:48', '17:09', '19:32', '20:57', '13:40', '07:12',
            '+92 21 32234567',
            'A neighborhood Shia mosque in Soldier Bazar offering regular prayers and community services.',
            0
        ]
    ];

    $stmt_m = $conn->prepare("INSERT INTO masjids (name, address, area, city, sect, latitude, longitude, fajr, zuhr, asr, maghrib, isha, juma_time, eid_time, phone, description, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($default_masjids as $m) {
        $stmt_m->bind_param("sssssddsssssssssi",
            $m[0], $m[1], $m[2], $m[3], $m[4], $m[5], $m[6],
            $m[7], $m[8], $m[9], $m[10], $m[11], $m[12], $m[13], $m[14], $m[15], $m[16]
        );
        $stmt_m->execute();
    }
    $stmt_m->close();
}

$conn->query("INSERT INTO masjid_prayer_timings (masjid_id, fajr, zuhr, asr, maghrib, isha, juma_time, eid_time)
    SELECT id,
           COALESCE(NULLIF(fajr, ''), '04:42'),
           COALESCE(NULLIF(zuhr, ''), '12:37'),
           COALESCE(NULLIF(asr, ''), '16:58'),
           COALESCE(NULLIF(maghrib, ''), '19:21'),
           COALESCE(NULLIF(isha, ''), '20:46'),
           COALESCE(NULLIF(juma_time, ''), '13:25'),
           eid_time
    FROM masjids
    WHERE id NOT IN (SELECT masjid_id FROM masjid_prayer_timings)");

$distinctTimingResult = $conn->query("SELECT COUNT(DISTINCT CONCAT(fajr, '|', zuhr, '|', asr, '|', maghrib, '|', isha)) AS cnt, COUNT(*) AS total FROM masjid_prayer_timings");
$distinctTimingRow = $distinctTimingResult ? $distinctTimingResult->fetch_assoc() : ['cnt' => 0, 'total' => 0];
$expectedDistinct = min(14, intval($distinctTimingRow['total']));

if (intval($distinctTimingRow['total']) > 0 && intval($distinctTimingRow['cnt']) < $expectedDistinct) {
    $karachi_timing_variations = [
        ['04:42', '12:37', '16:58', '19:21', '20:46', '13:25', '07:00'],
        ['04:44', '12:39', '17:00', '19:23', '20:48', '13:28', '07:02'],
        ['04:46', '12:41', '17:02', '19:25', '20:50', '13:18', '07:04'],
        ['04:40', '12:35', '16:55', '19:18', '20:43', '13:15', '06:45'],
        ['04:43', '12:38', '16:59', '19:22', '20:47', '13:30', '07:01'],
        ['04:47', '12:42', '17:03', '19:26', '20:51', '13:32', '07:05'],
        ['04:41', '12:36', '16:56', '19:19', '20:44', '13:27', '07:03'],
        ['04:45', '12:40', '17:01', '19:24', '20:49', '13:35', '07:06'],
        ['04:48', '12:43', '17:04', '19:27', '20:52', '13:36', '07:07'],
        ['04:49', '12:44', '17:05', '19:28', '20:53', '13:34', '07:08'],
        ['04:50', '12:45', '17:06', '19:29', '20:54', '13:37', '07:09'],
        ['04:51', '12:46', '17:07', '19:30', '20:55', '13:38', '07:10'],
        ['04:52', '12:47', '17:08', '19:31', '20:56', '13:39', '07:11'],
        ['04:53', '12:48', '17:09', '19:32', '20:57', '13:40', '07:12'],
    ];

    $masjidIdsResult = $conn->query("SELECT id FROM masjids ORDER BY id ASC");
    $masjidIds = [];
    while ($masjidIdsResult && $row = $masjidIdsResult->fetch_assoc()) {
        $masjidIds[] = intval($row['id']);
    }

    $updateLegacy = $conn->prepare("UPDATE masjids SET fajr=?, zuhr=?, asr=?, maghrib=?, isha=?, juma_time=?, eid_time=? WHERE id=?");
    $updateTiming = $conn->prepare("UPDATE masjid_prayer_timings SET fajr=?, zuhr=?, asr=?, maghrib=?, isha=?, juma_time=?, eid_time=? WHERE masjid_id=?");
    $idx = 0;

    foreach ($masjidIds as $masjidId) {
        $times = $karachi_timing_variations[$idx % count($karachi_timing_variations)];
        $updateLegacy->bind_param("sssssssi", $times[0], $times[1], $times[2], $times[3], $times[4], $times[5], $times[6], $masjidId);
        $updateLegacy->execute();
        $updateTiming->bind_param("sssssssi", $times[0], $times[1], $times[2], $times[3], $times[4], $times[5], $times[6], $masjidId);
        $updateTiming->execute();
        $idx++;
    }

    $updateLegacy->close();
    $updateTiming->close();
}

// ── Base URL helper ─────────────────────────────────────────
$base_url = '/Masjid';

/**
 * Get a setting value by key
 */
function getSetting($conn, $key, $default = '') {
    $stmt = $conn->prepare("SELECT setting_value FROM settings WHERE setting_key = ?");
    $stmt->bind_param("s", $key);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $stmt->close();
        return $row['setting_value'];
    }
    $stmt->close();
    return $default;
}
?>
