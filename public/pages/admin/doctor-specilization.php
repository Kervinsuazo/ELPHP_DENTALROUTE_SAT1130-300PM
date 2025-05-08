<?php
session_start();
error_reporting(0);
include('include/config.php');

if (!isset($_SESSION['id']) || strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
    exit();
}

// Add Doctor Specialization
if (isset($_POST['submit'])) {
    $doctorspecilization = trim($_POST['doctorspecilization']);
    $doctorspecilization = mysqli_real_escape_string($con, $doctorspecilization);

    if (!empty($doctorspecilization)) {
        $sql = mysqli_query($con, "INSERT INTO doctorSpecilization(specilization) VALUES ('$doctorspecilization')");
        $_SESSION['msg'] = $sql ? "Doctor Specialization added successfully!" : "Error adding specialization.";
    } else {
        $_SESSION['msg'] = "Specialization cannot be empty.";
    }
}

// Delete Doctor Specialization
if (isset($_GET['del']) && isset($_GET['id'])) {
    $sid = intval($_GET['id']);
    mysqli_query($con, "DELETE FROM doctorSpecilization WHERE id = $sid");
    $_SESSION['msg'] = "Doctor Specialization deleted!";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Doctor Specialization</title>
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
                                <h1 class="mainTitle">Admin | Add Doctor Specialization</h1>
                            </div>
                            <ol class="breadcrumb">
                                <li><span>Admin</span></li>
                                <li class="active"><span>Add Doctor Specialization</span></li>
                            </ol>
                        </div>
                    </section>

                    <div class="container-fluid container-fullw bg-white">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row margin-top-30">
                                    <div class="col-lg-6 col-md-12">
                                        <div class="panel panel-white">
                                            <div class="panel-heading">
                                                <h5 class="panel-title">Doctor Specialization</h5>
                                            </div>
                                            <div class="panel-body">
                                                <p style="color:red;">
                                                    <?php echo htmlentities($_SESSION['msg']);?>
                                                    <?php echo htmlentities($_SESSION['msg']=""); ?>
                                                </p>    
                                                <form role="form" name="dcotorspcl" method="post">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Doctor Specialization</label>
                                                        <input type="text" name="doctorspecilization" class="form-control" placeholder="Enter Doctor Specialization">
                                                    </div>
                                                    <button type="submit" name="submit" class="btn btn-o btn-primary">Submit</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12">
                                    <div class="panel panel-white"></div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="over-title margin-bottom-15">Manage <span class="text-bold">Doctor Specialization</span></h5>
                                    <table class="table table-hover" id="sample-table-1">
                                        <thead>
                                            <tr>
                                                <th class="center">#</th>
                                                <th>Specialization</th>
                                                <th class="hidden-xs">Creation Date</th>
                                                <th>Updation Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $sql = mysqli_query($con, "SELECT * FROM doctorSpecilization");
                                            $cnt = 1;
                                            while ($row = mysqli_fetch_array($sql)) {
                                            ?>
                                                <tr>
                                                    <td class="center"><?php echo $cnt;?>.</td>
                                                    <td class="hidden-xs"><?php echo $row['specilization']; ?></td>
                                                    <td><?php echo $row['creationDate']; ?></td>
                                                    <td><?php echo $row['updationDate']; ?></td>
                                                    <td>
                                                        <div class="visible-md visible-lg hidden-sm hidden-xs">
                                                            <a href="edit-doctor-specialization.php?id=<?php echo $row['id'];?>" class="btn btn-transparent btn-xs" tooltip-placement="top" tooltip="Edit">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                            <a href="doctor-specilization.php?id=<?php echo $row['id']?>&del=delete" onClick="return confirm('Are you sure you want to delete?')" class="btn btn-transparent btn-xs tooltips" tooltip-placement="top" tooltip="Remove">
                                                                <i class="fa fa-times fa fa-white"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php
                                            $cnt = $cnt + 1;
                                            } ?>
                                        </tbody>
                                    </table>
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

    <!-- start: MAIN JAVASCRIPTS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="vendor/modernizr/modernizr.js"></script>
    <script src="vendor/jquery-cookie/jquery.cookie.js"></script>
    <script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="vendor/switchery/switchery.min.js"></script>
    <!-- end: MAIN JAVASCRIPTS -->

    <!-- start: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->
    <script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
    <script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
    <script src="vendor/autosize/autosize.min.js"></script>
    <script src="vendor/selectFx/classie.js"></script>
    <script src="vendor/selectFx/selectFx.js"></script>
    <script src="vendor/select2/select2.min.js"></script>
    <script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
    <!-- end: JAVASCRIPTS REQUIRED FOR THIS PAGE ONLY -->

    <!-- start: CLIP-TWO JAVASCRIPTS -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/form-elements.js"></script>
    <script>
        jQuery(document).ready(function() {
            Main.init();
            FormElements.init();
        });
    </script>
    <!-- end: CLIP-TWO JAVASCRIPTS -->
</body>
</html>
