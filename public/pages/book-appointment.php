<?php
session_start();
include('include/config.php');
include('include/checklogin.php');
check_login();

// Enable MySQLi strict error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Check if it's a JSON request (API)
$isJsonRequest = isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
$isApiCall = isset($_GET['api']) && $_GET['api'] === 'appointments';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($isApiCall || $isJsonRequest) {
        // Handle JSON API request
        header('Content-Type: application/json');

        // Ensure the user is logged in (session check)
        if (!isset($_SESSION['id'])) {
            echo json_encode([
                "status" => "error",
                "message" => "Unauthorized. Please log in first."
            ]);
            exit;
        }

        // Parse the incoming JSON request body
        $data = json_decode(file_get_contents("php://input"), true);

        // Check for required fields
        if (
            isset($data['Doctorspecialization'], $data['doctor'], $data['fees'], $data['appdate'], $data['apptime'])
        ) {
            $specilization = $data['Doctorspecialization'];
            $doctorid = intval($data['doctor']);  // Ensure it's an integer
            $userid = intval($_SESSION['id']);    // Ensure it's an integer
            $fees = intval($data['fees']);        // Ensure it's an integer
            $appdate = $data['appdate'];
            $time = $data['apptime'];
            $userstatus = 1;
            $docstatus = 1;

            // Prepare and execute the query
            $stmt = $con->prepare("INSERT INTO appointment (doctorSpecialization, doctorId, userId, consultancyFees, appointmentDate, appointmentTime, userStatus, doctorStatus) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("siiissii", $specilization, $doctorid, $userid, $fees, $appdate, $time, $userstatus, $docstatus);

            try {
                $stmt->execute();

                echo json_encode([
                    "status" => "success",
                    "message" => "Appointment booked successfully.",
                    "appointment" => [
                        "doctorSpecialization" => $specilization,
                        "doctorId" => $doctorid,
                        "userId" => $userid,
                        "consultancyFees" => $fees,
                        "appointmentDate" => $appdate,
                        "appointmentTime" => $time
                    ]
                ]);
            } catch (Exception $e) {
                echo json_encode([
                    "status" => "error",
                    "message" => "Database error: " . $e->getMessage()
                ]);
            }

            $stmt->close();
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Missing required fields."
            ]);
        }

        exit; // Prevent further HTML output
    } else {
        // Handle form-based (HTML) requests
        if (isset($_POST['submit'])) {
            $specilization = $_POST['Doctorspecialization'];
            $doctorid = intval($_POST['doctor']);
            $userid = intval($_SESSION['id']);
            $fees = intval($_POST['fees']);
            $appdate = $_POST['appdate'];
            $time = $_POST['apptime'];
            $userstatus = 1;
            $docstatus = 1;

            $stmt = $con->prepare("INSERT INTO appointment (doctorSpecialization, doctorId, userId, consultancyFees, appointmentDate, appointmentTime, userStatus, doctorStatus) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("siiissii", $specilization, $doctorid, $userid, $fees, $appdate, $time, $userstatus, $docstatus);

            try {
                $stmt->execute();
                $_SESSION['msg1'] = "Your appointment has been successfully booked.";
            } catch (Exception $e) {
                $_SESSION['msg1'] = "Error booking appointment: " . $e->getMessage();
            }

            $stmt->close();
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <title>User | Book Appointment</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
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
                                <h1 class="mainTitle">User | Book Appointment</h1>
                            </div>
                            <ol class="breadcrumb">
                                <li><span>User</span></li>
                                <li class="active"><span>Book Appointment</span></li>
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
                                                <h5 class="panel-title">Book Appointment</h5>
                                            </div>
                                            <div class="panel-body">
                                                <p style="color:red;">
                                                    <?php echo htmlentities($_SESSION['msg1']); ?>
                                                    <?php echo htmlentities($_SESSION['msg1'] = ""); ?>
                                                </p>

                                                <!-- FORM START -->
                                                <form role="form" name="book" method="post">
                                                    <div class="form-group">
                                                        <label for="DoctorSpecialization">Doctor Specialization</label>
                                                        <select name="Doctorspecialization" class="form-control" onChange="getdoctor(this.value);" required>
                                                            <option value="">Select Specialization</option>
                                                            <?php 
                                                            $ret = mysqli_query($con, "SELECT * FROM doctorspecilization");
                                                            while ($row = mysqli_fetch_array($ret)) { ?>
                                                                <option value="<?php echo htmlentities($row['specilization']); ?>">
                                                                    <?php echo htmlentities($row['specilization']); ?>
                                                                </option>
                                                            <?php } ?>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="doctor">Doctors</label>
                                                        <select name="doctor" class="form-control" id="doctor" onChange="getfee(this.value); updateMap(this.options[this.selectedIndex].getAttribute('data-location'));" required>
                                                            <option value="">Select Doctor</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="consultancyfees">Consultancy Fees</label>
                                                        <input type="text" name="fees" class="form-control" id="fees" readonly>
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="AppointmentDate">Date</label>
                                                        <input class="form-control datepicker" name="appdate" required data-date-format="yyyy-mm-dd">
                                                    </div>

                                                    <div class="form-group">
                                                        <label for="Appointmenttime">Time</label>
                                                        <input class="form-control timepicker" name="apptime" id="timepicker1" required>
                                                    </div>

                                                    <div class="form-group">
                                                        <label>Doctor's Location</label>
                                                        <div id="map-container">
                                                            <iframe id="google-map" 
                                                                    width="100%" height="300" 
                                                                    frameborder="0" 
                                                                    style="border:0;" 
                                                                    src="https://www.google.com/maps?q=Cebu+City&output=embed" 
                                                                    allowfullscreen>
                                                            </iframe>
                                                        </div>
                                                    </div>

                                                    <button type="submit" name="submit" class="btn btn-o btn-primary">Submit</button>
                                                </form>
                                                <!-- FORM END -->
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
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <script src="assets/js/main.js"></script>

    <script>
        function getdoctor(val) {
            $.ajax({
                type: "POST",
                url: "get_doctor.php",
                data: 'specilizationid=' + val,
                success: function(data) {
                    $("#doctor").html(data);
                }
            });
        }

        function getfee(val) {
            $.ajax({
                type: "POST",
                url: "get_doctor.php",
                data: 'doctor=' + val,
                success: function(data) {
                    $("#fees").val(data);
                }
            });
        }

        function updateMap(location) {
            if (location) {
                var encodedLocation = encodeURIComponent(location);
                var mapUrl = "https://www.google.com/maps?q=" + encodedLocation + "&output=embed";
                document.getElementById("google-map").src = mapUrl;
            }
        }

        $(document).ready(function() {
            $('.datepicker').datepicker({ format: 'yyyy-mm-dd', autoclose: true });
            $('#timepicker1').timepicker({ showMeridian: false });
        });
    </script>
</body>
</html>
