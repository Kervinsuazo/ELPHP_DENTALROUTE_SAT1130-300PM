<?php
session_start();
include("include/config.php");

// Ensure session variables exist
if (!isset($_SESSION['cnumber']) || !isset($_SESSION['email'])) {
    header("Location: forgot-password.php");
    exit();
}

// Warning message variable
$warningMessage = "";

// Update password
if (isset($_POST['change'])) {
    $cno = $_SESSION['cnumber'];
    $email = $_SESSION['email'];
    $newpassword = $_POST['password'];
    $confirmPassword = $_POST['password_again'];

    if ($newpassword !== $confirmPassword) {
        $warningMessage = "Passwords do not match.";
    } else {
        $query = mysqli_query($con, "UPDATE doctors SET password='$newpassword' WHERE contactno='$cno' AND docEmail='$email'");
        if ($query) {
            session_unset(); // Clear session data
            session_destroy();
            echo "<script>alert('Password successfully updated. Please log in.');</script>";
            echo "<script>window.location.href ='index.php'</script>";
            exit();
        } else {
            $warningMessage = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Password Reset | DentalRoute</title>
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
        .reset-container {
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .reset-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            width: 400px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
            color: black;
            text-align: center;
        }
        .reset-box h2 {
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
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 10px;
            cursor: pointer;
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
    
    <script>
        function validatePassword() {
            var password = document.getElementById("password").value;
            var confirm_password = document.getElementById("password_again").value;
            if (password !== confirm_password) {
                showWarning("Passwords do not match.");
                return false;
            }
            return true;
        }

        function togglePassword(id) {
            var field = document.getElementById(id);
            var icon = document.getElementById(id + "-toggle");
            if (field.type === "password") {
                field.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                field.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            }
        }

        function showWarning(message) {
            var warningBox = document.getElementById('warning-box');
            warningBox.innerHTML = message;
            warningBox.style.display = "block";
        }
    </script>
</head>
<body>

<div class="reset-container">
    <div class="reset-box">
        <h2>Password Reset</h2>
        
        <!-- Warning Messages -->
        <div id="warning-box" class="alert alert-warning" <?php if($warningMessage != "") echo 'style="display:block;"'; ?>>
            <?php echo $warningMessage; ?>
        </div>

        <form method="post" onsubmit="return validatePassword();">
            <div class="form-group position-relative">
                <input type="password" class="form-control" id="password" name="password" placeholder="New Password" required>
                <i class="fa fa-eye toggle-password" id="password-toggle" onclick="togglePassword('password')"></i>
            </div>
            <div class="form-group position-relative">
                <input type="password" class="form-control" id="password_again" name="password_again" placeholder="Confirm Password" required>
                <i class="fa fa-eye toggle-password" id="password_again-toggle" onclick="togglePassword('password_again')"></i>
            </div>
            <button type="submit" class="btn btn-primary" name="change">Reset Password</button>
            <div class="new-account">
                Already have an account? <a href="index.php">Log in</a>
            </div>
        </form>
    </div>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>

</body>
</html>
