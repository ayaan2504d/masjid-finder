@extends('layouts.admin')

@section('title', 'Add Masjid')

@section('content')
<form method="POST" action="{{ route('admin.store-masjid') }}" class="admin-form-card">
  @csrf
  <h5><i class="fas fa-plus-circle me-1"></i> Add New Masjid</h5>
  <div class="row g-3">
    <div class="col-md-6"><label class="form-label">Name</label><input type="text" name="name" class="form-control" required></div>
    <div class="col-md-6"><label class="form-label">Area</label><input type="text" name="area" class="form-control"></div>
    <div class="col-md-6"><label class="form-label">City</label><input type="text" name="city" class="form-control" value="Karachi"></div>
    <div class="col-md-6"><label class="form-label">Sect</label><select name="sect" class="form-select"><option>Sunni</option><option>Shia</option></select></div>
    <div class="col-md-6"><label class="form-label">Latitude</label><input type="number" step="any" name="latitude" class="form-control" required></div>
    <div class="col-md-6"><label class="form-label">Longitude</label><input type="number" step="any" name="longitude" class="form-control" required></div>
    <div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2" required></textarea></div>
    <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control"></div>
    <div class="col-md-6"><label class="form-label">Featured</label><select name="is_featured" class="form-select"><option value="0">No</option><option value="1">Yes</option></select></div>
    <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3"></textarea></div>
    <div class="col-md-4"><label class="form-label">Fajr</label><input type="text" name="fajr" class="form-control"></div>
    <div class="col-md-4"><label class="form-label">Zuhr</label><input type="text" name="zuhr" class="form-control"></div>
    <div class="col-md-4"><label class="form-label">Asr</label><input type="text" name="asr" class="form-control"></div>
    <div class="col-md-4"><label class="form-label">Maghrib</label><input type="text" name="maghrib" class="form-control"></div>
    <div class="col-md-4"><label class="form-label">Isha</label><input type="text" name="isha" class="form-control"></div>
    <div class="col-md-4"><label class="form-label">Juma Time</label><input type="text" name="juma_time" class="form-control"></div>
    <div class="col-md-4"><label class="form-label">Eid Time</label><input type="text" name="eid_time" class="form-control"></div>
  </div>
  <button class="btn btn-success mt-3"><i class="fas fa-save me-1"></i> Save Masjid</button>
</form>
@endsection
