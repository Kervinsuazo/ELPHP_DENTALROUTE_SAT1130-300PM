<?php
session_start();
include('include/config.php');
include('include/checklogin.php');
check_login();

date_default_timezone_set('Asia/Kolkata');
$currentTime = date('d-m-Y h:i:s A', time());

// API Mode Support
$isApi = isset($_GET['api']) && $_GET['api'] === 'password';

if ($isApi && $_SERVER['REQUEST_METHOD'] === 'POST' && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false) {
    $input = json_decode(file_get_contents("php://input"), true);

    $currentPassword = isset($input['cpass']) ? $input['cpass'] : '';
    $newPassword = isset($input['npass']) ? $input['npass'] : '';
    $confirmPassword = isset($input['cfpass']) ? $input['cfpass'] : '';

    header('Content-Type: application/json');

    if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    if ($newPassword !== $confirmPassword) {
        echo json_encode(["success" => false, "message" => "Passwords do not match."]);
        exit;
    }

    $userId = $_SESSION['id'];
    $stmt = $con->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($storedPassword);
    $stmt->fetch();
    $stmt->close();

    if ($storedPassword === md5($currentPassword)) {
        $newHashedPassword = md5($newPassword);
        $stmt = $con->prepare("UPDATE users SET password = ?, updationDate = ? WHERE id = ?");
        $stmt->bind_param("ssi", $newHashedPassword, $currentTime, $userId);
        $stmt->execute();
        $stmt->close();

        echo json_encode(["success" => true, "message" => "Password changed successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Incorrect current password."]);
    }
    exit;
}

// Web form submission
if (isset($_POST['submit'])) {
    $userId = $_SESSION['id'];
    $sql = mysqli_query($con, "SELECT password FROM users WHERE password='" . md5($_POST['cpass']) . "' AND id='$userId'");
    $num = mysqli_fetch_array($sql);

    if ($num > 0) {
        mysqli_query($con, "UPDATE users SET password='" . md5($_POST['npass']) . "', updationDate='$currentTime' WHERE id='$userId'");
        $_SESSION['msg1'] = "Password Changed Successfully !!";
    } else {
        $_SESSION['msg1'] = "Old Password not match !!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>User | Change Password</title>
    <link href="http://fonts.googleapis.com/css?family=Lato|Raleway|Crete+Round" rel="stylesheet">
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
    <link href="vendor/animate.css/animate.min.css" rel="stylesheet">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet">
    <link href="vendor/switchery/switchery.min.css" rel="stylesheet">
    <link href="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet">
    <link href="vendor/select2/select2.min.css" rel="stylesheet">
    <link href="vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet">
    <link href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/plugins.css">
    <link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />

    <script type="text/javascript">
        function valid() {
            if (document.chngpwd.cpass.value == "") {
                alert("Current Password Field is Empty !!");
                document.chngpwd.cpass.focus();
                return false;
            } else if (document.chngpwd.npass.value == "") {
                alert("New Password Field is Empty !!");
                document.chngpwd.npass.focus();
                return false;
            } else if (document.chngpwd.cfpass.value == "") {
                alert("Confirm Password Field is Empty !!");
                document.chngpwd.cfpass.focus();
                return false;
            } else if (document.chngpwd.npass.value != document.chngpwd.cfpass.value) {
                alert("Password and Confirm Password Field do not match !!");
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
                            <h1 class="mainTitle">User | Change Password</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>User</span></li>
                            <li class="active"><span>Change Password</span></li>
                        </ol>
                    </div>
                </section>
                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">
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
                                            <label>Current Password</label>
                                            <input type="password" name="cpass" class="form-control" placeholder="Enter Current Password">
                                        </div>
                                        <div class="form-group">
                                            <label>New Password</label>
                                            <input type="password" name="npass" class="form-control" placeholder="New Password">
                                        </div>
                                        <div class="form-group">
                                            <label>Confirm Password</label>
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

        <?php include('include/footer.php'); ?>
    </div>
    <?php include('include/setting.php'); ?>
</div>

<!-- Scripts -->
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
