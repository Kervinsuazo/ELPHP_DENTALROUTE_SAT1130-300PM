<?php
session_start();
error_reporting(0);
include('include/config.php');

// --- API ROUTING --- //
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['api']) && $_GET['api'] === 'appointments') {
    header('Content-Type: application/json');
    if (isset($_SESSION['id']) && $_SESSION['id'] != 0) {
        $userId = $_SESSION['id'];
        $result = mysqli_query($con, "SELECT doctors.doctorName as docname, appointment.* FROM appointment JOIN doctors ON doctors.id = appointment.doctorId WHERE appointment.userId='$userId'");
        $appointments = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $appointments[] = $row;
        }
        echo json_encode(['status' => 'success', 'appointments' => $appointments]);
    } else {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    }
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && isset($_GET['api']) && $_GET['api'] === 'appointments') {
    header('Content-Type: application/json');
    $input = json_decode(file_get_contents("php://input"), true);

    // Optional debug logging
    // file_put_contents("debug.txt", json_encode(['session' => $_SESSION, 'input' => $input]));

    if (!isset($_SESSION['id']) || empty($input['id'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid request or unauthorized."
        ]);
        exit;
    }

    $appointmentId = intval($input['id']);
    $userId = $_SESSION['id'];

    $query = mysqli_query($con, "UPDATE appointment SET userStatus='0' WHERE id='$appointmentId' AND userId='$userId'");

    if ($query) {
        echo json_encode([
            "status" => "success",
            "message" => "Appointment canceled."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Could not cancel appointment."
        ]);
    }
    exit();
}

// --- CANCEL FROM URL PARAMETER (BROWSER INTERFACE) --- //
if (strlen($_SESSION['id'] == 0)) {
    header('location:logout.php');
} else {
    if (isset($_GET['cancel'])) {
        mysqli_query($con, "UPDATE appointment SET userStatus='0' WHERE id = '" . $_GET['id'] . "' AND userId = '" . $_SESSION['id'] . "'");
        $_SESSION['msg'] = "Your appointment canceled !!";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>User | Appointment History</title>
    <!-- Stylesheets -->
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" />
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
</head>
<body>
<div id="app">      
    <?php include('include/sidebar.php');?>
    <div class="app-content">
        <?php include('include/header.php');?>
        <div class="main-content">
            <div class="wrap-content container" id="container">
                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-8">
                            <h1 class="mainTitle">User | Appointment History</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>User</span></li>
                            <li class="active"><span>Appointment History</span></li>
                        </ol>
                    </div>
                </section>
                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">
                            <p style="color:red;"><?php echo htmlentities($_SESSION['msg']); ?>
                            <?php echo htmlentities($_SESSION['msg'] = "");?></p>   
                            <table class="table table-hover" id="sample-table-1">
                                <thead>
                                    <tr>
                                        <th class="center">#</th>
                                        <th class="hidden-xs">Doctor Name</th>
                                        <th>Specialization</th>
                                        <th>Consultancy Fee</th>
                                        <th>Appointment Date / Time</th>
                                        <th>Appointment Creation Date</th>
                                        <th>Current Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php
$sql = mysqli_query($con, "SELECT doctors.doctorName as docname, appointment.* FROM appointment JOIN doctors ON doctors.id = appointment.doctorId WHERE appointment.userId='" . $_SESSION['id'] . "'");
$cnt = 1;
while ($row = mysqli_fetch_array($sql)) {
?>
                                    <tr>
                                        <td class="center"><?php echo $cnt; ?>.</td>
                                        <td class="hidden-xs"><?php echo $row['docname']; ?></td>
                                        <td><?php echo $row['doctorSpecialization']; ?></td>
                                        <td><?php echo $row['consultancyFees']; ?></td>
                                        <td><?php echo $row['appointmentDate']; ?> / <?php echo $row['appointmentTime']; ?></td>
                                        <td><?php echo $row['postingDate']; ?></td>
                                        <td>
<?php 
if (($row['userStatus'] == 1) && ($row['doctorStatus'] == 1)) echo "Active";
if (($row['userStatus'] == 0) && ($row['doctorStatus'] == 1)) echo "Canceled by You";
if (($row['userStatus'] == 1) && ($row['doctorStatus'] == 0)) echo "Canceled by Doctor";
?>
                                        </td>
                                        <td>
                                            <div class="visible-md visible-lg hidden-sm hidden-xs">
<?php if (($row['userStatus'] == 1) && ($row['doctorStatus'] == 1)) { ?>
    <a href="appointment-history.php?id=<?php echo $row['id'] ?>&cancel=update" onClick="return confirm('Are you sure you want to cancel this appointment?')" class="btn btn-primary btn-xs">Cancel</a>
<?php } else {
    echo "Canceled";
} ?>
                                            </div>
                                        </td>
                                    </tr>
<?php $cnt = $cnt + 1; } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>
    <?php include('include/footer.php');?>
    <?php include('include/setting.php');?>
</div>
<!-- JS Scripts -->
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
    jQuery(document).ready(function() {
        Main.init();
        FormElements.init();
    });
</script>
</body>
</html>
<?php } ?>
