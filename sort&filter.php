<?php
include 'db_connect.php';

$order = isset($_GET['order']) ? $_GET['order'] : 'created_at DESC';
$filter = isset($_GET['status']) ? $_GET['status'] : '';

$sql = "SELECT id, rating, comment, created_at FROM reviews WHERE 1=1";

if ($filter) {
    $sql .= " AND status = ?";
}

$sql .= " ORDER BY $order";

$stmt = $conn->prepare($sql);

if ($filter) {
    $stmt->bind_param('s', $filter);
}

$stmt->execute();
$result = $stmt->get_result();
?>