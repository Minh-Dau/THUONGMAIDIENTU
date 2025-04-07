<!-- Phần thông báo admin-->
<?php
include 'config.php';

// Notification logic
$new_review_count = 0;
$notifications = [];
$sql = "SELECT id, user_id, sanpham_id, created_at FROM danhgia WHERE is_seen = 0 ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result === false) {
    // Kiểm tra lỗi truy vấn SQL
    echo "Lỗi SQL: " . $conn->error;
    exit;
}
if ($result->num_rows > 0) {
    $new_review_count = $result->num_rows;
    while ($row = $result->fetch_assoc()) {
        $notifications[] = $row;
    }
}
// Approval logic
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['review_id'])) {
    $review_id = $_POST['review_id'];

    // Update the review to set trangthaiduyet to 1
    $sql = "UPDATE danhgia SET trangthaiduyet = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $review_id);

    if ($stmt->execute()) {
        // Redirect back to the same page with a success message
        header("Location: " . $_SERVER['PHP_SELF'] . "?message=Review approved successfully");
        exit();
    } else {
        // Redirect back with an error message
        header("Location: " . $_SERVER['PHP_SELF'] . "?error=Failed to approve review");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>

<!-- Phần thông báo admin -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let newReviewCount = <?php echo $new_review_count; ?>;
        let notificationList = document.getElementById("notificationList");
        let notificationCount = document.getElementById("notificationCount");

        if (newReviewCount > 0) {
            notificationCount.textContent = newReviewCount;
            notificationCount.style.display = "inline"; // Hiển thị số lượng
            notificationList.innerHTML = ""; // Xóa nội dung mặc định

            let notifications = <?php echo json_encode($notifications); ?>;

            notifications.forEach(function(notif) {
                let item = document.createElement("li");
                let link = document.createElement("a");
                link.className = "dropdown-item";
                link.href = "chitietsanpham.php?id=" + notif.sanpham_id + "&review_id=" + notif.id;
                link.textContent = "Đánh giá mới từ User #" + notif.user_id + " về sản phẩm #" + notif.sanpham_id;
                item.appendChild(link);
                notificationList.appendChild(item);
            });
        } else {
            notificationCount.style.display = "none"; // Ẩn số lượng khi không có thông báo
            notificationList.innerHTML = '<li><a class="dropdown-item" href="#">Không có thông báo mới</a></li>';
        }
    });
