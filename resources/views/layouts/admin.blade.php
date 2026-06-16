<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel')</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" rel="stylesheet">
    <link href="{{ asset('admin/assets/css/admin.css') }}" rel="stylesheet">
</head>
<body class="admin-body">
<div class="admin-wrapper">
  <aside class="admin-sidebar" id="adminSidebar">
    <div class="sidebar-brand">
      <i class="fas fa-mosque me-2"></i>
      <span>Masjid<span class="highlight">Admin</span></span>
    </div>
    <nav class="sidebar-nav">
      <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fas fa-chart-line fa-fw"></i> Dashboard</a>
      <a href="{{ route('admin.masjids') }}" class="{{ request()->routeIs('admin.masjids') ? 'active' : '' }}"><i class="fas fa-mosque fa-fw"></i> Manage Masajid</a>
      <a href="{{ route('admin.add-masjid') }}" class="{{ request()->routeIs('admin.add-masjid') ? 'active' : '' }}"><i class="fas fa-plus-circle fa-fw"></i> Add New Masjid</a>
      <a href="{{ route('admin.timings') }}" class="{{ request()->routeIs('admin.timings') ? 'active' : '' }}"><i class="fas fa-clock fa-fw"></i> Update Timings</a>
      <a href="{{ route('admin.settings') }}" class="{{ request()->routeIs('admin.settings') ? 'active' : '' }}"><i class="fas fa-sliders-h fa-fw"></i> System Settings</a>
    </nav>
    <div class="sidebar-footer">
      <a href="{{ url('/') }}" target="_blank"><i class="fas fa-external-link-alt me-1"></i> View Main Site</a>
    </div>
  </aside>
  <div class="admin-main">
    <header class="admin-header">
      <div class="d-flex align-items-center gap-3">
        <button class="sidebar-toggle-btn" id="sidebarToggle" title="Toggle Sidebar">
          <i class="fas fa-bars"></i>
        </button>
        <h4 class="mb-0 fw-bold">@yield('title', 'Admin Control Panel')</h4>
      </div>
      <div class="admin-user-info small text-secondary">
        <i class="fas fa-user-shield me-1"></i> Administrator
      </div>
    </header>
    <div class="admin-content">
      @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('status') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif
      @if($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      @yield('content')
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const toggleBtn = document.getElementById('sidebarToggle');
  const sidebar = document.getElementById('adminSidebar');
  if (!toggleBtn || !sidebar) return;
  toggleBtn.addEventListener('click', (event) => {
    event.stopPropagation();
    sidebar.classList.toggle('active');
  });
  document.addEventListener('click', (event) => {
    if (sidebar.classList.contains('active') && !sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
      sidebar.classList.remove('active');
    }
  });
});
</script>
</body>
</html>
