<?php

$tripid = $_POST['trip_id'];
require '../db.php';
$response = array();
$qry = mysqli_query($conn, "update tbl_trips set trip_status='COMPLETE' where trip_id='$tripid' ");
if ($qry) {
    $response['status'] = "success";
    $response['message'] = "Trip status updated successfully Done...";
} else {
    $response['status'] = "fail";
    $response['message'] = "Trip status update fail...";
}

echo json_encode($response);
mysqli_close($conn);
?>