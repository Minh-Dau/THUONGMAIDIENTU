<?php
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
if (isset($data['id']) && isset($data['invoice_status'])) {
    $conn = new mysqli("localhost", "root", "", "webbanhang");

    if ($conn->connect_error) {
        echo json_encode(["status" => "error", "message" => "Lỗi kết nối database"]);
        exit();
    }

    $orderId = (int)$data['id'];
    $sql = "UPDATE oder SET invoice_status = 'Đã in' WHERE id = $orderId";

    if ($conn->query($sql) === TRUE) {
        echo json_encode(["status" => "success"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Cập nhật thất bại"]);
    }

    $conn->close();
}
?>
