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

// Lấy ID sách từ yêu cầu
$bookId = intval($_GET['id']);

// Truy vấn thông tin sách
$sql = "SELECT * FROM books WHERE id = $bookId";
$bookResult = $conn->query($sql);
$book = $bookResult->fetch_assoc();

// Truy vấn hình ảnh sáchz
$imageSql = "SELECT image_url FROM book_images WHERE book_id = $bookId";
$imageResult = $conn->query($imageSql);

$images = [];
while ($imageRow = $imageResult->fetch_assoc()) {
    $images[] = $imageRow['image_url'];
}

// Đóng kết nối
$conn->close();

// Trả về dữ liệu dưới dạng JSON
echo json_encode([
    'title' => $book['title'],
    'description' => $book['description'],
    'author' => $book['author'],
    'origin' => $book['origin'],
    'genre' => $book['genre'],
    'images' => $images
]);