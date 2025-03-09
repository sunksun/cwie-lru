<?php
session_start();
include_once('admin/connect.php');

// เพิ่มการโหลด Composer autoloader
require 'vendor/autoload.php';

// เพิ่ม namespace ของ PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// ตรวจสอบว่ามีการส่งโทเค็นมาหรือไม่
if (!isset($_GET['token']) || empty($_GET['token'])) {
    die("ไม่พบโทเค็นการอนุมัติ");
}

$token = $_GET['token'];

// ตรวจสอบว่าโทเค็นมีอยู่ในฐานข้อมูลหรือไม่
$token_sql = "SELECT * FROM approval_tokens WHERE token = ?";
$token_stmt = $conn->prepare($token_sql);
$token_stmt->bind_param('s', $token);
$token_stmt->execute();
$token_result = $token_stmt->get_result();

if ($token_result->num_rows === 0) {
    die("โทเค็นไม่ถูกต้องหรือหมดอายุแล้ว");
}

$token_data = $token_result->fetch_assoc();
$user_id = $token_data['user_id'];

// ดึงข้อมูลผู้ใช้
$user_sql = "SELECT * FROM tblusers WHERE id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param('i', $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result->num_rows === 0) {
    die("ไม่พบข้อมูลผู้ใช้");
}

$user_data = $user_result->fetch_assoc();

// อัปเดตสถานะผู้ใช้เป็น 'approved'
$update_sql = "UPDATE tblusers SET status = 'approved' WHERE id = ?";
$update_stmt = $conn->prepare($update_sql);
$update_stmt->bind_param('i', $user_id);
$update_stmt->execute();

// ลบโทเค็นหลังจากใช้งานแล้ว
$delete_token_sql = "DELETE FROM approval_tokens WHERE id = ?";
$delete_token_stmt = $conn->prepare($delete_token_sql);
$delete_token_stmt->bind_param('i', $token_data['id']);
$delete_token_stmt->execute();

// สร้างเนื้อหาข้อความอีเมล์สำหรับแจ้งผู้ใช้ว่าบัญชีได้รับการอนุมัติแล้ว
$message_body = "
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .button { background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; }
    </style>
</head>
<body>
    <div class='container'>
        <h2>บัญชีของคุณได้รับการอนุมัติแล้ว</h2>
        <p>เรียน คุณ{$user_data['fullname']},</p>
        <p>บัญชีของคุณได้รับการอนุมัติเรียบร้อยแล้ว คุณสามารถเข้าสู่ระบบได้ด้วยชื่อผู้ใช้และรหัสผ่านที่คุณกำหนดไว้</p>
        <p><a href='http://{$_SERVER['HTTP_HOST']}/admin/login.php' class='button'>เข้าสู่ระบบ</a></p>
        <p>ด้วยความเคารพ,<br>ทีมงานระบบสหกิจศึกษา มหาวิทยาลัยราชภัฏเลย</p>
    </div>
</body>
</html>
";

// ส่งอีเมล์ด้วย PHPMailer
try {
    // ตั้งค่า PHPMailer
    $mail = new PHPMailer(true);

    // Server settings
    $mail->SMTPDebug = SMTP::DEBUG_OFF; // เปลี่ยนเป็น SMTP::DEBUG_SERVER เพื่อดูข้อมูลการดีบัก
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com'; // เปลี่ยนเป็น SMTP server ของคุณ
    $mail->SMTPAuth   = true;
    $mail->Username   = 'sunksunlapunt@gmail.com'; // เปลี่ยนเป็นอีเมล์ของคุณ
    $mail->Password   = 'yjlojkmbmjwvhpqs'; // เปลี่ยนเป็นรหัสผ่านหรือ App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8'; // สำคัญสำหรับภาษาไทย

    // Recipients
    $mail->setFrom('sunksunlapunt@gmail.com', 'ระบบสหกิจศึกษา');
    $mail->addAddress($user_data['useremail'], $user_data['fullname']);

    // Content
    $mail->isHTML(true);
    $mail->Subject = "บัญชีของคุณได้รับการอนุมัติแล้ว - ระบบสหกิจศึกษา";
    $mail->Body = $message_body;

    $mail->send();
    $email_status = "ส่งอีเมล์แจ้งเตือนเรียบร้อยแล้ว";
} catch (Exception $e) {
    $email_status = "ไม่สามารถส่งอีเมล์ได้: " . $mail->ErrorInfo;
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>อนุมัติผู้ใช้งาน - ระบบสหกิจศึกษา</title>

    <!-- Stylesheets -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">
    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
</head>

<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body text-center">
                <img src="images/lru.png" alt="Logo" width="100" class="mb-3">
                <h2 class="card-title">อนุมัติผู้ใช้งานสำเร็จ</h2>
                <p class="card-text">บัญชีของ <strong><?php echo $user_data['fullname']; ?></strong> ได้รับการอนุมัติเรียบร้อยแล้ว</p>
                <p>อีเมล์แจ้งเตือนการอนุมัติได้ถูกส่งไปยัง <?php echo $user_data['useremail']; ?></p>
                <p><small class="text-muted"><?php echo $email_status; ?></small></p>
                <a href="admin/login.php" class="btn btn-primary mt-3">กลับไปยังหน้าเข้าสู่ระบบ</a>
            </div>
        </div>
    </div>

    <!-- JavaScript Files -->
    <script src="js/jquery.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>