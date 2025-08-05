<?php
header('Content-Type: application/json');

// Connect to DB
$conn = new mysqli("localhost", "root", "", "moisture_db");
if ($conn->connect_error) {
    echo json_encode(["error" => "Database connection failed"]);
    exit;
}

$device_id = isset($_GET['device_id']) ? intval($_GET['device_id']) : 0;

if (!$device_id) {
    echo json_encode(["error" => "Invalid device_id"]);
    exit;
}

$query = "SELECT id, device_name, serial_number, company_name, location FROM devices WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $device_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(["error" => "Device not found"]);
}

$stmt->close();
$conn->close();
?>
