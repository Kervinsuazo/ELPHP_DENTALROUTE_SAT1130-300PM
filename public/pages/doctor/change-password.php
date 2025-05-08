<?php
session_start();
error_reporting(0);
include('include/config.php');
date_default_timezone_set('Asia/Kolkata');
$currentTime = date('d-m-Y h:i:s A', time());

// API Mode: http://localhost/pages/doctor/change-password.php?api=password
if (isset($_GET['api']) && $_GET['api'] === 'password') {
    header('Content-Type: application/json');

    if (!isset($_SESSION['id']) || strlen($_SESSION['id']) == 0) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized. Please log in.']);
        exit;
    }

    $did = $_SESSION['id'];
    $cpass = isset($_POST['cpass']) ? $_POST['cpass'] : '';
    $npass = isset($_POST['npass']) ? $_POST['npass'] : '';
    $cfpass = isset($_POST['cfpass']) ? $_POST['cfpass'] : '';

    if (empty($cpass) || empty($npass) || empty($cfpass)) {
        echo json_encode(['success' => false, 'message' => 'All fields are required.']);
        exit;
    }

    if ($npass !== $cfpass) {
        echo json_encode(['success' => false, 'message' => 'New password and confirm password do not match.']);
        exit;
    }

    $cpass_md5 = md5($cpass);
    $npass_md5 = md5($npass);

    $check = mysqli_query($con, "SELECT password FROM doctors WHERE id='$did' AND password='$cpass_md5'");
    if (mysqli_num_rows($check) == 0) {
        echo json_encode(['success' => false, 'message' => 'Current password is incorrect.']);
        exit;
    }

    $update = mysqli_query($con, "UPDATE doctors SET password='$npass_md5', updationDate='$currentTime' WHERE id='$did'");
    if ($update) {
        echo json_encode(['success' => true, 'message' => 'Password changed successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Password update failed.']);
    }

    exit;
}

// HTML Form Mode
if (strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
    exit;
}

if (isset($_POST['submit'])) {
    $cpass = md5($_POST['cpass']);
    $did = $_SESSION['id'];
    $sql = mysqli_query($con, "SELECT password FROM doctors WHERE password='$cpass' AND id='$did'");
    $num = mysqli_fetch_array($sql);

    if ($num > 0) {
        $npass = md5($_POST['npass']);
        mysqli_query($con, "UPDATE doctors SET password='$npass', updationDate='$currentTime' WHERE id='$did'");
        $_SESSION['msg1'] = "Password Changed Successfully !!";
    } else {
        $_SESSION['msg1'] = "Old Password not match !!";
    }
}
?>

<!-- HTML PAGE BEGINS -->
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Doctor | Change Password</title>
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
    <link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
    <link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" media="screen">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/plugins.css">
    <link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />

    <script type="text/javascript">
    function valid() {
        if (document.chngpwd.cpass.value == "") {
            alert("Current Password field is empty !!");
            document.chngpwd.cpass.focus();
            return false;
        } else if (document.chngpwd.npass.value == "") {
            alert("New Password field is empty !!");
            document.chngpwd.npass.focus();
            return false;
        } else if (document.chngpwd.cfpass.value == "") {
            alert("Confirm Password field is empty !!");
            document.chngpwd.cfpass.focus();
            return false;
        } else if (document.chngpwd.npass.value != document.chngpwd.cfpass.value) {
            alert("Password and Confirm Password do not match !!");
            document.chngpwd.cfpass.focus();
            return false;
        }
        return true;
    }
    </script>
</head>
<body>
<div id="app">		
    <?php include('include/sidebar.php'); ?>
    <div class="app-content">
        <?php include('include/header.php'); ?>
        <div class="main-content">
            <div class="wrap-content container" id="container">
                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-8">
                            <h1 class="mainTitle">Doctor | Change Password</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Doctor</span></li>
                            <li class="active"><span>Change Password</span></li>
                        </ol>
                    </div>
                </section>

                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row margin-top-30">
                                <div class="col-lg-8 col-md-12">
                                    <div class="panel panel-white">
                                        <div class="panel-heading">
                                            <h5 class="panel-title">Change Password</h5>
                                        </div>
                                        <div class="panel-body">
                                            <p style="color:red;">
                                                <?php echo htmlentities($_SESSION['msg1']); ?>
                                                <?php echo htmlentities($_SESSION['msg1'] = ""); ?>
                                            </p>
                                            <form role="form" name="chngpwd" method="post" onSubmit="return valid();">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail1">Current Password</label>
                                                    <input type="password" name="cpass" class="form-control" placeholder="Enter Current Password">
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputPassword1">New Password</label>
                                                    <input type="password" name="npass" class="form-control" placeholder="New Password">
                                                </div>
                                                <div class="form-group">
                                                    <label for="exampleInputPassword1">Confirm Password</label>
                                                    <input type="password" name="cfpass" class="form-control" placeholder="Confirm Password">
                                                </div>
                                                <button type="submit" name="submit" class="btn btn-o btn-primary">Submit</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php include('include/footer.php'); ?>
        <?php include('include/setting.php'); ?>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/modernizr/modernizr.js"></script>
<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="vendor/switchery/switchery.min.js"></script>
<script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
<script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
<script src="vendor/autosize/autosize.min.js"></script>
<script src="vendor/selectFx/classie.js"></script>
<script src="vendor/selectFx/selectFx.js"></script>
<script src="vendor/select2/select2.min.js"></script>
<script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/form-elements.js"></script>
<script>
    jQuery(document).ready(function () {
        Main.init();
        FormElements.init();
    });
</script>
</body>
</html>
