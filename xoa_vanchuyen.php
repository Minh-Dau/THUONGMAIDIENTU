<?php
include 'config.php'; // Kết nối database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST["id"]);

    if ($id > 0) {
        $sql = "DELETE FROM shipping_method WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);

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
