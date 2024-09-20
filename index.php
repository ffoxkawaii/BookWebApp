<?php
// Kết nối đến cơ sở dữ liệu
$servername = "db";
$username = "user";
$password = "user_password";
$dbname = "WebDB";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
    die("Kết nối thất bại: " . $conn->connect_error);
}

// Lấy danh sách sách từ cơ sở dữ liệu
$sql = "SELECT * FROM books";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Web Bán Sách</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        header {
            background-color: #4CAF50;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            margin-bottom: 20px;
        }
        .book-list {
            display: flex;
            flex-wrap: wrap;
        }
        .book-item {
            flex: 1 1 30%;
            margin: 15px;
            padding: 15px;
            background-color: #f9f9f9;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            text-align: center;
            cursor: pointer;
        }
        img {
            width: 150px;
            height: 150px;
            object-fit: cover;
        }
        .book-title {
            font-size: 18px;
            margin: 10px 0;
        }
        .book-price {
            color: #ff5722;
            font-size: 16px;
            margin-bottom: 15px;
        }
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 300px;
            padding: 20px;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }
        .popup h3 {
            margin: 0;
            margin-bottom: 10px;
        }
        .popup p {
            margin: 10px 0;
        }
        .popup button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            display: block;
            margin-top: 10px;
            width: 100%;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 500;
        }
        .image-gallery {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .image-gallery img {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>

    <header>
        <h1>Cửa Hàng Bán Sách</h1>
    </header>

    <div class="container">
        <h1>Sách Mới Nhất</h1>
        <div class="book-list">
            <?php
            if ($result->num_rows > 0) {
                // Hiển thị danh sách sách
                while($row = $result->fetch_assoc()) {
                    echo '<div class="book-item" onclick="showPopup(' . $row["id"] . ')">';
                    echo '<img src="https://via.placeholder.com/150" alt="' . $row["title"] . '">';
                    echo '<h3 class="book-title">' . $row["title"] . '</h3>';
                    echo '<p class="book-price">' . number_format($row["price"], 2) . ' VND</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>Không có sách nào trong cơ sở dữ liệu.</p>';
            }
            ?>
        </div>
    </div>

    <!-- Popup -->
    <div class="overlay" id="overlay"></div>
    <div class="popup" id="popup">
        <h3 id="popup-title"></h3>
        <p id="popup-content"></p>
        <p id="popup-author"></p>
        <p id="popup-origin"></p>
        <p id="popup-genre"></p>
        <div class="image-gallery" id="image-gallery"></div>
        <button onclick="closePopup()">Đóng</button>
    </div>

    <script>
        function showPopup(bookId) {
            // Gọi API hoặc thực hiện truy vấn AJAX để lấy thông tin sách theo ID
            fetch(`get_book_details.php?id=${bookId}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('popup-title').innerText = data.title;
                    document.getElementById('popup-content').innerText = data.description;
                    document.getElementById('popup-author').innerText = 'Tác giả: ' + data.author;
                    document.getElementById('popup-origin').innerText = 'Xuất xứ: ' + data.origin;
                    document.getElementById('popup-genre').innerText = 'Thể loại: ' + data.genre;

                    // Hiển thị hình ảnh
                    const imageGallery = document.getElementById('image-gallery');
                    imageGallery.innerHTML = ''; // Xóa nội dung cũ
                    data.images.forEach(image => {
                        const imgElement = document.createElement('img');
                        imgElement.src = image;
                        imageGallery.appendChild(imgElement);
                    });

                    document.getElementById('popup').style.display = 'block';
                    document.getElementById('overlay').style.display = 'block';
                })
                .catch(error => console.error('Lỗi:', error));
        }

        function closePopup() {
            document.getElementById('popup').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }
    </script>

</body>
</html>

<?php
$conn->close();
?>