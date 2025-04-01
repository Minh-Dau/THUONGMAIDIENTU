<?php
include 'config.php';
header('Content-Type: application/json');
error_log(print_r($_POST, true));
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM frm_dangky WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if(!$stmt) {
        echo json_encode(["status" => "error", "message" => "Lỗi prepare: " . $conn->error]);
        exit();
    }
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Người dùng đã được xóa!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Lỗi xóa: " . $stmt->error]);
    }
    $stmt->close();
    $conn->close();
    exit();
}
?>
