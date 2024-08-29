<?php
session_start();
include 'db_connect.php';

if (isset($_POST['action'], $_POST['review_id'])) {
    $review_id = intval($_POST['review_id']);
    $action = $_POST['action'];

    if ($action === 'like') {
        $sql = "UPDATE reviews SET likes = likes + 1 WHERE id = ?";
    } elseif ($action === 'dislike') {
        $sql = "UPDATE reviews SET dislikes = dislikes + 1 WHERE id = ?";
    } else {
        exit('Invalid action');
    }

    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $review_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }

    $stmt->close();
    $conn->close();
    exit();
}
?>
