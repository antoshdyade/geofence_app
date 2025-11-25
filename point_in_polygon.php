<?php
function pointInsidePolygon($point, $polygon) {
    $x = $point['lng']; 
    $y = $point['lat'];
    $inside = false;
    $n = count($polygon);
    for ($i = 0, $j = $n - 1; $i < $n; $j = $i++) {
        $xi = $polygon[$i][0]; $yi = $polygon[$i][1];
        $xj = $polygon[$j][0]; $yj = $polygon[$j][1];
        $intersect = (($yi > $y) != ($yj > $y))
            && ($x < ($xj - $xi) * ($y - $yi) / ($yj - $yi + 1e-10) + $xi);
        if ($intersect) $inside = !$inside;
    }
    return $inside;
}
?>
