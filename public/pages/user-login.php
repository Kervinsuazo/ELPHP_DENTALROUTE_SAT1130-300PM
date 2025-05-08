<?php 
session_start();
error_reporting(0);
include("include/config.php");

$warningMessage = "";
$isJsonRequest = isset($_SERVER["CONTENT_TYPE"]) && strpos($_SERVER["CONTENT_TYPE"], "application/json") !== false;

// JSON login handler
if ($isJsonRequest) {
    header("Content-Type: application/json");

    $jsonData = json_decode(file_get_contents("php://input"), true);

    if (!isset($jsonData['username']) || !isset($jsonData['password'])) {
        echo json_encode([
            "success" => false,
            "message" => "Missing username or password in JSON body."
        ]);
        exit();
    }

    $puname = mysqli_real_escape_string($con, $jsonData['username']);  
    $ppwd = md5(mysqli_real_escape_string($con, $jsonData['password']));

    $ret = mysqli_query($con, "SELECT * FROM users WHERE email='$puname' and password='$ppwd'");
    $num = mysqli_fetch_array($ret);

    if ($num > 0) {
        $_SESSION['login'] = $puname;
        $_SESSION['id'] = $num['id'];
        $pid = $num['id'];
        $uip = $_SERVER['REMOTE_ADDR'];
        $status = 1;

        mysqli_query($con, "INSERT INTO userlog(uid, username, userip, status) VALUES ('$pid', '$puname', '$uip', '$status')");

        echo json_encode([
            "success" => true,
            "message" => "Login successful",
            "userId" => $pid
        ]);
        exit();
    } else {
        $uip = $_SERVER['REMOTE_ADDR'];
        $status = 0;
        mysqli_query($con, "INSERT INTO userlog(username, userip, status) VALUES ('$puname', '$uip', '$status')");

        echo json_encode([
            "success" => false,
            "message" => "Invalid username or password"
        ]);
        exit();
    }
}

// HTML form login handler
if (isset($_POST['submit'])) {
    $puname = mysqli_real_escape_string($con, $_POST['username']);  
    $ppwd = md5(mysqli_real_escape_string($con, $_POST['password']));

    $ret = mysqli_query($con, "SELECT * FROM users WHERE email='$puname' and password='$ppwd'");
    $num = mysqli_fetch_array($ret);

    if ($num > 0) {
        $_SESSION['login'] = $_POST['username'];
        $_SESSION['id'] = $num['id'];
        $pid = $num['id'];
        $uip = $_SERVER['REMOTE_ADDR'];
        $status = 1;

        mysqli_query($con, "INSERT INTO userlog(uid, username, userip, status) VALUES ('$pid', '$puname', '$uip', '$status')");
        header("Location: dashboard.php");
        exit();
    } else {
        $uip = $_SERVER['REMOTE_ADDR'];
        $status = 0;
        mysqli_query($con, "INSERT INTO userlog(username, userip, status) VALUES ('$puname', '$uip', '$status')");
        $warningMessage = " Invalid username or password. Please try again.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Patient Login | DentalRoute</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">

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
            text-align: center;
        }
        .login-box h2 {
            font-weight: 600;
            margin-bottom: 20px;
            color: #003366;
        }
        .form-control {
            background: rgba(255, 255, 255, 0.3);
            border: none;
            color: black;
            margin-top: 10px;
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
        .new-account {
            text-align: center;
            margin-top: 15px;
        }
        .new-account a {
            color: black;
            font-weight: 600;
        }
        .new-account a:hover {
            text-decoration: underline;
        }
        .alert {
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            font-weight: bold;
            text-align: center;
            display: none;
        }
        .alert-warning {
            background: #ffcc00;
            color: black;
            border-left: 5px solid #ff9900;
        }
    </style>
</head>
<body>

<div class="login-container">
    <div class="login-box">
        <h2>DentalRoute | Patient Login</h2>
        
        <div id="warning-box" class="alert alert-warning" <?php if($warningMessage != "") echo 'style="display:block;"'; ?>>
            <?php echo $warningMessage; ?>
        </div>

        <form method="post">
            <div class="form-group">
                <input type="email" class="form-control" name="username" placeholder="Email" required 
                onkeypress="return blockSpecialChars(event)" onpaste="return false">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required 
                onkeypress="return blockSpecialChars(event)" onpaste="return false">
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Login</button>

            <div class="new-account">
                <a href="forgot-password.php">Forgot Password?</a> | 
                <a href="registration.php">Create an account</a>
            </div>
        </form>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>

<script>
    function showWarning(message) {
        document.getElementById('warning-box').innerHTML = message;
        document.getElementById('warning-box').style.display = "block";
    }

    document.addEventListener('contextmenu', function(event) {
        event.preventDefault();
        showWarning(" Right-click disabled by Administrator.");
    });

    document.addEventListener("keydown", function(event) {
        if (event.ctrlKey && (event.key === "u" || event.key === "i" || event.key === "j")) {
            event.preventDefault();
            showWarning(" Developer mode is disabled!");
        }
        if (event.keyCode == 123) {
            event.preventDefault();
            showWarning("Developer mode is disabled!");
        }
    });

    function blockSpecialChars(event) {
        var regex = new RegExp("^[a-zA-Z0-9@.]*$");
        var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
        if (!regex.test(key)) {
            event.preventDefault();
            return false;
        }
    }
</script>

</body>
</html>
