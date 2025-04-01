<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $tendanhmuc = $_POST['tendanhmuc'];

    $sql = "UPDATE danhmuc SET tendanhmuc = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $tendanhmuc, $id);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Danh mục đã được cập nhật thành công!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Lỗi khi cập nhật danh mục: " . $conn->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
