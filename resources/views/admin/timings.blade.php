@extends('layouts.admin')

@section('title', 'Timings')

@section('content')
<div class="admin-table-card">
  <div class="card-header"><i class="fas fa-clock me-1"></i> Masjid Prayer Timings</div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table admin-table mb-0">
        <thead><tr><th>Masjid</th><th>Fajr</th><th>Zuhr</th><th>Asr</th><th>Maghrib</th><th>Isha</th><th>Juma</th><th>Eid</th></tr></thead>
        <tbody>
          @forelse($masjids as $masjid)
            <tr>
              <td class="fw-bold text-success">{{ $masjid->name }}</td>
              <td>{{ $masjid->fajr ?: '-' }}</td>
              <td>{{ $masjid->zuhr ?: '-' }}</td>
              <td>{{ $masjid->asr ?: '-' }}</td>
              <td>{{ $masjid->maghrib ?: '-' }}</td>
              <td>{{ $masjid->isha ?: '-' }}</td>
              <td>{{ $masjid->juma_time ?: '-' }}</td>
              <td>{{ $masjid->eid_time ?: '-' }}</td>
            </tr>
          @empty
            <tr><td colspan="8" class="text-center py-4 text-muted">No timings found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
