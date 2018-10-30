<?php

$trip_id = $_POST['trip_id'];

require '../db.php';
require '../sendPush.php';
$response = array();

$result = mysqli_query($conn, "update tbl_trips set trip_status='CANCEL' where trip_id='$trip_id'");
if ($result) {
    mysqli_query($conn, "update tbl_trip_detail set status='Cancel' where trip_id='$trip_id'");
    $response['status'] = "success";
    $response['message'] = "Cancel Successfully...";
    //Send Push Notification
    $message_to = "";
    $title_to = "Carpooling Service";
    send_push_touser('', $trip_id, $message_to, $title_to);
} else {
    $response['status'] = "fail";
    $response['message'] = "Cancel Failed...";
}
echo json_encode($response);
mysqli_close($conn);
?>

