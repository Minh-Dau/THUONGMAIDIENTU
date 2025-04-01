<?php
session_start();
include("config.php");

// Nhận dữ liệu từ form
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL; // Set to NULL if not logged in
$product_id = $_POST['product_id'];
$comment = trim($_POST['comment']);
$rating = $_POST['rating'];

// Kiểm tra dữ liệu
if (empty($comment) || $rating < 1 || $rating > 5) {
    die("Dữ liệu không hợp lệ!");
}

// Lưu vào database
$sql = "INSERT INTO binhluan (product_id, user_id, comment, rating) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iisi", $product_id, $user_id, $comment, $rating);

if ($stmt->execute()) {
    header("Location: chitietsanpham.php?id=" . $product_id);
} else {
    echo "Lỗi khi lưu bình luận: " . $conn->error;
}

$stmt->close();
$conn->close();
exit();
?>