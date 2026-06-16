<?php
/**
 * Masjid Detail Profile Page — Masjid Locator & Namaz Timings System
 * 
 * Features:
 * - Detailed prayer timings
 * - Embedded Leaflet map showing location pin
 * - "Get Directions" navigation button
 * - Favorite button (persisted in localStorage)
 * - Distance calculation from user
 * - Nearby mosques slider
 */

require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$masjid = getMasjidById($conn, $id);

if (!$masjid) {
    setFlashMessage('danger', 'The selected masjid was not found in our system.');
    header('Location: ' . $base_url . '/masjids/');
    exit;
}

$page_title = $masjid['name'];
require_once __DIR__ . '/../includes/header.php';

// Calculate related / nearby mosques using PHP
// We get other mosques and sort by proximity if we know default lat/lng
$default_lat = floatval(getSetting($conn, 'default_lat', '31.5204'));
$default_lng = floatval(getSetting($conn, 'default_lng', '74.3587'));

$all_masjids = getAllMasjids($conn);
$nearby_masjids = [];
foreach ($all_masjids as $m) {
    if ($m['id'] === $masjid['id']) continue;
    $dist = getDistanceHaversine($masjid['latitude'], $masjid['longitude'], $m['latitude'], $m['longitude']);
    $m['proximity'] = $dist;
    $nearby_masjids[] = $m;
}

// Sort by proximity ascending
usort($nearby_masjids, function($a, $b) {
    return $a['proximity'] <=> $b['proximity'];
});

// Limit to 5 nearby
$nearby_masjids = array_slice($nearby_masjids, 0, 5);
$next_prayer = getNextPrayer($masjid);
?>

<!-- ═══════════ BREADCRUMB SECTION ═══════════ -->
<section class="breadcrumb-section">
    <div class="container">
        <h2 class="breadcrumb-title"><?php echo sanitize($masjid['name']); ?></h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/">Home</a></li>
                <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/masjids/">Masajid</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?php echo sanitize($masjid['name']); ?> Details</li>
            </ol>
        </nav>
    </div>
</section>

