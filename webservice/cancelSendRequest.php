<?php

$tripdetailid = $_POST['tripdetail_id'];
$status = $_POST['status'];
$username = $_POST['username'];
$triproute= $_POST['triproute'];

$response = array();
require '../db.php';
require '../sendPushToRider.php';
if ($status == "Pending") {
    $result = mysqli_query($conn, "update tbl_trip_detail set status='Cancel' where id='$tripdetailid'");
    if ($result) {
        $response['status'] = "success";
        $response['message'] = "Send Request Cancelled...";
		 $message_to = $username. " has cancelled the request for route ".$triproute;
            $title_to = "Carpooling Service";
            send_push_torider('', $tripdetailid, $message_to, $title_to);
    } else {
        $response['status'] = "Fail";
        $response['message'] = "Send Request Cancellation Failed...";
    }
} else if ($status == "Accept") {
    $result = mysqli_query($conn, "update tbl_trip_detail set status='Cancel',pickup_time='' where id='$tripdetailid'");
    if ($result) {
        $getid = mysqli_query($conn, "select * from tbl_trip_detail where id='$tripdetailid'");
        $row = mysqli_fetch_array($getid);
        $trip_id = $row['trip_id'];
        $seat = $row['seats'];

        $r = mysqli_query($conn, "update tbl_trips set trip_avilable_seat=trip_avilable_seat+$seat where trip_id='$trip_id'");
        if ($r) {
            $response['status'] = "success";
            $response['message'] = "Send Request Cancelled...";
            //Send Push Notification
            $message_to = $username. " has cancelled the request for route ".$triproute;
            $title_to = "Carpooling Service";
            send_push_torider('', $tripdetailid, $message_to, $title_to);
        }
    } else {
        $response['status'] = "Fail";
        $response['message'] = "Send Request Cancellation Failed...";
    }
} else {
    $response['status'] = "Fail";
    $response['message'] = "Send Request Cancellation Failed...";
}
echo json_encode($response);
mysqli_close($conn);
?>
