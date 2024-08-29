<?php
session_start();
include 'db_connect.php';

if (isset($_POST['review_id'])) {
    $review_id = intval($_POST['review_id']);
    $user_id = $_SESSION['user_id']; // Assuming you have user sessions

    // Insert report into the database
    $sql = "INSERT INTO reports (review_id, user_id, report_date) VALUES (?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $review_id, $user_id);
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
