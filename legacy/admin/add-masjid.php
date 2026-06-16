<?php
/**
 * Admin Add Masjid — Masjid Locator & Namaz Timings System
 * 
 * Features:
 * - Fully validated form to register new mosque
 * - Interactive map pin picker to set coordinates automatically
 * - Form insertion handling using MySQL prepared statements
 */

$admin_page = 'add-masjid';
$page_title = 'Add New Masjid';
require_once __DIR__ . '/includes/sidebar.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $address = sanitize($_POST['address'] ?? '');
    $sect = sanitize($_POST['sect'] ?? 'Sunni');
    $latitude = floatval($_POST['latitude'] ?? 0);
    $longitude = floatval($_POST['longitude'] ?? 0);
    $fajr = sanitize($_POST['fajr'] ?? '');
    $zuhr = sanitize($_POST['zuhr'] ?? '');
    $asr = sanitize($_POST['asr'] ?? '');
    $maghrib = sanitize($_POST['maghrib'] ?? '');
    $isha = sanitize($_POST['isha'] ?? '');
    $juma_time = sanitize($_POST['juma_time'] ?? '');
    $eid_time = sanitize($_POST['eid_time'] ?? '');
    $phone = sanitize($_POST['phone'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;

    $errors = [];

    // Validations
    if ($name === '') $errors[] = 'Masjid Name is required.';
    if ($address === '') $errors[] = 'Address is required.';
    if ($latitude === 0.0 || $longitude === 0.0) $errors[] = 'Please specify valid coordinates or select on map.';
    if ($fajr === '' || $zuhr === '' || $asr === '' || $maghrib === '' || $isha === '' || $juma_time === '') {
        $errors[] = 'Daily prayer and Juma times are required.';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO masjids (name, address, sect, latitude, longitude, fajr, zuhr, asr, maghrib, isha, juma_time, eid_time, phone, description, is_featured) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->bind_param("sssddsssssssssi", 
            $name, $address, $sect, $latitude, $longitude,
            $fajr, $zuhr, $asr, $maghrib, $isha,
            $juma_time, $eid_time, $phone, $description, $is_featured
        );

        if ($stmt->execute()) {
            $masjid_id = $conn->insert_id;
            $timing_stmt = $conn->prepare("INSERT INTO masjid_prayer_timings (masjid_id, fajr, zuhr, asr, maghrib, isha, juma_time, eid_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $timing_stmt->bind_param("isssssss", $masjid_id, $fajr, $zuhr, $asr, $maghrib, $isha, $juma_time, $eid_time);
            $timing_stmt->execute();
            $timing_stmt->close();
            setFlashMessage('success', 'Masjid registered successfully!');
            header('Location: ' . $base_url . '/admin/masjids.php');
            exit;
        } else {
            $errors[] = 'Database insertion failed: ' . $conn->error;
        }
        $stmt->close();
    }

    if (!empty($errors)) {
        setFlashMessage('danger', implode('<br>', $errors));
    }
}

// Retrieve default coordinates from settings to center map
$default_lat = getSetting($conn, 'default_lat', '31.5204');
$default_lng = getSetting($conn, 'default_lng', '74.3587');
?>

<div class="row g-4">
    <!-- Form Block -->
    <div class="col-lg-7">
        <div class="admin-form-card">
            <h5><i class="fas fa-edit"></i> Masjid Details</h5>
            <form action="" method="POST">
                <!-- Basic Info -->
                <div class="mb-3">
                    <label class="form-label">Masjid Name *</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Badshahi Mosque" value="<?php echo isset($_POST['name']) ? sanitize($_POST['name']) : ''; ?>" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label">Sect/School *</label>
                        <select name="sect" class="form-select">
                            <option value="Sunni" <?php echo (isset($_POST['sect']) && $_POST['sect'] === 'Sunni') ? 'selected' : ''; ?>>Sunni</option>
                            <option value="Shia" <?php echo (isset($_POST['sect']) && $_POST['sect'] === 'Shia') ? 'selected' : ''; ?>>Shia</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="e.g. +92 42 1234567" value="<?php echo isset($_POST['phone']) ? sanitize($_POST['phone']) : ''; ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Address *</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="e.g. Circular Road, Walled City, Lahore" required><?php echo isset($_POST['address']) ? sanitize($_POST['address']) : ''; ?></textarea>
                </div>

                <!-- Coordinates Picker (Updates dynamically via Map clicking) -->
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label">Latitude *</label>
                        <input type="number" step="any" name="latitude" id="inputLat" class="form-control" placeholder="e.g. 31.5881" value="<?php echo isset($_POST['latitude']) ? floatval($_POST['latitude']) : ''; ?>" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Longitude *</label>
                        <input type="number" step="any" name="longitude" id="inputLng" class="form-control" placeholder="e.g. 74.3107" value="<?php echo isset($_POST['longitude']) ? floatval($_POST['longitude']) : ''; ?>" required>
                    </div>
                    <div class="col-12">
                        <span class="small text-muted"><i class="fas fa-info-circle"></i> Click on the map on the right to set latitude and longitude pins automatically.</span>
                    </div>
                </div>

                <!-- Prayer Timings -->
                <h5 class="mt-4 pt-2 border-top"><i class="fas fa-clock"></i> Prayer Timings</h5>
                
                <div class="row g-3 mb-3">
                    <div class="col">
                        <label class="form-label">Fajr *</label>
                        <input type="time" name="fajr" class="form-control" value="<?php echo isset($_POST['fajr']) ? sanitize($_POST['fajr']) : '04:30'; ?>" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Zuhr *</label>
                        <input type="time" name="zuhr" class="form-control" value="<?php echo isset($_POST['zuhr']) ? sanitize($_POST['zuhr']) : '12:30'; ?>" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Asr *</label>
                        <input type="time" name="asr" class="form-control" value="<?php echo isset($_POST['asr']) ? sanitize($_POST['asr']) : '16:00'; ?>" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Maghrib *</label>
                        <input type="time" name="maghrib" class="form-control" value="<?php echo isset($_POST['maghrib']) ? sanitize($_POST['maghrib']) : '18:30'; ?>" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Isha *</label>
                        <input type="time" name="isha" class="form-control" value="<?php echo isset($_POST['isha']) ? sanitize($_POST['isha']) : '20:00'; ?>" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label">Juma Salah Time *</label>
                        <input type="time" name="juma_time" class="form-control" value="<?php echo isset($_POST['juma_time']) ? sanitize($_POST['juma_time']) : '13:00'; ?>" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Eid Salah Time</label>
                        <input type="time" name="eid_time" class="form-control" value="<?php echo isset($_POST['eid_time']) ? sanitize($_POST['eid_time']) : ''; ?>">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Description / Announcements</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Historical context, parking details or regular announcements..."><?php echo isset($_POST['description']) ? sanitize($_POST['description']) : ''; ?></textarea>
                </div>

                <!-- Checkbox Option -->
                <div class="mb-4 form-check">
                    <input type="checkbox" name="is_featured" class="form-check-input" id="checkFeatured" <?php echo isset($_POST['is_featured']) ? 'checked' : ''; ?>>
                    <label class="form-check-label fw-bold text-success" for="checkFeatured">Feature this Masjid on Homepage</label>
                </div>

                <button type="submit" class="btn btn-success px-4 py-2 fw-bold w-100">
                    <i class="fas fa-save me-1"></i> Save Masjid Profile
                </button>
            </form>
        </div>
    </div>

    <!-- Map Block -->
    <div class="col-lg-5">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden h-100 d-flex flex-column" style="min-height: 450px;">
            <div class="bg-success bg-gradient text-white p-3">
                <h6 class="mb-0 fw-bold"><i class="fas fa-map-marked-alt"></i> Pick Coordinates on Map</h6>
            </div>
            <div id="pickerMap" class="flex-grow-1" style="height: 100%; min-height: 380px;"></div>
        </div>
    </div>
</div>

<!-- ═══════════ SCRIPTS ═══════════ -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const defaultLat = <?php echo $default_lat; ?>;
    const defaultLng = <?php echo $default_lng; ?>;
    
    // Initialize picker map
    const map = L.map('pickerMap').setView([defaultLat, defaultLng], 12);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    let marker = null;
    const inputLat = document.getElementById('inputLat');
    const inputLng = document.getElementById('inputLng');

    // Handle clicks on map to set marker & update input fields
    map.on('click', (e) => {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;

        inputLat.value = lat.toFixed(6);
        inputLng.value = lng.toFixed(6);

        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng, { draggable: true }).addTo(map);
            
            // Listen for marker drag events
            marker.on('dragend', () => {
                const pos = marker.getLatLng();
                inputLat.value = pos.lat.toFixed(6);
                inputLng.value = pos.lng.toFixed(6);
            });
        }
    });
});
</script>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
