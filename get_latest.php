<?php
include 'db_connect.php';

$last_id = isset($_GET['last_id']) ? intval($_GET['last_id']) : 0;
$sql = "SELECT * FROM your_table_name WHERE id > $last_id ORDER BY timestamp ASC";

$result = $conn->query($sql);
$data = [];

while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
?>
