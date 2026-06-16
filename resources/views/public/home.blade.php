@extends('layouts.public')

@section('title', 'Find Nearest Masjid')

@section('content')
<section class="hero-section text-white py-5">
  <div class="container hero-content">
    <div class="row align-items-center g-5">
      <div class="col-lg-6 animate-fadeInLeft">
        <span class="badge bg-white text-success px-3 py-2 rounded-pill mb-3 fw-bold shadow-sm">SMART GPS LOCATOR</span>
        <h1 class="hero-title mb-3">Find Your <span class="highlight">Nearest</span> Masjid & Prayer Timings</h1>
        <p class="hero-subtitle mb-4">Instantly locate mosques around you, view accurate daily prayer times, get directions, and keep track of Friday (Juma) and Eid salah schedules.</p>
        <div class="hero-search mb-3">
          <form action="{{ route('masjids.index') }}" method="GET">
            <div class="input-group">
              <input type="text" name="search" class="form-control" placeholder="Search by masjid name or area..." aria-label="Search masjid">
              <button class="btn btn-gold" type="submit"><i class="fas fa-search"></i> Search</button>
            </div>
          </form>
        </div>
        <button id="btnGPSLocate" class="hero-gps-btn">
          <i class="fas fa-location-crosshairs fa-lg me-1"></i>
          <span>Locate Nearest Masjid</span>
        </button>
        <div id="gpsStatus" class="mt-2 small text-warning d-none"></div>
      </div>
      <div class="col-lg-6 animate-fadeInUp">
        <div class="hero-map-container">
          <div id="heroMap" style="height:100%; width:100%;"></div>
        </div>
      </div>
    </div>
  </div>
</section>

