<?php

$userid = $_POST['user_id'];
require '../db.php';
$response = array();

$result = mysqli_query($conn, "select t.*,v.*,vc.* from tbl_trips t,tbl_vehicle v,tbl_vehicle_category vc where trip_vehicle_id=v.id and vehicle_cat_id=car_id and trip_user_id='$userid' and depature_date<Now() order by t.trip_id DESC");
if (mysqli_num_rows($result)) {
    $ride = array();
    while ($row = mysqli_fetch_array($result)) {
        $routenm = '';
        $id = $row['trip_id'];
        $ride['trip_id'] = $id;
        $ride['vehicle_id'] = $row['trip_vehicle_id'];
        $ride['vehicle'] = $row['model'] . '-' . $row['car_name'] . ' (' . $row['vehicle_type'] . ')';
        $ride['source'] = $row['source'];
        $ride['source_lat'] = $row['source_lat'];
        $ride['source_long'] = $row['source_long'];
        $ride['destination'] = $row['destination'];
        $ride['dest_lat'] = $row['destination_lat'];
        $ride['dest_long'] = $row['destination_long'];
        //Depature Date Time
        $dt = new DateTime($row['depature_date']);
        $dept_date = $dt->format('d M Y');
        $dept_time = Date('g:i a', strtotime($row['trip_depature_time']));
        $ride['dep_time'] = $dept_date . ' ' . $dept_time;

        //Return Date Time
        $rt = $row['return_date'];
        if ($rt != '0000-00-00') {
            $dt1 = new DateTime();
            $ret_date = $dt1->format('d M Y');
            $ret_time = Date('g:i a', strtotime($row['trip_return_time']));
            $ride['ret_time'] = $ret_date . ' ' . $ret_time;
        } else {
            $ride['ret_time'] = "";
        }
        $ride['trip_type'] = $row['trip_type'];
        $ride['trip_freq'] = $row['trip_frequncy'];
        $ride['seats'] = $row['trip_avilable_seat'];
        $ride['passenger_type'] = $row['passenger_type'];
        $ride['userid'] = $row['trip_user_id'];
        $ride['trip_status'] = $row['trip_status'];
        $ride['rate'] = $row['trip_rate_details'];
        $getroute = mysqli_query($conn, "select DISTINCT(source_leg) from tbl_t_trip_legs where trip_id='$id'");
        if (mysqli_num_rows($getroute)) {
            while ($r = mysqli_fetch_array($getroute)) {
                if($row['destination']!=$r[0])
                $routenm = $routenm . " > " . $r[0];
            }
        }
        $routenm = ltrim($routenm, ' > ');

        $ride['route'] = $routenm." > ".$row['destination'];
        $ride['smoking'] = $row['smoking'];
        $ride['ac'] = $row['ac'];
        $ride['extra'] = $row['extra'];
        array_push($response, $ride);
    }
}
echo json_encode($response);
mysqli_close($conn);
?>