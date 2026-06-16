@extends('layouts.admin')

@section('title', 'Manage Masjids')

@section('content')
<div class="admin-table-card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span><i class="fas fa-mosque me-1"></i> Manage Masajid</span>
    <a href="{{ route('admin.add-masjid') }}" class="btn btn-success btn-sm"><i class="fas fa-plus-circle me-1"></i> Add Masjid</a>
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table admin-table mb-0">
        <thead>
          <tr>
            <th>Name</th>
            <th>Area</th>
            <th>Sect</th>
            <th>Phone</th>
            <th>Timings</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse($masjids as $masjid)
            <tr>
              <td class="fw-bold text-success">{{ $masjid->name }}</td>
              <td>{{ $masjid->area ?: 'Karachi' }}</td>
              <td><span class="badge badge-sect badge-{{ strtolower($masjid->sect) }}">{{ $masjid->sect }}</span></td>
              <td>{{ $masjid->phone ?: '-' }}</td>
              <td class="small">Fajr {{ $masjid->fajr ?: '-' }} / Isha {{ $masjid->isha ?: '-' }}</td>
              <td>
                <div class="d-flex gap-1">
                  <a href="{{ route('admin.edit-masjid', $masjid) }}" class="btn-admin-action btn-edit"><i class="fas fa-edit"></i> Edit</a>
                  <form method="POST" action="{{ route('admin.delete-masjid') }}" onsubmit="return confirm('Delete this masjid?');">
                    @csrf
                    <input type="hidden" name="id" value="{{ $masjid->id }}">
                    <button class="btn-admin-action btn-delete" type="submit"><i class="fas fa-trash"></i> Delete</button>
                  </form>
                </div>
              </td>
            </tr>
          @empty
            <tr><td colspan="6" class="text-center py-4 text-muted">No masjids found.</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
