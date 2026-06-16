@extends('layouts.public')

@section('title', 'Juma & Eid')

@section('content')
<section class="py-5">
  <div class="container">
    <div class="text-center mb-5">
      <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill fw-semibold mb-3"><i class="fas fa-star-and-crescent me-2"></i>Juma & Eid Timings</span>
      <h1 class="display-6 fw-bold text-success mb-3">Friday and Eid prayer schedules at a glance.</h1>
      <p class="lead text-secondary mb-0">Browse the available masjid schedules and open each detail page for the full timing information.</p>
    </div>

    <div class="row g-4">
      @forelse($masjids as $masjid)
        <div class="col-md-6 col-lg-4">
          <article class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-4">
              <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                <div>
                  <span class="badge bg-success-subtle text-success rounded-pill mb-2">{{ $masjid->sect ?? 'Community' }}</span>
                  <h2 class="h5 fw-bold text-success mb-1">{{ $masjid->name }}</h2>
                </div>
                <i class="fas fa-mosque text-success fs-4"></i>
              </div>
              <p class="text-secondary small mb-3"><i class="fas fa-map-marker-alt me-2"></i>{{ $masjid->address }}</p>
              <div class="rounded-4 bg-light p-3 mb-3">
                <div class="d-flex justify-content-between small mb-2"><span class="text-secondary">Juma</span><strong class="text-success">{{ $masjid->juma_time ?? '—' }}</strong></div>
                <div class="d-flex justify-content-between small"><span class="text-secondary">Eid</span><strong class="text-success">{{ $masjid->eid_time ?? '—' }}</strong></div>
              </div>
              <a href="{{ route('timings.juma.details', $masjid) }}" class="btn btn-outline-custom w-100"><i class="fas fa-arrow-right me-2"></i>View full details</a>
            </div>
          </article>
        </div>
      @empty
        <div class="col-12">
          <div class="alert alert-info mb-0">No Juma and Eid timings are available right now.</div>
        </div>
      @endforelse
    </div>
  </div>
</section>
@endsection
