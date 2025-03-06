<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แบบฟอร์มยืมหนังสือ</title>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Sarabun', sans-serif;
            margin: 0;
            background: url('พื้นหลัง.png') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }
        .main-container {
            background: rgba(255, 255, 255, 0.8);
            margin: 50px auto;
            padding: 40px;
            width: 50%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            backdrop-filter: blur(10px);
        }
        .header {
            background: rgba(179, 58, 58, 0.9);
            color: white;
            padding: 15px;
            font-size: 1.8em;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        label {
            display: block;
            margin-top: 10px;
            font-size: 1.1em;
            text-align: left;
        }
        input, select {
            width: 100%;
            padding: 12px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1.1em;
            outline: none;
        }
        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            background: rgba(204, 85, 85, 0.9);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1.2em;
            cursor: pointer;
            transition: 0.3s;
        }
        button:hover {
            background: rgba(179, 58, 58, 0.9);
        }
    </style>
</head>
<body>
    <div class="main-container">
        <div class="header">แบบฟอร์มยืมหนังสือ</div>
        <form action="borrow-data.php" method="POST">
            <label>ชื่อผู้ใช้:</label>
            <input type="text" name="username" required placeholder="กรอกชื่อของคุณ" style="width: 100%; height: 45px;">
            
            <label>ชื่อหนังสือ:</label>
            <select name="book_title" required style="width: 100%; height: 45px;">
                <option value="">เลือกหนังสือ</option>
                <?php
                include 'database.php';
                $sql = "SELECT title FROM books WHERE status = 'available'";
                $result = $conn->query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row['title'] . "'>" . $row['title'] . "</option>";
                }
                $conn->close();
                ?>
            </select>
            
            <label>วันที่ยืม:</label>
            <input type="date" name="borrow_date" required style="width: 100%; height: 45px;">
            
            <label>วันที่คืน:</label>
            <input type="date" name="return_date" required style="width: 100%; height: 45px;">
            
            <button type="submit">ยืมหนังสือ</button>
        </form>
    </div>
</body>
</html>
