<?php
include 'database.php';

$sql = "SELECT username, book_title, borrow_date, return_date, status FROM borrow_records ORDER BY borrow_date DESC";
$result = $conn->query($sql);
$history = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $history[] = $row;
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ประวัติการยืมหนังสือ</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            margin: 0;
            background: url('พื้นหลัง.png') no-repeat center center fixed;
            background-size: cover;
            color: #333;
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
        .main-container {
            background: rgba(255, 255, 255, 0.8);
            margin-left: 280px;
            padding: 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            width: calc(100% - 280px);
            box-sizing: border-box;
            border-radius: 10px;
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
            border-radius: 10px;
        }
        .header img {
            height: 80px;
            max-width: 120px;
            margin-right: 20px;
        }
        .navbar {
            background: rgba(204, 85, 85, 0.9);
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: center;
            gap: 20px;
            width: 100%;
            border-radius: 10px;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            font-size: 1.2em;
        }
        .table-container {
            width: 90%;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        th {
            background: rgba(179, 58, 58, 0.9);
            color: white;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h3>เมนูหลัก</h3>
        <ul>
            <li><a href="index.php" style="color:white; text-decoration:none;">หน้าหลัก</a></li>
        </ul>
    </div>
    <div class="main-container">
        <div class="header">
            <img src="รูปภาพ1.png" alt="โลโก้วิทยาลัย" onerror="this.style.display='none'">
            <h2>ประวัติการยืมหนังสือ</h2>
        </div>
        <div class="navbar">
            <a href="index.php">หน้าหลัก</a>
        </div>
        <div class="table-container">
            <h3>รายการประวัติการยืมหนังสือ</h3>
            <table>
                <tr>
                    <th>ชื่อผู้ใช้</th>
                    <th>ชื่อหนังสือ</th>
                    <th>วันที่ยืม</th>
                    <th>วันที่คืน</th>
                    <th>สถานะ</th>
                </tr>
                <?php foreach ($history as $record): ?>
                <tr>
                    <td><?= htmlspecialchars($record['username']) ?></td>
                    <td><?= htmlspecialchars($record['book_title']) ?></td>
                    <td><?= htmlspecialchars($record['borrow_date']) ?></td>
                    <td><?= htmlspecialchars($record['return_date']) ?></td>
                    <td style="color: <?= $record['status'] === 'borrowed' ? 'red' : 'green' ?>;">
                        <?= $record['status'] === 'borrowed' ? 'ถูกยืม' : 'คืนแล้ว' ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
</body>
</html>