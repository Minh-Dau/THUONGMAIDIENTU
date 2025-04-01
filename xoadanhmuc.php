<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Xóa tất cả sản phẩm thuộc danh mục này
    $delete_products = "DELETE FROM sanpham WHERE danhmuc_id = ?";
    $stmt1 = $conn->prepare($delete_products);
    $stmt1->bind_param("i", $id);
    $stmt1->execute();
    $stmt1->close();

    // Xóa danh mục
    $delete_category = "DELETE FROM danhmuc WHERE id = ?";
    $stmt2 = $conn->prepare($delete_category);
    $stmt2->bind_param("i", $id);
    
    if ($stmt2->execute()) {
        echo "Danh mục và tất cả sản phẩm liên quan đã được xóa!";
    } else {
        echo "Lỗi khi xóa: " . $conn->error;
    }
    
    $stmt2->close();
    $conn->close();
}
?>