@extends('layouts.public')

@section('title', 'Interactive Map')

@section('content')
<div class="container-fluid p-0">
  <div class="row g-0">
    <div class="col-lg-3 col-md-4 map-sidebar d-flex flex-column" id="mapSidebar">
      <div class="sidebar-header">
        <h5 class="fw-bold mb-1"><i class="fas fa-mosque me-1"></i> Nearby Masajid</h5>
        <p class="small mb-0 text-white-50">Select a masjid to locate it on the map.</p>
      </div>
      <div class="p-3 border-bottom bg-light">
        <div class="mb-3">
          <div class="input-group input-group-sm">
            <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
            <input type="text" id="mapSearch" class="form-control border-start-0" placeholder="Filter by name...">
          </div>
        </div>
        <div class="mb-2">
          <label class="form-label small fw-bold text-success mb-1">Search Radius</label>
          <select id="mapRadiusSelect" class="form-select form-select-sm">
            <option value="all">Show All Masajid</option>
            <option value="1">Within 1 KM</option>
            <option value="5">Within 5 KM</option>
            <option value="10">Within 10 KM</option>
          </select>
        </div>
        <div class="d-flex gap-1 mt-2">
          <button class="btn btn-sm btn-outline-success flex-grow-1 py-1 active" id="btnSectAll" onclick="updateMapSectFilter('all')">All</button>
          <button class="btn btn-sm btn-outline-success flex-grow-1 py-1" id="btnSectSunni" onclick="updateMapSectFilter('Sunni')">Sunni</button>
          <button class="btn btn-sm btn-outline-success flex-grow-1 py-1" id="btnSectShia" onclick="updateMapSectFilter('Shia')">Shia</button>
        </div>
      </div>
      <div class="flex-grow-1 overflow-y-auto" id="sidebarList">
        @forelse($masjids as $m)
          <div class="masjid-item" data-id="{{ $m->id }}" data-name="{{ strtolower($m->name) }}" data-sect="{{ $m->sect }}" data-lat="{{ $m->latitude }}" data-lng="{{ $m->longitude }}">
            <div class="d-flex justify-content-between align-items-start mb-1">
              <h6 class="fw-bold text-success mb-0 text-truncate" style="max-width:170px;">{{ $m->name }}</h6>
              <span class="badge badge-sect badge-{{ strtolower($m->sect) }} py-1" style="font-size:0.65rem;">{{ $m->sect }}</span>
            </div>
            <p class="small text-muted mb-1 text-truncate-2">{{ $m->address }}</p>
            <div class="d-flex justify-content-between align-items-center">
              <span class="badge bg-light text-secondary border small distance-span d-none">Calculating...</span>
              <span class="small text-success fw-bold"><i class="fas fa-clock"></i> Fajr: {{ $m->fajr }}</span>
            </div>
          </div>
        @empty
          <div class="p-4 text-center text-muted"><i class="fas fa-mosque fa-2x mb-2"></i><p>No masjids in database.</p></div>
        @endforelse
      </div>
    </div>
    <div class="col-lg-9 col-md-8 position-relative">
      <div class="map-fullscreen" id="fullMap"></div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const masjids = @json($masjids->values());
  const defaultLat = 24.8607;
  const defaultLng = 67.0011;
  let userCoords = null;
  let activeSect = 'all';

  MapHelper.init('fullMap', defaultLat, defaultLng, 12);
  MapHelper.addMasjidMarkers(masjids);

  const listItems = document.querySelectorAll('.masjid-item');
  listItems.forEach(item => {
    item.addEventListener('click', () => {
      const id = parseInt(item.dataset.id);
      const lat = parseFloat(item.dataset.lat);
      const lng = parseFloat(item.dataset.lng);
      MapHelper.map.setView([lat, lng], 15);
      MapHelper.markers.eachLayer(marker => {
        const popup = marker.getPopup();
        if (popup && popup.getContent().includes(`/masjids/${id}`)) {
          MapHelper.markers.zoomToShowLayer(marker, () => marker.openPopup());
        }
      });
    });
  });

  GPS.getLocation().then(pos => {
    userCoords = pos;
    MapHelper.addUserMarker(pos.lat, pos.lng);
    MapHelper.map.setView([pos.lat, pos.lng], 13);
    MapHelper.addMasjidMarkers(masjids, pos.lat, pos.lng);
    listItems.forEach(item => {
      const dist = haversineDistance(pos.lat, pos.lng, parseFloat(item.dataset.lat), parseFloat(item.dataset.lng));
      const distSpan = item.querySelector('.distance-span');
      distSpan.innerHTML = `<i class="fas fa-route"></i> ${dist} km`;
      distSpan.classList.remove('d-none');
      item.dataset.distance = dist;
    });
    sortListByDistance();
  }).catch(() => {});

  document.getElementById('mapSearch').addEventListener('input', filterListAndMarkers);
  document.getElementById('mapRadiusSelect').addEventListener('change', filterListAndMarkers);

  function filterListAndMarkers() {
    const query = document.getElementById('mapSearch').value.toLowerCase().trim();
    const radius = document.getElementById('mapRadiusSelect').value;
    let filteredMasjids = masjids;
    if (activeSect !== 'all') filteredMasjids = filteredMasjids.filter(m => m.sect === activeSect);
    if (query) filteredMasjids = filteredMasjids.filter(m => m.name.toLowerCase().includes(query) || m.address.toLowerCase().includes(query));
    if (radius !== 'all' && userCoords) filteredMasjids = filteredMasjids.filter(m => parseFloat(haversineDistance(userCoords.lat, userCoords.lng, m.latitude, m.longitude)) <= parseFloat(radius));
    MapHelper.addMasjidMarkers(filteredMasjids, userCoords?.lat, userCoords?.lng);
    listItems.forEach(item => {
      const id = parseInt(item.dataset.id);
      item.style.display = filteredMasjids.some(m => m.id === id) ? 'block' : 'none';
    });
  }

  window.updateMapSectFilter = function(sect) {
    activeSect = sect;
    document.getElementById('btnSectAll').classList.toggle('active', sect === 'all');
    document.getElementById('btnSectSunni').classList.toggle('active', sect === 'Sunni');
    document.getElementById('btnSectShia').classList.toggle('active', sect === 'Shia');
    filterListAndMarkers();
  };

  function sortListByDistance() {
    const listContainer = document.getElementById('sidebarList');
    Array.from(listItems).sort((a, b) => (parseFloat(a.dataset.distance) || Infinity) - (parseFloat(b.dataset.distance) || Infinity)).forEach(item => listContainer.appendChild(item));
  }
});
</script>
@endsection
