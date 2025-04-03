<?php
include 'config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['review_id'])) {
    $review_id = intval($_POST['review_id']);
    $sql = "DELETE FROM danhgia WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $review_id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Đánh giá đã được xóa."]);
    } else {
        echo json_encode(["status" => "error", "message" => "Không thể xóa đánh giá."]);
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Yêu cầu không hợp lệ."]);
}
?>
