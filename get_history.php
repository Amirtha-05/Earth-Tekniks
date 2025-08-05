<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "moisture_db";

$conn = new mysqli($servername, $username, $password, $dbname);

// Fetch last 100 entries (or adjust as needed)
$sql = "SELECT timestamp, moisture_percentage FROM moisture_readings ORDER BY timestamp DESC LIMIT 100";
$result = $conn->query($sql);

$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

$conn->close();

header('Content-Type: application/json');
echo json_encode(array_reverse($data)); // oldest to newest
?>
