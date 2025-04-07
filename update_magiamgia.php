<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST["id"];
    $code = $_POST["code"];
    $discount_type = $_POST["discount_type"];
    $discount_value = $_POST["discount_value"];
    $min_order_value = $_POST["min_order_value"];
    $max_uses = $_POST["max_uses"];

    $sql = "UPDATE discount_codes SET code='$code', discount_type='$discount_type', discount_value='$discount_value', min_order_value='$min_order_value', max_uses='$max_uses' WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "success";
    } else {
        echo "error";
    }

    $conn->close();
}
?>
