<?php
/**
 * Fullscreen Map — Masjid Locator & Namaz Timings System
 * 
 * Features:
 * - Full screen height layout
 * - Floating Leaflet.js interactive map
 * - User location marker (pulsing)
 * - Mosque markers (clustered)
 * - Proximity / radius filter (1km, 5km, 10km, all)
 * - Sidebar list of masjids with click-to-pan map feature
 */

$page_title = 'Interactive Map';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$all_masjids = getAllMasjids($conn);
$all_masjids_json = getMasjidsJson($conn);
?>

<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Sidebar Panel: Masjid List -->
        <div class="col-lg-3 col-md-4 map-sidebar d-flex flex-column" id="mapSidebar">
            <div class="sidebar-header">
                <h5 class="fw-bold mb-1"><i class="fas fa-mosque me-1"></i> Nearby Masajid</h5>
                <p class="small mb-0 text-white-50">Select a masjid to locate it on the map.</p>
            </div>
            
            <!-- Filters inside Sidebar -->
            <div class="p-3 border-bottom bg-light">
                <!-- Search bar -->
                <div class="mb-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                        <input type="text" id="mapSearch" class="form-control border-start-0" placeholder="Filter by name...">
                    </div>
                </div>

                <!-- Radius Filter -->
                <div class="mb-2">
                    <label class="form-label small fw-bold text-success mb-1">Search Radius</label>
                    <select id="mapRadiusSelect" class="form-select form-select-sm">
                        <option value="all">Show All Masajid</option>
                        <option value="1">Within 1 KM</option>
                        <option value="5">Within 5 KM</option>
                        <option value="10">Within 10 KM</option>
                    </select>
                </div>

                <!-- Sect Filter -->
                <div class="d-flex gap-1 mt-2">
                    <button class="btn btn-sm btn-outline-success flex-grow-1 py-1 active" id="btnSectAll" onclick="updateMapSectFilter('all')">All</button>
                    <button class="btn btn-sm btn-outline-success flex-grow-1 py-1" id="btnSectSunni" onclick="updateMapSectFilter('Sunni')">Sunni</button>
                    <button class="btn btn-sm btn-outline-success flex-grow-1 py-1" id="btnSectShia" onclick="updateMapSectFilter('Shia')">Shia</button>
                </div>
            </div>

            <!-- Scrollable Masjid List -->
            <div class="flex-grow-1 overflow-y-auto" id="sidebarList">
                <?php if (!empty($all_masjids)): ?>
                    <?php foreach ($all_masjids as $m): ?>
                        <div class="masjid-item" data-id="<?php echo $m['id']; ?>" data-name="<?php echo strtolower(sanitize($m['name'])); ?>" data-sect="<?php echo $m['sect']; ?>" data-lat="<?php echo $m['latitude']; ?>" data-lng="<?php echo $m['longitude']; ?>">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <h6 class="fw-bold text-success mb-0 text-truncate" style="max-width: 170px;">
                                    <?php echo sanitize($m['name']); ?>
                                </h6>
                                <span class="badge badge-sect badge-<?php echo strtolower($m['sect']); ?> py-1" style="font-size: 0.65rem;">
                                    <?php echo $m['sect']; ?>
                                </span>
                            </div>
                            <p class="small text-muted mb-1 text-truncate-2"><?php echo sanitize($m['address']); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-light text-secondary border small distance-span d-none">
                                    Calculating...
                                </span>
                                <span class="small text-success fw-bold"><i class="fas fa-clock"></i> Fajr: <?php echo formatTime12h($m['fajr']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-4 text-center text-muted">
                        <i class="fas fa-mosque fa-2x mb-2"></i>
                        <p>No masjids in database.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Map Area -->
        <div class="col-lg-9 col-md-8 position-relative">
            <!-- Fullscreen Leaflet Map -->
            <div class="map-fullscreen" id="fullMap"></div>
        </div>
    </div>
</div>

<!-- ═══════════ SCRIPTS ═══════════ -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const masjids = <?php echo $all_masjids_json; ?>;
    const defaultLat = 24.8607;
    const defaultLng = 67.0011;
    let userCoords = null;
    let activeSect = 'all';

    // 1. Initialize Leaflet Map
    MapHelper.init('fullMap', defaultLat, defaultLng, 12);
    MapHelper.addMasjidMarkers(masjids);

    // 2. Handle sidebar click event to pan to masjid marker
    const listItems = document.querySelectorAll('.masjid-item');
    listItems.forEach(item => {
        item.addEventListener('click', () => {
            const id = parseInt(item.dataset.id);
            const lat = parseFloat(item.dataset.lat);
            const lng = parseFloat(item.dataset.lng);
            
            // Pan map
            MapHelper.map.setView([lat, lng], 15);

            // Open Marker Popup
            MapHelper.markers.eachLayer(marker => {
                // Leaflet marker cluster holds markers inside _childClusters or _layers
                // Let's zoom to and find the layer to open popup
                const popup = marker.getPopup();
                if (popup && popup.getContent().includes(`details.php?id=${id}`)) {
                    MapHelper.markers.zoomToShowLayer(marker, () => {
                        marker.openPopup();
                    });
                }
            });
        });
    });

    // 3. User Geolocation
    GPS.getLocation().then(pos => {
        userCoords = pos;
        
        // Add user pulsing marker & pan map
        MapHelper.addUserMarker(pos.lat, pos.lng);
        MapHelper.map.setView([pos.lat, pos.lng], 13);
        MapHelper.addMasjidMarkers(masjids, pos.lat, pos.lng);

        // Update list distances
        listItems.forEach(item => {
            const lat = parseFloat(item.dataset.lat);
            const lng = parseFloat(item.dataset.lng);
            const dist = haversineDistance(pos.lat, pos.lng, lat, lng);
            
            const distSpan = item.querySelector('.distance-span');
            distSpan.innerHTML = `<i class="fas fa-route"></i> ${dist} km`;
            distSpan.classList.remove('d-none');
            
            // Set distance as data attribute for sorting
            item.dataset.distance = dist;
        });

        // Sort sidebar list by distance ascending
        sortListByDistance();
    }).catch(err => {
        console.warn('GPS location permission denied or timed out:', err.message);
    });

    // 4. Search Filter
    const searchInput = document.getElementById('mapSearch');
    searchInput.addEventListener('input', () => {
        filterListAndMarkers();
    });

    // 5. Radius Filter
    const radiusSelect = document.getElementById('mapRadiusSelect');
    radiusSelect.addEventListener('change', () => {
        filterListAndMarkers();
    });

    // Filtering logic combining Search, Radius, and Sect
    function filterListAndMarkers() {
        const query = searchInput.value.toLowerCase().trim();
        const radius = radiusSelect.value;
        
        let filteredMasjids = masjids;

        // Apply sect filter
        if (activeSect !== 'all') {
            filteredMasjids = filteredMasjids.filter(m => m.sect === activeSect);
        }

        // Apply search query filter
        if (query !== '') {
            filteredMasjids = filteredMasjids.filter(m => m.name.toLowerCase().includes(query) || m.address.toLowerCase().includes(query));
        }

        // Apply radius filter
        if (radius !== 'all' && userCoords) {
            filteredMasjids = filteredMasjids.filter(m => {
                const dist = haversineDistance(userCoords.lat, userCoords.lng, m.latitude, m.longitude);
                return parseFloat(dist) <= parseFloat(radius);
            });
        }

        // Update markers on Map
        if (userCoords) {
            MapHelper.addMasjidMarkers(filteredMasjids, userCoords.lat, userCoords.lng);
        } else {
            MapHelper.addMasjidMarkers(filteredMasjids);
        }

        // Update sidebar items visibility
        listItems.forEach(item => {
            const id = parseInt(item.dataset.id);
            const isMatch = filteredMasjids.some(m => m.id === id);
            
            if (isMatch) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Sect Filter buttons update helper
    window.updateMapSectFilter = function(sect) {
        activeSect = sect;
        
        // Update button states
        document.getElementById('btnSectAll').classList.toggle('active', sect === 'all');
        document.getElementById('btnSectSunni').classList.toggle('active', sect === 'Sunni');
        document.getElementById('btnSectShia').classList.toggle('active', sect === 'Shia');

        filterListAndMarkers();
    };

    // Sort list items by distance
    function sortListByDistance() {
        const listContainer = document.getElementById('sidebarList');
        const items = Array.from(listItems);

        items.sort((a, b) => {
            const distA = parseFloat(a.dataset.distance) || Infinity;
            const distB = parseFloat(b.dataset.distance) || Infinity;
            return distA - distB;
        });

        // Append sorted items
        items.forEach(item => listContainer.appendChild(item));
    }
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
