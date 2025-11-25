<?php
session_start();
if (!isset($_SESSION['admin'])) { echo json_encode(["error"=>"Unauthorized"]); exit; }

$conn = new mysqli("localhost","root","","geo_fencing");
$data = json_decode(file_get_contents("php://input"), true);

$name = $data["name"];
$polygon_geojson = json_encode($data["polygon"]);

$conn->query("INSERT INTO geofences(name,fence_type,polygon_geojson)
              VALUES('$name','restricted','$polygon_geojson')");

echo json_encode(["id" => $conn->insert_id]);
?>
