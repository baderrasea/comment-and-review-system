<?php
session_start();
include 'db_connect.php';

if ($_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

if (isset($_POST['review_id'], $_POST['action'])) {
    $review_id = intval($_POST['review_id']);
    $action = $_POST['action'];

    if ($action === 'approve') {
        $sql = "UPDATE reviews SET status = 'approved', rejection_reason = NULL WHERE id = ?";
    } elseif ($action === 'reject') {
        $reason = "Inappropriate content"; // You may add a reason input
        $sql = "UPDATE reviews SET status = 'rejected', rejection_reason = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('si', $reason, $review_id);
    } else {
        exit('Invalid action');
    }

    if ($action !== 'reject') {
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $review_id);
    }

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: admin_reviews.php");
        exit();
    } else {
        echo 'An error occurred';
    }

    $stmt->close();
    $conn->close();
}
?>
