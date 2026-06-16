<?php
/**
 * About Us Page — Masjid Locator & Namaz Timings System
 * 
 * Features:
 * - Mission statement
 * - 3-step visual guide on how it works
 * - Platform benefits (Responsive, Accurate, GPS, Sect splits)
 * - Navigation links to extended details
 */

$page_title = 'About Us';
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- ═══════════ BREADCRUMB SECTION ═══════════ -->
<section class="breadcrumb-section">
    <div class="container">
        <h2 class="breadcrumb-title">About Our Platform</h2>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo $base_url; ?>/">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">About Us</li>
            </ol>
        </nav>
    </div>  
</section>

<!-- ═══════════ ABOUT CONTENT ═══════════ -->
<div class="container my-5 animate-fadeInUp">
    <!-- Intro Block -->
    <div class="row align-items-center g-5 mb-5">
        <div class="col-lg-6">
            <h3 class="section-title">Serving the Ummah</h3>
            <p class="text-secondary mt-3">
                <strong>Masjid Locator</strong> is a modern, GPS-powered digital directory built to bridge the gap between Muslims and nearby places of worship. We understand how crucial it is to perform congregational prayers on time, especially when traveling or settling in a new area.
            </p>
            <p class="text-secondary">
                Our platform lists local mosques with accurate coordinate details, school of thought (Sunni/Shia), daily prayer timings, and Friday congregation details to ensure you never miss your salah.
            </p>
            <div class="mt-4">
                <a href="<?php echo $base_url; ?>/about/about-details.php" class="btn btn-primary-custom">
                    Explore Technical Specs & FAQs <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="p-4 bg-success bg-gradient text-white rounded-4 shadow-sm text-center">
                <i class="fas fa-hands-praying fa-4x mb-3 text-warning"></i>
                <h4 class="fw-bold mb-2">Our Core Mission</h4>
                <p class="mb-0 text-white-50">To provide a highly reliable, ad-free, and simple tool to help Muslims locate mosques and Namaz timings anywhere, anytime, with minimal effort.</p>
            </div>
        </div>
    </div>

    <!-- How It Works Section -->
    <h3 class="section-title text-center d-block mb-2">How the System Works</h3>
    <p class="section-subtitle text-center mb-5">Getting directions to your nearby mosque in three simple clicks</p>
    
    <div class="row g-4 mb-5">
        <!-- Step 1 -->
        <div class="col-md-4">
            <div class="info-card text-center">
                <div class="info-icon mx-auto"><i class="fas fa-location-arrow"></i></div>
                <h5>1. Share Geolocation</h5>
                <p class="mb-0">Allow browser GPS geolocation permission. The system automatically detects your current latitude and longitude coordinates.</p>
            </div>
        </div>
        <!-- Step 2 -->
        <div class="col-md-4">
            <div class="info-card text-center">
                <div class="info-icon mx-auto"><i class="fas fa-mosque"></i></div>
                <h5>2. Find Nearby Mosques</h5>
                <p class="mb-0">Our backend computes distances on-the-fly using the Haversine formula and lists mosques sorted by proximity.</p>
            </div>
        </div>
        <!-- Step 3 -->
        <div class="col-md-4">
            <div class="info-card text-center">
                <div class="info-icon mx-auto"><i class="fas fa-route"></i></div>
                <h5>3. Navigate to Destination</h5>
                <p class="mb-0">Click 'Get Directions' to open turn-by-turn navigation guides on Google Maps directly to the selected mosque.</p>
            </div>
        </div>
    </div>

    <!-- Platform Benefits -->
    <h3 class="section-title text-center d-block mb-2">Platform Benefits</h3>
    <p class="section-subtitle text-center mb-5">Why use our digital directory database</p>

    <div class="row g-4">
        <div class="col-md-6 col-lg-3">
            <div class="card p-4 h-100 border-0 shadow-sm rounded-4 text-center">
                <i class="fas fa-mobile-alt fa-2x text-success mb-3"></i>
                <h6 class="fw-bold text-success">Mobile Responsive</h6>
                <p class="small text-secondary mb-0">Fully responsive UI designed to work seamlessly on mobile browsers while on the go.</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card p-4 h-100 border-0 shadow-sm rounded-4 text-center">
                <i class="fas fa-search-location fa-2x text-success mb-3"></i>
                <h6 class="fw-bold text-success">Radius Search</h6>
                <p class="small text-secondary mb-0">Filter mosques by search radii (1km, 5km, or 10km) to see only walkable options.</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card p-4 h-100 border-0 shadow-sm rounded-4 text-center">
                <i class="fas fa-users fa-2x text-success mb-3"></i>
                <h6 class="fw-bold text-success">Sect Splits</h6>
                <p class="small text-secondary mb-0">Separate markers and cards for Sunni and Shia schools of thought to avoid ambiguity.</p>
            </div>
        </div>
        <div class="col-md-6 col-lg-3">
            <div class="card p-4 h-100 border-0 shadow-sm rounded-4 text-center">
                <i class="fas fa-clock fa-2x text-success mb-3"></i>
                <h6 class="fw-bold text-success">Live Countdown</h6>
                <p class="small text-secondary mb-0">Integrated real-time clock displaying hours and minutes remaining until the next congregation.</p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
