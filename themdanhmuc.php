<?php
header('Content-Type: application/json');
include 'config.php';
$tendanhmuc = isset($_POST['tendanhmuc']) ? trim($_POST['tendanhmuc']) : '';

$response = array();
if (empty($tendanhmuc)) {
    $response['status'] = 'error';
    $response['message'] = 'Tên danh mục không được để trống!';
    echo json_encode($response);
    exit();
}
$sql = "INSERT INTO danhmuc (tendanhmuc) VALUES (?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $tendanhmuc);
if ($stmt->execute()) {
    $response['status'] = 'success';
    $response['message'] = 'Thêm danh mục thành công!';
} else {
    $response['status'] = 'error';
    $response['message'] = 'Không thể thêm danh mục. Vui lòng thử lại!';
}
$stmt->close();
$conn->close();
echo json_encode($response);
?>