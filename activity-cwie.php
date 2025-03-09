<?php
session_start();
include_once('admin/connect.php');
?>
<!DOCTYPE html>
<html lang="th">

<head>
	<meta charset="utf-8">
	<title>กิจกรรม CWIE | CWIE LRU</title>
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
			font-weight: bold;
		}

		.table td {
			vertical-align: middle;
		}

		.faculty-header {
			background-color: #e9ecef;
			font-weight: bold;
		}

		.text-center {
			text-align: center;
		}

		.text-right {
			text-align: right;
		}

		.section-title {
			margin-bottom: 30px;
			text-align: center;
		}

		.activity-section {
			margin-bottom: 50px;
		}

		.activity-table {
			margin-bottom: 30px;
		}

		.card {
			margin-bottom: 20px;
			box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
		}

		.card-header {
			background-color: #f0f0f0;
			font-weight: bold;
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
				<h1 class="d-none">กิจกรรม CWIE</h1>
			</div>
		</section>
		<!-- end main-content -->

		<!--Activity CWIE Start-->
		<section class="blog-details">
			<div class="container">
				<div class="row">
					<div class="col-xl-12 col-lg-12">
						<h4 class="section-title">กิจกรรมสหกิจศึกษาและการศึกษาเชิงบูรณาการกับการทำงาน (CWIE)</h4>

						<!-- กิจกรรมนักศึกษา -->
						<div class="activity-section">
							<div class="card">
								<div class="card-header">
									<h4>กิจกรรมนักศึกษา</h4>
								</div>
								<div class="card-body">
									<div class="table-responsive activity-table">
										<table class="table table-bordered">
											<thead>
												<tr>
													<th style="width: 5%">ลำดับ</th>
													<th style="width: 20%">คณะ</th>
													<th style="width: 25%">ชื่อกิจกรรม</th>
													<th style="width: 25%">หลักสูตร/สาขาวิชา</th>
													<th style="width: 15%">วันที่จัดกิจกรรม</th>
													<th style="width: 10%">จำนวนผู้เข้าร่วม</th>
												</tr>
											</thead>
											<tbody>
												<?php
												// ดึงข้อมูลกิจกรรมนักศึกษา (activity_type = 'กิจกรรมนักศึกษา')
												$sql_student = "SELECT ac.*, f.faculty AS faculty_name 
															FROM activity_cwie ac 
															LEFT JOIN faculty f ON ac.faculty_id = f.fid 
															WHERE ac.activity_type = 'กิจกรรมนักศึกษา' 
															ORDER BY ac.date_regis DESC";
												$result_student = $conn->query($sql_student);

												if ($result_student && $result_student->num_rows > 0) {
													$counter = 1;
													while ($row = $result_student->fetch_assoc()) {
														echo '<tr>
															<td class="text-center">' . $counter . '</td>
															<td>' . $row['faculty_name'] . '</td>
															<td>' . $row['activity_name'] . '</td>
															<td>' . $row['course'] . '</td>
															<td class="text-center">' . $row['activity_date'] . '</td>
															<td class="text-center">' . $row['amount'] . '</td>
														</tr>';
														$counter++;
													}
												} else {
													echo '<tr><td colspan="6" class="text-center">ไม่พบข้อมูลกิจกรรมนักศึกษา</td></tr>';
												}
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>

						<!-- กิจกรรมอาจารย์ -->
						<div class="activity-section">
							<div class="card">
								<div class="card-header">
									<h3>กิจกรรมอาจารย์</h3>
								</div>
								<div class="card-body">
									<div class="table-responsive activity-table">
										<table class="table table-bordered">
											<thead>
												<tr>
													<th style="width: 5%">ลำดับที่</th>
													<th style="width: 20%">คณะ</th>
													<th style="width: 25%">ชื่อกิจกรรม</th>
													<th style="width: 25%">หลักสูตร/สาขาวิชา</th>
													<th style="width: 15%">วันที่จัดกิจกรรม</th>
													<th style="width: 10%">จำนวนผู้เข้าร่วม</th>
												</tr>
											</thead>
											<tbody>
												<?php
												// ดึงข้อมูลกิจกรรมอาจารย์ (activity_type = 'กิจกรรมอาจารย์')
												$sql_teacher = "SELECT ac.*, f.faculty AS faculty_name 
															FROM activity_cwie ac 
															LEFT JOIN faculty f ON ac.faculty_id = f.fid 
															WHERE ac.activity_type = 'กิจกรรมอาจารย์' 
															ORDER BY ac.date_regis DESC";
												$result_teacher = $conn->query($sql_teacher);

												if ($result_teacher && $result_teacher->num_rows > 0) {
													$counter = 1;
													while ($row = $result_teacher->fetch_assoc()) {
														echo '<tr>
															<td class="text-center">' . $counter . '</td>
															<td>' . $row['faculty_name'] . '</td>
															<td>' . $row['activity_name'] . '</td>
															<td>' . $row['course'] . '</td>
															<td class="text-center">' . $row['activity_date'] . '</td>
															<td class="text-center">' . $row['amount'] . '</td>
														</tr>';
														$counter++;
													}
												} else {
													echo '<tr><td colspan="6" class="text-center">ไม่พบข้อมูลกิจกรรมอาจารย์</td></tr>';
												}
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>

						<!-- กิจกรรมร่วมกับสถานประกอบการ -->
						<div class="activity-section">
							<div class="card">
								<div class="card-header">
									<h3>กิจกรรมร่วมกับสถานประกอบการ</h3>
								</div>
								<div class="card-body">
									<div class="table-responsive activity-table">
										<table class="table table-bordered">
											<thead>
												<tr>
													<th style="width: 5%">ลำดับที่</th>
													<th style="width: 20%">คณะ</th>
													<th style="width: 25%">ชื่อกิจกรรม</th>
													<th style="width: 25%">หลักสูตร/สาขาวิชา</th>
													<th style="width: 15%">วันที่จัดกิจกรรม</th>
													<th style="width: 10%">จำนวนผู้เข้าร่วม</th>
												</tr>
											</thead>
											<tbody>
												<?php
												// ดึงข้อมูลกิจกรรมร่วมกับสถานประกอบการ (activity_type = 'กิจกรรมร่วมกับสถานประกอบการ')
												$sql_org = "SELECT ac.*, f.faculty AS faculty_name 
															FROM activity_cwie ac 
															LEFT JOIN faculty f ON ac.faculty_id = f.fid 
															WHERE ac.activity_type = 'กิจกรรมร่วมกับสถานประกอบการ' 
															ORDER BY ac.date_regis DESC";
												$result_org = $conn->query($sql_org);

												if ($result_org && $result_org->num_rows > 0) {
													$counter = 1;
													while ($row = $result_org->fetch_assoc()) {
														echo '<tr>
															<td class="text-center">' . $counter . '</td>
															<td>' . $row['faculty_name'] . '</td>
															<td>' . $row['activity_name'] . '</td>
															<td>' . $row['course'] . '</td>
															<td class="text-center">' . $row['activity_date'] . '</td>
															<td class="text-center">' . $row['amount'] . '</td>
														</tr>';
														$counter++;
													}
												} else {
													echo '<tr><td colspan="6" class="text-center">ไม่พบข้อมูลกิจกรรมร่วมกับสถานประกอบการ</td></tr>';
												}
												?>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>

						<div class="mb-5">
							<h5>หมายเหตุ:</h5>
							<p>
								1. ข้อมูลแสดงกิจกรรมด้านสหกิจศึกษาและการศึกษาเชิงบูรณาการกับการทำงาน (CWIE) แยกตามประเภทกิจกรรม<br>
								2. ข้อมูล ณ วันที่ <?php echo date('d/m/Y'); ?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!--Activity CWIE End-->

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