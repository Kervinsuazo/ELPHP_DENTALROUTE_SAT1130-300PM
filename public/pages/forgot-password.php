<?php
session_start();
error_reporting(0);
include("include/config.php");

// Warning message variable
$warningMessage = "";

// ======================
// API MODE
// ======================
if (isset($_GET['api']) && $_GET['api'] === 'password' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json; charset=UTF-8');

    $json = file_get_contents("php://input");
    $data = json_decode($json, true);

    $name = isset($data['fullname']) ? mysqli_real_escape_string($con, $data['fullname']) : '';
    $email = isset($data['email']) ? mysqli_real_escape_string($con, $data['email']) : '';

    if (empty($name) || empty($email)) {
        echo json_encode([
            "status" => "error",
            "message" => "Missing fullname or email."
        ]);
        exit();
    }

    $query = mysqli_query($con, "SELECT id FROM users WHERE fullName='$name' AND email='$email'");
    if (mysqli_num_rows($query) > 0) {
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        echo json_encode([
            "status" => "success",
            "message" => "User verified. Proceed to reset password.",
            "redirect" => "reset-password.php"
        ]);
        exit();
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid name or email."
        ]);
        exit();
    }
}

// ======================
// HTML FORM MODE
// ======================
if (isset($_POST['submit'])) {
    $name = $_POST['fullname'];
    $email = $_POST['email'];

    // Prevent SQL Injection
    $name = mysqli_real_escape_string($con, $name);
    $email = mysqli_real_escape_string($con, $email);

    $query = mysqli_query($con, "SELECT id FROM users WHERE fullName='$name' AND email='$email'");
    $row = mysqli_num_rows($query);

    if ($row > 0) {
        $_SESSION['name'] = $name;
        $_SESSION['email'] = $email;
        header('location:reset-password.php');
        exit();
    } else {
        $warningMessage = "Invalid details. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Forgot Password | DentalRoute</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('assets/images/background.jpg') no-repeat center center fixed;
            background-size: cover;
        }
        .auth-container {
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            width: 400px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
            color: black;
            text-align: center;
        }
        .auth-box h2 {
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

<div class="auth-container">
    <div class="auth-box">
        <h2>Forgot Password</h2>

        <!-- Warning Message -->
        <div id="warning-box" class="alert alert-warning" <?php if ($warningMessage != "") echo 'style="display:block;"'; ?>>
            <?php echo $warningMessage; ?>
        </div>

        <form method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="fullname" placeholder="Registered Full Name" required 
                       onkeypress="return blockSpecialChars(event)" onpaste="return false">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Registered Email" required 
                       onkeypress="return blockSpecialChars(event)" onpaste="return false">
            </div>

            <button type="submit" name="submit" class="btn btn-primary">Reset Password</button>

            <div class="new-account">
                <a href="user-login.php">Back to Login</a>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>

<script>
    function showWarning(message) {
        document.getElementById('warning-box').innerHTML = message;
        document.getElementById('warning-box').style.display = "block";
    }

    // Disable right-click
    document.addEventListener('contextmenu', function(event) {
        event.preventDefault();
        showWarning(" Right-click disabled by Administrator.");
    });

    // Disable Developer Tools
    document.addEventListener("keydown", function(event) {
        if (event.ctrlKey && (event.key === "u" || event.key === "i" || event.key === "j")) {
            event.preventDefault();
            showWarning(" Developer mode is disabled!");
        }
        if (event.keyCode == 123) { // F12
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
