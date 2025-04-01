<!DOCTYPE html>
<html lang="en">
<head>
<style>
.filter-buttons {
    margin: 20px 0;
    text-align: center;
    display: flex;
    justify-content: center;
    flex-wrap: wrap;
    gap: 10px;
}

.filter-buttons button {
    padding: 10px 15px;
    font-size: 16px;
    border: 2px solid transparent;
    border-radius: 8px;
    background-color: #f8f9fa;
    color: #333;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
    box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
}

.filter-buttons button:hover {
    background-color: #007bff;
    color: white;
    border-color: #0056b3;
    transform: scale(1.05);
}

.filter-buttons button.active {
    background-color: #007bff;
    color: white;
    border-color: #0056b3;
    font-weight: bold;
}
</style>
</head>
<body>
<?php
include 'header.php';
include("config.php");
?>

<div class="img_main">
    <div class="carousel">
        <img src="IMG/testimg1.jpg" alt="Ảnh 1">
        <img src="IMG/testimg2.jpg" alt="Ảnh 2">
        <img src="IMG/testimg3.jpg" alt="Ảnh 3">
    </div>
</div>

<div class="headline">
    <h3>SẢN PHẨM MỚI NHẤT</h3>
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
<div class="wrapper">
    <div class="product" id="product-list">
        <?php
            include("config.php");
            $danhmuc_id = isset($_GET['danhmuc_id']) ? $_GET['danhmuc_id'] : 'all';
            if ($danhmuc_id === 'all') {
                $sql = "SELECT * FROM sanpham WHERE trangthai = 'Hiển Thị' LIMIT 4";
                $result = mysqli_query($conn, $sql);
            } else {
                $sql = "SELECT * FROM sanpham WHERE trangthai = 'Hiển Thị' AND danhmuc_id = ? LIMIT 4";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("i", $danhmuc_id);
                $stmt->execute();
                $result = $stmt->get_result();
            }

            if ($result->num_rows > 0) {
                while ($row = mysqli_fetch_array($result)) {
            ?>
                    <div class="product_item">
                        <div class="product_top">
                            <a href="chitietsanpham.php?id=<?= $row['id'] ?>" class="product_thumb">
                                <img src="<?php echo $row['img'] ?>" alt="" width="250" height="250">
                            </a>
                            <a href="chitietsanpham.php?id=<?= $row['id'] ?>" class="buy_now">Mua ngay</a>
                        </div>
                        <div class="product_info">
                            <a href="chitietsanpham.php?id=<?= $row['id'] ?>" class="product_cat"><?php echo $row['tensanpham'] ?></a>
                            <div class="product_price"><?php echo $row['gia'] ?>$</div>
                        </div>
                    </div>
            <?php
                }
            } else {
                echo '<p style="text-align: center; color: red;">Không có sản phẩm nào thuộc danh mục này.</p>';
            }

            if ($danhmuc_id !== 'all') {
                $stmt->close();
            }
            $conn->close();
        ?>
    </div>
</div>
<div class="product_all">
    <a href="shop.php">Xem tất cả</a>
</div>

<?php include 'footer.php' ?>

<div>
    <hr>
    <p style="font-size: 17px;" align="center" class="banquyen">Copyright © 2025 Hustler Stonie</p>
</div>

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
</body>
</html>