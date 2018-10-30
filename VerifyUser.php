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
if (isset($_POST['btnverify'])) {
    $id = $_POST['id'];
    $result = mysqli_query($conn, "update tbl_users set isverified='yes' where user_id='$id'");
    if ($result) {
        header("Location:AppUser.php?success");
    } else {
        $errormsg = "update tbl_users isverified='yes' where user_id='$id'";
    }
}
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
                            if ($errormsg != '')
                                echo $errormsg;
                            $disable = '';
                            if (isset($_REQUEST['id'])) {
                                $id = $_REQUEST['id'];
                                $getdata = mysqli_query($conn, "select * from tbl_users where user_id='$id'");
                                if (mysqli_num_rows($getdata)) {
                                    $row = mysqli_fetch_array($getdata);
                                    $st = $row['isverified'];
                                    if ($st == "" || $st == "No")
                                        $status = "Verify User";
                                    else {
                                        $status = "User already Verify";
                                        $disable = "disabled";
                                    }
                                    $identityimg = $row['user_identity_img'];
                                    if ($identityimg == '')
                                        $identityimg = 'upload/noimage.png';
                                    else {
                                        $img = 'upload/identity/' . $identityimg;
                                        if (file_exists($img))
                                            $identityimg = $img;
                                        else
                                            $identityimg = 'upload/noimage.png';
                                    }
                                }
                            }
                            ?>
                            <input type="hidden" id="id" name="id" value="<?php echo $id; ?>"/>
                            <div class="col-lg-5 col-xs-8">
                                <img src="<?php echo $identityimg; ?>" style="height: 100%;width: 100%"/>
                            </div>

                            <div class="col-lg-3 col-xs-6">
                                <center> <input type="submit" id="btnverify" name="btnverify" value="<?php echo $status; ?>" class="btn btn-primary" <?php echo $disable; ?>/></center>
                            </div>

                        </div>

                    </form>
                </div>
            </div>
        </div>
        <?php
        mysqli_close($conn);
        include 'Footer.php';
        ?>

    </body>
</html>
