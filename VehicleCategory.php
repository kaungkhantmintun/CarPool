<?php
if (session_id() == '') {
    session_start();
}
if (!isset($_SESSION['usr'])) {
    header("Location: index.php");
    die();
}
require 'db.php';

$nm = '';
$msg = '';
$errormsg = '';
$id = 0;
//Fetch Data
if (isset($_REQUEST['eid'])) {
    $id = $_REQUEST['eid'];
    $getnm = mysqli_query($conn, "select car_id,car_name from tbl_vehicle_category where car_id='$id'");
    $rownm = mysqli_fetch_array($getnm);
    $nm = $rownm['car_name'];
}

if (isset($_POST['btnsubmit'])) {
    $nm = $_POST['name'];
    if ($id > 0 || isset($_REQUEST['eid'])) {
        $id = $_REQUEST['eid'];
        $result = mysqli_query($conn, "update tbl_vehicle_category set car_name='$nm' where car_id='$id'");
        if ($result)
            $msg = "Updated successfully...";
        else
            $errormsg = "Updated Failed...";
    } else {
        $result = mysqli_query($conn, "insert into tbl_vehicle_category(car_name) values('$nm')");
        if ($result)
            $msg = "Inserted successfully...";
        else
            $errormsg = "Inserted Failed...";
    }
}
//delete
if (isset($_REQUEST['did'])) {
    $id = $_REQUEST['did'];
    $result = mysqli_query($conn, "delete from tbl_vehicle_category where car_id='$id'");
    if ($result)
        $msg = "Deleted Successfully...";
    else
        $errormsg = "Deleted Failed...";
}
?>
<!DOCTYPE html>
<html lang="en">
    <?php include 'header.php'; ?>
   
    <body>
 <input type="hidden" value="category" id="page_name">
        <?php include 'topnav.php'; ?>

        <div class="container-fluid">
            <div class="row">
                <?php include 'sidenav.php'; ?>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <h1 class="page-header">Vehicle Category</h1>

                    <form class="form-horizontal" method="POST">
                        <div class="col-lg-12">

                            <?php
                            if ($msg != '') {
                                echo '<div class="input-group has-success">';
                                echo '<label id="msg"  class="control-label" for="inputSuccess"><i class="fa fa-check"></i>' . $msg . '</label>';
                                echo '</div><br/>';
                            } if ($errormsg != '') {
                                echo '<div class="input-group has-error">';
                                echo '<label id="errormsg"  class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i>' . $errormsg . '</label>';
                                echo '</div><br/>';
                            }
                            ?>
                            <div class="form-group">
                                <label class="control-label col-sm-2">Company Name:</label>
                                <div class="col-sm-4">
                                    <input type="text" id="name" name="name" class="form-control" value="<?php echo $nm; ?>" placeholder="Enter Vehicle Company Name" required/>
                                </div>
                            </div>
                            <div class="form-group">
                                <center><input type="submit" id="btnsubmit" name="btnsubmit" value="Save" class="btn btn-primary"/></center>
                            </div>
                        </div>


                        <div class="col-lg-12">
                            <h1 class="page-header">View Vehicle Category Detail</h1>
                            <div class="col-lg-11">
                                <?php
                                $no = 1;
                                $get = mysqli_query($conn, "select * from tbl_vehicle_category");
                                if (mysqli_num_rows($get)) {
                                    ?>
                                    <table id="tbtable" class="table table-bordered table-striped table-responsive">
                                        <thead>
                                            <tr>
                                                <th>Sr No</th>
                                                <th>Company Name</th>
                                                <th>View/Update</th>
                                                <th>Delete</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            while ($row = mysqli_fetch_array($get)) {
                                                $id = $row['car_id'];
                                                $nm = $row['car_name'];
                                                ?>
                                                <tr>
                                                    <td><?php echo $no; ?></td>
                                                    <td><?php echo $nm; ?></td>
                                                    <td><center><a href="VehicleCategory.php?eid=<?php echo $id; ?>"><i class="fa fa-edit"></i></a></center></td>
                                            <td><center><a href="VehicleCategory.php?did=<?php echo $id; ?>"><i class="fa fa-remove"></i></a></center></td>
                                            </tr>
                                            <?php
                                            $no++;
                                        }
                                        ?>
                                        </tbody>
                                    </table>
                                    <?php
                                } else {
                                    echo '<h4 class="box-body">No Data Avaliable For Vehicle Category...</h4>';
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
        include 'footer.php';
        ?>

    </body>
</html>
