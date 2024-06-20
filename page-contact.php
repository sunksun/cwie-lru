<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>ติดต่อเรา | สหกิจศึกษามหาวิทยาลัยราชภัฏเลย</title>
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
		<!--End Main Header -->

		<!-- Start main-content -->
		<!-- end main-content -->

		<!--Contact Details Start-->
		<section class="contact-details">
			<div class="container ">
				<div class="row">
					<div class="col-xl-7 col-lg-6">
						<div class="sec-title">
							<span class="sub-title">ส่งข้อความ</span>
						</div>
						<!-- Contact Form -->
						<form id="contact_form" name="contact_form" class="" action="includes/sendmail.php" method="post">
							<div class="row">
								<div class="col-sm-6">
									<div class="mb-3">
										<input name="form_name" class="form-control" type="text" placeholder="ชื่อ-นามสกุล">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="mb-3">
										<input name="form_email" class="form-control required email" type="email" placeholder="อีเมล์">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-6">
									<div class="mb-3">
										<input name="form_subject" class="form-control required" type="text" placeholder="ชื่อเรื่องติดต่อ">
									</div>
								</div>
								<div class="col-sm-6">
									<div class="mb-3">
										<input name="form_phone" class="form-control" type="text" placeholder="หมายเลขโทรศัพท์">
									</div>
								</div>
							</div>
							<div class="mb-3">
								<textarea name="form_message" class="form-control required" rows="7" placeholder="ข้อความ...."></textarea>
							</div>
							<div class="mb-3">
								<input name="form_botcheck" class="form-control" type="hidden" value="" />
								<button type="submit" class="theme-btn btn-style-one" data-loading-text="Please wait..."><span class="btn-title">ส่งข้อความ</span></button>
								<button type="reset" class="theme-btn btn-style-one bg-theme-color5"><span class="btn-title">รีเซ็ต</span></button>
							</div>
						</form>
						<!-- Contact Form Validation-->
					</div>
					<div class="col-xl-5 col-lg-6">
						<div class="contact-details__right">
							<div class="sec-title">
								<span class="sub-title">ช่องทางการติดต่อ</span>
							</div>
							<ul class="list-unstyled contact-details__info">
								<li>
									<div class="icon bg-theme-color2">
										<span class="lnr-icon-phone-plus"></span>
									</div>
									<div class="text">
										<h6>หมายเลขโทรศัพท์</h6>
										<a href="tel:980089850"><span>Tel</span> 042 - 835224 - 8 ต่อ 41127 - 41132</a>
									</div>
								</li>
								<li>
									<div class="icon">
										<span class="lnr-icon-screen"></span>
									</div>
									<div class="text">
										<h6>Facebook Page</h6>
										<a href="https://www.facebook.com/academiclru/">www.facebook.com/academiclru/</a>
									</div>
								</li>
								<li>
									<div class="icon">
										<span class="lnr-icon-location"></span>
									</div>
									<div class="text">
										<h6>สถานที่ติดต่อ</h6>
										<span>มหาวิทยาลัยราชภัฏเลย 234 <br>ต.เมือง อ.เมือง จ.เลย 42000</span>
									</div>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!--Contact Details End-->

		<!-- Divider: Google Map -->
		<section>
			<div class="container-fluid p-0">
				<div class="row">
					<!-- Google Map HTML Codes -->
					<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.843149788316!2d144.9537131159042!3d-37.81714274201087!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad65d4c2b349649%3A0xb6899234e561db11!2sEnvato!5e0!3m2!1sbn!2sbd!4v1583760510840!5m2!1sbn!2sbd" data-tm-width="100%" height="500" frameborder="0" allowfullscreen=""></iframe>
				</div>
			</div>
		</section>

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