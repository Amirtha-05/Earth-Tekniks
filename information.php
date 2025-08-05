<!-- index.php -->
<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Earth Tekniks - Dashboard</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
  <style>
    .device-card {
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      border-radius: 10px;
      overflow: hidden;
      margin: 15px;
      text-align: center;
      background-color: #e8f7fa;
      transition: transform 0.2s;
      cursor: pointer;
    }
    .device-card:hover {
      transform: scale(1.03);
    }
    .device-img {
      width: 100%;
      height: 150px;
      object-fit: contain;
      padding: 10px;
    }
  </style>
</head>
<body>
  <div class="container mt-5">
    <h3 class="text-center mb-4 text-primary">Device Dashboard</h3>
    <div class="row justify-content-center">

      <?php
      $query = "SELECT * FROM devices";
      $result = $conn->query($query);
      while ($row = $result->fetch_assoc()) {
        $device_id = $row['id'];
        $name = $row['device_name'];
        $serial = $row['serial_number'];
        $image = $row['image_url'];
        ?>
        <div class="col-md-3">
          <div class="device-card" onclick="showDeviceInfo(<?= htmlspecialchars(json_encode($row), ENT_QUOTES, 'UTF-8') ?>)">
            <img src="<?= $image ?>" alt="Device Image" class="device-img">
            <div class="p-2">
              <strong>Device Id:</strong> <?= $device_id ?><br>
              <strong>Device Name:</strong> <?= $name ?><br>
              <strong>Serial No:</strong> <?= $serial ?>
            </div>
          </div>
        </div>
      <?php } ?>

    </div>

    <!-- Device Information Area -->
    <div id="deviceInfoContainer" class="mt-5"></div>
  </div>

  <script>
    function showDeviceInfo(device) {
      const container = document.getElementById('deviceInfoContainer');
      container.innerHTML = `
        <div class="card shadow">
          <div class="row g-0">
            <div class="col-md-4">
              <img src="${device.image_url}" alt="Device Image" class="img-fluid rounded-start p-3">
            </div>
            <div class="col-md-8">
              <div class="card-body">
                <h5 class="card-title">${device.device_name}</h5>
                <p class="card-text">Here are the device specifications:</p>
                <ul class="list-group list-group-flush">
                  <li class="list-group-item"><strong>Device ID:</strong> ${device.id}</li>
                  <li class="list-group-item"><strong>Serial Number:</strong> ${device.serial_number}</li>
                  <li class="list-group-item"><strong>Company:</strong> ETPL</li>
                  <li class="list-group-item"><strong>Location:</strong> Chennai</li>
                </ul>
              </div>
            </div>
          </div>
        </div>
      `;
      container.scrollIntoView({ behavior: 'smooth' });
    }
  </script>
</body>
</html>
