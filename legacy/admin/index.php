<?php
/**
 * Admin Dashboard — Masjid Locator & Namaz Timings System
 * 
 * Features:
 * - Summary statistics widgets (Masjids, Sect splits, Messages)
 * - Quick-view table of recently added masjids
 * - Quick-view table of recent contact inquiries
 */

$admin_page = 'dashboard';
$page_title = 'Admin Dashboard';
require_once __DIR__ . '/includes/sidebar.php';

// Get counts
$total_masjids = getTotalMasjids($conn);
$sunni_count = getCountBySect($conn, 'Sunni');
$shia_count = getCountBySect($conn, 'Shia');
$unread_messages = getUnreadContacts($conn);

// Fetch recent items
$recent_masjids = getRecentMasjids($conn, 5);
$recent_messages = getRecentContacts($conn, 5);
?>

<!-- ═══════════ STATISTICS ROW ═══════════ -->
<div class="row g-4 mb-4">
    <!-- Stat 1: Total Masjids -->
    <div class="col-xl-3 col-sm-6">
        <div class="admin-stat-card">
            <div class="admin-stat-info">
                <h6>Total Masajid</h6>
                <h3><?php echo $total_masjids; ?></h3>
            </div>
            <div class="admin-stat-icon stat-green">
                <i class="fas fa-mosque"></i>
            </div>
        </div>
    </div>
    <!-- Stat 2: Sunni Split -->
    <div class="col-xl-3 col-sm-6">
        <div class="admin-stat-card">
            <div class="admin-stat-info">
                <h6>Sunni Sect</h6>
                <h3><?php echo $sunni_count; ?></h3>
            </div>
            <div class="admin-stat-icon stat-teal">
                <i class="fas fa-users"></i>
            </div>
        </div>
    </div>
    <!-- Stat 3: Shia Split -->
    <div class="col-xl-3 col-sm-6">
        <div class="admin-stat-card">
            <div class="admin-stat-info">
                <h6>Shia Sect</h6>
                <h3><?php echo $shia_count; ?></h3>
            </div>
            <div class="admin-stat-icon stat-blue">
                <i class="fas fa-users-cog"></i>
            </div>
        </div>
    </div>
    <!-- Stat 4: Unread Messages -->
    <div class="col-xl-3 col-sm-6">
        <div class="admin-stat-card">
            <div class="admin-stat-info">
                <h6>Unread Messages</h6>
                <h3><?php echo $unread_messages; ?></h3>
            </div>
            <div class="admin-stat-icon stat-orange">
                <i class="fas fa-envelope-open-text"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Recent Masjids -->
    <div class="col-lg-7">
        <div class="admin-table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-mosque me-1"></i> Recently Added Masajid</span>
                <a href="<?php echo $base_url; ?>/admin/masjids.php" class="btn btn-xs btn-outline-success py-1 px-2 small" style="font-size:0.75rem;">Manage All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table admin-table mb-0">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Sect</th>
                                <th>Address</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_masjids)): ?>
                                <?php foreach ($recent_masjids as $m): ?>
                                    <tr>
                                        <td class="fw-bold text-success"><?php echo sanitize($m['name']); ?></td>
                                        <td>
                                            <span class="badge badge-sect badge-<?php echo strtolower($m['sect']); ?> py-1" style="font-size:0.65rem;">
                                                <?php echo $m['sect']; ?>
                                            </span>
                                        </td>
                                        <td class="text-truncate" style="max-width: 180px;"><?php echo sanitize($m['address']); ?></td>
                                        <td>
                                            <div class="d-flex gap-1">
                                                <a href="<?php echo $base_url; ?>/admin/edit-masjid.php?id=<?php echo $m['id']; ?>" class="btn-admin-action btn-edit" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No masjids added yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Recent Messages -->
    <div class="col-lg-5">
        <div class="admin-table-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fas fa-envelope me-1"></i> Contact Enquiries</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table admin-table mb-0">
                        <thead>
                            <tr>
                                <th>Sender</th>
                                <th>Subject</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($recent_messages)): ?>
                                <?php foreach ($recent_messages as $msg): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?php echo sanitize($msg['name']); ?></div>
                                            <div class="small text-muted"><?php echo sanitize($msg['email']); ?></div>
                                        </td>
                                        <td class="text-truncate" style="max-width: 150px;"><?php echo sanitize($msg['subject']); ?></td>
                                        <td>
                                            <?php if ($msg['is_read']): ?>
                                                <span class="badge bg-secondary">Read</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">New</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">No messages received yet.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
