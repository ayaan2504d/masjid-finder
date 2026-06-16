@extends('layouts.public')

@section('title', 'About Details')

@section('content')
<section class="py-5">
  <div class="container">
    <div class="text-center mb-5">
      <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-semibold mb-3"><i class="fas fa-info-circle me-2"></i>Project Details</span>
      <h1 class="display-6 fw-bold text-success mb-3">A clean, reliable platform for mosque discovery and prayer information.</h1>
      <p class="lead text-secondary mb-0">This section highlights the key features and purpose of the Laravel-based public experience now running on the current site.</p>
    </div>

    <div class="row g-4 mb-5">
      <div class="col-md-6 col-lg-4">
        <article class="info-card h-100">
          <div class="info-icon"><i class="fas fa-map-marked-alt"></i></div>
          <h5>Interactive mosque map</h5>
          <p>Browse masjid locations, view nearby areas, and explore the directory with the same responsive map experience used on the homepage.</p>
        </article>
      </div>
      <div class="col-md-6 col-lg-4">
        <article class="info-card h-100">
          <div class="info-icon"><i class="fas fa-clock"></i></div>
          <h5>Prayer timing support</h5>
          <p>Daily timings, Friday Juma details, and special Eid schedules are presented in an organized and easy-to-read format.</p>
        </article>
      </div>
      <div class="col-md-6 col-lg-4">
        <article class="info-card h-100">
          <div class="info-icon"><i class="fas fa-paper-plane"></i></div>
          <h5>Contact and feedback</h5>
          <p>The public contact form remains active and helps visitors send messages directly through the Laravel workflow.</p>
        </article>
      </div>
    </div>

    <div class="row g-4 align-items-stretch">
      <div class="col-lg-7">
        <article class="card border-0 shadow-sm rounded-4 h-100">
          <div class="card-body p-4 p-lg-5">
            <h2 class="h4 fw-bold text-success mb-3"><i class="fas fa-hands-praying me-2"></i>What this project provides</h2>
            <p class="text-secondary">The Laravel migration preserves the original website purpose while improving the public pages for a smoother and more professional user experience.</p>
            <ul class="list-unstyled text-secondary mb-0">
              <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Nearby mosque discovery and map browsing</li>
              <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Prayer timing, Juma, and Eid information</li>
              <li class="mb-2"><i class="fas fa-check-circle text-success me-2"></i> Contact form workflow and public page navigation</li>
              <li><i class="fas fa-check-circle text-success me-2"></i> Responsive design that matches the existing website theme</li>
            </ul>
          </div>
        </article>
      </div>
      <div class="col-lg-5">
        <article class="card border-0 shadow-sm rounded-4 h-100 bg-success text-white">
          <div class="card-body p-4 p-lg-5">
            <h2 class="h4 fw-bold mb-3"><i class="fas fa-star me-2"></i>Why it matters</h2>
            <p class="text-white-50">A clean and easy-to-use experience helps visitors find information quickly, especially when they need timely prayer or mosque details.</p>
            <a href="{{ route('contact') }}" class="btn btn-gold mt-2"><i class="fas fa-envelope me-2"></i>Contact us</a>
          </div>
        </article>
      </div>
    </div>
  </div>
</section>
@endsection
