<?php
/**
 * Admin Manage Masajid — Masjid Locator & Namaz Timings System
 * 
 * Features:
 * - Full list of all registered mosques
 * - Direct access links to Edit, Delete, and Update Timings
 * - Search filter for sorting
 */

$admin_page = 'masjids';
$page_title = 'Manage Masajid';
require_once __DIR__ . '/includes/sidebar.php';

$all_masjids = getAllMasjids($conn);
?>

<div class="row mb-4">
    <div class="col-md-6 col-sm-12">
        <p class="text-secondary small mb-0">Total of <?php echo count($all_masjids); ?> masjids registered in database.</p>
    </div>
    <div class="col-md-6 col-sm-12 text-md-end mt-2 mt-md-0">
        <a href="<?php echo $base_url; ?>/admin/add-masjid.php" class="btn btn-success btn-sm px-3 rounded-pill fw-bold">
            <i class="fas fa-plus-circle me-1"></i> Add New Masjid
        </a>
    </div>
</div>

<!-- Masajid Table Grid -->
<div class="admin-table-card">
    <div class="card-header"><i class="fas fa-mosque me-1"></i> Masjid Directory Directory</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table admin-table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Sect</th>
                        <th>Address</th>
                        <th>Phone</th>
                        <th>Fajr</th>
                        <th>Zuhr</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($all_masjids)): ?>
                        <?php foreach ($all_masjids as $m): ?>
                            <tr>
                                <td><?php echo $m['id']; ?></td>
                                <td class="fw-bold text-success"><?php echo sanitize($m['name']); ?></td>
                                <td>
                                    <span class="badge badge-sect badge-<?php echo strtolower($m['sect']); ?> py-1" style="font-size:0.65rem;">
                                        <?php echo $m['sect']; ?>
                                    </span>
                                </td>
                                <td class="text-truncate" style="max-width: 200px;" title="<?php echo sanitize($m['address']); ?>"><?php echo sanitize($m['address']); ?></td>
                                <td><?php echo sanitize($m['phone'] ?: 'N/A'); ?></td>
                                <td><?php echo formatTime12h($m['fajr']); ?></td>
                                <td><?php echo formatTime12h($m['zuhr']); ?></td>
                                <td>
                                    <div class="d-flex gap-1 align-items-center">
                                        <a href="<?php echo $base_url; ?>/admin/edit-masjid.php?id=<?php echo $m['id']; ?>" class="btn-admin-action btn-edit" title="Edit Profile">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="<?php echo $base_url; ?>/admin/timings.php?masjid_id=<?php echo $m['id']; ?>" class="btn-admin-action btn-edit text-success bg-success-subtle border-success-subtle" title="Edit Timings">
                                            <i class="fas fa-clock"></i> Timings
                                        </a>
                                        <!-- Form-based delete to prevent CSRF -->
                                        <form action="<?php echo $base_url; ?>/admin/delete-masjid.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this masjid? This action cannot be undone.');" class="d-inline">
                                            <input type="hidden" name="id" value="<?php echo $m['id']; ?>">
                                            <button type="submit" class="btn-admin-action btn-delete border-0" title="Delete">
                                                <i class="fas fa-trash-alt"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-mosque fa-2x mb-2"></i>
                                <p class="mb-0">No masjids found. Click "Add New Masjid" to add one.</p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/admin-footer.php'; ?>
