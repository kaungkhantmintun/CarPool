<?php

$pickuplat = $_POST['pickup_lat'];
$pickuplong = $_POST['pickup_long'];
$droplat = $_POST['drop_lat'];
$droplong = $_POST['drop_long'];
$onlyfemale = $_POST['onlyfemale'];
$numtype = $_POST['numtype'];
$dt = $_POST['seldate'];
$radius = 5;
$response = array();
require '../db.php';

$distance = distance($pickuplat, $pickuplong, $droplat, $droplong, "K");

if ($distance >= 0 && $distance <= 5)
    $radius = 1;
else if ($distance >= 6 && $distance <= 10)
    $radius = 2;
else if ($distance >= 11 && $distance <= 50)
    $radius = 5;
else if ($radius >= 51 && $distance <= 100)
    $radius = 10;
else if ($radius >= 101 && $distance <= 1000)
    $radius = 30;
else
    $radius = 10;
//km=6380,miles=3959
$filter_gender = "";
$filter_oddeven = "";
$filter_date = "";
if ($onlyfemale == 'yes') {
    $filter_gender = " and user_gender='female' ";
}
if ($numtype == 'Odd') {
    $filter_oddeven = " and num%2=0";
} else if ($numtype == 'Even') {
    $filter_oddeven = " and num%2>0";
}
if ($dt != "") {
    $filter_date = " and (`depature_date`='$dt' or `return_date`='$dt')";
}
//$qry = "select u.*,v.*,vc.*,t.*,r.*,CONCAT(depature_date,' ',trip_depature_time) as dt,CONCAT(return_date,' ',trip_return_time) as retdt,ACOS(SIN(RADIANS(source_latitude)) * SIN(RADIANS('$pickuplat'))+COS(RADIANS(source_latitude))*COS(RADIANS('$pickuplat')) * COS(RADIANS(source_longitude) - RADIANS('$pickuplong' )) ) * 6380 AS source_distance,ACOS(SIN(RADIANS(destination_latitude)) * SIN(RADIANS('$droplat'))+COS(RADIANS(destination_latitude))*COS(RADIANS('$droplat')) * COS(RADIANS(destination_longitude) - RADIANS('$droplong' )) ) * 6380 AS destination_distance from tbl_t_trip_legs r,tbl_trips t,tbl_vehicle v,tbl_vehicle_category vc,tbl_users u where trip_user_id=u.user_id and trip_vehicle_id=v.id and vehicle_cat_id=car_id and r.trip_id=t.trip_id and trip_user_id>0 and trip_avilable_seat>0 HAVING (dt>NOW() or retdt>NOW()) and source_distance <$radius AND destination_distance <$radius order by dt";
$qry = "select right(`vehicle_number`,1) as num,u.*,v.*,vc.*,t.*,r.*,CONCAT(depature_date,' ',trip_depature_time) as dt,CONCAT(return_date,' ',trip_return_time) as retdt,ACOS(SIN(RADIANS(source_latitude)) * SIN(RADIANS('$pickuplat'))+COS(RADIANS(source_latitude))*COS(RADIANS('$pickuplat')) * COS(RADIANS(source_longitude) - RADIANS('$pickuplong' )) ) * 6380 AS source_distance,ACOS(SIN(RADIANS(destination_latitude)) * SIN(RADIANS('$droplat'))+COS(RADIANS(destination_latitude))*COS(RADIANS('$droplat')) * COS(RADIANS(destination_longitude) - RADIANS('$droplong' )) ) * 6380 AS destination_distance from tbl_t_trip_legs r,tbl_trips t,tbl_vehicle v,tbl_vehicle_category vc,tbl_users u where trip_user_id=u.user_id and trip_vehicle_id=v.id and vehicle_cat_id=car_id and r.trip_id=t.trip_id and trip_user_id>0 and trip_avilable_seat>0 $filter_gender $filter_date HAVING (dt>=NOW() or retdt>NOW()) and source_distance <$radius AND destination_distance <$radius $filter_oddeven order by dt";

//echo $qry;
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
        $ride['rate'] = $row['route_rate']."&#x20B9;"."\n(Per Seat)";
        //Depature Date Time
        $dt = new DateTime($row['depature_date']);
        $dept_date = $dt->format('d M Y');
        $dept_time = Date('g:i a', strtotime($row['trip_depature_time']));

        $routenm = '';
        $rlat = '';
        $rlong = '';
        $getroute = mysqli_query($conn, "select DISTINCT(source_leg),source_latitude as lat,source_longitude as lon from tbl_t_trip_legs where trip_id='$id'");
        if (mysqli_num_rows($getroute)) {
            $tripreturn = $row['trip_return'];
            $ride['return'] = $tripreturn;
            if ($tripreturn == 0) {
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
            } elseif ($tripreturn == 1) {
                $ride['source'] = $row['destination'];
                $ride['source_lat'] = $row['destination_lat'];
                $ride['source_long'] = $row['destination_long'];
                $ride['destination'] = $row['source'];
                $ride['dest_lat'] = $row['source_lat'];
                $ride['dest_long'] = $row['source_long'];
                $rt = $row['return_date'];
                if ($rt != '0000-00-00') {
                    $dt1 = new DateTime($rt);
                    $ret_date = $dt1->format('d M Y');
                    $ret_time = Date('g:i a', strtotime($row['trip_return_time']));
                    $ride['dep_time'] = $ret_date . ' ' . $ret_time;
                } else {
                    $ride['dep_time'] = "";
                }
                $ride['ret_time'] = "";
                while ($r = mysqli_fetch_array($getroute)) {
                    if ($r[0] != $row['source']) {
                        $routenm = $r[0] . ' > ' . $routenm;
                        $rlat = $r['lat'] . ' > ' . $rlat;
                        $rlong = $r['lon'] . ' > ' . $rlong;
                    }
                }
                $ride['route'] = $routenm . $row['source'];
                $ride['routelat'] = $rlat . $row['source_lat'];
                $ride['routelong'] = $rlong . $row['source_long'];
            }
        }
        $ride['smoking'] = $row['smoking'];
        $ride['ac'] = $row['ac'];
        $ride['extra'] = $row['extra'];

        array_push($response, $ride);
    }
}
echo json_encode($response);

function distance($lat1, $lon1, $lat2, $lon2, $unit) {
    $theta = $lon1 - $lon2;
    $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
    $dist = acos($dist);
    $dist = rad2deg($dist);
    $miles = $dist * 60 * 1.1515;
    $unit = strtoupper($unit);
    if ($unit == "K") {
        $distance = round(($miles * 1.609344));
        if ((string) $distance == "NAN") {
            $distance = 0;
        }
        return $distance;
    } else if ($unit == "N") {
        return ($miles * 0.8684);
    } else {
        return $miles;
    }
}

mysqli_close($conn);
?>