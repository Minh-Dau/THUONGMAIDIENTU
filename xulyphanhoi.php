<?php
include("config.php");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Kiểm tra quyền admin/nhân viên
$user_id = $_SESSION['user_id'] ?? 0;
if ($user_id <= 0) {
    die("Bạn cần đăng nhập để thực hiện hành động này.");
}

$sql_role = "SELECT phanquyen FROM frm_dangky WHERE id = ?";
$stmt_role = $conn->prepare($sql_role);
$stmt_role->bind_param("i", $user_id);
$stmt_role->execute();
$result_role = $stmt_role->get_result();
$user_role = $result_role->fetch_assoc()['phanquyen'] ?? '';
$stmt_role->close();
if ($user_role !== 'admin' && $user_role !== 'nhanvien') {
    die("Bạn không có quyền thực hiện hành động này.");
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Kiểm tra dữ liệu gửi lên
    if (empty($_POST['review_id']) || empty($_POST['admin_reply']) || empty($_POST['sanpham_id'])) {
        die("Thiếu thông tin phản hồi.");
    }
    $review_id = intval($_POST['review_id']);
    $admin_reply = trim($_POST['admin_reply']);
    $sanpham_id = intval($_POST['sanpham_id']);
    $sql_update = "UPDATE danhgia SET admin_reply = ?, admin_id = ? WHERE id = ?";
    if ($stmt_update = $conn->prepare($sql_update)) {
        $stmt_update->bind_param("sii", $admin_reply, $user_id, $review_id);
        if ($stmt_update->execute()) {
            $stmt_update->close();
            $conn->close();
            header("Location: chitietsanpham.php?id=" . $sanpham_id);
            exit();
        } else {
            die("Lỗi khi lưu phản hồi: " . htmlspecialchars($stmt_update->error));
        }
    } else {
        die("Lỗi truy vấn: " . htmlspecialchars($conn->error));
    }
}

$conn->close();
?>