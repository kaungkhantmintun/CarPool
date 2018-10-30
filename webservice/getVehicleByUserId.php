<?php

$response = array();
require '../db.php';
if (isset($_POST['user_id']))
{
    $userid=$_POST['user_id'];
    $result = mysqli_query($conn, "select v.*,vc.* from tbl_vehicle v,tbl_vehicle_category vc where user_id='$userid' and vehicle_cat_id=car_id");
}
if (isset($_POST['vehicle_id'])){
    $vehicleid=$_POST['vehicle_id'];
    $result = mysqli_query($conn, "select v.*,vc.* from tbl_vehicle v,tbl_vehicle_category vc where id='$vehicleid' and vehicle_cat_id=car_id");
}
if ($result) {
    $vehicle = array();
    while ($row = mysqli_fetch_array($result)) {
        $vehicle['vehicle_id'] = $row['id'];
        $vehicle['company'] = $row['car_name'];
        $vehicle['model'] = $row['model'];
        $vehicle['number'] = $row['vehicle_number'];
        $vehicle['image'] = $row['vehicle_image'];
        $vehicle['seats'] = $row['avaliable_seats'];
        $vehicle['type'] = $row['vehicle_type'];
       
        array_push($response, $vehicle);
    }
    echo json_encode($response);
}
mysqli_close($conn);
?>
