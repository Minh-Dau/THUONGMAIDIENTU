<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $tensanpham = $_POST["tensanpham"];
    $gia = (int)$_POST["gia"];
    $gia_nhap = (int)$_POST["gia_nhap"];
    $soluong = (int)$_POST["soluong"];
    $noidungsanpham = $_POST["noidungsanpham"];
    $danhmuc_id = (int)$_POST["danhmuc_id"];
    $trangthai = $_POST["trangthai"];

    $conn->set_charset("utf8mb4");

    $stmt = $conn->prepare("SELECT trangthai FROM danhmuc WHERE id = ?");
    $stmt->bind_param("i", $danhmuc_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $category_status = $result->fetch_assoc()["trangthai"];
    $stmt->close();
    if ($category_status == "Ẩn") {
        $trangthai = "Ẩn"; 
    } else {
        $trangthai = $_POST["trangthai"];
    }
    
    $sql = "UPDATE sanpham 
            SET tensanpham=?, gia=?, gia_nhap=?, soluong=?, noidungsanpham=?, trangthai=?, danhmuc_id=?
            WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("siiissii", $tensanpham, $gia, $gia_nhap, $soluong, $noidungsanpham, $trangthai, $danhmuc_id, $id);

    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật thành công!'); window.location.href='quanlysanpham.php';</script>";
    } else {
        echo "Lỗi: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
