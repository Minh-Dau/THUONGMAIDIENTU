<?php
include 'config.php'; // Kết nối database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST["id"]);
    $method_name = $_POST["method_name"];
    $cost = $_POST["cost"];
    $description = $_POST["description"];

    if ($id > 0) {
        $sql = "UPDATE shipping_method SET method_name = ?, cost = ?, description = ?, update_at = NOW() WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdsi", $method_name, $cost, $description, $id);

        if ($stmt->execute()) {
            echo "success";
        } else {
            echo "Lỗi SQL: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "ID không hợp lệ!";
    }
    $conn->close();
}
?>
