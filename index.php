<?php
session_start();
include_once('admin/connect.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>CWIE | สหกิจศึกษามหาวิทยาลัยราชภัฏเลย</title>
	<!-- Stylesheets -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="plugins/revolution/css/settings.css" rel="stylesheet" type="text/css"><!-- REVOLUTION SETTINGS STYLES -->
	<link href="plugins/revolution/css/layers.css" rel="stylesheet" type="text/css"><!-- REVOLUTION LAYERS STYLES -->
	<link href="plugins/revolution/css/navigation.css" rel="stylesheet" type="text/css"><!-- REVOLUTION NAVIGATION STYLES -->

	<link href="css/style.css" rel="stylesheet">
	<link href="css/responsive.css" rel="stylesheet">

	<link rel="shortcut icon" href="images/favicon.png" type="image/x-icon">
	<link rel="icon" href="images/favicon.png" type="image/x-icon">

	<!-- Responsive -->
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
	<!--[if lt IE 9]><script src="js/html5shiv.js"></script><![endif]-->
	<!--[if lt IE 9]><script src="js/respond.js"></script><![endif]-->
	<script src="https://cdn.linearicons.com/free/1.0.0/svgembedder.min.js"></script>
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
				<div class="row">
					<!-- News Block -->
					<?php
					$sql = "SELECT * FROM `news` ORDER BY id DESC LIMIT 1 OFFSET 0;";
					$result = $conn->query($sql);
					$result->num_rows > 0;
					$row = $result->fetch_assoc();
					?>
					<div class="news-block col-lg-4 col-md-6 col-sm-12 wow fadeInUp">
						<div class="inner-box">
							<div class="image-box">
								<figure class="image"><a href="#"><img src="./admin/img_news/370x360_<?php echo $row["img"]; ?>" alt=""></a></figure>
							</div>
							<div class="content-box">
								<div class="content">
									<h6 class="title"><a href="news-details.php?newid=<?php echo $row["id"]; ?>"><?php echo $row["title"]; ?></a></h6>
								</div>
							</div>
						</div>
					</div>
					<!-- News Block -->
					<?php
					$sql = "SELECT * FROM `news` ORDER BY id DESC LIMIT 1 OFFSET 1;";
					$result = $conn->query($sql);
					$result->num_rows > 0;
					$row = $result->fetch_assoc();
					?>
					<div class="news-block col-lg-4 col-md-6 col-sm-12 wow fadeInUp" data-wow-delay="300ms">
						<div class="inner-box">
							<div class="image-box">
								<figure class="image"><a href="#"><img src="./admin/img_news/370x360_<?php echo $row["img"]; ?>" alt=""></a></figure>
							</div>
							<div class="content-box">
								<div class="content">
									<h6 class="title"><a href="news-details.php?newid=<?php echo $row["id"]; ?>"><?php echo $row["title"]; ?></a></h6>
								</div>
							</div>
						</div>
					</div>

					<!-- News Block -->
					<?php
					$sql = "SELECT * FROM `news` ORDER BY id DESC LIMIT 1 OFFSET 2;";
					$result = $conn->query($sql);
					$result->num_rows > 0;
					$row = $result->fetch_assoc();
					?>
					<div class="news-block col-lg-4 col-md-6 col-sm-12 wow fadeInUp" data-wow-delay="300ms">
						<div class="inner-box">
							<div class="image-box">
								<figure class="image"><a href="#"><img src="./admin/img_news/370x360_<?php echo $row["img"]; ?>" alt=""></a></figure>
							</div>
							<div class="content-box">
								<div class="content">
									<h6 class="title"><a href="news-details.php?newid=<?php echo $row["id"]; ?>"><?php echo $row["title"]; ?></a></h6>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</section>
		<!--End News Section -->

		<!-- Courses Section โครงการและกิจกรรม -->
		<section class="courses-section">
			<div class="auto-container">
				<div class="anim-icons">
					<span class="icon icon-e wow zoomIn"></span>
				</div>
				<div class="carousel-outer">
					<!-- Courses Carousel -->
					<div class="courses-carousel owl-carousel owl-theme default-nav">
						<?php
						$sql = "SELECT * FROM activity ORDER BY date_regis DESC LIMIT 4;";
						$result = $conn->query($sql);
						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
								$thai_word = iconv_substr($row["activity_name"], 0, 60, 'UTF-8');
						?>
								<!-- Course Block -->
								<div class="course-block">
									<div class="inner-box">
										<div class="image-box">
											<figure class="image"><a href="activity-details.php?actid=<?php echo $row["id"]; ?>"><img src="admin/img_act/270x270_<?php echo $row["filename"]; ?>" alt=""></a></figure>
											<div class="value"><?php echo $row["activity_type"]; ?></div>
										</div>
										<div class="content-box">
											<ul class="course-info">
												<li><i class="fa fa-book"></i><?php echo $row["activity_date"]; ?></li>
											</ul>
											<h5 class="title">
												<a href="activity-details.php?actid=<?php echo $row["id"]; ?>">
													<?php
													$title = $row["activity_name"];
													if (mb_strlen($title) > 40) {
														$title = mb_substr($title, 0, 40) . '...';
													}
													echo $title;
													?>
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
						<strong>หลักการของหลักสูตร CWIE (4 Key Characteristics of CWIE) </strong> <a href="" class="theme-btn btn-style-one small">Explore All Courses</a>
					</div>
				</div>
			</div>
		</section>
		<!-- End Courses Section-->

		<!--Main Slider-->

		<!-- End Main Slider-->

		<!-- About Section -->

		<!--Emd About Section -->

		<!-- Features Section -->
		<section class="features-section">
			<div class="auto-container">
				<div class="row">
					<!-- Feature Block -->
					<div class="feature-block col-lg-3 col-md-6 col-sm-6 wow fadeInUp">
						<div class="inner-box ">
							<i class="icon flaticon-online-learning"></i>
							<h6 class="title">University-Workplace Engagement</h6>
						</div>
					</div>

					<!-- Feature Block -->
					<div class="feature-block col-lg-3 col-md-6 col-sm-6 wow fadeInUp" data-wow-delay="400ms">
						<div class="inner-box ">
							<i class="icon flaticon-elearning"></i>
							<h5 class="title">Co-design Curriculum</h5>
						</div>
					</div>

					<!-- Feature Block -->
					<div class="feature-block col-lg-3 col-md-6 col-sm-6 wow fadeInUp" data-wow-delay="800ms">
						<div class="inner-box ">
							<i class="icon flaticon-web-2"></i>
							<h5 class="title">Competency-based Education</h5>
						</div>
					</div>

					<!-- Feature Block -->
					<div class="feature-block col-lg-3 col-md-6 col-sm-6 wow fadeInUp" data-wow-delay="1200ms">
						<div class="inner-box ">
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
							<h6 class="title"><a href="https://manage.lru.ac.th/th/">คณะวิทยาการจัดการ</a></h6>
						</div>
					</div>

					<!-- Category Block -->
					<div class="category-block-current col-xl-2 col-lg-3 col-md-4 col-sm-6">
						<div class="inner-box">
							<div class="icon-box">
								<i class="icon flaticon-stationary"></i>
							</div>
							<h6 class="title"><a href="https://www.sci.lru.ac.th/th/">คณะวิทยาศาสตร์และเทคโนโลยี</a></h6>
						</div>
					</div>

					<!-- Category Block -->
					<div class="category-block-current col-xl-2 col-lg-3 col-md-4 col-sm-6">
						<div class="inner-box">
							<div class="icon-box">
								<i class="icon flaticon-online-learning"></i>
							</div>
							<h6 class="title"><a href="https://idtech.lru.ac.th/th/">คณะเทคโนโลยีอุตสาหกรรม</a></h6>
						</div>
					</div>

					<!-- Category Block -->
					<div class="category-block-current col-xl-2 col-lg-3 col-md-4 col-sm-6">
						<div class="inner-box">
							<div class="icon-box">
								<i class="icon flaticon-study"></i>
							</div>
							<h6 class="title"><a href="https://human.lru.ac.th/th/">คณะมนุษยศาสตร์และสังคมศาสตร์</a></h6>
						</div>
					</div>

					<!-- Category Block -->
					<div class="category-block-current col-xl-2 col-lg-3 col-md-4 col-sm-6">
						<div class="inner-box">
							<div class="icon-box">
								<i class="icon flaticon-pie-chart"></i>
							</div>
							<h6 class="title"><a href="https://edu.lru.ac.th/th/">คณะครุศาสตร์</a></h6>
						</div>
					</div>

				</div>
			</div>
		</section>
		<!-- End Product Categories -->

		<!-- Signup Section -->

		<!--End FAQ Section -->

		<!-- Team Section -->
		<section id="team-section" class="team-section">
			<div class="auto-container">
				<div class="sec-title text-center">
					<span class="sub-title" style="font-size: 40px;">อาจารย์นิเทศสหกิจศึกษา</span>
				</div>
				<div class="row">
					<!-- Team block -->
					<?php
					$sql = "SELECT * FROM tea_cwie";
					$result = $conn->query($sql);
					if ($result->num_rows > 0) {
						while ($row = $result->fetch_assoc()) {
					?>
							<div class="team-block col-xl-3 col-lg-6 col-md-6 col-sm-12 wow fadeInUp">
								<div class="inner-box">
									<div class="image-box">
										<figure class="image"><a href="#"><img src="admin/img_teach/<?php echo $row["filename"]; ?>" alt=""></a></figure>
										<span class="share-icon fa fa-share-alt"></span>
									</div>
									<div class="info-box">
										<h6 class="name"><a href="#"><?php echo $row["name_tea_cwie"]; ?></a></h6>
										<span class="designation">สาขาวิชา<?php echo $row["course"]; ?></span>
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

		<!-- Call To Action Two -->
		<section class="call-to-action" style="background-image: url(./images/background/1.jpg)">
			<div class="anim-icons">
				<span class="icon icon-calculator zoom-one"></span>
				<span class="icon icon-pin-clip zoom-one"></span>
				<span class="icon icon-dots"></span>
			</div>

		</section>
		<!--End Call To Action Two -->

		<!-- Testimonial Section Three -->
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
								$sql = "SELECT * FROM `job_cwie` ORDER BY `job_cwie`.`id`  DESC";
								$result = $conn->query($sql);
								if ($result->num_rows > 0) {
									while ($row = $result->fetch_assoc()) {
										$job_des =  $row["job_des"];
										global $job_des;
								?>
										<!-- Testimonial Block -->
										<div class="testimonial-block">
											<div class="inner-box">
												<div class="content-box">
													<figure class="thumb"><img src="admin/img_job/<?php echo $row["filename"]; ?>" alt=""></figure>
													<div class="rating"><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i><i class="fa fa-star"></i></div>
													<div class="text">
														<?php $resStr = str_replace('-', '<br> -', $job_des);
														print_r($resStr);
														?>
													</div>
													<div class="info-box">
														<span class="icon-quote"></span>
														<h4 class="name"><?php echo $row["job_title"]; ?></h4>
														<span class="designation"><?php echo $row["company"]; ?></span>
													</div>
													<div class="btn-box mt-3">
														<a href="<?php echo $row["link"]; ?>" class="theme-btn btn-style-one"><span class="btn-title">อ่านต่อ</span></a>
													</div>
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
		<!-- About Section Two-->
		<section class="about-section-two">
			<div class="anim-icons">
				<span class="icon icon-e wow zoomIn"></span>
				<span class="icon icon-dots-2 bounce-x"></span>
			</div>
			<div class="auto-container">
				<div class="row">
					<div class="content-column col-lg-6 col-md-12 order-2 wow fadeInRight" data-wow-delay="600ms">
						<div class="inner-column">
							<div class="sec-title">
								<h2>นักศึกษาสหกิจศึกษาและการศึกษาเชิงบูรณาการกับการทำงาน CWIE LRU</h2>
							</div>
							<div class="row">
								<?php
								$sql = "SELECT * FROM `stu_highlight` ORDER BY `stu_highlight`.`id`  DESC LIMIT 2";
								$result = $conn->query($sql);
								if ($result->num_rows > 0) {
									while ($row = $result->fetch_assoc()) {
								?>
										<div class="about-block col-lg-6 col-md-6 col-sm-6 wow fadeInUp">
											<div class="inner-box">
												<span class="info-text"><?php echo $row["highlight"]; ?></span>
												<div class="info-box">
													<div class="thumb"><img src="./admin/img_stu_htghlight/<?php echo $row["filename"]; ?>" alt=""></div>
													<h6 class="name"><?php echo $row["name"]; ?></h6>
													<span class="designation"><?php echo $row["faculty"]; ?></span>
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

					<!-- Image Column -->
					<div class="image-column col-lg-6 col-md-12">
						<div class="inner-column wow fadeInLeft">
							<?php
							$sql = "SELECT * FROM `stu_highlight` ORDER BY date_regis DESC LIMIT 1";
							$result = $conn->query($sql);
							if ($result->num_rows > 0) {
								while ($row = $result->fetch_assoc()) {
							?>
									<div class="icons-box">
										<span class="icon icon-dotted-map"></span>
										<span class="icon icon-dotted-line"></span>
										<span class="icon icon-papper-plan"></span>
									</div>
									<figure class="image overlay-anim wow fadeInUp"><img src="./admin/img_stu_htghlight/<?php echo $row["filename"]; ?>" alt="">
									</figure>
						</div>
				<?php
								}
							}
				?>
					</div>
				</div>
			</div>
		</section>
		<!--Emd About Section Two-->

		<!-- Countdown Section -->
		<!-- End Deal Section -->

		<!-- News Section -->

		<!--End Clients Section -->

		<!-- Main Footer -->
		<footer class="main-footer">
			<div class="bg-image zoom-two" style="background-image: url(./images/background/4.jpg)"></div>

			<!--Widgets Section-->


			<!--Footer Bottom-->
			<div class="footer-bottom">
				<div class="auto-container">
					<div class="inner-container">
						<div class="copyright-text">&copy; Copyright 2022 by <a href="index.php">CWIE | สหกิจศึกษามหาวิทยาลัยราชภัฏเลย</a></div>
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
	<!--Revolution Slider-->
	<script src="js/bootstrap.min.js"></script>
	<script src="js/jquery.fancybox.js"></script>
	<script src="js/jquery-ui.js"></script>
	<script src="js/wow.js"></script>
	<script src="js/appear.js"></script>
	<script src="js/jquery.countdown.js"></script>
	<script src="js/select2.min.js"></script>
	<script src="js/swiper.min.js"></script>
	<script src="js/owl.js"></script>
	<script src="js/script.js"></script>
</body>

</html>