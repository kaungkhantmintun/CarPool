<?php

require '../db.php';
$vehicleid = $_POST['vehicle_id'];
$response = array();
$chkvehicle = mysqli_query($conn, "select count(*),v.* from tbl_trips t,tbl_vehicle v where trip_vehicle_id=v.id and trip_vehicle_id='$vehicleid' and (depature_date>=Now() or return_date>=Now())");
if (mysqli_num_rows($chkvehicle)) {
    $chkrow = mysqli_fetch_array($chkvehicle);
    $cntvehicle = $chkrow[0];
    if ($cntvehicle > 0) {
        $response['status'] = "success";
        $response['message'] = "You are posted Trip with this vehicle.So,vehicle not deleted.";
    } else {
        $vimg=$chkrow['vehicle_image'];
        $delvehicle = mysqli_query($conn, "delete from tbl_vehicle where id='$vehicleid'");
        if ($delvehicle) {
            if($vimg!='' && file_exists("../upload/vehicle/".$vimg))
                unlink ("../upload/vehicle/".$vimg);
            $response['status'] = "success";
            $response['message'] = "Vehicle Deleted Successfully Done...";
        } else {
            $response['status'] = "fail";
            $response['message'] = "Vehicle Delete Failed...";
        }
    }
}
echo json_encode($response);
mysqli_close($conn);
?>
