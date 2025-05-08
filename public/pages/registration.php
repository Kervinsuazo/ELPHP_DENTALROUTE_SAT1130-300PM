<?php
include_once('include/config.php');

// API Registration: application/json
if (isset($_GET['api']) && $_GET['api'] === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');

    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data || !isset($data['full_name'], $data['address'], $data['city'], $data['gender'], $data['email'], $data['password'], $data['password_again'])) {
        echo json_encode(["success" => false, "message" => "Missing required fields."]);
        exit();
    }

    $fname = $data['full_name'];
    $address = $data['address'];
    $city = $data['city'];
    $gender = $data['gender'];
    $email = $data['email'];
    $password = $data['password'];
    $password_again = $data['password_again'];

    if ($password !== $password_again) {
        echo json_encode(["success" => false, "message" => "Passwords do not match."]);
        exit();
    }

    $hashed_password = md5($password); // Upgrade to password_hash() for better security

    $query = mysqli_query($con, "INSERT INTO users(fullname, address, city, gender, email, password) 
                                 VALUES('$fname', '$address', '$city', '$gender', '$email', '$hashed_password')");

    if ($query) {
        echo json_encode(["success" => true, "message" => "User registered successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Registration failed."]);
    }
    exit();
}

// HTML form-based registration
if (isset($_POST['submit'])) {
    $fname = $_POST['full_name'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $password = md5($_POST['password']); // MD5 Hash

    $query = mysqli_query($con, "INSERT INTO users(fullname, address, city, gender, email, password) 
                                 VALUES('$fname', '$address', '$city', '$gender', '$email', '$password')");

    if ($query) {
        header("Location: user-login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Patient Registration | DentalRoute</title>
    
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
        .register-container {
            width: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-box {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 15px;
            padding: 30px;
            width: 400px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.2);
            color: black;
            text-align: center;
        }
        .register-box h2 {
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
        .login-link {
            text-align: center;
            margin-top: 15px;
        }
        .login-link a {
            color: black;
            font-weight: 600;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="register-container">
    <div class="register-box">
        <h2>DentalRoute | Patient Registration</h2>

        <form name="registration" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="full_name" placeholder="Full Name" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="address" placeholder="Address" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="city" placeholder="City" required>
            </div>
            <div class="form-group">
                <label class="block">Gender</label>
                <div class="radio">
                    <label><input type="radio" name="gender" value="female" required> Female</label>
                    <label><input type="radio" name="gender" value="male"> Male</label>
                </div>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password_again" placeholder="Confirm Password" required>
            </div>
            <button type="submit" class="btn btn-primary" name="submit">Register</button>
        </form>

        <div class="login-link">
            <a href="user-login.php">Already have an account? Log in</a>
        </div>
    </div>
</div>

</body>
</html>
