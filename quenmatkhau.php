<?php
session_start();
include("config.php");
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$error = "";
$show_verification = false;
$show_new_password = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Bước 1: Nhập email và username
    if (isset($_POST['submit_email']) && !isset($_POST['verify_code']) && !isset($_POST['reset_password'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);

        $query = "SELECT * FROM frm_dangky WHERE email='$email' AND username='$username'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $verification_code = rand(100000, 999999);
            $_SESSION['reset_verification_code'] = $verification_code; // Dùng key riêng để tránh xung đột
            $_SESSION['reset_email'] = $email; // Dùng key riêng
            $_SESSION['reset_username'] = $username; // Dùng key riêng

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'khannhsiuvip@gmail.com';
                $mail->Password = 'zlwjpcootaprksdp';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('khannhsiuvip@gmail.com', 'Hustler Stonie');
                $mail->addAddress($email);

                $mail->isHTML(true);
                $mail->Subject = 'Mã Xác Nhận Quên Mật Khẩu';
                $mail->Body = "Xin chào $username,<br>
                                Mã xác nhận để đặt lại mật khẩu của bạn là: <b>$verification_code</b><br>
                                Vui lòng nhập mã này để tiếp tục. <br>
                                HUSTLER STONIE CHÚC QUÝ KHÁCH NHỮNG THỜI GIAN TRẢI NGHIỆM TỐT NHẤT!";
                $mail->CharSet = 'UTF-8';

                $mail->send();
                $show_verification = true;
                $error = "Đã gửi mã xác nhận đến email của bạn. Vui lòng kiểm tra!";
            } catch (Exception $e) {
                $error = "Không thể gửi mã xác nhận. Lỗi: {$mail->ErrorInfo}";
                unset($_SESSION['reset_verification_code']);
                unset($_SESSION['reset_email']);
                unset($_SESSION['reset_username']);
            }
        } else {
            $error = "Email hoặc tài khoản không tồn tại!";
        }
    } 
    // Bước 2: Xác nhận mã
    elseif (isset($_POST['verify_code'])) {
        $input_code = $_POST['verification_code'];
        if ($input_code == $_SESSION['reset_verification_code']) {
            $show_new_password = true;
            $error = "Vui lòng nhập mật khẩu mới.";
        } else {
            $error = "Mã xác nhận không đúng!";
            $show_verification = true;
        }
    } 
    // Bước 3: Đặt lại mật khẩu
    elseif (isset($_POST['reset_password'])) {
        $new_password = mysqli_real_escape_string($conn, $_POST['new_password']);
        $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm_password']);

        if (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $new_password)) {
            $error = "Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ in hoa, số và ký tự đặc biệt";
            $show_new_password = true;
        } elseif ($new_password !== $confirm_password) {
            $error = "Mật khẩu xác nhận không khớp!";
            $show_new_password = true;
        } else {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $username = $_SESSION['reset_username'];
            $update_query = "UPDATE frm_dangky SET password='$hashed_password' WHERE username='$username'";
            
            if (mysqli_query($conn, $update_query)) {
                unset($_SESSION['reset_verification_code']);
                unset($_SESSION['reset_email']);
                unset($_SESSION['reset_username']);
                header('Location: dangnhap.php');
                exit();
            } else {
                $error = "Lỗi khi cập nhật mật khẩu: " . mysqli_error($conn);
                $show_new_password = true;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên Mật Khẩu | Hustler Stonie</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css_quenmk.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
<?php include 'header.php'; ?>
    <div class="welcome">
        <?php
        // Chỉ hiển thị khi đã đăng nhập thực sự (có biến $_SESSION['logged_in'])
        if (isset($_SESSION['username']) && isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
            echo '<p>Xin chào, <span class="welcome_user">' . $_SESSION['username'] . '</span>!  <i class="bi bi-box-arrow-right"></i><a href="logout.php">Logout</a></p>';
        }
        ?>
    </div>
    <div class="quenmatkhau_form">
        <h1 class="tieude_quenmatkhau">QUÊN MẬT KHẨU</h1>
        <a href="dangnhap.php" class="quayve_login"><i class="bi bi-arrow-return-right"></i> Quay về Trang Đăng Nhập</a>
        <form action="quenmatkhau.php" method="POST" style="margin-top: 20px;">
            <?php if (!$show_verification && !$show_new_password): ?>
                <div class="cha_quen">
                    <input class="nhap_quen" type="email" name="email" required placeholder="Nhập email" style="width: 300px; padding: 10px; margin-bottom: 10px;">
                </div>
                <div class="cha_quen">
                    <input class="nhap_quen" type="text" name="username" required placeholder="Nhập tên tài khoản" style="width: 300px; padding: 10px; margin-bottom: 10px;">
                </div>
                <button type="submit" name="submit_email" style="padding: 10px 20px; background-color: #3498db; color: white; border: none; cursor: pointer;">Gửi mã xác nhận</button>
            <?php elseif ($show_verification && !$show_new_password): ?>
                <div class="cha_quen">
                    <input class="nhap_quen" type="text" name="verification_code" required placeholder="Nhập mã xác nhận" style="width: 300px; padding: 10px; margin-bottom: 10px;">
                </div>
                <button type="submit" name="verify_code" style="padding: 10px 20px; background-color: #3498db; color: white; border: none; cursor: pointer;">Xác nhận</button>
            <?php elseif ($show_new_password): ?>
                <div class="cha_quen">
                    <input class="nhap_quen" type="password" name="new_password" required placeholder="Nhập mật khẩu mới" style="width: 300px; padding: 10px; margin-bottom: 10px;">
                </div>
                <div class="cha_quen">
                    <input class="nhap_quen" type="password" name="confirm_password" required placeholder="Xác nhận mật khẩu" style="width: 300px; padding: 10px; margin-bottom: 10px;">
                </div>
                <button type="submit" name="reset_password" style="padding: 10px 20px; background-color: #3498db; color: white; border: none; cursor: pointer;">Đặt lại mật khẩu</button>
            <?php endif; ?>
        </form>
        <?php if (!empty($error)): ?>
            <p style="color: <?php echo strpos($error, 'Đã gửi') !== false ? 'green' : 'red'; ?>; margin-top: 10px;"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
    <div class="support">
        <p><i class="bi bi-telephone-fill"></i> Hỗ trợ - Mua hàng: <span style="color: rgb(201, 0, 0);"><b>0122112211</b></span></p>
    </div>
    <footer class="footer">
        <div class="container" style="font-size: 20px;">           
            <div class="row">
                <div class="footer-col">
                    <h4>HỆ THỐNG CỬA HÀNG</h4>
                    <p><i class="bi bi-geo-alt"></i> Chi Nhánh Trường Đại Học Sư Phạm Kỹ Thuật Vĩnh Long</p>
                    <p><i class="bi bi-geo-alt"></i> Đường Nguyễn Huệ, Phường 2, Thành Phố Vĩnh Long</p>
                    <p><i class="bi bi-telephone-fill"></i> Hotline: 0122 112 211</p>
                    <p><i class="bi bi-envelope-fill"></i> hustlerstonie@gmail.com</p>
                </div>
                <div class="footer-col">
                    <h4>Chính sách</h4>
                    <ul>
                        <li><i class="bi bi-dot"></i> Chính sách bảo mật</li>
                        <li><i class="bi bi-dot"></i> FAQ</li>
                        <li><i class="bi bi-dot"></i> Chính sách Thẻ Thành Viên</li>
                        <li><i class="bi bi-dot"></i> Chính sách Bảo hành & Đổi trả</li>
                        <li><i class="bi bi-dot"></i> Chính sách giao hàng hỏa tốc</li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Mạng Xã Hội</h4>
                    <p><i class="bi bi-facebook"></i> Hustler Stonie</p>
                    <p><i class="bi bi-instagram"></i> Hustler Stonie</p>
                </div>
            </div>
        </div>
    </footer>
    <div>
        <hr>
        <p style="font-size: 17px;" align="center" class="banquyen">Copyright © 2025 Hustler Stonie</p>
    </div>
</body>
</html>