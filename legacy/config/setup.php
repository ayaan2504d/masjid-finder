<?php
/**
 * Database Seeder — Karachi Masjid Data Only
 * Overwrites all previous data to keep only Karachi mosques.
 */

// Drop tables first to force schema recreation in db.php
$temp_conn = new mysqli('localhost', 'root', '', 'masjid_locator');
if (!$temp_conn->connect_error) {
    $temp_conn->query("DROP TABLE IF EXISTS masjids, contacts, messages, settings");
    $temp_conn->close();
}

require_once __DIR__ . '/db.php';

// Fetch count of masjids after auto-seeding
$result = $conn->query("SELECT COUNT(*) as cnt FROM masjids");
$count = $result->fetch_assoc()['cnt'];

echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><title>Setup Complete</title>";
echo "<link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap' rel='stylesheet'>";
echo "<style>body{font-family:'Inter',sans-serif;display:flex;justify-content:center;align-items:center;min-height:100vh;background:linear-gradient(135deg,#1B5E20,#4CAF50);margin:0;} .card{background:#fff;padding:50px;border-radius:20px;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,0.3);max-width:500px;} h1{color:#1B5E20;} .count{font-size:48px;font-weight:700;color:#2E7D32;} a{display:inline-block;margin-top:20px;padding:12px 30px;background:#1B5E20;color:#fff;text-decoration:none;border-radius:10px;font-weight:600;transition:all 0.3s;} a:hover{background:#2E7D32;transform:translateY(-2px);}</style>";
echo "</head><body><div class='card'>";
echo "<h1>🕌 Setup Complete!</h1>";
echo "<p class='count'>{$count}</p>";
echo "<p>Karachi-only masjids have been added to the database.</p>";
echo "<a href='{$base_url}/'>Go to Homepage →</a>";
echo "</div></body></html>";
?>
