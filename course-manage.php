<?php
session_start();
include_once('admin/connect.php');
?>
<!DOCTYPE html>
<html lang="th">

<head>
	<meta charset="utf-8">
	<title>หลักสูตรที่มีการเรียนการสอนแบบ CWIE | CWIE LRU</title>
	<!-- Stylesheets -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style.css" rel="stylesheet">
	<link href="css/responsive.css" rel="stylesheet">
	<link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
	<link rel="icon" href="images/favicon.png" type="image/x-icon">

	<!-- Responsive -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<!--[if lt IE 9]><script src="js/html5shiv.js"></script><![endif]-->
	<!--[if lt IE 9]><script src="js/respond.js"></script><![endif]-->
	<style>
		.table-responsive {
			margin-top: 20px;
		}

		.table th {
			background-color: #f8f9fa;
			text-align: center;
			vertical-align: middle;
		}

		.table td {
			vertical-align: middle;
		}

		.faculty-header {
			background-color: #e9ecef;
			font-weight: bold;
		}

		.check-mark {
			color: green;
			font-size: 20px;
			display: block;
			text-align: center;
		}

		.note-cell {
			max-width: 200px;
		}

		.course-table {
			margin-bottom: 30px;
		}

		.page-title {
			margin-bottom: 30px;
		}
	</style>
</head>