<div class="container my-5">
  <div id="recommendedSection" class="row mb-5 d-none">
    <div class="col-12">
      <h3 class="section-title mb-4">Nearest Recommended Masjid</h3>
      <div id="recommendedContainer"></div>
    </div>
  </div>

  <div class="row g-4 mb-5">
    <div class="col-lg-6">
      <div class="countdown-card" id="countdownCard">
        <div class="countdown-label">Next Prayer Countdown</div>
        <div class="countdown-prayer-name" id="countdownPrayerName">{{ $nextPrayer['name'] }}</div>
        <div class="countdown-digits" id="prayerCountdown">
          <div class="digit-box"><div class="digit-value countdown-hours">00</div><div class="digit-label">Hrs</div></div>
          <div class="digit-box"><div class="digit-value countdown-minutes">00</div><div class="digit-label">Min</div></div>
          <div class="digit-box"><div class="digit-value countdown-seconds">00</div><div class="digit-label">Sec</div></div>
        </div>
        @if($defaultMasjid)
          <p class="mt-3 mb-0 small text-white-50">Based on default: <strong>{{ $defaultMasjid->name }}</strong></p>
        @endif
      </div>
    </div>
    <div class="col-lg-6">
      <div class="row g-4 h-100">
        <div class="col-sm-6">
          <div class="stat-card h-100 d-flex flex-column justify-content-center">
            <div class="stat-icon"><i class="fas fa-mosque"></i></div>
            <div class="stat-number">{{ $total }}</div>
            <div class="stat-label">Total Masajid Covered</div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="stat-card h-100 d-flex flex-column justify-content-center">
            <div class="stat-icon bg-info"><i class="fas fa-users"></i></div>
            <div class="stat-number">{{ $sunniCount }} / {{ $shiaCount }}</div>
            <div class="stat-label">Sunni / Shia Masajid</div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row mb-4">
    <div class="col-md-6">
      <h3 class="section-title">Featured Masajid</h3>
      <p class="section-subtitle">Discover historical and prominent mosques in our directory</p>
    </div>
    <div class="col-md-6 text-md-end d-flex align-items-center justify-content-md-end gap-2 mb-4">
      <button class="sect-filter-btn active" data-filter="all" onclick="filterBySect('all')">All</button>
      <button class="sect-filter-btn" data-filter="Sunni" onclick="filterBySect('Sunni')">Sunni</button>
      <button class="sect-filter-btn" data-filter="Shia" onclick="filterBySect('Shia')">Shia</button>
    </div>
  </div>

  <div class="row g-4 mb-5" id="featuredGrid">
    @forelse($featured as $m)
      <div class="col-lg-4 col-md-6 masjid-item-col" data-sect="{{ $m->sect }}">
        <div class="masjid-card">
          <div class="card-img-top"><i class="fas fa-mosque"></i></div>
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="badge badge-sect badge-{{ strtolower($m->sect) }}">{{ $m->sect }}</span>
              <span class="distance-badge d-none" data-lat="{{ $m->latitude }}" data-lng="{{ $m->longitude }}"><i class="fas fa-route"></i> Calculating...</span>
            </div>
            <h5 class="card-title">{{ $m->name }}</h5>
            <p class="card-text text-truncate-2 mb-3"><i class="fas fa-map-marker-alt text-success me-1"></i> {{ $m->address }}</p>
            <div class="row g-1 text-center py-2 px-1 bg-light rounded mb-3 small">
              <div class="col"><strong>Fajr</strong><br>{{ $m->fajr }}</div>
              <div class="col"><strong>Zuhr</strong><br>{{ $m->zuhr }}</div>
              <div class="col"><strong>Asr</strong><br>{{ $m->asr }}</div>
              <div class="col"><strong>Maghrib</strong><br>{{ $m->maghrib }}</div>
              <div class="col"><strong>Isha</strong><br>{{ $m->isha }}</div>
            </div>
          </div>
          <div class="card-footer">
            <a href="{{ route('masjids.show', $m) }}" class="btn btn-outline-custom w-100 btn-sm">View Details <i class="fas fa-arrow-right ms-1"></i></a>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12 text-center py-5"><i class="fas fa-mosque fa-3x text-muted mb-3"></i><p class="text-muted">No masjids found.</p></div>
    @endforelse
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const masjids = @json($featured->values());
  MapHelper.init('heroMap', 24.8607, 67.0011, 12);
  MapHelper.addMasjidMarkers(masjids);
  @if($defaultMasjid)
    PrayerCountdown.start(@json($defaultMasjid), 'prayerCountdown');
  @endif

  const btnGPS = document.getElementById('btnGPSLocate');
  const gpsStatus = document.getElementById('gpsStatus');
  const recommendedSection = document.getElementById('recommendedSection');
  const recommendedContainer = document.getElementById('recommendedContainer');

  btnGPS.addEventListener('click', () => {
    btnGPS.disabled = true;
    btnGPS.querySelector('span').textContent = 'Locating...';
    gpsStatus.classList.remove('d-none');
    gpsStatus.textContent = 'Acquiring GPS location...';

    GPS.getLocation().then(pos => {
      btnGPS.querySelector('span').textContent = 'Location Updated';
      gpsStatus.innerHTML = '<i class="fas fa-check-circle"></i> Location acquired.';
      gpsStatus.className = 'mt-2 small text-white';
      MapHelper.addUserMarker(pos.lat, pos.lng);
      MapHelper.map.setView([pos.lat, pos.lng], 13);
      MapHelper.addMasjidMarkers(masjids, pos.lat, pos.lng);

      document.querySelectorAll('.distance-badge').forEach(badge => {
        const dist = haversineDistance(pos.lat, pos.lng, parseFloat(badge.dataset.lat), parseFloat(badge.dataset.lng));
        badge.innerHTML = `<i class="fas fa-route"></i> ${dist} km`;
        badge.classList.remove('d-none');
      });

      let nearest = null;
      let minDist = Infinity;
      masjids.forEach(m => {
        const dist = parseFloat(haversineDistance(pos.lat, pos.lng, m.latitude, m.longitude));
        if (dist < minDist) {
          minDist = dist;
          nearest = m;
        }
      });

      if (nearest) {
        recommendedSection.classList.remove('d-none');
        recommendedContainer.innerHTML = `
          <div class="recommended-card p-4 animate-fadeInUp">
            <div class="row g-4 align-items-center">
              <div class="col-md-7">
                <div class="d-flex align-items-center gap-2 mb-2">
                  <span class="badge badge-sect badge-${nearest.sect.toLowerCase()}">${nearest.sect}</span>
                  <span class="badge bg-primary rounded-pill"><i class="fas fa-route"></i> ${minDist.toFixed(2)} km away</span>
                </div>
                <h4 class="text-success fw-bold">${nearest.name}</h4>
                <p class="text-secondary mb-3"><i class="fas fa-map-marker-alt"></i> ${nearest.address}</p>
                <p class="mb-4 small text-muted">${nearest.description || 'No description available.'}</p>
                <div class="d-flex flex-wrap gap-2">
                  <a href="{{ url('/masjids') }}/${nearest.id}" class="btn btn-primary-custom btn-sm">View Full Profile <i class="fas fa-chevron-right ms-1"></i></a>
                  <a href="${GPS.buildDirectionsUrl(nearest.latitude, nearest.longitude, pos.lat, pos.lng)}" target="_blank" class="btn btn-gold btn-sm" data-directions-link="true" data-dest-lat="${nearest.latitude}" data-dest-lng="${nearest.longitude}"><i class="fas fa-location-arrow me-1"></i> Get Directions</a>
                </div>
              </div>
              <div class="col-md-5">
                <div class="p-3 bg-light rounded-4">
                  <h6 class="text-success fw-bold mb-3 border-bottom pb-2"><i class="fas fa-clock"></i> Today's Prayer Timings</h6>
                  <table class="table table-sm table-borderless mb-0">
                    <tr><td><strong>Fajr</strong></td><td class="text-end">${formatTime12h(nearest.fajr)}</td></tr>
                    <tr><td><strong>Zuhr</strong></td><td class="text-end">${formatTime12h(nearest.zuhr)}</td></tr>
                    <tr><td><strong>Asr</strong></td><td class="text-end">${formatTime12h(nearest.asr)}</td></tr>
                    <tr><td><strong>Maghrib</strong></td><td class="text-end">${formatTime12h(nearest.maghrib)}</td></tr>
                    <tr><td><strong>Isha</strong></td><td class="text-end">${formatTime12h(nearest.isha)}</td></tr>
                    <tr class="table-success border-top"><td><strong>Juma</strong></td><td class="text-end"><strong>${formatTime12h(nearest.juma_time)}</strong></td></tr>
                  </table>
                </div>
              </div>
            </div>
          </div>`;
        PrayerCountdown.start(nearest, 'prayerCountdown');
      }
    }).catch(() => {
      btnGPS.disabled = false;
      btnGPS.querySelector('span').textContent = 'Locate Nearest Masjid';
      gpsStatus.innerHTML = '<i class="fas fa-exclamation-triangle"></i> GPS access denied or timed out.';
      gpsStatus.className = 'mt-2 small text-danger';
    });
  });
});
</script>
@endsection
