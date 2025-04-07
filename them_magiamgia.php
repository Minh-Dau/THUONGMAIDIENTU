<?php
include 'config.php'; // Kết nối database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $code = $_POST["code"];
    $discount_type = $_POST["discount_type"];
    $discount_value = $_POST["discount_value"];
    $min_order_value = $_POST["min_order_value"];
    $max_uses = $_POST["max_uses"];
    $expiry_date = $_POST["expiry_date"];
    $is_active = $_POST["is_active"];

    $sql = "INSERT INTO discount_codes (code, discount_type, discount_value, min_order_value, max_uses, expiry_date, is_active) 
            VALUES ('$code', '$discount_type', '$discount_value', '$min_order_value', '$max_uses', '$expiry_date', '$is_active')";

    if ($conn->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "error";
    }

    $conn->close();
}
?>
