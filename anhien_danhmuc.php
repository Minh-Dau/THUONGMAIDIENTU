<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['trangthai'])) {
    $id = $_POST['id'];
    $trangthai = $_POST['trangthai'];
    $sql = "UPDATE danhmuc SET trangthai = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $trangthai, $id);
    if ($stmt->execute()) {
        $sql_update_sanpham = "UPDATE sanpham SET trangthai = ? WHERE danhmuc_id = ?";
        $stmt_sanpham = $conn->prepare($sql_update_sanpham);
        $stmt_sanpham->bind_param("si", $trangthai, $id);
        $stmt_sanpham->execute();
        $stmt_sanpham->close();
    } else {
        echo "Lỗi cập nhật danh mục: " . $conn->error;
    }
    $stmt->close();
    $conn->close();
}
?>