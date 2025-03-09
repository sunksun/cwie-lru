<?php
session_start();
// เพิ่ม error reporting เพื่อดูข้อผิดพลาด (อาจลบออกเมื่อใช้งานจริง)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// เพิ่มการโหลด Composer autoloader
require 'vendor/autoload.php';

include('admin/connect.php');

// เพิ่ม namespace ของ PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// กำหนดตัวแปรสำหรับข้อความแจ้งเตือน
$message = '';
$messageClass = '';

// เมื่อมีการส่งฟอร์ม
if (isset($_POST['submit'])) {
    // รับค่าอีเมล์จากฟอร์ม
    $email = $_POST['email'];

    // ตรวจสอบว่าอีเมล์ถูกกรอกหรือไม่
    if (empty($email)) {
        $message = "กรุณากรอกอีเมล์";
        $messageClass = "alert-danger";
    } else {
        try {
            // เตรียมคำสั่ง SQL สำหรับค้นหาผู้ใช้จากอีเมล์
            $sql = "SELECT * FROM tblusers WHERE useremail = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            // ตรวจสอบว่าพบผู้ใช้หรือไม่
            if ($result->num_rows > 0) {
                $userdata = $result->fetch_assoc();

                // สร้างรหัสผ่านใหม่แบบสุ่ม
                $newPassword = substr(md5(time()), 0, 8); // รหัสผ่าน 8 ตัวอักษร
                $hashedPassword = md5($newPassword);

                // อัปเดตรหัสผ่านในฐานข้อมูล
                $update_sql = "UPDATE tblusers SET password = ?, pass_update = '1' WHERE useremail = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("ss", $hashedPassword, $email);
                $update_stmt->execute();

                if ($update_stmt->affected_rows > 0) {
                    try {
                        // สร้างเนื้อหาข้อความอีเมล์
                        $message_body = "
                                        <html>
                                        <head>
                                        <title>รีเซ็ตรหัสผ่าน</title>
                                        </head>
                                        <body>
                                        <div style='padding:20px; border:1px solid #ddd; border-radius:5px;'>
                                        <h2>ระบบสหกิจศึกษา - รีเซ็ตรหัสผ่าน</h2>
                                        <p>เรียน {$userdata['fullname']}</p>
                                        <p>รหัสผ่านใหม่ของคุณ: <strong>{$newPassword}</strong></p>
                                        <p>ด้วยความเคารพ,<br>ทีมงานระบบสหกิจศึกษา มหาวิทยาลัยราชภัฏเลย</p>
                                        </div>
                                        </body>
                                        </html>
                                        ";
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
                        $mail->setFrom('your-email@gmail.com', 'ระบบสหกิจศึกษา');
                        $mail->addAddress($email, $userdata['fullname']);

                        // Content
                        $mail->isHTML(true);
                        $mail->Subject = "รีเซ็ตรหัสผ่าน - ระบบสหกิจศึกษา";
                        $mail->Body = $message_body;

                        $mail->send();
                        $message = "รหัสผ่านใหม่ถูกส่งไปยังอีเมล์ของคุณแล้ว กรุณาตรวจสอบกล่องจดหมาย";
                        $messageClass = "alert-success";
                    } catch (Exception $e) {
                        $message = "รีเซ็ตรหัสผ่านสำเร็จ! รหัสผ่านใหม่คือ: <strong>{$newPassword}</strong> แต่ไม่สามารถส่งอีเมล์ได้: " . $mail->ErrorInfo;
                        $messageClass = "alert-warning";
                    }
                } else {
                    $message = "เกิดข้อผิดพลาดในการอัปเดตรหัสผ่าน กรุณาลองใหม่อีกครั้ง";
                    $messageClass = "alert-danger";
                }

                // ปิดการเชื่อมต่อ statements
                $update_stmt->close();
            } else {
                $message = "ไม่พบอีเมล์นี้ในระบบ กรุณาตรวจสอบอีเมล์อีกครั้ง";
                $messageClass = "alert-danger";
            }

            // ปิดการเชื่อมต่อ statement
            $stmt->close();
        } catch (Exception $e) {
            $message = "เกิดข้อผิดพลาดในระบบ: " . $e->getMessage();
            $messageClass = "alert-danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <title>ลืมรหัสผ่าน - ระบบสหกิจศึกษา</title>

    <!-- Stylesheets -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="plugins/revolution/css/settings.css" rel="stylesheet" type="text/css"><!-- REVOLUTION SETTINGS STYLES -->
    <link href="plugins/revolution/css/layers.css" rel="stylesheet" type="text/css"><!-- REVOLUTION LAYERS STYLES -->
    <link href="plugins/revolution/css/navigation.css" rel="stylesheet" type="text/css"><!-- REVOLUTION NAVIGATION STYLES -->

    <link href="css/style.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">

    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
    <link rel="icon" href="images/favicon.png" type="image/x-icon">

    <!-- Responsive -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <!--[if lt IE 9]><script src="js/html5shiv.js"></script><![endif]-->
    <!--[if lt IE 9]><script src="js/respond.js"></script><![endif]-->
    <script src="https://cdn.linearicons.com/free/1.0.0/svgembedder.min.js"></script>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        .forgot-password-container {
            max-width: 500px;
            margin: 100px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 30px;
        }

        .logo-container img {
            max-width: 150px;
        }

        .alert {
            border-radius: 3px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="forgot-password-container">
            <div class="logo-container">
                <img src="images/lru.png" alt="Logo ระบบสหกิจศึกษา" onerror="this.src='admin/images/logo.png'">
                <h2 class="mt-3">ลืมรหัสผ่าน</h2>
                <p class="text-muted">กรุณากรอกอีเมล์ที่ใช้ลงทะเบียนในระบบ</p>
            </div>

            <?php if (!empty($message)) : ?>
                <div class="alert <?php echo $messageClass; ?> alert-dismissible fade show" role="alert">
                    <?php echo $message; ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <form method="post" action="">
                <div class="form-group">
                    <label for="email"><i class="fas fa-envelope"></i> อีเมล์</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="กรอกอีเมล์ของคุณ" required>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" name="submit" class="theme-btn btn-style-one">ส่งรหัสผ่านใหม่</button>
                </div>

                <div class="text-center mt-3">
                    <a href="index.php" class="text-decoration-none">
                        <i class="fas fa-arrow-left"></i> กลับไปหน้าเข้าสู่ระบบ
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript Files -->
    <script src="js/jquery.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery-ui.js"></script>
    <script src="js/jquery.fancybox.js"></script>
    <script src="js/appear.js"></script>
    <script src="js/owl.js"></script>
    <script src="js/wow.js"></script>
    <script src="js/scrollbar.js"></script>
    <script src="js/script.js"></script>
</body>

</html>