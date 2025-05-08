<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include('include/config.php');

// ——————————————
// SESSION CHECK
// ——————————————
if (!isset($_SESSION['id']) || $_SESSION['id'] == 0) {
    header('Location: logout.php');
    exit;
}

$docId = $_SESSION['id'];

// ——————————————
// API ROUTING
// ——————————————
if (isset($_GET['api']) && $_GET['api'] === 'profile') {
    header('Content-Type: application/json');

    // GET current profile
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $stmt = $con->prepare("
            SELECT specilization, doctorName, address, docFees, contactno, docEmail, creationDate, updationDate
            FROM doctors
            WHERE id = ?
        ");
        $stmt->bind_param("i", $docId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            echo json_encode(array_merge(["status" => "success"], $row));
        } else {
            http_response_code(404);
            echo json_encode(["status" => "error", "message" => "Profile not found"]);
        }
        $stmt->close();
        exit;
    }

    // POST update profile
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = json_decode(file_get_contents("php://input"), true);
        $required = ['Doctorspecialization','docname','clinicaddress','docfees','doccontact'];

        // validate
        foreach ($required as $f) {
            if (empty($data[$f])) {
                http_response_code(400);
                echo json_encode(["status" => "error", "message" => "Missing field: $f"]);
                exit;
            }
        }

        // update
        $stmt = $con->prepare("
            UPDATE doctors
            SET specilization = ?, doctorName = ?, address = ?, docFees = ?, contactno = ?, updationDate = NOW()
            WHERE id = ?
        ");
        $stmt->bind_param(
            "sssssi",
            $data['Doctorspecialization'],
            $data['docname'],
            $data['clinicaddress'],
            $data['docfees'],
            $data['doccontact'],
            $docId
        );
        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Profile updated"]);
        } else {
            http_response_code(500);
            echo json_encode(["status" => "error", "message" => "Update failed"]);
        }
        $stmt->close();
        exit;
    }

    // other methods not allowed
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method not allowed"]);
    exit;
}
// ——————————————
// END API ROUTING
// ——————————————

// ——————————————
// HTML-FORM HANDLER
// ——————————————
if (isset($_POST['submit'])) {
    $stmt = $con->prepare("
        UPDATE doctors
        SET specilization = ?, doctorName = ?, address = ?, docFees = ?, contactno = ?, updationDate = NOW()
        WHERE id = ?
    ");
    $stmt->bind_param(
        "sssssi",
        $_POST['Doctorspecialization'],
        $_POST['docname'],
        $_POST['clinicaddress'],
        $_POST['docfees'],
        $_POST['doccontact'],
        $docId
    );
    if ($stmt->execute()) {
        echo "<script>alert('Doctor Details updated Successfully');</script>";
    }
    $stmt->close();
}

// ——————————————
// Fetch current data for form
// ——————————————
$stmt = $con->prepare("
    SELECT specilization, doctorName, address, docFees, contactno, docEmail
    FROM doctors
    WHERE id = ?
");
$stmt->bind_param("i", $docId);
$stmt->execute();
$current = $stmt->get_result()->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Doctor | Edit Profile</title>
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
                        <h1 class="mainTitle">Doctor | Edit Profile</h1>
                        <ol class="breadcrumb">
                            <li><span>Doctor</span></li>
                            <li class="active"><span>Edit Profile</span></li>
                        </ol>
                    </section>

                    <form method="post" class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Specialization</label>
                            <div class="col-sm-8">
                                <select name="Doctorspecialization" class="form-control" required>
                                    <option value="<?php echo htmlentities($current['specilization']); ?>">
                                        <?php echo htmlentities($current['specilization']); ?>
                                    </option>
                                    <?php
                                    $r = mysqli_query($con, "SELECT specilization FROM doctorspecilization");
                                    while ($row = mysqli_fetch_assoc($r)) {
                                    ?>
                                    <option value="<?php echo htmlentities($row['specilization']); ?>">
                                        <?php echo htmlentities($row['specilization']); ?>
                                    </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Doctor Name</label>
                            <div class="col-sm-8">
                                <input type="text" name="docname" class="form-control"
                                       value="<?php echo htmlentities($current['doctorName']); ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Clinic Address</label>
                            <div class="col-sm-8">
                                <textarea name="clinicaddress" class="form-control" required><?php echo htmlentities($current['address']); ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Consultancy Fees</label>
                            <div class="col-sm-8">
                                <input type="text" name="docfees" class="form-control" required
                                       value="<?php echo htmlentities($current['docFees']); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Contact No</label>
                            <div class="col-sm-8">
                                <input type="text" name="doccontact" class="form-control" required
                                       value="<?php echo htmlentities($current['contactno']); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Email (readonly)</label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" readonly
                                       value="<?php echo htmlentities($current['docEmail']); ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-8">
                                <button type="submit" name="submit" class="btn btn-primary">Update</button>
                            </div>
                        </div>
                    </form>

                </div>
            </div>
            <?php include('include/footer.php'); ?>
            <?php include('include/setting.php'); ?>
        </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
