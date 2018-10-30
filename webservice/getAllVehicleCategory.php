<?php

require '../db.php';

$response = array();
$getcat = mysqli_query($conn, "select * from tbl_vehicle_category order by car_id");
if (mysqli_num_rows($getcat)) {
    
    $category = array();
    while ($rowcat = mysqli_fetch_array($getcat)) {
        $category['categoryid']=$rowcat['car_id'];
        $category['categorynm'] = $rowcat['car_name'];
        array_push($response, $category);
    }
}
echo json_encode($response);
mysqli_close($conn);
?>
