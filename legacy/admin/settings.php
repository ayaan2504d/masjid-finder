<?php
/**
 * Admin Settings Panel — Masjid Locator & Namaz Timings System
 * 
 * Features:
 * - Direct editable form for all site settings
 * - Dynamic config retrieval from DB
 * - prepared statement updates
 */

$admin_page = 'settings';
$page_title = 'System Settings';
require_once __DIR__ . '/includes/sidebar.php';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keys = [
        'site_name', 'site_tagline', 'default_city', 
        'contact_email', 'contact_phone', 'contact_address',
        'default_lat', 'default_lng'
    ];

    $errors = [];
    $success = true;

    $stmt = $conn->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");

    foreach ($keys as $key) {
        $value = sanitize($_POST[$key] ?? '');
        $stmt->bind_param("ss", $key, $value);
        if (!$stmt->execute()) {
            $success = false;
            $errors[] = "Error saving setting '{$key}': " . $conn->error;
        }
    }
    $stmt->close();

    if ($success) {
        setFlashMessage('success', 'System settings saved successfully!');
        // Force redirect to refresh session configurations
        header('Location: ' . $base_url . '/admin/settings.php');
        exit;
    } else {
        setFlashMessage('danger', implode('<br>', $errors));
    }
}

// Retrieve configurations
$site_name = getSetting($conn, 'site_name', 'Masjid Locator');
$site_tagline = getSetting($conn, 'site_tagline', 'Find Nearest Masjid');
$default_city = getSetting($conn, 'default_city', 'Lahore');
$contact_email = getSetting($conn, 'contact_email', 'info@masjidlocator.com');
$contact_phone = getSetting($conn, 'contact_phone', '+92 300 1234567');
$contact_address = getSetting($conn, 'contact_address', 'Lahore, Pakistan');
$default_lat = getSetting($conn, 'default_lat', '31.5204');
$default_lng = getSetting($conn, 'default_lng', '74.3587');
?>

<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="admin-form-card">
            <h5><i class="fas fa-sliders-h"></i> System Settings Configuration</h5>
            
            <form action="" method="POST">
                <!-- Site Branding -->
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label">Site Name</label>
                        <input type="text" name="site_name" class="form-control" value="<?php echo sanitize($site_name); ?>" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Site Tagline</label>
                        <input type="text" name="site_tagline" class="form-control" value="<?php echo sanitize($site_tagline); ?>">
                    </div>
                </div>

                <!-- Map Defaults -->
                <h6 class="mt-4 pb-2 border-bottom text-success fw-bold"><i class="fas fa-map-marker-alt"></i> Default City & Coordinates</h6>
                <div class="row g-3 mb-3">
                    <div class="col-sm-4">
                        <label class="form-label">Default City Name</label>
                        <input type="text" name="default_city" class="form-control" value="<?php echo sanitize($default_city); ?>" required>
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label">Default Latitude</label>
                        <input type="number" step="any" name="default_lat" class="form-control" value="<?php echo floatval($default_lat); ?>" required>
                    </div>
                    <div class="col-sm-4">
                        <label class="form-label">Default Longitude</label>
                        <input type="number" step="any" name="default_lng" class="form-control" value="<?php echo floatval($default_lng); ?>" required>
                    </div>
                </div>

                <!-- Contact Info -->
                <h6 class="mt-4 pb-2 border-bottom text-success fw-bold"><i class="fas fa-address-book"></i> Contact Details</h6>
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label">Support Email Address</label>
                        <input type="email" name="contact_email" class="form-control" value="<?php echo sanitize($contact_email); ?>" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Contact Phone Number</label>
                        <input type="text" name="contact_phone" class="form-control" value="<?php echo sanitize($contact_phone); ?>" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Postal / Office Address</label>
                    <textarea name="contact_address" class="form-control" rows="2" required><?php echo sanitize($contact_address); ?></textarea>
                </div>

                <button type="submit" class="btn btn-success fw-bold w-100 py-2">
                    <i class="fas fa-save me-1"></i> Save Configuration Settings
                </button>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
