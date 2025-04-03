<?php
ob_start();
session_start();
date_default_timezone_set('Asia/Ho_Chi_Minh'); // Đảm bảo múi giờ được đặt đúng
include("config.php");

// Validate and fetch product data
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID sản phẩm không hợp lệ!");
}

// Sử dụng prepared statement để lấy thông tin sản phẩm
$stmt = $conn->prepare("SELECT * FROM sanpham WHERE id = ?");
$stmt->bind_param("i", $_GET['id']);
$stmt->execute();
$result = $stmt->get_result();

if (!$result || $result->num_rows == 0) {
    die("Sản phẩm không tồn tại!");
}
$product = $result->fetch_assoc();
$stmt->close();

// Xử lý "Mua ngay"
if (isset($_SESSION['username']) && isset($_POST['buy_now'])) {
    $username = $_SESSION['username'];
    $product_id = $_POST['id'];
    $tensanpham = trim($_POST['tensanpham']);
    $soluong = isset($_POST['soluong']) ? intval($_POST['soluong']) : 1;

    // Lấy user_id từ username
    $stmt = $conn->prepare("SELECT id FROM frm_dangky WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Không tìm thấy người dùng!',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ff4d4d'
            });
        </script>";
        $stmt->close();
        exit();
    }
    $user = $result->fetch_assoc();
    $user_id = $user['id'];
    $stmt->close();

    // Kiểm tra số lượng tồn kho
    $stmt = $conn->prepare("SELECT soluong FROM sanpham WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $soluong_tonkho = $row['soluong'];
    $stmt->close();

    if ($soluong > $soluong_tonkho) {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Sản phẩm $tensanpham chỉ còn $soluong_tonkho sản phẩm trong kho!',
                confirmButtonText: 'OK',
                confirmButtonColor: '#ff4d4d'
            });
        </script>";
        exit();
    }

    // Kiểm tra xem sản phẩm đã có trong giỏ hàng chưa
    $stmt = $conn->prepare("SELECT soluong FROM cart_item WHERE user_id = ? AND sanpham_id = ?");
    $stmt->bind_param("ii", $user_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Sản phẩm đã có trong giỏ hàng, cập nhật số lượng
        $row = $result->fetch_assoc();
        $new_soluong = $row['soluong'] + $soluong;
        $stmt_update = $conn->prepare("UPDATE cart_item SET soluong = ? WHERE user_id = ? AND sanpham_id = ?");
        $stmt_update->bind_param("iii", $new_soluong, $user_id, $product_id);
        $stmt_update->execute();
        $stmt_update->close();
    } else {
        // Sản phẩm chưa có trong giỏ hàng, thêm mới
        $stmt_insert = $conn->prepare("INSERT INTO cart_item (user_id, sanpham_id, soluong) VALUES (?, ?, ?)");
        $stmt_insert->bind_param("iii", $user_id, $product_id, $soluong);
        $stmt_insert->execute();
        $stmt_insert->close();
    }
    $stmt->close();

    // Chuyển hướng đến trang giỏ hàng
    header("Location: giohang.php?buy_now=" . $product_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DUMEMAY|VIETNAM</title>
    <link rel="stylesheet" href="css_chitiet.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Include jQuery for AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function validateQuantity() {
            var inputElement = document.getElementById('so');
            if (!inputElement) {
                console.error('Input #so không tồn tại!');
                return;
            }
            var inputValue = inputElement.value.trim();
            var numericRegex = /^[0-9]+$/;
            if (!numericRegex.test(inputValue) || inputValue <= 0) {
                inputElement.value = 1;
            }
        }

        function giamSo() {
            var soInput = document.getElementById('so');
            var currentQuantity = parseInt(soInput.value);
            if (currentQuantity > 1) {
                soInput.value = currentQuantity - 1;
            }
        }

        function tangSo() {
            var soInput = document.getElementById('so');
            var currentQuantity = parseInt(soInput.value);
            soInput.value = currentQuantity + 1;
        }

        function validateForm() {
            validateQuantity();
            return true;
        }

        // AJAX function for "Add to Cart"
        function addToCart() {
            validateQuantity();
            var id = $('input[name="id"]').val();
            var tensanpham = $('input[name="tensanpham"]').val();
            var soluong = $('#so').val();

            // Debug: Kiểm tra các input có tồn tại không
            console.log('Input ID exists:', $('input[name="id"]').length);
            console.log('Input tensanpham exists:', $('input[name="tensanpham"]').length);
            console.log('Input so exists:', $('#so').length);

            // Debug: Kiểm tra giá trị
            console.log('ID:', id);
            console.log('Số lượng:', soluong);
            console.log('Tên sản phẩm:', tensanpham);

            if (!id || !soluong) {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: 'Dữ liệu không hợp lệ! Vui lòng kiểm tra lại.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#ff4d4d'
                });
                console.log('ID:', id, 'Số lượng:', soluong, 'Tên sản phẩm:', tensanpham);
                return;
            }

            console.log('Sending data:', { id: id, tensanpham: tensanpham, soluong: soluong });

            $.ajax({
                url: 'ajax_cart.php',
                type: 'POST',
                data: {
                    id: id,
                    tensanpham: tensanpham,
                    soluong: soluong,
                    add_to_cart: true
                },
                success: function(response) {
                    console.log('Raw Response:', response);
                    // No need for JSON.parse since jQuery already parsed the response
                    var result = response;
                    console.log('Parsed Result:', result);
                    if (result.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Thành công',
                            text: result.message || 'Đã thêm vào giỏ hàng thành công!',
                            showCancelButton: true,
                            confirmButtonText: 'Đi đến giỏ hàng',
                            cancelButtonText: 'Tiếp tục mua sắm',
                            confirmButtonColor: '#ff4d4d',
                            cancelButtonColor: '#707070'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = 'giohang.php';
                            }
                        });
                        $('#cart-count').text(result.total_items || 0);
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Có lỗi xảy ra khi thêm vào giỏ hàng: ' + (result.message || 'Không có thông tin lỗi'),
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#ff4d4d'
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', status, error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Lỗi',
                        text: 'Không thể kết nối đến server!',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#ff4d4d'
                    });
                }
            });
        }
    </script>
