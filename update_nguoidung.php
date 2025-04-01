<?php
include 'config.php';
session_start(); // Bắt đầu session để lấy thông tin quyền hạn của người đăng nhập

// Lấy quyền của người đang đăng nhập
$logged_in_user_role = $_SESSION['phanquyen'] ?? '';

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Sanitize and validate input
    $id         = isset($_POST['id']) ? (int)$_POST['id'] : null;
    $username   = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email      = isset($_POST['email']) ? trim($_POST['email']) : '';
    $sdt        = isset($_POST['sdt']) ? trim($_POST['sdt']) : null;
    $phanquyen  = isset($_POST['phanquyen']) ? trim($_POST['phanquyen']) : '';
    $trangthai  = isset($_POST['trangthai']) ? trim($_POST['trangthai']) : '';
    $password   = isset($_POST['password']) ? trim($_POST['password']) : '';
    $current_anh = isset($_POST['current_anh']) ? trim($_POST['current_anh']) : '';

    $specific_address = trim($_POST['specific_address']);
    $province_name = trim($_POST['province_name']);
    $district_name = trim($_POST['district_name']);
    $ward_name = trim($_POST['ward_name']);
    $address_components = array_filter([$specific_address, $ward_name, $district_name, $province_name], function($value) {
        return !empty($value);
    });
    $diachi = implode(', ', $address_components);

    if (empty($id)) {
        echo json_encode(["status" => "error", "message" => "ID không hợp lệ"]);
        exit;
    }
    if (empty($username)) {
        echo json_encode(["status" => "error", "message" => "Tên người dùng không được để trống"]);
        exit;
    }
    if (empty($email)) {
        echo json_encode(["status" => "error", "message" => "Email không được để trống"]);
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["status" => "error", "message" => "Email không hợp lệ"]);
        exit;
    }
    if (empty($phanquyen) || !in_array($phanquyen, ['admin', 'user', 'nhanvien'])) {
        echo json_encode(["status" => "error", "message" => "Quyền không hợp lệ"]);
        exit;
    }
    if (empty($trangthai) || !in_array($trangthai, ['hoạt động', 'đã khóa'])) {
        echo json_encode(["status" => "error", "message" => "Trạng thái không hợp lệ"]);
        exit;
    }
    if (empty($diachi)) {
        echo json_encode(["status" => "error", "message" => "Địa chỉ không được để trống"]);
        exit;
    }
    if (strlen($diachi) > 500) {
        echo json_encode(["status" => "error", "message" => "Địa chỉ không được dài quá 500 ký tự"]);
        exit;
    }

    // Kiểm tra quyền hạn: Nếu người dùng hiện tại không phải admin, không cho phép sửa quyền thành admin
    if ($logged_in_user_role !== 'admin' && $phanquyen === 'admin') {
        echo json_encode(["status" => "error", "message" => "Bạn không có quyền cấp quyền admin"]);
        exit;
    }

    // Xử lý upload ảnh
    $anh = $current_anh;
    if (isset($_FILES['anh']) && $_FILES['anh']['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        if (in_array($_FILES['anh']['type'], $allowedTypes) && $_FILES['anh']['size'] <= $maxSize) {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
            $targetFile = $targetDir . basename($_FILES['anh']['name']);
            if (move_uploaded_file($_FILES['anh']['tmp_name'], $targetFile)) {
                $anh = $targetFile;
            }
        } else {
            echo json_encode(["status" => "error", "message" => "File ảnh không hợp lệ (chỉ chấp nhận JPEG, PNG, GIF, tối đa 5MB)"]);
            exit;
        }
    }

    // Cập nhật dữ liệu
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "UPDATE frm_dangky
                SET username=?, email=?, sdt=?, diachi=?, phanquyen=?, trangthai=?, anh=?, password=?
                WHERE id=?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
            exit;
        }
        $stmt->bind_param("ssssssssi", $username, $email, $sdt, $diachi, $phanquyen, $trangthai, $anh, $hashedPassword, $id);
    } else {
        $sql = "UPDATE frm_dangky
                SET username=?, email=?, sdt=?, diachi=?, phanquyen=?, trangthai=?, anh=?
                WHERE id=?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
            exit;
        }
        $stmt->bind_param("sssssssi", $username, $email, $sdt, $diachi, $phanquyen, $trangthai, $anh, $id);
    }

    // Thực thi truy vấn
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Cập nhật thành công!"]);
    } else {
        if ($stmt->errno == 1062) {
            echo json_encode(["status" => "error", "message" => "Email đã tồn tại, vui lòng sử dụng email khác"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Lỗi cập nhật: " . $stmt->error]);
        }
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
