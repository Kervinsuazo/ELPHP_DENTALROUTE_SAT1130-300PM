<?php
session_start();
error_reporting(0);
include("include/config.php");

// Warning message variable
$warningMessage = "";

// Code for updating Password
if(isset($_POST['change']))
{
    $name = $_SESSION['name'];
    $email = $_SESSION['email'];
    $newpassword = md5($_POST['password']);

    // Prevent SQL Injection
    $name = mysqli_real_escape_string($con, $name);
    $email = mysqli_real_escape_string($con, $email);
    $newpassword = mysqli_real_escape_string($con, $newpassword);

    $query = mysqli_query($con, "UPDATE users SET password='$newpassword' WHERE fullName='$name' AND email='$email'");
    
    if ($query) {
        echo "<script>alert('Password successfully updated.');</script>";
        echo "<script>window.location.href ='user-login.php'</script>";
        exit();
    } else {
        $warningMessage = "Error updating password. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Reset Password | DentalRoute</title>

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
        /* Warning Messages */
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

    <script type="text/javascript">
        function validateForm() {
            let password = document.getElementById("password").value;
            let confirmPassword = document.getElementById("password_again").value;
            if (password !== confirmPassword) {
                showWarning("Passwords do not match!");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>

<div class="auth-container">
    <div class="auth-box">
        <h2>Reset Password</h2>
        
        <!-- Warning Messages -->
        <div id="warning-box" class="alert alert-warning" <?php if($warningMessage != "") echo 'style="display:block;"'; ?>>
            <?php echo $warningMessage; ?>
        </div>

        <form method="post" onsubmit="return validateForm();">
            <div class="form-group">
                <input type="password" class="form-control" id="password" name="password" placeholder="New Password" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" id="password_again" name="password_again" placeholder="Confirm Password" required>
            </div>

            <button type="submit" name="change" class="btn btn-primary">Update Password</button>

            <div class="new-account">
                <a href="user-login.php">Back to Login</a>
            </div>
        </form>
    </div>
</div>

<!-- Security Scripts -->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>

<script>
    function showWarning(message) {
        document.getElementById('warning-box').innerHTML = message;
        document.getElementById('warning-box').style.display = "block";
    }

    // Disable Right Click
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

    // Block Special Characters & Paste
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
