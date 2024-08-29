<?php
session_start();
include 'db_connect.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    if (empty($rating) || empty($comment)) {
        $_SESSION['message'] = "Rating and comment are required.";
        $_SESSION['msg_type'] = "error";
    } else {
        $stmt = $conn->prepare("UPDATE reviews SET rating = ?, comment = ? WHERE id = ?");
        $stmt->bind_param("isi", $rating, $comment, $id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Review updated successfully!";
            $_SESSION['msg_type'] = "success";
        } else {
            $_SESSION['message'] = "Error: Could not update the review.";
            $_SESSION['msg_type'] = "error";
        }

        $stmt->close();
    }

    $conn->close();
    header("Location: index.php");
    exit();
}

$stmt = $conn->prepare("SELECT rating, comment FROM reviews WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$review = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Review</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container" style="      margin-top: 10px;
">
        <h2>Edit Review</h2>

        <?php
        if (isset($_SESSION['message'])):
        ?>
        <div class="<?php echo $_SESSION['msg_type']; ?>">
            <?php
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            unset($_SESSION['msg_type']);
            ?>
        </div>
        <?php endif; ?>

        <form action="edit_review.php?id=<?php echo $id; ?>" method="POST">
            <div class="rating">
                <label for="rating">Rating:</label>
                <div class="stars">
                    <?php for ($i = 5; $i >= 1; $i--): ?>
                    <input type="radio" id="star<?php echo $i; ?>" name="rating" value="<?php echo $i; ?>" <?php echo $i == $review['rating'] ? 'checked' : ''; ?>>
                    <label for="star<?php echo $i; ?>" title="<?php echo $i; ?> stars">&#9733;</label>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="comment-box">
                <label for="comment">Comment:</label>
                <textarea id="comment" name="comment" rows="5"><?php echo htmlspecialchars($review['comment']); ?></textarea>
            </div>

            <div class="submit-button">
                <button type="submit">Update Review</button>
            </div>
        </form>
    </div>
</body>
</html>
<?php $conn->close(); ?>
