<?php
/**
 * Homepage — Masjid Locator & Namaz Timings System
 * 
 * Features:
 * - Hero section with search bar and GPS location trigger
 * - Live prayer countdown timer
 * - Dynamic nearest (recommended) masjid detection using browser GPS
 * - Embedded Leaflet map showing nearby masjids
 * - Filterable featured masjids cards (All / Sunni / Shia)
 */

$page_title = 'Find Nearest Masjid';
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

// Get featured masjids from database
$featured = getFeaturedMasjids($conn, 6);

// Get all masjids for JSON mapping
$all_masjids_json = getMasjidsJson($conn);

// Get default masjid for next prayer countdown if GPS is not yet loaded
// We use the first featured masjid or first masjid from database
$default_masjid = null;
if (!empty($featured)) {
    $default_masjid = $featured[0];
} else {
    $all = getAllMasjids($conn);
    if (!empty($all)) {
        $default_masjid = $all[0];
    }
}

$next_prayer = ['name' => 'Fajr', 'time' => '04:30'];
if ($default_masjid) {
    $next_prayer = getNextPrayer($default_masjid);
}
?>

<!-- ═══════════ HERO SECTION ═══════════ -->
<section class="hero-section text-white py-5">
    <div class="container hero-content">
        <div class="row align-items-center g-5">
            <!-- Hero Left: Info, Search, GPS -->
            <div class="col-lg-6 animate-fadeInLeft">
                <span class="badge bg-white text-success px-3 py-2 rounded-pill mb-3 fw-bold shadow-sm">
                    ✨ SMART GPS LOCATOR
                </span>
                <h1 class="hero-title mb-3">
                    Find Your <span class="highlight">Nearest</span> Masjid & Prayer Timings
                </h1>
                <p class="hero-subtitle mb-4">
                    Instantly locate mosques around you, view accurate daily prayer times, get directions, and keep track of Friday (Juma) and Eid salah schedules.
                </p>

                <!-- Search box -->
                <div class="hero-search mb-3">
                    <form action="<?php echo $base_url; ?>/masjids/index.php" method="GET">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search by masjid name or area..." aria-label="Search masjid">
                            <button class="btn btn-gold" type="submit">
                                <i class="fas fa-search"></i> Search
                            </button>
                        </div>
                    </form>
                </div>

                <!-- GPS Locate Button -->
                <button id="btnGPSLocate" class="hero-gps-btn">
                    <i class="fas fa-location-crosshairs fa-lg me-1"></i> 
                    <span>Locate Nearest Masjid</span>
                </button>
                
                <div id="gpsStatus" class="mt-2 small text-warning d-none"></div>
            </div>

            <!-- Hero Right: Live Interactive Map -->
            <div class="col-lg-6 animate-fadeInUp">
                <div class="hero-map-container">
                    <div id="heroMap" style="height: 100%; width: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ═══════════ DYNAMIC CONTENT SECTION ═══════════ -->
