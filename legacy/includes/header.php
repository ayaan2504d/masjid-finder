<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($base_url)) $base_url = '/Masjid';
if (!isset($page_title)) $page_title = 'Masjid Locator';

// Detect current page for active nav
$current_page = basename($_SERVER['PHP_SELF'], '.php');
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Find the nearest masjid, view prayer timings, and get directions. Masjid Locator helps you discover mosques near your location.">
    <meta name="keywords" content="masjid, mosque, prayer times, namaz, salah, GPS, locator, nearby mosque">
    <title><?php echo sanitize($page_title); ?> — Masjid Locator</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Leaflet.js -->
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
    <link href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" rel="stylesheet">
    <link href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo $base_url; ?>/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <!-- ═══════════ NAVBAR ═══════════ -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="<?php echo $base_url; ?>/">
                <i class="fas fa-mosque me-2"></i>
                <span class="brand-text">Masjid<span class="brand-highlight">Locator</span></span>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarMain">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_dir == 'Masjid' && $current_page == 'index') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>/">
                            <i class="fas fa-home me-1"></i> Home
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_dir == 'masjids') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>/masjids/">
                            <i class="fas fa-mosque me-1"></i> Masajid
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_dir == 'map') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>/map/">
                            <i class="fas fa-map-marked-alt me-1"></i> Map
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_dir == 'timings' && $current_page != 'juma-eid') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>/timings/">
                            <i class="fas fa-clock me-1"></i> Timings
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'juma-eid' || $current_page == 'juma-eid-details') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>/timings/juma-eid.php">
                            <i class="fas fa-star-and-crescent me-1"></i> Juma & Eid
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_dir == 'about') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>/about/">
                            <i class="fas fa-info-circle me-1"></i> About Us
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_dir == 'contact') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>/contact/">
                            <i class="fas fa-envelope me-1"></i> Contact
                        </a>
                    </li>
                    <li class="nav-item ms-lg-2">
                        <a class="nav-link nav-admin-btn <?php echo ($current_dir == 'admin') ? 'active' : ''; ?>" href="<?php echo $base_url; ?>/admin/">
                            <i class="fas fa-cog me-1"></i> Admin
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Message -->
    <?php
    $flash = getFlashMessage();
    if ($flash): ?>
    <div class="container mt-3">
        <div class="alert alert-<?php echo $flash['type']; ?> alert-dismissible fade show" role="alert">
            <?php echo $flash['message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main Content Wrapper -->
    <main>
