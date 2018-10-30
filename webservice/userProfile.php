<?php

$userid = $_POST['user_id'];
$response = array();
require '../db.php';
//user data
$getuserdata = mysqli_query($conn, "select * from tbl_users where user_id='$userid'");
if (mysqli_num_rows($getuserdata)) {
    $rowuser = mysqli_fetch_array($getuserdata);
    $response['username'] = $rowuser['user_name'];
	 $response['user_city'] = $rowuser['user_city'];
	  $response['user_country'] = $rowuser['user_country'];
	   $response['user_gender'] = $rowuser['user_gender'];
	     $response['membersince'] = $rowuser['membersince'];
		      $response['dob'] = $rowuser['dob'];
    $response['profile_img']=$rowuser['user_profile_img'];
    $response['verify']=$rowuser['isverified'];
}
//total post ride(offer ride)
$getpostride = mysqli_query($conn, "select count(trip_id) as postcnt from tbl_trips where trip_user_id='$userid'");
if (mysqli_num_rows($getpostride)) {
    $rowpost = mysqli_fetch_array($getpostride);
    $response['postridecnt'] = $rowpost['postcnt'];
} else {
    $response['postridecnt'] = 0;
}

//total take ride
$takeride = mysqli_query($conn, "select count(id) as takecnt from tbl_trip_detail where user_id='$userid' and Status='Accept'");
if (mysqli_num_rows($takeride)) {
  $rowtake=  mysqli_fetch_array($takeride);
  $response['takeridecnt']=$rowtake['takecnt'];
} else {
    $response['takeridecnt'] = 0;
}
echo json_encode($response);
mysqli_close($conn);
?>

