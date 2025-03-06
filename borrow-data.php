<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $book_title = $_POST['book_title'];
    $borrow_date = $_POST['borrow_date'];
    $return_date = $_POST['return_date'];

    // ตรวจสอบข้อมูลที่ได้รับ
    if (!empty($username) && !empty($book_title) && !empty($borrow_date) && !empty($return_date)) {
        // ตรวจสอบว่าสถานะหนังสือยังว่างหรือไม่
        $check_sql = "SELECT status FROM books WHERE title = ?";
        if ($stmt = $conn->prepare($check_sql)) {
            $stmt->bind_param("s", $book_title);
            $stmt->execute();
            $stmt->bind_result($status);
            $stmt->fetch();
            $stmt->close();
        }

        if ($status === 'available') {
            // บันทึกการยืมลงฐานข้อมูล
            $sql = "INSERT INTO borrow_records (username, book_title, borrow_date, return_date, status) VALUES (?, ?, ?, ?, 'borrowed')";
            if ($stmt = $conn->prepare($sql)) {
                $stmt->bind_param("ssss", $username, $book_title, $borrow_date, $return_date);
                if ($stmt->execute()) {
                    // อัปเดตสถานะหนังสือเป็น 'borrowed'
                    $update_sql = "UPDATE books SET status = 'borrowed' WHERE title = ?";
                    if ($update_stmt = $conn->prepare($update_sql)) {
                        $update_stmt->bind_param("s", $book_title);
                        $update_stmt->execute();
                        $update_stmt->close();
                    }
                    echo "<script>alert('บันทึกข้อมูลการยืมหนังสือสำเร็จ!'); window.location.href='index.php';</script>";
                } else {
                    echo "<script>alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล'); window.location.href='borrow_form.php';</script>";
                }
                $stmt->close();
            }
        } else {
            echo "<script>alert('หนังสือเล่มนี้ถูกยืมไปแล้ว'); window.location.href='borrow_form.php';</script>";
        }
    } else {
        echo "<script>alert('กรุณากรอกข้อมูลให้ครบทุกช่อง!'); window.location.href='borrow_form.php';</script>";
    }
}
$conn->close();
?>