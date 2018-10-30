<?php
if (session_id() == '') {
    session_start();
}
if (!isset($_SESSION['usr'])) {
    header("Location: index.php");
    die();
}
require 'db.php';
$msg = '';
$errormsg = '';
?>
<!DOCTYPE html>
<html lang="en">
    <?php include 'header.php'; ?>
    <body>
        <input type="hidden" value="recently" id="page_name">
        <?php include 'topnav.php'; ?>

        <div class="container-fluid">
            <div class="row">
                <?php include 'sidenav.php'; ?>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <h1 class="page-header">Recently Rides</h1>

                    <form class="form-horizontal" method="POST">
                        <div class="col-md-12">
                            <?php
                            $no = 1;
                            $gettrip = mysqli_query($conn, "select t.*,v.*,vc.*,u.* from tbl_trips t,tbl_vehicle v,tbl_vehicle_category vc,tbl_users u where t.trip_user_id=u.user_id and trip_vehicle_id=v.id and vehicle_cat_id=car_id and trip_status!='CANCEL' and depature_date<Now() order by depature_date,trip_depature_time");
                            if (mysqli_num_rows($gettrip)) {
                                ?>
                                <table id="tbtable" class="table table-bordered table-striped table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Source</th>
                                            <th>Destination</th>
                                            <th>Depature DateTime</th>
                                            <th>Rider Name</th>
                                            <th>Trip Type</th>
                                            <th>View Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($rowtrip = mysqli_fetch_array($gettrip)) {
                                            $routenm = '';
                                            $name = $rowtrip['user_name'];
                                            $id = $rowtrip['trip_id'];
                                            $source = $rowtrip['source'];
                                            $dest = $rowtrip['destination'];
                                            //Depature Date Time
                                            $dt = new DateTime($rowtrip['depature_date']);
                                            $dept_date = $dt->format('d M Y');
                                            $dept_time = Date('g:i a', strtotime($rowtrip['trip_depature_time']));
                                            $deptdt = $dept_date . ' ' . $dept_time;
                                            $triptype = $rowtrip['trip_type'];
                                            ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $source; ?></td>
                                                <td><?php echo $dest; ?></td>
                                                <td><?php echo $deptdt; ?></td>
                                                <td><?php echo $name; ?></td>
                                                <td><?php echo $triptype; ?></td>
                                                <td><center><a href="RideDetail.php?rid=<?php echo $id; ?>"><i class="fa fa-edit"></i></a></center></td>
                                        </tr>
                                        <?php
                                        $no++;
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <?php
                            } else {
                                echo "<h3>No Recently Rides...</h3>";
                            }
                            ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <?php
        mysqli_close($conn);
        include 'footer.php';
        ?>

    </body>
</html>
