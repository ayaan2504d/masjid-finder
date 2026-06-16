<?php
/**
 * Juma & Eid Details Page — Masjid Locator & Namaz Timings System
 * 
 * Features:
 * - Special timeline display of timings
 * - Custom description of Khutbah & sermons
 * - Leaflet map centering
 * - Google directions navigation link
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$masjid = getMasjidById($conn, $id);

if (!$masjid) {
    setFlashMessage('danger', 'The selected masjid was not found in our database.');
    header('Location: ' . $base_url . '/timings/juma-eid.php');
    exit;
}

$page_title = $masjid['name'] . ' Special Timings';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- ═══════════ BREADCRUMB SECTION ═══════════ -->
<section class="breadcrumb-section">
    <div class="container">
        <h2 class="breadcrumb-title"><?php echo sanitize($masjid['name']); ?> Special Details</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/">Home</a></li>
                <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/timings/juma-eid.php">Juma & Eid</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo sanitize($masjid['name']); ?> details</li>
            </ol>
        </nav>
    </div>
</section>

<!-- ═══════════ SPECIAL DETAILS CONTENT ═══════════ -->
<div class="container my-5 animate-fadeInUp">
    <div class="row g-4">
        <!-- Left Column: Special Timings Timeline -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 rounded-4 p-4 mb-4">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <span class="badge bg-success badge-sect"><?php echo $masjid['sect']; ?> School</span>
                    <h4 class="fw-bold text-success mb-0"><i class="fas fa-calendar-alt"></i> Congregation Schedule</h4>
                </div>

                <div class="special-timeline ps-2">
                    <!-- Timeline Item 1: Juma -->
                    <div class="timeline-item">
                        <h5 class="fw-bold text-success"><i class="fas fa-mosque me-1 text-success"></i> Friday (Juma) Prayer</h5>
                        <p class="text-secondary small mb-1">Weekly congregational sermon and prayer timings:</p>
                        <div class="p-3 bg-light rounded-3 d-inline-block mt-2">
                            <span class="text-muted small d-block">Khutbah & Jama'at</span>
                            <span class="fs-4 fw-bold text-primary"><?php echo formatTime12h($masjid['juma_time']); ?></span>
                        </div>
                        <p class="text-muted small mt-2"><i class="fas fa-info-circle text-info me-1"></i> Sermon starts 15-20 minutes before the prayer time. Worshippers are requested to perform wudhu beforehand.</p>
                    </div>

                    <!-- Timeline Item 2: Eid -->
                    <?php if ($masjid['eid_time']): ?>
                        <div class="timeline-item">
                            <h5 class="fw-bold text-success"><i class="fas fa-star-and-crescent me-1 text-warning"></i> Eid Prayers (Fitr/Adha)</h5>
                            <p class="text-secondary small mb-1">Bi-annual Eid prayer congregations:</p>
                            <div class="p-3 bg-light rounded-3 d-inline-block mt-2">
                                <span class="text-muted small d-block">Eid Jama'at Time</span>
                                <span class="fs-4 fw-bold text-success"><?php echo formatTime12h($masjid['eid_time']); ?></span>
                            </div>
                            <p class="text-muted small mt-2"><i class="fas fa-exclamation-triangle text-warning me-1"></i> Please check back near the lunar sighting announcements for confirmation updates.</p>
                        </div>
                    <?php endif; ?>

                    <!-- Timeline Item 3: Daily Routine -->
                    <div class="timeline-item pb-0 border-0">
                        <h5 class="fw-bold text-success"><i class="fas fa-clock me-1 text-info"></i> Daily Prayers (Salah)</h5>
                        <p class="text-secondary small mb-3">Regular five daily prayers occur as scheduled below:</p>
                        <div class="row g-2 text-center text-secondary small bg-light rounded-3 p-3">
                            <div class="col"><strong>Fajr</strong><br><?php echo formatTime12h($masjid['fajr']); ?></div>
                            <div class="col"><strong>Zuhr</strong><br><?php echo formatTime12h($masjid['zuhr']); ?></div>
                            <div class="col"><strong>Asr</strong><br><?php echo formatTime12h($masjid['asr']); ?></div>
                            <div class="col"><strong>Maghrib</strong><br><?php echo formatTime12h($masjid['maghrib']); ?></div>
                            <div class="col"><strong>Isha</strong><br><?php echo formatTime12h($masjid['isha']); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Location & Contact -->
        <div class="col-lg-5">
            <!-- Map Card -->
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                <div class="map-embed" id="detailsMap" style="height: 300px;"></div>
                <div class="card-body">
                    <h5 class="fw-bold text-success mb-2"><?php echo sanitize($masjid['name']); ?></h5>
                    <p class="text-secondary small mb-3"><i class="fas fa-map-marker-alt text-success me-1"></i> <?php echo sanitize($masjid['address']); ?></p>
                    
                    <a href="https://www.google.com/maps/search/?api=1&query=<?php echo $masjid['latitude']; ?>,<?php echo $masjid['longitude']; ?>" target="_blank" class="btn btn-gold w-100" data-directions-link="true" data-dest-lat="<?php echo $masjid['latitude']; ?>" data-dest-lng="<?php echo $masjid['longitude']; ?>">
                        <i class="fas fa-location-arrow me-1"></i> Get Directions (Google Maps)
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ═══════════ SCRIPTS ═══════════ -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const lat = <?php echo $masjid['latitude']; ?>;
    const lng = <?php echo $masjid['longitude']; ?>;
    const name = "<?php echo addslashes($masjid['name']); ?>";

    // Initialize map
    MapHelper.init('detailsMap', lat, lng, 15);
    
    // Add mosque marker
    L.marker([lat, lng], { icon: MapHelper.getMosqueIcon() })
        .bindPopup(`<strong>${name}</strong>`)
        .addTo(MapHelper.map);
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
