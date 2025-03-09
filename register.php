<?php
session_start();
include_once('admin/connect.php');

// เพิ่มการโหลด Composer autoloader
require 'vendor/autoload.php';

// เพิ่ม namespace ของ PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// ดึงข้อมูลคณะจากฐานข้อมูล
$sql_faculty = "SELECT * FROM faculty ORDER BY faculty ASC";
$result_faculty = $conn->query($sql_faculty);

// สร้าง array สำหรับเก็บ username prefixes ตามคณะที่กำหนด
$faculty_prefixes = array();

// กำหนดอีเมล์ผู้ดูแลระบบ
$admin_email = "sunksunlapunt@gmail.com"; // เปลี่ยนเป็นอีเมล์ผู้ดูแลระบบจริง

// เมื่อมีการส่งฟอร์มลงทะเบียน
if (isset($_POST['register'])) {
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $useremail = $_POST['useremail'];
    $faculty = $_POST['faculty'];
    $faculty_id = $_POST['faculty_id'];
    $position = $_POST['position'];
    $plain_password = $_POST['password']; // เก็บรหัสผ่านในรูปแบบข้อความธรรมดาสำหรับส่งอีเมล์
    $password = md5($_POST['password']); // เข้ารหัสรหัสผ่านด้วย MD5
    $confirm_password = md5($_POST['confirm_password']);
    $img = "avatar.png"; // รูปโปรไฟล์เริ่มต้น
    $pass_update = '1'; // รหัสผ่านได้รับการอัปเดตแล้ว
    $status = 'pending'; // สถานะรอการอนุมัติ

    // ตรวจสอบว่ามีการเลือกคณะหรือไม่
    if ($faculty_id == '0') {
        echo '<script type="text/javascript">';
        echo 'setTimeout(function () { swal.fire({
                title: "ผิดพลาด!",
                text: "กรุณาเลือกคณะ!",
                type: "warning",
                icon: "error"
            });';
        echo '}, 500);</script>';
    }
    // ตรวจสอบว่ารหัสผ่านและยืนยันรหัสผ่านตรงกันหรือไม่
    else if ($password !== $confirm_password) {
        echo '<script type="text/javascript">';
        echo 'setTimeout(function () { swal.fire({
                title: "ผิดพลาด!",
                text: "รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน!",
                type: "warning",
                icon: "error"
            });';
        echo '}, 500);</script>';
    } else {
        // ตรวจสอบว่ามีอีเมลนี้ในระบบแล้วหรือไม่ (อนุญาตให้ username ซ้ำกันได้)
        $check_sql = "SELECT * FROM tblusers WHERE useremail = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param('s', $useremail);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal.fire({
                    title: "ผิดพลาด!",
                    text: "อีเมลนี้มีในระบบแล้ว! กรุณาใช้อีเมลอื่น",
                    type: "warning",
                    icon: "error"
                });';
            echo '}, 500);</script>';
        } else {
            try {
                // เริ่มต้น transaction เพื่อให้แน่ใจว่าข้อมูลถูกบันทึกทั้งชุด
                $conn->begin_transaction();

                // เพิ่มผู้ใช้ใหม่ลงในฐานข้อมูล
                $insert_sql = "INSERT INTO tblusers (fullname, username, useremail, faculty, position, password, img, faculty_id, pass_update, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $insert_stmt = $conn->prepare($insert_sql);
                $insert_stmt->bind_param('ssssssssss', $fullname, $username, $useremail, $faculty, $position, $password, $img, $faculty_id, $pass_update, $status);
                $insert_stmt->execute();

                if ($insert_stmt->affected_rows > 0) {
                    $user_id = $conn->insert_id; // รับ ID ของผู้ใช้ที่เพิ่งสร้างใหม่

                    // สร้างโทเค็นการอนุมัติที่ไม่ซ้ำ
                    $approval_token = md5(uniqid($username . time(), true));

                    // บันทึกโทเค็นลงในฐานข้อมูล
                    $token_sql = "INSERT INTO approval_tokens (user_id, token) VALUES (?, ?)";
                    $token_stmt = $conn->prepare($token_sql);
                    $token_stmt->bind_param('is', $user_id, $approval_token);
                    $token_stmt->execute();

                    if ($token_stmt->affected_rows > 0) {
                        // Commit transaction หากทุกอย่างสำเร็จ
                        $conn->commit();

                        // สร้าง URL สำหรับการอนุมัติ
                        $approval_url = "http://" . $_SERVER['HTTP_HOST'] . "/approve_user.php?token=" . $approval_token;

                        // สร้างเนื้อหาอีเมล์สำหรับผู้ดูแลระบบ
                        $admin_message_body = "
                        <html>
                        <head>
                            <style>
                                body { font-family: 'Sarabun', Arial, sans-serif; }
                                .container { padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
                                .header { background-color: #4b5ebd; color: white; padding: 10px; border-radius: 5px 5px 0 0; text-align: center; }
                                .button { background-color: #4CAF50; color: white; padding: 10px 20px; text-decoration: none; border-radius: 4px; display: inline-block; }
                                .footer { font-size: 12px; color: #777; margin-top: 20px; }
                                .user-info { background-color: #f9f9f9; padding: 10px; border-radius: 5px; margin: 10px 0; }
                            </style>
                        </head>
                        <body>
                            <div class='container'>
                                <div class='header'>
                                    <h2>มีผู้สมัครใช้งานระบบใหม่รอการอนุมัติ</h2>
                                </div>
                                <p>เรียน ผู้ดูแลระบบ</p>
                                <p>มีผู้ใช้งานใหม่ลงทะเบียนในระบบสหกิจศึกษา มหาวิทยาลัยราชภัฏเลย กรุณาตรวจสอบและอนุมัติการใช้งาน</p>
                                
                                <div class='user-info'>
                                    <p><strong>ข้อมูลผู้สมัคร:</strong></p>
                                    <p>ชื่อ-นามสกุล: $fullname</p>
                                    <p>ชื่อผู้ใช้: $username</p>
                                    <p>อีเมล์: $useremail</p>
                                    <p>คณะ: $faculty</p>
                                    <p>ตำแหน่ง: $position</p>
                                </div>
                                
                                <p>กรุณาคลิกที่ปุ่มด้านล่างเพื่ออนุมัติผู้ใช้งานนี้</p>
                                <p><a href='$approval_url' class='button'>อนุมัติผู้ใช้งาน</a></p>
                                <p>หรือคัดลอกลิงก์นี้ไปวางในเบราว์เซอร์: $approval_url</p>
                                
                                <div class='footer'>
                                    <p>ด้วยความเคารพ,<br>ระบบสหกิจศึกษา มหาวิทยาลัยราชภัฏเลย</p>
                                </div>
                            </div>
                        </body>
                        </html>
                        ";

                        // สร้างเนื้อหาอีเมล์สำหรับผู้สมัคร
                        $user_message_body = "
                        <html>
                        <head>
                            <style>
                                body { font-family: 'Sarabun', Arial, sans-serif; }
                                .container { padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
                                .header { background-color: #4b5ebd; color: white; padding: 10px; border-radius: 5px 5px 0 0; text-align: center; }
                                .footer { font-size: 12px; color: #777; margin-top: 20px; }
                                .info-box { background-color: #f9f9f9; padding: 10px; border-radius: 5px; margin: 10px 0; }
                                .credentials { background-color: #e9f7fe; padding: 15px; border-radius: 5px; margin: 15px 0; border-left: 4px solid #4b5ebd; }
                            </style>
                        </head>
                        <body>
                            <div class='container'>
                                <div class='header'>
                                    <h2>ยืนยันการลงทะเบียน - ระบบสหกิจศึกษา</h2>
                                </div>
                                <p>เรียน คุณ$fullname</p>
                                <p>ขอบคุณสำหรับการลงทะเบียนในระบบสหกิจศึกษา มหาวิทยาลัยราชภัฏเลย</p>
                                
                                <div class='info-box'>
                                    <p><strong>บัญชีของคุณอยู่ระหว่างรอการอนุมัติจากผู้ดูแลระบบ</strong></p>
                                    <p>เมื่อบัญชีของคุณได้รับการอนุมัติแล้ว คุณจะได้รับอีเมล์แจ้งเตือนอีกครั้ง</p>
                                </div>
                                
                                <div class='credentials'>
                                    <h3>ข้อมูลการเข้าสู่ระบบของคุณ</h3>
                                    <p><strong>ชื่อผู้ใช้:</strong> $username</p>
                                    <p><strong>รหัสผ่าน:</strong> $plain_password</p>
                                    <p><em>กรุณาเก็บข้อมูลนี้ไว้เป็นความลับและเปลี่ยนรหัสผ่านของคุณหลังจากเข้าสู่ระบบครั้งแรก</em></p>
                                </div>
                                
                                <div class='footer'>
                                    <p>ด้วยความเคารพ,<br>ทีมงานระบบสหกิจศึกษา มหาวิทยาลัยราชภัฏเลย</p>
                                    <p>หากมีคำถามหรือต้องการความช่วยเหลือ กรุณาติดต่อ: <a href='mailto:sunksunlapunt@gmail.com'>sunksunlapunt@gmail.com</a></p>
                                </div>
                            </div>
                        </body>
                        </html>
                        ";

                        // ส่งอีเมล์แจ้งเตือนผู้ดูแลระบบ
                        $email_sent = sendEmail($admin_email, 'ระบบสหกิจศึกษา', 'แจ้งเตือน: มีผู้สมัครใช้งานระบบใหม่รอการอนุมัติ', $admin_message_body);

                        // ส่งอีเมล์แจ้งเตือนผู้สมัคร
                        $user_email_sent = sendEmail($useremail, 'ระบบสหกิจศึกษา', 'ยืนยันการลงทะเบียน - ระบบสหกิจศึกษา', $user_message_body);

                        echo '<script type="text/javascript">';
                        echo 'setTimeout(function () { swal.fire({
                                title: "ลงทะเบียนสำเร็จ!",
                                text: "บัญชีของคุณอยู่ระหว่างรอการอนุมัติจากผู้ดูแลระบบ",
                                type: "success",
                                icon: "success"
                            });';
                        echo '}, 500 );</script>';

                        echo '<script type="text/javascript">';
                        echo 'setTimeout(function () { 
                                window.location.href = "admin/login.php";';
                        echo '}, 3000 );</script>';
                    } else {
                        throw new Exception("ไม่สามารถสร้างโทเค็นการอนุมัติได้");
                    }
                } else {
                    throw new Exception("ไม่สามารถเพิ่มผู้ใช้ใหม่ได้");
                }
            } catch (Exception $e) {
                // ถ้าเกิดข้อผิดพลาด ให้ rollback transaction
                $conn->rollback();

                echo '<script type="text/javascript">';
                echo 'setTimeout(function () { swal.fire({
                        title: "ผิดพลาด!",
                        text: "เกิดข้อผิดพลาดในการลงทะเบียน: ' . $e->getMessage() . '",
                        type: "warning",
                        icon: "error"
                    });';
                echo '}, 500);</script>';
            }
        }
    }
}

/**
 * ฟังก์ชันสำหรับส่งอีเมล์ด้วย PHPMailer
 * 
 * @param string $to อีเมล์ผู้รับ
 * @param string $from_name ชื่อผู้ส่ง
 * @param string $subject หัวข้ออีเมล์
 * @param string $body เนื้อหาอีเมล์
 * @return bool สถานะการส่ง (true หากสำเร็จ, false หากล้มเหลว)
 */
function sendEmail($to, $from_name, $subject, $body)
{
    try {
        // ตั้งค่า PHPMailer
        $mail = new PHPMailer(true);
        $mail->SMTPDebug = SMTP::DEBUG_OFF;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sunksunlapunt@gmail.com';
        $mail->Password   = 'yjlojkmbmjwvhpqs';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        // ตั้งค่าผู้ส่งและผู้รับ
        $mail->setFrom('sunksunlapunt@gmail.com', $from_name);
        $mail->addAddress($to);

        // ตั้งค่าเนื้อหาอีเมล์
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        // ส่งอีเมล์
        return $mail->send();
    } catch (Exception $e) {
        // บันทึกข้อผิดพลาดลงในไฟล์ log (อาจเพิ่มในอนาคต)
        // error_log('ไม่สามารถส่งอีเมล์ได้: ' . $mail->ErrorInfo);
        return false;
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="utf-8">
    <title>สมัครสมาชิก | สหกิจศึกษา :: มหาวิทยาลัยราชภัฏเลย</title>

    <!-- Stylesheets -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="plugins/revolution/css/settings.css" rel="stylesheet" type="text/css"><!-- REVOLUTION SETTINGS STYLES -->
    <link href="plugins/revolution/css/layers.css" rel="stylesheet" type="text/css"><!-- REVOLUTION LAYERS STYLES -->
    <link href="plugins/revolution/css/navigation.css" rel="stylesheet" type="text/css"><!-- REVOLUTION NAVIGATION STYLES -->

    <link href="css/style.css" rel="stylesheet">
    <link href="css/responsive.css" rel="stylesheet">

    <link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
    <link rel="icon" href="images/favicon.png" type="image/x-icon">

    <link href="css/register.css" rel="stylesheet">

    <!-- Responsive -->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <!--[if lt IE 9]><script src="js/html5shiv.js"></script><![endif]-->
    <!--[if lt IE 9]><script src="js/respond.js"></script><![endif]-->
    <script src="https://cdn.linearicons.com/free/1.0.0/svgembedder.min.js"></script>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <!-- sweetalert2 -->
    <script src="admin/sweetalert2/dist/sweetalert2.all.min.js"></script>
    <script src="admin/sweetalert2/dist/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="admin/sweetalert2/dist/sweetalert2.min.css">
    <script>
        // กำหนดค่า username ตามคณะที่เลือก
        function updateFacultyAndUsername() {
            let faculty_id = document.getElementById("faculty_id").value;
            let facultySelect = document.getElementById("faculty_id");
            let selectedOption = facultySelect.options[facultySelect.selectedIndex];
            let username = "";

            // อัปเดตค่า faculty
            document.getElementById("faculty").value = selectedOption.text;

            // กำหนดค่า username ตามคณะที่เลือก
            if (faculty_id !== "0") {
                let facultyName = selectedOption.text;

                if (facultyName.includes("วิทยาศาสตร์และเทคโนโลยี")) {
                    username = "admin_sci";
                } else if (facultyName.includes("มนุษยศาสตร์และสังคมศาสตร์")) {
                    username = "admin_hum";
                } else if (facultyName.includes("เทคโนโลยีอุตสาหกรรม")) {
                    username = "admin_tec";
                } else if (facultyName.includes("วิทยาการจัดการ")) {
                    username = "admin_fms";
                } else if (facultyName.includes("ครุศาสตร์")) {
                    username = "admin_edu";
                } else {
                    // ถ้าไม่ตรงกับคณะที่กำหนด ใช้ชื่อย่อของคณะ
                    username = "admin_" + facultyName.substring(0, 3).toLowerCase();
                }

                // อัปเดตค่า username ในฟอร์ม
                document.getElementById("username").value = username;
                document.getElementById("username").readOnly = true;
                document.getElementById("username_notice").style.display = "block";
            } else {
                // ถ้าไม่ได้เลือกคณะ ให้ล้างค่า username
                document.getElementById("username").value = "";
                document.getElementById("username").readOnly = false;
                document.getElementById("username_notice").style.display = "none";
            }
        }

        function validateForm() {
            let fullname = document.forms["registerForm"]["fullname"].value;
            if (fullname == "") {
                alert("กรุณากรอกชื่อ-นามสกุล");
                document.forms["registerForm"]["fullname"].focus();
                return false;
            }

            let username = document.forms["registerForm"]["username"].value;
            if (username == "") {
                alert("กรุณาเลือกคณะเพื่อกำหนดชื่อผู้ใช้งาน");
                document.forms["registerForm"]["faculty_id"].focus();
                return false;
            }

            let useremail = document.forms["registerForm"]["useremail"].value;
            if (useremail == "") {
                alert("กรุณากรอกอีเมล");
                document.forms["registerForm"]["useremail"].focus();
                return false;
            }

            let position = document.forms["registerForm"]["position"].value;
            if (position == "") {
                alert("กรุณากรอกตำแหน่ง");
                document.forms["registerForm"]["position"].focus();
                return false;
            }

            let faculty_id = document.forms["registerForm"]["faculty_id"].value;
            if (faculty_id == "0") {
                alert("กรุณาเลือกคณะ");
                document.forms["registerForm"]["faculty_id"].focus();
                return false;
            }

            let password = document.forms["registerForm"]["password"].value;
            if (password == "") {
                alert("กรุณากรอกรหัสผ่าน");
                document.forms["registerForm"]["password"].focus();
                return false;
            }

            let confirm_password = document.forms["registerForm"]["confirm_password"].value;
            if (confirm_password == "") {
                alert("กรุณายืนยันรหัสผ่าน");
                document.forms["registerForm"]["confirm_password"].focus();
                return false;
            }

            if (password !== confirm_password) {
                alert("รหัสผ่านและยืนยันรหัสผ่านไม่ตรงกัน");
                document.forms["registerForm"]["confirm_password"].focus();
                return false;
            }
        }
    </script>
</head>

<body>
    <div class="container">
        <div class="register-box">
            <div class="logo-container text-center mb-4">
                <img src="images/lru.png" width="120 px" alt="Logo ระบบสหกิจศึกษา" onerror="this.src='admin/images/logo.png'">
                <h4 class="mt-3">สมัครเข้าใช้งานระบบ</h4>
                <p class="text-muted">สหกิจศึกษาและการจัดการเรียนรู้เชิงบูรณาการกับการทำงาน (CWIE)</p>
            </div>
            <div class="card-body">
                <p class="text-center mb-4">สำหรับอาจารย์/เจ้าหน้าที่</p>
                <!-- เพิ่มข้อความแจ้งเตือนเกี่ยวกับการอนุมัติ -->
                <div class="alert alert-info mb-4" role="alert">
                    <i class="fas fa-info-circle"></i> หลังจากลงทะเบียน บัญชีของคุณจะต้องได้รับการอนุมัติจากผู้ดูแลระบบก่อนจึงจะสามารถเข้าใช้งานได้
                </div>
                <form name="registerForm" action="" method="post" onsubmit="return validateForm()">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-user"></i> ชื่อ-นามสกุล</label>
                                <input type="text" class="form-control" placeholder="ชื่อ-นามสกุล" name="fullname" id="fullname">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-user-circle"></i> ชื่อผู้ใช้งาน</label>
                                <input type="text" class="form-control" placeholder="ชื่อผู้ใช้งานจะถูกกำหนดตามคณะที่เลือก" name="username" id="username" readonly>
                                <small id="username_notice" style="display:none;" class="form-text text-info">
                                    <i class="fas fa-info-circle"></i> ชื่อผู้ใช้ถูกกำหนดอัตโนมัติตามคณะที่เลือก
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-envelope"></i> อีเมล</label>
                                <input type="email" class="form-control" placeholder="อีเมล" name="useremail" id="useremail">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-briefcase"></i> ตำแหน่ง</label>
                                <input type="text" class="form-control" placeholder="ตำแหน่ง" name="position" id="position">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-university"></i> คณะ</label>
                        <select class="form-control" name="faculty_id" id="faculty_id" onchange="updateFacultyAndUsername()">
                            <option value="0">-- เลือกคณะ --</option>
                            <?php while ($row = $result_faculty->fetch_assoc()) : ?>
                                <option value="<?php echo $row['fid']; ?>"><?php echo $row['faculty']; ?></option>
                            <?php endwhile; ?>
                        </select>
                        <input type="hidden" name="faculty" id="faculty" value="">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-lock"></i> รหัสผ่าน</label>
                                <input type="password" class="form-control" placeholder="รหัสผ่าน" name="password" id="password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><i class="fas fa-lock"></i> ยืนยันรหัสผ่าน</label>
                                <input type="password" class="form-control" placeholder="ยืนยันรหัสผ่าน" name="confirm_password" id="confirm_password">
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="icheck-primary">
                                <p class="mb-1">
                                    <a href="admin/login.php"><i class="fas fa-arrow-left"></i> มีบัญชีแล้ว? เข้าสู่ระบบ</a>
                                </p>
                            </div>
                        </div>
                        <!-- /.col -->
                        <div class="col-md-6 text-right">
                            <button type="submit" name="register" class="theme-btn btn-style-one btn-register">สมัครสมาชิก</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.register-box -->
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