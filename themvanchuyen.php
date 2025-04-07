<?php
include 'config.php'; // Kết nối database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $method_name = trim($_POST["method_name"]);
    $cost = trim($_POST["cost"]);
    $description = trim($_POST["description"]);
    if (empty($method_name) || empty($cost)) {
        echo "Thiếu dữ liệu bắt buộc!";
        exit;
    }
    error_log("Dữ liệu nhận: $method_name, $cost, $description");

    $sql = "INSERT INTO shipping_method (method_name, cost, description, create_at, update_at) 
            VALUES (?, ?, ?, NOW(), NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sds", $method_name, $cost, $description); // `sds`: string, double, string

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "Lỗi SQL: " . $stmt->error;
    }
    $stmt->close();
    $conn->close();
}
?>
