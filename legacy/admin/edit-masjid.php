<?php
/**
 * Admin Edit Masjid — Masjid Locator & Namaz Timings System
 * 
 * Features:
 * - Pre-populated profile form
 * - Interactive Leaflet map with a draggable pin at current coordinates
 * - MySQL prepared statement updates
 */

$admin_page = 'masjids';
$page_title = 'Edit Masjid';
require_once __DIR__ . '/includes/sidebar.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$masjid = getMasjidById($conn, $id);

if (!$masjid) {
    setFlashMessage('danger', 'The selected masjid was not found.');
    header('Location: ' . $base_url . '/admin/masjids.php');
    exit;
}

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
    if ($latitude === 0.0 || $longitude === 0.0) $errors[] = 'Please specify valid coordinates.';
    if ($fajr === '' || $zuhr === '' || $asr === '' || $maghrib === '' || $isha === '' || $juma_time === '') {
        $errors[] = 'Daily prayer and Juma times are required.';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE masjids SET name=?, address=?, sect=?, latitude=?, longitude=?, fajr=?, zuhr=?, asr=?, maghrib=?, isha=?, juma_time=?, eid_time=?, phone=?, description=?, is_featured=? WHERE id=?");
        
        $stmt->bind_param("sssddsssssssssii", 
            $name, $address, $sect, $latitude, $longitude,
            $fajr, $zuhr, $asr, $maghrib, $isha,
            $juma_time, $eid_time, $phone, $description, $is_featured, $id
        );

        if ($stmt->execute()) {
            $timing_stmt = $conn->prepare("INSERT INTO masjid_prayer_timings (masjid_id, fajr, zuhr, asr, maghrib, isha, juma_time, eid_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE fajr=VALUES(fajr), zuhr=VALUES(zuhr), asr=VALUES(asr), maghrib=VALUES(maghrib), isha=VALUES(isha), juma_time=VALUES(juma_time), eid_time=VALUES(eid_time)");
            $timing_stmt->bind_param("isssssss", $id, $fajr, $zuhr, $asr, $maghrib, $isha, $juma_time, $eid_time);
            $timing_stmt->execute();
            $timing_stmt->close();
            setFlashMessage('success', 'Masjid profile updated successfully!');
            header('Location: ' . $base_url . '/admin/masjids.php');
            exit;
        } else {
            $errors[] = 'Database update failed: ' . $conn->error;
        }
        $stmt->close();
    }

    if (!empty($errors)) {
        setFlashMessage('danger', implode('<br>', $errors));
    }
    
    // Refresh $masjid with edited values if validation fails to keep inputs intact
    $masjid = $_POST;
    $masjid['id'] = $id;
}
?>

