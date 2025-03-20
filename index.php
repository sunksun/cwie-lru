<?php
session_start();
include_once('admin/connect.php');

?>
<!DOCTYPE html>
<html lang="th">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<title>CWIE | สหกิจศึกษามหาวิทยาลัยราชภัฏเลย</title>
	<!-- Favicon -->
	<link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
	<link rel="icon" href="images/favicon.png" type="image/x-icon">

	<!-- Stylesheets -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<!-- Revolution Slider CSS -->
	<link href="plugins/revolution/css/settings.css" rel="stylesheet" type="text/css">
	<link href="plugins/revolution/css/layers.css" rel="stylesheet" type="text/css">
	<link href="plugins/revolution/css/navigation.css" rel="stylesheet" type="text/css">
	<!-- Main Stylesheets -->
	<link href="css/style.css" rel="stylesheet">
	<link href="css/responsive.css" rel="stylesheet">

	<!--[if lt IE 9]><script src="js/html5shiv.js"></script><![endif]-->
	<!--[if lt IE 9]><script src="js/respond.js"></script><![endif]-->

	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>
	<div class="page-wrapper">
		<!-- Preloader -->
		<div class="preloader"></div>

		<!-- Main Header-->
		<?php include_once 'mainHeader.php'; ?>
		<!--End Main Header -->

		<!-- News Section ข่าวประชาสัมพันธ์ -->
		<section class="news-section">
			<div class="auto-container">
				<div class="sec-title text-center">
					<span class="sub-title" style="font-size: 40px;">ข่าวประชาสัมพันธ์</span>
				</div>
				<div class="row">
					<?php
					// ดึงข่าวล่าสุด 3 ข่าว
					$sql = "SELECT id, title, img FROM `news` ORDER BY id DESC LIMIT 3";
					$result = $conn->query($sql);

					if ($result && $result->num_rows > 0) {
						$delay = 0;
						while ($row = $result->fetch_assoc()) {
							$delayAttr = $delay > 0 ? 'data-wow-delay="' . $delay * 300 . 'ms"' : '';
					?>
							<!-- News Block -->
							<div class="news-block col-lg-4 col-md-6 col-sm-12 wow fadeInUp" <?php echo $delayAttr; ?>>
								<div class="inner-box">
									<div class="image-box">
										<figure class="image">
											<a href="news-details.php?newid=<?php echo htmlspecialchars($row["id"]); ?>">
												<img src="./admin/img_news/370x360_<?php echo htmlspecialchars($row["img"]); ?>" alt="<?php echo htmlspecialchars($row["title"]); ?>">
											</a>
										</figure>
									</div>
									<div class="content-box">
										<div class="content">
											<h6 class="title">
												<a href="news-details.php?newid=<?php echo htmlspecialchars($row["id"]); ?>">
													<?php echo htmlspecialchars($row["title"]); ?>
												</a>
											</h6>
										</div>
									</div>
								</div>
							</div>
					<?php
							$delay++;
						}
					}
					?>
				</div>
			</div>
		</section>
		<!--End News Section -->

		<!-- Courses Section โครงการและกิจกรรม -->
		<section class="courses-section">
			<div class="auto-container">
				<div class="sec-title text-center">
					<span class="sub-title" style="font-size: 40px;">โครงการและกิจกรรม</span>
				</div>
				<div class="anim-icons">
					<span class="icon icon-e wow zoomIn"></span>
				</div>
				<div class="carousel-outer">
					<!-- Courses Carousel -->
					<div class="courses-carousel owl-carousel owl-theme default-nav">
						<?php
						$sql = "SELECT id, activity_name, activity_type, activity_date, filename FROM activity ORDER BY date_regis DESC LIMIT 4";
						$result = $conn->query($sql);

						if ($result && $result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
								// ตัดข้อความหากยาวเกินไป
								$title = $row["activity_name"];
								if (mb_strlen($title, 'UTF-8') > 40) {
									$title = mb_substr($title, 0, 40, 'UTF-8') . '...';
								}
						?>
								<!-- Course Block -->
								<div class="course-block">
									<div class="inner-box">
										<div class="image-box">
											<figure class="image">
												<a href="activity-details.php?actid=<?php echo htmlspecialchars($row["id"]); ?>">
													<img src="admin/img_act/270x270_<?php echo htmlspecialchars($row["filename"]); ?>" alt="<?php echo htmlspecialchars($title); ?>">
												</a>
											</figure>
											<div class="value"><?php echo htmlspecialchars($row["activity_type"]); ?></div>
										</div>
										<div class="content-box">
											<ul class="course-info">
												<li><i class="fa fa-book"></i><?php echo htmlspecialchars($row["activity_date"]); ?></li>
											</ul>
											<h5 class="title">
												<a href="activity-details.php?actid=<?php echo htmlspecialchars($row["id"]); ?>">
													<?php echo htmlspecialchars($title); ?>
												</a>
											</h5>
										</div>
									</div>
								</div>
						<?php
							}
						}
						?>
					</div>
				</div>

				<div class="bottom-text">
					<div class="content">
						<strong>ภาพรวมสหกิจศึกษา (CWIE) มหาวิทยาลัยราชภัฏเลย </strong>
					</div>
				</div>
				<!-- ส่วนที่จะแสดงกราฟ -->
				<div class="container mt-4">
					<div class="text-center mb-3">
						<select id="year-selector" class="form-control d-inline-block" style="width: auto;">
							<option value="1/2566">ภาคเรียนที่ 1/2566</option>
							<option value="2/2566">ภาคเรียนที่ 2/2566</option>
							<option value="1/2567">ภาคเรียนที่ 1/2567</option>
							<option value="2/2567" selected>ภาคเรียนที่ 2/2567</option>
						</select>
					</div>

					<canvas id="cwieChart" width="800" height="400"></canvas>

					<!-- ตารางข้อมูล -->
					<div class="table-responsive mt-4">
						<table class="table table-bordered">
							<!-- ส่วนหัวตาราง -->
							<thead>
								<tr class="bg-light">
									<th>คณะ</th>
									<th class="text-center">นักศึกษาฝึกประสบการณ์วิชาชีพ</th>
									<th class="text-center">นักศึกษาสหกิจศึกษา</th>
									<th class="text-center">บัณฑิต CWIE</th>
									<th class="text-center">บัณฑิต CWIE ที่ได้งานทำ</th>
									<th class="text-center">บัณฑิต CWIE ที่ได้งานทำในสถานประกอบการ</th>
								</tr>
							</thead>
							<tbody>
								<!-- PHP loop เพื่อแสดงข้อมูล -->
								<?php
								// ดึงข้อมูลจากฐานข้อมูล - ตัวอย่างโค้ด
								$year = "2/2567"; // เริ่มต้นใช้ปีล่าสุด
								if (isset($_GET['year'])) {
									$year = $_GET['year'];
								}

								$faculties = [
									['id' => '01', 'name' => 'คณะวิทยาการจัดการ'],
									['id' => '02', 'name' => 'คณะวิทยาศาสตร์และเทคโนโลยี'],
									['id' => '03', 'name' => 'คณะเทคโนโลยีอุตสาหกรรม'],
									['id' => '04', 'name' => 'คณะมนุษยศาสตร์และสังคมศาสตร์'],
									['id' => '05', 'name' => 'คณะครุศาสตร์']
								];

								$totals = ['practice' => 0, 'cwie' => 0, 'graduate' => 0, 'employed' => 0, 'employed_org' => 0];

								foreach ($faculties as $faculty) {
									// ในสถานการณ์จริง คุณควรใช้ prepared statement
									$sql = "SELECT 
									SUM(num_practice) as practice, 
									SUM(num_cwie) as cwie, 
									SUM(num_pundit) as graduate, 
									SUM(num_pundit_job) as employed, 
									SUM(num_pundit_job_work) as employed_org 
									FROM num_stu_cwie 
									WHERE faculty_id = '{$faculty['id']}' AND year = '$year'";

									$result = $conn->query($sql);
									$data = $result->fetch_assoc();

									// เพิ่มค่าในผลรวม
									$totals['practice'] += $data['practice'] ?? 0;
									$totals['cwie'] += $data['cwie'] ?? 0;
									$totals['graduate'] += $data['graduate'] ?? 0;
									$totals['employed'] += $data['employed'] ?? 0;
									$totals['employed_org'] += $data['employed_org'] ?? 0;

									echo "<tr>";
									echo "<td>{$faculty['name']}</td>";
									// แสดงเครื่องหมาย - แทนค่า 0
									echo "<td class='text-center'>" . (($data['practice'] ?? 0) > 0 ? ($data['practice'] ?? 0) : "-") . "</td>";
									echo "<td class='text-center'>" . (($data['cwie'] ?? 0) > 0 ? ($data['cwie'] ?? 0) : "-") . "</td>";
									echo "<td class='text-center'>" . (($data['graduate'] ?? 0) > 0 ? ($data['graduate'] ?? 0) : "-") . "</td>";
									echo "<td class='text-center'>" . (($data['employed'] ?? 0) > 0 ? ($data['employed'] ?? 0) : "-") . "</td>";
									echo "<td class='text-center'>" . (($data['employed_org'] ?? 0) > 0 ? ($data['employed_org'] ?? 0) : "-") . "</td>";
									echo "</tr>";
								}
								?>

								<!-- แถวสรุปผลรวม -->
								<tr class="bg-light font-weight-bold">
									<td>รวม</td>
									<td class="text-center"><?php echo ($totals['practice'] > 0) ? $totals['practice'] : "-"; ?></td>
									<td class="text-center"><?php echo ($totals['cwie'] > 0) ? $totals['cwie'] : "-"; ?></td>
									<td class="text-center"><?php echo ($totals['graduate'] > 0) ? $totals['graduate'] : "-"; ?></td>
									<td class="text-center"><?php echo ($totals['employed'] > 0) ? $totals['employed'] : "-"; ?></td>
									<td class="text-center"><?php echo ($totals['employed_org'] > 0) ? $totals['employed_org'] : "-"; ?></td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>

				<script>
					document.addEventListener('DOMContentLoaded', function() {
						// Set up year selector
						const yearSelector = document.getElementById('year-selector');
						const currentYear = yearSelector.value;

						// Fetch faculty data from PHP variables
						const facultyNames = <?php echo json_encode(array_map(function ($f) {
													return $f['name'];
												}, $faculties)); ?>;

						// Set up data arrays for each metric
						const practiceData = <?php echo json_encode(array_map(function ($f) use ($conn, $year) {
													$sql = "SELECT SUM(num_practice) as practice FROM num_stu_cwie WHERE faculty_id = '{$f['id']}' AND year = '$year'";
													$result = $conn->query($sql);
													$data = $result->fetch_assoc();
													return intval($data['practice'] ?? 0);
												}, $faculties)); ?>;

						const cwieData = <?php echo json_encode(array_map(function ($f) use ($conn, $year) {
												$sql = "SELECT SUM(num_cwie) as cwie FROM num_stu_cwie WHERE faculty_id = '{$f['id']}' AND year = '$year'";
												$result = $conn->query($sql);
												$data = $result->fetch_assoc();
												return intval($data['cwie'] ?? 0);
											}, $faculties)); ?>;

						const graduateData = <?php echo json_encode(array_map(function ($f) use ($conn, $year) {
													$sql = "SELECT SUM(num_pundit) as graduate FROM num_stu_cwie WHERE faculty_id = '{$f['id']}' AND year = '$year'";
													$result = $conn->query($sql);
													$data = $result->fetch_assoc();
													return intval($data['graduate'] ?? 0);
												}, $faculties)); ?>;

						const employedData = <?php echo json_encode(array_map(function ($f) use ($conn, $year) {
													$sql = "SELECT SUM(num_pundit_job) as employed FROM num_stu_cwie WHERE faculty_id = '{$f['id']}' AND year = '$year'";
													$result = $conn->query($sql);
													$data = $result->fetch_assoc();
													return intval($data['employed'] ?? 0);
												}, $faculties)); ?>;

						const employedOrgData = <?php echo json_encode(array_map(function ($f) use ($conn, $year) {
													$sql = "SELECT SUM(num_pundit_job_work) as employed_org FROM num_stu_cwie WHERE faculty_id = '{$f['id']}' AND year = '$year'";
													$result = $conn->query($sql);
													$data = $result->fetch_assoc();
													return intval($data['employed_org'] ?? 0);
												}, $faculties)); ?>;

						// Create chart
						const ctx = document.getElementById('cwieChart').getContext('2d');
						const chart = new Chart(ctx, {
							type: 'bar',
							data: {
								labels: facultyNames,
								datasets: [{
										label: 'นักศึกษาฝึกประสบการณ์วิชาชีพ',
										data: practiceData,
										backgroundColor: '#4bc0c0',
										borderColor: '#3aa7a7',
										borderWidth: 1
									},
									{
										label: 'นักศึกษาสหกิจศึกษา',
										data: cwieData,
										backgroundColor: '#ff9f40',
										borderColor: '#e88c30',
										borderWidth: 1
									},
									{
										label: 'บัณฑิต CWIE',
										data: graduateData,
										backgroundColor: '#36a2eb',
										borderColor: '#2993d8',
										borderWidth: 1
									},
									{
										label: 'บัณฑิต CWIE ที่ได้งานทำ',
										data: employedData,
										backgroundColor: '#9966ff',
										borderColor: '#8a57e0',
										borderWidth: 1
									},
									{
										label: 'บัณฑิต CWIE ที่ได้งานทำในสถานประกอบการ',
										data: employedOrgData,
										backgroundColor: '#ff6384',
										borderColor: '#e95375',
										borderWidth: 1
									}
								]
							},
							options: {
								responsive: true,
								plugins: {
									legend: {
										position: 'top',
									},
									title: {
										display: true,
										text: 'ข้อมูลสหกิจศึกษา (CWIE) ประจำภาคเรียนที่ ' + currentYear
									},
									tooltip: {
										mode: 'index',
										intersect: false,
									}
								},
								scales: {
									x: {
										title: {
											display: true,
											text: 'คณะ'
										}
									},
									y: {
										beginAtZero: true,
										title: {
											display: true,
											text: 'จำนวน (คน)'
										}
									}
								}
							}
						});

						// Handle year selection change
						yearSelector.addEventListener('change', function() {
							window.location.href = 'index.php?year=' + this.value;
						});
					});
					console.log('Chart.js version:', Chart.version);
				</script>

			</div>
		</section>
		<!-- End Courses Section-->

		<!-- Features Section -->
		<section class="features-section">
			<div class="auto-container">
				<div class="row">
					<!-- Feature Block -->
					<div class="feature-block col-lg-3 col-md-6 col-sm-6 wow fadeInUp">
						<div class="inner-box">
							<i class="icon flaticon-online-learning"></i>
							<h6 class="title">University-Workplace Engagement</h6>
						</div>
					</div>

					<!-- Feature Block -->
					<div class="feature-block col-lg-3 col-md-6 col-sm-6 wow fadeInUp" data-wow-delay="400ms">
						<div class="inner-box">
							<i class="icon flaticon-elearning"></i>
							<h5 class="title">Co-design Curriculum</h5>
						</div>
					</div>

					<!-- Feature Block -->
					<div class="feature-block col-lg-3 col-md-6 col-sm-6 wow fadeInUp" data-wow-delay="800ms">
						<div class="inner-box">
							<i class="icon flaticon-web-2"></i>
							<h5 class="title">Competency-based Education</h5>
						</div>
					</div>

					<!-- Feature Block -->
					<div class="feature-block col-lg-3 col-md-6 col-sm-6 wow fadeInUp" data-wow-delay="1200ms">
						<div class="inner-box">
							<i class="icon flaticon-users"></i>
							<h5 class="title">Experiential-based Learning</h5>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End Features Section-->

		<!-- Categories Section -->
		<section class="categories-section-current">
			<div class="auto-container">
				<div class="anim-icons">
					<span class="icon icon-group-1 bounce-y"></span>
					<span class="icon icon-group-2 bounce-y"></span>
				</div>

				<div class="sec-title text-center">
					<span class="sub-title" style="font-size: 45px;">คณะที่เกี่ยวข้อง</span>
				</div>

				<div class="row justify-content-center">
					<!-- Category Block -->
					<div class="category-block-current col-xl-2 col-lg-3 col-md-4 col-sm-6">
						<div class="inner-box">
							<div class="icon-box">
								<i class="icon flaticon-student-2"></i>
							</div>
							<h6 class="title"><a href="https://manage.lru.ac.th/th/" target="_blank">คณะวิทยาการจัดการ</a></h6>
						</div>
					</div>

					<!-- Category Block -->
					<div class="category-block-current col-xl-2 col-lg-3 col-md-4 col-sm-6">
						<div class="inner-box">
							<div class="icon-box">
								<i class="icon flaticon-stationary"></i>
							</div>
							<h6 class="title"><a href="https://www.sci.lru.ac.th/th/" target="_blank">คณะวิทยาศาสตร์และเทคโนโลยี</a></h6>
						</div>
					</div>

					<!-- Category Block -->
					<div class="category-block-current col-xl-2 col-lg-3 col-md-4 col-sm-6">
						<div class="inner-box">
							<div class="icon-box">
								<i class="icon flaticon-online-learning"></i>
							</div>
							<h6 class="title"><a href="https://idtech.lru.ac.th/th/" target="_blank">คณะเทคโนโลยีอุตสาหกรรม</a></h6>
						</div>
					</div>

					<!-- Category Block -->
					<div class="category-block-current col-xl-2 col-lg-3 col-md-4 col-sm-6">
						<div class="inner-box">
							<div class="icon-box">
								<i class="icon flaticon-study"></i>
							</div>
							<h6 class="title"><a href="https://human.lru.ac.th/th/" target="_blank">คณะมนุษยศาสตร์และสังคมศาสตร์</a></h6>
						</div>
					</div>

					<!-- Category Block -->
					<div class="category-block-current col-xl-2 col-lg-3 col-md-4 col-sm-6">
						<div class="inner-box">
							<div class="icon-box">
								<i class="icon flaticon-pie-chart"></i>
							</div>
							<h6 class="title"><a href="https://edu.lru.ac.th/th/" target="_blank">คณะครุศาสตร์</a></h6>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End Product Categories -->

		<!-- Team Section -->
		<section id="team-section" class="team-section">
			<div class="auto-container">
				<div class="sec-title text-center">
					<span class="sub-title" style="font-size: 40px;">อาจารย์นิเทศสหกิจศึกษา</span>
				</div>
				<div class="row">
					<?php
					$sql = "SELECT name_tea_cwie, course, filename FROM `num_tea_cwie` ORDER BY `id` DESC LIMIT 8";
					$result = $conn->query($sql);

					if ($result && $result->num_rows > 0) {
						while ($row = $result->fetch_assoc()) {
							// ค้นหาสาขาวิชาจากข้อความ
							$course = $row["course"];
							$substring = '';
							if (strpos($course, 'สาขาวิชา') !== false) {
								$substring = strstr($course, 'สาขาวิชา');
							}
					?>
							<!-- Team block -->
							<div class="team-block col-xl-3 col-lg-6 col-md-6 col-sm-12 wow fadeInUp">
								<div class="inner-box">
									<div class="image-box">
										<figure class="image">
											<?php if (!empty($row["filename"])): ?>
												<a href="#">
													<img src="admin/img_teach/220x220_<?php echo htmlspecialchars($row["filename"]); ?>" alt="<?php echo htmlspecialchars($row["name_tea_cwie"]); ?>">
												</a>
											<?php else: ?>
												<a href="#">
													<img src="admin/img_teach/default-teacher.jpg" alt="<?php echo htmlspecialchars($row["name_tea_cwie"]); ?>">
												</a>
											<?php endif; ?>
										</figure>
										<span class="share-icon fa fa-share-alt"></span>
									</div>
									<div class="info-box">
										<h6 class="name"><a href="#"><?php
																		$name = $row["name_tea_cwie"];
																		// แปลงชื่อตำแหน่งให้เป็นตัวย่อ
																		$name = str_replace('ผู้ช่วยศาสตราจารย์', 'ผศ.', $name);
																		$name = str_replace('รองศาสตราจารย์', 'รศ.', $name);
																		echo htmlspecialchars($name);
																		?></a></h6>
										<span class="designation"><?php echo htmlspecialchars($substring); ?></span>
									</div>
								</div>
							</div>
					<?php
						}
					}
					?>
				</div>
			</div>
		</section>
		<!-- End Team Section -->

		<!-- Call To Action -->
		<section class="call-to-action" style="background-image: url(./images/background/1.jpg)">
			<div class="anim-icons">
				<span class="icon icon-calculator zoom-one"></span>
				<span class="icon icon-pin-clip zoom-one"></span>
				<span class="icon icon-dots"></span>
			</div>
		</section>
		<!-- End Call To Action -->

		<!-- Testimonial Section -->
		<section class="testimonial-section">
			<div class="anim-icons">
				<span class="icon icon-dotted-map-2"></span>
			</div>
			<div class="auto-container">
				<div class="row">
					<!-- Title Column -->
					<div class="title-column col-xl-4 col-lg-5 col-md-12">
						<div class="inner-column">
							<div class="sec-title">
								<span class="sub-title" style="font-size: 45px;">ตำแหน่งงาน</span>
								<h4>CWIE LRU<br>ได้รวบรวมข้อมูลตำแหน่งงานที่เปิดรับสมัครจากสถานประกอบการที่เป็นเครือข่าย</h4>
							</div>
						</div>
					</div>

					<!-- Testimonial Column -->
					<div class="testimonial-column col-xl-8 col-lg-7 col-md-12">
						<div class="carousel-outer">
							<div class="testimonial-carousel owl-carousel owl-theme">
								<?php
								$sql = "SELECT job_title, company, job_des, filename, link FROM `job_cwie` ORDER BY `id` DESC";
								$result = $conn->query($sql);

								if ($result && $result->num_rows > 0) {
									while ($row = $result->fetch_assoc()) {
										$job_des = $row["job_des"];
										// แปลง - เป็น <br> -
										$resStr = str_replace('-', '<br> -', $job_des);
								?>
										<!-- Testimonial Block -->
										<div class="testimonial-block">
											<div class="inner-box">
												<div class="content-box">
													<?php if (!empty($row["filename"])): ?>
														<figure class="thumb">
															<img src="admin/img_job/<?php echo htmlspecialchars($row["filename"]); ?>" alt="<?php echo htmlspecialchars($row["job_title"]); ?>">
														</figure>
													<?php endif; ?>
													<div class="rating">
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
														<i class="fa fa-star"></i>
													</div>
													<div class="text">
														<?php echo $resStr; ?>
													</div>
													<div class="info-box">
														<span class="icon-quote"></span>
														<h4 class="name"><?php echo htmlspecialchars($row["job_title"]); ?></h4>
														<span class="designation"><?php echo htmlspecialchars($row["company"]); ?></span>
													</div>
													<?php if (!empty($row["link"])): ?>
														<div class="btn-box mt-3">
															<a href="<?php echo htmlspecialchars($row["link"]); ?>" target="_blank" class="theme-btn btn-style-one">
																<span class="btn-title">อ่านต่อ</span>
															</a>
														</div>
													<?php endif; ?>
												</div>
											</div>
										</div>
								<?php
									}
								}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- End Testimonial Section -->

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
	<!--Revolution Slider-->
	<script src="plugins/revolution/js/jquery.themepunch.revolution.min.js"></script>
	<script src="plugins/revolution/js/jquery.themepunch.tools.min.js"></script>
	<script src="plugins/revolution/js/extensions/revolution.extension.actions.min.js"></script>
	<script src="plugins/revolution/js/extensions/revolution.extension.carousel.min.js"></script>
	<script src="plugins/revolution/js/extensions/revolution.extension.kenburn.min.js"></script>
	<script src="plugins/revolution/js/extensions/revolution.extension.layeranimation.min.js"></script>
	<script src="plugins/revolution/js/extensions/revolution.extension.migration.min.js"></script>
	<script src="plugins/revolution/js/extensions/revolution.extension.navigation.min.js"></script>
	<script src="plugins/revolution/js/extensions/revolution.extension.parallax.min.js"></script>
	<script src="plugins/revolution/js/extensions/revolution.extension.slideanims.min.js"></script>
	<script src="plugins/revolution/js/extensions/revolution.extension.video.min.js"></script>
	<script src="js/main-slider-script.js"></script>
	<!--Other Scripts-->
	<script src="js/jquery.fancybox.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script src="js/wow.js"></script>
	<script src="js/appear.js"></script>
	<script src="js/jquery.countdown.js"></script>
	<script src="js/select2.min.js"></script>
	<script src="js/swiper.min.js"></script>
	<script src="js/owl.js"></script>
	<script src="js/script.js"></script>
	<script src="https://cdn.linearicons.com/free/1.0.0/svgembedder.min.js"></script>
</body>

</html>