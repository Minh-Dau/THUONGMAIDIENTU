
<!-- Phần thông báo admin-->
<?php
include 'config.php'; // Kết nối với database

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
                        </div>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">QUẢN LÝ SẢN PHẨM</h1>
                        <ol class="breadcrumb mb-4">
                            <li class="breadcrumb-item"><a href="index.html">QUẢN LÝ</a></li>
                            <li class="breadcrumb-item active">QUẢN LÝ SẢN PHẨM</li>
                        </ol>
                        <div class="card mb-4">
                        </div>
                        <div class="card mb-4">
                            <div class="card-header">
                                <i class="fas fa-table me-1"></i>
                                Danh sách sản phẩm
                            </div>
                            <div class="card-body">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">Thêm sản phẩm</button>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">QUẢN LÝ DANH MỤC</button>
                                <table id="datatablesSimple">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Ảnh</th>
                                            <th>Giá nhập</th>
                                            <th>Giá bán</th>
                                            <th>Số lượng</th>
                                            <th>Loại</th>
                                            <th>Nội dung</th>
                                            <th>Trạng thái</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tên sản phẩm</th>
                                            <th>Ảnh</th>
                                            <th>Giá nhập</th>
                                            <th>Giá bán</th>
                                            <th>Số lượng</th>
                                            <th>Loại</th>
                                            <th>Nội dung</th>
                                            <th>Trạng thái</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <?php
                                        include 'config.php';
                                        $sql = "SELECT sp.id, sp.tensanpham, sp.img, sp.gia_nhap, sp.gia, sp.soluong, 
                                                    sp.noidungsanpham, sp.trangthai, dm.tendanhmuc 
                                                FROM sanpham sp
                                                JOIN danhmuc dm ON sp.danhmuc_id = dm.id";  // Thay vì lấy loaisanpham, ta lấy tendanhmuc từ bảng danhmuc

                                        $result = $conn->query($sql);
                                        if ($result->num_rows > 0) {
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>" . $row["id"] . "</td>";
                                                echo "<td>" . $row["tensanpham"] . "</td>";
                                                echo "<td><img src='" . $row["img"] . "' width='50' height='50'></td>";
                                                echo "<td>" . $row["gia_nhap"] . "</td>";
                                                echo "<td>" . $row["gia"] . "</td>";
                                                echo "<td>" . $row["soluong"] . "</td>";
                                                echo "<td>" . $row["tendanhmuc"] . "</td>";  // Hiển thị tên danh mục thay vì mã danh mục
                                                echo "<td>" . $row["noidungsanpham"] . "</td>";
                                                echo "<td>" . ($row["trangthai"] == "Hiển thị" 
                                                ? "<span style='color: green; font-size: 20px;'>&#x25CF;</span> Hiển thị" 
                                                : "<span style='color: red; font-size: 20px;'>&#x25CF;</span> Đang ẩn") . "</td>";                                            
                                                echo "<td>
                                                        <button class='btn btn-warning btn-sm edit-btn' 
                                                            data-id='" . $row["id"] . "' 
                                                            data-tensanpham='" . $row["tensanpham"] . "'
                                                            data-img='" . $row["img"] . "'
                                                            data-gia_nhap='" . $row["gia_nhap"] . "'
                                                            data-gia='" . $row["gia"] . "'
                                                            data-soluong='" . $row["soluong"] . "'
                                                            data-danhmuc_id='" . $row["tendanhmuc"] . "' 
                                                            data-noidungsanpham='" . $row["noidungsanpham"] . "'
                                                            data-trangthai='" . $row["trangthai"] . "'
                                                            data-bs-toggle='modal' data-bs-target='#editModal'>
                                                            Sửa
                                                        </button>
                                                        <button class='btn btn-danger btn-sm delete-btn' data-id='" . $row["id"] . "'>
                                                            Xóa
                                                        </button>
                                                    </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='10'>Không có sản phẩm nào</td></tr>";
                                        }
                                        $conn->close();
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </main>
               <!-- Modal Thêm Sản Phẩm -->
            <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addProductModalLabel">Thêm Sản Phẩm</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="addProductForm" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="add-tensanpham" class="form-label">Tên sản phẩm</label>
                                    <input type="text" class="form-control" name="tensanpham" id="add-tensanpham" required>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="add-gia_nhap" class="form-label">Giá nhập</label>
                                        <input type="number" class="form-control" name="gia_nhap" id="add-gia_nhap" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="add-gia" class="form-label">Giá bán</label>
                                        <input type="number" class="form-control" name="gia" id="add-gia" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label for="add-soluong" class="form-label">Số lượng</label>
                                        <input type="number" class="form-control" name="soluong" id="add-soluong" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="add-danhmuc_id" class="form-label">Danh mục sản phẩm</label>
                                        <select class="form-control" name="danhmuc_id" id="add-danhmuc_id" required>
                                            <?php
                                            include 'config.php';
                                            $sql = "SELECT id, tendanhmuc FROM danhmuc WHERE trangthai = 'Hiển thị'";
                                            $result = $conn->query($sql);

                                            if ($result === false) {
                                                echo "<option value=''>Lỗi: " . $conn->error . "</option>";
                                            } elseif ($result->num_rows > 0) {
                                                while ($row = $result->fetch_assoc()) {
                                                    echo "<option value='" . $row["id"] . "'>" . $row["tendanhmuc"] . "</option>";
                                                }
                                            } else {
                                                echo "<option value=''>Không có danh mục nào</option>";
                                            }
                                            $conn->close();
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="add-noidungsanpham" class="form-label">Nội dung sản phẩm</label>
                                    <textarea class="form-control" name="noidungsanpham" id="add-noidungsanpham" required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="add-img" class="form-label">Chọn ảnh</label>
                                    <input type="file" class="form-control" name="img" id="add-img" required>
                                </div>
                                <div class="mb-3">
                                    <label for="add-trangthai" class="form-label">Trạng thái</label>
                                    <select class="form-control" name="trangthai" id="add-trangthai" required>
                                        <option value="Hiển thị">Hiển thị</option>
                                        <option value="Ẩn">Ẩn</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-primary">Thêm</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- Modal Quản lý Danh mục -->
            <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg"> <!-- Tăng kích thước modal để chứa bảng -->
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addCategoryModalLabel">Quản lý danh mục</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <h6>Thêm danh mục mới</h6>
                            <form id="addCategoryForm">
                                <div class="mb-3">
                                    <label for="add-tendanhmuc" class="form-label">Tên danh mục</label>
                                    <input type="text" class="form-control" name="tendanhmuc" id="add-tendanhmuc" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Thêm</button>
                            </form>
                            <hr>
                            <h6>Danh sách danh mục</h6>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên danh mục</th>
                                        <th>Trạng thái</th>
                                        <th>Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include 'config.php';
                                    $sql = "SELECT id, tendanhmuc, trangthai FROM danhmuc";
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            $trangthai = $row["trangthai"] == "Hiển thị" ? "Hiển thị" : "Ẩn";
                                            $buttonText = $row["trangthai"] == "Hiển thị" ? "Ẩn" : "Hiển thị";
                                            echo "<tr>";
                                            echo "<td>" . $row["id"] . "</td>";
                                            echo "<td>" . $row["tendanhmuc"] . "</td>";
                                            echo "<td>" . $trangthai . "</td>";
                                            echo "<td>
                                                    <button class='btn btn-warning btn-sm edit-category-btn' 
                                                            data-id='" . $row["id"] . "' 
                                                            data-tendanhmuc='" . $row["tendanhmuc"] . "' 
                                                            data-bs-toggle='modal' 
                                                            data-bs-target='#editCategoryModal'>Sửa</button>
                                                    <button class='btn btn-danger btn-sm delete-category-btn' 
                                                            data-id='" . $row["id"] . "'>Xóa</button>
                                                    <button class='btn btn-info btn-sm toggle-category-btn' 
                                                            data-id='" . $row["id"] . "' 
                                                            data-trangthai='" . $row["trangthai"] . "'>" . $buttonText . "</button>
                                                </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='4'>Không có danh mục nào</td></tr>";
                                    }
                                    $conn->close();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Chỉnh sửa Danh mục -->
                <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editCategoryModalLabel">Chỉnh sửa danh mục</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form id="editCategoryForm">
                                <div class="modal-body">
                                    <input type="hidden" name="id" id="edit-category-id">
                                    <div class="mb-3">
                                        <label for="edit-tendanhmuc" class="form-label">Tên danh mục</label>
                                        <input type="text" class="form-control" name="tendanhmuc" id="edit-tendanhmuc" required>
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
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
        <?php
            include 'config.php';
            if (isset($_GET["id"])) {
                $id = $_GET["id"];
                $sql = "SELECT trangthai FROM danhmuc WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $id);
                $stmt->execute();
                $result = $stmt->get_result();
                $trangthai_danhmuc = ($row = $result->fetch_assoc()) ? $row["trangthai"] : "Không tồn tại";

                $stmt->close();
            }
            ?>
    <!-- Modal Chỉnh Sửa Sản Phẩm -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Chỉnh sửa sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="update_product.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="id" id="edit-id">
                        <div class="mb-3">
                            <label for="edit-tensanpham" class="form-label">Tên sản phẩm</label>
                            <input type="text" class="form-control" name="tensanpham" id="edit-tensanpham">
                        </div>
                        <div class="mb-3">
                            <label for="edit-img" class="form-label">Hình ảnh hiện tại</label><br>
                            <img id="current-img" src="" width="100"><br>
                            <label for="edit-new-img" class="form-label mt-2">Chọn ảnh mới</label>
                            <input type="file" class="form-control" name="new_img" id="edit-new-img">
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="edit-gia_nhap" class="form-label">Giá nhập</label>
                                <input type="number" class="form-control" name="gia_nhap" id="edit-gia_nhap">
                            </div>
                            <div class="col-md-6">
                                <label for="edit-gia" class="form-label">Giá</label>
                                <input type="number" class="form-control" name="gia" id="edit-gia">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label for="edit-soluong" class="form-label">Số lượng</label>
                                <input type="number" class="form-control" name="soluong" id="edit-soluong">
                            </div>
                            <div class="col-md-6">
                                <label for="edit-danhmuc_id" class="form-label">Danh mục sản phẩm</label>
                                <select class="form-control" name="danhmuc_id" id="edit-danhmuc_id">
                                    <?php
                                    $sql = "SELECT id, tendanhmuc FROM danhmuc"; 
                                    $result = $conn->query($sql);
                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row["id"] . "'>" . $row["tendanhmuc"] . "</option>";
                                        }
                                    } else {
                                        echo "<option value=''>Không có danh mục nào</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit-noidungsanpham" class="form-label">Nội dung sản phẩm</label>
                            <textarea class="form-control" name="noidungsanpham" id="edit-noidungsanpham"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit-trangthai" class="form-label">Trạng thái</label>
                            <select class="form-control" name="trangthai" id="edit-trangthai"
                                <?php echo ($trangthai_danhmuc == "Ẩn") ? "disabled" : ""; ?>>
                                <option value="Hiển thị">Hiển thị</option>
                                <option value="Ẩn">Đang ẩn</option>
                            </select>
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
 <!-- chỗ này xử lý cập nhật thông tin-->
 <script>
document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll(".edit-btn");
    editButtons.forEach(button => {
        button.addEventListener("click", function () {
            document.getElementById("edit-id").value = this.dataset.id;
            document.getElementById("edit-tensanpham").value = this.dataset.tensanpham;
            document.getElementById("current-img").src = this.dataset.img;
            document.getElementById("edit-gia_nhap").value = this.dataset.gia_nhap;
            document.getElementById("edit-gia").value = this.dataset.gia;
            document.getElementById("edit-noidungsanpham").value = this.dataset.noidungsanpham;
            document.getElementById("edit-trangthai").value = this.dataset.trangthai;
            document.getElementById("edit-soluong").value = this.dataset.soluong;

            // Cập nhật danh mục sản phẩm
            let selectElement = document.getElementById("edit-danhmuc_id");
            let selectedValue = this.dataset.danhmuc_id; 

            for (let i = 0; i < selectElement.options.length; i++) {
                if (parseInt(selectElement.options[i].value) === parseInt(selectedValue)) {
                    selectElement.options[i].selected = true;
                    break;
                }
            }
        });
    });
});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".delete-btn").forEach(button => {
                button.addEventListener("click", function () {
                    let productId = this.getAttribute("data-id");

                    Swal.fire({
                        title: "Bạn có chắc chắn?",
                        text: "Sản phẩm sẽ bị xóa khỏi hệ thống!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Xóa ngay",
                        cancelButtonText: "Hủy"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: "Đang xử lý...",
                                text: "Vui lòng chờ...",
                                icon: "info",
                                allowOutsideClick: false,
                                showConfirmButton: false,
                                timerProgressBar: true
                            });

                            fetch("xoasanpham.php", {
                                method: "POST",
                                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                                body: "id=" + productId
                            })
                            .then(response => response.text())
                            .then(data => {
                                Swal.fire({
                                    title: "Thành công!",
                                    text: data,
                                    icon: "success"
                                }).then(() => {
                                    location.reload(); // Reload lại trang sau khi xóa
                                });
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: "Lỗi!",
                                    text: "Không thể xóa sản phẩm. Vui lòng thử lại!",
                                    icon: "error"
                                });
                                console.error("Lỗi:", error);
                            });
                        }
                    });
                });
            });
        });
    </script>
    <!-- Thông báo thêm thành công-->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.getElementById("addProductForm").addEventListener("submit", function (e) {
        e.preventDefault(); // Ngăn form submit mặc định
        let formData = new FormData(this); // Lấy dữ liệu từ form
        Swal.fire({
            title: "Đang xử lý...",
            text: "Vui lòng chờ...",
            icon: "info",
            allowOutsideClick: false,
            showConfirmButton: false,
            timerProgressBar: true
        });
        fetch("themsanpham.php", {
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
                    document.getElementById("addProductForm").reset(); 
                    var modal = new bootstrap.Modal(document.getElementById('addProductModal'));
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
    <script>
document.getElementById("addCategoryForm").addEventListener("submit", function (e) {
    e.preventDefault(); // Ngăn form submit mặc định
    let formData = new FormData(this); // Lấy dữ liệu từ form

    Swal.fire({
        title: "Đang xử lý...",
        text: "Vui lòng chờ...",
        icon: "info",
        allowOutsideClick: false,
        showConfirmButton: false,
        timerProgressBar: true
    });

    fetch("themdanhmuc.php", {
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
                document.getElementById("addCategoryForm").reset(); 
                var modal = new bootstrap.Modal(document.getElementById('addCategoryModal'));
                modal.hide(); 
                location.reload(); // Reload lại trang để cập nhật danh sách danh mục
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
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Xử lý nút Chỉnh sửa
    const editCategoryButtons = document.querySelectorAll(".edit-category-btn");
    editCategoryButtons.forEach(button => {
        button.addEventListener("click", function () {
            document.getElementById("edit-category-id").value = this.dataset.id;
            document.getElementById("edit-tendanhmuc").value = this.dataset.tendanhmuc;
        });
    });
    // Xử lý nút Xóa
    document.querySelectorAll(".delete-category-btn").forEach(button => {
        button.addEventListener("click", function () {
            let categoryId = this.getAttribute("data-id");
            Swal.fire({
                title: "Bạn có chắc chắn?",
                text: "Danh mục sẽ bị xóa khỏi hệ thống!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Xóa ngay",
                cancelButtonText: "Hủy"
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: "Đang xử lý...",
                        text: "Vui lòng chờ...",
                        icon: "info",
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        timerProgressBar: true
                    });
                    fetch("xoadanhmuc.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: "id=" + categoryId
                    })
                    .then(response => response.text())
                    .then(data => {
                        Swal.fire({
                            title: "Thành công!",
                            text: data,
                            icon: "success"
                        }).then(() => {
                            location.reload();
                        });
                    })
                    .catch(error => {
                        Swal.fire({
                            title: "Lỗi!",
                            text: "Không thể xóa danh mục. Vui lòng thử lại!",
                            icon: "error"
                        });
                        console.error("Lỗi:", error);
                    });
                }
            });
        });
    });
    // Xử lý form Chỉnh sửa danh mục
    document.getElementById("editCategoryForm").addEventListener("submit", function (e) {
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
        fetch("update_danhmuc.php", {
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
                    var modal = new bootstrap.Modal(document.getElementById('editCategoryModal'));
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
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".toggle-category-btn").forEach(button => {
        button.addEventListener("click", function () {
            let categoryId = this.getAttribute("data-id");
            let currentTrangthai = this.getAttribute("data-trangthai");
            let newTrangthai = currentTrangthai == "Hiển thị" ? "Ẩn" : "Hiển thị";
            if (confirm("Bạn có chắc chắn muốn thay đổi trạng thái danh mục này?")) {
                fetch("anhien_danhmuc.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/x-www-form-urlencoded" },
                    body: "id=" + categoryId + "&trangthai=" + newTrangthai
                })
                .then(response => response.text())
                .then(data => {
                    alert(data); 
                    location.reload(); 
                })
                .catch(error => {
                    alert("Lỗi: Không thể thay đổi trạng thái!");
                    console.error("Lỗi:", error);
                });
            }
        });
    });
});
</script>
