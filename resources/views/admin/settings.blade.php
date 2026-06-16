@extends('layouts.admin')

@section('title', 'Settings')

@section('content')
<form method="POST" action="{{ route('admin.update-settings') }}" class="admin-form-card">
  @csrf
  <h5><i class="fas fa-sliders-h me-1"></i> System Settings</h5>
  <div class="row g-3">
    <div class="col-md-6"><label class="form-label">Site Name</label><input type="text" name="site_name" class="form-control" value="{{ $settings['site_name'] ?? '' }}"></div>
    <div class="col-md-6"><label class="form-label">Tagline</label><input type="text" name="site_tagline" class="form-control" value="{{ $settings['site_tagline'] ?? '' }}"></div>
    <div class="col-md-6"><label class="form-label">Default City</label><input type="text" name="default_city" class="form-control" value="{{ $settings['default_city'] ?? '' }}"></div>
    <div class="col-md-6"><label class="form-label">Contact Email</label><input type="email" name="contact_email" class="form-control" value="{{ $settings['contact_email'] ?? '' }}"></div>
    <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="contact_phone" class="form-control" value="{{ $settings['contact_phone'] ?? '' }}"></div>
    <div class="col-md-6"><label class="form-label">Address</label><input type="text" name="contact_address" class="form-control" value="{{ $settings['contact_address'] ?? '' }}"></div>
  </div>
  <button class="btn btn-primary mt-3"><i class="fas fa-save me-1"></i> Save Settings</button>
</form>
@endsection
