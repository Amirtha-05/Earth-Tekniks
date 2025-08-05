<?php
header('Content-Type: application/json');

// Connect to moisture_db
$conn = new mysqli("localhost", "root", "", "moisture_db");
if ($conn->connect_error) {
    echo json_encode(["error" => "DB connection failed"]);
    exit;
}

// Optional device_id filtering
$device_id = isset($_GET['device_id']) ? intval($_GET['device_id']) : 0;

if ($device_id) {
    $stmt = $conn->prepare("
        SELECT * FROM moisture_readings 
        WHERE device_id = ? 
        ORDER BY timestamp ASC
    ");
    $stmt->bind_param("i", $device_id);
} else {
    $stmt = $conn->prepare("
        SELECT * FROM moisture_readings 
        ORDER BY timestamp ASC
    ");
}

$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
$stmt->close();
$conn->close();
?>
