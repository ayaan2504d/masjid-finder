<?php
/**
 * Juma & Eid Timings Directory — Masjid Locator & Namaz Timings System
 * 
 * Features:
 * - Friday khutbah timings grid
 * - Eid prayer timings grid
 * - Static announcement cards for updates/notices
 * - Clickable details per masjid
 */

$page_title = 'Juma & Eid Timings';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$all_masjids = getAllMasjids($conn);
?>

<!-- ═══════════ BREADCRUMB SECTION ═══════════ -->
<section class="breadcrumb-section">
    <div class="container">
        <h2 class="breadcrumb-title">Juma & Eid Timings</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Special Prayer timings</li>
            </ol>
        </nav>
    </div>
</section>

<!-- ═══════════ MAIN CONTENT ═══════════ -->
<div class="container my-5 animate-fadeInUp">
    <!-- Header Block -->
    <div class="text-center mb-5">
        <h3 class="fw-bold text-success"><i class="fas fa-star-and-crescent"></i> Friday (Juma) & Eid Prayers</h3>
        <p class="text-secondary max-width-600 mx-auto">Browse special prayer sermon details, timings, and other community congregation updates for mosques around Karachi.</p>
    </div>

    <!-- Juma Timings Grid -->
    <h4 class="section-title mb-4">🕌 Friday (Juma) Khutbah timings</h4>
    <div class="row g-4 mb-5">
        <?php if (!empty($all_masjids)): ?>
            <?php foreach ($all_masjids as $m): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden masjid-card">
                        <div class="card-body">
                            <span class="badge badge-sect badge-<?php echo strtolower($m['sect']); ?> mb-2"><?php echo $m['sect']; ?></span>
                            <h5 class="fw-bold text-success mb-2"><?php echo sanitize($m['name']); ?></h5>
                            <p class="text-muted small mb-3 text-truncate"><i class="fas fa-map-marker-alt"></i> <?php echo sanitize($m['address']); ?></p>
                            
                            <div class="bg-light p-3 rounded-3 mb-2 text-center">
                                <span class="text-secondary small d-block">Juma Congregational Time</span>
                                <h3 class="fw-bold text-primary mb-0"><?php echo formatTime12h($m['juma_time']); ?></h3>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-0 p-3 pt-0 text-center">
                            <a href="<?php echo $base_url; ?>/timings/juma-eid-details.php?id=<?php echo $m['id']; ?>" class="btn btn-outline-custom btn-sm w-100">
                                View Full Special Details <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center text-muted">No masjids found.</div>
        <?php endif; ?>
    </div>

    <!-- Eid Timings Grid -->
    <h4 class="section-title mb-4">🌙 Eid-ul-Fitr & Eid-ul-Adha Timings</h4>
    <div class="row g-4 mb-5">
        <?php if (!empty($all_masjids)): ?>
            <?php foreach ($all_masjids as $m): ?>
                <?php if ($m['eid_time']): ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden masjid-card" style="border-left: 4px solid var(--gold) !important;">
                            <div class="card-body">
                                <span class="badge badge-sect bg-warning text-dark mb-2"><?php echo $m['sect']; ?></span>
                                <h5 class="fw-bold text-success mb-2"><?php echo sanitize($m['name']); ?></h5>
                                <p class="text-muted small mb-3 text-truncate"><i class="fas fa-map-marker-alt"></i> <?php echo sanitize($m['address']); ?></p>
                                
                                <div class="bg-light p-3 rounded-3 mb-2 text-center">
                                    <span class="text-secondary small d-block">Eid Jama'at Time</span>
                                    <h3 class="fw-bold text-success mb-0"><?php echo formatTime12h($m['eid_time']); ?></h3>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-0 p-3 pt-0 text-center">
                                <a href="<?php echo $base_url; ?>/timings/juma-eid-details.php?id=<?php echo $m['id']; ?>" class="btn btn-outline-custom btn-sm w-100">
                                    View Full Special Details <i class="fas fa-arrow-right ms-1"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12 text-center text-muted">No Eid prayer timings announced yet.</div>
        <?php endif; ?>
    </div>

    <!-- Static Announcements / Notices Section -->
    <h4 class="section-title mb-4">📢 Special Notices & Announcements</h4>
    <div class="row g-4">
        <div class="col-md-6 col-lg-4">
            <div class="announcement-card h-100">
                <span class="badge bg-danger mb-2"><i class="fas fa-bullhorn"></i> URGENT NOTICE</span>
                <h5 class="fw-bold text-success">Baitul Mukarram Juma Khutbah</h5>
                <p class="text-secondary small mb-0">Due to massive congregations, please arrive at least 30 minutes before the 01:30 PM Juma sermon to ensure hassle-free entry and parking.</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="announcement-card h-100" style="border-left-color: var(--gold) !important;">
                <span class="badge bg-warning text-dark mb-2"><i class="fas fa-calendar-check"></i> EID ANNOUNCEMENT</span>
                <h5 class="fw-bold text-success">Tooba Masjid DHA (Gol Masjid)</h5>
                <p class="text-secondary small mb-0">Eid prayer timings are scheduled for 07:00 AM sharp. Multiple parking gates will be open. Shuttles are available from the main commercial street.</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-4">
            <div class="announcement-card h-100" style="border-left-color: #1E88E5 !important;">
                <span class="badge bg-primary mb-2"><i class="fas fa-info-circle"></i> EDUCATION CLASS</span>
                <h5 class="fw-bold text-success">Jamia Banuri Town Classes</h5>
                <p class="text-secondary small mb-0">Daily Quran and Hifz classes for kids run between Asr and Maghrib. Registrations are currently open for the summer batch.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
