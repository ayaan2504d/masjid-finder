<?php
require_once '../config.php';
require_once ROOT_PATH.'/home/header.php';
?>

<link rel="stylesheet"
href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>

<div class="container py-5">

    <h2 class="mb-4">
        🗺️ Nearby Masjids Map
    </h2>

    <div id="map"
         style="height:600px;
         border-radius:20px;">
    </div>

</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>

var map = L.map('map').setView([24.8607,67.0011],13);

L.tileLayer(
'https://tile.openstreetmap.org/{z}/{x}/{y}.png',
{
maxZoom:19
}
).addTo(map);

L.marker([24.8607,67.0011])
.addTo(map)
.bindPopup('🕌 Masjid Noor');

L.marker([24.8650,67.0100])
.addTo(map)
.bindPopup('🕌 Jamia Masjid');

L.marker([24.8550,67.0150])
.addTo(map)
.bindPopup('🕌 Ali Masjid');

</script>

<?php
require_once ROOT_PATH.'/home/footer.php';
?>
```