<div class="row g-4">
    <!-- Form Block -->
    <div class="col-lg-7">
        <div class="admin-form-card">
            <h5><i class="fas fa-edit"></i> Edit Masjid Details</h5>
            <form action="" method="POST">
                <!-- Basic Info -->
                <div class="mb-3">
                    <label class="form-label">Masjid Name *</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Badshahi Mosque" value="<?php echo sanitize($masjid['name']); ?>" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label">Sect/School *</label>
                        <select name="sect" class="form-select">
                            <option value="Sunni" <?php echo ($masjid['sect'] === 'Sunni') ? 'selected' : ''; ?>>Sunni</option>
                            <option value="Shia" <?php echo ($masjid['sect'] === 'Shia') ? 'selected' : ''; ?>>Shia</option>
                        </select>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" placeholder="e.g. +92 42 1234567" value="<?php echo sanitize($masjid['phone']); ?>">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Address *</label>
                    <textarea name="address" class="form-control" rows="2" placeholder="e.g. Circular Road, Walled City, Lahore" required><?php echo sanitize($masjid['address']); ?></textarea>
                </div>

                <!-- Coordinates Picker (Updates dynamically via Map clicking or drag) -->
                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label">Latitude *</label>
                        <input type="number" step="any" name="latitude" id="inputLat" class="form-control" placeholder="e.g. 31.5881" value="<?php echo floatval($masjid['latitude']); ?>" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Longitude *</label>
                        <input type="number" step="any" name="longitude" id="inputLng" class="form-control" placeholder="e.g. 74.3107" value="<?php echo floatval($masjid['longitude']); ?>" required>
                    </div>
                    <div class="col-12">
                        <span class="small text-muted"><i class="fas fa-info-circle"></i> Drag the map marker on the right or click anywhere to adjust coordinates.</span>
                    </div>
                </div>

                <!-- Prayer Timings -->
                <h5 class="mt-4 pt-2 border-top"><i class="fas fa-clock"></i> Prayer Timings</h5>
                
                <div class="row g-3 mb-3">
                    <div class="col">
                        <label class="form-label">Fajr *</label>
                        <input type="time" name="fajr" class="form-control" value="<?php echo sanitize($masjid['fajr']); ?>" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Zuhr *</label>
                        <input type="time" name="zuhr" class="form-control" value="<?php echo sanitize($masjid['zuhr']); ?>" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Asr *</label>
                        <input type="time" name="asr" class="form-control" value="<?php echo sanitize($masjid['asr']); ?>" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Maghrib *</label>
                        <input type="time" name="maghrib" class="form-control" value="<?php echo sanitize($masjid['maghrib']); ?>" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Isha *</label>
                        <input type="time" name="isha" class="form-control" value="<?php echo sanitize($masjid['isha']); ?>" required>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <label class="form-label">Juma Salah Time *</label>
                        <input type="time" name="juma_time" class="form-control" value="<?php echo sanitize($masjid['juma_time']); ?>" required>
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label">Eid Salah Time</label>
                        <input type="time" name="eid_time" class="form-control" value="<?php echo sanitize($masjid['eid_time']); ?>">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label">Description / Announcements</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Historical context, parking details or regular announcements..."><?php echo sanitize($masjid['description']); ?></textarea>
                </div>

                <!-- Checkbox Option -->
                <div class="mb-4 form-check">
                    <input type="checkbox" name="is_featured" class="form-check-input" id="checkFeatured" <?php echo ($masjid['is_featured'] == 1) ? 'checked' : ''; ?>>
                    <label class="form-check-label fw-bold text-success" for="checkFeatured">Feature this Masjid on Homepage</label>
                </div>

                <div class="row g-2">
                    <div class="col-sm-6">
                        <a href="<?php echo $base_url; ?>/admin/masjids.php" class="btn btn-secondary px-4 py-2 w-100">Cancel</a>
                    </div>
                    <div class="col-sm-6">
                        <button type="submit" class="btn btn-success px-4 py-2 fw-bold w-100">
                            <i class="fas fa-save me-1"></i> Update Profile
                        </button>
                    </div>
                </div>
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
    const lat = <?php echo floatval($masjid['latitude']); ?>;
    const lng = <?php echo floatval($masjid['longitude']); ?>;
    
    // Initialize picker map centered at current coordinates
    const map = L.map('pickerMap').setView([lat, lng], 14);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const inputLat = document.getElementById('inputLat');
    const inputLng = document.getElementById('inputLng');

    // Add draggable marker at current coordinates
    let marker = L.marker([lat, lng], { draggable: true }).addTo(map);

    // Drag helper
    marker.on('dragend', () => {
        const pos = marker.getLatLng();
        inputLat.value = pos.lat.toFixed(6);
        inputLng.value = pos.lng.toFixed(6);
    });

    // Handle clicks on map to reset marker coordinates
    map.on('click', (e) => {
        marker.setLatLng(e.latlng);
        inputLat.value = e.latlng.lat.toFixed(6);
        inputLng.value = e.latlng.lng.toFixed(6);
    });
});
</script>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
