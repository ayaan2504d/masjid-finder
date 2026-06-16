<?php
/**
 * Prayer Timings — Masjid Locator & Namaz Timings System
 * 
 * Features:
 * - Mosque selector dropdown
 * - Live prayer countdown timer
 * - Todays date in Islamic format
 * - Detailed prayer timings table with current/next prayer highlighting
 * - Juma details highlight
 */

$page_title = 'Prayer Timings';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$all_masjids = getAllMasjids($conn);

// Select masjid (default to first)
$selected_id = isset($_GET['masjid_id']) ? intval($_GET['masjid_id']) : 0;
$masjid = null;

if ($selected_id > 0) {
    $masjid = getMasjidById($conn, $selected_id);
}

if (!$masjid && !empty($all_masjids)) {
    $masjid = $all_masjids[0];
}

$next_prayer = ['name' => 'Fajr', 'time' => '04:30'];
if ($masjid) {
    $next_prayer = getNextPrayer($masjid);
}
?>

<!-- ═══════════ BREADCRUMB SECTION ═══════════ -->
<section class="breadcrumb-section">
    <div class="container">
        <h2 class="breadcrumb-title">Prayer Timings</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Namaz Schedule</li>
            </ol>
        </nav>
    </div>
</section>

<!-- ═══════════ TIMINGS CONTENT ═══════════ -->
<div class="container my-5 animate-fadeInUp">
    <div class="row g-4">
        <!-- Left Column: Masjid Selector & Countdown -->
        <div class="col-lg-5">
            <!-- Selector Dropdown -->
            <div class="card shadow-sm border-0 rounded-4 p-4 mb-4">
                <h5 class="fw-bold text-success mb-3"><i class="fas fa-search-location"></i> Select Masjid</h5>
                <form action="" method="GET">
                    <select name="masjid_id" class="form-select form-select-lg mb-3" onchange="this.form.submit()">
                        <?php foreach ($all_masjids as $m): ?>
                            <option value="<?php echo $m['id']; ?>" <?php echo ($masjid && $masjid['id'] == $m['id']) ? 'selected' : ''; ?>>
                                [<?php echo $m['sect']; ?>] <?php echo sanitize($m['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
                <?php if ($masjid): ?>
                    <p class="text-secondary small mb-0"><i class="fas fa-map-marker-alt"></i> <?php echo sanitize($masjid['address']); ?></p>
                <?php endif; ?>
            </div>

            <!-- Countdown Timer Card -->
            <?php if ($masjid): ?>
                <div class="countdown-card" id="countdownCard">
                    <div class="countdown-label">COUNTDOWN TO NEXT PRAYER</div>
                    <div class="countdown-prayer-name" id="countdownPrayerName"><?php echo $next_prayer['name']; ?></div>
                    <div class="countdown-digits" id="prayerCountdown">
                        <div class="digit-box">
                            <div class="digit-value countdown-hours">00</div>
                            <div class="digit-label">Hrs</div>
                        </div>
                        <div class="digit-box">
                            <div class="digit-value countdown-minutes">00</div>
                            <div class="digit-label">Min</div>
                        </div>
                        <div class="digit-box">
                            <div class="digit-value countdown-seconds">00</div>
                            <div class="digit-label">Sec</div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Right Column: Today's Schedule -->
        <div class="col-lg-7">
            <?php if ($masjid): ?>
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <div class="bg-success bg-gradient text-white p-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <h4 class="mb-1 fw-bold"><?php echo sanitize($masjid['name']); ?></h4>
                            <span class="badge bg-white text-success fw-bold"><?php echo $masjid['sect']; ?> School</span>
                        </div>
                        <div class="text-md-end">
                            <div class="fw-bold"><i class="far fa-calendar-alt"></i> <?php echo date('l, d F Y'); ?></div>
                            <div class="small text-white-50">Hijri date placeholder</div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-hover prayer-table mb-0">
                            <thead>
                                <tr>
                                    <th>Namaz (Salah)</th>
                                    <th>Azaan / Jama'at Time</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr id="row-fajr" class="<?php echo $next_prayer['name'] === 'Fajr' ? 'current-prayer' : ''; ?>">
                                    <td>
                                        <span class="prayer-icon prayer-icon-fajr"><i class="fas fa-feather-alt"></i></span> 
                                        <strong>Fajr</strong>
                                    </td>
                                    <td><?php echo formatTime12h($masjid['fajr']); ?></td>
                                    <td>
                                        <?php if ($next_prayer['name'] === 'Fajr'): ?>
                                            <span class="badge bg-success">Next Namaz</span>
                                        <?php else: ?>
                                            <span class="text-muted small">Upcoming</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr id="row-zuhr" class="<?php echo $next_prayer['name'] === 'Zuhr' ? 'current-prayer' : ''; ?>">
                                    <td>
                                        <span class="prayer-icon prayer-icon-zuhr"><i class="fas fa-sun"></i></span> 
                                        <strong>Zuhr</strong>
                                    </td>
                                    <td><?php echo formatTime12h($masjid['zuhr']); ?></td>
                                    <td>
                                        <?php if ($next_prayer['name'] === 'Zuhr'): ?>
                                            <span class="badge bg-success">Next Namaz</span>
                                        <?php else: ?>
                                            <span class="text-muted small">Upcoming</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr id="row-asr" class="<?php echo $next_prayer['name'] === 'Asr' ? 'current-prayer' : ''; ?>">
                                    <td>
                                        <span class="prayer-icon prayer-icon-asr"><i class="fas fa-cloud-sun"></i></span> 
                                        <strong>Asr</strong>
                                    </td>
                                    <td><?php echo formatTime12h($masjid['asr']); ?></td>
                                    <td>
                                        <?php if ($next_prayer['name'] === 'Asr'): ?>
                                            <span class="badge bg-success">Next Namaz</span>
                                        <?php else: ?>
                                            <span class="text-muted small">Upcoming</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr id="row-maghrib" class="<?php echo $next_prayer['name'] === 'Maghrib' ? 'current-prayer' : ''; ?>">
                                    <td>
                                        <span class="prayer-icon prayer-icon-maghrib"><i class="fas fa-cloud-moon"></i></span> 
                                        <strong>Maghrib</strong>
                                    </td>
                                    <td><?php echo formatTime12h($masjid['maghrib']); ?></td>
                                    <td>
                                        <?php if ($next_prayer['name'] === 'Maghrib'): ?>
                                            <span class="badge bg-success">Next Namaz</span>
                                        <?php else: ?>
                                            <span class="text-muted small">Upcoming</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr id="row-isha" class="<?php echo $next_prayer['name'] === 'Isha' ? 'current-prayer' : ''; ?>">
                                    <td>
                                        <span class="prayer-icon prayer-icon-isha"><i class="fas fa-moon"></i></span> 
                                        <strong>Isha</strong>
                                    </td>
                                    <td><?php echo formatTime12h($masjid['isha']); ?></td>
                                    <td>
                                        <?php if ($next_prayer['name'] === 'Isha'): ?>
                                            <span class="badge bg-success">Next Namaz</span>
                                        <?php else: ?>
                                            <span class="text-muted small">Upcoming</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <tr class="table-success">
                                    <td>
                                        <span class="prayer-icon bg-success text-white"><i class="fas fa-users"></i></span> 
                                        <strong>Juma (Friday)</strong>
                                    </td>
                                    <td><strong><?php echo formatTime12h($masjid['juma_time']); ?></strong></td>
                                    <td><span class="badge bg-success">Weekly</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">Please add mosques in the admin panel to view timings.</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Bottom Buttons / Comparison Page Link -->
    <div class="row mt-5">
        <div class="col-md-6 mb-3">
            <div class="card p-4 shadow-sm border-0 rounded-4 h-100 text-center">
                <h5 class="fw-bold text-success mb-2"><i class="fas fa-table"></i> Compare All Timings</h5>
                <p class="text-secondary small">Compare Fajr, Zuhr, Asr, Maghrib, Isha, and Juma timings across all registered mosques in Karachi.</p>
                <a href="<?php echo $base_url; ?>/timings/timings-details.php" class="btn btn-outline-custom mt-auto">Compare Timings Table →</a>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card p-4 shadow-sm border-0 rounded-4 h-100 text-center">
                <h5 class="fw-bold text-success mb-2"><i class="fas fa-star-and-crescent"></i> Juma & Eid timings</h5>
                <p class="text-secondary small">Find the Friday sermon details, special Eid-ul-Fitr, and Eid-ul-Adha timings along with community notices.</p>
                <a href="<?php echo $base_url; ?>/timings/juma-eid.php" class="btn btn-primary-custom mt-auto">Juma & Eid Timings →</a>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════ SCRIPTS ═══════════ -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Start countdown for selected masjid
    <?php if ($masjid): ?>
    PrayerCountdown.start(<?php echo json_encode($masjid); ?>, "prayerCountdown");
    <?php endif; ?>
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
