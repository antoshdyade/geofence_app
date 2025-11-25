<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost","root","","geo_fencing");

// Fetch all geofences
$res = $conn->query("SELECT * FROM geofences");
$geofences = [];
while($r = $res->fetch_assoc()) $geofences[] = $r;
?>
<!DOCTYPE html>
<html>
<head>
<title>Geofencing Dashboard</title>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.css">

<style>
body { margin:0; font-family: Arial, sans-serif; background:#ecf0f1; }
header {
    background:#2c3e50;
    padding:15px;
    color:white;
    font-size:22px;
}
.top-bar {
    background:#34495e;
    padding:10px;
    display:flex;
    gap:12px;
}
.top-bar button {
    padding:8px 14px;
    border:none;
    cursor:pointer;
    font-size:15px;
    border-radius:4px;
    font-weight:bold;
}
.top-bar .green { background:#27ae60; color:white; }
.top-bar .red { background:#e74c3c; color:white; }
#map { height: 90vh; }
footer {
    text-align:center;
    background:#2c3e50;
    padding:8px;
    font-size:14px;
    color:white;
}
.delete-btn {
    padding:5px 10px;
    background:#e74c3c;
    color:white;
    border:none;
    border-radius:4px;
    cursor:pointer;
    font-size:13px;
    margin-top:5px;
}
</style>
</head>
<body>

<header>🔐 Geofencing Admin Dashboard</header>

<div class="top-bar">

    <?php if ($_SESSION['role'] === 'admin') { ?>
        <button class="green" onclick="location.href='manage_users.php'">➕ Add User</button>
    <?php } ?>

    <button class="red" onclick="location.href='logout.php'">⏻ Logout</button>
</div>

<div id="map"></div>

<footer>© Geofence App – Authorized Access Only</footer>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw@1.0.4/dist/leaflet.draw.js"></script>

<script>
let map = L.map('map').setView([20.59, 78.96], 5);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
}).addTo(map);

let drawnItems = new L.FeatureGroup();
map.addLayer(drawnItems);

// Load existing geofences
<?php foreach($geofences as $f) { ?>
var layer<?= $f['id'] ?> = L.geoJSON(<?= $f['polygon_geojson'] ?>).addTo(map)
.bindPopup(`
    <b><?= $f['name'] ?></b><br><br>

    <?php if ($_SESSION['role'] === 'admin') { ?>
        <button class="delete-btn" onclick="deleteGeofence(<?= $f['id'] ?>)">🗑 Delete</button>
    <?php } ?>
`);
<?php } ?>

// Drawing control
let drawControl = new L.Control.Draw({
    draw: { polygon:true, rectangle:true, marker:false, polyline:false, circle:false, circlemarker:false },
    edit: { featureGroup: drawnItems }
});
map.addControl(drawControl);

// Save new geofence
map.on(L.Draw.Event.CREATED, function(e){
    let layer = e.layer;
    drawnItems.addLayer(layer);

    let geojson = layer.toGeoJSON();
    let name = prompt("Enter geofence name:");
    if (!name) return;

    fetch("save_geofence.php", {
        method:"POST",
        headers:{ "Content-Type":"application/json" },
        body:JSON.stringify({ name:name, polygon: geojson })
    })
    .then(r => r.json())
    .then(d => alert("Geofence saved successfully! ID = " + d.id));
});

// Delete geofence
function deleteGeofence(id) {
    if (!confirm("Are you sure you want to delete this geofence?")) return;

    fetch("delete_geofence.php?id=" + id, { method:"GET" })
    .then(r => r.json())
    .then(res => {
        if (res.status === "success") {
            alert("Geofence deleted successfully!");
            location.reload();
        } else {
            alert("Error: " + res.message);
        }
    });
}
</script>

</body>
</html>
