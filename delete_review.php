<?php
session_start();
include 'db_connect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Review deleted successfully!";
        $_SESSION['msg_type'] = "success";
    } else {
        $_SESSION['message'] = "Error: Could not delete the review.";
        $_SESSION['msg_type'] = "error";
    }

    $stmt->close();
}

$conn->close();
header("Location: index.php");
exit();
?>
