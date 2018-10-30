<?php

$tripid=$_POST['tripid'];

$response=array();
require '../db.php';
$data=array();
$getmember=  mysqli_query($conn,"select * from tbl_trip_detail td,tbl_users u where td.user_id=u.user_id and trip_id='$tripid' and td.status='Accept'");
if(mysqli_num_rows($getmember)){
    while ($get=  mysqli_fetch_array($getmember)){
        $data['tripdetailid']=$get['id'];
        $data['tripid']=$tripid;
        $data['memberid']=$get['user_id'];
        $data['membernm']=$get['user_name'];
        $data['membermob']=$get['user_mobile'];
        $data['pickup']=$get['pickup_point'];
        $data['drop']=$get['drop_point'];
        $data['pickuptime']=$get['pickup_time'];
        $data['seat']=$get['seats'];
        array_push($response,$data);
    }
}
echo json_encode($response);
mysqli_close($conn);
?>