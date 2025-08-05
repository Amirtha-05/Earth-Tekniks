<?php
include 'db_connect.php';

header('Content-Type: application/json');

// Get data from POST
$device_id = $_POST['device_id'] ?? null;
$moisture = $_POST['moisture_percentage'] ?? null;
$min = $_POST['moisture_min'] ?? null;
$max = $_POST['moisture_max'] ?? null;
$avg = $_POST['moisture_avg'] ?? null;
$status = $_POST['gauge_status'] ?? 'Good';
$temp = $_POST['temperature'] ?? null;
$time = date('Y-m-d H:i:s');

// Check for missing values
if ($device_id === null || $moisture === null || $min === null || $max === null || $avg === null || $temp === null) {
    echo json_encode(["success" => false, "error" => "Missing required fields"]);
    exit;
}

// Prepare SQL query
$sql = "INSERT INTO moisture_readings 
    (device_id, moisture_percentage, moisture_min, moisture_max, moisture_avg, gauge_status, temperature, timestamp) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(["success" => false, "error" => "Prepare failed: " . $conn->error]);
    exit;
}

// Bind parameters: idddddss → i (device_id), dddd (moisture), s (status), d (temp), s (timestamp)
$stmt->bind_param("iddddsss", $device_id, $moisture, $min, $max, $avg, $status, $temp, $time);

// Execute
if ($stmt->execute()) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "error" => "Execute failed: " . $stmt->error]);
}

$stmt->close();
$conn->close();
?>
