@extends('layouts.public')

@section('title', 'Timing Comparison')

@section('content')
<section class="container py-5">
  <h1 class="h2 mb-3">Timing Comparison</h1>
  <div class="table-responsive">
    <table class="table table-striped align-middle shadow-sm">
      <thead>
        <tr>
          <th>Masjid</th>
          <th>Fajr</th>
          <th>Zuhr</th>
          <th>Asr</th>
          <th>Maghrib</th>
          <th>Isha</th>
        </tr>
      </thead>
      <tbody>
        @foreach($masjids as $masjid)
          <tr>
            <td>{{ $masjid->name }}</td>
            <td>{{ $masjid->fajr ?? '—' }}</td>
            <td>{{ $masjid->zuhr ?? '—' }}</td>
            <td>{{ $masjid->asr ?? '—' }}</td>
            <td>{{ $masjid->maghrib ?? '—' }}</td>
            <td>{{ $masjid->isha ?? '—' }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</section>
@endsection
