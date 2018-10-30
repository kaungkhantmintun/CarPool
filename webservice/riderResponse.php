<?php

$tripdetail_id = $_POST['tripdetail_id'];
$seat = $_POST['seat'];
$trip_id = $_POST['trip_id'];
$status = $_POST['status'];
$expectedtime = $_POST['expectedtime'];
$username =  $_POST['username'];
$triproute =  $_POST['triproute'];
require '../db.php';
require '../sendPushToUser.php';
require '../sendPushToRider.php';
$response = array();


if ($status == "Rereject") {
    $r1 = mysqli_query($conn, "update tbl_trips set trip_avilable_seat=trip_avilable_seat+$seat where trip_id='$trip_id'");
    if ($r1) {
        $result = mysqli_query($conn, "update tbl_trip_detail set status='Reject',pickup_time='' where id='$tripdetail_id'");
        if ($result) {
            //Send Push Notification
            $message_to = $username. " again rejected your request for route ".$triproute;
            $title_to = "Carpooling Service";
            send_push_torider('', $tripdetailid, $message_to, $title_to);
            $response['status'] = "success";
            $response['message'] = "Rejected...";
        }
    }
} else if ($status == "Reject") {
    $result = mysqli_query($conn, "update tbl_trip_detail set status='$status' where id='$tripdetail_id'");
    //Send Push Notification
    $message_to = $username. " rejected your request for route ".$triproute;
    $title_to = "Carpooling Service";
    send_push_torider('', $tripdetailid, $message_to, $title_to);
    $response['status'] = "success";
    $response['message'] = "Rejected...";
} else if ($status == "Accept") {
    $chkseats = mysqli_query($conn, "select * from tbl_trips where trip_id='$trip_id'");
    if (mysqli_num_rows($chkseats)) {
        $row = mysqli_fetch_array($chkseats);
        $avaliableseats = $row['trip_avilable_seat'];
        if ($avaliableseats > 0) {
            if ($avaliableseats >= $seat) {
                mysqli_query($conn, "update tbl_trip_detail set status='$status',pickup_time='$expectedtime' where id='$tripdetail_id'");
                mysqli_query($conn, "update tbl_trips set trip_avilable_seat=trip_avilable_seat-$seat,trip_status='FILLING' where trip_id='$trip_id'");
                $response['status'] = "success";
                $response['message'] = "Accepted...";
                
                //Send Push Notification
               $message_to = $username. " has accepted your request for route ".$triproute;
             
                $title_to = "Carpooling Service";
                send_push_torider('', $tripdetail_id, $message_to, $title_to);
                
            } else {
                $response['status'] = "fail";
                $response['message'] = $avaliableseats . " seats avaliable Only...";
            }
        } else {
            $response['status'] = "fail";
            $response['message'] = "Seat not avaliable...";
        }
    }
} else {
    $response['status'] = "fail";
    $response['message'] = "Invalid Status...";
}

echo json_encode($response);
mysqli_close($conn);
?>