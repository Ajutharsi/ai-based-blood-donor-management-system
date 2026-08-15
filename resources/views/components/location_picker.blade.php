{{--
  Reusable lat/lng location picker: an interactive Leaflet + OpenStreetMap
  widget backed by two plain number inputs, so it degrades gracefully to
  manual entry if the map tiles or script fail to load. Include with:
    @include('components.location_picker', ['latitude' => $donor->latitude, 'longitude' => $donor->longitude])
  Leaving both blank is fully supported -- the map just centres on Sri
  Lanka with no marker until the user clicks, drags, or types a value.

  Self-contained styling (its own --loc-* scoped rules, not the host
  page's .field/.form-row classes) since donor and hospital profile pages
  use different class/variable naming conventions.
--}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
  .loc-picker{margin-bottom:1.5rem;}
  .loc-picker-title{font-size:0.7rem;font-weight:500;letter-spacing:0.1em;text-transform:uppercase;color:var(--primary,#1D4ED8);margin-bottom:0.6rem;padding-bottom:0.6rem;border-bottom:1px solid var(--border,rgba(29,78,216,0.12));}
  .loc-picker-help{font-size:0.78rem;color:var(--muted,#64748B);margin-bottom:0.75rem;line-height:1.5;}
  .loc-picker-map{height:280px;border-radius:10px;border:1px solid var(--gray-border,var(--gray-b,#E2E8F0));margin-bottom:0.75rem;}
  .loc-picker-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:0.6rem;}
  .loc-picker-field label{display:block;font-size:0.8rem;font-weight:500;color:var(--text,#1E293B);margin-bottom:6px;}
  .loc-picker-field input{width:100%;padding:0.6rem 0.85rem;border:1px solid var(--gray-border,var(--gray-b,#E2E8F0));border-radius:8px;font-family:'DM Sans',sans-serif;font-size:0.85rem;color:var(--text,#1E293B);background:white;outline:none;}
  .loc-picker-field input:focus{border-color:var(--primary,#1D4ED8);box-shadow:0 0 0 3px rgba(29,78,216,0.08);}
  .loc-picker-locate-btn{padding:0.55rem 1rem;border:1px solid var(--gray-border,var(--gray-b,#E2E8F0));border-radius:8px;background:white;font-family:'DM Sans',sans-serif;font-size:0.8rem;color:var(--primary,#1D4ED8);cursor:pointer;font-weight:500;}
  .loc-picker-locate-btn:hover{border-color:var(--primary,#1D4ED8);}
</style>

<div class="loc-picker">
  <div class="loc-picker-title">Location (Optional)</div>
  <div class="loc-picker-help">
    Click the map or drag the pin to set your location — this lets hospitals see real distance (not just district) when ranking matched donors for urgent requests. You can leave this blank.
  </div>
  <div id="locationMap" class="loc-picker-map"></div>
  <div class="loc-picker-row">
    <div class="loc-picker-field">
      <label>Latitude</label>
      <input type="number" step="any" min="-90" max="90" name="latitude" id="locLatInput" value="{{ old('latitude', $latitude ?? null) }}" placeholder="e.g. 6.9271">
    </div>
    <div class="loc-picker-field">
      <label>Longitude</label>
      <input type="number" step="any" min="-180" max="180" name="longitude" id="locLngInput" value="{{ old('longitude', $longitude ?? null) }}" placeholder="e.g. 79.8612">
    </div>
  </div>
  <button type="button" id="locUseMyLocationBtn" class="loc-picker-locate-btn">📍 Use My Current Location</button>
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
(function () {
  var latInput = document.getElementById('locLatInput');
  var lngInput = document.getElementById('locLngInput');

  var initialLat = parseFloat(latInput.value);
  var initialLng = parseFloat(lngInput.value);
  var hasInitial = !isNaN(initialLat) && !isNaN(initialLng);

  // Sri Lanka's approximate centre, used only as the map's starting view
  // when no location has been set yet -- never written into the inputs.
  var viewLat = hasInitial ? initialLat : 7.8731;
  var viewLng = hasInitial ? initialLng : 80.7718;

  var map = L.map('locationMap').setView([viewLat, viewLng], hasInitial ? 12 : 7);
  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
    maxZoom: 18,
  }).addTo(map);

  var marker = hasInitial ? L.marker([initialLat, initialLng], { draggable: true }) : null;
  if (marker) {
    marker.addTo(map);
    marker.on('dragend', function (e) {
      applyLatLng(e.target.getLatLng().lat, e.target.getLatLng().lng);
    });
  }

  function applyLatLng(lat, lng) {
    if (!marker) {
      marker = L.marker([lat, lng], { draggable: true }).addTo(map);
      marker.on('dragend', function (e) {
        applyLatLng(e.target.getLatLng().lat, e.target.getLatLng().lng);
      });
    } else {
      marker.setLatLng([lat, lng]);
    }
    latInput.value = lat.toFixed(7);
    lngInput.value = lng.toFixed(7);
  }

  map.on('click', function (e) {
    applyLatLng(e.latlng.lat, e.latlng.lng);
  });

  document.getElementById('locUseMyLocationBtn').addEventListener('click', function () {
    if (!navigator.geolocation) {
      alert('Geolocation is not supported by your browser. Enter coordinates manually instead.');
      return;
    }
    navigator.geolocation.getCurrentPosition(function (pos) {
      map.setView([pos.coords.latitude, pos.coords.longitude], 14);
      applyLatLng(pos.coords.latitude, pos.coords.longitude);
    }, function () {
      alert('Unable to retrieve your location. Enter coordinates manually instead.');
    });
  });

  [latInput, lngInput].forEach(function (input) {
    input.addEventListener('change', function () {
      var lat = parseFloat(latInput.value);
      var lng = parseFloat(lngInput.value);
      if (!isNaN(lat) && !isNaN(lng)) {
        map.setView([lat, lng], 14);
        applyLatLng(lat, lng);
      }
    });
  });
})();
</script>
