<?php
session_start();
include_once('admin/connect.php');
?>
<!DOCTYPE html>
<html lang="th">

<head>
	<meta charset="utf-8">
	<title>จำนวนสาขาวิชาและจำนวนนักศึกษาที่ออกฝึกในรูปแบบสหกิจศึกษา | CWIE LRU</title>
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
				<h1 class="d-none">จำนวนสาขาวิชาและจำนวนนักศึกษาที่ออกฝึกในรูปแบบสหกิจศึกษา</h1>
			</div>
		</section>
		<!-- end main-content -->

		<!--Course CWIE Start-->
		<section class="blog-details">
			<div class="container">
				<div class="row">
					<div class="col-xl-12 col-lg-12">
						<div class="table-responsive course-table">
							<h3 class="text-center mb-4">จำนวนสาขาวิชาและจำนวนนักศึกษาที่ออกฝึกในรูปแบบสหกิจศึกษา</h3>

							<table class="table table-bordered">
								<thead>
									<tr>
										<th rowspan="2" class="align-middle" style="width: 5%">ลำดับที่</th>
										<th rowspan="2" class="align-middle" style="width: 40%">คณะ</th>
										<th rowspan="2" class="align-middle" style="width: 10%">จำนวนนักศึกษาที่ออกฝึกทั้งหมด</th>
										<th rowspan="2" class="align-middle" style="width: 12%">จำนวนนักศึกษาที่ออกฝึกภาคปกติ</th>
										<th rowspan="2" class="align-middle" style="width: 13%">จำนวนนักศึกษาที่ออกฝึกในรูปแบบสหกิจฯ (CWIE)</th>
										<th rowspan="2" class="align-middle" style="width: 10%">คิดเป็นร้อยละ</th>
										<th rowspan="2" class="align-middle" style="width: 10%">หมายเหตุ</th>
									</tr>
								</thead>
								<tbody>
									<?php
									// ดึงข้อมูลคณะทั้งหมด
									$sql_faculty = "SELECT fid, faculty FROM faculty ORDER BY id ASC";
									$result_faculty = $conn->query($sql_faculty);

									if ($result_faculty && $result_faculty->num_rows > 0) {
										$faculty_counter = 1;

										while ($faculty_row = $result_faculty->fetch_assoc()) {
											$faculty_id = $faculty_row['fid'];
											$faculty_name = $faculty_row['faculty'];

											// คำนวณยอดรวมสำหรับแต่ละคณะ
											$sql_sum = "SELECT 
												SUM(num_practice) as total_practice, 
												SUM(num_cwie) as total_cwie, 
												SUM(num_pundit) as total_pundit, 
												SUM(num_pundit_job) as total_pundit_job, 
												SUM(num_pundit_job_work) as total_pundit_job_work
												FROM num_stu_cwie 
												WHERE faculty_id = '$faculty_id'";
											$result_sum = $conn->query($sql_sum);
											$row_sum = $result_sum->fetch_assoc();

											$total_practice = $row_sum['total_practice'] ?: 0;
											$total_cwie = $row_sum['total_cwie'] ?: 0;
											$total_pundit = $row_sum['total_pundit'] ?: 0;

											// คำนวณร้อยละ
											$percentage = ($total_practice > 0) ? number_format(($total_cwie / $total_practice) * 100, 2) : 0;

											// แสดงแถวของคณะ
											echo '<tr class="faculty-row">
												<td class="text-center">' . $faculty_counter . '</td>
												<td><strong>' . $faculty_name . '</strong></td>
												<td class="text-center">' . $total_practice . '</td>
												<td class="text-center">' . ($total_practice - $total_cwie) . '</td>
												<td class="text-center">' . $total_cwie . '</td>
												<td class="text-center">' . $percentage . '</td>
												<td></td>
											</tr>';

											// ดึงข้อมูลสาขาวิชาตามคณะ
											$sql_major = "SELECT major, num_practice, num_cwie, num_pundit, num_pundit_job, num_pundit_job_work, note 
												FROM num_stu_cwie 
												WHERE faculty_id = '$faculty_id' 
												ORDER BY id ASC";
											$result_major = $conn->query($sql_major);

											if ($result_major && $result_major->num_rows > 0) {
												$major_counter = 1;

												while ($major_row = $result_major->fetch_assoc()) {
													$major = $major_row['major'];
													$num_practice = $major_row['num_practice'] ?: 0;
													$num_cwie = $major_row['num_cwie'] ?: 0;
													$num_pundit = $major_row['num_pundit'] ?: 0;
													$num_pundit_job = $major_row['num_pundit_job'] ?: 0;
													$note = $major_row['note'];

													// คำนวณร้อยละสำหรับสาขาวิชา
													$major_percentage = ($num_practice > 0) ? number_format(($num_cwie / $num_practice) * 100, 2) : 0;

													// ดึงชื่อสาขาวิชาจากข้อความเต็ม (หลังคำว่า --)
													$major_name = $major;
													if (strpos($major, '--') !== false) {
														$parts = explode('--', $major);
														$major_name = isset($parts[1]) ? $parts[1] : $major;
													}

													echo '<tr class="major-row">
														<td></td>
														<td class="major-name">' . $major_counter . '. ' . htmlspecialchars($major_name) . '</td>
														<td class="text-center">' . $num_practice . '</td>
														<td class="text-center">' . ($num_practice - $num_cwie) . '</td>
														<td class="text-center">' . $num_cwie . '</td>
														<td class="text-center">' . $major_percentage . '</td>
														<td>' . htmlspecialchars($note) . '</td>
													</tr>';

													$major_counter++;
												}
											} else {
												echo '<tr>
													<td></td>
													<td colspan="6" class="text-center">ไม่พบข้อมูลสาขาวิชา</td>
												</tr>';
											}

											$faculty_counter++;
										}

										// รวมยอดทั้งหมด
										$sql_total = "SELECT 
											SUM(num_practice) as grand_practice, 
											SUM(num_cwie) as grand_cwie
											FROM num_stu_cwie";
										$result_total = $conn->query($sql_total);
										$row_total = $result_total->fetch_assoc();

										$grand_practice = $row_total['grand_practice'] ?: 0;
										$grand_cwie = $row_total['grand_cwie'] ?: 0;
										$grand_percentage = ($grand_practice > 0) ? number_format(($grand_cwie / $grand_practice) * 100, 2) : 0;

										echo '<tr class="table-secondary">
											<td colspan="2" class="text-center"><strong>รวมทั้งหมด</strong></td>
											<td class="text-center"><strong>' . $grand_practice . '</strong></td>
											<td class="text-center"><strong>' . ($grand_practice - $grand_cwie) . '</strong></td>
											<td class="text-center"><strong>' . $grand_cwie . '</strong></td>
											<td class="text-center"><strong>' . $grand_percentage . '</strong></td>
											<td></td>
										</tr>';
									} else {
										echo '<tr><td colspan="7" class="text-center">ไม่พบข้อมูลคณะ</td></tr>';
									}
									?>
								</tbody>
							</table>
						</div>

						<div class="mb-5">
							<h5>หมายเหตุ:</h5>
							<p>
								1. จำนวนนักศึกษาที่ออกฝึกทั้งหมด หมายถึง จำนวนนักศึกษาทั้งหมดที่ออกฝึกประสบการณ์วิชาชีพ<br>
								2. จำนวนนักศึกษาที่ออกฝึกภาคปกติ หมายถึง จำนวนนักศึกษาที่ออกฝึกงานทั่วไป<br>
								3. จำนวนนักศึกษาที่ออกฝึกในรูปแบบสหกิจฯ (CWIE) หมายถึง จำนวนนักศึกษาที่ออกฝึกตามรูปแบบสหกิจศึกษา<br>
								4. ร้อยละ คำนวณจาก (จำนวนนักศึกษาที่ออกฝึกในรูปแบบสหกิจฯ / จำนวนนักศึกษาที่ออกฝึกทั้งหมด) × 100
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