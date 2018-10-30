<?php
if (session_id() == '') {
    session_start();
}
if (!isset($_SESSION['usr'])) {
    header("Location: index.php");
    die();
}
require 'db.php';
$ucnt = 0;
$upcomingcnt = 0;
$recentcnt = 0;
$getappuser = mysqli_query($conn, "select count(*) from tbl_users");
$r = mysqli_fetch_row($getappuser);
$ucnt = $r[0];
$getupcoming = mysqli_query($conn, "select count(*) from tbl_trips where trip_status!='CANCEL' and depature_date>=Now()");
$r1 = mysqli_fetch_array($getupcoming);
$upcomingcnt = $r1[0];
$getrecent = mysqli_query($conn, "select count(*) from tbl_trips where trip_status!='CANCEL' and depature_date<Now()");
$r2 = mysqli_fetch_row($getrecent);
$recentcnt = $r2[0];
?>
<!DOCTYPE html>
<html lang="en">
    <?php include 'header.php'; ?>

    <body>
        <input type="hidden" id="page_name" name="page_name" value="dashboard"/>
        <?php include 'topnav.php'; ?>

        <div class="container-fluid">
            <div class="row">
                <?php include 'sidenav.php'; ?>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <h1 class="page-header">Dashboard</h1>

                    <div class="row">
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-aqua-active">
                                <div class="inner">
                                    <h3><?php echo $ucnt; ?></h3>
                                    <p>No of App User</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-users"></i>
                                </div>
                                <a href="AppUser.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div><!-- ./col -->

                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-green-active">
                                <div class="inner">
                                    <h3><?php echo $upcomingcnt; ?></h3>
                                    <p>Upcoming Rides</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-car"></i>
                                </div>
                                <a href="UpcomingRides.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div><!-- ./col -->

                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-maroon-active">
                                <div class="inner">
                                    <h3><?php echo $recentcnt; ?></h3>
                                    <p>Recent Rides</p>
                                </div>
                                <div class="icon">
                                    <i class="fa fa-car"></i>
                                </div>
                                <a href="RecentlyRides.php" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                            </div>
                        </div><!-- ./col -->
                    </div>
                </div>
            </div>
        </div>
        <?php
        mysqli_close($conn);
        include 'footer.php';
        ?>
    </body>
</html>
