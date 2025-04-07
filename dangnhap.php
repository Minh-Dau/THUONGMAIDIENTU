<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
    <link rel="stylesheet" href="css_dangnhap.css">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
<?php
session_start();
include("config.php");

$error_message1 = "";
$error_message2 = "";
$error_message3 = "";

// Nếu đã đăng nhập, chuyển hướng về trang chính
if (isset($_SESSION['username']) && isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: trangchinh.php');
    exit();
}

// Trong login.php, sau khi đăng nhập thành công

// Kiểm tra khi nhấn nút đăng nhập
if (isset($_POST['dangnhap']) && !empty($_POST['username']) && !empty($_POST['password'])) {
    $taikhoan = $_POST['username'];
    $password = $_POST['password'];

    // Truy vấn lấy thông tin tài khoản từ CSDL
    $sql = "SELECT * FROM frm_dangky WHERE username=? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $taikhoan);
    $stmt->execute();
    $result = $stmt->get_result();

    // Kiểm tra tài khoản có tồn tại không
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($user['trangthai'] == "đã khóa") { 
            echo "<script>alert('Tài khoản của bạn đã bị khóa! Vui lòng liên hệ quản trị viên hoặc mở khóa tại trang hỗ trợ.'); window.location.href='unclock.php';</script>";
            exit(); 
        } elseif (password_verify($password, $user['password'])) {
            $_SESSION["username"] = $user["username"];
            $_SESSION["phanquyen"] = $user["phanquyen"];
            $_SESSION['user_id'] = $user['id']; // Lấy id từ database
            if ($user["phanquyen"] == "admin") {
                header("Location: quanlysanpham.php");
            } elseif ($user["phanquyen"] == "nhanvien") {
                header("Location: quanlydonhang.php"); // Ví dụ: Nhân viên sẽ vào trang quản lý đơn hàng
            } else {
                header("Location: trangchinh.php");
            }            
            exit();
        } else {
            $error_message1 = "Sai mật khẩu!";
        }
    } else {
        $error_message2 = "Tài khoản không tồn tại!";
    }
    $stmt->close();
}
$conn->close();
?>
    <?php include 'header.php'; ?>
    <form id="trangdangnhap" action="dangnhap.php" method = "POST">
        <div class="taikhoan">
            <h2>ĐĂNG NHẬP TÀI KHOẢN TẠI ĐÂY</h2>
            <p class="thep">Bạn chưa có tài khoản?<a href="dangky.php"> Đăng ký tại đây</a><p>
            <div class="cha">
                <input type="text" name="username" class="input-group_gmail" required id="email" >
                <label for="email" class="input-group_label_gmail"> Tài Khoản  <span>*</span> </label>
            </div>
          <div class="cha">
              <input type="password" name="password" class="input-group_matkhau" required id="pass">
                <label for="matkhau" class="input-group_label_matkhau">Mật khẩu <span>*</span></label>
                <br>
                <span id="thongbao" style="color: red;"><?php echo $error_message1; ?></span>
                <span id="thongbao" style="color: red;"><?php echo $error_message2; ?></span>
                <span id="thongbao" style="color: red;"><?php echo $error_message3; ?></span> <!-- Thêm dòng này -->
            <table class="hienthimatkhau">
                <tr>
                    <td>
                        <input type="checkbox" id="check"> 
                    </td>
                    <td><p>Hiển thị mật khẩu</p></td>
                </tr>   
            </table>
            <p>Quên mật khẩu?<a href="quenmatkhau.php" class="a_quenmk"> Nhấn vào đây</a></p>
           <div class="cha">
            <button class="click" name="dangnhap">Đăng Nhập</button>
           </div>
        </div>
        </div>
    </form>

    <br>
    <div>
        <div class="support">
            <p><i class="bi bi-telephone-fill"></i> Hỗ trợ - Mua hàng: <span style="color: rgb(201, 0, 0);"><b>0122112211</b></span></p>
        </div>
        <footer class="footer">
        <div class="container" style="font-size: 20px;">           
            <div class="row">
            <div class="footer-col">
                    <h4>HỆ THỐNG CỬA HÀNG</h4>
                    <p><i class="bi bi-geo-alt"></i> &nbsp;Chi Nhánh Trường Đại Học Sư Phạm Kỹ Thuật Vĩnh Long</p>
                    <p><i class="bi bi-geo-alt"></i> &nbsp;Đường Nguyễn Huệ, Phường 2, Thành Phố Vĩnh Long</p>
                    <p><i class="bi bi-telephone-fill"></i> &nbsp;Hotline: 0122 112 211</p>
                    <p><i class="bi bi-envelope-fill"></i> &nbsp;hustlerstonie@gmail.com</p>
                </div>
                <div class="footer-col">
                    <h4>Chính sách</h4>
                        <ul>
                            <li><i class="bi bi-dot"></i>&nbsp;Chính sách bảo mật</li>
                            <li><i class="bi bi-dot"></i>&nbsp;FAQ</li>
                            <li><i class="bi bi-dot"></i>&nbsp;Chính sách Thẻ Thành Viên</li>
                            <li><i class="bi bi-dot"></i>&nbsp;Chính sách Bảo hành & Đổi trả</li>
                            <li><i class="bi bi-dot"></i>&nbsp;Chính sách giao hàng hỏa tốc</li>
                        </ul>
                </div>
                <div class="footer-col">
                    <h4>Mạng Xã Hội</h4>
                    <p><i class="bi bi-facebook"></i>&nbsp; Hustler Stonie</p>
                    <P><i class="bi bi-instagram"></i>&nbsp; Hustler Stonie</P>
                </div>
                <div class="footer-col">
                    <h4>Fanpage</h4>
                </div>
            </div>
        </div>
    </footer>
    <div>
        <hr>
        <p style="font-size: 17px;" align="center" class="banquyen">Copyright © 2025 Hustler Stonie</p>
    </div>
</body>
<script>
    var pass=document.getElementById("pass");
    var check=document.getElementById("check");
    check.onchange=function(e){
        pass.type=check.checked ? "text" : "password"
    };
</script>
</html>
<?php if (isset($error_message3)) echo "<p style='color: red;'>$error_message3</p>"; ?>
<?php if (isset($error_message)) echo "<p style='color: red;'>$error_message</p>"; ?>

