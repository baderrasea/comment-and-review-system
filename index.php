<?php
session_start();
include 'db_connect.php';

$order = isset($_GET['order']) ? $_GET['order'] : 'created_at DESC';
$filter = isset($_GET['status']) ? $_GET['status'] : '';

$sql = "SELECT id, rating, comment, created_at, likes, dislikes FROM reviews";


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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review System</title>
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.like-btn, .dislike-btn').on('click', function () {
                var reviewId = $(this).data('id');
                var action = $(this).data('action');

                $.ajax({
                    type: 'POST',
                    url: 'like_dislike.php',
                    data: { action: action, review_id: reviewId },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status === 'success') {
                            location.reload();
                        } else {
                            alert('An error occurred');
                        }
                    }
                });
            });
        });
    </script>
</head>

<body>
    <div class="container">
        <h2>Leave a Review</h2>
        <form id="reviewForm" action="submit_review.php" method="POST">
            <div class="rating">
                <label for="rating">Rating:</label>
                <div class="stars">
                    <input type="radio" id="star5" name="rating" value="5">
                    <label for="star5" title="5 stars">&#9733;</label>
                    <input type="radio" id="star4" name="rating" value="4">
                    <label for="star4" title="4 stars">&#9733;</label>
                    <input type="radio" id="star3" name="rating" value="3">
                    <label for="star3" title="3 stars">&#9733;</label>
                    <input type="radio" id="star2" name="rating" value="2">
                    <label for="star2" title="2 stars">&#9733;</label>
                    <input type="radio" id="star1" name="rating" value="1">
                    <label for="star1" title="1 star">&#9733;</label>
                </div>
            </div>

            <div class="comment-box">
                <label for="comment">Comment:</label>
                <textarea id="comment" name="comment" rows="5" placeholder="Write your comment here..."></textarea>
            </div>
            <div class="g-recaptcha" data-sitekey="6LcWfS8qAAAAAEr7KoxNJCNQsLhynW-tjknFbPaA"></div>


            <div class="submit-button">
                <button type="submit">Submit Review</button>
            </div>
        </form>

        <div class="filters">
            <form method="GET" action="">
                <!-- <div class="filter-group">
                    <label for="status">Filter by Status:</label>
                    <select id="status" name="status">
                        <option value="">All</option>
                        <option value="approved" <?php echo $filter == 'approved' ? 'selected' : ''; ?>>Approved</option>
                        <option value="pending" <?php echo $filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
                    </select>
                </div> -->
                <div class="filter-group">
                    <label for="order">Sort by:</label>
                    <select id="order" name="order">
                        <option value="created_at DESC" <?php echo $order == 'created_at DESC' ? 'selected' : ''; ?>>
                            Newest</option>
                        <option value="created_at ASC" <?php echo $order == 'created_at ASC' ? 'selected' : ''; ?>>Oldest
                        </option>
                        <option value="rating DESC" <?php echo $order == 'rating DESC' ? 'selected' : ''; ?>>Highest
                            Rating</option>
                        <option value="rating ASC" <?php echo $order == 'rating ASC' ? 'selected' : ''; ?>>Lowest Rating
                        </option>
                    </select>
                </div>
                <button type="submit">Apply</button>
            </form>
        </div>

        <h3>Existing Reviews</h3>
        <?php while ($row = $result->fetch_assoc()): ?>
          
<div class="review">
    <p class="rating"><strong>Rating:</strong> <?php echo $row['rating']; ?> stars</p>
    <p class="comment"><strong>Comment:</strong> <?php echo htmlspecialchars($row['comment']); ?></p>
    <p><strong>Submitted on:</strong> <?php echo $row['created_at']; ?></p>
    <div class="review-stats">
        <button class="like-btn" data-id="<?php echo $row['id']; ?>" data-action="like">Like (<?php echo $row['likes']; ?>)</button>
        <button class="dislike-btn" data-id="<?php echo $row['id']; ?>" data-action="dislike">Dislike (<?php echo $row['dislikes']; ?>)</button>
        <button class="report-btn" data-id="<?php echo $row['id']; ?>">Report</button>
    </div>
    <div class="edit-delete">
        <a href="edit_review.php?id=<?php echo $row['id']; ?>">Edit</a>
        <a href="delete_review.php?id=<?php echo $row['id']; ?>" class="delete" onclick="return confirm('Are you sure you want to delete this review?');">Delete</a>
    </div>
</div>

        <?php endwhile; ?>
       

         </div>

    </div>
</body>

</html>
<?php $conn->close(); ?>
<script>
$(document).ready(function() {
    $('.like-btn, .dislike-btn').on('click', function() {
        var reviewId = $(this).data('id');
        var action = $(this).data('action');

        $.ajax({
            type: 'POST',
            url: 'like_dislike.php',
            data: { action: action, review_id: reviewId },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    location.reload();
                } else {
                    alert('An error occurred');
                }
            }
        });
    });

    $('.report-btn').on('click', function() {
        var reviewId = $(this).data('id');

        $.ajax({
            type: 'POST',
            url: 'report_review.php',
            data: { review_id: reviewId },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    alert('Thank you for reporting. We will review this content.');
                } else {
                    alert('An error occurred');
                }
            }
        });
    });
});
</script>
