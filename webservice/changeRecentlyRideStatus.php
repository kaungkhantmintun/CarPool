<?php

$trip_id = $_POST['trip_id'];
$status = $_POST['status'];
require '../db.php';
$response = array();

$updatestatus = mysqli_query($conn, "update tbl_trips set trip_status='$status' where trip_id='$trip_id'");
if ($updatestatus) {
    $response['status'] = "success";
    $response['message'] = "Status updated...";
} else {
    $response['status'] = "fail";
    $response['message'] = "status update fail...";
}
echo json_encode($response);
mysqli_close($conn);
?>

