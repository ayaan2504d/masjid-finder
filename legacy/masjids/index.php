<?php
/**
 * Masajid Directory Listing — Masjid Locator & Namaz Timings System
 * 
 * Features:
 * - Search by name or address
 * - Filter by sect (Sunni/Shia)
 * - Grid card listing
 * - Client-side dynamic distance calculation if GPS is active
 */

$page_title = 'Explore Masajid';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

// Retrieve filter inputs
$search = isset($_GET['search']) ? sanitize($_GET['search']) : '';
$sect = isset($_GET['sect']) ? sanitize($_GET['sect']) : 'all';

// Build SQL query based on filters
$sql = getMasjidTimingSelectSql() . " WHERE 1=1";
if ($search !== '') {
    $escaped_search = $conn->real_escape_string($search);
    $sql .= " AND (m.name LIKE '%" . $escaped_search . "%' OR m.address LIKE '%" . $escaped_search . "%')";
}
if ($sect !== 'all') {
    $sql .= " AND m.sect = '" . $conn->real_escape_string($sect) . "'";
}
$sql .= " ORDER BY m.name ASC";

$result = $conn->query($sql);
$masjids = [];
while ($row = $result->fetch_assoc()) {
    $masjids[] = $row;
}
?>

<!-- ═══════════ BREADCRUMB SECTION ═══════════ -->
<section class="breadcrumb-section">
    <div class="container">
        <h2 class="breadcrumb-title">Explore Masajid</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Masajid Directory</li>
            </ol>
        </nav>
    </div>
</section>

<!-- ═══════════ DIRECTORY CONTENT ═══════════ -->
<div class="container my-5 animate-fadeInUp">
    <!-- Filter Toolbar -->
    <div class="row mb-5 g-3">
        <!-- Search bar -->
        <div class="col-lg-6">
            <form action="" method="GET" class="search-box">
                <?php if ($sect !== 'all'): ?>
                    <input type="hidden" name="sect" value="<?php echo $sect; ?>">
                <?php endif; ?>
                <i class="fas fa-search search-icon"></i>
                <input type="text" name="search" class="form-control" placeholder="Search by name, address or keyword..." value="<?php echo $search; ?>">
            </form>
        </div>
        <!-- Sect Filters -->
        <div class="col-lg-6 text-lg-end d-flex align-items-center justify-content-lg-end gap-2">
            <a href="?search=<?php echo urlencode($search); ?>&sect=all" class="sect-filter-btn <?php echo $sect === 'all' ? 'active' : ''; ?>">All Sects</a>
            <a href="?search=<?php echo urlencode($search); ?>&sect=Sunni" class="sect-filter-btn <?php echo $sect === 'Sunni' ? 'active' : ''; ?>">Sunni</a>
            <a href="?search=<?php echo urlencode($search); ?>&sect=Shia" class="sect-filter-btn <?php echo $sect === 'Shia' ? 'active' : ''; ?>">Shia</a>
        </div>
    </div>

    <!-- Active Filters Notification -->
    <?php if ($search !== '' || $sect !== 'all'): ?>
        <div class="mb-4 d-flex align-items-center gap-2">
            <span class="text-secondary">Active filters:</span>
            <?php if ($search !== ''): ?>
                <span class="badge bg-success px-3 py-2">Search: "<?php echo $search; ?>" 
                    <a href="?sect=<?php echo $sect; ?>" class="text-white ms-2 text-decoration-none"><i class="fas fa-times"></i></a>
                </span>
            <?php endif; ?>
            <?php if ($sect !== 'all'): ?>
                <span class="badge bg-success px-3 py-2">Sect: <?php echo $sect; ?> 
                    <a href="?search=<?php echo urlencode($search); ?>" class="text-white ms-2 text-decoration-none"><i class="fas fa-times"></i></a>
                </span>
            <?php endif; ?>
            <a href="?" class="btn btn-link text-success btn-sm text-decoration-none fw-bold ms-2">Reset Filters</a>
        </div>
    <?php endif; ?>

    <!-- Masajid Grid -->
    <div class="row g-4">
        <?php if (!empty($masjids)): ?>
            <?php foreach ($masjids as $m): ?>
                <div class="col-lg-4 col-md-6" data-sect="<?php echo $m['sect']; ?>">
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
                            <p class="card-text mb-4 text-truncate-2">
                                <i class="fas fa-map-marker-alt text-success me-1"></i> <?php echo sanitize($m['address']); ?>
                            </p>
                            
                            <!-- Detailed Timings Quick View -->
                            <div class="row g-1 text-center py-2 px-1 bg-light rounded mb-3 small">
                                <div class="col"><strong>Fajr</strong><br><?php echo formatTime12h($m['fajr']); ?></div>
                                <div class="col"><strong>Zuhr</strong><br><?php echo formatTime12h($m['zuhr']); ?></div>
                                <div class="col"><strong>Asr</strong><br><?php echo formatTime12h($m['asr']); ?></div>
                                <div class="col"><strong>Maghrib</strong><br><?php echo formatTime12h($m['maghrib']); ?></div>
                                <div class="col"><strong>Isha</strong><br><?php echo formatTime12h($m['isha']); ?></div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="<?php echo $base_url; ?>/masjids/masjid-details.php?id=<?php echo $m['id']; ?>" class="btn btn-outline-custom btn-sm w-100">
                                View Details & Map <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center py-5">
                <i class="fas fa-search fa-3x text-muted mb-3"></i>
                <h4 class="text-secondary fw-bold">No Masajid Found</h4>
                <p class="text-muted">We couldn't find any mosques matching your filters. Try checking spelling or changing filters.</p>
                <a href="?" class="btn btn-primary-custom mt-3">Reset Search</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- ═══════════ SCRIPTS ═══════════ -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Check if GPS was acquired on homepage or fetch now
    GPS.getLocation().then(pos => {
        const badges = document.querySelectorAll('.distance-badge');
        badges.forEach(badge => {
            const lat = parseFloat(badge.dataset.lat);
            const lng = parseFloat(badge.dataset.lng);
            const dist = haversineDistance(pos.lat, pos.lng, lat, lng);
            badge.innerHTML = `<i class="fas fa-route"></i> ${dist} km`;
            badge.classList.remove('d-none');
        });
    }).catch(err => {
        console.warn('GPS location not active on listing page:', err.message);
    });
});
</script>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