</head>
<style>
    .btn_giohang {
        font-size: 20px;
        padding: 10px 20px;
        cursor: pointer;
        background-color: #707070;
        color: white;
        border: none;
        border-radius: 5px;
        margin-bottom: 10px;
        box-shadow: rgba(6, 42, 105, 0.25) 0px 4px 8px -2px, rgba(9, 30, 66, 0.08) 0px 0px 0px 1px;
    }
    .btn_giohang:hover {
        background-color: #000000;
        box-shadow: rgba(17, 17, 26, 0.1) 0px 4px 16px, rgba(17, 17, 26, 0.05) 0px 8px 32px;
    }
    .btn_muahang {
        font-size: 20px;
        padding: 10px 20px;
        cursor: pointer;
        background-color: #ff4d4d;
        color: white;
        border: none;
        border-radius: 5px;
        margin-bottom: 10px;
        box-shadow: rgba(6, 42, 105, 0.25) 0px 4px 8px -2px, rgba(9, 30, 66, 0.08) 0px 0px 0px 1px;
    }
    .btn_muahang:hover {
        background-color: #e60000;
        box-shadow: rgba(17, 17, 26, 0.1) 0px 4px 16px, rgba(17, 17, 26, 0.05) 0px 8px 32px;
    }
    .mota {
        font-size: 20px;
        font-weight: bold;
        margin: 20px 0;
        text-align: center;
    }
    .motasanpham {
        margin: 20px 0;
        padding: 0 20px;
        font-size: 16px;
        line-height: 1.6;
    }
   /* Hiển thị số lượng sao trung bình của các đánh giá */
.rv-rating-container {
    display: flex;
    align-items: center;
    font-size: 15px;
    gap: 5px;
}

.rv-rating-number {
    font-weight: bold;
    color: #333;
}

.rv-stars {
    color: #FFA500; /* Màu vàng cam */
}

