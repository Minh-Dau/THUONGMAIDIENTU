<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DUMEMAY|VIETNAM</title>
    <link rel="stylesheet" href="css_chitiet.css">
    <link rel="stylesheet" href="style.js">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <!-- Phần trên -->
    <header class="main">
        <a href="trangchinh.php"><img class="imgLogo" src="IMG/logo2.png" alt="logo" id="reset"></a>
        <nav>
            <div class="danhsach">
                <ul>
                    <li class="thea"><a href="trangchinh.php"><b>TRANG CHỦ</b></a></li>
                    <li class="thea"><a href="shop.php"><b>SHOP</b></a></li>                   
                    <li class="thea"><a href="contact.php"><b>CONTACT</b></a></li>
                    <li class="thea"><a href="about.php"><b>ABOUT</b></a></li>
                <ul>
            </div>
        </nav>
        <div>
            <div class="shopping" href="#"><a href="#" style="color: black;"><i class="bi bi-cart2"></i></a></div>
            <a class="login" href="dangnhap.php"><button id="bt">LOGIN</button></a>
        </div>
        <script>
            function validateQuantity() {
    var inputElement = document.getElementById('so');
    var inputValue = inputElement.value.trim(); // Trim to remove leading and trailing spaces
    // Use a regular expression to allow only numeric values
    var numericRegex = /^[0-9]+$/;
    if (!numericRegex.test(inputValue)) {
        // If the input is not numeric, set the value to 1
        inputElement.value = 1;
    }
}
function giam() {
    var inputElement = document.getElementById('so');
    var currentValue = parseInt(inputElement.value, 10);

    if (currentValue > 1) {
        inputElement.value = currentValue - 1;
    }
}
function tang() {
    var inputElement = document.getElementById('so');
    var currentValue = parseInt(inputElement.value, 10);

    inputElement.value = currentValue + 1;
}
function nhan() {
    var selectedSize = document.querySelector('input[name="group"]:checked');

    if (selectedSize) {
        var kichThuocElement = document.getElementById('kichthuoc');
        kichThuocElement.innerHTML = '<b style="font-size: 20px">Kích thước: </b>' + selectedSize.value;
    }
}
function addToCart(){
    
}
        </script>
    </header>
    <!-- Phần giữa -->
    <div class=" mainsanpham">
       <hr style="font-size: 100px; height: 30px; background-color: rgb(117, 117, 117);">
       <div></div>
       <table class="sanpham">
            <tr>
                <td> <img src="IMG/sp1.jpg" alt="" class="anh" onclick="zoom(this)"></td>
                <td >
                    <di class="thongtin">
                    <h2>Tên Sản Phẩm</h2>
                    <br>
                    <br>
                    <p id="gia">1.000.000VNĐ</p>
                    
                    &nbsp;
                    <p id="kichthuoc">Kích thước:</p>
                    <br>
                    <div class="kichthuoc-options">
                        <input type="radio" value="S" class="kichthuoc" name="group" id="sizeS" onclick="nhan()">
                        <label for="sizeS">S</label>
                        <input type="radio" value="M" class="kichthuoc" name="group" id="sizeM" onclick="nhan()">
                        <label for="sizeM">M</label>
                        <input type="radio" value="L" class="kichthuoc" name="group" id="sizeL" onclick="nhan()">
                        <label for="sizeL">L</label>
                        <input type="radio" value="XL" class="kichthuoc" name="group" id="sizeXL" onclick="nhan()">
                        <label for="sizeXL">XL</label>
                    </div>
                    <br>
                    <p id="mausac"> Số lượng</p>
                    <div id="mainsoluongmua">
                    <div id="soluong">
                        <button class="tru" onclick="giam()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12h-15" />
                            </svg>
                        </button>
                        <input type="text" id="so" value="1" >
                        <button class="cong" onclick="tang()">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </button>
                    </div>
                   
                </div>
                    <div class="bton">
                        <button class="them">Thêm vào giỏ hàng</button>
                        <button class="mua">Mua</button>
                    </div>
                    <div class="sdt">
                        <span> Gọi đặt mua <span>0987654321</span> (8:00-22:00)</span>    
                    </div>
                        <hr>
                </div>
               
                </td>
                
            </tr>
       </table>
       <div class=" mota">
            <u>Mô tả sản phâm </u>
            <hr>
       </div>
       <div class="motasanpham">
        <p>
            Thông tin sản phẩm: <br>
            - Chất liệu: Cotton 100%  đảm bảo áo thun mềm mại, thoáng khí và dễ chịu cho cơ thể. Với khả năng co giãn tốt, áo thun này sẽ mang lại sự thoải mái và linh hoạt trong mọi hoạt động của bạn. <br>
            -Form:Oversize <br>
            -Màu sắc: Đen <br>
            -Thiết kế:........ <br>
            &nbsp;
            <br>
            Đối với những ai đang tìm kiếm một chiếc áo thun vừa thoải mái, vừa cá tính và độc đáo, áo thun chất liệu cotton 100% cao cấp 2 chiều với thiết kế cách điệu chữ HYBID và yếu tố lửa sẽ là một sự lựa chọn tuyệt vời. Đặc biệt hơn, chữ HYBID cách điệu theo hoạ tiết ngọn lửa sẽ được thêu tỉ mỉ tạo nên điểm nhấn cho chiếc áo. <br>
            <br>
            HYBID® là một thương hiệu thời trang tiền thân của Lâm Vlog shop, được thiết kế đặc biệt dành cho giới trẻ. Với phong cách tối giản và hiện đại, HYBID mang đến những sản phẩm chất lượng tốt và đa dạng để khách hàng có thể tự tin thể hiện cái tôi riêng của mình thông qua trang phục. <br>
            <br>
            Với việc kết hợp giữa sự đơn giản và sự tinh tế, HYBID® tạo ra những thiết kế thời trang trẻ trung, năng động và thời thượng. Từ áo phông, áo sơ mi, áo hoodie, áo khoác dù đến phụ kiện như nón, dép và túi,.. HYBID® luôn đảm bảo sự thoải mái và phong cách độc đáo cho khách hàng của mình. <br>
            <br>
            Sự chú trọng đến chi tiết, chất liệu cao cấp và quy trình sản xuất chất lượng là những yếu tố mà HYBID® không bao giờ bỏ qua. Với mục tiêu mang đến những sản phẩm dễ mặc, dễ phối và dễ bảo quản. <br>
            <br>
            Với HYBID®, bạn không chỉ mặc một chiếc áo hay một chiếc quần, mà bạn đang thể hiện phong cách đơn giản nhưng lại có sức hút với mọi người xung quanh. Hãy khám phá thế giới thú vị của HYBID và trở thành một phần trong gia đình team Lâm Vlog tụi mình nhé. <br>
            <br>


        </p>
       </div>
    </div>  
    <!-- Phần dưới -->
    <div class="support">
        <p>Hỗ trợ - Mua hàng: <span style="color: rgb(201, 0, 0);"><b>0122112211</b></span></p>
    </div>
    <footer class="footer">
        <div class="container" style="font-size: 20px;">           
            <div class="row">
                <div class="footer-col">
                    <h4>HỆ THỐNG CỬA HÀNG</h4>
                    <p><i class="bi bi-geo-alt"></i> &nbsp;Chi Nhánh Trường Đại Học Sư Phạm Kỹ Thuật Vĩnh Long</p>
                    <p><i class="bi bi-geo-alt"></i> &nbsp;Đường Nguyễn Huệ, Phường 2, Thành Phố Vĩnh Long</p>
                    <p><i class="bi bi-telephone-fill"></i> &nbsp;Hotline: 0122112211 - 0122886688</p>
                    <p><i class="bi bi-envelope-fill"></i> &nbsp;dumemayteam@gmail.com</p>
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
                    <p><i class="bi bi-facebook"></i>&nbsp; DUMEMAY</p>
                    <P><i class="bi bi-instagram"></i>&nbsp; DUMEMAY</P>
                </div>
                <div class="footer-col">
                    <h4>Fanpage</h4>
                </div>
            </div>
        </div>
    </footer>
    <div>
        <hr>
        <p style="font-size: 17px;" align="center" class="banquyen">Copyright © 2023 DUMEMAY STUDIO</p>
    </div>
</body>
</html>