<div class="container my-5">
    <!-- Nearest / Recommended Masjid Panel (Populated dynamically via JS) -->
    <div id="recommendedSection" class="row mb-5 d-none">
        <div class="col-12">
            <h3 class="section-title mb-4">Nearest Recommended Masjid</h3>
            <div id="recommendedContainer">
                <!-- Recommended card will be injected here -->
            </div>
        </div>
    </div>

    <!-- Countdown & Next Prayer Grid -->
    <div class="row g-4 mb-5">
        <!-- Next Prayer Countdown Card -->
        <div class="col-lg-6">
            <div class="countdown-card" id="countdownCard">
                <div class="countdown-label">Next Prayer Countdown</div>
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
                <?php if ($default_masjid): ?>
                    <p class="mt-3 mb-0 small text-white-50">
                        Based on default: <strong><?php echo sanitize($default_masjid['name']); ?></strong> 
                        (Timings: <?php echo formatTime12h($default_masjid[strtolower($next_prayer['name'])]); ?>)
                    </p>
                <?php endif; ?>
            </div>
        </div>

        <!-- System Quick Stats -->
        <div class="col-lg-6">
            <div class="row g-4 h-100">
                <div class="col-sm-6">
                    <div class="stat-card h-100 d-flex flex-column justify-content-center">
                        <div class="stat-icon"><i class="fas fa-mosque"></i></div>
                        <div class="stat-number"><?php echo getTotalMasjids($conn); ?></div>
                        <div class="stat-label">Total Masajid Covered</div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="stat-card h-100 d-flex flex-column justify-content-center">
                        <div class="stat-icon bg-info"><i class="fas fa-users"></i></div>
                        <div class="stat-number"><?php echo getCountBySect($conn, 'Sunni'); ?> / <?php echo getCountBySect($conn, 'Shia'); ?></div>
                        <div class="stat-label">Sunni / Shia Masajid</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Featured Masjids Section -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h3 class="section-title">Featured Masajid</h3>
            <p class="section-subtitle">Discover historical and prominent mosques in our directory</p>
        </div>
        <div class="col-md-6 text-md-end d-flex align-items-center justify-content-md-end gap-2 mb-4">
            <button class="sect-filter-btn active" data-filter="all" onclick="filterBySect('all')">All</button>
            <button class="sect-filter-btn" data-filter="Sunni" onclick="filterBySect('Sunni')">Sunni</button>
            <button class="sect-filter-btn" data-filter="Shia" onclick="filterBySect('Shia')">Shia</button>
        </div>
    </div>

    <div class="row g-4 mb-5" id="featuredGrid">
        <?php if (!empty($featured)): ?>
            <?php foreach ($featured as $m): ?>
                <div class="col-lg-4 col-md-6 masjid-item-col" data-sect="<?php echo $m['sect']; ?>">
                    <div class="masjid-card">
                        <div class="card-img-top">
                            <i class="fas fa-mosque"></i>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge badge-sect badge-<?php echo strtolower($m['sect']); ?>">
                                    <?php echo $m['sect']; ?>
                                </span>
                                <span class="distance-badge d-none" data-lat="<?php echo $m['latitude']; ?>" data-lng="<?php echo $m['longitude']; ?>">
                                    <i class="fas fa-route"></i> Calculating...
                                </span>
                            </div>
                            <h5 class="card-title"><?php echo sanitize($m['name']); ?></h5>
                            <p class="card-text text-truncate-2 mb-3">
                                <i class="fas fa-map-marker-alt text-success me-1"></i> <?php echo sanitize($m['address']); ?>
                            </p>
                            
                            <!-- Small grid of prayer times for quick view -->
                            <div class="row g-1 text-center py-2 px-1 bg-light rounded mb-3 small">
                                <div class="col"><strong>Fajr</strong><br><?php echo formatTime12h($m['fajr']); ?></div>
                                <div class="col"><strong>Zuhr</strong><br><?php echo formatTime12h($m['zuhr']); ?></div>
                                <div class="col"><strong>Asr</strong><br><?php echo formatTime12h($m['asr']); ?></div>
                                <div class="col"><strong>Maghrib</strong><br><?php echo formatTime12h($m['maghrib']); ?></div>
                                <div class="col"><strong>Isha</strong><br><?php echo formatTime12h($m['isha']); ?></div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="<?php echo $base_url; ?>/masjids/masjid-details.php?id=<?php echo $m['id']; ?>" class="btn btn-outline-custom w-100 btn-sm">
                                View Details <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-mosque fa-3x text-muted mb-3"></i>
                <p class="text-muted">No masjids found. Run setup database first.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ═══════════ SCRIPTS ═══════════ -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // 1. Initialize Leaflet Map
    const masjids = <?php echo $all_masjids_json; ?>;
    const defaultLat = 24.8607;
    const defaultLng = 67.0011;
    
    // Init map focused on default city center (Karachi)
    MapHelper.init('heroMap', defaultLat, defaultLng, 12);
    MapHelper.addMasjidMarkers(masjids);

    // 2. Start Countdown Timer for the default masjid
    <?php if ($default_masjid): ?>
    PrayerCountdown.start(<?php echo json_encode($default_masjid); ?>, "prayerCountdown");
    <?php endif; ?>

    // 3. GPS Location Handler
    const btnGPS = document.getElementById('btnGPSLocate');
    const gpsStatus = document.getElementById('gpsStatus');
    const recommendedSection = document.getElementById('recommendedSection');
    const recommendedContainer = document.getElementById('recommendedContainer');

    btnGPS.addEventListener('click', () => {
        btnGPS.disabled = true;
        btnGPS.querySelector('span').textContent = 'Locating...';
        gpsStatus.classList.remove('d-none');
        gpsStatus.textContent = 'Acquiring GPS location...';

        GPS.getLocation().then(pos => {
            btnGPS.querySelector('span').textContent = 'Location Updated';
            gpsStatus.innerHTML = `<i class="fas fa-check-circle"></i> Location acquired! Accuracy within meters.`;
            gpsStatus.className = 'mt-2 small text-white';

            // Add user marker & center map
            MapHelper.addUserMarker(pos.lat, pos.lng);
            MapHelper.map.setView([pos.lat, pos.lng], 13);
            MapHelper.addMasjidMarkers(masjids, pos.lat, pos.lng);

            // Calculate distance for all featured cards
            const distBadges = document.querySelectorAll('.distance-badge');
            distBadges.forEach(badge => {
                const lat = parseFloat(badge.dataset.lat);
                const lng = parseFloat(badge.dataset.lng);
                const dist = haversineDistance(pos.lat, pos.lng, lat, lng);
                badge.innerHTML = `<i class="fas fa-route"></i> ${dist} km`;
                badge.classList.remove('d-none');
            });

            // Find nearest masjid from list
            let nearest = null;
            let minDist = Infinity;
            masjids.forEach(m => {
                const dist = parseFloat(haversineDistance(pos.lat, pos.lng, m.latitude, m.longitude));
                if (dist < minDist) {
                    minDist = dist;
                    nearest = m;
                }
            });

            if (nearest) {
                // Show Recommended Masjid card
                recommendedSection.classList.remove('d-none');
                
                // Format times for display
                const formattedFajr = formatTime12h(nearest.fajr);
                const formattedZuhr = formatTime12h(nearest.zuhr);
                const formattedAsr = formatTime12h(nearest.asr);
                const formattedMaghrib = formatTime12h(nearest.maghrib);
                const formattedIsha = formatTime12h(nearest.isha);

                recommendedContainer.innerHTML = `
                    <div class="recommended-card p-4 animate-fadeInUp">
                        <div class="row g-4 align-items-center">
                            <div class="col-md-7">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <span class="badge badge-sect badge-${nearest.sect.toLowerCase()}">${nearest.sect}</span>
                                    <span class="badge bg-primary rounded-pill"><i class="fas fa-route"></i> ${minDist.toFixed(2)} km away</span>
                                </div>
                                <h4 class="text-success fw-bold">${nearest.name}</h4>
                                <p class="text-secondary mb-3"><i class="fas fa-map-marker-alt"></i> ${nearest.address}</p>
                                <p class="mb-4 small text-muted">${nearest.description || 'No description available.'}</p>
                                <div class="d-flex flex-wrap gap-2">
                                    <a href="<?php echo $base_url; ?>/masjids/masjid-details.php?id=${nearest.id}" class="btn btn-primary-custom btn-sm">
                                        View Full Profile <i class="fas fa-chevron-right ms-1"></i>
                                    </a>
                                    <a href="${GPS.buildDirectionsUrl(nearest.latitude, nearest.longitude, pos.lat, pos.lng)}" target="_blank" class="btn btn-gold btn-sm" data-directions-link="true" data-dest-lat="${nearest.latitude}" data-dest-lng="${nearest.longitude}">
                                        <i class="fas fa-location-arrow me-1"></i> Get Directions
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="p-3 bg-light rounded-4">
                                    <h6 class="text-success fw-bold mb-3 border-bottom pb-2"><i class="fas fa-clock"></i> Today's Prayer Timings</h6>
                                    <table class="table table-sm table-borderless mb-0">
                                        <tr><td><strong>Fajr</strong></td><td class="text-end">${formattedFajr}</td></tr>
                                        <tr><td><strong>Zuhr</strong></td><td class="text-end">${formattedZuhr}</td></tr>
                                        <tr><td><strong>Asr</strong></td><td class="text-end">${formattedAsr}</td></tr>
                                        <tr><td><strong>Maghrib</strong></td><td class="text-end">${formattedMaghrib}</td></tr>
                                        <tr><td><strong>Isha</strong></td><td class="text-end">${formattedIsha}</td></tr>
                                        <tr class="table-success border-top"><td><strong>Juma</strong></td><td class="text-end"><strong>${formatTime12h(nearest.juma_time)}</strong></td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                // Update Countdown Timer to point to the nearest masjid's next prayer
                PrayerCountdown.start(nearest, "prayerCountdown");
            }
        }).catch(err => {
            btnGPS.disabled = false;
            btnGPS.querySelector('span').textContent = 'Locate Nearest Masjid';
            gpsStatus.innerHTML = `<i class="fas fa-exclamation-triangle"></i> GPS Access Denied or Timed Out. Showing default city center.`;
            gpsStatus.className = 'mt-2 small text-danger';
        });
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
