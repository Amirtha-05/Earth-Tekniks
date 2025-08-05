<?php
$mysqli = new mysqli("localhost", "root", "", "moisture_db");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Get POST data safely
$device_id = $_POST['device_id'];
$volt_5 = $_POST['volt_5'];
$volt_15 = $_POST['volt_15'];
$volt_neg_15 = $_POST['volt_neg_15'];
$volt_neg_5 = $_POST['volt_neg_5'];
$volt_3_3 = $_POST['volt_3_3'];
$mains_frequency = $_POST['mains_frequency'];
$lamp_voltage = $_POST['lamp_voltage'];
$lamp_current = $_POST['lamp_current'];
$lamp_status = $_POST['lamp_status'];
$gauge_temperature = $_POST['gauge_temperature'];
$pwm_output = $_POST['pwm_output'];
$wheel_frequency = $_POST['wheel_frequency'];

// Prepare and bind safely (recommended)
$stmt = $mysqli->prepare("INSERT INTO diagnostics (
    device_id, volt_5, volt_15, volt_neg_15, volt_neg_5, volt_3_3,
    mains_frequency, lamp_voltage, lamp_current, lamp_status,
    gauge_temperature, pwm_output, wheel_frequency
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("iddddddiddsdd", $device_id, $volt_5, $volt_15, $volt_neg_15, $volt_neg_5, $volt_3_3,
                             $mains_frequency, $lamp_voltage, $lamp_current, $lamp_status,
                             $gauge_temperature, $pwm_output, $wheel_frequency);

if ($stmt->execute()) {
    echo "<script>alert('Diagnostic data inserted successfully'); window.location.href = 'diagnostics_form.html';</script>";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$mysqli->close();
?>
