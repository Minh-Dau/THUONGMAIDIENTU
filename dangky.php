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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['dangky']) && !isset($_POST['verify_code'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $username = mysqli_real_escape_string($conn, $_POST['username']);
        $password = mysqli_real_escape_string($conn, $_POST['password']);
        $resetpassword = mysqli_real_escape_string($conn, $_POST['resetpassword']);
        
        $default_phone = "";
        $default_role = "user";
        $default_address = "";
        $default_avatar = "";

        if (strlen($username) < 6) {
            $error = "Tên tài khoản phải có ít nhất 6 ký tự";
        } elseif (!preg_match('/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
            $error = "Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ in hoa, số và ký tự đặc biệt";
        } elseif ($password !== $resetpassword) {
            $error = "Mật khẩu nhập lại không khớp";
        } else {
            $check_query = "SELECT * FROM frm_dangky WHERE username='$username' OR email='$email'";
            $check_result = mysqli_query($conn, $check_query);

            if (mysqli_num_rows($check_result) > 0) {
                $error = "Tài khoản hoặc email đã tồn tại vui lòng nhập lại thông tin!";
            } else {
                $verification_code = rand(100000, 999999);
                $_SESSION['reg_verification_code'] = $verification_code; // Key riêng cho đăng ký
                $_SESSION['reg_email'] = $email; // Key riêng
                $_SESSION['reg_username'] = $username; // Key riêng
                $_SESSION['reg_password'] = password_hash($password, PASSWORD_DEFAULT); // Key riêng
                
                $mail = new PHPMailer(true);
                try {
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'khannhsiuvip@gmail.com';
                    $mail->Password = 'zlwjpcootaprksdp'; // App Password chính xác
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    $mail->setFrom('khannhsiuvip@gmail.com', 'Hustler Stonie');
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->Subject = 'Mã xác nhận đăng ký tài khoản';
                    $mail->Body = "Xin chào $username,<br>
                                    Cảm ơn bạn đã đăng ký tài khoản! Mã xác nhận của bạn là: <b>$verification_code</b><br>Vui lòng nhập mã này để hoàn tất đăng ký. <br>
                                    HUSTLER STONIE CHÚC QUÝ KHÁCH NHỮNG THỜI GIAN TRẢI NGHIỆM MUA HÀNG TỐT NHẤT!";
                    $mail->CharSet = 'UTF-8';

                    $mail->send();
                    $show_verification = true;
                    $error = "Đã gửi mã xác nhận đến email của bạn. Vui lòng kiểm tra!";
                } catch (Exception $e) {
                    $error = "Không thể gửi mã xác nhận. Lỗi: {$mail->ErrorInfo}";
                    unset($_SESSION['reg_verification_code']);
                    unset($_SESSION['reg_email']);
                    unset($_SESSION['reg_username']);
                    unset($_SESSION['reg_password']);
                }
            }
        }
    } elseif (isset($_POST['verify_code'])) {
        $input_code = $_POST['verification_code'];
        if ($input_code == $_SESSION['reg_verification_code']) {
            $email = $_SESSION['reg_email'];
            $username = $_SESSION['reg_username'];
            $hashed_password = $_SESSION['reg_password'];
            $default_phone = "";
            $default_role = "user";
            $default_address = "";
            $default_avatar = "";

            $insert_query = "INSERT INTO frm_dangky (email, username, password, sdt, phanquyen, diachi, anh) 
                            VALUES ('$email', '$username', '$hashed_password', '$default_phone', '$default_role', '$default_address', '$default_avatar')";
            
            if (mysqli_query($conn, $insert_query)) {
                unset($_SESSION['reg_verification_code']);
                unset($_SESSION['reg_email']);
                unset($_SESSION['reg_username']);
                unset($_SESSION['reg_password']);
                header('Location: dangnhap.php');
                exit();
            } else {
                $error = "Lỗi đăng ký: " . mysqli_error($conn);
                $show_verification = true;
            }
        } else {
            $error = "Mã xác nhận không đúng!";
            $show_verification = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký | Hustler Stonie</title>
    <link rel="stylesheet" href="css_dangky.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
<?php include 'header.php'; ?>

<form id="trangdk" action="dangky.php" method="POST">
    <div>
        <h1 class="h1_dk">ĐĂNG KÝ TÀI KHOẢN</h1>
        <?php if (!$show_verification): ?>
            <p class="p_tt">Bạn đã có tài khoản? <a href="dangnhap.php">Đăng nhập tại đây</a></p>
        <?php endif; ?>
        <br>
        <?php if (!$show_verification): // Chỉ hiển thị khi không ở bước nhập mã ?>
            <p class="p_tde">THÔNG TIN CÁ NHÂN</p>
        <?php endif; ?>
        <div class="cha_dk">
            <?php if (!$show_verification): ?>
                <h4 class="h4_dk">Email <span>*</span></h4>
                <input type="email" name="email" class="nhaptt" required placeholder="Nhập email">
                
                <h4 class="h4_dk">Tên tài khoản <span>*</span></h4>
                <input type="text" name="username" class="nhaptt" required placeholder="Nhập tên tài khoản" minlength="6">
                
                <h4 class="h4_dk">Mật khẩu <span>*</span></h4>
                <input type="password" name="password" class="nhaptt" required placeholder="Nhập mật khẩu">
                
                <h4 class="h4_dk">Nhập lại mật khẩu <span>*</span></h4>
                <input type="password" name="resetpassword" class="nhaptt" required placeholder="Nhập lại mật khẩu">
                
                <button class="nhandk" name="dangky">Đăng ký</button>
            <?php else: ?>
                <h4 class="h4_dk">Nhập mã xác nhận <span>*</span></h4>
                <input type="text" name="verification_code" class="nhaptt" required placeholder="Nhập mã xác nhận">
                <button class="nhandk" name="verify_code">Xác nhận</button>
            <?php endif; ?>
            
            <?php if (!empty($error)): ?>
                <span style="color: <?php echo strpos($error, 'Đã gửi') !== false ? 'green' : 'red'; ?>; display: block; margin-top: 10px;"><?php echo $error; ?></span>
            <?php endif; ?>
            <br>
        </div>
    </div>
</form>

<div class="support">
    <p><i class="bi bi-telephone-fill"></i> Hỗ trợ - Mua hàng: <span style="color: rgb(201, 0, 0);"><b>0122112211</b></span></p>
</div>

<footer class="footer">
    <div class="container">
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

<p style="font-size: 17px;" align="center" class="banquyen">Copyright © 2025 Hustler Stonie</p>

<script>
    document.getElementById("trangdk").onsubmit = function(event) {
        <?php if (!$show_verification): ?>
            const username = document.querySelector("[name='username']").value;
            const password = document.querySelector("[name='password']").value;
            const resetpassword = document.querySelector("[name='resetpassword']").value;
            
            if (username.length < 6) {
                alert("Tên tài khoản phải có ít nhất 6 ký tự.");
                event.preventDefault();
            } else if (!/^(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/.test(password)) {
                alert("Mật khẩu phải có ít nhất 8 ký tự, bao gồm chữ in hoa, số và ký tự đặc biệt.");
                event.preventDefault();
            } else if (password !== resetpassword) {
                alert("Mật khẩu nhập lại không khớp.");
                event.preventDefault();
            }
        <?php endif; ?>
    };
</script>
</body>
</html>