<?php
session_start();
include_once('admin/connect.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>รายละเอียดข่าว | CWEI LRU</title>
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
</head>

<body>

	<div class="page-wrapper">

		<!-- Preloader -->
		<div class="preloader"></div>

		<!-- Main Header-->
		<?php include_once 'mainHeader.php'; ?>
		</header>
		<!--End Main Header -->

		<!-- Start main-content -->
		<section class="page-title" style="background-image: url(images/colormag-logolru-1.png);">
			<div class="auto-container">
			</div>
		</section>
		<!-- end main-content -->

		<!--Blog Details Start-->
		<section class="blog-details">
			<div class="container">
				<div class="row">
					<?php
					$newid = $_GET["newid"];
					$sql = "SELECT * FROM news WHERE id = '$newid';";
					$result = $conn->query($sql);
					$result->num_rows > 0;
					$row = $result->fetch_assoc();
					?>
					<div class="col-xl-8 col-lg-7">
						<div class="blog-details__left">
							<div class="blog-details__img">
								<img src="./admin/img_news/<?php echo $row["img"]; ?>" alt="">
								<div class="blog-details__date">
									<span class="day">28</span>
									<span class="month">Aug</span>
								</div>
							</div>
							<div class="blog-details__content">
								<h3 class="blog-details__title"><?php echo $row["title"]; ?></h3>
								<p class="blog-details__text-2"><?php echo $row["detail"]; ?></p>
								<p class="blog-details__text-2"><?php echo $row["detail2"]; ?></p>
							</div>
							<div class="blog-details__bottom">
								<p class="blog-details__tags"> <span>หัวข้อ</span> <a href="news-details.html">Education</a> <a href="news-details.html">College</a> </p>
								<div class="blog-details__social-list"> <a href="news-details.html"><i class="fab fa-twitter"></i></a> <a href="news-details.html"><i class="fab fa-facebook"></i></a> <a href="news-details.html"><i class="fab fa-pinterest-p"></i></a> <a href="news-details.html"><i class="fab fa-instagram"></i></a> </div>
							</div>
						</div>
					</div>
					<div class="col-xl-4 col-lg-5">
						<div class="sidebar">
							<div class="sidebar__single sidebar__search">
								<form action="#" class="sidebar__search-form">
									<input type="search" placeholder="Search here">
									<button type="submit"><i class="lnr-icon-search"></i></button>
								</form>
							</div>
							<div class="sidebar__single sidebar__post">
								<h3 class="sidebar__title">ข่าวล่าสุด</h3>
								<ul class="sidebar__post-list list-unstyled">
									<li>
										<div class="sidebar__post-image"> <img src="https://via.placeholder.com/370x360" alt=""> </div>
										<div class="sidebar__post-content">
											<h3> <span class="sidebar__post-content-meta"><i class="fas fa-user-circle"></i>Admin</span> <a href="news-details.html">Top crypto exchange influencers</a>
											</h3>
										</div>
									</li>
									<li>
										<div class="sidebar__post-image"> <img src="https://via.placeholder.com/370x360" alt=""> </div>
										<div class="sidebar__post-content">
											<h3> <span class="sidebar__post-content-meta"><i class="fas fa-user-circle"></i>Admin</span> <a href="news-details.html">Necessity may give us best virtual court</a> </h3>
										</div>
									</li>
									<li>
										<div class="sidebar__post-image"> <img src="https://via.placeholder.com/370x360" alt=""> </div>
										<div class="sidebar__post-content">
											<h3> <span class="sidebar__post-content-meta"><i class="fas fa-user-circle"></i>Admin</span> <a href="news-details.html">You should know about business plan</a> </h3>
										</div>
									</li>
								</ul>
							</div>
							<div class="sidebar__single sidebar__category">
								<h3 class="sidebar__title">ปรพเภทข่าว</h3>
								<ul class="sidebar__category-list list-unstyled">
									<li><a href="news-details.html">Artifical Intelligence<span class="icon-right-arrow"></span></a> </li>
									<li class="active"><a href="news-details.html">Cloud Solution<span class="icon-right-arrow"></span></a></li>
									<li><a href="news-details.html">Cyber Data<span class="icon-right-arrow"></span></a> </li>
									<li><a href="news-details.html">SEO Marketing<span class="icon-right-arrow"></span></a> </li>
									<li><a href="news-details.html">UI/UX Design<span class="icon-right-arrow"></span></a> </li>
									<li><a href="news-details.html">Web Development<span class="icon-right-arrow"></span></a> </li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!--Blog Details End-->

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
								<div class="logo"><a href="index.html"><img src="images/logo-2.png" alt=""></a></div>
								<div class="text">Get 26,000+ best online courses from us</div>
								<ul class="social-icon-two">
									<li><a href="#"><i class="fab fa-twitter"></i></a></li>
									<li><a href="#"><i class="fab fa-facebook"></i></a></li>
									<li><a href="#"><i class="fab fa-pinterest"></i></a></li>
									<li><a href="#"><i class="fab fa-instagram"></i></a></li>
								</ul>
							</div>
						</div>

						<!--Footer Column-->
						<div class="footer-column col-xl-2 col-lg-4 col-md-6 col-sm-12">
							<div class="footer-widget">
								<h4 class="widget-title">Explore</h4>
								<ul class="user-links">
									<li><a href="#">Gallery</a></li>
									<li><a href="#">News & Articles</a></li>
									<li><a href="#">FAQ's</a></li>
									<li><a href="#">Sign In/Registration</a></li>
									<li><a href="#">Coming Soon</a></li>
									<li><a href="#">Contacts</a></li>
								</ul>
							</div>
						</div>

						<!--Footer Column-->
						<div class="footer-column col-xl-2 col-lg-4 col-md-6 col-sm-12">
							<div class="footer-widget">
								<h4 class="widget-title">Links</h4>
								<ul class="user-links">
									<li><a href="#">About</a></li>
									<li><a href="#">Courses</a></li>
									<li><a href="#">Instructor</a></li>
									<li><a href="#">Events</a></li>
									<li><a href="#">Instructor Profile</a></li>
								</ul>
							</div>
						</div>

						<!--Footer Column-->
						<div class="footer-column col-xl-5 col-lg-4 col-md-6 col-sm-12">
							<div class="footer-widget contact-widget">
								<h4 class="widget-title">Contact</h4>
								<div class="widget-content">
									<ul class="contact-info">
										<li><i class="fa fa-phone-square"></i> <a href="tel:+926668880000">+92 (0088) 6823</a></li>
										<li><i class="fa fa-envelope"></i> <a href="mailto:needhelp@potisen.com">needhelp@company.com</a></li>
										<li><i class="fa fa-map-marker-alt"></i> 80 Broklyn Golden Street. New York. USA</li>
									</ul>
									<div class="subscribe-form">

										<form method="post" action="#">
											<div class="form-group">
												<input type="email" name="email" class="email" value="" placeholder="Email Address" required="">
												<button type="button" class="theme-btn btn-style-one"><i class="fa fa-long-arrow-alt-right"></i></button>
											</div>
										</form>
									</div>
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
						<div class="copyright-text">&copy; Copyright 2022 by <a href="index.html">Company.com</a></div>
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
	<!-- form submit -->
	<script src="js/jquery.validate.min.js"></script>
	<script src="js/jquery.form.min.js"></script>
	<script>
		(function($) {
			$("#contact_form").validate({
				submitHandler: function(form) {
					var form_btn = $(form).find('button[type="submit"]');
					var form_result_div = '#form-result';
					$(form_result_div).remove();
					form_btn.before('<div id="form-result" class="alert alert-success" role="alert" style="display: none;"></div>');
					var form_btn_old_msg = form_btn.html();
					form_btn.html(form_btn.prop('disabled', true).data("loading-text"));
					$(form).ajaxSubmit({
						dataType: 'json',
						success: function(data) {
							if (data.status == 'true') {
								$(form).find('.form-control').val('');
							}
							form_btn.prop('disabled', false).html(form_btn_old_msg);
							$(form_result_div).html(data.message).fadeIn('slow');
							setTimeout(function() {
								$(form_result_div).fadeOut('slow')
							}, 6000);
						}
					});
				}
			});
		})(jQuery);
	</script>
</body>

</html>