<?php
// ตรวจสอบและแสดงรูปภาพกิจกรรมแบบปลอดภัย
function displayActivityImage($filename)
{
    if (!empty($filename)) {
        $imagePath = "admin/img_act/270x270_" . $filename;
        // ตรวจสอบว่าไฟล์มีอยู่จริงหรือไม่
        if (file_exists($imagePath)) {
            return $imagePath;
        } else {
            // ถ้าไม่พบไฟล์ที่มีการปรับขนาด ให้ใช้ไฟล์ต้นฉบับ
            $originalPath = "admin/img_act/" . $filename;
            if (file_exists($originalPath)) {
                return $originalPath;
            } else {
                // ถ้าไม่พบทั้งสองแบบให้ใช้รูปภาพพื้นฐาน
                return "images/placeholder-image.jpg";
            }
        }
    } else {
        // กรณีไม่มีชื่อไฟล์
        return "images/placeholder-image.jpg";
    }
}

// การใช้งานฟังก์ชัน:
// <img src="<?php echo displayActivityImage($row["filename"]); 
?>" alt="<?php echo htmlspecialchars($title); ?>">
?>