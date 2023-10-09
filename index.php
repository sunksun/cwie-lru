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

		<!-- News Section -->
		<section class="news-section">

			<div class="auto-container">
				<div class="sec-title text-center">
					<span class="sub-title">ข่าวประชาสัมพันธ์</span>
				</div>
				<div class="row">
					<!-- News Block -->
					<?php
					$sql = "SELECT * FROM `news` WHERE new_type = 'information' ORDER BY id DESC LIMIT 1 OFFSET 0;";
					$result = $conn->query($sql);
					$result->num_rows > 0;
					$row = $result->fetch_assoc();
					?>
					<div class="news-block col-lg-4 col-md-6 col-sm-12 wow fadeInUp">
						<div class="inner-box">
							<div class="image-box">
								<figure class="image"><a href="news-details.php"><img src="./admin/img_news/<?php echo $row["img"]; ?>" alt=""></a></figure>
							</div>
							<div class="content-box">
								<div class="content">
									<ul class="post-info">
										<li><i class="fa fa-user"></i> by Admin</li>
										<li><i class="fa fa-comments"></i> 2 Comments</li>
									</ul>
									<h4 class="title"><a href="news-details.php"><?php echo $row["title"]; ?></a></h4>
									<a href="news-details.php" class="read-more">อ่านต่อ <i class="fa fa-long-arrow-alt-right"></i></a>
								</div>
							</div>
						</div>
					</div>
					<!-- News Block -->
					<?php
					$sql = "SELECT * FROM `news` WHERE new_type = 'information' ORDER BY id DESC LIMIT 1 OFFSET 1;";
					$result = $conn->query($sql);
					$result->num_rows > 0;
					$row = $result->fetch_assoc();
					?>
					<div class="news-block col-lg-4 col-md-6 col-sm-12 wow fadeInUp" data-wow-delay="300ms">
						<div class="inner-box">
							<div class="image-box">
								<figure class="image"><a href="news-details.php"><img src="./admin/img_news/<?php echo $row["img"]; ?>" alt=""></a></figure>
							</div>
							<div class="content-box">
								<div class="content">
									<ul class="post-info">
										<li><i class="fa fa-user"></i> by Admin</li>
										<li><i class="fa fa-comments"></i> 2 Comments</li>
									</ul>
									<h4 class="title"><a href="news-details.php"><?php echo $row["title"]; ?></a></h4>
									<a href="news-details.php" class="read-more">อ่านต่อ <i class="fa fa-long-arrow-alt-right"></i></a>
								</div>
							</div>
						</div>
					</div>

					<!-- News Block -->
					<?php
					$sql = "SELECT * FROM `news` WHERE new_type = 'information' ORDER BY id DESC LIMIT 1 OFFSET 2;";
					$result = $conn->query($sql);
					$result->num_rows > 0;
					$row = $result->fetch_assoc();
					?>
					<div class="news-block col-lg-4 col-md-6 col-sm-12 wow fadeInUp" data-wow-delay="300ms">
						<div class="inner-box">
							<div class="image-box">
								<figure class="image"><a href="news-details.php"><img src="./admin/img_news/<?php echo $row["img"]; ?>" alt=""></a></figure>
							</div>
							<div class="content-box">
								<div class="content">
									<ul class="post-info">
										<li><i class="fa fa-user"></i> by Admin</li>
										<li><i class="fa fa-comments"></i> 2 Comments</li>
									</ul>
									<h4 class="title"><a href="news-details.php"><?php echo $row["title"]; ?></a></h4>
									<a href="news-details.php" class="read-more">อ่านต่อ <i class="fa fa-long-arrow-alt-right"></i></a>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>
		</section>
		<!--End News Section -->

		<!-- Courses Section -->
		<section class="courses-section">
			<div class="auto-container">
				<div class="anim-icons">
					<span class="icon icon-e wow zoomIn"></span>
				</div>

				<div class="sec-title">
					<span class="sub-title" style="font-size: 40px;">โครงการและกิจกรรม</span>
				</div>

				<div class="carousel-outer">
					<!-- Courses Carousel -->
					<div class="courses-carousel owl-carousel owl-theme default-nav">
						<?php
						$sql = "SELECT * FROM activity ORDER BY date_regis DESC LIMIT 4;";
						$result = $conn->query($sql);
						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
								$thai_word = iconv_substr($row["activity_name"], 0, 50, 'UTF-8');
						?>
								<!-- Course Block -->
								<div class="course-block">
									<div class="inner-box">
										<div class="image-box">
											<figure class="image"><a href="page-course-details.html"><img src="admin/img_act/<?php echo $row["filename"]; ?>" alt=""></a></figure>
											<div class="value"><?php echo $row["activity_type"]; ?></div>
										</div>
										<div class="content-box">
											<ul class="course-info">
												<li><i class="fa fa-book"></i><?php echo $row["activity_date"]; ?></li>
											</ul>
											<h5 class="title"><a href="page-course-details.html"><?php echo $thai_word; ?>...</a></h5>
											<div class="other-info">
												<div class="rating-box">
													<span class="text">(4.9 /8 Rating)</span>
													<div class="rating"><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span><span class="fa fa-star"></span></div>
												</div>
												<?php
												$date_regis = substr($row["date_regis"], 0, 10);
												$date_now = date("Y-m-d");
												$date1 = "$date_now";
												$date2 = "$date_regis";
												//$diff = date_diff($date1, $date2);
												?>
												<div class="duration"><i class="fa fa-clock"></i> <?php echo "$date2"; ?> </div>
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

				<div class="bottom-text">
					<div class="content">
						<strong>หลักการของหลักสูตร CWIE (4 Key Characteristics of CWIE) </strong> <a href="#" class="theme-btn btn-style-one small">Explore All Courses</a>
					</div>
				</div>
			</div>
		</section>
		<!-- End Courses Section-->

		<!--Main Slider-->
		<section class="main-slider">
			<div class="rev_slider_wrapper fullwidthbanner-container" id="rev_slider_one_wrapper" data-source="gallery">
				<div class="rev_slider fullwidthabanner" id="rev_slider_one" data-version="5.4.1">
					<ul>
						<!-- Slide 1 -->
						<li data-index="rs-1" data-transition="zoomout">
							<!-- MAIN IMAGE -->
							<?php
							$sql = "SELECT * FROM news WHERE new_type = 'Highlight' ORDER BY date_time DESC LIMIT 1;";
							$result = $conn->query($sql);
							if ($result->num_rows > 0) {
								while ($row = $result->fetch_assoc()) {
							?>
									<img src="images/main-slider/1.jpg" alt="" class="rev-slidebg">

									<div class="tp-caption tp-shape tp-shapewrapper tp-resizeme big-ipad-hidden rs-parallaxlevel-1" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingtop="[0,0,0,0]" data-responsive_offset="on" data-type="shape" data-height="auto" data-whitespace="nowrap" data-width="none" data-hoffset="['-260','-100','-100','-100']" data-voffset="['-270','-190','-190','-190']" data-x="['left','left','left','left']" data-y="['middle','middle','middle','middle']" data-frames='[{"from":"x:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'>
										<figure><img src="images/main-slider/icon/icon-book.png" alt=""></figure>
									</div>

									<div class="tp-caption tp-shape tp-shapewrapper tp-resizeme big-ipad-hidden rs-parallaxlevel-1" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingtop="[0,0,0,0]" data-responsive_offset="on" data-type="shape" data-height="auto" data-whitespace="nowrap" data-width="none" data-hoffset="['-300','-100','-100','-100']" data-voffset="['280','-190','-190','-190']" data-x="['right','right','right','right']" data-y="['middle','middle','middle','middle']" data-frames='[{"from":"x:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":1000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'>
										<figure><img src="images/main-slider/icon/icon-globe.png" alt=""></figure>
									</div>

									<div class="tp-caption tp-resizeme rs-parallaxlevel-2 big-ipad-hidden" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingtop="[0,0,0,0]" data-responsive_offset="on" data-type="shape" data-height="none" data-whitespace="nowrap" data-width="none" data-hoffset="['-250','-120','-120','-120']" data-voffset="['190','100','100','100']" data-x="['left','left','left','left']" data-y="['middle','middle','middle','middle']" data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1500,"ease":"Power3.easeInOut"},{"delay":"wait","speed":3000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'>
										<figure><img src="images/main-slider/icon/icon-dots.png" alt=""></figure>
									</div>


									<div class="tp-caption tp-resizeme rs-parallaxlevel-3 big-ipad-hidden" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingtop="[0,0,0,0]" data-responsive_offset="on" data-type="shape" data-height="none" data-whitespace="nowrap" data-width="none" data-hoffset="['-170','120','120','120']" data-voffset="['220','180','180','180']" data-x="['center','center','center','center']" data-y="['middle','middle','middle','middle']" data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":2000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":3000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'>
										<figure><img src="images/main-slider/icon/icon-star.png" alt=""></figure>
									</div>

									<div class="tp-caption tp-resizeme rs-parallaxlevel-1 big-ipad-hidden" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingtop="[0,0,0,0]" data-responsive_offset="on" data-type="shape" data-height="none" data-whitespace="nowrap" data-width="none" data-hoffset="['-40','120','120','120']" data-voffset="['-160','100','100','100']" data-x="['center','center','center','center']" data-y="['middle','middle','middle','middle']" data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":2000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":3000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'>
										<figure><img src="images/main-slider/icon/icon-arrow.png" alt=""></figure>
									</div>

									<div class="tp-caption tp-resizeme rs-parallaxlevel-2 big-ipad-hidden" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingtop="[0,0,0,0]" data-responsive_offset="on" data-type="shape" data-height="none" data-whitespace="nowrap" data-width="none" data-hoffset="['-120','-30','-30','-30']" data-voffset="['-180','-180','-180','-180']" data-x="['right','right','right','right']" data-y="['middle','middle','middle','middle']" data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":2000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":3000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'>
										<figure><img src="images/main-slider/icon/icon-dots-2.png" alt=""></figure>
									</div>

									<div class="tp-caption tp-resizeme rs-parallaxlevel-1 big-ipad-hidden" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingtop="[0,0,0,0]" data-responsive_offset="on" data-type="shape" data-height="none" data-whitespace="nowrap" data-width="none" data-hoffset="['-160','-50','0','150']" data-voffset="['170','120','120','120']" data-x="['right','right','right','right']" data-y="['middle','middle','middle','middle']" data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":2000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":3000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'>
										<figure><img src="images/main-slider/icon/icon-circle-1.png" alt=""></figure>
									</div>

									<div class="tp-caption tp-resizeme rs-parallaxlevel-1 big-ipad-hidden" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingtop="[0,0,0,0]" data-responsive_offset="on" data-type="shape" data-height="none" data-whitespace="nowrap" data-width="none" data-hoffset="['30','-50','0','150']" data-voffset="['300','120','120','120']" data-x="['center','center','center','center']" data-y="['middle','middle','middle','middle']" data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":2000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":3000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'>
										<figure><img src="images/main-slider/icon/icon-circle-2.png" alt=""></figure>
									</div>

									<div class="tp-caption tp-resizeme rs-parallaxlevel-1 big-ipad-hidden" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingtop="[0,0,0,0]" data-responsive_offset="on" data-type="shape" data-height="none" data-whitespace="nowrap" data-width="none" data-hoffset="['250','-50','0','150']" data-voffset="['-190','120','120','120']" data-x="['center','center','center','center']" data-y="['middle','middle','middle','middle']" data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":2000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":3000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'>
										<figure><img src="images/main-slider/icon/icon-circle-3.png" alt=""></figure>
									</div>


									<div class="tp-caption tp-resizeme rs-parallaxlevel-2 big-ipad-hidden" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingtop="[0,0,0,0]" data-responsive_offset="on" data-type="shape" data-height="none" data-whitespace="nowrap" data-width="none" data-hoffset="['-220','-30','-30','-30']" data-voffset="['-80','-180','-180','-180']" data-x="['right','right','right','right']" data-y="['middle','middle','middle','middle']" data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":2000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":3000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'>
										<figure><img src="images/main-slider/icon/icon-bulb.png" alt=""></figure>
									</div>

									<div class="tp-caption tp-resizeme" data-paddingbottom="[0,0,0,0]" data-paddingleft="[0,0,0,0]" data-paddingright="[0,0,0,0]" data-paddingtop="[0,0,0,0]" data-responsive_offset="on" data-type="shape" data-height="none" data-whitespace="nowrap" data-width="none" data-hoffset="['-100','-100','-200','-320']" data-voffset="['0','0','-30','-30']" data-x="['right','right','right','right']" data-y="['bottom','bottom','bottom','bottom']" data-frames='[{"from":"y:[100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","speed":1500,"to":"o:1;","delay":1000,"ease":"Power3.easeInOut"},{"delay":"wait","speed":3000,"to":"auto:auto;","mask":"x:0;y:0;s:inherit;e:inherit;","ease":"Power3.easeInOut"}]'>
										<figure class="main-image"><img src="./admin/img_news/<?php echo $row["img"]; ?>" width="666 px" alt=""></figure>
									</div>

									<div class="tp-caption" data-paddingbottom="[15,15,15,15]" data-paddingleft="[15,15,15,15]" data-paddingright="[0,0,0,0]" data-paddingtop="[0,0,0,0]" data-responsive_offset="on" data-type="text" data-height="none" data-width="['750','750','750','750']" data-whitespace="normal" data-hoffset="['0','0','0','0']" data-voffset="['-205','-190','-210','-220']" data-x="['left','left','left','left']" data-y="['middle','middle','middle','middle']" data-textalign="['top','top','top','top']" data-frames='[{"delay":1000,"speed":1500,"frame":"0","from":"y:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]'>
									</div>

									<div class="tp-caption" data-paddingbottom="[0,0,0,0]" data-paddingleft="[15,15,15,15]" data-paddingright="[15,15,15,15]" data-paddingtop="[0,0,0,0]" data-responsive_offset="on" data-type="text" data-height="none" data-width="['650','750','750','450']" data-whitespace="normal" data-hoffset="['0','0','0','0']" data-voffset="['-55','-50','-50','-90']" data-x="['left','left','left','left']" data-y="['middle','middle','middle','middle']" data-textalign="['top','top','top','top']" data-frames='[{"delay":1000,"speed":1500,"frame":"0","from":"y:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]'>
										<h2><?php echo $row["title"]; ?></h2>
									</div>

									<div class="tp-caption" data-paddingbottom="[0,0,0,0]" data-paddingleft="[15,15,15,15]" data-paddingright="[0,0,0,0]" data-paddingtop="[0,0,0,0]" data-responsive_offset="on" data-type="text" data-height="none" data-width="['550','750','750','450']" data-whitespace="normal" data-hoffset="['0','0','0','0']" data-voffset="['110','90','100','65']" data-x="['left','left','left','left']" data-y="['middle','middle','middle','middle']" data-textalign="['top','top','top','top']" data-frames='[{"delay":1000,"speed":1500,"frame":"0","from":"y:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]'>
										<div class="text"><?php echo $row["detail"]; ?></div>
									</div>


									<div class="tp-caption" data-paddingbottom="[0,0,0,0]" data-paddingleft="[15,15,15,15]" data-paddingright="[15,15,15,15]" data-paddingtop="[0,0,0,0]" data-responsive_offset="on" data-type="text" data-height="none" data-width="['700','750','700','450']" data-whitespace="normal" data-hoffset="['0','0','0','0']" data-voffset="['200','185','200','185']" data-x="['left','left','left','left']" data-y="['middle','middle','middle','middle']" data-textalign="['top','top','top','top']" data-frames='[{"delay":1000,"speed":1500,"frame":"0","from":"y:[-100%];z:0;rX:0deg;rY:0;rZ:0;sX:1;sY:1;skX:0;skY:0;","mask":"x:0px;y:0px;s:inherit;e:inherit;","to":"o:1;","ease":"Power3.easeInOut"},{"delay":"wait","speed":300,"frame":"999","to":"auto:auto;","ease":"Power3.easeInOut"}]'>
										<a href="#" class="theme-btn btn-style-one">อ่านต่อ</a>
									</div>
						</li>
				<?php
								}
							}
				?>

					</ul>
				</div>
			</div>
		</section>
		<!-- End Main Slider-->

		<!-- About Section -->
		<section class="about-section">
			<div class="anim-icons">
				<span class="icon icon-dotted-map"></span>
			</div>
			<div class="auto-container">
				<div class="row">
					<div class="content-column col-lg-6 col-md-12 order-2 wow fadeInRight" data-wow-delay="600ms">
						<div class="inner-column">
							<div class="sec-title">
								<h2>Cooperative and Work Integrated Education (CWIE)</h2>
								<div class="text">คือ หลักสูตรการเรียนการสอนในลักษณะร่วมผลิตระหว่างสถานบันอุดมศึกษา และสถานประกอบการ (ภาครัฐ เอกชน ชุมชน) เพื่อให้บัณฑิตพร้อมสู้โลกแห่งการทำงานจริงได้ทันที มีสมรรถนะตรงกับความต้องการของตลาดงาน สามารถพัฒนาอาชีพในปัจจุบันและเตรียมพร้อมรองรับตำแหน่งงานในอนาคต</div>
							</div>

							<ul class="list-style-one two-column">
								<li><i class="icon fa fa-check"></i> นักศึกษา</li>
								<li><i class="icon fa fa-check"></i> สถาบันอุดมศึกษา</li>
								<li><i class="icon fa fa-check"></i> สถานประกอบการ</li>
								<li><i class="icon fa fa-check"></i> ประเทศชาติ</li>
							</ul>

							<div class="btn-box">
								<a href="#" class="theme-btn btn-style-one"><span class="btn-title">อ่านต่อ</span></a>
							</div>
						</div>
					</div>

					<!-- Image Column -->
					<div class="image-column col-lg-6 col-md-12">
						<div class="anim-icons">
							<span class="icon icon-dotted-map-2"></span>
							<span class="icon icon-paper-plan"></span>
							<span class="icon icon-dotted-line"></span>
						</div>
						<?php
						$sql = "SELECT * FROM news WHERE new_type = 'next_activity' ORDER BY date_time DESC LIMIT 1;";
						$result = $conn->query($sql);
						if ($result->num_rows > 0) {
							while ($row = $result->fetch_assoc()) {
						?>
								<div class="inner-column wow fadeInLeft">
									<figure class="image-1 overlay-anim wow fadeInUp"><img src="admin/img_news/<?php echo $row["img"]; ?>" alt=""></figure>
									<figure class="image-2 overlay-anim wow fadeInRight"><img src="images/resource/about-2.jpg" alt=""></figure>
									<div class="experience bounce-y"><span class="count" style="font-size: 25px;"><?php echo $row["date1"]; ?></span>
										<?php echo $row["mou_year"]; ?>
									</div>

								</div>
						<?php
							}
						}
						?>
					</div>
				</div>
			</div>
		</section>
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
		<section class="signup-section">
			<div class="auto-container">
				<div class="anim-icons">
					<span class="icon icon-paper-clip bounce-x"></span>
				</div>
				<div class="outer-box" style="background-image: url(./images/background/3.jpg)">
					<span class="float-icon icon-pencil-line wow fadeInUp"></span>
					<div class="row">
						<!-- Title Column -->
						<div class="title-column col-lg-6 col-md-12 col-sm-12">
							<div class="sec-title light">
								<h2>สมัคร<br> รับข้อมูลข่าวสารจาก<br> CWIE LRU</h2>
							</div>
						</div>

						<!-- Form Column -->
						<div class="form-column col-lg-6 col-md-12 col-sm-12">
							<div class="inner-column">
								<!-- Sign Form -->
								<div class="signup-form wow fadeInLeft">
									<!--Contact Form-->
									<form method="post" action="get" id="contact-form">
										<div class="form-group">
											<input type="text" name="full_name" placeholder="ชื่อ-นามสกุล" required>
										</div>

										<div class="form-group">
											<input type="text" name="Email" placeholder="อีเมล์" required>
										</div>

										<div class="form-group">
											<select class="custom-select">
												<?php
												$sql = "SELECT * FROM faculty ";
												$result = $conn->query($sql);
												if ($result->num_rows > 0) {
													while ($optionData = $result->fetch_assoc()) {
														$option = $optionData['faculty'];
												?>
														<option value="<?php echo $option; ?>">
															<?php echo $option; ?></option>
												<?php
													}
												}
												?>
											</select>
										</div>

										<div class="form-group">
											<button class="theme-btn btn-style-one" type="submit" name="submit-form">Send Request</button>
										</div>
									</form>
								</div>
								<!--End Contact Form -->
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!--End FAQ Section -->

		<!-- Team Section -->
		<section class="team-section">
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