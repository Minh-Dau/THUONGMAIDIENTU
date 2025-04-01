<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $sql_img = "SELECT img FROM sanpham WHERE id = $id";
    $result_img = $conn->query($sql_img);
    if ($result_img->num_rows > 0) {
        $row = $result_img->fetch_assoc();
        $image_path = $row["img"];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }
    $sql = "DELETE FROM sanpham WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        echo "Xóa thành công!";
    } else {
        echo "Lỗi: " . $conn->error;
    }
    $conn->close();
}
?>
