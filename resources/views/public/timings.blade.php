@extends('layouts.public')

@section('title', 'Prayer Timings')

@section('content')
<section class="breadcrumb-section">
  <div class="container">
    <h2 class="breadcrumb-title">Prayer Timings</h2>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
        <li class="breadcrumb-item active" aria-current="page">Namaz Schedule</li>
      </ol>
    </nav>
  </div>
</section>

<div class="container my-5 animate-fadeInUp">
  <div class="row g-4">
    <div class="col-lg-5">
      <div class="card shadow-sm border-0 rounded-4 p-4 mb-4">
        <h5 class="fw-bold text-success mb-3"><i class="fas fa-search-location"></i> Select Masjid</h5>
        <form action="{{ route('timings.index') }}" method="GET">
          <select name="masjid_id" class="form-select form-select-lg mb-3" onchange="this.form.submit()">
            @foreach($masjids as $m)
              <option value="{{ $m->id }}" @selected($selectedMasjid && $selectedMasjid->id === $m->id)>[{{ $m->sect }}] {{ $m->name }}</option>
            @endforeach
          </select>
        </form>
        @if($selectedMasjid)
          <p class="text-secondary small mb-0"><i class="fas fa-map-marker-alt"></i> {{ $selectedMasjid->address }}</p>
        @endif
      </div>

      @if($selectedMasjid)
        <div class="countdown-card" id="countdownCard">
          <div class="countdown-label">COUNTDOWN TO NEXT PRAYER</div>
          <div class="countdown-prayer-name" id="countdownPrayerName">{{ $nextPrayer['name'] }}</div>
          <div class="countdown-digits" id="prayerCountdown">
            <div class="digit-box"><div class="digit-value countdown-hours">00</div><div class="digit-label">Hrs</div></div>
            <div class="digit-box"><div class="digit-value countdown-minutes">00</div><div class="digit-label">Min</div></div>
            <div class="digit-box"><div class="digit-value countdown-seconds">00</div><div class="digit-label">Sec</div></div>
          </div>
        </div>
      @endif
    </div>

    <div class="col-lg-7">
      @if($selectedMasjid)
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
          <div class="bg-success bg-gradient text-white p-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
              <h4 class="mb-1 fw-bold">{{ $selectedMasjid->name }}</h4>
              <span class="badge bg-white text-success fw-bold">{{ $selectedMasjid->sect }} School</span>
            </div>
            <div class="text-md-end">
              <div class="fw-bold"><i class="far fa-calendar-alt"></i> {{ now()->format('l, d F Y') }}</div>
              <div class="small text-white-50">Hijri date placeholder</div>
            </div>
          </div>
          <div class="card-body p-0">
            <table class="table table-hover prayer-table mb-0">
              <thead><tr><th>Namaz (Salah)</th><th>Azaan / Jama'at Time</th><th>Status</th></tr></thead>
              <tbody>
                @foreach(['fajr' => ['Fajr', 'feather-alt'], 'zuhr' => ['Zuhr', 'sun'], 'asr' => ['Asr', 'cloud-sun'], 'maghrib' => ['Maghrib', 'cloud-moon'], 'isha' => ['Isha', 'moon']] as $key => [$label, $icon])
                  <tr id="row-{{ $key }}" class="{{ $nextPrayer['key'] === $key ? 'current-prayer' : '' }}">
                    <td><span class="prayer-icon prayer-icon-{{ $key }}"><i class="fas fa-{{ $icon }}"></i></span> <strong>{{ $label }}</strong></td>
                    <td>{{ $selectedMasjid->{$key} ?: '-' }}</td>
                    <td>
                      @if($nextPrayer['key'] === $key)
                        <span class="badge bg-success">Next Namaz</span>
                      @else
                        <span class="text-muted small">Upcoming</span>
                      @endif
                    </td>
                  </tr>
                @endforeach
                <tr class="table-success">
                  <td><span class="prayer-icon bg-success text-white"><i class="fas fa-users"></i></span> <strong>Juma (Friday)</strong></td>
                  <td><strong>{{ $selectedMasjid->juma_time ?: '-' }}</strong></td>
                  <td><span class="badge bg-success">Weekly</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      @else
        <div class="alert alert-warning">Please add mosques in the admin panel to view timings.</div>
      @endif
    </div>
  </div>

  <div class="row mt-5">
    <div class="col-md-6 mb-3">
      <div class="card p-4 shadow-sm border-0 rounded-4 h-100 text-center">
        <h5 class="fw-bold text-success mb-2"><i class="fas fa-table"></i> Compare All Timings</h5>
        <p class="text-secondary small">Compare Fajr, Zuhr, Asr, Maghrib, Isha, and Juma timings across all registered mosques in Karachi.</p>
        <a href="{{ route('timings.details') }}" class="btn btn-outline-custom mt-auto">Compare Timings Table</a>
      </div>
    </div>
    <div class="col-md-6 mb-3">
      <div class="card p-4 shadow-sm border-0 rounded-4 h-100 text-center">
        <h5 class="fw-bold text-success mb-2"><i class="fas fa-star-and-crescent"></i> Juma & Eid timings</h5>
        <p class="text-secondary small">Find the Friday sermon details, special Eid-ul-Fitr, and Eid-ul-Adha timings along with community notices.</p>
        <a href="{{ route('timings.juma') }}" class="btn btn-primary-custom mt-auto">Juma & Eid Timings</a>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  @if($selectedMasjid)
    PrayerCountdown.start(@json($selectedMasjid), 'prayerCountdown');
  @endif
});
</script>
@endsection
