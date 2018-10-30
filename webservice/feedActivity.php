<?php

$response = array();
require '../db.php';

$qry = "select u.*,v.*,vc.*,t.* from tbl_trips t,tbl_vehicle v,tbl_vehicle_category vc,tbl_users u where trip_user_id=u.user_id and trip_vehicle_id=v.id and vehicle_cat_id=car_id and trip_user_id>0 and trip_avilable_seat>0 and `depature_date`>=CURDATE() order by trip_created_date DESC limit 20";

$result = mysqli_query($conn, $qry);

if (mysqli_num_rows($result)) {

    $ride = array();
    while ($row = mysqli_fetch_array($result)) {

        $id = $row['trip_id'];
        $ride['trip_id'] = $id;
        $ride['vehicle_id'] = $row['trip_vehicle_id'];
        $ride['vehicle'] = $row['model'] . '-' . $row['car_name'] . '(' . $row['vehicle_type'] . ')';
        $ride['vehicleimg'] = $row['vehicle_image'];
        $ride['trip_type'] = $row['trip_type'];
        $ride['seats'] = $row['trip_avilable_seat'];
        $ride['passenger_type'] = $row['passenger_type'];
        $ride['userid'] = $row['trip_user_id'];
        $ride['userimg'] = $row['user_profile_img'];
        $ride['trip_status'] = $row['trip_status'];
        $ride['rate'] = $row['trip_rate_details']."&#x20B9"."/Km";
        //Depature Date Time
        $dt = new DateTime($row['depature_date']);
        $dept_date = $dt->format('d M Y');
        $dept_time = Date('g:i a', strtotime($row['trip_depature_time']));

        $routenm = '';
        $rlat = '';
        $rlong = '';
        $getroute = mysqli_query($conn, "select DISTINCT(source_leg),source_latitude as lat,source_longitude as lon from tbl_t_trip_legs where trip_id='$id'");
        if (mysqli_num_rows($getroute)) {

            $ride['source'] = $row['source'];
            $ride['source_lat'] = $row['source_lat'];
            $ride['source_long'] = $row['source_long'];
            $ride['destination'] = $row['destination'];
            $ride['dest_lat'] = $row['destination_lat'];
            $ride['dest_long'] = $row['destination_long'];
            $ride['dep_time'] = $dept_date . ' ' . $dept_time;

            //Return Date Time
            $rt = $row['return_date'];
            if ($rt != '0000-00-00') {
                $dt1 = new DateTime($rt);
                $ret_date = $dt1->format('d M Y');
                $ret_time = Date('g:i a', strtotime($row['trip_return_time']));
                $ride['ret_time'] = $ret_date . ' ' . $ret_time;
            } else {
                $ride['ret_time'] = "";
            }
            while ($r = mysqli_fetch_array($getroute)) {
                if ($r[0] != $row['destination']) {
                    $routenm = $routenm . " > " . $r[0];
                    $rlat = $rlat . " > " . $r['lat'];
                    $rlong = $rlong . " > " . $r['lon'];
                }
            }
            $routenm = ltrim($routenm, ' > ');
            $rlat = ltrim($rlat, ' > ');
            $rlong = ltrim($rlong, ' > ');
            $ride['route'] = $routenm . ' > ' . $row['destination'];
            $ride['routelat'] = $rlat . ' > ' . $row['destination_lat'];
            $ride['routelong'] = $rlong . ' > ' . $row['destination_long'];
        }
        $ride['smoking'] = $row['smoking'];
        $ride['ac'] = $row['ac'];
        $ride['extra'] = $row['extra'];

        array_push($response, $ride);
    }
}
echo json_encode($response);

mysqli_close($conn);
?>