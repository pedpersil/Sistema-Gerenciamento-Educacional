<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../classes/Database.php';

$db = new Database();
$conn = $db->connect();

$sql = "SELECT * FROM events";
$result = $conn->query($sql);
$events = [];

while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
    $events[] = [
        'id' => $row['id'],
        'title' => $row['title'],
        'start' => $row['start'],
        'end' => $row['end']
    ];
}

echo json_encode($events);
?>
