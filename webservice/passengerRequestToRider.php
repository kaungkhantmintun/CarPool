<?php

$userid=$_POST['user_id'];

$response=array();
require '../db.php';

$result=  mysqli_query($conn,"select td.user_id as uid,td.*,u.*,t.* from tbl_trips t,tbl_trip_detail td,tbl_users u where td.user_id=u.user_id and td.trip_id=t.trip_id and rider_id='$userid' and td.status='Pending'");
if(mysqli_num_rows($result)){
    $req=array();
    while ($row = mysqli_fetch_array($result)) {
        $dt=new DateTime($row['depature_date']);
        $dept_date= $dt->format('d M Y');
        $dept_time=Date('g:i a',  strtotime($row['trip_depature_time']));
        $req['deptdate']=$dept_date;
        $req['depttime']=$dept_time;
        $req['tripdetail_id']=$row['id'];
        $req['trip_id']=$row['trip_id'];
        $req['userid']=$row['uid'];
        $req['username']=$row['user_name'];
        $req['pickup_point']=$row['pickup_point'];
        $req['drop_point']=$row['drop_point'];
        $req['seats']=$row['seats'];
        $req['comment']=$row['comment'];
        array_push($response,$req);
    }
}
echo json_encode($response);
mysqli_close($conn);
