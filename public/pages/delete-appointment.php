<?php
session_start();
header('Content-Type: application/json');
include('include/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Read raw input
    $input = json_decode(file_get_contents("php://input"), true);

    // Validate session and input
    if (!isset($_SESSION['id']) || empty($input['id'])) {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid request or unauthorized."
        ]);
        exit;
    }

    $appointmentId = intval($input['id']);
    $userId = $_SESSION['id'];

    // Delete or cancel logic
    $query = mysqli_query($con, "UPDATE appointment SET userStatus='0' WHERE id='$appointmentId' AND userId='$userId'");

    if ($query) {
        echo json_encode([
            "status" => "success",
            "message" => "Appointment canceled."
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Could not cancel appointment."
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid request method."
    ]);
}
?>
