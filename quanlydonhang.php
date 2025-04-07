<!-- Phần thông báo admin-->
<?php
include 'config.php';
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
    <style>
    .filter-section {
        margin-bottom: 15px;
    }

    .filter-section label {
        margin-right: 10px;
        font-weight: bold;
    }

    .filter-section select {
        padding: 5px 10px; 
        border: 1px solid #ccc; 
        border-radius: 8px;
        cursor: pointer; 
        font-size: 14px; 
        background-color: #fff;
        transition: border-color 0.3s ease; 
    }

    .filter-section select:focus {
        outline: none; 
        border-color: #007bff; 
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.3); 
    }
    </style>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="index.html">Start Bootstrap</a>
            <!-- Sidebar Toggle-->
            <button class="btn btn-link btn-sm oder-1 oder-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
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
                    <h1 class="mt-4">QUẢN LÝ ĐƠN HÀNG</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.html">QUẢN LÝ</a></li>
                        <li class="breadcrumb-item active">QUẢN LÝ ĐƠN HÀNG</li>
                    </ol>
                    <div class="card mb-4"></div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            DANH SÁCH ĐƠN HÀNG
                        </div>
                        <div class="card-body">
                        <table id="datatablesSimple">
                            <div class="filter-section">
                                <label for="statusFilter">Lọc đơn hàng theo trạng thái: </label>
                                <select id="statusFilter" name="statusFilter">
                                    <option value="">Tất cả</option>
                                    <option value="Chờ xác nhận">Chờ xác nhận</option>
                                    <option value="Đã xác nhận">Đã xác nhận</option>
                                    <option value="Đang giao">Đang giao</option>
                                    <option value="Đã giao">Đã giao</option>
                                    <option value="Đã hủy">Đã hủy</option>
                                </select>
                            </div>
                            <thead>
                                <tr>
                                    <th>Ngày đặt hàng</th>
                                    <th>Phương thức thanh toán</th>
                                    <th>Trạng thái</th>
                                    <th>Phí ship</th>
                                    <th>Trạng thái thanh toán</th>
                                    <th>Tổng tiền</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Ngày đặt hàng</th>
                                    <th>Phương thức thanh toán</th>
                                    <th>Trạng thái</th>
                                    <th>Phí ship</th>
                                    <th>Trạng thái thanh toán</th>
                                    <th>Tổng tiền</th>
                                    <th>Thao tác</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                <?php
                                include 'config.php';
                                $sql = "SELECT o.*, u.hoten AS user_name, u.email AS user_email, u.sdt AS user_phone, u.diachi AS user_address 
                                        FROM oder o 
                                        JOIN frm_dangky u ON o.user_id = u.id";
                                $result = $conn->query($sql);

                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $oder_id = $row["id"];
                                        $oder_details_sql = "SELECT od.*, sp.tensanpham, sp.img 
                                                            FROM oder_detail od 
                                                            JOIN sanpham sp ON od.sanpham_id = sp.id 
                                                            WHERE od.oder_id = ?";
                                        $stmt = $conn->prepare($oder_details_sql);
                                        $stmt->bind_param("i", $oder_id);
                                        $stmt->execute();
                                        $oder_details_result = $stmt->get_result();
                                        $oder_details = [];
                                        if ($oder_details_result->num_rows > 0) {
                                            while ($item = $oder_details_result->fetch_assoc()) {
                                                $oder_details[] = $item;
                                            }
                                        }
                                        $stmt->close();
                                        $oder_details_json = json_encode($oder_details);
                                        echo "<tr>";
                                        echo "<td>" . $row["ngaydathang"] . "</td>";
                                        echo "<td>" . $row["payment_method"] . "</td>";
                                        echo "<td>" . $row["trangthai"] . "</td>";
                                        echo "<td>" . number_format($row["shipping_cost"], 0, ',', '.') . " VND</td>";
                                        echo "<td>" . $row["payment_status"] . "</td>";
                                        echo "<td>" . number_format($row["total"], 0, ',', '.') . " VND</td>"; // Tổng tiền di chuyển lên trước Hành động
                                        echo "<td>";
                                        echo "<button class='btn btn-info btn-sm view-btn'
                                            data-id='" . $row["id"] . "'
                                            data-user_id='" . $row["user_id"] . "'
                                            data-total='" . $row["total"] . "'
                                            data-ngaydathang='" . $row["ngaydathang"] . "'
                                            data-payment_method='" . $row["payment_method"] . "'
                                            data-trangthai='" . $row["trangthai"] . "'
                                            data-shipping_cost='" . $row["shipping_cost"] . "'
                                            data-payment_status='" . $row["payment_status"] . "'
                                            data-user_name='" . htmlspecialchars($row["user_name"], ENT_QUOTES, 'UTF-8') . "'
                                            data-user_email='" . htmlspecialchars($row["user_email"], ENT_QUOTES, 'UTF-8') . "'
                                            data-user_phone='" . htmlspecialchars($row["user_phone"], ENT_QUOTES, 'UTF-8') . "'
                                            data-user_address='" . htmlspecialchars($row["user_address"], ENT_QUOTES, 'UTF-8') . "'
                                            data-oder_details='" . htmlspecialchars($oder_details_json, ENT_QUOTES, 'UTF-8') . "'>
                                            Xem chi tiết
                                        </button> ";
                                        // Xử lý nút "Xác nhận" dựa trên trạng thái
                                        $trangthai = $row["trangthai"];
                                        $buttonText = "";
                                        $buttonClass = "btn btn-warning btn-sm update-status-btn";
                                        $disabled = "";
                            
                                        if ($trangthai == "Chờ xác nhận") {
                                            $buttonText = "Xác nhận";
                                        } elseif ($trangthai == "Đã xác nhận") {
                                            $buttonText = "Đang giao";
                                        } elseif ($trangthai == "Đang giao") {
                                            $buttonText = "Đã giao";
                                        } elseif ($trangthai == "Đã giao") {
                                            $buttonText = "Đã giao";
                                            $disabled = "disabled";
                                        }
                                        echo "<button class='$buttonClass' $disabled
                                        data-id='" . $row["id"] . "'
                                        data-trangthai='" . $trangthai . "'>
                                        $buttonText
                                    </button> ";
                                        $invoiceStatus = $row["invoice_status"]; 
                                        echo "<button class='btn btn-secondary btn-sm' 
                                                    id='printOrder' 
                                                    data-invoice-status='" . htmlspecialchars($invoiceStatus) . "'>
                                                    " . ($invoiceStatus === "Đã in" ? "Đã in" : "In Đơn") . "
                                                </button>";

                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>Không có đơn hàng nào</td></tr>";
                                }
                                $conn->close();
                                ?>
                            </tbody>
                        </table>
                            <div id="oderDetailsSection" class="mt-4" style="display: none;">
                                <div class="card">
                                    <div class="card-header">
                                        <h5>CHI TIẾT ĐƠN HÀNG</h5>
                                    </div>
                                    <div class="card-body">
                                        <form>
                                            <h5 class="mt-4">Thông tin người dùng</h5>
                                            <div class="row mb-3">
                                                <div class="col-md-3">
                                                    <label for="detailUserName" class="form-label">Tên người dùng</label>
                                                    <input type="text" class="form-control" id="detailUserName" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="detailUserEmail" class="form-label">Email</label>
                                                    <input type="text" class="form-control" id="detailUserEmail" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="detailUserPhone" class="form-label">Số điện thoại</label>
                                                    <input type="text" class="form-control" id="detailUserPhone" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="detailUserAddress" class="form-label">Địa chỉ</label>
                                                    <input type="text" class="form-control" id="detailUserAddress" readonly>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                
                                                <div class="col-md-3">
                                                    <label for="detailoderDate" class="form-label">Ngày đặt hàng</label>
                                                    <input type="text" class="form-control" id="detailoderDate" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="detailPaymentMethod" class="form-label">Phương thức thanh toán</label>
                                                    <input type="text" class="form-control" id="detailPaymentMethod" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="detailStatus" class="form-label">Trạng thái</label>
                                                    <input type="text" class="form-control" id="detailStatus" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="detailPaymentStatus" class="form-label">Trạng thái thanh toán</label>
                                                    <input type="text" class="form-control" id="detailPaymentStatus" readonly>
                                                </div>
                                            </div>
                                            <div class="row mb-3">
                                                <div class="col-md-3">
                                                    <label for="detailShippingCost" class="form-label">Phí ship</label>
                                                    <input type="text" class="form-control" id="detailShippingCost" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="detailTotal" class="form-label">Tổng tiền</label>
                                                    <input type="text" class="form-control" id="detailTotal" readonly>
                                                </div>
                                            </div>
                                            <h5 class="mt-4">Danh sách sản phẩm</h5>
                                            <table class="table table-bodered">
                                                <thead>
                                                    <tr>
                                                        <th>Hình ảnh</th>
                                                        <th>Tên sản phẩm</th>
                                                        <th>Số lượng</th>
                                                        <th>Giá</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="oderItemsTableBody">
                                                </tbody>
                                            </table>
                                            <button type="button" class="btn btn-secondary" id="closeDetails">Đóng</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
    </body>
