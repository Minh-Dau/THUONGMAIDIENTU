<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DUMEMAY|VIETNAM</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<style>
    .avatar {
        width: 40px;
        height: 40px;
        border-radius: 10%;
        margin-right: 10px;
        object-fit: cover;
    }
    .i {
        color: black;
    }
    
    .no-products {
        width: 100%;
        text-align: center;
        font-size: 30px;
        font-weight: bold;
        color: #555;
    }
    .filter-buttons {
        margin: 20px 0;
        text-align: center;
    }
    .filter-buttons button {
        padding: 10px 20px;
        margin: 0 5px;
        border: none;
        border-radius: 5px;
        background-color: #f0f0f0;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }
    .filter-buttons button:hover {
        background-color: #ddd;
    }
    .filter-buttons button.active {
        background-color: #007bff;
        color: white;
    }
    </style>
<body>
    <?php
        include 'header.php';
        include("config.php");
    ?>
        <div class="thumb_shop">
            <img src="IMG/testimg.jpg" alt="">
        </div>
        <div class="filter-buttons">
            <button class="filter-btn" data-category-id="all">Tất cả</button>
            <?php
            include("config.php");
            $sql_danhmuc = "SELECT * FROM danhmuc";
            $result_danhmuc = mysqli_query($conn, $sql_danhmuc);

            while ($row = mysqli_fetch_assoc($result_danhmuc)) {
                echo '<button class="filter-btn" data-category-id="'.$row['id'].'">'.$row['tendanhmuc'].'</button>';
            }
            ?>
        </div>
        <!-- code tìm kiếm-->
        <div class="wrapper">
            <div class="product" id="product-list">
                <?php
                include("config.php");

                $search = isset($_GET['search']) ? trim($_GET['search']) : "";
                $danhmuc_id = isset($_GET['danhmuc_id']) ? $_GET['danhmuc_id'] : 'all';
                $sql = "SELECT * FROM sanpham WHERE trangthai = 'Hiển Thị'";
                $params = [];
                $types = "";
                if (!empty($search)) {
                    $sql .= " AND ((tensanpham LIKE ? OR SOUNDEX(tensanpham) = SOUNDEX(?)) 
                            OR danhmuc_id IN 
                                (SELECT id FROM danhmuc WHERE tendanhmuc LIKE ? OR SOUNDEX(tendanhmuc) = SOUNDEX(?)))";
                    $params[] = "%$search%";
                    $params[] = $search;
                    $params[] = "%$search%";
                    $params[] = $search;
                    $types .= "ssss";
                }
                if ($danhmuc_id !== 'all') {
                    $sql .= " AND danhmuc_id = ?";
                    $params[] = $danhmuc_id;
                    $types .= "i";
                }
                $stmt = $conn->prepare($sql);
                if (!empty($params)) {
                    $stmt->bind_param($types, ...$params);
                }
                $stmt->execute();
                $result = $stmt->get_result();
                ?>
                <div class="wrapper">
                    <div class="product">
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                        ?>
                                <div class="product_item">
                                    <div class="product_top">
                                        <a href="chitietsanpham.php?id=<?= $row['id'] ?>" class="product_thumb">
                                            <img src="<?= $row['img'] ?>" alt="" width="250" height="250">
                                        </a>
                                        <a href="chitietsanpham.php?id=<?= $row['id'] ?>" class="buy_now">Mua ngay</a>
                                    </div>
                                    <div class="product_info">
                                        <a href="chitietsanpham.php?id=<?= $row['id'] ?>" class="product_cat"><?= htmlspecialchars($row['tensanpham']) ?></a>
                                        <div class="product_price"><?= number_format($row['gia'], 0, ',', '.') ?>$</div>
                                    </div>
                                </div>
                        <?php 
                            }
                        } else {
                            echo '<p style="text-align: center; color: red;">Không tìm thấy sản phẩm nào.</p>';
                        }

                        $stmt->close();
                        $conn->close();
                        ?>
                    </div>
                </div>
            </div>
        </div>
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
</html>
<script>
const carousel = document.querySelector(".carousel");
const images = document.querySelectorAll(".carousel img");
const totalImages = images.length;
let currentIndex = 0;

function getImageWidth() {
    return images[0].clientWidth;
}

function setPositionByIndex() {
    carousel.style.transition = "transform 0.5s ease-in-out";
    carousel.style.transform = `translateX(${-currentIndex * getImageWidth()}px)`;
}

function autoSlide() {
    currentIndex++;
    if (currentIndex >= totalImages) {
        currentIndex = 0;
    }
    setPositionByIndex();
}

window.addEventListener("resize", setPositionByIndex);
setInterval(autoSlide, 5000);
document.querySelectorAll('.filter-btn').forEach(button => {
    button.addEventListener('click', function () {
        document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
        this.classList.add('active');
        const danhmucId = this.getAttribute('data-category-id');
        window.location.href = `?danhmuc_id=${danhmucId}`;
    });
});

const urlParams = new URLSearchParams(window.location.search);
const danhmucIdParam = urlParams.get('danhmuc_id') || 'all';
document.querySelectorAll('.filter-btn').forEach(button => {
    if (button.getAttribute('data-category-id') === danhmucIdParam) {
        button.classList.add('active');
    }
});
</script>