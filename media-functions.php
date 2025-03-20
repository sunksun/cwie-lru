<?php

/**
 * ชุดฟังก์ชันสำหรับจัดการไฟล์มีเดียต่างๆ
 */

/**
 * ตรวจสอบและแสดงรูปภาพกิจกรรม
 */
function displayActivityImage($filename)
{
    if (!empty($filename)) {
        // ลองเรียกใช้ไฟล์ที่มีการปรับขนาด
        $thumbPath = "admin/img_act/270x270_" . $filename;
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $thumbPath)) {
            return $thumbPath;
        }

        // หากไม่มีไฟล์ thumbnail ให้ใช้ไฟล์ต้นฉบับ
        $originalPath = "admin/img_act/" . $filename;
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $originalPath)) {
            return $originalPath;
        }
    }

    // กรณีไม่มีไฟล์ให้แสดงรูปภาพพื้นฐาน
    return "images/resource/course-1.jpg";
}

/**
 * ตรวจสอบและแสดงรูปภาพข่าว
 */
function displayNewsImage($filename)
{
    if (!empty($filename)) {
        // ลองเรียกใช้ไฟล์ที่มีการปรับขนาด
        $thumbPath = "admin/img_news/370x360_" . $filename;
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $thumbPath)) {
            return $thumbPath;
        }

        // หากไม่มีไฟล์ thumbnail ให้ใช้ไฟล์ต้นฉบับ
        $originalPath = "admin/img_news/" . $filename;
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $originalPath)) {
            return $originalPath;
        }
    }

    // กรณีไม่มีไฟล์ให้แสดงรูปภาพพื้นฐาน
    return "images/resource/news-1.jpg";
}

/**
 * ตรวจสอบและแสดงรูปภาพอาจารย์
 */
function displayTeacherImage($filename)
{
    if (!empty($filename)) {
        // ลองเรียกใช้ไฟล์ที่มีการปรับขนาด
        $thumbPath = "admin/img_teach/220x220_" . $filename;
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $thumbPath)) {
            return $thumbPath;
        }

        // หากไม่มีไฟล์ thumbnail ให้ใช้ไฟล์ต้นฉบับ
        $originalPath = "admin/img_teach/" . $filename;
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $originalPath)) {
            return $originalPath;
        }
    }

    // กรณีไม่มีไฟล์ให้แสดงรูปภาพพื้นฐาน
    return "admin/img_teach/default-teacher.jpg";
}

/**
 * ตรวจสอบและแสดงรูปภาพงาน
 */
function displayJobImage($filename)
{
    if (!empty($filename)) {
        $path = "admin/img_job/" . $filename;
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $path)) {
            return $path;
        }
    }

    // กรณีไม่มีไฟล์ให้แสดงรูปภาพพื้นฐาน
    return "images/resource/job-default.jpg";
}