.rv-review-section {
    margin: 30px 0;
    padding: 20px;
    background-color: #f9f9f9;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

/* Tiêu đề "ĐÁNH GIÁ SẢN PHẨM" */
.rv-review-section h3 {
    font-size: 24px;
    font-weight: bold;
    color: #333;
    margin-bottom: 20px;
    border-bottom: 2px solid #007bff;
    padding-bottom: 10px;
}

/* Điểm trung bình và ngôi sao */
.rv-rating-container {
    display: flex;
    align-items: center;
    margin-bottom: 20px;
}

.rv-rating-number {
    font-size: 28px;
    font-weight: bold;
    color: #ff9800;
    margin-right: 10px;
}

.rv-stars {
    font-size: 20px;
    color: #ff9800;
}

.rv-stars i {
    margin-right: 2px;
}

.rv-review-form {
    margin-bottom: 20px;
    padding: 15px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
}

.rv-review-form label {
    font-weight: bold;
    color: #555;
    margin-right: 10px;
}

.rv-review-form select, .rv-review-form input[type="text"] {
    padding: 8px;
    margin: 5px 0;
    border: 1px solid #ccc;
    border-radius: 5px;
    font-size: 14px;
    width: 200px;
}

.rv-review-form input[type="text"] {
    width: 300px;
}

.rv-review-form button {
    padding: 10px 20px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
}

.rv-review-form button:hover {
    background-color: #0056b3;
}

/* Tiêu đề "ĐÁNH GIÁ TỪ NGUỒI MUA" */
.rv-review-list h3 {
    font-size: 20px;
    font-weight: bold;
    color: #333;
    margin-top: 30px;
    margin-bottom: 15px;
}

/* Mỗi đánh giá từ người mua */
.rv-review-item {
    padding: 15px;
    margin-bottom: 15px;
    background-color: #fff;
    border: 1px solid #eee;
    border-radius: 8px;
    transition: box-shadow 0.3s;
}

.rv-review-item:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.rv-review-item p {
    margin: 5px 0;
}

.rv-review-item strong {
    color: #007bff;
    font-weight: bold;
}

.rv-review-item .rv-rating {
    color: #ff9800;
    font-size: 16px;
}

.rv-review-item hr {
    border: none;
    border-top: 1px solid #eee;
    margin: 10px 0;
}

/* Thông báo khi chưa có đánh giá */
.rv-no-reviews {
    color: #777;
    font-style: italic;
}

/* Phần xem thêm của đánh giá */
.rv-hidden-review {
    display: none;
}

#rv-show-more-btn {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px 15px;
    margin-top: 10px;
    cursor: pointer;
    border-radius: 5px;
}

#rv-show-more-btn:hover {
    background-color: #0056b3;
}

/* Phản hồi từ admin */
.rv-admin-reply {
    margin-top: 10px;
    padding: 10px;
    background-color: #e7f3ff;
    border-left: 4px solid #007bff;
    border-radius: 5px;
    color: #333;
}

.rv-admin-reply strong {
    color: #007bff;
}

