<?php
include 'config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tensanpham = $_POST["tensanpham"];
    $gia_nhap = (int)$_POST["gia_nhap"];
    $gia = (int)$_POST["gia"];
    $soluong = (int)$_POST["soluong"];
    $noidungsanpham = $_POST["noidungsanpham"];
    $trangthai = $_POST["trangthai"];
    $danhmuc_id = (int)$_POST["danhmuc_id"]; 
    $target_dir = "uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0755, true);
    }
    $target_file = $target_dir . basename($_FILES["img"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["img"]["tmp_name"]);
    if ($check === false) {
        echo json_encode(["status" => "error", "message" => "Tệp không phải là hình ảnh."]);
        exit();
    }
    if ($_FILES["img"]["size"] > 5000000) {
        echo json_encode(["status" => "error", "message" => "Tệp quá lớn."]);
        exit();
    }
    if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
        echo json_encode(["status" => "error", "message" => "Chỉ chấp nhận file JPG, JPEG, PNG, GIF."]);
        exit();
    }
    if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
        $stmt = $conn->prepare("INSERT INTO sanpham (tensanpham, img, gia_nhap, gia, soluong, noidungsanpham, trangthai, danhmuc_id) 
                                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssiiissi", $tensanpham, $target_file, $gia_nhap, $gia, $soluong, $noidungsanpham, $trangthai, $danhmuc_id);

        if ($stmt->execute()) {
            echo json_encode(["status" => "success", "message" => "Thêm sản phẩm thành công!"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Lỗi: " . $stmt->error]);
        }

        $stmt->close();
    } else {
        echo json_encode(["status" => "error", "message" => "Lỗi khi tải lên hình ảnh."]);
    }

    $conn->close();
}
?>