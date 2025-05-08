<?php
session_start();
include("include/config.php");
error_reporting(0);

// Check if the request is a JSON request
$isJsonRequest = isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false;

// JSON Request Handler
if ($isJsonRequest) {
    header("Content-Type: application/json");

    // Get the raw POST data (JSON)
    $jsonData = json_decode(file_get_contents("php://input"), true);

    if (!isset($jsonData['username'], $jsonData['password'])) {
        echo json_encode([
            "success" => false,
            "message" => "Missing required fields in the JSON request."
        ]);
        exit();
    }

    // Prevent SQL Injection
    $uname = mysqli_real_escape_string($con, $jsonData['username']);
    $dpassword = mysqli_real_escape_string($con, $jsonData['password']);

    $ret = mysqli_query($con, "SELECT * FROM admin WHERE username='$uname' AND password='$dpassword'");
    $num = mysqli_fetch_array($ret);

    if ($num > 0) {
        $_SESSION['login'] = $uname;
        $_SESSION['id'] = $num['id'];
        $uid = $num['id'];
        $uip = $_SERVER['REMOTE_ADDR'];
        $status = 1;

        // Insert log
        mysqli_query($con, "INSERT INTO admin_log(uid, username, userip, status) VALUES ('$uid', '$uname', '$uip', '$status')");

        echo json_encode([
            "success" => true,
            "message" => "Login successful. Redirecting to dashboard.",
            "redirect" => "dashboard.php"
        ]);
        exit();
    } else {
        $uip = $_SERVER['REMOTE_ADDR'];
        $status = 0;
        mysqli_query($con, "INSERT INTO admin_log(username, userip, status) VALUES ('$uname', '$uip', '$status')");

        echo json_encode([
            "success" => false,
            "message" => "Invalid username or password"
        ]);
        exit();
    }
}

// HTML Form Handler (for regular form submissions)
if (isset($_POST['submit'])) {
    $uname = $_POST['username'];
    $dpassword = $_POST['password']; // No hashing

    $ret = mysqli_query($con, "SELECT * FROM admin WHERE username='$uname' AND password='$dpassword'");
    $num = mysqli_fetch_array($ret);

    if ($num > 0) {
        $_SESSION['login'] = $_POST['username'];
        $_SESSION['id'] = $num['id'];
        $uid = $num['id'];
        $uip = $_SERVER['REMOTE_ADDR'];
        $status = 1;

        // Insert log
        mysqli_query($con, "INSERT INTO admin_log(uid, username, userip, status) VALUES ('$uid', '$uname', '$uip', '$status')");

        header("location:dashboard.php");
        exit();
    } else {
        $uip = $_SERVER['REMOTE_ADDR'];
        $status = 0;
        mysqli_query($con, "INSERT INTO admin_log(username, userip, status) VALUES ('$uname', '$uip', '$status')");

        echo "<script>alert('Invalid username or password');</script>";
        echo "<script>window.location.href='index.php'</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Login</title>

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">

    <!-- Custom Styles -->
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('assets/images/background.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .login-container {
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            width: 400px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
            color: black;
        }
        .login-box h2 {
            text-align: center;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .form-control {
            background: rgba(255, 255, 255, 0.3);
            border: none;
            color: black;
        }
        .form-control::placeholder {
            color: rgba(0, 0, 0, 0.6);
        }
        .btn-primary {
            background: #0056b3;
            border: none;
            width: 100%;
            padding: 10px;
            border-radius: 5px;
        }
        .btn-primary:hover {
            background: #004494;
        }
        .forgot-password {
            text-align: right;
            display: block;
            margin-top: 10px;
            color: black;
        }
        .forgot-password:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-box">
        <h2>Admin Login</h2>
        <form method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Login</button>
            <a href="forgot-password.php" class="forgot-password">Forgot Password?</a>
        </form>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>

</body>
</html>
