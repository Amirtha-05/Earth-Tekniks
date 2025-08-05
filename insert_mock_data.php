<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "moisture_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$moisture = rand(10, 90);
$min = $moisture - rand(1, 5);
$max = $moisture + rand(1, 5);
$avg = ($min + $moisture + $max) / 3;
$temp = rand(25, 40);
$status = $moisture > 50 ? 'OK' : 'LOW';

$sql = "INSERT INTO moisture_readings (moisture_percentage, moisture_min, moisture_max, moisture_avg, temperature, gauge_status, timestamp)
        VALUES ($moisture, $min, $max, $avg, $temp, '$status', NOW())";

if ($conn->query($sql) === TRUE) {
  echo "New data inserted";
} else {
  echo "Error: " . $conn->error;
}

$conn->close();
?>
