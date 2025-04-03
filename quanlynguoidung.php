
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
<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="index.html">Start Bootstrap</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        </form>
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
                    </div>
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">QUẢN LÝ NGƯỜI DÙNG</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="index.html">QUẢN LÝ</a></li>
                        <li class="breadcrumb-item active">QUẢN LÝ NGƯỜI DÙNG</li>
                    </ol>
                    <div class="card mb-4">
                    </div>
                    <div class="card mb-4">
                        <div class="card-header">
                            <i class="fas fa-table me-1"></i>
                            DANH SÁCH NGƯỜI DÙNG
                        </div>
                        <div class="card-body">
                            <!-- Nút bấm mở Modal -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                                Thêm người dùng mới
                            </button>
                            <table id="datatablesSimple">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên</th>
                                        <th>Ảnh</th>
                                        <th>Email</th>
                                        <th>Quyền</th>
                                        <th>Số điện thoại</th>
                                        <th>Địa chỉ</th>
                                        <th>Trạng thái</th>
                                        <th>Hoạt động</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên</th>
                                        <th>Ảnh</th>
                                        <th>Email</th>
                                        <th>Quyền</th>
                                        <th>Số điện thoại</th>
                                        <th>Địa chỉ</th>
                                        <th>Trạng thái</th>
                                        <th>Hoạt động</th>
                                    </tr>
                                </tfoot>
                                <tbody>
                                    <?php
                                    include 'config.php';
                                    $sql = "SELECT * FROM frm_dangky";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row["id"] . "</td>";
                                            echo "<td>" . htmlspecialchars($row["username"]) . "</td>";
                                            echo "<td><img src='" . htmlspecialchars($row["anh"] ?? 'default.jpg') . "' width='50' height='50' onerror=\"this.src='default.jpg'\"></td>";
                                            echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["phanquyen"]) . "</td>";
                                            echo "<td>" . htmlspecialchars($row["sdt"] ?? '') . "</td>";
                                            echo "<td>" . htmlspecialchars($row["diachi"] ?? '') . "</td>";
                                            echo "<td>" . ($row["trangthai"] == "hoạt động" 
                                                ? "<span style='color: green; font-size: 20px;'>●</span> Hoạt Động" 
                                                : "<span style='color: red; font-size: 20px;'>●</span> Bị Khóa") . "</td>";
                                            echo "<td>
                                                <button class='btn btn-warning btn-sm edit-btn'
                                                    data-id='" . $row["id"] . "'
                                                    data-username='" . htmlspecialchars($row["username"]) . "'
                                                    data-email='" . htmlspecialchars($row["email"]) . "'
                                                    data-sdt='" . htmlspecialchars($row["sdt"] ?? '') . "'
                                                    data-diachi='" . htmlspecialchars($row["diachi"] ?? '') . "'
                                                    data-phanquyen='" . htmlspecialchars($row["phanquyen"]) . "'
                                                    data-anh='" . htmlspecialchars($row["anh"] ?? '') . "'
                                                    data-trangthai='" . htmlspecialchars($row["trangthai"]) . "'
                                                    data-bs-toggle='modal'
                                                    data-bs-target='#editUserModal'>
                                                    Sửa
                                                </button>
                                                <button class='btn btn-info btn-sm permission-btn'
                                                    data-id='" . $row["id"] . "'
                                                    data-bs-toggle='modal'
                                                    data-bs-target='#permissionModal'>
                                                    Quyền
                                                </button>
                                                <button class='btn btn-danger btn-sm delete-btn' data-id='" . $row["id"] . "'>
                                                    Xóa
                                                </button>
                                            </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='9'>Không có người dùng nào</td></tr>";
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
    <script src="js/datatables-simple-demo.js"></script>
    <!-- Modal Thêm người dùng -->
    <div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="addUserForm" action="them_nguoidung.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addUserModalLabel">Thêm người dùng mới</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="username" class="form-label">Tên đăng nhập</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id W="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="phanquyen" class="form-label">Phân quyền</label>
                            <select class="form-select" id="phanquyen" name="phanquyen">
                                <option value="user">User</option>
                                <option value="admin">Admin</option>
                                <option value="nhanvien">Nhân Viên</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="sdt" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="sdt" name="sdt">
                        </div>
                        <div class="mb-3">
                            <label for="province" class="form-label">Tỉnh/Thành phố</label>
                            <select class="form-select" id="province" name="province" required>
                                <option value="">Chọn tỉnh/thành phố</option>
                            </select>
                            <input type="hidden" id="province_name" name="province_name">
                        </div>
                        <div class="mb-3">
                            <label for="district" class="form-label">Quận/Huyện</label>
                            <select class="form-select" id="district" name="district" required>
                                <option value="">Chọn quận/huyện</option>
                            </select>
                            <input type="hidden" id="district_name" name="district_name">
                        </div>
                        <div class="mb-3">
                            <label for="ward" class="form-label">Phường/Xã</label>
                            <select class="form-select" id="ward" name="ward" required>
                                <option value="">Chọn phường/xã</option>
                            </select>
                            <input type="hidden" id="ward_name" name="ward_name">
                        </div>
                        <div class="mb-3">
                            <label for="specific_address" class="form-label">Địa chỉ cụ thể</label>
                            <input type="text" class="form-control" id="specific_address" name="specific_address" placeholder="Số nhà, tên đường..." required>
                        </div>
                        <div class="mb-3">
                            <label for="anh" class="form-label">Ảnh đại diện</label>
                            <input type="file" class="form-control" id="anh" name="anh">
                        </div>
                        <div class="mb-3">
                            <label for="trangthai" class="form-label">Trạng thái</label>
                            <select class="form-select" id="trangthai" name="trangthai">
                                <option value="hoạt động">Hoạt động</option>
                                <option value="đã khóa">Bị khóa</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Modal Chỉnh Sửa Người Dùng -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Chỉnh sửa người dùng</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editUserForm" action="update_nguoidung.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="mb-3">
                            <label for="edit-username" class="form-label">Tên người dùng</label>
                            <input type="text" class="form-control" name="username" id="edit-username" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-email" class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="edit-email" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-password" class="form-label">Mật khẩu</label>
                            <input type="password" class="form-control" name="password" id="edit-password" placeholder="Nhập mật khẩu mới (nếu muốn thay đổi)">
                        </div>
                        <div class="mb-3">
                            <label for="edit-sdt" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" name="sdt" id="edit-sdt">
                        </div>
                        <div class="mb-3">
                            <label for="edit-province" class="form-label">Tỉnh/Thành phố</label>
                            <select class="form-select" id="edit-province" name="province" required>
                                <option value="">Chọn tỉnh/thành phố</option>
                            </select>
                            <input type="hidden" id="edit-province_name" name="province_name">
                        </div>
                        <div class="mb-3">
                            <label for="edit-district" class="form-label">Quận/Huyện</label>
                            <select class="form-select" id="edit-district" name="district" required>
                                <option value="">Chọn quận/huyện</option>
                            </select>
                            <input type="hidden" id="edit-district_name" name="district_name">
                        </div>
                        <div class="mb-3">
                            <label for="edit-ward" class="form-label">Phường/Xã</label>
                            <select class="form-select" id="edit-ward" name="ward" required>
                                <option value="">Chọn phường/xã</option>
                            </select>
                            <input type="hidden" id="edit-ward_name" name="ward_name">
                        </div>
                        <div class="mb-3">
                            <label for="edit-specific_address" class="form-label">Địa chỉ cụ thể</label>
                            <input type="text" class="form-control" id="edit-specific_address" name="specific_address" placeholder="Số nhà, tên đường..." required>
                        </div>
                        <div class="mb-3">
                            <label for="edit-phanquyen" class="form-label">Quyền</label>
                            <select class="form-control" name="phanquyen" id="edit-phanquyen">
                                <option value="admin">Admin</option>
                                <option value="nhanvien">Nhân Viên</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit-trangthai" class="form-label">Trạng thái</label>
                            <select class="form-control" name="trangthai" id="edit-trangthai">
                                <option value="hoạt động">Hoạt động</option>
                                <option value="đã khóa">Bị Khóa</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="edit-anh" class="form-label">Ảnh</label>
                            <br>
                            <img id="current-user-img" src="" width="50" height="50" style="margin-bottom: 10px;">
                            <input type="hidden" name="current_anh" id="current-anh">
                            <input type="file" class="form-control" name="anh" id="edit-anh">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Function to load provinces
