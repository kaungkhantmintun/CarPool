<?php

$routedata = json_decode(file_get_contents("php://input"));

require '../db.php';
$response = array();
$data = array();
$source='';
$slat='';
$slong='';
$dest='';
$dlat='';
$dlong='';
$query = "insert into tbl_trips(trip_vehicle_id,source,source_lat,source_long,destination,destination_lat,destination_long,depature_date,trip_depature_time,return_date,trip_return_time,trip_type,trip_frequncy,trip_avilable_seat,passenger_type,trip_rate_details,trip_user_id,trip_status,ac,smoking,extra) " .
        "values('$routedata->vehicle_id','$routedata->source','$routedata->source_lat','$routedata->source_long','$routedata->destination','$routedata->dest_lat','$routedata->dest_long','$routedata->depature_date','$routedata->depature_time','$routedata->return_date','$routedata->return_time','$routedata->trip_type','$routedata->frequency','$routedata->seats','$routedata->passenger_type','$routedata->rate','$routedata->user_id','CREATED','$routedata->ac','$routedata->smoking','$routedata->extra')";

$result = mysqli_query($conn, $query);
$trip_id = mysqli_insert_id($conn);
if ($result) {
    $data['status'] = "success";
    $data['message'] = "Saved Successfully...";
   
    if (isset($routedata->route->route)) {
        
        $routes = $routedata->route->route;
        foreach ($routes as $data) {
            $rnm = $data->route_name;
            $rlat = $data->route_lat;
            $rlong = $data->route_long;
            
            $pnm=  explode("-",$rnm);
            $plat= explode("-",$rlat);
            $plong=  explode("-",$rlong);
            $source=$pnm[0];
            $dest=$pnm[1];
            $slat=$plat[0];
            $dlat=$plat[1];
            $slong=$plong[0];
            $dlong=$plong[1];
            $distance=  distance($slat,$slong,$dlat,$dlong,"K");
            $rate=$distance*$routedata->rate;
           $result1= mysqli_query($conn,"insert into tbl_t_trip_legs(source_leg,source_latitude,source_longitude,destination_leg,destination_latitude,destination_longitude,trip_id,route_rate,trip_return) values('$source','$slat','$slong','$dest','$dlat','$dlong','$trip_id','$rate','$data->route_type')");
           // $result1 = mysqli_query($conn, "insert into tbl_route(trip_id,route_name,route_lat,route_long) values('$trip_id','$rnm','$rlat','$rlong')");
        }
        if ($result1) :
   
            $data = array(
                'status' => 200,
                'message' => 'success'
            );
        else:
            $data = array(
                'status' => 418,
                'message' => 'fail'
            );
        endif;
        header("Content-type: application/json; charset=utf-8");
      
    }
      echo json_encode($data);
} else {
    $response['status'] = "fail";
    $response['message'] = "Failed to post...";
    echo json_encode($response);
}

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