/* Form trả lời */
.rv-admin-reply-form {
    margin-top: 15px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.rv-admin-reply-form textarea {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    resize: vertical;
    min-height: 80px;
    font-size: 14px;
}

.rv-admin-reply-form button {
    padding: 8px 15px;
    background-color: #28a745;
    color: #fff;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s;
    align-self: flex-start;
}

.rv-admin-reply-form button:hover {
    background-color: #218838;
}
    </style>
<body>
    <?php include 'header.php'; ?>
    <table class="sanpham">
        <tr>
            <td><img src="<?= htmlspecialchars($product['img']) ?>" alt="" class="anh" onclick="zoom(this)"></td>
            <td>
                <div class="thongtin">
                    <div class="titel_chitiet"><?= htmlspecialchars($product['tensanpham']) ?></div>
                    
                    <p id="gia">Giá: <?= number_format($product['gia'], 0, ',', '.') ?> VNĐ</p>
                    <br>
                    <!-- Form for "Buy Now" -->
                    <form action="" method="POST" onsubmit="return validateForm()" id="buyNowForm">
                        <input type="hidden" name="id" value="<?= $product['id'] ?>">
                        <input type="hidden" name="tensanpham" value="<?= htmlspecialchars($product['tensanpham']) ?>">
                        <p id="mausac"><b style="font-size: 20px">Số lượng:</b></p>
                        <div id="soluong">
                            <input type="button" value="-" id="giam" onclick="giamSo()">
                            <input type="text" id="so" name="soluong" value="1" oninput="validateQuantity()">
                            <input type="button" value="+" id="tang" onclick="tangSo()">
                        </div>
                        <button type="button" class="btn_giohang" onclick="addToCart()">Thêm vào giỏ hàng</button>
                        <input type="submit" name="buy_now" value="Mua hàng" class="btn_muahang">
                    </form>
                    <div class="sdt">
                        <span>Gọi đặt mua 0122112211 8:00-22:00</span>
                    </div>
                    <hr width="460px">
                </div>       
            </td>
        </tr>
    </table>
    <!-- Mô tả sản phẩm -->
    <div class="mota">
        <p>Mô tả sản phẩm</p>
    </div>
    <hr>
    <div class="motasanpham">
        <p><?= htmlspecialchars($product['noidungsanpham']) ?></p>
    </div>
    <hr>
<!-- Đánh giá sản phẩm -->
<div class="rv-review-section" id="review-section">
    <h3>Đánh giá sản phẩm</h3>
    <?php
    include("config.php");
    $user_id = $_SESSION['user_id'] ?? 0;
    $product_id = $_GET['id'] ?? 0;
    $review_id = $_GET['review_id'] ?? 0; 
    $da_mua = false;
    $orders = [];
    $is_admin_or_staff = false;

    // Update the is_seen status for a specific review if review_id is provided
    if ($review_id > 0) {
        $sql_update_seen = "UPDATE danhgia SET is_seen = 1 WHERE id = ?";
        $stmt_update_seen = $conn->prepare($sql_update_seen);
        $stmt_update_seen->bind_param("i", $review_id);
        $stmt_update_seen->execute();
        $stmt_update_seen->close();
    }

    // Check if the user is an admin or staff
    if ($user_id > 0) {
        $sql_role = "SELECT phanquyen FROM frm_dangky WHERE id = ?";
        $stmt_role = $conn->prepare($sql_role);
        $stmt_role->bind_param("i", $user_id);
        $stmt_role->execute();
        $result_role = $stmt_role->get_result();
        $user_role = $result_role->fetch_assoc()['phanquyen'] ?? '';
        $stmt_role->close();

        if ($user_role === 'admin' || $user_role === 'nhanvien') {
            $is_admin_or_staff = true;
        }
    }

    // Check if the user has purchased the product
    if ($user_id > 0 && $product_id > 0) {
        $sql_check = "SELECT oder.id FROM oder_detail 
                      INNER JOIN oder ON oder_detail.oder_id = oder.id
                      WHERE oder.user_id = ? AND oder_detail.sanpham_id = ?";
        $stmt = $conn->prepare($sql_check);
        $stmt->bind_param("ii", $user_id, $product_id);
        $stmt->execute();
        $result_check = $stmt->get_result();

        while ($row = $result_check->fetch_assoc()) {
            $orders[] = $row['id'];
            $da_mua = true;
        }
        $stmt->close();
    }

    // Display the review form or status for users who have purchased the product
    if ($user_id > 0 && $da_mua): ?>
        <?php foreach ($orders as $order_id): ?>
            <?php
            // Check if a review exists for this user, product, and order
            $sql_check_review = "SELECT * FROM danhgia WHERE user_id = ? AND sanpham_id = ? AND oder_id = ?";
            $stmt_check = $conn->prepare($sql_check_review);
            $stmt_check->bind_param("iii", $user_id, $product_id, $order_id);
            $stmt_check->execute();
            $result_check_review = $stmt_check->get_result();
            $review = $result_check_review->fetch_assoc();
            $stmt_check->close();
            ?>

            <?php if ($review): ?>
                <?php if ($review['trangthaiduyet'] == 0): ?>
                    <!-- Review exists but is not yet approved -->
                    <p class="rv-waiting-approval">Bạn đã gửi đánh giá, vui lòng chờ admin duyệt đánh giá.</p>
                <?php elseif ($review['trangthaiduyet'] == 1 && !$review['is_edited']): ?>
                    <!-- Review is approved and can still be edited -->
                    <form action="xulydanhgia.php" method="POST" class="rv-review-form">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                        <input type="hidden" name="sanpham_id" value="<?php echo $product_id; ?>">
                        <input type="hidden" name="oder_id" value="<?php echo $order_id; ?>">
                        
                        <label>Chọn đánh giá:</label>
                        <select name="rating">
                            <option value="5" <?php echo ($review['rating'] == 5) ? "selected" : ""; ?>>★★★★★</option>
                            <option value="4" <?php echo ($review['rating'] == 4) ? "selected" : ""; ?>>★★★★</option>
                            <option value="3" <?php echo ($review['rating'] == 3) ? "selected" : ""; ?>>★★★</option>
                            <option value="2" <?php echo ($review['rating'] == 2) ? "selected" : ""; ?>>★★</option>
                            <option value="1" <?php echo ($review['rating'] == 1) ? "selected" : ""; ?>>★</option>
                        </select>
                        
                        <input type="text" name="comment" value="<?php echo htmlspecialchars($review['comment']); ?>">
                        
                        <button type="submit" name="update_review">Cập nhật đánh giá</button>
                    </form>
                <?php else: ?>
                    <!-- Review is approved and has been edited, no further edits allowed -->
                    <p>Bạn đã chỉnh sửa đánh giá cho đơn hàng này và không thể sửa lại.</p>
                <?php endif; ?>
            <?php else: ?>
                <!-- No review exists, show the form to submit a new review -->
                <form action="xulydanhgia.php" method="POST" class="rv-review-form">
                    <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                    <input type="hidden" name="sanpham_id" value="<?php echo $product_id; ?>">
                    <input type="hidden" name="oder_id" value="<?php echo $order_id; ?>">
                    
                    <label>Chọn đánh giá:</label>
                    <select name="rating">
                        <option value="5">★★★★★</option>
                        <option value="4">★★★★</option>
                        <option value="3">★★★</option>
                        <option value="2">★★</option>
                        <option value="1">★</option>
                    </select>
                    
                    <input type="text" name="comment" placeholder="Nhập đánh giá của bạn">
                    
                    <button type="submit">Gửi đánh giá</button>
                </form>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Bạn cần mua sản phẩm này để có thể đánh giá.</p>
    <?php endif; ?>

    <?php
    // Calculate the average rating, considering only approved reviews
    $sql_avg_rating = "SELECT AVG(rating) as avg_rating FROM danhgia WHERE sanpham_id = ? AND trangthaiduyet = 1";
    $stmt_avg = $conn->prepare($sql_avg_rating);
    $stmt_avg->bind_param("i", $product_id);
    $stmt_avg->execute();
    $result_avg = $stmt_avg->get_result();
    $row_avg = $result_avg->fetch_assoc();
    $avg_rating = round($row_avg['avg_rating'] ?? 0, 1); // Default to 0 if no approved reviews
    $stmt_avg->close();

    function renderStars($rating) {
        $fullStars = floor($rating);
        $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
        $emptyStars = 5 - ($fullStars + $halfStar);

        $starsHTML = "";
        for ($i = 0; $i < $fullStars; $i++) {
            $starsHTML .= '<i class="fas fa-star"></i>';
        }
        if ($halfStar) {
            $starsHTML .= '<i class="fas fa-star-half-alt"></i>';
        }
        for ($i = 0; $i < $emptyStars; $i++) {
            $starsHTML .= '<i class="far fa-star"></i>';
        }
        return $starsHTML;
    }
    ?>
    <div class="rv-rating-container">
        <span class="rv-rating-number"><?php echo $avg_rating; ?></span>
        <span class="rv-stars"><?php echo renderStars($avg_rating); ?></span>
    </div>

    <div class="rv-review-list">
        <h3>Đánh giá từ người mua</h3>
        <?php
        // Fetch only approved reviews for display
        $sql_reviews = "SELECT danhgia.*, frm_dangky.username, frm_dangky.hoten AS user_name, admin_user.hoten AS admin_name 
                        FROM danhgia 
                        JOIN frm_dangky ON danhgia.user_id = frm_dangky.id
                        LEFT JOIN frm_dangky AS admin_user ON danhgia.admin_id = admin_user.id
                        WHERE danhgia.sanpham_id = ? AND danhgia.trangthaiduyet = 1
                        ORDER BY danhgia.created_at DESC";
        $stmt_reviews = $conn->prepare($sql_reviews);
        $stmt_reviews->bind_param("i", $product_id);
        $stmt_reviews->execute();
        $result_reviews = $stmt_reviews->get_result();

        if ($result_reviews->num_rows > 0) {
            while ($review = $result_reviews->fetch_assoc()) {
                echo '<div class="rv-review-item">';
                echo '<p><strong>' . htmlspecialchars($review['user_name']) . '</strong> - <span class="rv-rating">' . $review['rating'] . '★</span></p>';
                echo '<p>' . htmlspecialchars($review['comment']) . '</p>';

                // Hiển thị phản hồi từ admin/nhân viên nếu có
                if (!empty($review['admin_reply'])) {
                    $admin_name = $review['admin_name'] ? htmlspecialchars($review['admin_name']) : 'Cửa hàng';
                    echo '<p class="rv-admin-reply"><strong>Phản hồi từ ' . $admin_name . ':</strong> ' . htmlspecialchars($review['admin_reply']) . '</p>';
                }
                // Hiển thị form trả lời nếu người dùng là admin/nhân viên và chưa có phản hồi
                if ($is_admin_or_staff && empty($review['admin_reply'])) {
                    echo '<form action="xulyphanhoi.php" method="POST" class="rv-admin-reply-form">';
                    echo '<input type="hidden" name="review_id" value="' . $review['id'] . '">';
                    echo '<input type="hidden" name="sanpham_id" value="' . $product_id . '">';
                    echo '<textarea name="admin_reply" placeholder="Nhập phản hồi của bạn" required></textarea>';
                    echo '<button type="submit">Gửi phản hồi</button>';
                    echo '</form>';
                }
                echo '<hr>';
                echo '</div>';
            }
        } else {
            echo '<p class="rv-no-reviews">Chưa có đánh giá nào.</p>';
        }

        $stmt_reviews->close();
        $conn->close();
        ?>
    </div>
</div>
    <!-- Sản phẩm cùng danh mục -->
    <div class="wrapper">
        <div class="product" id="related-products">
            <?php
                include("config.php");
                $product_id = isset($_GET['id']) ? $_GET['id'] : 0;
                $sql = "SELECT danhmuc_id FROM sanpham WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $product_id);
                $stmt->execute();
                $result = $stmt->get_result();
                $product_data = $result->fetch_assoc();
                $danhmuc_id = $product_data['danhmuc_id'];

                // Lấy danh sách sản phẩm cùng danh mục nhưng không bao gồm sản phẩm hiện tại
                $sql_related = "SELECT * FROM sanpham WHERE danhmuc_id = ? AND id != ? AND trangthai = 'Hiển Thị'";
                $stmt_related = $conn->prepare($sql_related);
                $stmt_related->bind_param("ii", $danhmuc_id, $product_id);
                $stmt_related->execute();
                $result_related = $stmt_related->get_result();

                if ($result_related->num_rows > 0) {
                    while ($row = $result_related->fetch_assoc()) {
            ?>
                        <div class="product_item">
                            <div class="product_top">
                                <a href="chitietsanpham.php?id=<?= $row['id'] ?>" class="product_thumb">
                                    <img src="<?= htmlspecialchars($row['img']) ?>" alt="" width="250" height="250">
                                </a>
                                <a href="chitietsanpham.php?id=<?= $row['id'] ?>" class="buy_now">Mua ngay</a>
                            </div>
                            <div class="product_info">
                                <a href="chitietsanpham.php?id=<?= $row['id'] ?>" class="product_cat"><?= htmlspecialchars($row['tensanpham']) ?></a>
                                <div class="product_price"><?= number_format($row['gia'], 0, ',', '.') ?> VNĐ</div>
                            </div>
                        </div>
            <?php
                    }
                } else {
                    echo '<p style="text-align: center; color: red;">Không có sản phẩm liên quan.</p>';
                }

                $stmt->close();
                $stmt_related->close();
                $conn->close();
            ?>
        </div>
    </div>
    
    <!-- Footer -->
    <?php include 'footer.php'; ?>
    <div>
        <hr>
        <p style="font-size: 17px;" align="center" class="banquyen">Copyright © 2025 Hustler Stonie</p>
    </div>
</body>
</html>
<script>
    document.addEventListener("DOMContentLoaded", function () {
    const showMoreBtn = document.getElementById("show-more-btn");
    if (showMoreBtn) {
        showMoreBtn.addEventListener("click", function () {
            document.querySelectorAll(".hidden-review").forEach(item => {
                item.style.display = "block"; // Hiện tất cả đánh giá bị ẩn
            });
            showMoreBtn.style.display = "none"; // Ẩn nút sau khi bấm
        });
    }
});
</script>