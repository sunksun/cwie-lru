<?php
session_start();
include_once('admin/connect.php');
?>
<!DOCTYPE html>
<html lang="th">

<head>
	<meta charset="utf-8">
	<title>ข้อมูลนักศึกษา CWIE และการได้งานทำ | CWIE LRU</title>
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

		.faculty-total {
			font-weight: bold;
		}

		.table-container {
			margin-bottom: 50px;
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
				<h1 class="d-none">ข้อมูลนักศึกษา CWIE และการได้งานทำ</h1>
			</div>
		</section>
		<!-- end main-content -->

		<!--Course CWIE Start-->
		<section class="blog-details">
			<div class="container">
				<div class="row">
					<div class="col-xl-12 col-lg-12">
						<!-- ตาราง MOU -->
						<div class="table-responsive course-table table-container">
							<h3 class="text-center mb-4">จำนวนการทำบันทึกข้อตกลงระหว่างองค์กรกับคณะ (MOU)</h3>

							<table class="table table-bordered">
								<thead>
									<tr>
										<th class="align-middle" style="width: 5%">ลำดับ</th>
										<th class="align-middle" style="width: 40%">คณะ</th>
										<th class="align-middle" style="width: 10%">2564</th>
										<th class="align-middle" style="width: 10%">2565</th>
										<th class="align-middle" style="width: 10%">2566</th>
										<th class="align-middle" style="width: 10%">2567</th>
										<th class="align-middle" style="width: 10%">รวม</th>
										<th class="align-middle" style="width: 10%">หมายเหตุ</th>
									</tr>
								</thead>
								<tbody>
									<?php
									// ดึงข้อมูลคณะทั้งหมด
									$sql_faculty = "SELECT fid, faculty FROM faculty ORDER BY id ASC";
									$result_faculty = $conn->query($sql_faculty);

									if ($result_faculty && $result_faculty->num_rows > 0) { // ถ้ามีข้อมูล
										$counter = 1;
										$total_2564 = 0;
										$total_2565 = 0;
										$total_2566 = 0;
										$total_2567 = 0;
										$total_all = 0;

										while ($faculty_row = $result_faculty->fetch_assoc()) {
											$faculty_id = $faculty_row['fid'];
											$faculty_name = $faculty_row['faculty'];

											// คำนวณจำนวน MOU ในแต่ละปีสำหรับแต่ละคณะโดยดูจากคอลัมน์ year
											$sql = "SELECT 
												COUNT(CASE WHEN year LIKE '%/2564' THEN 1 END) as count_2564,
												COUNT(CASE WHEN year LIKE '%/2565' THEN 1 END) as count_2565,
												COUNT(CASE WHEN year LIKE '%/2566' THEN 1 END) as count_2566,
												COUNT(CASE WHEN year LIKE '%/2567' THEN 1 END) as count_2567
												FROM organization_mou 
												WHERE faculty_id = '$faculty_id'";
											$result = $conn->query($sql);
											$row = $result->fetch_assoc();

											$count_2564 = $row['count_2564'] ?: 0;
											$count_2565 = $row['count_2565'] ?: 0;
											$count_2566 = $row['count_2566'] ?: 0;
											$count_2567 = $row['count_2567'] ?: 0;

											// คำนวณผลรวมแต่ละแถว
											$row_total = $count_2564 + $count_2565 + $count_2566 + $count_2567;

											// สะสมผลรวมทั้งหมด
											$total_2564 += $count_2564;
											$total_2565 += $count_2565;
											$total_2566 += $count_2566;
											$total_2567 += $count_2567;
											$total_all += $row_total;

											echo '<tr>
												<td class="text-center">' . $counter . '</td>
												<td>' . $faculty_name . '</td>
												<td class="text-center">' . ($count_2564 > 0 ? $count_2564 : '-') . '</td>
												<td class="text-center">' . ($count_2565 > 0 ? $count_2565 : '-') . '</td>
												<td class="text-center">' . ($count_2566 > 0 ? $count_2566 : '-') . '</td>
												<td class="text-center">' . ($count_2567 > 0 ? $count_2567 : '-') . '</td>
												<td class="text-center">' . ($row_total > 0 ? $row_total : '-') . '</td>
												<td></td>
											</tr>';

											$counter++;
										}

										// แสดงแถวสรุปผลรวม
										echo '<tr class="table-secondary">
											<td colspan="2" class="text-center"><strong>รวม</strong></td>
											<td class="text-center"><strong>' . ($total_2564 > 0 ? $total_2564 : '-') . '</strong></td>
											<td class="text-center"><strong>' . ($total_2565 > 0 ? $total_2565 : '-') . '</strong></td>
											<td class="text-center"><strong>' . ($total_2566 > 0 ? $total_2566 : '-') . '</strong></td>
											<td class="text-center"><strong>' . ($total_2567 > 0 ? $total_2567 : '-') . '</strong></td>
											<td class="text-center"><strong>' . ($total_all > 0 ? $total_all : '-') . '</strong></td>
											<td></td>
										</tr>';
									} else { // ถ้าไม่มีข้อมูล
										echo '<tr><td colspan="8" class="text-center">ไม่พบข้อมูลการทำบันทึกข้อตกลง</td></tr>';
									}
									?>
								</tbody>
							</table>
						</div>

						<!-- ตารางข้อมูลนักศึกษา CWIE และการได้งานทำ -->
						<div class="table-responsive course-table table-container">
							<h3 class="text-center mb-4">นักศึกษาสหกิจศึกษา (CWIE)<br>และการได้งานทำกับสถานประกอบการ</h3>

							<table class="table table-bordered">
								<thead>
									<tr>
										<th rowspan="2" class="align-middle" style="width: 5%">ลำดับที่</th>
										<th rowspan="2" class="align-middle" style="width: 35%">คณะ</th>
										<th rowspan="2" class="align-middle" style="width: 12%">จำนวนบัณฑิต CWIE<br>(ปีการศึกษา 2/2567)</th>
										<th rowspan="2" class="align-middle" style="width: 12%">จำนวนบัณฑิต CWIE<br>ที่ได้งานทำ<br>(ปีการศึกษา2/2567)</th>
										<th rowspan="2" class="align-middle" style="width: 12%">จำนวนบัณฑิต CWIE<br>ที่ได้งานทำในสถานประกอบการ<br>(ปีการศึกษา2/2567)</th>
										<th rowspan="2" class="align-middle" style="width: 10%">คิดเป็น<br>ร้อยละ</th>
									</tr>
								</thead>
								<tbody>
									<?php
									// ดึงข้อมูลคณะทั้งหมด
									$sql_faculty = "SELECT fid, faculty FROM faculty ORDER BY id ASC";
									$result_faculty = $conn->query($sql_faculty);

									if ($result_faculty && $result_faculty->num_rows > 0) {
										$faculty_counter = 1;
										$total_num_pundit = 0;
										$total_num_pundit_job = 0;
										$total_num_pundit_job_work = 0;

										while ($faculty_row = $result_faculty->fetch_assoc()) {
											$faculty_id = $faculty_row['fid'];
											$faculty_name = $faculty_row['faculty'];

											// คำนวณยอดรวมสำหรับแต่ละคณะ
											$sql_sum = "SELECT 
												SUM(num_pundit) as total_pundit, 
												SUM(num_pundit_job) as total_pundit_job, 
												SUM(num_pundit_job_work) as total_pundit_job_work
												FROM num_stu_cwie 
												WHERE faculty_id = '$faculty_id' AND year = '2/2567'";
											$result_sum = $conn->query($sql_sum);
											$row_sum = $result_sum->fetch_assoc();

											$faculty_num_pundit = $row_sum['total_pundit'] ?: 0;
											$faculty_num_pundit_job = $row_sum['total_pundit_job'] ?: 0;
											$faculty_num_pundit_job_work = $row_sum['total_pundit_job_work'] ?: 0;

											// คำนวณร้อยละ
											$faculty_percentage = ($faculty_num_pundit > 0) ? number_format(($faculty_num_pundit_job / $faculty_num_pundit) * 100, 2) : 0;

											// เพิ่มยอดรวมทั้งหมด
											$total_num_pundit += $faculty_num_pundit;
											$total_num_pundit_job += $faculty_num_pundit_job;
											$total_num_pundit_job_work += $faculty_num_pundit_job_work;

											// แสดงแถวของคณะ
											echo '<tr class="faculty-row">
												<td class="text-center">' . $faculty_counter . '</td>
												<td><strong>' . $faculty_name . '</strong></td>
												<td class="text-center">' . ($faculty_num_pundit > 0 ? $faculty_num_pundit : '-') . '</td>
												<td class="text-center">' . ($faculty_num_pundit_job > 0 ? $faculty_num_pundit_job : '-') . '</td>
												<td class="text-center">' . ($faculty_num_pundit_job_work > 0 ? $faculty_num_pundit_job_work : '-') . '</td>
												<td class="text-center">' . ($faculty_percentage > 0 ? $faculty_percentage : '-') . '</td>
											</tr>';

											// ดึงข้อมูลสาขาวิชาตามคณะ
											$sql_major = "SELECT major, num_pundit, num_pundit_job, num_pundit_job_work 
												FROM num_stu_cwie 
												WHERE faculty_id = '$faculty_id' AND year = '2/2567'
												ORDER BY id ASC";
											$result_major = $conn->query($sql_major);

											if ($result_major && $result_major->num_rows > 0) {
												$major_counter = 1;

												while ($major_row = $result_major->fetch_assoc()) {
													$major = $major_row['major'];
													$num_pundit = $major_row['num_pundit'] ?: 0;
													$num_pundit_job = $major_row['num_pundit_job'] ?: 0;
													$num_pundit_job_work = $major_row['num_pundit_job_work'] ?: 0;

													// คำนวณร้อยละสำหรับสาขาวิชา
													$major_percentage = ($num_pundit > 0) ? number_format(($num_pundit_job / $num_pundit) * 100, 2) : 0;

													// ดึงชื่อสาขาวิชาจากข้อความเต็ม (หลังคำว่า --)
													$major_name = $major;
													if (strpos($major, '--') !== false) {
														$parts = explode('--', $major);
														$major_name = isset($parts[1]) ? $parts[1] : $major;
													}

													echo '<tr class="major-row">
														<td></td>
														<td class="major-name">' . $major_counter . '. ' . htmlspecialchars($major_name) . '</td>
														<td class="text-center">' . ($num_pundit > 0 ? $num_pundit : '-') . '</td>
														<td class="text-center">' . ($num_pundit_job > 0 ? $num_pundit_job : '-') . '</td>
														<td class="text-center">' . ($num_pundit_job_work > 0 ? $num_pundit_job_work : '-') . '</td>
														<td class="text-center">' . ($major_percentage > 0 ? $major_percentage : '-') . '</td>
													</tr>';

													$major_counter++;
												}
											} else {
												echo '<tr>
													<td></td>
													<td colspan="5" class="text-center">ไม่พบข้อมูลสาขาวิชา</td>
												</tr>';
											}

											$faculty_counter++;
										}

										// คำนวณร้อยละสำหรับยอดรวมทั้งหมด
										$total_percentage = ($total_num_pundit > 0) ? number_format(($total_num_pundit_job / $total_num_pundit) * 100, 2) : 0;

										// รวมยอดทั้งหมด
										echo '<tr class="table-secondary">
											<td colspan="2" class="text-center"><strong>รวมทั้งหมด</strong></td>
											<td class="text-center"><strong>' . ($total_num_pundit > 0 ? $total_num_pundit : '-') . '</strong></td>
											<td class="text-center"><strong>' . ($total_num_pundit_job > 0 ? $total_num_pundit_job : '-') . '</strong></td>
											<td class="text-center"><strong>' . ($total_num_pundit_job_work > 0 ? $total_num_pundit_job_work : '-') . '</strong></td>
											<td class="text-center"><strong>' . ($total_percentage > 0 ? $total_percentage : '-') . '</strong></td>
										</tr>';
									} else {
										echo '<tr><td colspan="6" class="text-center">ไม่พบข้อมูลคณะ</td></tr>';
									}
									?>
								</tbody>
							</table>
						</div>

						<div class="mb-5">
							<h5>หมายเหตุ:</h5>
							<p>
								1. ข้อมูลแสดงจำนวนการทำบันทึกข้อตกลงระหว่างองค์กรกับคณะ (MOU) แยกตามปี พ.ศ.<br>
								2. ข้อมูลแสดงจำนวนบัณฑิต CWIE และการได้งานทำหลังสำเร็จการศึกษา<br>
								3. ร้อยละ คำนวณจาก (จำนวนบัณฑิต CWIE ที่ได้งานทำ / จำนวนบัณฑิต CWIE) × 100<br>
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