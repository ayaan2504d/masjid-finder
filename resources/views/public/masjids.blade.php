@extends('layouts.public')

@section('title', 'Explore Masajid')

@section('content')
<section class="breadcrumb-section">
  <div class="container">
    <h2 class="breadcrumb-title">Explore Masajid</h2>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Masajid Directory</li>
      </ol>
    </nav>
  </div>
</section>

<div class="container my-5 animate-fadeInUp">
  <div class="row mb-5 g-3">
    <div class="col-lg-6">
      <form action="{{ route('masjids.index') }}" method="GET" class="search-box">
        @if(request('sect'))<input type="hidden" name="sect" value="{{ request('sect') }}">@endif
        <i class="fas fa-search search-icon"></i>
        <input type="text" name="search" class="form-control" placeholder="Search by name, address or keyword..." value="{{ request('search') }}">
      </form>
    </div>
    <div class="col-lg-6 text-lg-end d-flex align-items-center justify-content-lg-end gap-2">
      <a href="{{ route('masjids.index', ['search' => request('search')]) }}" class="sect-filter-btn {{ request('sect') ? '' : 'active' }}">All Sects</a>
      <a href="{{ route('masjids.index', ['search' => request('search'), 'sect' => 'Sunni']) }}" class="sect-filter-btn {{ request('sect') === 'Sunni' ? 'active' : '' }}">Sunni</a>
      <a href="{{ route('masjids.index', ['search' => request('search'), 'sect' => 'Shia']) }}" class="sect-filter-btn {{ request('sect') === 'Shia' ? 'active' : '' }}">Shia</a>
    </div>
  </div>

  @if(request('search') || request('sect'))
    <div class="mb-4 d-flex align-items-center gap-2 flex-wrap">
      <span class="text-secondary">Active filters:</span>
      @if(request('search'))
        <span class="badge bg-success px-3 py-2">Search: "{{ request('search') }}"</span>
      @endif
      @if(request('sect'))
        <span class="badge bg-success px-3 py-2">Sect: {{ request('sect') }}</span>
      @endif
      <a href="{{ route('masjids.index') }}" class="btn btn-link text-success btn-sm text-decoration-none fw-bold ms-2">Reset Filters</a>
    </div>
  @endif

  <div class="row g-4">
    @forelse($masjids as $m)
      <div class="col-lg-4 col-md-6" data-sect="{{ $m->sect }}">
        <div class="masjid-card">
          <div class="card-img-top"><i class="fas fa-mosque"></i></div>
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="badge badge-sect badge-{{ strtolower($m->sect) }}">{{ $m->sect }}</span>
              <span class="distance-badge d-none" data-lat="{{ $m->latitude }}" data-lng="{{ $m->longitude }}"><i class="fas fa-route"></i> Calculating...</span>
            </div>
            <h5 class="card-title">{{ $m->name }}</h5>
            <p class="card-text mb-4 text-truncate-2"><i class="fas fa-map-marker-alt text-success me-1"></i> {{ $m->address }}</p>
            <div class="row g-1 text-center py-2 px-1 bg-light rounded mb-3 small">
              <div class="col"><strong>Fajr</strong><br>{{ $m->fajr }}</div>
              <div class="col"><strong>Zuhr</strong><br>{{ $m->zuhr }}</div>
              <div class="col"><strong>Asr</strong><br>{{ $m->asr }}</div>
              <div class="col"><strong>Maghrib</strong><br>{{ $m->maghrib }}</div>
              <div class="col"><strong>Isha</strong><br>{{ $m->isha }}</div>
            </div>
          </div>
          <div class="card-footer">
            <a href="{{ route('masjids.show', $m) }}" class="btn btn-outline-custom btn-sm w-100">View Details & Map <i class="fas fa-arrow-right ms-1"></i></a>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12 text-center py-5">
        <i class="fas fa-search fa-3x text-muted mb-3"></i>
        <h4 class="text-secondary fw-bold">No Masajid Found</h4>
        <p class="text-muted">We couldn't find any mosques matching your filters. Try checking spelling or changing filters.</p>
        <a href="{{ route('masjids.index') }}" class="btn btn-primary-custom mt-3">Reset Search</a>
      </div>
    @endforelse
  </div>

  <div class="mt-4">{{ $masjids->links() }}</div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  GPS.getLocation().then(pos => {
    document.querySelectorAll('.distance-badge').forEach(badge => {
      const dist = haversineDistance(pos.lat, pos.lng, parseFloat(badge.dataset.lat), parseFloat(badge.dataset.lng));
      badge.innerHTML = `<i class="fas fa-route"></i> ${dist} km`;
      badge.classList.remove('d-none');
    });
  }).catch(() => {});
});
</script>
@endsection
