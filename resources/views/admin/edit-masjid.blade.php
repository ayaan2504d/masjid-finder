@extends('layouts.admin')

@section('title', 'Edit Masjid')

@section('content')
<form method="POST" action="{{ route('admin.update-masjid', $masjid) }}" class="admin-form-card">
  @csrf
  @method('PUT')
  <h5><i class="fas fa-edit me-1"></i> Edit Masjid</h5>
  <div class="row g-3">
    <div class="col-md-6"><label class="form-label">Name</label><input type="text" name="name" class="form-control" value="{{ $masjid->name }}" required></div>
    <div class="col-md-6"><label class="form-label">Area</label><input type="text" name="area" class="form-control" value="{{ $masjid->area }}"></div>
    <div class="col-md-6"><label class="form-label">City</label><input type="text" name="city" class="form-control" value="{{ $masjid->city }}"></div>
    <div class="col-md-6"><label class="form-label">Sect</label><select name="sect" class="form-select"><option value="Sunni" @selected($masjid->sect==='Sunni')>Sunni</option><option value="Shia" @selected($masjid->sect==='Shia')>Shia</option></select></div>
    <div class="col-md-6"><label class="form-label">Latitude</label><input type="number" step="any" name="latitude" class="form-control" value="{{ $masjid->latitude }}" required></div>
    <div class="col-md-6"><label class="form-label">Longitude</label><input type="number" step="any" name="longitude" class="form-control" value="{{ $masjid->longitude }}" required></div>
    <div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2" required>{{ $masjid->address }}</textarea></div>
    <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="{{ $masjid->phone }}"></div>
    <div class="col-md-6"><label class="form-label">Featured</label><select name="is_featured" class="form-select"><option value="0" @selected(!$masjid->is_featured)>No</option><option value="1" @selected($masjid->is_featured)>Yes</option></select></div>
    <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3">{{ $masjid->description }}</textarea></div>
    <div class="col-md-4"><label class="form-label">Fajr</label><input type="text" name="fajr" class="form-control" value="{{ $masjid->fajr }}"></div>
    <div class="col-md-4"><label class="form-label">Zuhr</label><input type="text" name="zuhr" class="form-control" value="{{ $masjid->zuhr }}"></div>
    <div class="col-md-4"><label class="form-label">Asr</label><input type="text" name="asr" class="form-control" value="{{ $masjid->asr }}"></div>
    <div class="col-md-4"><label class="form-label">Maghrib</label><input type="text" name="maghrib" class="form-control" value="{{ $masjid->maghrib }}"></div>
    <div class="col-md-4"><label class="form-label">Isha</label><input type="text" name="isha" class="form-control" value="{{ $masjid->isha }}"></div>
    <div class="col-md-4"><label class="form-label">Juma Time</label><input type="text" name="juma_time" class="form-control" value="{{ $masjid->juma_time }}"></div>
    <div class="col-md-4"><label class="form-label">Eid Time</label><input type="text" name="eid_time" class="form-control" value="{{ $masjid->eid_time }}"></div>
  </div>
  <button class="btn btn-primary mt-3"><i class="fas fa-save me-1"></i> Update Masjid</button>
</form>
@endsection
