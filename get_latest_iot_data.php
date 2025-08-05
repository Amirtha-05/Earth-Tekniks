<?php
$connect = new mysqli("localhost", "root", "", "moisture_db");

$sql = "SELECT * FROM moisture_readings ORDER BY id DESC LIMIT 1";
$result = $connect->query($sql);

if ($result && $row = $result->fetch_assoc()) {
    echo json_encode($row);
} else {
    echo json_encode(["error" => "No data"]);
}
?>
