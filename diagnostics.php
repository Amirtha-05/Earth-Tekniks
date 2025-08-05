<?php
$mysqli = new mysqli("localhost", "root", "", "moisture_db");
$current_page = basename($_SERVER['PHP_SELF']);
$device_id = isset($_GET['device_id']) ? intval($_GET['device_id']) : null;

if ($mysqli->connect_error) {
  die("Connection failed: " . $mysqli->connect_error);
}

$device_id = isset($_GET['device_id']) ? intval($_GET['device_id']) : null;

if ($device_id) {
    $diagnostic_sql = "SELECT * FROM diagnostics WHERE device_id = $device_id ORDER BY timestamp DESC LIMIT 1";
    $device_sql = "SELECT * FROM devices WHERE id = $device_id LIMIT 1";
} else {
    $diagnostic_sql = "SELECT * FROM diagnostics ORDER BY timestamp DESC LIMIT 1";
    $device_sql = null;
}

$diagnostic_result = $mysqli->query($diagnostic_sql);
$diagnostic = $diagnostic_result ? $diagnostic_result->fetch_assoc() : null;

$device = null;
if ($device_sql) {
    $device_result = $mysqli->query($device_sql);
    $device = $device_result ? $device_result->fetch_assoc() : null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Diagnostics - Earth Tekniks</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body {
      background-color: #f4f4f4;
      margin: 0;
      font-family: Arial, sans-serif;
    }
    .sidebar {
      width: 220px;
      background-color: #2d3e50;
      color: white;
      padding: 20px 10px;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      overflow-y: auto;
    }
    .sidebar h3 {
      font-size: 22px;
      text-align: center;
      margin-bottom: 30px;
    }
    .sidebar ul {
      list-style: none;
      padding: 0;
    }
    .sidebar ul li {
      margin-bottom: 10px;
    }
    .sidebar ul li a {
      color: white;
      text-decoration: none;
      display: block;
      padding: 10px;
      border-radius: 5px;
      transition: background-color 0.3s ease;
    }
    .sidebar ul li a:hover,
    .sidebar ul li a.active {
      background-color: #1a2533;
      font-weight: bold;
    }
    .main-content {
      margin-left: 240px;
      padding: 30px;
    }
    .device-info-container {
      border: 1px solid #007BFF;
      border-radius: 6px;
      background: #fff;
      margin-bottom: 20px;
      box-shadow: 0 2px 6px rgba(0, 123, 255, 0.15);
      overflow: hidden;
    }
    .device-info-header {
      background-color: #007bff;
      color: white;
      font-weight: bold;
      font-size: 16px;
      padding: 12px 20px;
    }
    .device-info-body {
      padding: 0 20px;
    }
    .device-info-row {
      display: flex;
      justify-content: space-between;
      padding: 12px 0;
      border-bottom: 1px solid #eaeaea;
      font-size: 14px;
    }
    .device-info-row:last-child {
      border-bottom: none;
    }
    .content-box {
      background: #fff;
      border-radius: 6px;
      padding: 20px;
      box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }
    .content-box {
  background-color: #095b92;  /* Blue color, like your first image */
  color: white;               /* To make the text readable */
  border-radius: 8px;
  padding: 15px;
  box-shadow: 0 0 5px rgba(0,0,0,0.1);
  margin-bottom: 15px;
}
  </style>
</head>
<body>

<!-- Navbar/Header -->
<nav class="navbar navbar-expand-lg" style="background-color: #2c3e50;">
  <div class="container-fluid">
    <a class="navbar-brand d-flex align-items-center text-white" href="#">
      <img src="logo-icon.png" alt="Logo" width="30" height="30" class="me-2">
      <strong>Earth Tekniks</strong>
    </a>
    <div class="d-flex align-items-center gap-4 me-4">
      <a href="index.php" class="text-white" title="Home">
        <i class="bi bi-house-door-fill fs-5"></i>
      </a>
      <a href="#" class="text-white" title="Print">
        <i class="bi bi-printer-fill fs-5"></i>
      </a>
      <a href="logout.php" class="text-white" title="Logout">
        <i class="bi bi-power fs-5"></i>
      </a>
    </div>
  </div>
</nav>

<!-- Sidebar -->
<div class="sidebar">
  <h3>Earth Tekniks</h3>
  <ul>
    <li><a href="index.html?ref=index.html">Dashboard</a></li>
    <li><a href="diagnostics.php?ref=diagnostics.php" class="active">Diagnostics</a></li>
    <li><a href="report.html?ref=report.html">DT Reports</a></li>
    <li><a href="settings.php?ref=settings.php">Settings</a></li>
    <li><a href="alarm.html?ref=alarm_report.php">Alarm</a></li>
    <li><a href="information.html?ref=information.php">Information</a></li>
  </ul>
</div>


<!-- Main Content -->
<div class="main-content">
  <h4 class="mb-4">DIAGNOSTICS</h4>

  <?php if ($device): ?>
    <div class="device-info-container">
      <div class="device-info-header">DEVICE INFORMATION</div>
      <div class="device-info-body">
        <div class="device-info-row"><span>Company Name</span><span><?= $device['company_name'] ?></span></div>
        <div class="device-info-row"><span>Device Id</span><span><?= $device['id'] ?></span></div>
        <div class="device-info-row"><span>Device Name</span><span><?= $device['device_name'] ?></span></div>
        <div class="device-info-row"><span>Serial Number</span><span><?= $device['serial_number'] ?></span></div>
        <div class="device-info-row"><span>Location</span><span><?= $device['location'] ?></span></div>
      </div>
    </div>
  <?php endif; ?>

  <div class="content-box">
    <h5 class="mb-3">Diagnostics Readings</h5>
    <div class="row">
      <div class="col-md-4">
        <label>+5 Volt</label>
        <input type="text" class="form-control mb-2" value="<?= $diagnostic['volt_5'] ?? '' ?>" readonly>
        <label>+15 Volt</label>
        <input type="text" class="form-control mb-2" value="<?= $diagnostic['volt_15'] ?? '' ?>" readonly>
        <label>-15 Volt</label>
        <input type="text" class="form-control mb-2" value="<?= $diagnostic['volt_neg_15'] ?? '' ?>" readonly>
        <label>-5 Volt</label>
        <input type="text" class="form-control mb-2" value="<?= $diagnostic['volt_neg_5'] ?? '' ?>" readonly>
        <label>+3.3 Volt</label>
        <input type="text" class="form-control mb-2" value="<?= $diagnostic['volt_3_3'] ?? '' ?>" readonly>
        <label>Mains Frequency</label>
        <input type="text" class="form-control mb-2" value="<?= $diagnostic['mains_frequency'] ?? '' ?>" readonly>
      </div>
      <div class="col-md-4">
        <label>Lamp Voltage</label>
        <input type="text" class="form-control mb-2" value="<?= $diagnostic['lamp_voltage'] ?? '' ?>" readonly>
        <label>Lamp Current</label>
        <input type="text" class="form-control mb-2" value="<?= $diagnostic['lamp_current'] ?? '' ?>" readonly>
        <label>Lamp Status</label>
        <input type="text" class="form-control mb-2" value="<?= $diagnostic['lamp_status'] ?? '' ?>" readonly>
        <label>Gauge Temperature</label>
        <input type="text" class="form-control mb-2" value="<?= $diagnostic['gauge_temperature'] ?? '' ?>" readonly>
        <label>PWM Output</label>
        <input type="text" class="form-control mb-2" value="<?= $diagnostic['pwm_output'] ?? '' ?>" readonly>
        <label>Wheel Frequency</label>
        <input type="text" class="form-control mb-2" value="<?= $diagnostic['wheel_frequency'] ?? '' ?>" readonly>
      </div>
    </div>

    <!-- Add Reading Button -->
    <div class="text-center mt-3">
      <a href="diagnostics_form.html?device_id=<?= $device_id ?>" class="btn btn-light">Add Reading</a>
    </div>
  </div>

  <!-- Last Updated -->
  <p class="text-muted mt-3">Last Update: <?= $diagnostic['timestamp'] ?? 'No Data' ?> IST</p>

</div>
</body>
</html>




