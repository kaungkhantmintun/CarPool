<?php
$username= $_POST['username'];
$userid = $_POST['user_id'];
$tripid = $_POST['trip_id'];
$tripdepturetime=$_POST['trip_depature_time'];
$riderid = $_POST['rider_id'];
$pickup = $_POST['pickup_point'];
$drop = $_POST['drop_point'];
$seats=$_POST['seats'];
$cmt = $_POST['comment'];
$status = "Pending";
$dt= date('Y-m-d H:i:s',strtotime($tripdepturetime));

$response = array();
require '../db.php';
require '../sendPushForRequest.php';
$result = mysqli_query($conn, "insert into tbl_trip_detail(trip_id,user_id,rider_id,pickup_point,drop_point,seats,comment,status,trip_depature_time) values('$tripid','$userid','$riderid','$pickup','$drop','$seats','$cmt','$status','$dt')");

if ($result) {
 
     //Send Push Notification
    $message_to = "You have recieved new request from ". $username. " for route ".$pickup." to ".$drop;
    $title_to = "Carpooling Service";
    send_push_torider('', $riderid, $message_to, $title_to);
      $response['status'] = "success";
    $response['message'] = "Send Request Successfully Done..."; 
   
} else {
    $response['status'] = "fail";
    $response['message'] = "Send Request Failed...";
}
echo json_encode($response);
mysqli_close($conn);
?>