</script>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Tables - SB Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
</head>
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.html">Start Bootstrap</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <!-- Chuông thông báo -->
            <li class="nav-item dropdown">
                <a class="nav-link position-relative" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-bell fa-fw"></i>
                    <span id="notificationCount" class="badge bg-danger position-absolute top-0 start-100 translate-middle">0</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" id="notificationList">
                    <li><a class="dropdown-item" href="#">Không có thông báo mới</a></li>
                </ul>
            </li>

            <!-- Icon User -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="trangchinh.php">Trang Chính</a></li>
                    <li><hr class="dropdown-divider" /></li>
                    <li><a class="dropdown-item" href="logout.php">Đăng xuất</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            QUẢN LÝ
                        </a>
                        <div class="sb-sidenav-menu-heading">Interface</div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Layouts
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <a class="nav-link" href="layout-static.html">Static Navigation</a>
                                <a class="nav-link" href="layout-sidenav-light.html">Light Sidenav</a>
                            </nav>
                        </div>
                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                            <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                            Pages
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                                    Authentication
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="login.html">Login</a>
                                        <a class="nav-link" href="register.html">Register</a>
                                        <a class="nav-link" href="password.html">Forgot Password</a>
                                    </nav>
                                </div>
                                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                                    Error
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                                    <nav class="sb-sidenav-menu-nested nav">
                                        <a class="nav-link" href="401.html">401 Page</a>
                                        <a class="nav-link" href="404.html">404 Page</a>
                                        <a class="nav-link" href="500.html">500 Page</a>
                                    </nav>
                                </div>
                            </nav>
                        </div>
                        <div class="sb-sidenav-menu-heading">Addons</div>
                        <a class="nav-link" href="charts.html">
                            <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                            Charts
                        </a>
                        <a class="nav-link" href="quanlysanpham.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                            QUẢN LÝ SẢN PHẨM
                        </a>
                        <a class="nav-link" href="quanlynguoidung.php">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            QUẢN LÝ NGƯỜI DÙNG
                        </a>
                        <a class="nav-link" href="quanlydonhang.php">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-box"></i> 
                            </div>
                            QUẢN LÝ ĐƠN HÀNG
                        </a>
                        <a class="nav-link" href="quanlydanhgia.php">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-star"></i> 
                            </div>
                            QUẢN LÝ ĐÁNH GIÁ
                        </a>
                        <a class="nav-link" href="quanly_vanchuyen.php">
                            <div class="sb-nav-link-icon">
                                <i class="fas fa-truck"></i>  
                            </div>
                            QUẢN LÝ VẬN CHUYỂN 
                        </a>
                        <a class="nav-link" href="quanly_khuyenmai.php">
                                <div class="sb-nav-link-icon">
                                    <i class="fas fa-tags"></i>  
                                </div>
                                QUẢN LÝ KHUYẾN MÃI 
                        </a>
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">QUẢN LÝ MÃ GIẢM GIÁ</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.html">QUẢN LÝ</a></li>
                        <li class="breadcrumb-item active">QUẢN LÝ MÃ GIẢM GIÁ</li>
                    </ol>
                    <div class="card mb-4">
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            DANH SÁCH MÃ GIẢM GIÁ
                        </div>
                        <div class="card-body">
                            <!-- Nút bấm mở Modal -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDiscountCodeModal">
                                Thêm mã giảm giá mới
                            </button>
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Mã giảm giá</th>
                                        <th>Loại giảm giá</th>
                                        <th>Giá trị giảm</th>
                                        <th>Đơn hàng tối thiểu</th>
                                        <th>Số lần sử dụng tối đa</th>
                                        <th>Đã sử dụng</th>
                                        <th>Ngày hết hạn</th>
                                        <th>Trạng thái</th>
                                        <th>Hoạt động</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Mã giảm giá</th>
                                        <th>Loại giảm giá</th>
                                        <th>Giá trị giảm</th>
                                        <th>Đơn hàng tối thiểu</th>
                                        <th>Số lần sử dụng tối đa</th>
                                        <th>Đã sử dụng</th>
                                        <th>Ngày hết hạn</th>
                                        <th>Trạng thái</th>
                                        <th>Hoạt động</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    include 'config.php';
                                    $sql = "SELECT * FROM discount_codes";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>"; 
                                            echo "<td>" . $row["id"] . "</td>";
                                            echo "<td>" . htmlspecialchars($row["code"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["discount_type"]) . "</td>";
                                            echo "<td>" . number_format($row["discount_value"], 2) . "</td>";
                                            echo "<td>" . number_format($row["min_order_value"], 2) . "</td>";
                                            echo "<td>" . $row["max_uses"] . "</td>";
                                            echo "<td>" . $row["used_count"] . "</td>";
                                            echo "<td>" . $row["expiry_date"] . "</td>";
                                            echo "<td>" . ($row["is_active"] ? "Kích hoạt" : "Không kích hoạt") . "</td>";
                                            echo "<td>
                                                <button class='btn btn-warning btn-sm edit-btn'
                                                    data-id='" . $row["id"] . "'
                                                    data-code='" . htmlspecialchars($row["code"]) . "'
                                                    data-discount_type='" . htmlspecialchars($row["discount_type"]) . "'
                                                    data-discount_value='" . $row["discount_value"] . "'
                                                    data-min_order_value='" . $row["min_order_value"] . "'
                                                    data-max_uses='" . $row["max_uses"] . "'
                                                    data-expiry_date='" . $row["expiry_date"] . "'
                                                    data-is_active='" . $row["is_active"] . "'
                                                    data-bs-toggle='modal'
                                                    data-bs-target='#editDiscountCodeModal'>
                                                    Sửa
                                                </button>
                                                 <button class='btn btn-danger btn-sm delete-btn' data-id='" . $row["id"] . "'>
                                                    Xóa
                                                </button>
                                            </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='10'>Không có mã giảm giá nào</td></tr>";
                                    }
                                    $conn->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
