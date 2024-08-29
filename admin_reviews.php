<?php
session_start();
include 'db_connect.php';

if ($_SESSION['user_role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$sql = "SELECT r.id, r.rating, r.comment, r.created_at, r.likes, r.dislikes, r.status, r.rejection_reason, 
               (SELECT COUNT(*) FROM reports WHERE review_id = r.id) AS report_count 
        FROM reviews r 
        ORDER BY created_at DESC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Reviews</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h2>Admin Review Moderation</h2>

        <h3>Reviews to Moderate</h3>
        <?php while ($row = $result->fetch_assoc()): ?>
        <div class="review">
            <p class="rating"><strong>Rating:</strong> <?php echo $row['rating']; ?> stars</p>
            <p class="comment"><strong>Comment:</strong> <?php echo htmlspecialchars($row['comment']); ?></p>
            <p><strong>Submitted on:</strong> <?php echo $row['created_at']; ?></p>
            <p><strong>Likes:</strong> <?php echo $row['likes']; ?>, <strong>Dislikes:</strong> <?php echo $row['dislikes']; ?></p>
            <p><strong>Reports:</strong> <?php echo $row['report_count']; ?></p>
            <?php if ($row['status'] === 'pending'): ?>
                <form method="POST" action="moderate_review.php">
                    <input type="hidden" name="review_id" value="<?php echo $row['id']; ?>">
                    <button type="submit" name="action" value="approve">Approve</button>
                    <button type="submit" name="action" value="reject">Reject</button>
                </form>
                <?php if ($row['status'] === 'rejected'): ?>
                    <p><strong>Rejection Reason:</strong> <?php echo htmlspecialchars($row['rejection_reason']); ?></p>
                <?php endif; ?>
            <?php else: ?>
                <p><strong>Status:</strong> <?php echo ucfirst($row['status']); ?></p>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    </div>
</body>
</html>
<?php $conn->close(); ?>
