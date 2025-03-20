<?php
session_start();
include_once('admin/connect.php');
?>
<!DOCTYPE html>
<html lang="th">

<head>
	<meta charset="utf-8">
	<title>จำนวนคณาจารย์ที่อบรมคณาจารย์นิเทศสหกิจศึกษา | CWIE LRU</title>
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

		.major-name {
			padding-left: 20px;
		}

		.course-table {
			margin-bottom: 30px;
		}

		.page-title {
			margin-bottom: 30px;
		}

		.faculty-row {
			background-color: #f2f2f2;
			font-weight: bold;
		}

		.major-row {
			background-color: #ffffff;
		}

		.faculty-total {
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
				<h1 class="d-none">จำนวนคณาจารย์ที่อบรมคณาจารย์นิเทศสหกิจศึกษา</h1>
			</div>
		</section>
		<!-- end main-content -->

		<!--Course CWIE Start-->
		<section class="blog-details">
			<div class="container">
				<div class="row">
					<div class="col-xl-12 col-lg-12">
						<div class="table-responsive course-table">
							<h3 class="text-center mb-4">จำนวนคณาจารย์ที่อบรมคณาจารย์นิเทศสหกิจศึกษา</h3>

							<table class="table table-bordered">
								<thead>
									<tr>
										<th class="align-middle" style="width: 5%">ลำดับที่</th>
										<th class="align-middle" style="width: 45%">คณะ</th>
										<th class="align-middle" style="width: 16%">จำนวนคณาจารย์ทั้งหมด</th>
										<th class="align-middle" style="width: 16%">จำนวนคณาจารย์ที่อบรม</th>
										<th class="align-middle" style="width: 16%">คิดเป็นร้อยละ (%)</th>
									</tr>
								</thead>
								<tbody>
									<?php
									// ข้อมูลจำนวนคณาจารย์ทั้งหมดตามไฟล์ Word
									$total_faculty_data = [
										'01' => 63,  // คณะวิทยาการจัดการ
										'02' => 90,  // คณะวิทยาศาสตร์และเทคโนโลยี
										'03' => 26,  // คณะเทคโนโลยีอุตสาหกรรม
										'04' => 83,  // คณะมนุษยศาสตร์และสังคมศาสตร์
									];

									// ดึงข้อมูลคณะทั้งหมด
									$sql_faculty = "SELECT fid, faculty FROM faculty WHERE fid != '05' ORDER BY id ASC";
									$result_faculty = $conn->query($sql_faculty);

									if ($result_faculty && $result_faculty->num_rows > 0) {
										$counter = 1;
										$grand_total_all = 0;
										$grand_total_trained = 0;

										while ($faculty_row = $result_faculty->fetch_assoc()) {
											$faculty_id = $faculty_row['fid'];
											$faculty_name = $faculty_row['faculty'];

											// ข้อมูลจำนวนคณาจารย์ทั้งหมดจากไฟล์ Word
											$total_faculty = $total_faculty_data[$faculty_id] ?? 0;

											// นับจำนวนคณาจารย์ที่อบรมจากตาราง num_tea_cwie
											$sql_count = "SELECT COUNT(*) as total FROM num_tea_cwie WHERE faculty_id = '$faculty_id'";
											$result_count = $conn->query($sql_count);
											$row_count = $result_count->fetch_assoc();
											$total_trained = $row_count['total'] ?? 0;

											// คำนวณร้อยละ
											$percentage = $total_faculty > 0 ? number_format(($total_trained / $total_faculty) * 100, 2) : 0;

											// สะสมผลรวมทั้งหมด
											$grand_total_all += $total_faculty;
											$grand_total_trained += $total_trained;

											echo '<tr>
												<td class="text-center">' . $counter . '</td>
												<td>' . $faculty_name . '</td>
												<td class="text-center">' . ($total_faculty > 0 ? $total_faculty : '-') . '</td>
												<td class="text-center">' . ($total_trained > 0 ? $total_trained : '-') . '</td>
												<td class="text-center">' . ($percentage > 0 ? $percentage : '-') . '</td>
											</tr>';

											$counter++;
										}

										// คำนวณร้อยละสำหรับผลรวมทั้งหมด
										$grand_percentage = $grand_total_all > 0 ? number_format(($grand_total_trained / $grand_total_all) * 100, 2) : 0;

										// แสดงแถวสรุปผลรวม
										echo '<tr class="table-secondary">
											<td colspan="2" class="text-center"><strong>รวมทั้งหมด</strong></td>
											<td class="text-center"><strong>' . ($grand_total_all > 0 ? $grand_total_all : '-') . '</strong></td>
											<td class="text-center"><strong>' . ($grand_total_trained > 0 ? $grand_total_trained : '-') . '</strong></td>
											<td class="text-center"><strong>' . ($grand_percentage > 0 ? $grand_percentage : '-') . '</strong></td>
										</tr>';
									} else {
										echo '<tr><td colspan="5" class="text-center">ไม่พบข้อมูลคณะ</td></tr>';
									}
									?>
								</tbody>
							</table>
						</div>

						<div class="mb-5">
							<h5>หมายเหตุ:</h5>
							<p>
								1. ข้อมูลแสดงจำนวนคณาจารย์ที่ผ่านการอบรมคณาจารย์นิเทศสหกิจศึกษาและการศึกษาเชิงบูรณาการกับการทำงาน<br>
								2. จำนวนคณาจารย์ที่อบรม หมายถึง คณาจารย์ที่ผ่านการอบรมหลักสูตรคณาจารย์นิเทศสหกิจศึกษาและการศึกษาเชิงบูรณาการกับการทำงาน จากสมาคมสหกิจศึกษาไทย<br>
								3. ร้อยละ คำนวณจาก (จำนวนคณาจารย์ที่อบรม / จำนวนคณาจารย์ทั้งหมด) × 100<br>
								4. ข้อมูล ณ วันที่ <?php echo date('d/m/Y'); ?>
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