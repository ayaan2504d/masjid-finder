<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="app-base-url" content="{{ url('/') }}">
    <meta name="description" content="Find the nearest masjid, view prayer timings, and get directions. Masjid Locator helps you discover mosques near your location.">
    <meta name="keywords" content="masjid, mosque, prayer times, namaz, salah, GPS, locator, nearby mosque">
    <title>@yield('title', 'Masjid Locator')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
    <link href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" rel="stylesheet">
    <link href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top" id="mainNavbar">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
      <i class="fas fa-mosque me-2"></i>
      <span class="brand-text">Masjid<span class="brand-highlight">Locator</span></span>
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarMain">
      <ul class="navbar-nav ms-auto align-items-lg-center">
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ url('/') }}"><i class="fas fa-home me-1"></i> Home</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('masjids.*') ? 'active' : '' }}" href="{{ route('masjids.index') }}"><i class="fas fa-mosque me-1"></i> Masajid</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('map') ? 'active' : '' }}" href="{{ route('map') }}"><i class="fas fa-map-marked-alt me-1"></i> Map</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('timings.index', 'timings.details') ? 'active' : '' }}" href="{{ route('timings.index') }}"><i class="fas fa-clock me-1"></i> Timings</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('timings.juma*') ? 'active' : '' }}" href="{{ route('timings.juma') }}"><i class="fas fa-star-and-crescent me-1"></i> Juma & Eid</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('about*') ? 'active' : '' }}" href="{{ route('about') }}"><i class="fas fa-info-circle me-1"></i> About Us</a></li>
        <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}"><i class="fas fa-envelope me-1"></i> Contact</a></li>
        <li class="nav-item ms-lg-2"><a class="nav-link nav-admin-btn {{ request()->routeIs('admin.*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="fas fa-cog me-1"></i> Admin</a></li>
      </ul>
    </div>
  </div>
</nav>
@if(session('status'))
  <div class="container mt-3">
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      {{ session('status') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
  </div>
@endif
<main>
    @yield('content')
</main>
<footer class="site-footer">
  <div class="footer-wave">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 100" preserveAspectRatio="none">
      <path fill="currentColor" d="M0,40 C360,100 720,0 1080,60 C1260,90 1380,50 1440,40 L1440,100 L0,100 Z"></path>
    </svg>
  </div>
  <div class="footer-content">
    <div class="container">
      <div class="row g-4">
        <div class="col-lg-4 col-md-6">
          <div class="footer-brand"><i class="fas fa-mosque"></i><span>Masjid<span class="highlight">Locator</span></span></div>
          <p class="footer-desc">Find the nearest masjid, check prayer timings, and navigate to your destination. Our smart GPS-powered system makes it easy to never miss a prayer.</p>
          <div class="footer-social">
            <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
            <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
            <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
            <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
          </div>
        </div>
        <div class="col-lg-4 col-md-6">
          <h5 class="footer-title">Quick Links</h5>
          <ul class="footer-links">
            <li><a href="{{ url('/') }}"><i class="fas fa-chevron-right"></i> Home</a></li>
            <li><a href="{{ route('masjids.index') }}"><i class="fas fa-chevron-right"></i> Masajid</a></li>
            <li><a href="{{ route('map') }}"><i class="fas fa-chevron-right"></i> Map</a></li>
            <li><a href="{{ route('timings.index') }}"><i class="fas fa-chevron-right"></i> Prayer Timings</a></li>
            <li><a href="{{ route('timings.juma') }}"><i class="fas fa-chevron-right"></i> Juma & Eid</a></li>
            <li><a href="{{ route('about') }}"><i class="fas fa-chevron-right"></i> About Us</a></li>
            <li><a href="{{ route('contact') }}"><i class="fas fa-chevron-right"></i> Contact</a></li>
          </ul>
        </div>
        <div class="col-lg-4 col-md-6">
          <h5 class="footer-title">Contact Info</h5>
          <ul class="footer-contact">
            <li><i class="fas fa-map-marker-alt"></i><span>Karachi, Pakistan</span></li>
            <li><i class="fas fa-phone"></i><span>+92 300 1234567</span></li>
            <li><i class="fas fa-envelope"></i><span>info@masjidlocator.com</span></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container text-center">
      <p>&copy; {{ date('Y') }} Masjid Locator. All Rights Reserved. Built with <i class="fas fa-heart text-danger"></i> for the Ummah.</p>
    </div>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>
</body>
</html>
