<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="index.php" class="brand-link">
    <center><span style="font-size: large;"><?php echo $_SESSION['position']; ?></span></center>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <img src="dist/img/<?php echo $user_img; ?>" class="img-circle elevation-2" alt="User Image">
      </div>
      <div class="info">
        <a href="#" class="d-block"><?php echo $_SESSION['fullname']; ?></a>
      </div>
    </div>

    <!-- เพิ่ม CSS สำหรับปรับแต่ง nav-treeview -->
    <style>
      .nav-treeview {
        background-color: rgba(255, 255, 255, 0.05);
        border-left: 3px solid #4f5962;
        margin-left: 5px;
      }

      .nav-treeview .nav-item {
        margin-bottom: 2px;
      }

      .nav-treeview .nav-link {
        border-radius: 4px;
        margin-left: 5px;
        margin-right: 5px;
        padding-left: 15px;
      }

      .nav-treeview .nav-link:hover {
        background-color: rgba(255, 255, 255, 0.1);
      }

      .nav-treeview .nav-link.active {
        background-color: #007bff !important;
        color: #ffffff !important;
      }

      /* สีที่แตกต่างกันสำหรับเมนูย่อยระดับต่างๆ */
      .nav-item:nth-child(odd)>.nav-treeview {
        border-left-color: #17a2b8;
      }

      .nav-item:nth-child(even)>.nav-treeview {
        border-left-color: #28a745;
      }
    </style>

    <!-- SidebarSearch Form -->
    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        <?php if ($username == 'admin') : ?>
          <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                Dashboard
              </p>
            </a>

          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-bullhorn"></i>
              <p>
                ข่าวประชาสัมพันธ์
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="newsAdd.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>จัดการข่าวประชาสัมพันธ์</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-tree"></i>
              <p>
                โครงการ/กิจกรรม
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="activityAdd.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>จัดการโครงการ/กิจกรรม</p>
                </a>
              </li>
              <!--
              <li class="nav-item">
                <a href="activityPhotoAdd.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>เพิ่มรูปโครงการ/กิจกรรม</p>
                </a>
              </li>
              -->
            </ul>
          </li>
          <!--
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>
                อาจารย์นิเทศสหกิจฯ
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="teachCwieAdd.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>จัดการข้อมูลอาจารย์</p>
                </a>
              </li>
            </ul>
          </li>
        -->
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-image"></i>
              <p>
                ข่าวรับสมัครงาน
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="jobAdd.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>จัดการรับสมัครงาน</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="yearManage.php" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>จัดการปีการศึกษา</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="facultyTeacherManage.php" class="nav-link">
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>จำนวนอาจารย์</p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                รายงานข้อมูล CWIE
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="course-manage-report.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>การจัดการหลักสูตร</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="num-stu-cwie-report.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>จำนวนนักศึกษา</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="organization-cwie-report.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>สถานประกอบการ</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="num-tea-cwie-report.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>อาจารย์นิเทศ</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="activity-cwie-report.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>การจัดกิจกรรม</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                รายงานข้อมูล SIL
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="sil-course-manage-report.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>การจัดการหลักสูตร</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="num-stu-sil-report.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>จำนวนนักศึกษา</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="organization-sil-report.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>สถานศึกษา</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="num-tea-sil-report.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>อาจารย์นิเทศ</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="activity-sil-report.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>การจัดกิจกรรม</p>
                </a>
              </li>
            </ul>
          </li>
          <!-- 
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                นักศึกษาสหกิจฯ
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="studentAdd.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>จัดการข้อมูลนักศึกษา</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="stu-highlightAdd.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>นักศึกษา CWIE Highlight</p>
                </a>
              </li>
            </ul>
          </li>
        -->
          <!--
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-building"></i>
              <p>
                สถานประกอบการ
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="organizationAdd.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>จัดการสถานประกอบการ</p>
                </a>
              </li>
            </ul>
          </li>
        -->
          <!--
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-landmark"></i>
              <p>
                ข้อมูลสาขาวิชา
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="courseAdd.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>จัดการข้อมูลหลักสูตร</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-id-card"></i>
              <p>
                อาจารย์/เจ้าหน้าที่
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="officerAdd.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>ข้อมูลอาจารย์/เจ้าหน้าที่</p>
                </a>
              </li>
            </ul>
          </li>
        -->

        <?php endif; ?>

        <?php if ($username != 'admin' && $username != 'admin_edu') : ?>
          <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                CWIE
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                การจัดการหลักสูตร
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="cwieCourseAdd.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>รูปแบบการจัดการ</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="cwieCourseReport.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>พิมพ์รายงาน</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>
                จำนวนนักศึกษาฯ
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="numStuCwieAdd.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>เพิ่มข้อมูลนักศึกษา CWIE</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="numStuCwieReport.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>พิมพ์รายงาน</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-building"></i>
              <p>
                สถานประกอบการ
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="orgMouAdd.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>สถานประกอบการที่ MOU</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="orgMouReport.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>พิมพ์รายงาน</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-landmark"></i>
              <p>
                อาจารย์นิเทศสหกิจฯ
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="numTeachCwieAdd.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>อาจารย์ที่ขึ้นทะเบียน</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="numTeachCwieReport.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>พิมพ์รายงาน</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-id-card"></i>
              <p>
                การจัดกิจกรรม
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="activityCwieAdd.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>กิจกรรมสหกิจศึกษา</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="activityCwieReport.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>พิมพ์รายงาน</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="newsAdd.php" class="nav-link">
              <i class="nav-icon fas fa-bullhorn"></i>
              <p>
                ข่าวประชาสัมพันธ์
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
          </li>
          <li class="nav-item menu-open">
            <a href="logout.php" class="nav-link active">
              <i class="nav-icon fas fa-external-link-alt"></i>
              <p>
                ออกจากระบบ
              </p>
            </a>
          </li>
        <?php endif; ?>
        <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
        <?php if ($username == 'admin_edu') : ?>

          <li class="nav-item menu-open">
            <a href="#" class="nav-link active">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>
                SIL
              </p>
            </a>

          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-users"></i>
              <p>
                การจัดการหลักสูตร
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="silCourseAdd.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>รูปแบบการจัดการ</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="silCourseReport.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>พิมพ์รายงาน</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-user-tie"></i>
              <p>
                จำนวนนักศึกษาฯ
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="numStuSilAdd.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>เพิ่มข้อมูลนักศึกษา SIL</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="numStuSilReport.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>พิมพ์รายงาน</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-building"></i>
              <p>
                สถานศึกษากับคณะ SIL
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="orgMouAdd.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>สถานศึกษาที่ MOU</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="orgMouReport.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>พิมพ์รายงาน</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-landmark"></i>
              <p>
                อาจารย์นิเทศ
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="numTeachCwieAdd.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>อาจารย์ที่ขึ้นทะเบียน</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="numTeachCwieReport.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>พิมพ์รายงาน</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far fa-id-card"></i>
              <p>
                การจัดกิจกรรม
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="activityCwieAdd.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>กิจกรรม SIL</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="activityCwieReport.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>พิมพ์รายงาน</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-bullhorn"></i>
              <p>
                ข่าวประชาสัมพันธ์
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="newsAdd.php" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>จัดการข่าวประชาสัมพันธ์</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item menu-open">
            <a href="logout.php" class="nav-link active">
              <i class="nav-icon fas fa-external-link-alt"></i>
              <p>
                ออกจากระบบ
              </p>
            </a>
          </li>
        <?php endif; ?>
        <!-- 
        <li class="nav-header">EXAMPLES</li>
        <li class="nav-item">
          <a href="pages/calendar.html" class="nav-link">
            <i class="nav-icon far fa-calendar-alt"></i>
            <p>
              ปฏิทินกิจกรรม
              <span class="badge badge-info right">2</span>
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="pages/gallery.html" class="nav-link">
            <i class="nav-icon far fa-image"></i>
            <p>
              ภาพกิจกรรม
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="pages/kanban.html" class="nav-link">
            <i class="nav-icon fas fa-columns"></i>
            <p>
              ผลการดำเนินงาน
            </p>
          </a>
        </li>
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon far fa-envelope"></i>
            <p>
              แบบฟอร์ม
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="pages/mailbox/mailbox.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Inbox</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="pages/mailbox/compose.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Compose</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="pages/mailbox/read-mail.html" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Read</p>
              </a>
            </li>
          </ul>
        </li>
        -->
        <!-- QR Code for Support -->
        <li class="nav-header mt-4">ช่วยเหลือ</li>
        <li class="nav-item text-center">
          <div class="p-3">
            <img src="dist/img/IMG_CE87BB1CDEA9-1.jpeg" class="img-fluid" style="max-width: 150px;" alt="QR Code">
            <p class="text-center mt-2 text-light">แจ้งปัญหาการใช้งานระบบ</p>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>