</html>
<!-- này là trạng thái đơn-->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const viewButtons = document.querySelectorAll('.view-btn');
    const updateStatusButtons = document.querySelectorAll('.update-status-btn');
    const oderDetailsSection = document.getElementById('oderDetailsSection');
    const closeDetailsButton = document.getElementById('closeDetails');
    const oderItemsTableBody = document.getElementById('oderItemsTableBody');
    viewButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = button.getAttribute('data-id');
            const userId = button.getAttribute('data-user_id');
            const total = button.getAttribute('data-total');
            const oderDate = button.getAttribute('data-ngaydathang');
            const paymentMethod = button.getAttribute('data-payment_method');
            const status = button.getAttribute('data-trangthai');
            const shippingCost = button.getAttribute('data-shipping_cost');
            const paymentStatus = button.getAttribute('data-payment_status');
            const userName = button.getAttribute('data-user_name');
            const userEmail = button.getAttribute('data-user_email');
            const userPhone = button.getAttribute('data-user_phone');
            const userAddress = button.getAttribute('data-user_address');
            const oderDetails = JSON.parse(button.getAttribute('data-oder_details'));

            document.getElementById('detailUserName').value = userName || 'Không có thông tin';
            document.getElementById('detailUserEmail').value = userEmail || 'Không có thông tin';
            document.getElementById('detailUserPhone').value = userPhone || 'Không có thông tin';
            document.getElementById('detailUserAddress').value = userAddress || 'Không có thông tin';

            document.getElementById('detailTotal').value = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(total);
            document.getElementById('detailoderDate').value = oderDate;
            document.getElementById('detailPaymentMethod').value = paymentMethod;
            document.getElementById('detailStatus').value = status;
            document.getElementById('detailShippingCost').value = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(shippingCost);
            document.getElementById('detailPaymentStatus').value = paymentStatus;

            oderItemsTableBody.innerHTML = '';
            if (oderDetails.length > 0) {
                oderDetails.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td><img src="${item.img}" alt="${item.tensanpham}" style="width: 50px; height: 50px; object-fit: cover;"></td>
                        <td>${item.tensanpham}</td>
                        <td>${item.soluong}</td>
                        <td>${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(item.gia)}</td>
                    `;
                    oderItemsTableBody.appendChild(row);
                });
            } else {
                oderItemsTableBody.innerHTML = '<tr><td colspan="5">Không có sản phẩm nào trong đơn hàng này</td></tr>';
            }

            oderDetailsSection.style.display = 'block';
            oderDetailsSection.scrollIntoView({ behavior: 'smooth' });
        });
    });

    closeDetailsButton.addEventListener('click', function () {
        oderDetailsSection.style.display = 'none';
    });

    // Xử lý nút "Xác nhận", "Đang giao", "Đã giao", "Đã hủy"
    updateStatusButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = button.getAttribute('data-id');
            const currentStatus = button.getAttribute('data-trangthai');
            let newStatus = '';
            if (currentStatus === 'Chờ xác nhận') {
                newStatus = 'Đã xác nhận';
            } else if (currentStatus === 'Đã xác nhận') {
                newStatus = 'Đang giao';
            } else if (currentStatus === 'Đang giao') {
                newStatus = 'Đã giao';
            } else if (currentStatus === 'Đã giao') {
                newStatus = 'Đã hủy';
            }

            fetch('update_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${id}&trangthai=${newStatus}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.setAttribute('data-trangthai', newStatus);
                    button.textContent = newStatus === 'Đã xác nhận' ? 'Đang giao' :
                                         newStatus === 'Đang giao' ? 'Đã giao' :
                                         newStatus === 'Đã giao' ? 'Đã hủy' : '';
                    if (newStatus === 'Đã hủy') {
                        button.remove();
                    }
                    const statusCell = button.closest('tr').children[2];
                    statusCell.textContent = newStatus;
                } else {
                    alert('Cập nhật trạng thái thất bại!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Đã xảy ra lỗi khi cập nhật trạng thái!');
            });
        });
    });
});
</script>
<!-- này là lọc đơn trạng thái đơn-->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const viewButtons = document.querySelectorAll('.view-btn');
    const updateStatusButtons = document.querySelectorAll('.update-status-btn');
    const oderDetailsSection = document.getElementById('oderDetailsSection');
    const closeDetailsButton = document.getElementById('closeDetails');
    const oderItemsTableBody = document.getElementById('oderItemsTableBody');
    const statusFilter = document.getElementById('statusFilter');
    const tableRows = document.querySelectorAll('#datatablesSimple tbody tr');

    // Xử lý nút "Xem chi tiết"
    viewButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = button.getAttribute('data-id');
            const userId = button.getAttribute('data-user_id');
            const total = button.getAttribute('data-total');
            const oderDate = button.getAttribute('data-ngaydathang');
            const paymentMethod = button.getAttribute('data-payment_method');
            const status = button.getAttribute('data-trangthai');
            const shippingCost = button.getAttribute('data-shipping_cost');
            const paymentStatus = button.getAttribute('data-payment_status');
            const userName = button.getAttribute('data-user_name');
            const userEmail = button.getAttribute('data-user_email');
            const userPhone = button.getAttribute('data-user_phone');
            const userAddress = button.getAttribute('data-user_address');
            const oderDetails = JSON.parse(button.getAttribute('data-oder_details'));

            document.getElementById('detailUserName').value = userName || 'Không có thông tin';
            document.getElementById('detailUserEmail').value = userEmail || 'Không có thông tin';
            document.getElementById('detailUserPhone').value = userPhone || 'Không có thông tin';
            document.getElementById('detailUserAddress').value = userAddress || 'Không có thông tin';

            document.getElementById('detailTotal').value = new Intl.NumberFormat('vi-VN').format(total) + " VND";
            document.getElementById('detailoderDate').value = oderDate;
            document.getElementById('detailPaymentMethod').value = paymentMethod;
            document.getElementById('detailStatus').value = status;
            document.getElementById('detailShippingCost').value = new Intl.NumberFormat('vi-VN').format(shippingCost) + " VND";
            document.getElementById('detailPaymentStatus').value = paymentStatus;

            oderItemsTableBody.innerHTML = '';
            if (oderDetails.length > 0) {
                oderDetails.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td><img src="${item.img}" alt="${item.tensanpham}" style="width: 50px; height: 50px; object-fit: cover;"></td>
                        <td>${item.tensanpham}</td>
                        <td>${item.soluong}</td>
                        <td>${new Intl.NumberFormat('vi-VN').format(item.gia)} VND</td>
                    `;
                    oderItemsTableBody.appendChild(row);
                });
            } else {
                oderItemsTableBody.innerHTML = '<tr><td colspan="5">Không có sản phẩm nào trong đơn hàng này</td></tr>';
            }

            oderDetailsSection.style.display = 'block';
            oderDetailsSection.scrollIntoView({ behavior: 'smooth' });
        });
    });

    closeDetailsButton.addEventListener('click', function () {
        oderDetailsSection.style.display = 'none';
    });

    updateStatusButtons.forEach(button => {
        button.addEventListener('click', function () {
            const id = button.getAttribute('data-id');
            const currentStatus = button.getAttribute('data-trangthai');
            let newStatus = '';

            if (currentStatus === 'Chờ xác nhận') {
                newStatus = 'Đã xác nhận';
            } else if (currentStatus === 'Đã xác nhận') {
                newStatus = 'Đang giao';
            } else if (currentStatus === 'Đang giao') {
                newStatus = 'Đã giao';
            }

            fetch('update_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id=${id}&trangthai=${newStatus}`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    button.setAttribute('data-trangthai', newStatus);
                    button.textContent = newStatus === 'Đã xác nhận' ? 'Đang giao' :
                                         newStatus === 'Đang giao' ? 'Đã giao' : 'Đã giao';
                    if (newStatus === 'Đã giao') {
                        button.setAttribute('disabled', 'disabled');
                    }
                    const statusCell = button.closest('tr').children[2]; // Cột "Trạng thái" giờ là cột thứ 3
                    statusCell.textContent = newStatus;
                } else {
                    alert('Cập nhật trạng thái thất bại!');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Đã xảy ra lỗi khi cập nhật trạng thái!');
            });
        });
    });

    // Xử lý lọc đơn hàng theo trạng thái
    statusFilter.addEventListener('change', function () {
        const selectedStatus = this.value;

        tableRows.forEach(row => {
            const statusCell = row.children[2]; 
            const rowStatus = statusCell.textContent.trim();

            if (selectedStatus === '' || rowStatus === selectedStatus) {
                row.style.display = ''; // Hiển thị hàng
            } else {
                row.style.display = 'none'; // Ẩn hàng
            }
        });
    });
});
</script>
<!-- Bao gồm SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById("editUserForm").addEventListener("submit", function(e) {
    e.preventDefault(); // Ngăn form submit mặc định
    let formData = new FormData(this);
    Swal.fire({
        title: "Đang xử lý...",
        text: "Vui lòng chờ...",
        icon: "info",
        allowOutsideClick: false,
        showConfirmButton: false,
        timerProgressBar: true
    });
    fetch("update_nguoidung.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if(data.status === "success"){
            Swal.fire({
                title: "Thành công!",
                text: data.message,
                icon: "success"
            }).then(() => {
                var modalEl = document.getElementById('editUserModal');
                var modal = bootstrap.Modal.getInstance(modalEl);
                modal.hide();
                location.reload();
            });
        } else {
            Swal.fire({
                title: "Lỗi!",
                text: data.message,
                icon: "error"
            });
        }
    })
    .catch(error => {
        Swal.fire({
            title: "Lỗi!",
            text: "Có lỗi xảy ra. Vui lòng thử lại!",
            icon: "error"
        });
        console.error("Lỗi:", error);
    });
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const printButton = document.getElementById("printOrder");
    let invoiceStatus = printButton.getAttribute("data-invoice-status");

    // Initial button state
    if (invoiceStatus === "Đã in") {
        printButton.textContent = "Đã in";
        printButton.disabled = true;
    } else {
        printButton.textContent = "In Đơn";
        printButton.disabled = false;
    }

    printButton.addEventListener("click", function () {
        if (invoiceStatus === "Đã in") {
            alert("Hóa đơn này đã được in!");
            return;
        }

        // Get order details for PDF
        function maskPhoneNumber(phone) {
            return phone.slice(0, 5) + "*****";
        }

        function maskEmail(email) {
            let parts = email.split("@");
            return parts[0].length > 3 ? parts[0].slice(0, 3) + "*****@" + parts[1] : "*****@" + parts[1];
        }

        let userName = document.getElementById("detailUserName").value;
        let email = maskEmail(document.getElementById("detailUserEmail").value);
        let phone = maskPhoneNumber(document.getElementById("detailUserPhone").value);
        let address = document.getElementById("detailUserAddress").value;
        let orderDate = document.getElementById("detailoderDate").value;
        let paymentMethod = document.getElementById("detailPaymentMethod").value;
        let status = document.getElementById("detailStatus").value;
        let paymentStatus = document.getElementById("detailPaymentStatus").value;
        let shippingCost = document.getElementById("detailShippingCost").value + " VND";
        let total = document.getElementById("detailTotal").value.replace("VND VND", "VND");

        let docDefinition = {
            content: [
                { text: "HÓA ĐƠN MUA HÀNG", style: "header" },
                {
                    table: {
                        widths: ["35%", "65%"],
                        body: [
                            [{ text: "Tên khách hàng:", style: "boldText" }, userName],
                            [{ text: "Email:", style: "boldText" }, email],
                            [{ text: "Số điện thoại:", style: "boldText" }, phone],
                            [{ text: "Địa chỉ giao hàng:", style: "boldText" }, address],
                            [{ text: "Ngày đặt hàng:", style: "boldText" }, orderDate],
                            [{ text: "Phương thức thanh toán:", style: "boldText" }, paymentMethod],
                            [{ text: "Trạng thái đơn hàng:", style: "boldText" }, status],
                            [{ text: "Trạng thái thanh toán:", style: "boldText" }, paymentStatus],
                        ],
                    },
                    layout: "lightHorizontalLines",
                    margin: [0, 10, 0, 10],
                },
                { text: "Chi tiết đơn hàng", style: "subheader" },
                {
                    table: {
                        headerRows: 1,
                        widths: ["65%", "15%", "20%"],
                        body: [
                            [
                                { text: "Sản phẩm", style: "tableHeader" },
                                { text: "Số lượng", style: "tableHeader" },
                                { text: "Đơn giá", style: "tableHeader" },
                            ],
                            ...Array.from(document.querySelectorAll("#oderItemsTableBody tr")).map(row => {
                                let cells = Array.from(row.cells).slice(1);
                                return [
                                    { text: cells[0].textContent.trim() },
                                    { text: cells[1].textContent.trim(), alignment: "center" },
                                    { text: cells[2].textContent.trim(), alignment: "right" }
                                ];
                            }),
                        ],
                    },
                },
                {
                    table: {
                        widths: ["80%", "20%"],
                        body: [[
                            { text: "Tổng tiền:", style: "totalPrice" },
                            { text: total, style: "totalPrice", alignment: "right" }
                        ]],
                    },
                    layout: "noBorders",
                    margin: [0, 5, 0, 0],
                },
            ],
            styles: {
                header: { fontSize: 22, bold: true, alignment: "center", margin: [0, 0, 0, 10], color: "#EE4D2D" },
                subheader: { fontSize: 16, bold: true, margin: [0, 10, 0, 5] },
                tableHeader: { bold: true, fillColor: "#f3f3f3" },
                boldText: { bold: true },
                totalPrice: { fontSize: 14, bold: true, color: "red" },
            },
        };

        // Generate and download PDF
        pdfMake.createPdf(docDefinition).download("HoaDon.pdf");

        // Update status in UI
        printButton.textContent = "Đã in";
        printButton.disabled = true;
        invoiceStatus = "Đã in";

        // Get the order ID from the view button's data-id attribute
        const viewButton = document.querySelector('.view-btn');
        const orderId = viewButton.getAttribute('data-id');

        // Update status in database
        fetch("updatehoadon.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ 
                id: orderId, 
                invoice_status: "Đã in" 
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log("Invoice status updated successfully");
            } else {
                console.error("Failed to update invoice status:", data.message);
            }
        })
        .catch(error => console.error("Error:", error));
    });
});
</script>