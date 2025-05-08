<?php
session_start();
error_reporting(0);
include('include/config.php');

$apiMode = isset($_GET['api']) ? $_GET['api'] : null;

if ($apiMode === 'patient') {
    header('Content-Type: application/json');

    if (!isset($_SESSION['id']) || strlen($_SESSION['id']) == 0) {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit;
    }

    $docid = $_SESSION['id'];

    // GET: Fetch patients
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $sql = mysqli_query($con, "SELECT * FROM tblpatient WHERE Docid='$docid'");
        $patients = [];
        while ($row = mysqli_fetch_assoc($sql)) {
            $patients[] = $row;
        }
        echo json_encode(['status' => 'success', 'patients' => $patients]);
        exit;
    }

    // POST: Add a new patient
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $input = json_decode(file_get_contents("php://input"), true);
        $name = $input['name'];
        $contact = $input['contact'];
        $gender = $input['gender'];

        $stmt = $con->prepare("INSERT INTO tblpatient (Docid, PatientName, PatientContno, PatientGender) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $docid, $name, $contact, $gender);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Patient added']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Insert failed']);
        }
        exit;
    }

    // PUT: Update patient
    if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
        parse_str(file_get_contents("php://input"), $_PUT);
        $id = intval($_PUT['id']);
        $name = $_PUT['name'];
        $contact = $_PUT['contact'];
        $gender = $_PUT['gender'];

        $stmt = $con->prepare("UPDATE tblpatient SET PatientName=?, PatientContno=?, PatientGender=? WHERE ID=? AND Docid=?");
        $stmt->bind_param("sssii", $name, $contact, $gender, $id, $docid);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Patient updated']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Update failed']);
        }
        exit;
    }

    // DELETE: Remove patient
    if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        parse_str(file_get_contents("php://input"), $_DELETE);
        $id = intval($_DELETE['id']);

        $stmt = $con->prepare("DELETE FROM tblpatient WHERE ID=? AND Docid=?");
        $stmt->bind_param("ii", $id, $docid);

        if ($stmt->execute()) {
            echo json_encode(['status' => 'success', 'message' => 'Patient deleted']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Delete failed']);
        }
        exit;
    }

    // Unsupported method
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// If not API mode, show the page
if (strlen($_SESSION['id'] == 0)) {
    header('location:logout.php');
} else {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Doctor | Manage Patients</title>
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/styles.css">
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
<div class="col-sm-8"><h1 class="mainTitle">Doctor | Manage Patients</h1></div>
<ol class="breadcrumb">
<li><span>Doctor</span></li>
<li class="active"><span>Manage Patients</span></li>
</ol>
</div>
</section>

<div class="container-fluid container-fullw bg-white">
<div class="row">
<div class="col-md-12">
<h5 class="over-title margin-bottom-15">Manage <span class="text-bold">Patients</span></h5>
<table class="table table-hover" id="sample-table-1">
<thead>
<tr>
<th class="center">#</th>
<th>Patient Name</th>
<th>Contact</th>
<th>Gender</th>
<th>Created</th>
<th>Updated</th>
<th>Action</th>
</tr>
</thead>
<tbody>
<?php
$docid = $_SESSION['id'];
$sql = mysqli_query($con, "SELECT * FROM tblpatient WHERE Docid='$docid'");
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
<a href="view-patient.php?viewid=<?php echo $row['ID']; ?>" class="btn btn-warning btn-sm" target="_blank">View</a>
</td>
</tr>
<?php $cnt++; } ?>
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
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
<?php } ?>
