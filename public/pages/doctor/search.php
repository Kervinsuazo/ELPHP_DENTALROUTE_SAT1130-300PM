<?php
session_start();
error_reporting(0);
include('include/config.php');

// Handle API Request
if (isset($_GET['api']) && $_GET['api'] === 'patient') {
    header('Content-Type: application/json');
    if (strlen($_SESSION['id']) == 0) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit;
    }

    $docid = $_SESSION['id'];
    $search = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';

    if ($search !== '') {
        $sql = mysqli_query($con, "SELECT * FROM tblpatient WHERE Docid='$docid' AND (PatientName LIKE '%$search%' OR PatientContno LIKE '%$search%')");
    } else {
        $sql = mysqli_query($con, "SELECT * FROM tblpatient WHERE Docid='$docid'");
    }

    $patients = [];
    while ($row = mysqli_fetch_assoc($sql)) {
        $patients[] = $row;
    }

    echo json_encode(['status' => 'success', 'data' => $patients]);
    exit;
}

if (strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
} else {
?>
<!-- [REMAINING HTML INTERFACE HERE — UNCHANGED] -->
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Doctor | Manage Patients</title>
    <!-- [Include your stylesheets as you have them above] -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/css/styles.css" rel="stylesheet" />
</head>
<body>
    <div id="app">		
    <?php include('include/sidebar.php'); ?>
    <div class="app-content">
        <?php include('include/header.php'); ?>
        <div class="main-content">
            <div class="wrap-content container" id="container">
                <!-- PAGE TITLE -->
                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-8">
                            <h1 class="mainTitle">Doctor | Manage Patients</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Doctor</span></li>
                            <li class="active"><span>Manage Patients</span></li>
                        </ol>
                    </div>
                </section>
                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">
                            <form role="form" method="post" name="search">
                                <div class="form-group">
                                    <label for="doctorname">Search by Name/Mobile No.</label>
                                    <input type="text" name="searchdata" id="searchdata" class="form-control" required>
                                </div>
                                <button type="submit" name="search" id="submit" class="btn btn-o btn-primary">Search</button>
                            </form>
                            <?php
                            if (isset($_POST['search'])) {
                                $sdata = $_POST['searchdata'];
                            ?>
                                <h4 align="center">Result against "<?php echo $sdata; ?>" keyword</h4>
                                <table class="table table-hover" id="sample-table-1">
                                    <thead>
                                        <tr>
                                            <th class="center">#</th>
                                            <th>Patient Name</th>
                                            <th>Patient Contact Number</th>
                                            <th>Patient Gender </th>
                                            <th>Creation Date </th>
                                            <th>Updation Date </th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = mysqli_query($con, "SELECT * FROM tblpatient WHERE PatientName LIKE '%$sdata%' OR PatientContno LIKE '%$sdata%'");
                                        $num = mysqli_num_rows($sql);
                                        if ($num > 0) {
                                            $cnt = 1;
                                            while ($row = mysqli_fetch_array($sql)) {
                                        ?>
                                                <tr>
                                                    <td class="center"><?php echo $cnt; ?>.</td>
                                                    <td class="hidden-xs"><?php echo $row['PatientName']; ?></td>
                                                    <td><?php echo $row['PatientContno']; ?></td>
                                                    <td><?php echo $row['PatientGender']; ?></td>
                                                    <td><?php echo $row['CreationDate']; ?></td>
                                                    <td><?php echo $row['UpdationDate']; ?></td>
                                                    <td>
                                                        <a href="edit-patient.php?editid=<?php echo $row['ID']; ?>" class="btn btn-primary btn-sm" target="_blank">Edit</a>
                                                        <a href="view-patient.php?viewid=<?php echo $row['ID']; ?>" class="btn btn-warning btn-sm" target="_blank">View Details</a>
                                                    </td>
                                                </tr>
                                        <?php
                                                $cnt++;
                                            }
                                        } else {
                                            echo "<tr><td colspan='8'>No record found against this search</td></tr>";
                                        } ?>
                                    </tbody>
                                </table>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('include/footer.php'); ?>
    <?php include('include/setting.php'); ?>
</div>
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
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
<?php } ?>
