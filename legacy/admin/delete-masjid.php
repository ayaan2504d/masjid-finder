<?php
/**
 * Admin Delete Masjid Controller — Masjid Locator & Namaz Timings System
 * 
 * Safe handler accepting POST method only to delete masjids.
 * Redirects back to masjids dashboard with success message.
 */

if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

// Safe checking for POST requests
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;

    if ($id > 0) {
        $stmt = $conn->prepare("DELETE FROM masjids WHERE id = ?");
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            setFlashMessage('success', 'Masjid removed successfully from system.');
        } else {
            setFlashMessage('danger', 'Database error removing masjid: ' . $conn->error);
        }
        $stmt->close();
    } else {
        setFlashMessage('danger', 'Invalid request parameters.');
    }
} else {
    setFlashMessage('danger', 'Invalid request method. Delete must be performed via POST.');
}

// Redirect back to dashboard list
header('Location: ' . $base_url . '/admin/masjids.php');
exit;
?>
