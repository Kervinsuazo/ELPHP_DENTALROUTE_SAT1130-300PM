<?php
session_start();
include('include/config.php');
include('include/checklogin.php');
check_login();

// Enable error reporting for mysqli
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Flag for JSON API
$isJsonRequest = isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false;
$isApiCall = isset($_GET['api']) && $_GET['api'] == 'profile';

$msg = "";

// Handle GET API request
if ($_SERVER['REQUEST_METHOD'] === 'GET' && ($isJsonRequest || $isApiCall)) {
    header('Content-Type: application/json');

    if (!isset($_SESSION['id'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Unauthorized. Please log in first."
        ]);
        exit;
    }

    try {
        $stmt = $con->prepare("SELECT fullName, address, city, gender, email, regDate, updationDate FROM users WHERE id = ?");
        $stmt->bind_param("i", $_SESSION['id']);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            echo json_encode([
                "status" => "success",
                "data" => $row
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "User not found."
            ]);
        }
    } catch (Exception $e) {
        echo json_encode([
            "status" => "error",
            "message" => "Database error: " . $e->getMessage()
        ]);
    }

    exit;
}

// Handle form or API update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Parse API data if it's a JSON request
    if ($isJsonRequest || $isApiCall) {
        header('Content-Type: application/json');

        if (!isset($_SESSION['id'])) {
            echo json_encode([
                "status" => "error",
                "message" => "Unauthorized. Please log in first."
            ]);
            exit;
        }

        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['fname'], $data['address'], $data['city'], $data['gender'])) {
            echo json_encode([
                "status" => "error",
                "message" => "Missing one or more required fields."
            ]);
            exit;
        }

        $fname = $data['fname'];
        $address = $data['address'];
        $city = $data['city'];
        $gender = $data['gender'];

        try {
            $stmt = $con->prepare("UPDATE users SET fullName = ?, address = ?, city = ?, gender = ?, updationDate = NOW() WHERE id = ?");
            $stmt->bind_param("ssssi", $fname, $address, $city, $gender, $_SESSION['id']);
            $stmt->execute();

            echo json_encode([
                "status" => "success",
                "message" => "Profile updated successfully."
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => "Database error: " . $e->getMessage()
            ]);
        }

        exit;
    } else {
        // Handle form-based update
        if (isset($_POST['submit'])) {
            $fname = $_POST['fname'];
            $address = $_POST['address'];
            $city = $_POST['city'];
            $gender = $_POST['gender'];

            try {
                $stmt = $con->prepare("UPDATE users SET fullName = ?, address = ?, city = ?, gender = ?, updationDate = NOW() WHERE id = ?");
                $stmt->bind_param("ssssi", $fname, $address, $city, $gender, $_SESSION['id']);
                $stmt->execute();
                $msg = "Your Profile updated Successfully";
            } catch (Exception $e) {
                $msg = "Error updating profile: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>User | Edit Profile</title>
    <!-- Stylesheets -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="vendor/fontawesome/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/styles.css" rel="stylesheet">
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
                                <h1 class="mainTitle">User | Edit Profile</h1>
                            </div>
                            <ol class="breadcrumb">
                                <li><span>User</span></li>
                                <li class="active"><span>Edit Profile</span></li>
                            </ol>
                        </div>
                    </section>
                    <div class="container-fluid container-fullw bg-white">
                        <div class="row">
                            <div class="col-md-12">
                                <h5 style="color: green; font-size:18px;"><?php if ($msg) echo htmlentities($msg); ?></h5>
                                <div class="row margin-top-30">
                                    <div class="col-lg-8 col-md-12">
                                        <div class="panel panel-white">
                                            <div class="panel-heading">
                                                <h5 class="panel-title">Edit Profile</h5>
                                            </div>
                                            <div class="panel-body">
                                                <?php
                                                $sql = mysqli_query($con, "SELECT * FROM users WHERE id='" . $_SESSION['id'] . "'");
                                                while ($data = mysqli_fetch_array($sql)) {
                                                ?>
                                                    <h4><?php echo htmlentities($data['fullName']); ?>'s Profile</h4>
                                                    <p><b>Profile Reg. Date:</b> <?php echo htmlentities($data['regDate']); ?></p>
                                                    <?php if ($data['updationDate']) { ?>
                                                        <p><b>Last Updated:</b> <?php echo htmlentities($data['updationDate']); ?></p>
                                                    <?php } ?>
                                                    <hr />
                                                    <form method="post">
                                                        <div class="form-group">
                                                            <label>User Name</label>
                                                            <input type="text" name="fname" class="form-control" value="<?php echo htmlentities($data['fullName']); ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Address</label>
                                                            <textarea name="address" class="form-control"><?php echo htmlentities($data['address']); ?></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>City</label>
                                                            <input type="text" name="city" class="form-control" value="<?php echo htmlentities($data['city']); ?>">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Gender</label>
                                                            <select name="gender" class="form-control" required>
                                                                <option value="<?php echo htmlentities($data['gender']); ?>"><?php echo htmlentities($data['gender']); ?></option>
                                                                <option value="male">Male</option>
                                                                <option value="female">Female</option>
                                                                <option value="other">Other</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>User Email</label>
                                                            <input type="email" class="form-control" readonly value="<?php echo htmlentities($data['email']); ?>">
                                                            <a href="change-emaild.php">Update your email</a>
                                                        </div>
                                                        <button type="submit" name="submit" class="btn btn-primary">Update</button>
                                                    </form>
                                                <?php } ?>
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
        </div>
    </div>
    <!-- JS -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