async function loadProvinces(selectElement, hiddenInput, selectedProvince = '') {
    try {
        const response = await fetch("https://provinces.open-api.vn/api/p/");
        const data = await response.json();
        selectElement.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';
        data.forEach(province => {
            const option = document.createElement("option");
            option.value = province.code;
            option.textContent = province.name;
            if (province.name === selectedProvince) {
                option.selected = true;
            }
            selectElement.appendChild(option);
        });
        if (selectedProvince && selectElement.value) {
            hiddenInput.value = selectedProvince;
        }
    } catch (error) {
        console.error("Error loading provinces:", error);
        Swal.fire("Lỗi!", "Không thể tải danh sách tỉnh/thành phố. Vui lòng thử lại.", "error");
    }
}

// Function to load districts
async function loadDistricts(provinceCode, districtSelect, districtHiddenInput, wardSelect, wardHiddenInput, selectedDistrict = '') {
    try {
        districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
        wardHiddenInput.value = '';
        if (!provinceCode) return;
        const response = await fetch(`https://provinces.open-api.vn/api/p/${provinceCode}?depth=2`);
        const data = await response.json();
        data.districts.forEach(district => {
            const option = document.createElement("option");
            option.value = district.code;
            option.textContent = district.name;
            if (district.name === selectedDistrict) {
                option.selected = true;
            }
            districtSelect.appendChild(option);
        });
        if (selectedDistrict && districtSelect.value) {
            districtHiddenInput.value = selectedDistrict;
        }
    } catch (error) {
        console.error("Error loading districts:", error);
        Swal.fire("Lỗi!", "Không thể tải danh sách quận/huyện. Vui lòng thử lại.", "error");
    }
}

