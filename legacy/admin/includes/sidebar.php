<?php
/**
 * Admin Sidebar Include — Masjid Locator & Namaz Timings System
 * Opens the admin layout wrapper and outputs sidebar navigation.
 */

if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../includes/functions.php';

if (!isset($admin_page)) $admin_page = 'dashboard';
if (!isset($page_title)) $page_title = 'Admin Control Panel';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitize($page_title); ?> — Masjid Locator Admin</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <!-- Leaflet.js -->
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
    <!-- Admin CSS -->
    <link href="<?php echo $base_url; ?>/admin/assets/css/admin.css" rel="stylesheet">
</head>
<body class="admin-body">

<div class="admin-wrapper">
    <!-- ═══════════ SIDEBAR ═══════════ -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand">
            <i class="fas fa-mosque me-2"></i>
            <span>Masjid<span class="highlight">Admin</span></span>
        </div>
        <nav class="sidebar-nav">
            <a href="<?php echo $base_url; ?>/admin/index.php" class="<?php echo $admin_page === 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-chart-line fa-fw"></i> Dashboard
            </a>
            <a href="<?php echo $base_url; ?>/admin/masjids.php" class="<?php echo $admin_page === 'masjids' ? 'active' : ''; ?>">
                <i class="fas fa-mosque fa-fw"></i> Manage Masajid
            </a>
            <a href="<?php echo $base_url; ?>/admin/add-masjid.php" class="<?php echo $admin_page === 'add-masjid' ? 'active' : ''; ?>">
                <i class="fas fa-plus-circle fa-fw"></i> Add New Masjid
            </a>
            <a href="<?php echo $base_url; ?>/admin/timings.php" class="<?php echo $admin_page === 'timings' ? 'active' : ''; ?>">
                <i class="fas fa-clock fa-fw"></i> Update Timings
            </a>
            <a href="<?php echo $base_url; ?>/admin/settings.php" class="<?php echo $admin_page === 'settings' ? 'active' : ''; ?>">
                <i class="fas fa-sliders-h fa-fw"></i> System Settings
            </a>
        </nav>
        <div class="sidebar-footer">
            <a href="<?php echo $base_url; ?>/" target="_blank">
                <i class="fas fa-external-link-alt me-1"></i> View Main Site
            </a>
        </div>
    </aside>

    <!-- ═══════════ MAIN CONTENT SECTION ═══════════ -->
    <div class="admin-main">
        <header class="admin-header">
            <div class="d-flex align-items-center gap-3">
                <button class="sidebar-toggle-btn" id="sidebarToggle" title="Toggle Sidebar">
                    <i class="fas fa-bars"></i>
                </button>
                <h4 class="mb-0 fw-bold"><?php echo sanitize($page_title); ?></h4>
            </div>
            <div class="admin-user-info small text-secondary">
                <i class="fas fa-user-shield me-1"></i> Administrator
            </div>
        </header>
        
        <div class="admin-content">
            <!-- Flash Message display inside Admin -->
            <?php
            $flash = getFlashMessage();
            if ($flash): ?>
            <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show" role="alert">
                <?php echo $flash['message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
