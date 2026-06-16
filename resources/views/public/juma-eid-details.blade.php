@extends('layouts.public')

@section('title', 'Juma & Eid Details')

@section('content')
<section class="py-5">
  <div class="container">
    <div class="row g-4 align-items-stretch">
      <div class="col-lg-7">
        <article class="card border-0 shadow-sm rounded-4 h-100">
          <div class="card-body p-4 p-lg-5">
            <span class="badge bg-success-subtle text-success rounded-pill mb-3"><i class="fas fa-star-and-crescent me-2"></i>Juma & Eid Schedule</span>
            <h1 class="h2 fw-bold text-success mb-2">{{ $masjid->name }}</h1>
            <p class="text-secondary mb-4"><i class="fas fa-map-marker-alt me-2"></i>{{ $masjid->address }}</p>

            <div class="row g-3 mb-4">
              <div class="col-sm-6">
                <div class="rounded-4 bg-light p-4 h-100">
                  <div class="text-success mb-2"><i class="fas fa-users"></i></div>
                  <h2 class="h6 fw-bold text-success mb-1">Juma (Friday)</h2>
                  <p class="mb-0 text-secondary">{{ $masjid->juma_time ?? 'Time not available' }}</p>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="rounded-4 bg-light p-4 h-100">
                  <div class="text-success mb-2"><i class="fas fa-star"></i></div>
                  <h2 class="h6 fw-bold text-success mb-1">Eid Prayer</h2>
                  <p class="mb-0 text-secondary">{{ $masjid->eid_time ?? 'Time not available' }}</p>
                </div>
              </div>
            </div>

            <ul class="list-unstyled text-secondary mb-0">
              <li class="mb-2"><strong class="text-success">Sect:</strong> {{ $masjid->sect ?? 'Community' }}</li>
              <li class="mb-2"><strong class="text-success">Phone:</strong> {{ $masjid->phone ?? '—' }}</li>
              <li><strong class="text-success">Description:</strong> {{ $masjid->description ?? 'No additional description available.' }}</li>
            </ul>
          </div>
        </article>
      </div>
      <div class="col-lg-5">
        <article class="card border-0 shadow-sm rounded-4 h-100 bg-success text-white">
          <div class="card-body p-4 p-lg-5">
            <h2 class="h4 fw-bold mb-3"><i class="fas fa-info-circle me-2"></i>Quick note</h2>
            <p class="text-white-50">Use these schedules for Friday and Eid planning, and confirm the exact time with the mosque directly when needed.</p>
            <a href="{{ route('timings.juma') }}" class="btn btn-gold mt-2"><i class="fas fa-arrow-left me-2"></i>Back to all timings</a>
          </div>
        </article>
      </div>
    </div>
  </div>
</section>
@endsection
