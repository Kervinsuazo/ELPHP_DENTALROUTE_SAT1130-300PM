<?php
session_start();
error_reporting(0);
include('include/config.php');

// Handle API Mode
if (isset($_GET['api']) && $_GET['api'] == 'users') {
    header('Content-Type: application/json');

    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case 'GET':
            $query = mysqli_query($con, "SELECT * FROM users");
            $users = [];
            while ($row = mysqli_fetch_assoc($query)) {
                $users[] = $row;
            }
            echo json_encode(['status' => 'success', 'data' => $users]);
            break;

        case 'POST':
            $input = json_decode(file_get_contents('php://input'), true);
            if (!$input) {
                echo json_encode(['status' => 'error', 'message' => 'Invalid JSON']);
                exit;
            }

            $stmt = $con->prepare("INSERT INTO users (fullName, address, city, gender, email, regDate) VALUES (?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("sssss", $input['fullName'], $input['address'], $input['city'], $input['gender'], $input['email']);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'User created']);
            } else {
                echo json_encode(['status' => 'error', 'message' => $stmt->error]);
            }
            break;

        case 'PUT':
            $input = json_decode(file_get_contents('php://input'), true);
            if (!isset($input['id'])) {
                echo json_encode(['status' => 'error', 'message' => 'Missing user ID']);
                exit;
            }

            $stmt = $con->prepare("UPDATE users SET fullName=?, address=?, city=?, gender=?, email=?, updationDate=NOW() WHERE id=?");
            $stmt->bind_param("sssssi", $input['fullName'], $input['address'], $input['city'], $input['gender'], $input['email'], $input['id']);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'User updated']);
            } else {
                echo json_encode(['status' => 'error', 'message' => $stmt->error]);
            }
            break;

        case 'DELETE':
            $input = json_decode(file_get_contents('php://input'), true);
            if (!isset($input['id'])) {
                echo json_encode(['status' => 'error', 'message' => 'Missing user ID']);
                exit;
            }

            $stmt = $con->prepare("DELETE FROM users WHERE id=?");
            $stmt->bind_param("i", $input['id']);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'User deleted']);
            } else {
                echo json_encode(['status' => 'error', 'message' => $stmt->error]);
            }
            break;

        default:
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method Not Allowed']);
    }
    exit;
}

// HTML view below
if (strlen($_SESSION['id']) == 0) {
    header('location:logout.php');
} else {
    if (isset($_GET['del'])) {
        $uid = $_GET['id'];
        mysqli_query($con, "DELETE FROM users WHERE id ='$uid'");
        $_SESSION['msg'] = "data deleted !!";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin | Manage Users</title>
    <link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="vendor/fontawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="vendor/themify-icons/themify-icons.min.css">
    <link href="vendor/animate.css/animate.min.css" rel="stylesheet" media="screen">
    <link href="vendor/perfect-scrollbar/perfect-scrollbar.min.css" rel="stylesheet" media="screen">
    <link href="vendor/switchery/switchery.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css" rel="stylesheet" media="screen">
    <link href="vendor/select2/select2.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-datepicker/bootstrap-datepicker3.standalone.min.css" rel="stylesheet" media="screen">
    <link href="vendor/bootstrap-timepicker/bootstrap-timepicker.min.css" rel="stylesheet" media="screen">
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="assets/css/plugins.css">
    <link rel="stylesheet" href="assets/css/themes/theme-1.css" id="skin_color" />
</head>
<body>
<div id="app">        
    <?php include('include/sidebar.php');?>
    <div class="app-content">
        <?php include('include/header.php');?>
        <div class="main-content" >
            <div class="wrap-content container" id="container">
                <section id="page-title">
                    <div class="row">
                        <div class="col-sm-8">
                            <h1 class="mainTitle">Admin | Manage Users</h1>
                        </div>
                        <ol class="breadcrumb">
                            <li><span>Admin</span></li>
                            <li class="active"><span>Manage Users</span></li>
                        </ol>
                    </div>
                </section>
                <div class="container-fluid container-fullw bg-white">
                    <div class="row">
                        <div class="col-md-12">
                            <h5 class="over-title margin-bottom-15">Manage <span class="text-bold">Users</span></h5>
                            <p style="color:red;"><?php echo htmlentities($_SESSION['msg']); echo htmlentities($_SESSION['msg']="");?></p>    
                            <table class="table table-hover" id="sample-table-1">
                                <thead>
                                    <tr>
                                        <th class="center">#</th>
                                        <th>Full Name</th>
                                        <th class="hidden-xs">Address</th>
                                        <th>City</th>
                                        <th>Gender </th>
                                        <th>Email </th>
                                        <th>Creation Date </th>
                                        <th>Updation Date </th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
<?php
$sql = mysqli_query($con, "SELECT * FROM users");
$cnt = 1;
while ($row = mysqli_fetch_array($sql)) {
?>
<tr>
    <td class="center"><?php echo $cnt; ?>.</td>
    <td class="hidden-xs"><?php echo $row['fullName']; ?></td>
    <td><?php echo $row['address']; ?></td>
    <td><?php echo $row['city']; ?></td>
    <td><?php echo $row['gender']; ?></td>
    <td><?php echo $row['email']; ?></td>
    <td><?php echo $row['regDate']; ?></td>
    <td><?php echo $row['updationDate']; ?></td>
    <td>
        <a href="manage-users.php?id=<?php echo $row['id']?>&del=delete" onClick="return confirm('Are you sure you want to delete?')" class="btn btn-transparent btn-xs tooltips" tooltip-placement="top" tooltip="Remove"><i class="fa fa-times fa fa-white"></i></a>
    </td>
</tr>
<?php 
    $cnt++;
} ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include('include/footer.php');?>
    <?php include('include/setting.php');?>
</div>

<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<script src="vendor/modernizr/modernizr.js"></script>
<script src="vendor/jquery-cookie/jquery.cookie.js"></script>
<script src="vendor/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="vendor/switchery/switchery.min.js"></script>
<script src="vendor/maskedinput/jquery.maskedinput.min.js"></script>
<script src="vendor/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js"></script>
<script src="vendor/autosize/autosize.min.js"></script>
<script src="vendor/selectFx/classie.js"></script>
<script src="vendor/selectFx/selectFx.js"></script>
<script src="vendor/select2/select2.min.js"></script>
<script src="vendor/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
<script src="vendor/bootstrap-timepicker/bootstrap-timepicker.min.js"></script>
<script src="assets/js/main.js"></script>
<script src="assets/js/form-elements.js"></script>
<script>
    jQuery(document).ready(function() {
        Main.init();
        FormElements.init();
    });
</script>
</body>
</html>
<?php } ?>