<!-- ═══════════ DETAIL CONTENT ═══════════ -->
<div class="container my-5 animate-fadeInUp">
    <!-- Profile Header Wrapper -->
    <div class="profile-header mb-5">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div>
                <span class="badge badge-sect bg-white text-success mb-2"><?php echo $masjid['sect']; ?> Sect</span>
                <h2 class="profile-name text-white fw-bold mb-2"><?php echo sanitize($masjid['name']); ?></h2>
                <p class="profile-address text-white-50 mb-0">
                    <i class="fas fa-map-marker-alt me-1"></i> <?php echo sanitize($masjid['address']); ?>
                </p>
            </div>
            <div>
                <button id="btnFav" class="btn btn-outline-danger bg-white border-0 px-4 py-2 fw-bold rounded-pill text-danger">
                    <i class="far fa-heart"></i> Add to Favorites
                </button>
            </div>
        </div>
    </div>

    <!-- Main Detail Grid -->
    <div class="row g-4 mb-5">
        <!-- Left Column: Timings & Description -->
        <div class="col-lg-7">
            <!-- Timings Table Card -->
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                <div class="bg-success bg-gradient text-white p-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-clock me-2"></i> Namaz Timings</h5>
                    <span class="badge bg-white text-success fw-bold">Daily Schedule</span>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover prayer-table mb-0">
                        <thead>
                            <tr>
                                <th>Prayer</th>
                                <th>Azaan / Jama'at Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="<?php echo $next_prayer['name'] === 'Fajr' ? 'current-prayer' : ''; ?>">
                                <td>
                                    <span class="prayer-icon prayer-icon-fajr"><i class="fas fa-feather-alt"></i></span> 
                                    <strong>Fajr</strong>
                                </td>
                                <td><?php echo formatTime12h($masjid['fajr']); ?></td>
                            </tr>
                            <tr class="<?php echo $next_prayer['name'] === 'Zuhr' ? 'current-prayer' : ''; ?>">
                                <td>
                                    <span class="prayer-icon prayer-icon-zuhr"><i class="fas fa-sun"></i></span> 
                                    <strong>Zuhr</strong>
                                </td>
                                <td><?php echo formatTime12h($masjid['zuhr']); ?></td>
                            </tr>
                            <tr class="<?php echo $next_prayer['name'] === 'Asr' ? 'current-prayer' : ''; ?>">
                                <td>
                                    <span class="prayer-icon prayer-icon-asr"><i class="fas fa-cloud-sun"></i></span> 
                                    <strong>Asr</strong>
                                </td>
                                <td><?php echo formatTime12h($masjid['asr']); ?></td>
                            </tr>
                            <tr class="<?php echo $next_prayer['name'] === 'Maghrib' ? 'current-prayer' : ''; ?>">
                                <td>
                                    <span class="prayer-icon prayer-icon-maghrib"><i class="fas fa-cloud-moon"></i></span> 
                                    <strong>Maghrib</strong>
                                </td>
                                <td><?php echo formatTime12h($masjid['maghrib']); ?></td>
                            </tr>
                            <tr class="<?php echo $next_prayer['name'] === 'Isha' ? 'current-prayer' : ''; ?>">
                                <td>
                                    <span class="prayer-icon prayer-icon-isha"><i class="fas fa-moon"></i></span> 
                                    <strong>Isha</strong>
                                </td>
                                <td><?php echo formatTime12h($masjid['isha']); ?></td>
                            </tr>
                            <tr class="table-success">
                                <td>
                                    <span class="prayer-icon bg-success text-white"><i class="fas fa-users"></i></span> 
                                    <strong>Juma prayer</strong>
                                </td>
                                <td><strong><?php echo formatTime12h($masjid['juma_time']); ?></strong></td>
                            </tr>
                            <?php if ($masjid['eid_time']): ?>
                                <tr class="table-warning">
                                    <td>
                                        <span class="prayer-icon bg-warning text-dark"><i class="fas fa-star-and-crescent"></i></span> 
                                        <strong>Eid prayer</strong>
                                    </td>
                                    <td><strong><?php echo formatTime12h($masjid['eid_time']); ?></strong></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Profile Info & Description -->
            <div class="card shadow-sm border-0 rounded-4 p-4">
                <h5 class="text-success fw-bold mb-3"><i class="fas fa-info-circle me-1"></i> About This Masjid</h5>
                <p class="text-secondary mb-4"><?php echo nl2br(sanitize($masjid['description'] ?: 'No description or announcements available for this mosque.')); ?></p>
                
                <h5 class="text-success fw-bold mb-3 border-top pt-3"><i class="fas fa-address-book me-1"></i> Contact Information</h5>
                <div class="detail-info-item">
                    <span class="info-label">Address:</span>
                    <span class="info-value"><?php echo sanitize($masjid['address']); ?></span>
                </div>
                <div class="detail-info-item">
                    <span class="info-label">Phone:</span>
                    <span class="info-value"><?php echo sanitize($masjid['phone'] ?: 'N/A'); ?></span>
                </div>
                <div class="detail-info-item">
                    <span class="info-label">Sect/School:</span>
                    <span class="info-value"><?php echo sanitize($masjid['sect']); ?></span>
                </div>
            </div>
        </div>

        <!-- Right Column: Map, Distance, Get Directions -->
        <div class="col-lg-5">
            <!-- Map Card -->
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                <div class="map-embed" id="detailsMap" style="height: 350px;"></div>
                <div class="card-body">
                    <div id="distanceStatus" class="alert alert-info py-2 px-3 mb-3 d-none">
                        <i class="fas fa-location-crosshairs me-1 animate-pulse"></i> 
                        <span id="distanceText">Calculating distance...</span>
                    </div>

                    <div class="row g-2">
                        <div class="col-sm-6">
                            <a id="btnGetDirections" href="https://www.google.com/maps/search/?api=1&query=<?php echo $masjid['latitude']; ?>,<?php echo $masjid['longitude']; ?>" target="_blank" class="btn btn-gold w-100" data-directions-link="true" data-dest-lat="<?php echo $masjid['latitude']; ?>" data-dest-lng="<?php echo $masjid['longitude']; ?>">
                                <i class="fas fa-location-arrow me-1"></i> Get Directions
                            </a>
                        </div>
                        <div class="col-sm-6">
                            <a href="<?php echo $base_url; ?>/map/" class="btn btn-outline-custom w-100">
                                <i class="fas fa-map-marked-alt me-1"></i> View Full Map
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Nearby Mosques Scroll Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h4 class="section-title">Nearby Masajid</h4>
            <p class="section-subtitle">Other mosques located in close proximity</p>
        </div>
    </div>

    <div class="nearby-scroll pb-3">
        <?php foreach ($nearby_masjids as $n): ?>
            <div class="masjid-card" style="min-width: 300px; flex-shrink: 0;">
                <div class="card-img-top" style="height: 120px; font-size: 2rem;">
                    <i class="fas fa-mosque"></i>
                </div>
                <div class="card-body p-3">
                    <span class="badge badge-sect badge-<?php echo strtolower($n['sect']); ?> mb-2"><?php echo $n['sect']; ?></span>
                    <h6 class="fw-bold text-success mb-1 text-truncate"><?php echo sanitize($n['name']); ?></h6>
                    <p class="text-muted small mb-2 text-truncate"><i class="fas fa-map-marker-alt"></i> <?php echo sanitize($n['address']); ?></p>
                    <span class="badge bg-secondary"><i class="fas fa-route"></i> <?php echo round($n['proximity'], 2); ?> km away</span>
                </div>
                <div class="card-footer p-2 text-center bg-transparent">
                    <a href="<?php echo $base_url; ?>/masjids/masjid-details.php?id=<?php echo $n['id']; ?>" class="btn btn-sm btn-outline-custom w-100 py-1">
                        View Details
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ═══════════ SCRIPTS ═══════════ -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const masjid = {
        id: <?php echo $masjid['id']; ?>,
        name: "<?php echo addslashes($masjid['name']); ?>",
        address: "<?php echo addslashes($masjid['address']); ?>",
        sect: "<?php echo $masjid['sect']; ?>",
        latitude: <?php echo $masjid['latitude']; ?>,
        longitude: <?php echo $masjid['longitude']; ?>
    };

    // 1. Initialize Map
    MapHelper.init('detailsMap', masjid.latitude, masjid.longitude, 15);
    
    // Add Marker for this masjid
    const markerPopup = `
        <div class="map-popup text-center">
            <h6><i class="fas fa-mosque text-success"></i> ${masjid.name}</h6>
            <span class="badge bg-${masjid.sect === 'Sunni' ? 'success' : 'info'}">${masjid.sect}</span>
            <p class="small text-muted mb-0 mt-1">${masjid.address}</p>
        </div>
    `;
    L.marker([masjid.latitude, masjid.longitude], { icon: MapHelper.getMosqueIcon() })
        .bindPopup(markerPopup)
        .addTo(MapHelper.map)
        .openPopup();

    // 2. Favorite Toggle Button
    const btnFav = document.getElementById('btnFav');
    Favorites.updateButton(btnFav, masjid.id);

    btnFav.addEventListener('click', () => {
        const isFav = Favorites.toggle(masjid.id);
        Favorites.updateButton(btnFav, masjid.id);
    });

    // 3. Geolocation & Real-time Distance Calculation
    GPS.getLocation().then(pos => {
        // Add user marker
        MapHelper.addUserMarker(pos.lat, pos.lng);

        // Fit map bounds to show both user and masjid
        const bounds = L.latLngBounds([pos.lat, pos.lng], [masjid.latitude, masjid.longitude]);
        MapHelper.map.fitBounds(bounds, { padding: [50, 50] });

        // Update Distance Alert Badge
        const dist = haversineDistance(pos.lat, pos.lng, masjid.latitude, masjid.longitude);
        const directionsBtn = document.getElementById('btnGetDirections');
        if (directionsBtn) {
            directionsBtn.href = GPS.buildDirectionsUrl(masjid.latitude, masjid.longitude, pos.lat, pos.lng);
        }
        const distanceStatus = document.getElementById('distanceStatus');
        const distanceText = document.getElementById('distanceText');
        distanceText.innerHTML = `This masjid is <strong>${dist} km</strong> away from your current location.`;
        distanceStatus.classList.remove('d-none');
    }).catch(err => {
        console.warn('GPS location access denied or failed on details page:', err.message);
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
