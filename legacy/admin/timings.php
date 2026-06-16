<?php
/**
 * Admin Timings Update Panel — Masjid Locator & Namaz Timings System
 * 
 * Features:
 * - Select masjid dropdown
 * - Direct editable form for all 7 timing fields
 * - Prepared statement updates
 * - Full list summary display of current timings below
 */

$admin_page = 'timings';
$page_title = 'Update Namaz Timings';
require_once __DIR__ . '/includes/sidebar.php';

$all_masjids = getAllMasjids($conn);

// Retrieve selected masjid, default to first in list
$selected_id = isset($_GET['masjid_id']) ? intval($_GET['masjid_id']) : 0;
$masjid = null;

if ($selected_id > 0) {
    $masjid = getMasjidById($conn, $selected_id);
}

if (!$masjid && !empty($all_masjids)) {
    $masjid = $all_masjids[0];
}

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $masjid) {
    $fajr = sanitize($_POST['fajr'] ?? '');
    $zuhr = sanitize($_POST['zuhr'] ?? '');
    $asr = sanitize($_POST['asr'] ?? '');
    $maghrib = sanitize($_POST['maghrib'] ?? '');
    $isha = sanitize($_POST['isha'] ?? '');
    $juma_time = sanitize($_POST['juma_time'] ?? '');
    $eid_time = sanitize($_POST['eid_time'] ?? '');

    $errors = [];
    if ($fajr === '' || $zuhr === '' || $asr === '' || $maghrib === '' || $isha === '' || $juma_time === '') {
        $errors[] = 'Daily prayer and Juma times are required.';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE masjids SET fajr=?, zuhr=?, asr=?, maghrib=?, isha=?, juma_time=?, eid_time=? WHERE id=?");
        $stmt->bind_param("sssssssi", $fajr, $zuhr, $asr, $maghrib, $isha, $juma_time, $eid_time, $masjid['id']);

        if ($stmt->execute()) {
            $timing_stmt = $conn->prepare("INSERT INTO masjid_prayer_timings (masjid_id, fajr, zuhr, asr, maghrib, isha, juma_time, eid_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ON DUPLICATE KEY UPDATE fajr=VALUES(fajr), zuhr=VALUES(zuhr), asr=VALUES(asr), maghrib=VALUES(maghrib), isha=VALUES(isha), juma_time=VALUES(juma_time), eid_time=VALUES(eid_time)");
            $timing_stmt->bind_param("isssssss", $masjid['id'], $fajr, $zuhr, $asr, $maghrib, $isha, $juma_time, $eid_time);
            $timing_stmt->execute();
            $timing_stmt->close();
            setFlashMessage('success', 'Prayer timings for <strong>' . sanitize($masjid['name']) . '</strong> updated successfully!');
            // Refresh masjid details
            $masjid = getMasjidById($conn, $masjid['id']);
            // Refresh masjids list
            $all_masjids = getAllMasjids($conn);
        } else {
            $errors[] = 'Database update failed: ' . $conn->error;
        }
        $stmt->close();
    }

    if (!empty($errors)) {
        setFlashMessage('danger', implode('<br>', $errors));
    }
}
?>

<div class="row g-4">
    <!-- Form Block -->
    <div class="col-lg-5">
        <div class="admin-form-card">
            <h5><i class="fas fa-clock"></i> Select & Update Timings</h5>
            
            <form action="" method="GET" class="mb-4">
                <label class="form-label fw-bold text-success">Choose Mosque</label>
                <select name="masjid_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    <?php foreach ($all_masjids as $m): ?>
                        <option value="<?php echo $m['id']; ?>" <?php echo ($masjid && $masjid['id'] == $m['id']) ? 'selected' : ''; ?>>
                            [<?php echo $m['sect']; ?>] <?php echo sanitize($m['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>

            <?php if ($masjid): ?>
                <form action="?masjid_id=<?php echo $masjid['id']; ?>" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Fajr *</label>
                        <input type="time" name="fajr" class="form-control" value="<?php echo sanitize($masjid['fajr']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Zuhr *</label>
                        <input type="time" name="zuhr" class="form-control" value="<?php echo sanitize($masjid['zuhr']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Asr *</label>
                        <input type="time" name="asr" class="form-control" value="<?php echo sanitize($masjid['asr']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Maghrib *</label>
                        <input type="time" name="maghrib" class="form-control" value="<?php echo sanitize($masjid['maghrib']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Isha *</label>
                        <input type="time" name="isha" class="form-control" value="<?php echo sanitize($masjid['isha']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Juma (Friday) *</label>
                        <input type="time" name="juma_time" class="form-control" value="<?php echo sanitize($masjid['juma_time']); ?>" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Eid Prayer</label>
                        <input type="time" name="eid_time" class="form-control" value="<?php echo sanitize($masjid['eid_time']); ?>">
                    </div>

                    <button type="submit" class="btn btn-success fw-bold w-100 py-2">
                        <i class="fas fa-save me-1"></i> Save Timings
                    </button>
                </form>
            <?php else: ?>
                <div class="alert alert-warning">Please register mosques first.</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Overview Grid -->
    <div class="col-lg-7">
        <div class="admin-table-card h-100">
            <div class="card-header"><i class="fas fa-table"></i> Current Timings Directory</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table admin-table mb-0 text-center align-middle">
                        <thead class="table-success align-middle">
                            <tr>
                                <th class="text-start ps-3">Name</th>
                                <th>Fajr</th>
                                <th>Zuhr</th>
                                <th>Asr</th>
                                <th>Maghrib</th>
                                <th>Isha</th>
                                <th>Juma</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($all_masjids as $m): ?>
                                <tr class="<?php echo ($masjid && $masjid['id'] == $m['id']) ? 'table-success' : ''; ?>">
                                    <td class="text-start ps-3">
                                        <a href="?masjid_id=<?php echo $m['id']; ?>" class="fw-bold text-success text-decoration-none">
                                            <?php echo sanitize($m['name']); ?>
                                        </a>
                                    </td>
                                    <td><?php echo formatTime12h($m['fajr']); ?></td>
                                    <td><?php echo formatTime12h($m['zuhr']); ?></td>
                                    <td><?php echo formatTime12h($m['asr']); ?></td>
                                    <td><?php echo formatTime12h($m['maghrib']); ?></td>
                                    <td><?php echo formatTime12h($m['isha']); ?></td>
                                    <td class="fw-bold"><?php echo formatTime12h($m['juma_time']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
