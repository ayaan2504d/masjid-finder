<?php
/**
 * Helper Functions
 * Masjid Locator & Namaz Timings System
 */

/**
 * Calculate distance between two coordinates using Haversine formula
 * @return float Distance in kilometers
 */
function getDistanceHaversine($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371; // km
    $dLat = deg2rad($lat2 - $lat1);
    $dLon = deg2rad($lon2 - $lon1);
    $a = sin($dLat / 2) * sin($dLat / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($dLon / 2) * sin($dLon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    return round($earthRadius * $c, 2);
}

/**
 * Get the nearest masjid to given coordinates
 */
function getNearestMasjid($conn, $lat, $lon) {
    $result = $conn->query(getMasjidTimingSelectSql());
    $nearest = null;
    $minDist = PHP_FLOAT_MAX;
    while ($row = $result->fetch_assoc()) {
        $dist = getDistanceHaversine($lat, $lon, $row['latitude'], $row['longitude']);
        if ($dist < $minDist) {
            $minDist = $dist;
            $nearest = $row;
            $nearest['distance'] = $dist;
        }
    }
    return $nearest;
}

/**
 * Get all masjids, optionally filtered by sect
 */
function getAllMasjids($conn, $sect = null) {
    $sql = getMasjidTimingSelectSql();
    if ($sect && in_array($sect, ['Sunni', 'Shia'])) {
        $sql .= " WHERE m.sect = '" . $conn->real_escape_string($sect) . "'";
    }
    $sql .= " ORDER BY m.name ASC";
    $result = $conn->query($sql);
    $masjids = [];
    while ($row = $result->fetch_assoc()) {
        $masjids[] = $row;
    }
    return $masjids;
}

/**
 * Get featured masjids
 */
function getFeaturedMasjids($conn, $limit = 6) {
    $result = $conn->query(getMasjidTimingSelectSql() . " WHERE m.is_featured = 1 ORDER BY m.name ASC LIMIT " . intval($limit));
    $masjids = [];
    while ($row = $result->fetch_assoc()) {
        $masjids[] = $row;
    }
    return $masjids;
}

/**
 * Get a single masjid by ID
 */
function getMasjidById($conn, $id) {
    $stmt = $conn->prepare(getMasjidTimingSelectSql() . " WHERE m.id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $masjid = $result->fetch_assoc();
    $stmt->close();
    return $masjid;
}

/**
 * Get next prayer name and time for a masjid
 * @return array ['name' => string, 'time' => string] or null
 */
function getNextPrayer($masjid) {
    $prayers = [
        'Fajr' => $masjid['fajr'] ?? null,
        'Zuhr' => $masjid['zuhr'] ?? null,
        'Asr' => $masjid['asr'] ?? null,
        'Maghrib' => $masjid['maghrib'] ?? null,
        'Isha' => $masjid['isha'] ?? null,
    ];

    $now = date('H:i');
    foreach ($prayers as $name => $time) {
        if ($time && $time > $now) {
            return ['name' => $name, 'time' => $time];
        }
    }
    // If all prayers passed, next is tomorrow's Fajr
    return ['name' => 'Fajr', 'time' => $prayers['Fajr'] ?? '04:30'];
}

/**
 * Format 24h time to 12h with AM/PM
 */
function formatTime12h($time) {
    if (!$time) return 'N/A';
    return date('h:i A', strtotime($time));
}

/**
 * Sanitize input
 */
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Get total count of masjids
 */
function getTotalMasjids($conn) {
    $result = $conn->query("SELECT COUNT(*) as cnt FROM masjids");
    return $result->fetch_assoc()['cnt'];
}

/**
 * Get count by sect
 */
function getCountBySect($conn, $sect) {
    $stmt = $conn->prepare("SELECT COUNT(*) as cnt FROM masjids WHERE sect = ?");
    $stmt->bind_param("s", $sect);
    $stmt->execute();
    $result = $stmt->get_result();
    $count = $result->fetch_assoc()['cnt'];
    $stmt->close();
    return $count;
}

/**
 * Get total contact messages
 */
function getTotalContacts($conn) {
    $result = $conn->query("SELECT COUNT(*) as cnt FROM messages");
    return $result->fetch_assoc()['cnt'];
}

/**
 * Get unread contact messages count
 */
function getUnreadContacts($conn) {
    $result = $conn->query("SELECT COUNT(*) as cnt FROM messages WHERE is_read = 0");
    return $result->fetch_assoc()['cnt'];
}

/**
 * Get recent masjids
 */
function getRecentMasjids($conn, $limit = 5) {
    $result = $conn->query(getMasjidTimingSelectSql() . " ORDER BY m.created_at DESC LIMIT " . intval($limit));
    $masjids = [];
    while ($row = $result->fetch_assoc()) {
        $masjids[] = $row;
    }
    return $masjids;
}

/**
 * Get recent contact messages
 */
function getRecentContacts($conn, $limit = 5) {
    $result = $conn->query("SELECT * FROM messages ORDER BY created_at DESC LIMIT " . intval($limit));
    $contacts = [];
    while ($row = $result->fetch_assoc()) {
        $contacts[] = $row;
    }
    return $contacts;
}

/**
 * Set flash message in session
 */
function setFlashMessage($type, $message) {
    if (session_status() === PHP_SESSION_NONE) session_start();
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

/**
 * Get and clear flash message
 */
function getFlashMessage() {
    if (session_status() === PHP_SESSION_NONE) session_start();
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

/**
 * Get all masjids as JSON (for map/JS)
 */
function getMasjidsJson($conn) {
    $masjids = getAllMasjids($conn);
    return json_encode($masjids);
}

function getMasjidTimingSelectSql() {
    return "SELECT m.*,
            COALESCE(t.fajr, m.fajr) AS fajr,
            COALESCE(t.zuhr, m.zuhr) AS zuhr,
            COALESCE(t.asr, m.asr) AS asr,
            COALESCE(t.maghrib, m.maghrib) AS maghrib,
            COALESCE(t.isha, m.isha) AS isha,
            COALESCE(t.juma_time, m.juma_time) AS juma_time,
            COALESCE(t.eid_time, m.eid_time) AS eid_time
        FROM masjids m
        LEFT JOIN masjid_prayer_timings t ON t.masjid_id = m.id";
}
?>
