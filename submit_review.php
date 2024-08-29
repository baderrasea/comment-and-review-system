<?php
$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "reviews_db"; 

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    if (empty($rating) || empty($comment)) {
        echo "Rating and comment are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO reviews (rating, comment) VALUES (?, ?)");
        $stmt->bind_param("is", $rating, $comment);

        // Execute and check for errors
        if ($stmt->execute()) {
            echo "Review submitted successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    }
}

$conn->close();
header("Location: index.php");

?>
