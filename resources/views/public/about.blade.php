@extends('layouts.public')

@section('title', 'About Us')

@section('content')
<section class="py-5">
  <div class="container">
    <div class="row g-5 align-items-center">
      <div class="col-lg-7">
        <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-semibold mb-3"><i class="fas fa-mosque me-2"></i>About Masjid Locator</span>
        <h1 class="display-6 fw-bold text-success mb-3">Serving the Ummah with clarity, speed, and trust.</h1>
        <p class="lead text-secondary">Our platform helps Muslims discover nearby mosques, review prayer timings, and reach their destination with confidence using a modern, mobile-friendly experience.</p>
        <p class="text-secondary">The Laravel migration keeps the original website purpose intact while improving navigation, presentation, and reliability across the public pages.</p>
        <div class="d-flex flex-wrap gap-2 mt-4">
          <a href="{{ route('about.details') }}" class="btn btn-primary-custom"><i class="fas fa-arrow-right me-2"></i>Explore details</a>
          <a href="{{ route('contact') }}" class="btn btn-outline-custom"><i class="fas fa-envelope me-2"></i>Contact us</a>
        </div>
      </div>
      <div class="col-lg-5">
        <div class="p-4 rounded-4 shadow-sm border border-success-subtle bg-white">
          <div class="d-flex align-items-center gap-3 mb-3">
            <div class="info-icon"><i class="fas fa-hands-praying"></i></div>
            <div>
              <h4 class="h5 fw-bold text-success mb-1">Our mission</h4>
              <p class="text-secondary mb-0">To make finding a mosque and prayer information simple, accessible, and respectful.</p>
            </div>
          </div>
          <ul class="list-unstyled mb-0 text-secondary small">
            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> GPS-friendly mosque discovery</li>
            <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Daily prayer timings and Juma details</li>
            <li><i class="fas fa-check-circle text-success me-2"></i> Clear information for visitors and residents</li>
          </ul>
        </div>
      </div>
    </div>

    <div class="row g-4 mt-2">
      <div class="col-md-4">
        <article class="info-card text-center h-100">
          <div class="info-icon mx-auto"><i class="fas fa-location-arrow"></i></div>
          <h5>Find nearby mosques</h5>
          <p>Use the interactive map and directory to locate the closest mosque quickly and reliably.</p>
        </article>
      </div>
      <div class="col-md-4">
        <article class="info-card text-center h-100">
          <div class="info-icon mx-auto"><i class="fas fa-clock"></i></div>
          <h5>Check prayer schedules</h5>
          <p>Review daily timings, Friday, and Eid information all from one place on the public site.</p>
        </article>
      </div>
      <div class="col-md-4">
        <article class="info-card text-center h-100">
          <div class="info-icon mx-auto"><i class="fas fa-route"></i></div>
          <h5>Get directions</h5>
          <p>Open directions instantly and move from planning to arrival with a smooth, mobile-friendly flow.</p>
        </article>
      </div>
    </div>
  </div>
</section>
@endsection
