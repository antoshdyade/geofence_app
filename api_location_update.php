<?php
$conn = new mysqli("localhost","root","","geo_fencing");
include "point_in_polygon.php";

$data = json_decode(file_get_contents("php://input"), true);
$user = $data['user_id'];
$lat = $data['lat'];
$lng = $data['lng'];

$conn->query("INSERT INTO locations(user_id, latitude, longitude) VALUES('$user',$lat,$lng)");
$loc_id = $conn->insert_id;

$inside = [];
$res = $conn->query("SELECT * FROM geofences");
while($g = $res->fetch_assoc()){
  $poly = json_decode($g['polygon_geojson'], true);
  $coords = $poly["geometry"]["coordinates"][0];
  if(pointInsidePolygon(["lat"=>$lat, "lng"=>$lng], $coords)){
      $inside[] = $g["id"];
      $conn->query("INSERT INTO geofence_events(user_id,geofence_id,event_type) VALUES('$user',{$g['id']},'inside')");
  }
}

echo json_encode(["status"=>"ok","inside_geofences"=>$inside]);
?>
