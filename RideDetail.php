<?php
if (session_id() == '') {
    session_start();
}
if (!isset($_SESSION['usr'])) {
    header("Location: index.php");
    die();
}
require 'db.php';
if (isset($_REQUEST['id']) || isset($_REQUEST['rid'])) {
    if (isset($_REQUEST['id'])) {
        $id = $_REQUEST['id'];
        $t = "Upcoming Rides";
        $v = "upcoming";
    } elseif (isset($_REQUEST['rid'])) {
        $id = $_REQUEST['rid'];
        $t = "Recently Rides";
        $v = "recently";
    }
    $gettrip = mysqli_query($conn, "select t.*,v.*,vc.*,u.* from tbl_trips t,tbl_vehicle v,tbl_vehicle_category vc,tbl_users u where t.trip_id='$id' and t.trip_user_id=u.user_id and trip_vehicle_id=v.id and vehicle_cat_id=car_id ");

    $rowtrip = mysqli_fetch_array($gettrip);
    $routenm = '';
    $name = $rowtrip['user_name'];
    $email = $rowtrip['user_email'];
    $city = $rowtrip['user_city'];
    $country = $rowtrip['user_country'];
    $profileimg = $rowtrip['user_profile_img'];
    if ($profileimg == '')
        $profileimg = 'upload/noimage.png';
    else {
        $img1 = 'upload/profile/' . $profileimg;
        if (file_exists($img1))
            $profileimg = $img1;
        else
            $profileimg = 'upload/noimage.png';
    }
    $verify = $rowtrip['isverified'];
    if ($verify == "")
        $verify = "No";
    $id = $rowtrip['trip_id'];
    $source = $rowtrip['source'];
    $dest = $rowtrip['destination'];
    //Depature Date Time
    $dt = new DateTime($rowtrip['depature_date']);
    $dept_date = $dt->format('d M Y');
    $dept_time = Date('g:i a', strtotime($rowtrip['trip_depature_time']));
    $deptdt = $dept_date . ' ' . $dept_time;
    $triptype = $rowtrip['trip_type'];
    //Return Date Time
    $rt = $rowtrip['return_date'];
    if ($rt != '0000-00-00') {
        $dt1 = new DateTime($rt);
        $ret_date = $dt1->format('d M Y');
        $ret_time = Date('g:i a', strtotime($rowtrip['trip_return_time']));
        $retdt = $ret_date . ' ' . $ret_time;
    } else {
        $retdt = "";
    }
    $getroute = mysqli_query($conn, "select DISTINCT(source_leg) from tbl_t_trip_legs where trip_id='$id'");
    if (mysqli_num_rows($getroute)) {
        while ($r = mysqli_fetch_array($getroute)) {
            if ($rowtrip['destination'] != $r[0])
                $routenm = $routenm . " > " . $r[0];
        }
    }
    $routenm = ltrim($routenm, ' > ');

    $routenm = $routenm . " > " . $rowtrip['destination'];
    $seat = $rowtrip['trip_avilable_seat'];
    $passenger = $rowtrip['passenger_type'];
    $smoking = $rowtrip['smoking'];
    if ($smoking == "1")
        $smoke = "Smoking Allow";
    else {
        $smoke = "Smoking Not Allow";
    }
    $ac = $rowtrip['ac'];
    if ($ac = "1")
        $acst = "AC Avaliable";
    else {
        $acst = "AC is not Avaliable";
    }
    $extra = $rowtrip['extra'];
    $vehicle = $rowtrip['model'] . '-' . $rowtrip['car_name'] . '(' . $rowtrip['vehicle_type'] . ')';
    $rate = $rowtrip['trip_rate_details'];
    $vimg = $rowtrip['vehicle_image'];
    if ($vimg == '')
        $vimg = 'upload/noimage.png';
    else {
        $img = 'upload/vehicle/' . $vimg;
        if (file_exists($img))
            $vimg = $img;
        else
            $vimg = 'upload/noimage.png';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <?php include 'Header.php'; ?>
  
    <body>
  <input type="hidden" value="<?php echo $v; ?>" id="page_name">
        <?php include 'topnav.php'; ?>

        <div class="container-fluid">
            <div class="row">
                <?php include 'sidenav.php'; ?>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <h1 class="page-header">Recently Rides</h1>


                    <form class='form-horizontal' method="post" enctype="multipart/form-data">

                        <div class="col-md-12">
                            <div class="col-md-6">

                                <div class="form-group">
                                    <label class="control-label"><i class="fa fa-map-marker" style="color: green"></i> Source: <?php echo $source; ?></label>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"><i class="fa fa-map-marker" style="color: red"></i> Destination: <?php echo $dest; ?></label>
                                </div>
                                <?php
                                if ($triptype == "One Way")
                                    $sign = "fa fa-long-arrow-right ";

                                else if ($triptype == "Round Trip")
                                    $sign = "fa fa-exchange";
                                ?>
                                <div class="form-group">
                                    <label class="control-label"><i class="<?php echo $sign; ?>" style="color:blue"> </i> Type: <?php echo $triptype; ?></label>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"><i class="fa fa-calendar" style="color: green"></i> Depature Time: <?php echo $deptdt; ?></label>
                                </div>
                                <?php
                                if ($triptype != "One Way") {
                                    ?>
                                    <div class="form-group">
                                        <label class="control-label"><i class="fa fa-calendar" style="color: green"></i> Return Time: <?php echo $retdt; ?></label>
                                    </div>
                                    <?php
                                }
                                ?>
                                <div class="form-group">
                                    <label class="control-label">Rate : <?php echo $rate; ?> <i class="fa fa-rupee"></i> (Per Km)</label>
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Route: <?php echo $routenm; ?></label>
                                </div>
                                <table style="height: 100%;width: 100%;">
                                    <tr><th colspan="2"><h1 class="box-title">Vehicle Detail:</h1></th></tr>
                                    <tr><td rowspan="7">  <label class="control-label"><img src="<?php echo $vimg; ?>" style="max-height: 150px;max-width: 200px" class="img-rounded"></label></td></tr>
                                    <tr><td><label class="control-label"><i class="fa fa-car"></i> <?php echo $vehicle; ?></label></td></tr>
                                    <tr>  <td><label class="control-label"><?php echo $seat . " Seats Avaliable"; ?></label></td></tr>
                                    <tr> <td><label class="control-label"><?php echo $passenger . " Member Allow"; ?></label></td></tr>
                                    <tr> <td><label class="control-label"><?php echo $smoke; ?></label></td></tr>
                                    <tr><td><label class="control-label"><?php echo $acst; ?></label></td></tr>
                                    <?php
                                    if ($extra != "") {
                                        ?>
                                        <tr><td><?php echo $extra; ?></td></tr>
                                        <?php
                                    }
                                    ?>
                                </table>


                            </div>
                            <div class="col-md-6">
                                <table style="width: 100%;height: 100%">
                                    <tr>
                                        <th colspan="2"><h1 class="box-title">Rider Detail:</h1></th>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-user"></i> <label class="control-label">Rider Name: <?php echo $name; ?></label></td>
                                        <td rowspan="4"><img src="<?php echo $profileimg; ?>" style="max-height: 150px;max-width: 150px" class="img-circle"/></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-map-marker"></i> <label class="control-label"><?php echo $city . ',' . $country; ?></label></td>
                                    </tr>
                                    <tr>
                                        <td><i class="fa fa-envelope-o"></i> <label class="control-label"><?php echo $email; ?></label></td>
                                    </tr>
                                    <tr>
                                        <td> <label class="control-label">Verify: <?php echo $verify; ?></label></td>
                                    </tr>

                                </table>
                            </div>
                            <div class="row">
                                <div class="col-md-10">

                                    <?php
                                    $no = 1;
                                    $getmember = mysqli_query($conn, "select td.*,u.* from tbl_users u,tbl_trip_detail td where td.user_id=u.user_id and trip_id='$id'");
                                    if (mysqli_num_rows($getmember)) {
                                        ?>
                                        <br/>
                                        <div class="form-group">
                                            <h1 class="box-title">Ride Member Detail</h1>
                                        </div>
                                        <table id="tbtable" class="table table-responsive">
                                            <thead>
                                                <tr>
                                                    <th>Sr No.</th>
                                                    <th>Member Name</th>
                                                    <th>PickUp Point</th>
                                                    <th>Drop Point</th>
                                                    <th>Seats</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                while ($row = mysqli_fetch_array($getmember)) {
                                                    $nm = $row['user_name'];
                                                    $pick = $row['pickup_point'];
                                                    $drop = $row['drop_point'];
                                                    $seats = $row['seats'];
                                                    $st = $row['status'];
                                                    ?>
                                                    <tr>
                                                        <td><?php echo $no; ?></td>
                                                        <td><?php echo $nm; ?></td>
                                                        <td><?php echo $pick; ?></td>
                                                        <td><?php echo $drop; ?></td>
                                                        <td><?php echo $seats; ?></td>
                                                        <td><?php echo $st; ?></td>
                                                    </tr>
                                                    <?php
                                                    $no++;
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <?php
            mysqli_close($conn);
            include 'Footer.php';
            ?>

    </body>
</html>