// Function to load wards
async function loadWards(districtCode, wardSelect, wardHiddenInput, selectedWard = '') {
    try {
        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
        if (!districtCode) return;
        const response = await fetch(`https://provinces.open-api.vn/api/d/${districtCode}?depth=2`);
        const data = await response.json();
        data.wards.forEach(ward => {
            const option = document.createElement("option");
            option.value = ward.code;
            option.textContent = ward.name;
            if (ward.name === selectedWard) {
                option.selected = true;
            }
            wardSelect.appendChild(option);
        });
        if (selectedWard && wardSelect.value) {
            wardHiddenInput.value = selectedWard;
        }
    } catch (error) {
        console.error("Error loading wards:", error);
        Swal.fire("Lỗi!", "Không thể tải danh sách phường/xã. Vui lòng thử lại.", "error");
    }
}

// Initialize address dropdowns for Add User form
document.addEventListener("DOMContentLoaded", function () {
    const provinceSelect = document.getElementById("province");
    const districtSelect = document.getElementById("district");
    const wardSelect = document.getElementById("ward");
    const provinceHidden = document.getElementById("province_name");
    const districtHidden = document.getElementById("district_name");
    const wardHidden = document.getElementById("ward_name");

    // Load provinces for Add User form
    loadProvinces(provinceSelect, provinceHidden);

    // Update districts when province changes
    provinceSelect.addEventListener("change", function () {
        const provinceCode = this.value;
        const provinceName = this.options[this.selectedIndex].text;
        provinceHidden.value = provinceName;
        loadDistricts(provinceCode, districtSelect, districtHidden, wardSelect, wardHidden);
    });

    // Update wards when district changes
    districtSelect.addEventListener("change", function () {
        const districtCode = this.value;
        const districtName = this.options[this.selectedIndex].text;
        districtHidden.value = districtName;
        loadWards(districtCode, wardSelect, wardHidden);
    });

    // Update ward name when ward changes
    wardSelect.addEventListener("change", function () {
        const wardName = this.options[this.selectedIndex].text;
        wardHidden.value = wardName;
    });

    // Initialize address dropdowns for Edit User form
    const editProvinceSelect = document.getElementById("edit-province");
    const editDistrictSelect = document.getElementById("edit-district");
    const editWardSelect = document.getElementById("edit-ward");
    const editProvinceHidden = document.getElementById("edit-province_name");
    const editDistrictHidden = document.getElementById("edit-district_name");
    const editWardHidden = document.getElementById("edit-ward_name");

    // Load provinces for Edit User form
    loadProvinces(editProvinceSelect, editProvinceHidden);

    // Update districts when province changes
    editProvinceSelect.addEventListener("change", function () {
        const provinceCode = this.value;
        const provinceName = this.options[this.selectedIndex].text;
        editProvinceHidden.value = provinceName;
        loadDistricts(provinceCode, editDistrictSelect, editDistrictHidden, editWardSelect, editWardHidden);
    });

    // Update wards when district changes
    editDistrictSelect.addEventListener("change", function () {
        const districtCode = this.value;
        const districtName = this.options[this.selectedIndex].text;
        editDistrictHidden.value = districtName;
        loadWards(districtCode, editWardSelect, editWardHidden);
    });

    // Update ward name when ward changes
    editWardSelect.addEventListener("change", function () {
        const wardName = this.options[this.selectedIndex].text;
        editWardHidden.value = wardName;
    });

    // Populate the edit modal with user data
    const editButtons = document.querySelectorAll(".edit-btn");
    editButtons.forEach(button => {
        button.addEventListener("click", function () {
            document.getElementById("edit-id").value = this.dataset.id;
            document.getElementById("edit-username").value = this.dataset.username;
            document.getElementById("edit-email").value = this.dataset.email;
            document.getElementById("edit-sdt").value = this.dataset.sdt || '';
            document.getElementById("edit-phanquyen").value = this.dataset.phanquyen;
            document.getElementById("edit-trangthai").value = this.dataset.trangthai;
            document.getElementById("current-user-img").src = this.dataset.anh || 'default.jpg';
            document.getElementById("current-anh").value = this.dataset.anh || '';

            // Parse the diachi field into components
            const diachi = this.dataset.diachi || '';
            const addressParts = diachi.split(', ');
            const specificAddress = addressParts[0] || '';
            const ward = addressParts[1] || '';
            const district = addressParts[2] || '';
            const province = addressParts[3] || '';

            document.getElementById("edit-specific_address").value = specificAddress;
            editProvinceHidden.value = province;
            editDistrictHidden.value = district;
            editWardHidden.value = ward;

            // Load provinces and pre-select
            loadProvinces(editProvinceSelect, editProvinceHidden, province).then(() => {
                if (editProvinceSelect.value) {
                    loadDistricts(editProvinceSelect.value, editDistrictSelect, editDistrictHidden, editWardSelect, editWardHidden, district).then(() => {
                        if (editDistrictSelect.value) {
                            loadWards(editDistrictSelect.value, editWardSelect, editWardHidden, ward);
                        }
                    });
                }
            });
        });
    });

    // Handle Add User form submission
    document.getElementById("addUserForm").addEventListener("submit", function (e) {
        e.preventDefault();
        let formData = new FormData(this);
        Swal.fire({
            title: "Đang xử lý...",
            text: "Vui lòng chờ...",
            icon: "info",
            allowOutsideClick: false,
            showConfirmButton: false,
            timerProgressBar: true
        });
        fetch("them_nguoidung.php", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === "success") {
                Swal.fire({
                    title: "Thành công!",
                    text: data.message,
                    icon: "success"
                }).then(() => {
                    document.getElementById("addUserForm").reset();
                    const modalElement = document.getElementById('addUserModal');
                    const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
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

    // Handle Edit User form submission
    document.getElementById("editUserForm").addEventListener("submit", function (e) {
        e.preventDefault();
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
            if (data.status === "success") {
                Swal.fire({
                    title: "Thành công!",
                    text: data.message,
                    icon: "success"
                }).then(() => {
                    const modalElement = document.getElementById('editUserModal');
                    const modal = bootstrap.Modal.getOrCreateInstance(modalElement);
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

    // Handle Delete User
    document.querySelectorAll(".delete-btn").forEach(function (button) {
        button.addEventListener("click", function () {
            var userId = this.dataset.id;
            Swal.fire({
                title: "Bạn có chắc chắn?",
                text: "Dữ liệu sẽ bị xóa vĩnh viễn!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Xóa",
                cancelButtonText: "Hủy"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("delete_nguoidung.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: "id=" + encodeURIComponent(userId)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === "success") {
                            Swal.fire("Đã xóa!", data.message, "success").then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire("Lỗi!", data.message, "error");
                        }
                    })
                    .catch(error => {
                        Swal.fire("Lỗi!", "Có lỗi xảy ra. Vui lòng thử lại!", "error");
                        console.error("Error:", error);
                    });
                }
            });
        });
    });
});
</script>
