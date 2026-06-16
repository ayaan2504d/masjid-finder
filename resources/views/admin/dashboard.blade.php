@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="row g-4 mb-4">
  <div class="col-xl-3 col-sm-6">
    <div class="admin-stat-card">
      <div class="admin-stat-info"><h6>Total Masajid</h6><h3>{{ $totalMasjids }}</h3></div>
      <div class="admin-stat-icon stat-green"><i class="fas fa-mosque"></i></div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6">
    <div class="admin-stat-card">
      <div class="admin-stat-info"><h6>Sunni Sect</h6><h3>{{ $sunniCount }}</h3></div>
      <div class="admin-stat-icon stat-teal"><i class="fas fa-users"></i></div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6">
    <div class="admin-stat-card">
      <div class="admin-stat-info"><h6>Shia Sect</h6><h3>{{ $shiaCount }}</h3></div>
      <div class="admin-stat-icon stat-blue"><i class="fas fa-users-cog"></i></div>
    </div>
  </div>
  <div class="col-xl-3 col-sm-6">
    <div class="admin-stat-card">
      <div class="admin-stat-info"><h6>Unread Messages</h6><h3>{{ $unreadMessages }}</h3></div>
      <div class="admin-stat-icon stat-orange"><i class="fas fa-envelope-open-text"></i></div>
    </div>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-7">
    <div class="admin-table-card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span><i class="fas fa-mosque me-1"></i> Recently Added Masajid</span>
        <a href="{{ route('admin.masjids') }}" class="btn btn-xs btn-outline-success py-1 px-2 small" style="font-size:0.75rem;">Manage All</a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table admin-table mb-0">
            <thead><tr><th>Name</th><th>Sect</th><th>Address</th><th>Actions</th></tr></thead>
            <tbody>
              @forelse($recentMasjids as $m)
                <tr>
                  <td class="fw-bold text-success">{{ $m->name }}</td>
                  <td><span class="badge badge-sect badge-{{ strtolower($m->sect) }} py-1" style="font-size:0.65rem;">{{ $m->sect }}</span></td>
                  <td class="text-truncate" style="max-width:180px;">{{ $m->address }}</td>
                  <td><a href="{{ route('admin.edit-masjid', $m) }}" class="btn-admin-action btn-edit" title="Edit"><i class="fas fa-edit"></i></a></td>
                </tr>
              @empty
                <tr><td colspan="4" class="text-center py-4 text-muted">No masjids added yet.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="admin-table-card">
      <div class="card-header"><span><i class="fas fa-envelope me-1"></i> Contact Enquiries</span></div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table admin-table mb-0">
            <thead><tr><th>Sender</th><th>Subject</th><th>Status</th></tr></thead>
            <tbody>
              @forelse($recentMessages as $msg)
                <tr>
                  <td><div class="fw-bold">{{ $msg->name }}</div><div class="small text-muted">{{ $msg->email }}</div></td>
                  <td class="text-truncate" style="max-width:150px;">{{ $msg->subject }}</td>
                  <td><span class="badge {{ $msg->is_read ? 'bg-secondary' : 'bg-danger' }}">{{ $msg->is_read ? 'Read' : 'New' }}</span></td>
                </tr>
              @empty
                <tr><td colspan="3" class="text-center py-4 text-muted">No messages received yet.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
