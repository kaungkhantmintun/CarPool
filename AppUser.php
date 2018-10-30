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
    <?php include 'Header.php'; ?>
    <body>
        <input type="hidden" value="dashboard" id="page_name">
        <?php include 'topnav.php'; ?>

        <div class="container-fluid">
            <div class="row">
                <?php include 'sidenav.php'; ?>
                <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
                    <h1 class="page-header">App User</h1>
                    <form class='form-horizontal' method="post" enctype="multipart/form-data">

                        <div class="col-md-12">
                            <?php
                            $no = 1;
                            $getuser = mysqli_query($conn, "select * from tbl_users");
                            if (mysqli_num_rows($getuser)) {
                                ?>
                                <table id="tbtable" class="table table-bordered table-striped table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Email</th>
                                            <th>Name</th>
                                            <th>Profile</th>
                                            <th>Identity</th>
                                            <th>Mobile</th>
                                            <th>Verify Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($rowuser = mysqli_fetch_array($getuser)) {
                                            $id = $rowuser['user_id'];
                                            $email = $rowuser['user_email'];
                                            $nm = $rowuser['user_name'];
                                            $profileimg = $rowuser['user_profile_img'];
                                            $identityimg = $rowuser['user_identity_img'];
                                            $mob = $rowuser['user_mobile'];
                                            $verify = $rowuser['isverified'];
                                            if ($verify == "")
                                                $verify = "No";
                                            if ($profileimg == '')
                                                $profileimg = 'upload/noimage.png';
                                            else {
                                                $img1 = 'upload/profile/' . $profileimg;
                                                if (file_exists($img1))
                                                    $profileimg = $img1;
                                                else
                                                    $profileimg = 'upload/noimage.png';
                                            }
                                            if ($identityimg == '')
                                                $identityimg = 'upload/noimage.png';
                                            else {
                                                $img = 'upload/identity/' . $identityimg;
                                                if (file_exists($img))
                                                    $identityimg = $img;
                                                else
                                                    $identityimg = 'upload/noimage.png';
                                            }
                                            ?>
                                            <tr>
                                                <td><?php echo $no; ?></td>
                                                <td><?php echo $email; ?></td>
                                                <td><?php echo $nm; ?></td>
                                                <td><img src="<?php echo $profileimg ?>" style="height: 50px;width: 50px;"/></td>
                                                <td><img src="<?php echo $identityimg ?>" style="height: 50px;width: 50px;"/></td>
                                                <td><?php echo $mob; ?></td>
                                                <td><center><a href="VerifyUser.php?id=<?php echo $id; ?>"><?php echo $verify; ?></a></center></td>
                                        </tr>
                                        <?php
                                        $no++;
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <?php
                            } else {
                                echo "<h3>App User Not Avaliable...</h3>";
                            }
                            ?>
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
