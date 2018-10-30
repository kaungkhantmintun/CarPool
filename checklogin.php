<?php

ob_start();
include 'db.php';
$email = '';
$pwd = '';
if (isset($_REQUEST['email'])):
    if (!empty($_REQUEST['email'])):
        $email = htmlspecialchars($_GET['email']);
    else:
        header("Location: index.php?login=failed");
    //die();
    endif;
else:
    header("Location: index.php?login=failed");
// die();
endif;

if (isset($_REQUEST['pwd'])):
    if (!empty($_REQUEST['pwd'])):
        $pwd = htmlspecialchars($_GET['pwd']);

    else:
        header("Location: index.php?login=failed");
    // die();
    endif;
else:
    header("Location: index.php?login=failed");
//die();
endif;


$db_uname = '';
$db_pwd = '';
$db_email = '';


$result = mysqli_query($conn, "SELECT * FROM tbl_admin where admin_email='$email' and admin_password='$pwd'");
$row = mysqli_fetch_array($result);

$db_uname = $row['admin_name'];
$db_pwd = $row['admin_password'];
$db_email=$row['admin_email'];
if ($db_email == $email):
    if ($db_pwd == $pwd):
        session_start();
        $_SESSION['usr'] = $db_uname;
        header("Location:Dashboard.php?login=success");
    else:
        header("Location: index.php?login=failed");

    endif;
else:
    header("Location: index.php?login=failed");

endif;
mysqli_close($conn);
?>