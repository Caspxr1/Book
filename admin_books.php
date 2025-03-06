<?php
include 'database.php';

// ฟังก์ชันลบหนังสือ
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM books WHERE id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $delete_id);
        $stmt->execute();
        $stmt->close();
    }
    header("Location: admin_books.php");
    exit();
}

// เพิ่มหรืออัปเดตหนังสือ
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $image = $_FILES['image']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES['image']['name']);
    
    if (!empty($title) && !empty($author) && !empty($publisher)) {
        if (!empty($image)) {
            move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
        } else {
            $target_file = $_POST['existing_image'];
        }

        if (isset($_POST['book_id']) && !empty($_POST['book_id'])) {
            $book_id = $_POST['book_id'];
            $sql = "UPDATE books SET title = ?, author = ?, publisher = ?, image_url = ? WHERE id = ?";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ssssi", $title, $author, $publisher, $target_file, $book_id);
                $stmt->execute();
                $stmt->close();
            }
        } else {
            $sql = "INSERT INTO books (title, author, publisher, image_url, status) VALUES (?, ?, ?, ?, 'available')";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ssss", $title, $author, $publisher, $target_file);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
    header("Location: admin_books.php");
    exit();
}

// ดึงข้อมูลหนังสือ
$sql = "SELECT * FROM books";
$result = $conn->query($sql);
$books = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการหนังสือ</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        .form-container, .table-container {
            background: rgba(255, 255, 255, 0.95);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-top: 10px;
            font-weight: bold;
        }
        input, select, button {
            width: calc(100% - 20px);
            padding: 12px;
            margin-top: 8px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 1em;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        button {
            background: #b33a3a;
            color: white;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: #922d2d;
        }
        .table-container table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table-container th, .table-container td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .table-container th {
            background: #b33a3a;
            color: white;
            text-align: center;
        }
        .edit-btn, .delete-btn {
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            color: white;
            cursor: pointer;
            font-size: 0.9em;
            display: inline-block;
            margin-right: 5px;
        }
        .edit-btn {
            background: #ff9800;
        }
        .edit-btn:hover {
            background: #e68900;
        }
        .delete-btn {
            background: red;
        }
        .delete-btn:hover {
            background: darkred;
        }
        .sidebar {
            width: 250px;
            background: rgba(216, 108, 108, 0.9);
            color: white;
            height: 100vh;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            box-sizing: border-box;
            border-radius: 10px;
        }
        .sidebar h3 {
            text-align: center;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 12px;
            cursor: pointer;
            border-radius: 5px;
            margin-bottom: 5px;
            text-align: center;
            background: rgba(224, 123, 123, 0.9);
            transition: 0.3s;
        }
        .sidebar ul li:hover {
            background: rgba(255, 153, 153, 0.9);
        }
        .header {
            background: rgba(179, 58, 58, 0.9);
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            font-size: 1.8em;
            width: 100%;
            box-sizing: border-box;
            border-radius: 10px;
        }
        .header img {
            height: 80px;
            max-width: 120px;
            margin-right: 20px;
        }
    </style>
</head>
<body>
<div class="sidebar">
        <h3>เมนูหลัก</h3>
        <ul>
            <li href="admin_books.php">จัดการหนังสือ</li>
        </ul>
    </div>
    
    <div class="container" style="margin-left: 270px; padding: 20px; width: calc(100% - 270px);">
    <div class="header">
            <img src="รูปภาพ1.png" alt="โลโก้วิทยาลัย" onerror="this.style.display='none'">
            <h2>ห้องสมุดวิทยาลัย</h2>
        </div>
        <div class="form-container">
            <h3>เพิ่มหรือแก้ไขหนังสือ</h3>
            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="book_id" id="book_id">
                <label>ชื่อหนังสือ:</label>
                <input type="text" name="title" id="title" required>
                <label>ผู้แต่ง:</label>
                <input type="text" name="author" id="author" required>
                <label>สำนักพิมพ์:</label>
                <input type="text" name="publisher" id="publisher" required>
                <label>อัปโหลดรูปภาพ:</label>
                <input type="file" name="image">
                <input type="hidden" name="existing_image" id="existing_image">
                <button type="submit">บันทึก</button>
            </form>
        </div>
        <div class="table-container">
            <h3>รายการหนังสือ</h3>
            <table>
                <tr>
                    <th>รหัส</th>
                    <th>ชื่อหนังสือ</th>
                    <th>ผู้แต่ง</th>
                    <th>สำนักพิมพ์</th>
                    <th>สถานะ</th>
                    <th>แก้ไข</th>
                    <th>ลบ</th>
                </tr>
                <?php foreach ($books as $book): ?>
                <tr>
                    <td><?= $book['id'] ?></td>
                    <td><?= $book['title'] ?></td>
                    <td><?= $book['author'] ?></td>
                    <td><?= $book['publisher'] ?></td>
                    <td><?= $book['status'] === 'available' ? 'พร้อมให้ยืม' : 'ถูกยืม' ?></td>
                    <td><button class="edit-btn">แก้ไข</button></td>
                    <td><button class="delete-btn">ลบ</button></td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>