<body>

	<div class="page-wrapper">

		<!-- Preloader -->
		<div class="preloader"></div>

		<!-- Main Header-->
		<?php include_once 'mainHeader.php'; ?>
		<!--End Main Header -->

		<!-- Start main-content -->
		<section class="page-title" style="background-image: url(images/colormag-logolru-1.png);">
			<div class="auto-container">
				<h1 class="d-none">หลักสูตรที่มีการเรียนการสอนแบบสหกิจศึกษาและการจัดการเรียนรู้เชิงบูรณาการกับการทำงาน (CWIE)</h1>
			</div>
		</section>
		<!-- end main-content -->

		<!--Course CWIE Start-->
		<section class="blog-details">
			<div class="container">
				<div class="row">
					<div class="col-xl-12 col-lg-12">
						<div class="table-responsive course-table">
							<h4 class="text-center mb-4">หลักสูตรที่มีการเรียนการสอนแบบสหกิจศึกษา (CWIE)</h4>
							<?php
							// ดึงข้อมูลคณะทั้งหมด
							$sql_faculty = "SELECT fid, faculty FROM faculty ORDER BY id ASC";
							$result_faculty = $conn->query($sql_faculty);

							if ($result_faculty && $result_faculty->num_rows > 0) {
								$faculty_counter = 1;

								while ($faculty_row = $result_faculty->fetch_assoc()) {
									$faculty_id = $faculty_row['fid'];
									$faculty_name = $faculty_row['faculty'];

									// สร้างตารางสำหรับแต่ละคณะ
									echo '<h4 class="mt-4 mb-3">' . $faculty_counter . '. ' . $faculty_name . '</h4>';

									// เพิ่มตาราง
									echo '<table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="60%">สาขาวิชา</th>
                                                <th width="10%">แบบแยก<br>(Separate)</th>
                                                <th width="10%">แบบคู่ขนาน<br>(Parallel)</th>
                                                <th width="10%">แบบผสม<br>(Mix)</th>
                                                <th width="10%">หมายเหตุ</th>
                                            </tr>
                                        </thead>
                                        <tbody>';

									// ดึงข้อมูลหลักสูตรตามคณะ
									$sql_course = "SELECT * FROM cwie_course WHERE faculty_id = '$faculty_id' ORDER BY id ASC";
									$result_course = $conn->query($sql_course);

									if ($result_course && $result_course->num_rows > 0) {
										$course_counter = 1;

										while ($course_row = $result_course->fetch_assoc()) {
											$major = $course_row['major'];
											$separate = $course_row['separate'];
											$parallel = $course_row['parallel'];
											$mix = $course_row['mix'];
											$note = $course_row['note'];

											echo '<tr>
                                                <td>' . $course_counter . '. ' . htmlspecialchars($major) . '</td>
                                                <td>' . ($separate ? '<span class="check-mark">✓</span>' : '') . '</td>
                                                <td>' . ($parallel ? '<span class="check-mark">✓</span>' : '') . '</td>
                                                <td>' . ($mix ? '<span class="check-mark">✓</span>' : '') . '</td>
                                                <td class="note-cell">' . htmlspecialchars($note) . '</td>
                                            </tr>';

											$course_counter++;
										}
									} else {
										echo '<tr><td colspan="5" class="text-center">ไม่พบข้อมูลหลักสูตร</td></tr>';
									}

									echo '</tbody></table>';
									$faculty_counter++;
								}
							} else {
								echo '<div class="alert alert-info">ไม่พบข้อมูลคณะ</div>';
							}
							?>
						</div>

						<div class="mb-5">
							<h5>หมายเหตุ:</h5>
							<p>
								<strong>แบบแยก (Separate)</strong> - จัดการเรียนการสอนในห้องเรียนสลับกับการฝึกปฏิบัติในสถานประกอบการ<br>
								<strong>แบบคู่ขนาน (Parallel)</strong> - จัดการเรียนการสอนในห้องเรียนควบคู่กับการฝึกปฏิบัติในสถานประกอบการ<br>
								<strong>แบบผสม (Mix)</strong> - จัดการเรียนการสอนแบบผสมผสานระหว่างรูปแบบแยกและรูปแบบคู่ขนาน
							</p>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!--Course CWIE End-->

		<!-- Main Footer -->
		<footer class="main-footer">
			<div class="bg-image zoom-two" style="background-image: url(./images/background/4.jpg)"></div>

			<!--Widgets Section-->
			<div class="widgets-section">
				<div class="auto-container">
					<div class="row">
						<!--Footer Column-->
						<div class="footer-column col-xl-3 col-lg-12 col-md-6 col-sm-12">
							<div class="footer-widget about-widget">
								<div class="logo"><a href="index.php"><img src="images/logo-2.png" alt=""></a></div>
								<ul class="social-icon-two">
									<li><a href="#"><i class="fab fa-twitter"></i></a></li>
									<li><a href="#"><i class="fab fa-facebook"></i></a></li>
									<li><a href="#"><i class="fab fa-pinterest"></i></a></li>
									<li><a href="#"><i class="fab fa-instagram"></i></a></li>
								</ul>
							</div>
						</div>

						<!--Footer Column-->
						<div class="footer-column col-xl-5 col-lg-4 col-md-6 col-sm-12 offset-xl-4">
							<div class="footer-widget contact-widget">
								<h4 class="widget-title">ติดต่อเรา</h4>
								<div class="widget-content">
									<ul class="contact-info">
										<li><i class="fa fa-phone-square"></i> <a href="tel:042-835224">042 - 835224 - 8 ต่อ 41127 - 41132</a></li>
										<li><i class="fa fa-envelope"></i> <a href="mailto:academic@lru.ac.th">academic@lru.ac.th</a></li>
										<li><i class="fa fa-map-marker-alt"></i> สำนักส่งเสริมวิชาการและงานทะเบียน มหาวิทยาลัยราชภัฏเลย <br>
											234 ถ.เลย-เชียงคาน ต.เมือง อ.เมือง จ.เลย 42000</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!--Footer Bottom-->
			<div class="footer-bottom">
				<div class="auto-container">
					<div class="inner-container">
						<div class="copyright-text">&copy; Copyright <?php echo date('Y'); ?> by <a href="index.php">CWIE | สหกิจศึกษามหาวิทยาลัยราชภัฏเลย</a></div>
					</div>
				</div>
			</div>
		</footer>
		<!--End Main Footer -->

	</div><!-- End Page Wrapper -->

	<!-- Scroll To Top -->
	<div class="scroll-to-top scroll-to-target" data-target="html"><span class="fa fa-angle-up"></span></div>

	<script src="js/jquery.js"></script>
	<script src="js/popper.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.fancybox.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script src="js/jquery.countdown.js"></script>
	<script src="js/mixitup.js"></script>
	<script src="js/wow.js"></script>
	<script src="js/appear.js"></script>
	<script src="js/select2.min.js"></script>
	<script src="js/swiper.min.js"></script>
	<script src="js/owl.js"></script>
	<script src="js/script.js"></script>
</body>

</html>