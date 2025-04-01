<?php
include 'config.php';
session_start();

// Kiểm tra quyền của người dùng đăng nhập
$logged_in_user_role = $_SESSION['phanquyen'] ?? ''; // Lấy quyền từ session

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username   = isset($_POST['username']) ? trim($_POST['username']) : '';
    $email      = isset($_POST['email']) ? trim($_POST['email']) : '';
    $password   = isset($_POST['password']) ? trim($_POST['password']) : '';
    $phanquyen  = isset($_POST['phanquyen']) ? trim($_POST['phanquyen']) : '';
    $sdt        = isset($_POST['sdt']) ? trim($_POST['sdt']) : null;
    $trangthai  = isset($_POST['trangthai']) ? trim($_POST['trangthai']) : 'hoạt động';

    $specific_address = trim($_POST['specific_address']);
    $province_name = trim($_POST['province_name']);
    $district_name = trim($_POST['district_name']);
    $ward_name = trim($_POST['ward_name']);

    $address_components = array_filter([$specific_address, $ward_name, $district_name, $province_name], function($value) {
        return !empty($value);
    });
    $diachi = implode(', ', $address_components);

    // Nếu nhân viên đang đăng nhập, không cho phép cấp quyền admin
    if ($logged_in_user_role === 'nhanvien' && $phanquyen === 'admin') {
        echo json_encode(["status" => "error", "message" => "Bạn không có quyền cấp tài khoản admin"]);
        exit;
    }

    // Kiểm tra dữ liệu nhập vào
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
    if (empty($password)) {
        echo json_encode(["status" => "error", "message" => "Mật khẩu không được để trống"]);
        exit;
    }
    if (empty($phanquyen) || !in_array($phanquyen, ['admin', 'nhanvien', 'user'])) {
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

    // Xử lý tải ảnh
    $anh = null;
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

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO frm_dangky (username, email, password, phanquyen, sdt, diachi, anh, trangthai)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
        exit;
    }
    $stmt->bind_param("ssssssss", $username, $email, $hashedPassword, $phanquyen, $sdt, $diachi, $anh, $trangthai);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Thêm người dùng thành công!"]);
    } else {
        if ($stmt->errno == 1062) {
            echo json_encode(["status" => "error", "message" => "Email đã tồn tại, vui lòng sử dụng email khác"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Lỗi thêm người dùng: " . $stmt->error]);
        }
    }

    $stmt->close();
    $conn->close();
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
}
?>
