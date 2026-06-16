<?php
/**
 * Timings Comparison Details — Masjid Locator & Namaz Timings System
 * 
 * Features:
 * - Table format comparing all registered masjids side by side
 * - Filter by sect (Sunni/Shia)
 * - Print friendly CSS formatting
 * - Easy comparison of daily prayer & Juma times
 */

$page_title = 'Timings Comparison';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

$sect = isset($_GET['sect']) ? sanitize($_GET['sect']) : 'all';

$masjids = ($sect !== 'all') ? getAllMasjids($conn, $sect) : getAllMasjids($conn);
?>

<!-- ═══════════ BREADCRUMB SECTION ═══════════ -->
<section class="breadcrumb-section">
    <div class="container">
        <h2 class="breadcrumb-title">All Masjid Timings</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/">Home</a></li>
                <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/timings/">Timings</a></li>
                <li class="breadcrumb-item active" aria-current="page">Comparison Table</li>
            </ol>
        </nav>
    </div>
</section>

<!-- ═══════════ COMPARISON CONTENT ═══════════ -->
<div class="container my-5 animate-fadeInUp">
    <!-- Filters & Actions Toolbar -->
    <div class="row mb-4 align-items-center g-3 no-print">
        <!-- Sect Filters -->
        <div class="col-md-6">
            <div class="d-flex align-items-center gap-2">
                <span class="text-secondary fw-bold">Filter Sect:</span>
                <a href="?sect=all" class="btn btn-sm btn-outline-success <?php echo $sect === 'all' ? 'active' : ''; ?>">All</a>
                <a href="?sect=Sunni" class="btn btn-sm btn-outline-success <?php echo $sect === 'Sunni' ? 'active' : ''; ?>">Sunni</a>
                <a href="?sect=Shia" class="btn btn-sm btn-outline-success <?php echo $sect === 'Shia' ? 'active' : ''; ?>">Shia</a>
            </div>
        </div>
        <!-- Print Button -->
        <div class="col-md-6 text-md-end">
            <button onclick="window.print()" class="btn btn-gold btn-sm px-4">
                <i class="fas fa-print me-1"></i> Print / Save PDF
            </button>
        </div>
    </div>

    <!-- Timings Comparison Card -->
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="bg-success bg-gradient text-white p-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0 fw-bold"><i class="fas fa-table me-2"></i> Timings Comparison Directory</h5>
            <span class="badge bg-white text-success fw-bold"><?php echo count($masjids); ?> Mosques Listed</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 text-center align-middle">
                    <thead class="table-success align-middle">
                        <tr>
                            <th class="text-start ps-4">Masjid Name</th>
                            <th>Sect</th>
                            <th>Fajr</th>
                            <th>Zuhr</th>
                            <th>Asr</th>
                            <th>Maghrib</th>
                            <th>Isha</th>
                            <th>Juma</th>
                            <th class="no-print">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($masjids)): ?>
                            <?php foreach ($masjids as $m): ?>
                                <tr>
                                    <td class="text-start ps-4 fw-bold text-success">
                                        <?php echo sanitize($m['name']); ?>
                                        <div class="small text-muted fw-normal d-block text-truncate" style="max-width: 200px;">
                                            <?php echo sanitize($m['address']); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-sect badge-<?php echo strtolower($m['sect']); ?> py-1" style="font-size: 0.7rem;">
                                            <?php echo $m['sect']; ?>
                                        </span>
                                    </td>
                                    <td><?php echo formatTime12h($m['fajr']); ?></td>
                                    <td><?php echo formatTime12h($m['zuhr']); ?></td>
                                    <td><?php echo formatTime12h($m['asr']); ?></td>
                                    <td><?php echo formatTime12h($m['maghrib']); ?></td>
                                    <td><?php echo formatTime12h($m['isha']); ?></td>
                                    <td class="fw-bold text-primary"><?php echo formatTime12h($m['juma_time']); ?></td>
                                    <td class="no-print">
                                        <a href="<?php echo $base_url; ?>/masjids/masjid-details.php?id=<?php echo $m['id']; ?>" class="btn btn-xs btn-outline-success py-1 px-2" style="font-size:0.75rem;">
                                            <i class="fas fa-eye"></i> View Profile
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                                    <p class="mb-0">No masjids match this sect criteria.</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    .card {
        border: none !important;
        box-shadow: none !important;
    }
    table {
        border-collapse: collapse !important;
        width: 100% !important;
    }
    th, td {
        border: 1px solid #ddd !important;
        padding: 8px !important;
        font-size: 0.85rem !important;
    }
}
</style>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