<!-- From thêm mã giảm giá-->
<div class="modal fade" id="addDiscountCodeModal" tabindex="-1" aria-labelledby="addDiscountCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addDiscountCodeModalLabel">Thêm Mã Giảm Giá</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addDiscountCodeForm">
                    <div class="mb-3">
                        <label for="code" class="form-label">Mã giảm giá</label>
                        <input type="text" class="form-control" id="code" name="code" required>
                    </div>
                    <div class="mb-3">
                        <label for="discount_type" class="form-label">Loại giảm giá</label>
                        <select class="form-control" id="discount_type" name="discount_type">
                            <option value="percent">Phần trăm</option>
                            <option value="fixed">Giá trị cố định</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="discount_value" class="form-label">Giá trị giảm</label>
                        <input type="number" class="form-control" id="discount_value" name="discount_value" required>
                    </div>
                    <div class="mb-3">
                        <label for="min_order_value" class="form-label">Giá trị đơn hàng tối thiểu</label>
                        <input type="number" class="form-control" id="min_order_value" name="min_order_value">
                    </div>
                    <div class="mb-3">
                        <label for="max_uses" class="form-label">Số lần sử dụng tối đa</label>
                        <input type="number" class="form-control" id="max_uses" name="max_uses" required>
                    </div>
                    <div class="mb-3">
                        <label for="expiry_date" class="form-label">Ngày hết hạn</label>
                        <input type="date" class="form-control" id="expiry_date" name="expiry_date" required>
                    </div>
                    <div class="mb-3">
                        <label for="is_active" class="form-label">Trạng thái</label>
                        <select class="form-control" id="is_active" name="is_active">
                            <option value="1">Kích hoạt</option>
                            <option value="0">Không kích hoạt</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Thêm</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal Sửa Mã Giảm Giá -->
<div class="modal fade" id="editDiscountCodeModal" tabindex="-1" aria-labelledby="editDiscountCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editDiscountCodeModalLabel">Chỉnh Sửa Mã Giảm Giá</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editDiscountCodeForm">
                    <input type="hidden" id="edit_id" name="id">
                    <div class="mb-3">
                        <label for="edit_code" class="form-label">Mã giảm giá</label>
                        <input type="text" class="form-control" id="edit_code" name="code" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_discount_type" class="form-label">Loại giảm giá</label>
                        <select class="form-control" id="edit_discount_type" name="discount_type">
                            <option value="percent">Phần trăm</option>
                            <option value="fixed">Giá trị cố định</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_discount_value" class="form-label">Giá trị giảm</label>
                        <input type="number" class="form-control" id="edit_discount_value" name="discount_value" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_min_order_value" class="form-label">Đơn hàng tối thiểu</label>
                        <input type="number" class="form-control" id="edit_min_order_value" name="min_order_value" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_max_uses" class="form-label">Số lần sử dụng tối đa</label>
                        <input type="number" class="form-control" id="edit_max_uses" name="max_uses" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Lưu</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
    <!-- Include jQuery for AJAX -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
$(document).ready(function () {
    $("#addDiscountCodeForm").submit(function (e) {
        e.preventDefault();
        var formData = $(this).serialize();
        $.ajax({
            type: "POST",
            url: "them_magiamgia.php",
            data: formData,
            success: function (response) {
                if (response == "success") {
                    Swal.fire({
                        icon: "success",
                        title: "Thành công!",
                        text: "Mã giảm giá đã được thêm.",
                    }).then(() => {
                        $("#addDiscountCodeModal").modal("hide"); 
                        location.reload();
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Lỗi!",
                        text: "Không thể thêm mã giảm giá.",
                    });
                }
            }
        });
    });
});
</script>
<script>
$(document).ready(function () {
    $(".edit-btn").click(function () {
        $("#edit_id").val($(this).data("id"));
        $("#edit_code").val($(this).data("code"));
        $("#edit_discount_type").val($(this).data("discount_type"));
        $("#edit_discount_value").val($(this).data("discount_value"));
        $("#edit_min_order_value").val($(this).data("min_order_value"));
        $("#edit_max_uses").val($(this).data("max_uses"));
    });

    $("#editDiscountCodeForm").submit(function (e) {
        e.preventDefault();

        var formData = $(this).serialize();
        $.ajax({
            type: "POST",
            url: "update_magiamgia.php",
            data: formData,
            success: function (response) {
                if (response == "success") {
                    Swal.fire("Thành công!", "Mã giảm giá đã được cập nhật.", "success").then(() => {
                        $("#editDiscountCodeModal").modal("hide");
                        location.reload();
                    });
                } else {
                    Swal.fire("Lỗi!", "Không thể cập nhật.", "error");
                }
            }
        });
    });
    // Xóa mã giảm giá
    $(".delete-btn").click(function () {
        var id = $(this).data("id");

        Swal.fire({
            title: "Bạn có chắc chắn muốn xóa?",
            text: "Hành động này không thể hoàn tác!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Xóa",
            cancelButtonText: "Hủy"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    type: "POST",
                    url: "xoa_magiamgia.php",
                    data: { id: id },
                    success: function (response) {
                        if (response == "success") {
                            Swal.fire("Xóa thành công!", "", "success").then(() => location.reload());
                        } else {
                            Swal.fire("Lỗi!", "Không thể xóa.", "error");
                        }
                    }
                });
            }
        });
    });
});
</script>