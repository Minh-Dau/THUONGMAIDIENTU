<?php
include 'config.php';

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
$permissions = [
    'product_add' => false,
    'product_edit' => false,
    'product_delete' => false,
    'category_add' => false,
    'category_edit' => false,
    'category_delete' => false,
    'category_hide' => false,
    'user_add' => false,
    'user_edit' => false,
    'user_delete' => false,
    'order_manage' => false
];

if ($user_id > 0) {
    $sql = "SELECT permission_name, enabled FROM phanquyennhanvien WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $permissions[$row['permission_name']] = (bool)$row['enabled'];
    }
    $stmt->close();
}
$conn->close();
header('Content-Type: application/json');
echo json_encode($permissions);
?>