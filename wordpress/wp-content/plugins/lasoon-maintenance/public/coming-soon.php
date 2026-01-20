<style>
    .contact-icon svg {
        display: none;
		width: 30px;
        height: 30px;
        fill: #ffffff; /* Іконки білі */
    }
</style>

<?php
// 🔁 Вставляємо функцію один раз на початку
if (!function_exists('format_german_phone')) {
    function format_german_phone($number) {
        $clean = preg_replace('/\D+/', '', $number);

        if (strpos($clean, '49') === 0) {
            $country = '+49';
            $rest = substr($clean, 2);

            $mobile_prefixes = ['151', '152', '155', '157', '159', '160', '162', '163', '170', '171', '172', '173', '174', '175', '176', '177', '178', '179'];
            $prefix3 = substr($rest, 0, 3);
            $prefix4 = substr($rest, 0, 4);

            if (in_array($prefix3, $mobile_prefixes)) {
                $area_code = $prefix3;
                $subscriber = substr($rest, 3);
            } elseif (in_array($prefix4, $mobile_prefixes)) {
                $area_code = $prefix4;
                $subscriber = substr($rest, 4);
            } else {
                $area_code = substr($rest, 0, 4);
                $subscriber = substr($rest, 4);
            }

            $parts = [];
            if (strlen($subscriber) >= 8) {
                $parts[] = substr($subscriber, 0, 3);
                $parts[] = substr($subscriber, 3, 2);
                $parts[] = substr($subscriber, 5);
            } elseif (strlen($subscriber) >= 5) {
                $parts[] = substr($subscriber, 0, 3);
                $parts[] = substr($subscriber, 3);
            } else {
                $parts[] = $subscriber;
            }

            return $country . ' / ' . $area_code . ' / ' . implode(' ', $parts);
        }

        return $number;
    }
}

// Отримуємо номер
$git_contact = esc_attr(get_option('lasoon_git_contact')) ?: '+01235678899';
$formatted_phone = format_german_phone($git_contact);
?>


<?php
$site_logo = get_option('lasoon_logo')? esc_url(get_option('lasoon_logo')): LASOON_PUBLIC_PATH.'assets/images/logo.png';
$launch_date = get_option('lasoon_launch_date')? esc_attr(get_option('lasoon_launch_date')): '';
$contact_enable = get_option('lasoon_contact_us')?:'Unable';
$counter_style = get_option('lasoon_countdown_enable') === 'Unable'? '': esc_attr('style=display:none;');
$layout_select = get_option('lasoon_blog_layout')?:'layout-1';
$head_animate = get_option('lasoon_heading_animation')?:'line_up'; 
$counter_animate = get_option('lasoon_shape_animation')?:'dash_circle';


$id = ($body_classes === 'snow-rain')?"id=particles-js":"";

// Side bar variables
$about_us = (get_option('lasoon_about_show'))?:"Unable";
$git = (get_option('lasoon_git_show'))?:"Unable";

/* code for find launch time */
date_default_timezone_set('Asia/Kolkata');
$countDownDate = new DateTime($launch_date);
$now = new DateTime();
$diff = $countDownDate->diff($now);

$iniday = esc_html__($diff->days, 'lasoon'); 
$inihour = esc_html__($diff->h, 'lasoon');
$iniminute = esc_html__($diff->i, 'lasoon');
$iniasecond = esc_html__($diff->s, 'lasoon');
?>
<!DOCTYPE html>
<html lang="zxx" dir="ltr">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title><?php echo esc_html__($title, 'lasoon'); ?></title>
	<meta name="author" content="<?php echo esc_attr( $author ); ?>" />
	<meta name="description" content="<?php echo esc_attr( $description ); ?>" />
	<meta name="keywords" content="<?php echo esc_attr( $keywords ); ?>" />
	<meta name="robots" content="<?php echo esc_attr( $robots ); ?>" />
	<link rel="stylesheet" type="text/css" href="<?php echo LASOON_PUBLIC_PATH; ?>assets/css/lasoon-public.css">
	<?php if ($counter_animate === 'flip_counter') { ?>
		<link rel="stylesheet" type="text/css" href="<?php echo LASOON_PUBLIC_PATH; ?>assets/css/flip-timer.css"> 
	<?php } elseif ($counter_animate === 'flip_counter_two') { ?>
		<link rel="stylesheet" type="text/css" href="<?php echo LASOON_PUBLIC_PATH; ?>assets/css/flip-timer-two.css"> 
	<?php } ?>
	<link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,500,700&display=swap" rel="stylesheet">
	</head>
	<body class="<?php echo esc_attr($body_classes);?>" <?php echo esc_attr($id)?:'';?>>
		<div class="page-wrap">
			<header class="header site-header">
				<div class="page-logo">
					<a href="/" class="logo"><img src="<?php echo esc_url($site_logo); ?>" alt="Munas-Print"></a>
				</div>
				<?php if(esc_attr(get_option('lasoon_sidebar')) === 'Unable'){ ?>
					<div class="menu-bar">
						<div class="sidebar-btn toggle">
							<span></span>
							<span></span>
							<span></span>
						</div>
						<nav class="sidebar">
							<div class="close-btn">
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 26 26" id="Слой_1" version="1.1" viewBox="0 0 26 26" xml:space="preserve" width="30" height="30"><path d="M14.0605469,13L24.7802734,2.2802734c0.2929688-0.2929688,0.2929688-0.7675781,0-1.0605469  s-0.7675781-0.2929688-1.0605469,0L13,11.9394531L2.2802734,1.2197266c-0.2929688-0.2929688-0.7675781-0.2929688-1.0605469,0  s-0.2929688,0.7675781,0,1.0605469L11.9394531,13L1.2197266,23.7197266c-0.2929688,0.2929688-0.2929688,0.7675781,0,1.0605469  C1.3662109,24.9267578,1.5576172,25,1.75,25s0.3837891-0.0732422,0.5302734-0.2197266L13,14.0605469l10.7197266,10.7197266  C23.8662109,24.9267578,24.0576172,25,24.25,25s0.3837891-0.0732422,0.5302734-0.2197266  c0.2929688-0.2929688,0.2929688-0.7675781,0-1.0605469L14.0605469,13z" fill="#fff"/></svg>
							</div>
							<div class="main_side">
								<?php 
								if($about_us === "Unable"){ ?>
									<div class="page-about-us">
										<h2 class="nav-title"><?php echo esc_html__(get_option('lasoon_about_head')?:'About Us', 'lasoon'); ?></h2>
										<p><?php  echo wp_kses_post(get_option('lasoon_about_desc')?:''); ?> </p>
									</div>
								<?php }
								if($git === "Unable"){ ?>
									<div class="page-get-touch">
										<div class="get-touch-wrap">
											<h3 class="nav-title"><?php echo esc_html__(get_option('lasoon_git_head')?:'Get In Touch', 'lasoon'); ?></h3>
											<p><?php  echo wp_kses_post(get_option('lasoon_git_desc')?:''); ?></p>

											<div class="page-contact-detail <?php echo esc_attr($layout_select); ?>">
												<div class="detail-wrap">
													<ul class="navbar-contact-detail">
														<li class="icons">
															<div class="detail-icon">
																<svg width="37" height="37" viewBox="0 0 37 37" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M35.9297 27.7109L34.2422 34.8125C34.0312 35.8672 33.1875 36.5703 32.1328 36.5703C14.4141 36.5 0 22.0859 0 4.36719C0 3.3125 0.632812 2.46875 1.6875 2.25781L8.78906 0.570312C9.77344 0.359375 10.8281 0.921875 11.25 1.83594L14.5547 9.5C14.9062 10.4141 14.6953 11.4688 13.9219 12.0312L10.125 15.125C12.5156 19.9766 16.4531 23.9141 21.375 26.3047L24.4688 22.5078C25.0312 21.8047 26.0859 21.5234 27 21.875L34.6641 25.1797C35.5781 25.6719 36.1406 26.7266 35.9297 27.7109Z" fill="#000000"/></svg>
															</div>


<div class="navitem phone-col">
    <span class="detail-title"><?php echo esc_html__('phone', 'lasoon'); ?></span>
    <div class="deatil-text">
        <a href="tel:<?php echo str_replace(' ', '', $git_contact); ?>">
            <?php echo esc_html($formatted_phone); ?>
        </a>
    </div>
</div>
												

														</li>
														<li class="icons">
															<div class="detail-icon">
																<svg width="37" height="37" viewBox="0 0 36 27" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M32.625 0C34.4531 0 36 1.54688 36 3.375C36 4.5 35.4375 5.48438 34.5938 6.11719L19.3359 17.5781C18.4922 18.2109 17.4375 18.2109 16.5938 17.5781L1.33594 6.11719C0.492188 5.48438 0 4.5 0 3.375C0 1.54688 1.47656 0 3.375 0H32.625ZM15.2578 19.4062C16.875 20.6016 19.0547 20.6016 20.6719 19.4062L36 7.875V22.5C36 25.0312 33.9609 27 31.5 27H4.5C1.96875 27 0 25.0312 0 22.5V7.875L15.2578 19.4062Z" fill="#000000" class="email-icon"/></svg>
															</div>
															<div class="navitem email-col">
																<?php $git_email =  esc_attr(get_option('lasoon_git_email'))?:'info@site.com'; ?>
																<span class="detail-title"> <?php echo esc_html__('email', 'lasoon'); ?> </span>
																<div class="deatil-text"><a href="mailto:<?php echo $git_email; ?>"><?php echo $git_email; ?></a>
																</div>					 
															</div>
														</li>
														<li class="icons">
															<div class="detail-icon">
																<svg width="37" height="37" viewBox="0 0 27 37" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.8125 35.6562C8.15625 31.0859 0 20.1875 0 14C0 6.54688 5.97656 0.5 13.5 0.5C20.9531 0.5 27 6.54688 27 14C27 20.1875 18.7734 31.0859 15.1172 35.6562C14.2734 36.7109 12.6562 36.7109 11.8125 35.6562ZM13.5 18.5C15.9609 18.5 18 16.5312 18 14C18 11.5391 15.9609 9.5 13.5 9.5C10.9688 9.5 9 11.5391 9 14C9 16.5312 10.9688 18.5 13.5 18.5Z" fill="#000000"/></svg>
															</div>
															<div class="navitem address-col">
																<span class="detail-title"><?php echo esc_html__('Address', 'lasoon'); ?></span>
																<div class="deatil-text"><a href="#"><?php  echo wp_kses_post(get_option('lasoon_git_address')?:'2358 Cyan St, NY'); ?></a></div>
															</div>
														</li>
													</ul>
												</div>
											</div>
										</div>
									</div>
								<?php }
								if(esc_attr($contact_enable) === 'Unable' ){ ?>
									<div class="page-contact-us <?php echo esc_attr($layout_select); ?>">
										<h4 class="nav-title"><?php echo esc_html__('Contact Us', 'lasoon'); ?></h4>
										<div class="contact-us-form">
											<form class="lasoon_contact_form" method="post" enctype="multipart/form-data">
												<input type="text" id="fname" name="name" class="input-group" placeholder="Name" required><br>
												<input type="email" id="email" name="email" class="input-group" placeholder="Email" required>
												<textarea id="message" name="message" class="input-group" placeholder="Message" cols="40" rows="5"></textarea><br>
												<button type="submit" class="message-button lasson_sbmt" data-target="myPopup"><?php echo esc_html__('send message', 'lasoon'); ?></button>
											</form>
										</div>
									</div>
								<?php } 
								if(esc_attr($layout_select) === 'layout_2') { ?>
									<div class="page-social-icons navbar-social layout-two">
										<div class="social-icons">
											<ul class="icon-list">
												<?php
												$links_arr = array('facebook', 'twitter','instagram', 'linkedin', 'pinterest','whatsapp','snapchat', 'wechat',  'youtube', 'skype', 'behance', 'dribble', 'github', 'google');
												foreach($links_arr as $links){
													if($link = lcm_get_url_of_social_icon($links)){ 
														?>
														<li class="icon-item">
															<a href="<?php echo $link; ?>"><img src="<?php echo LASOON_PUBLIC_PATH; ?>assets/images/<?php echo $links; ?>.svg" alt="<?php echo esc_html__('this is social icons.', 'lasoon'); ?>"></a>
														</li>
													<?php }	
												}   ?>
											</ul>
										</div>
									</div>
								<?php } ?>
							</div>
						</nav>
					</div>
				<?php } ?>
			</header>
			<!--Header Section End here -->

			<!--Page Main Section Start here -->
			<section class="Slider page-background">
				<div class="Slider-content <?php echo esc_attr($layout_select); ?>">
					<div class="Slider-content-inner">
						<div class="page-main-content">
							<div class="wrapper">
								<h1 class="main-title <?php echo esc_attr($head_animate);?>" data-text="<?php echo esc_html__(get_option('lasoon_heading_text', 'lasoon'))?:esc_html__("we're coming soon", 'lasoon'); ?>"><?php echo esc_html__(get_option('lasoon_heading_text', 'lasoon'))?:esc_html__("we're coming soon", 'lasoon'); ?></h1>
							</div>
							<?php 
							if(get_option('lasoon_subtitle_enable') && esc_attr(get_option('lasoon_subtitle_enable')) === 'Unable'){
								$head_subtitle = '<p class="page-description">'.get_option('lasoon_heading_subtitle')?:'Lorem ipsum'.'</p>';
								echo wp_kses_post($head_subtitle); 
							}
							?>
						</div>
						<!--Timer Section -->
						<div class="page-timer layout  <?php echo esc_attr($counter_shape?:'dash_circle'); ?>" <?php echo $counter_style; ?>>
							<div id="end"></div>
							<div class="timer-wrap">
								<div class="timer-days timer-col counter-shape bo-ma <?php echo esc_attr($counter_shape?:'dash_circle');?>" id="days">
									<div class="counter-content">
										<div id="day" class="counter-time"><?php echo $iniday?:'NaN'; ?></div>
										<div class="timer-text" ><?php echo esc_html__('Tage', 'lasoon'); ?></div>
									</div>
								</div>
								<div class="timer-hour  timer-col counter-shape <?php echo esc_attr($counter_shape?:'dash_circle');?>" id="hour">
									<div class="counter-content">
										<div id="hours" class="counter-time"><?php echo $inihour?:'NaN'; ?></div>
										<div class="timer-text" ><?php echo esc_html__('Stunden', 'lasoon'); ?></div>
									</div>
								</div>
								<div class="timer-minute  timer-col counter-shape bo-ma <?php echo esc_attr($counter_shape?:'dash_circle');?>" id="minute">
									<div class="counter-content">
										<div id="minutes" class="counter-time"><?php echo $iniminute?:'NaN'; ?></div>
										<div class="timer-text"><?php echo esc_html__('Minuten', 'lasoon'); ?></div>
									</div>
								</div>
								<div class="timer-second  timer-col counter-shape <?php echo esc_attr($counter_shape?:'dash_circle');?>" id="second">
									<div class="counter-content">
										<div id="seconds" class="counter-time"><?php echo $iniasecond?:'NaN'; ?></div>
										<div class="timer-text" ><?php echo esc_html__('Sekunden', 'lasoon'); ?></div>
									</div>
								</div>
							</div>
							<?php  if ($counter_animate === 'flip_counter') { ?> 
								<div class="countdown flip-counter" id="countdown"></div>

							<?php } elseif($counter_animate === 'flip_counter_two') { ?>
								<div class="tick" data-did-init="handleTickInit">
									<div data-repeat="true" data-layout="horizontal center fit" data-transform="preset(d, h, m, s) -> delay">
										<div class="tick-group">
											<div data-key="value" data-repeat="true" data-transform="pad(00) -> split -> delay">
												<span data-view="flip"></span>
											</div>
											<span data-key="label" data-view="text" class="tick-label"></span>
										</div>
									</div>
								</div>

							<?php } elseif($counter_animate === 'circle_line_counter') { ?>
								<div class="la-count-down-wrapper">
									<ul class="row la-count-down">                       
										<li>   
											<div class="la-count-element">
												<input class="knob days" data-readonly="true" data-min="0" data-max="365" data-width="200" data-height="200" data-thickness="0.07" data-fgcolor="#34aadc" data-bgcolor="#e1e2e6" data-angleoffset="180" readonly="readonly" >
											</div>
											<span id="days-title">days</span>
										</li>
										<li> 
											<div class="la-count-element">
												<input class="knob hour" data-readonly="true" data-min="0" data-max="24" data-width="200" data-height="200" data-thickness="0.07" data-fgcolor="#4cd964" data-bgcolor="#e1e2e6" data-angleoffset="180" readonly="readonly" >
											</div>
											<span id="hours-title">hours</span>
										</li>
										<li> 
											<div class="la-count-element">
												<input class="knob minute" data-readonly="true" data-min="0" data-max="60" data-width="200" data-height="200" data-thickness="0.07" data-fgcolor="#ff9500" data-bgcolor="#e1e2e6" data-angleoffset="180" readonly="readonly">
											</div>
											<span id="mins-title">minutes</span>
										</li>
										<li> 
											<div class="la-count-element">
												<input class="knob second" data-readonly="true" data-min="0" data-max="60" data-width="200" data-height="200" data-thickness="0.07" data-fgcolor="#ff3b30" data-bgcolor="#e1e2e6" data-angleoffset="180" readonly="readonly">
											</div>
											<span id="secs-title">seconds</span>
										</li>                
									</ul>              
								</div>

							<?php } elseif($counter_animate === 'fill_circle_line') { ?>
								<div class="la-count-down-wrapper">
									<ul class="row la-count-down">                       
										<li>   
											<div class="la-count-element">
												<input class="knob days" data-readonly="true" data-min="0" data-max="365" data-width="200" data-height="200" data-thickness="0.07" data-fgcolor="#34aadc" data-bgcolor="#e1e2e6" data-angleoffset="180" readonly="readonly" >
											</div>
											<span id="days-title">days</span>
										</li>
										<li> 
											<div class="la-count-element">
												<input class="knob hour" data-readonly="true" data-min="0" data-max="24" data-width="200" data-height="200" data-thickness="0.07" data-fgcolor="#4cd964" data-bgcolor="#e1e2e6" data-angleoffset="180" readonly="readonly" >
											</div>
											<span id="hours-title">hours</span>
										</li>
										<li> 
											<div class="la-count-element">
												<input class="knob minute" data-readonly="true" data-min="0" data-max="60" data-width="200" data-height="200" data-thickness="0.07" data-fgcolor="#ff9500" data-bgcolor="#e1e2e6" data-angleoffset="180" readonly="readonly">
											</div>
											<span id="mins-title">minutes</span>
										</li>
										<li> 
											<div class="la-count-element">
												<input class="knob second" data-readonly="true" data-min="0" data-max="60" data-width="200" data-height="200" data-thickness="0.07" data-fgcolor="#ff3b30" data-bgcolor="#e1e2e6" data-angleoffset="180" readonly="readonly">
											</div>
											<span id="secs-title">seconds</span>
										</li>                
									</ul>              
								</div>

							<?php } elseif($counter_animate === 'countdown_clock') { ?>
								<div id="clock-countdown">
									<div id='tiles'></div>
									<div class="labels">
										<li>Days</li>
										<li>Hours</li>
										<li>Mins</li>
										<li>Secs</li>
									</div>
								</div>

							<?php } ?>

						</div>

						<!--Email Subscription 
						<div class="page-email-subscription <?php echo esc_attr($layout_select); ?>">
							<form class="subscribe_form" method="post">
								<div class="input-group">
									<input type="email" class="form-control layout-form" name="sub_email" placeholder="Geben Sie Ihre E-Mail-Adresse ein">
									<div class="input-group-btn">
										<?php if(esc_attr($layout_select) === 'layout_1' || esc_attr($layout_select) === 'layout_4' ){ ?>
											<button class="email-btn" type="submit"><svg width="25" height="25" viewBox="0 0 36 27" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M32.625 0C34.4531 0 36 1.54688 36 3.375C36 4.5 35.4375 5.48438 34.5938 6.11719L19.3359 17.5781C18.4922 18.2109 17.4375 18.2109 16.5938 17.5781L1.33594 6.11719C0.492188 5.48438 0 4.5 0 3.375C0 1.54688 1.47656 0 3.375 0H32.625ZM15.2578 19.4062C16.875 20.6016 19.0547 20.6016 20.6719 19.4062L36 7.875V22.5C36 25.0312 33.9609 27 31.5 27H4.5C1.96875 27 0 25.0312 0 22.5V7.875L15.2578 19.4062Z" fill="#ffffff"/></svg>
											</button>
										<?php } else { ?>
											<button class="btn-default" type="submit"><?php echo esc_html__('submit', 'lasoon'); ?></button>
										<?php } ?>
									</div>
								</div>
							</form>
						</div>-->



						<?php function lcm_get_url_of_social_icon($id){
							return get_option($id.'_social_link')? esc_url(get_option($id.'_social_link')): '';
						} ?>
						<!--Social sharing icon -->				
						<div class="page-social-icons <?php echo esc_attr($layout_select); ?>">
							<div class="social-icons">
								<ul class="icon-list">
									<?php
									$links_arr = array('facebook', 'twitter','instagram', 'linkedin', 'pinterest','whatsapp','snapchat', 'wechat',  'youtube', 'skype', 'behance', 'dribble', 'github', 'google');
									foreach($links_arr as $links){
										if($link = lcm_get_url_of_social_icon($links)){ 
											?>
											<li class="icon-item">
												<a href="<?php echo esc_url($link); ?>"><img src="<?php echo LASOON_PUBLIC_PATH; ?>assets/images/<?php echo $links; ?>.svg" alt="<?php echo esc_html__('this is a social icons.', 'lasoon'); ?>"></a>
											</li>
											<?php 
										}	
									}
									?>
								</ul>
							</div>
						</div>
						<!-- Contacts Section -->
												<div class="page-contacts">
							<div class="contacts-container">
								<div class="contact-item">
									<div class="contact-icon">
										<svg width="37" height="37" viewBox="0 0 37 37" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M35.9297 27.7109L34.2422 34.8125C34.0312 35.8672 33.1875 36.5703 32.1328 36.5703C14.4141 36.5 0 22.0859 0 4.36719C0 3.3125 0.632812 2.46875 1.6875 2.25781L8.78906 0.570312C9.77344 0.359375 10.8281 0.921875 11.25 1.83594L14.5547 9.5C14.9062 10.4141 14.6953 11.4688 13.9219 12.0312L10.125 15.125C12.5156 19.9766 16.4531 23.9141 21.375 26.3047L24.4688 22.5078C25.0312 21.8047 26.0859 21.5234 27 21.875L34.6641 25.1797C35.5781 25.6719 36.1406 26.7266 35.9297 27.7109Z" fill="#fff"/>
										</svg>
									</div>

<div class="contact-text">
    <span class="contact-title"><?php echo esc_html__('Phone', 'lasoon'); ?></span><br>
    <span><?php echo esc_html($formatted_phone); ?></span>
</div>
									<!-- #region 
									<div class="contact-text">
										<span class="contact-title"><?php echo esc_html__('Phone', 'lasoon'); ?></span><br>
										<span><?php echo $git_contact; ?></span>
									</div> -->


								</div>
								<div class="contact-item">
									<div class="contact-icon">
										<svg width="37" height="37" viewBox="0 0 36 27" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M32.625 0C34.4531 0 36 1.54688 36 3.375C36 4.5 35.4375 5.48438 34.5938 6.11719L19.3359 17.5781C18.4922 18.2109 17.4375 18.2109 16.5938 17.5781L1.33594 6.11719C0.492188 5.48438 0 4.5 0 3.375C0 1.54688 1.47656 0 3.375 0H32.625ZM15.2578 19.4062C16.875 20.6016 19.0547 20.6016 20.6719 19.4062L36 7.875V22.5C36 25.0312 33.9609 27 31.5 27H4.5C1.96875 27 0 25.0312 0 22.5V7.875L15.2578 19.4062Z" fill="#fff"/>
										</svg>
									</div>
									<div class="contact-text">
										<span class="contact-title"><?php echo esc_html__('Email', 'lasoon'); ?></span><br>
										<span><?php echo $git_email; ?></span>
									</div>
								</div>
								<div class="contact-item">
									<div class="contact-icon">
										<svg width="37" height="37" viewBox="0 0 27 37" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M11.8125 35.6562C8.15625 31.0859 0 20.1875 0 14C0 6.54688 5.97656 0.5 13.5 0.5C20.9531 0.5 27 6.54688 27 14C27 20.1875 18.7734 31.0859 15.1172 35.6562C14.2734 36.7109 12.6562 36.7109 11.8125 35.6562ZM13.5 18.5C15.9609 18.5 18 16.5312 18 14C18 11.5391 15.9609 9.5 13.5 9.5C10.9688 9.5 9 11.5391 9 14C9 16.5312 10.9688 18.5 13.5 18.5Z" fill="#fff"/>
										</svg>
									</div>
									<div class="contact-text">
										<span class="contact-title"><?php echo esc_html__('Address', 'lasoon'); ?></span><br>
										<span><?php echo wp_kses_post(get_option('lasoon_git_address') ?: '2358 Cyan St, NY'); ?></span>
									</div>
								</div>
							</div>
						</div>

						
						<!--Copyright text -->
						<div class="page-footer-text">
							<span class="copyright">
    <?php echo wp_kses_post('Unsere Website wird gerade von dem Team von <a href="https://munas-print.de" target="_blank" class="munas-link">Munas-Print</a> erstellt.'); ?>
</span>

						</div>
					</div>	
				</div>
				<?php if(esc_attr(get_option('lasoon_background_type')) === 'Unable'){
					$back_1_image = get_option('lasoon_back_1_image')?:LASOON_PUBLIC_PATH."assets/images/background-image.jpg";
					$back_2_image = get_option('lasoon_back_2_image')?:'';
					$back_3_image = get_option('lasoon_back_3_image')?:'';

					if($back_1_image){
						?>
						<div class="Slider-slide show" style="background-image: url('<?php echo esc_url($back_1_image); ?>');"></div>
					<?php }
					if($back_2_image){
						?>
						<div class="Slider-slide" style="background-image: url('<?php echo esc_url($back_2_image); ?>');"></div>
					<?php }
					if($back_3_image){ ?>
						<div class="Slider-slide" style="background-image: url('<?php echo esc_url($back_3_image); ?>');"></div>

						<?php
					}
				} else {

					?>
					<div class="page-background-video">
						<?php 
						$video_type = get_option('lasoon_background_video_type')?:"Disable";
						if(esc_attr($video_type) === 'Unable'){
							$back_video = get_option('lasoon_back_video')?:LASOON_PUBLIC_PATH."assets/video/section-video.mp4";
							?>

							<video controls autoplay muted loop id="bg_video">
								<source src="<?php echo esc_url($back_video); ?>" type="video/mp4">
								</video>
							<?php } else { ?>
								<!--Set the video using iframe -->
								<div id="bg-iframe-video">	
									<?php $back_video_embedd = get_option('lasoon_embed_url')?:'<iframe src="https://player.vimeo.com/video/305795958?background=1&autoplay=1&;title=0&;byline=0&;portrait=0&;loop=1&;autopause=0&;muted=1"  frameborder="0" webkitallowfullscreen="" mozallowfullscreen="" allowfullscreen="" "></iframe>';

									if($video_type && $video_type === 'Disable'){
										$back_video_embedd = html_entity_decode( esc_html( $back_video_embedd ) );
										echo '<div id="player">'.$back_video_embedd.'</div>';
									}    ?>
								</div>
							<?php } ?>
						</div>
					<?php }  ?>
				</section>
				<!--page Main Section End here -->
				<?php 

				if($_POST && isset($_POST['sub_email'])){
					$sub_email_arr = get_option('subscription_email_list');
					if(!is_array($sub_email_arr)){$sub_email_arr = array($_POST['sub_email']);}
					if(!in_array(sanitize_email($_POST['sub_email']), $sub_email_arr)){
						$sub_email_arr[] = sanitize_email($_POST['sub_email']);
					}
					update_option('subscription_email_list', $sub_email_arr);
				} else if($_POST && isset($_POST['name'])) {
					$name =  $_POST['name']?esc_attr($_POST['name']):'';
					$email =  $_POST['email']?sanitize_email($_POST['email']):'';
					$message =  $_POST['message']?esc_html($_POST['message']):'';

					$admin =get_option('admin_email');
					$subject = $name.esc_html__(' Contact Us Form', 'lasoon');

					if(wp_mail($admin, $subject, $message) )
					{
						$notice = "your mail is successfully  sent!";
					} else {
						$notice = "your mail is not sent! please check your mail.";
					}
					?>

					<div id="myPopup" class="popup">
						<div class="popup-header">
							<?php echo esc_html__('Contact Us', 'lasoon'); ?>
							<div class="close toggle-btn" data-target="myPopup"><?php echo esc_html__('close', 'lasoon'); ?></div>
						</div>
						<div class="popup-body">
							<?php echo esc_html__($notice, 'lasoon'); ?>
						</div>
						<div class="popup-footer">
							<button class="toggle-btn button" data-target="myPopup"><?php echo esc_html__('Got it !' ,'lasoon'); ?></button>
						</div>
					</div>
					<script type="text/javascript">
						$(".sidebar").fadeOut().removeClass("show");
					</script>
					<?php
				}
				?>
			</div>

			<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/jquery.min.js"></script>


			<?php
			if($body_classes === 'particles') { ?>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/particle-script.js"></script>

			<?php } elseif ($body_classes === 'fire_ball') { ?>
				<canvas id="canvas"></canvas>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/fire-ball.js"></script>

			<?php } elseif ($body_classes === 'magical_particles') { ?>
				<canvas id='canv'></canvas>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/magical-particles.js"></script>

			<?php } elseif ($body_classes === 'snow-rain') { ?>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/particle.min.js"></script>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/snow-rain.js"></script>

			<?php } elseif ($body_classes === 'lighting_ball') { ?>
				<canvas id='particles'></canvas>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/lightball.min.js"></script>

			<?php } elseif ($body_classes === 'ripple') { ?>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/ripple.min.js"></script>

			<?php } elseif ($body_classes === 'particle_waves') { ?>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/particle-waves.js"></script>

			<?php } elseif ($body_classes === 'confetti') { ?>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/confetti/confetti.js"></script>

			<?php } elseif ($body_classes === 'constellation_particle') { ?>
				<canvas id="demo-canvas"></canvas>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/EasePack.min.js"></script>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/rAF.js"></script>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/TweenLite.min.js"></script>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/constellection-particle.js"></script>

			<?php } elseif ($body_classes === 'fireworks') { ?>
				<canvas id="canvas"></canvas>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/firework/fireworks.js"></script>

			<?php } elseif ($body_classes === 'particle_fields') { ?>
				<canvas id="particlesField"></canvas>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/particle-field/particlefield.js"></script>

			<?php } elseif ($body_classes === 'bubble_particle') { ?>
				<div id="bubble-js"></div>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/particle.min.js"></script>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/bubble-particle/bubble-particle.js"></script>


			<?php } elseif ($body_classes === 'shapes') { ?>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/shape/jquery.transit.min.js"></script>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/shape/shapes.js"></script>

			<?php } elseif ($body_classes === 'shooting_star') { ?>
				<canvas id="bgCanvas"></canvas>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/shooting-star/shooting-star.js"></script>

			<?php } elseif ($body_classes === 'projector') { ?>
				<canvas id="projector"></canvas>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/TweenMax.min.js"></script>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/projector/easeljs.min.js"></script>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/projector/projector.js"></script>

			<?php } elseif ($body_classes === 'rain_matrix') { ?>
				<canvas id="matrix_canvas"></canvas>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/rain-matrix/rain-matrix.js"></script>

			<?php } elseif ($body_classes === 'rain_matrix_two') { ?>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/p5.min.js"></script>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/rain-matrix/rain-matrix-two.js"></script>

			<?php } elseif ($body_classes === 'rain_matrix_three') { ?>
				<canvas id="matrix_canvas_two"></canvas>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/rain-matrix/rain-matrix-three.js"></script>

			<?php } elseif ($body_classes === 'matrix_cell') { ?>
				<canvas id="scene" width="1920" height="937"></canvas>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/three.min.js"></script>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/matrix-cell/box-perline.js"></script>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/TweenMax.min.js"></script>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/matrix-cell/box-matrix.js"></script>

			<?php } elseif ($body_classes === 'growing_bubbles') { ?>
				<canvas id="bubble-canvas" width="1920" height="937"></canvas>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/TweenLite.min.js"></script>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/growing-bubble/bubble-app.js"></script>

			<?php } elseif ($body_classes === 'bouncing_ball') { ?>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/bouncing-ball/quietflow.js"></script>

			<?php } elseif ($body_classes === 'circle_particle') { ?>
				<canvas id="circle-particle" width="1920" height="937"></canvas>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/circle-particle/circle-three.min.js"></script>
				<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/TweenMax.min.js"></script>
				<script type="x-shader/x-vertex" id="wrapVertexShader">
					attribute float size;
					attribute vec3 color;
					varying vec3 vColor;
					void main() {
					vColor = color;
					vec4 mvPosition = modelViewMatrix * vec4( position, 1.0 );
					gl_PointSize = size * ( 350.0 / - mvPosition.z );
					gl_Position = projectionMatrix * mvPosition;
				}
			</script>
			<script type="x-shader/x-fragment" id="wrapFragmentShader">
				varying vec3 vColor;
				uniform sampler2D texture;
				void main(){
				vec4 textureColor = texture2D( texture, gl_PointCoord );
				if ( textureColor.a < 0.3 ) discard;
				vec4 color = vec4(vColor.xyz, 1.0) * textureColor;
				gl_FragColor = color;
			}
		</script>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/circle-particle/circle-particle.js"></script>

	<?php } elseif ($body_classes === 'cloud') { ?>
		<div id="cloud-animation">
			<img src="<?php echo LASOON_PUBLIC_PATH; ?>assets/images/cloud-1.png" alt="this is image" id="cloud1">
			<img src="<?php echo LASOON_PUBLIC_PATH; ?>assets/images/cloud-2.png" alt="this is image" id="cloud2">
			<img src="<?php echo LASOON_PUBLIC_PATH; ?>assets/images/cloud-3.png" alt="this is image" id="cloud3">
			<img src="<?php echo LASOON_PUBLIC_PATH; ?>assets/images/cloud-4.png" alt="this is image" id="cloud4">
		</div>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/cloud/jquery.nicescroll.min.js"></script>

	<?php } elseif ($body_classes === 'infinit_tunnel') { ?>
		<canvas id="infinie-tunnel"></canvas>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/three.min.js"></script>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/infinite-tunnel/fulltilt.js"></script>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/infinite-tunnel/mobile-gyroscope.js"></script>

	<?php } elseif ($body_classes === 'triangle') { ?>
		<canvas id="triangle-ani"></canvas>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/three.min.js"></script>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/triangle/perlin.js"></script>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/triangle/triangle.js"></script>

	<?php } elseif ($body_classes === 'moving_star') { ?>
		<div class="starfield"></div>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/star-move/stats-star.min.js"></script>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/star-move/jquery.warpdrive.min.js"></script>

	<?php } elseif ($body_classes === 'shine_mozaic') { ?>
		<canvas id="dotty"></canvas>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/shine/mozaic.js"></script>

	<?php } elseif ($body_classes === 'hawking') { ?>
		<canvas id="hawkin-effect" width="1920" height="937"></canvas>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/three.min.js"></script>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/hawking/hawking.js"></script>

	<?php } elseif ($body_classes === 'space_war') { ?>
		<canvas id="space-war" width="1920" height="937"></canvas>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/three.min.js"></script>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/TweenMax.min.js"></script>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/space-war/space-war.js"></script>

	<?php } elseif ($body_classes === 'constellation') { ?>
		<canvas id="constellationel" width="1920" height="937"></canvas>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/constellation/constellation.js"></script>

	<?php } elseif ($body_classes === 'space') { ?>
		<div id="space-bg">
			<canvas width="1920" height="937"></canvas>
			<canvas width="1920" height="937"></canvas>
			<canvas width="1920" height="937"></canvas>
		</div>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/space/space.js"></script>

	<?php } elseif ($body_classes === 'space_time') { ?>
		<canvas id="space_time" width="1920" height="937"></canvas>
		<script type="x-shader/x-vertex" id="wrapVertexShader" src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/wrapVertexShader.js"></script>
		<script type="x-shader/x-fragment" id="wrapFragmentShader" src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/wrapFragmentShader.js"></script>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/three.min.js"></script>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/TweenMax.min.js"></script>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/space-time/spacetime.js"></script>

	<?php } elseif ($body_classes === 'color_birds') { ?>
		<div id="vantajs" style="height: 400px;"></div>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/birds/highlight.min.js"></script>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/birds/bird-three.min.js"></script>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/birds/vanta.birds.min.js"></script>
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/birds/birds.min.js"></script>

	<?php } elseif ($body_classes === 'animated_background') { ?>
		<div class="bg-screen">
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
			style="margin:auto;background:#f1f2f3;display:block;z-index:1;position:relative" width="1920" height="969"
			preserveAspectRatio="xMidYMid" viewBox="0 0 1920 969">
			<g transform="">
				<circle cx="0" cy="0" r="2150.665245918109" fill="#00a950" fill-opacity="0.4">
					<animateTransform attributeName="transform" type="scale" repeatCount="indefinite" dur="2s" begin="-2s"
					keyTimes="0;0.5;1" values="0.943;1.057;0.943" keySplines="0.4 0 0.6 1;0.4 0 0.6 1" calcMode="spline"></animateTransform>
				</circle>

				<circle cx="0" cy="0" r="1955.1502235619173" fill="#00a360" fill-opacity="0.4">
					<animateTransform attributeName="transform" type="scale" repeatCount="indefinite" dur="2s"
					begin="-1.8181818181818181s" keyTimes="0;0.5;1" values="0.943;1.057;0.943"
					keySplines="0.4 0 0.6 1;0.4 0 0.6 1" calcMode="spline"></animateTransform>
				</circle>

				<circle cx="0" cy="0" r="1759.6352012057257" fill="#009c6e" fill-opacity="0.4">
					<animateTransform attributeName="transform" type="scale" repeatCount="indefinite" dur="2s"
					begin="-1.6363636363636365s" keyTimes="0;0.5;1" values="0.943;1.057;0.943"
					keySplines="0.4 0 0.6 1;0.4 0 0.6 1" calcMode="spline"></animateTransform>
				</circle>

				<circle cx="0" cy="0" r="1564.1201788495339" fill="#00957b" fill-opacity="0.4">
					<animateTransform attributeName="transform" type="scale" repeatCount="indefinite" dur="2s"
					begin="-1.4545454545454546s" keyTimes="0;0.5;1" values="0.943;1.057;0.943"
					keySplines="0.4 0 0.6 1;0.4 0 0.6 1" calcMode="spline"></animateTransform>
				</circle>

				<circle cx="0" cy="0" r="1368.605156493342" fill="#008d86" fill-opacity="0.4">
					<animateTransform attributeName="transform" type="scale" repeatCount="indefinite" dur="2s"
					begin="-1.2727272727272727s" keyTimes="0;0.5;1" values="0.943;1.057;0.943"
					keySplines="0.4 0 0.6 1;0.4 0 0.6 1" calcMode="spline"></animateTransform>
				</circle>

				<circle cx="0" cy="0" r="1173.0901341371502" fill="#00848f" fill-opacity="0.4">
					<animateTransform attributeName="transform" type="scale" repeatCount="indefinite" dur="2s"
					begin="-1.0909090909090908s" keyTimes="0;0.5;1" values="0.943;1.057;0.943"
					keySplines="0.4 0 0.6 1;0.4 0 0.6 1" calcMode="spline"></animateTransform>
				</circle>

				<circle cx="0" cy="0" r="977.5751117809587" fill="#007c95" fill-opacity="0.4">
					<animateTransform attributeName="transform" type="scale" repeatCount="indefinite" dur="2s"
					begin="-0.9090909090909091s" keyTimes="0;0.5;1" values="0.943;1.057;0.943"
					keySplines="0.4 0 0.6 1;0.4 0 0.6 1" calcMode="spline"></animateTransform>
				</circle>

				<circle cx="0" cy="0" r="782.0600894247669" fill="#007398" fill-opacity="0.4">
					<animateTransform attributeName="transform" type="scale" repeatCount="indefinite" dur="2s"
					begin="-0.7272727272727273s" keyTimes="0;0.5;1" values="0.943;1.057;0.943"
					keySplines="0.4 0 0.6 1;0.4 0 0.6 1" calcMode="spline"></animateTransform>
				</circle>

				<circle cx="0" cy="0" r="586.5450670685751" fill="#006998" fill-opacity="0.4">
					<animateTransform attributeName="transform" type="scale" repeatCount="indefinite" dur="2s"
					begin="-0.5454545454545454s" keyTimes="0;0.5;1" values="0.943;1.057;0.943"
					keySplines="0.4 0 0.6 1;0.4 0 0.6 1" calcMode="spline"></animateTransform>
				</circle>

				<circle cx="0" cy="0" r="391.03004471238347" fill="#006095" fill-opacity="0.4">
					<animateTransform attributeName="transform" type="scale" repeatCount="indefinite" dur="2s"
					begin="-0.36363636363636365s" keyTimes="0;0.5;1" values="0.943;1.057;0.943"
					keySplines="0.4 0 0.6 1;0.4 0 0.6 1" calcMode="spline"></animateTransform>
				</circle>

				<circle cx="0" cy="0" r="195.51502235619174" fill="#00568f" fill-opacity="0.4">
					<animateTransform attributeName="transform" type="scale" repeatCount="indefinite" dur="2s"
					begin="-0.18181818181818182s" keyTimes="0;0.5;1" values="0.943;1.057;0.943"
					keySplines="0.4 0 0.6 1;0.4 0 0.6 1" calcMode="spline"></animateTransform>
				</circle>

				<circle cx="0" cy="0" r="0" fill="#014c86" fill-opacity="0.4">
					<animateTransform attributeName="transform" type="scale" repeatCount="indefinite" dur="2s" begin="0s"
					keyTimes="0;0.5;1" values="0.943;1.057;0.943" keySplines="0.4 0 0.6 1;0.4 0 0.6 1" calcMode="spline"></animateTransform>
				</circle>
			</g>
		</svg>
	</div>

<?php } elseif ($body_classes === 'line_waves') { ?>
	<canvas id="waves" width="1920" height="937"></canvas>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/line-waves/line-wave.js"></script>

<?php } elseif ($body_classes === 'topology') { ?>
	<div id="topology_bg_effect">
		<canvas id="defaultCanvas0" class="p5Canvas vanta-canvas"></canvas>
	</div>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/p5.min.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/topology/topology.min.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/topology/topology.js"></script>

<?php } elseif ($body_classes === 'halo') { ?>
	<div id="vantajs-bg">
		<canvas class="vanta-canvas" width="1920" height="937"></canvas>
	</div>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/halo/bird-three.min.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/halo/halo.min.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/halo/halo.js"></script>

<?php } elseif ($body_classes === 'smoke_simulation') { ?>
	<canvas id="smoke-simu" width="1920" height="937"></canvas>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/smoke/smoke.js"></script>

<?php } elseif ($body_classes === 'rainbow_box') { ?>
	<canvas id="rainbow-box" width='1920' height='937'></canvas>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/rainbow-box/rainbow-box.js"></script>

<?php } elseif ($body_classes === 'neon_rain') { ?>
	<canvas id="neon-rain" width='1920' height='937'></canvas>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/neon-rain/neon-rain.js"></script>

<?php } elseif ($body_classes === 'color_birds_two') { ?>
	<div id="bird"></div>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/birds/bird-three.min.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/birds/vanta.birds.min.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/birds/blue-bird.js"></script>

<?php } elseif ($body_classes === 'fog') { ?>
	<div id="fog-bg">
		<canvas class="vanta-canvas" width="1920" height="937"></canvas>
	</div>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/fog/bird-three.min.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/fog/fog.min.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/fog/fog.js"></script>

<?php } elseif ($body_classes === 'particle_net') { ?>
	<div id="particle-net-bg">
		<canvas class="vanta-canvas" width="1920" height="937"></canvas>
	</div>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/particle-net/bird-three.min.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/particle-net/particle-net.min.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/particle-net/particle-net.js"></script>

<?php } elseif ($body_classes === 'ripple_cell') { ?>
	<div id="cell-bg">
		<canvas class="vanta-canvas" width="1920" height="937"></canvas>
	</div>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/cell/bird-three.min.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/cell/cell.min.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/cell/cell.js"></script>

<?php } elseif ($body_classes === 'swash_bubble') { ?>
	<canvas id="swash-canvas"></canvas>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/swash-bubble/swash.js"></script>

<?php } elseif ($body_classes === 'interactive_background') { ?>
	<canvas id="inter-background"></canvas>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/three.min.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/animated-background/simplex-noise.min.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/animated-background/chroma.min.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/animated-background/interactive.js"></script>

<?php } elseif ($body_classes === 'chewing_gum') { ?>
	<canvas class="scene" id="chew_gum"></canvas>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/TweenMax.min.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/three.min.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/chewing-gum/perlin.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/chewing-gum/chewing.js"></script>

<?php } elseif ($body_classes === 'univers') { ?>
	<canvas></canvas>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/three.min.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/TweenMax.min.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/universal/univer.js"></script>

<?php } elseif ($body_classes === 'squidematic') { ?>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/squidmate/squid.js"></script>

<?php } elseif ($body_classes === 'orbit_lines') { ?>
	<canvas id="orbit"></canvas>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/orbit/orbit.js"></script>

<?php } elseif ($body_classes === 'hexagon_forming') { ?>
	<canvas id="comb"></canvas>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/hexagon/hexagon.js"></script>

<?php } elseif ($body_classes === 'particle_tails') { ?>
	<canvas id="tails"></canvas>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/particle-tails/tails.js"></script>

<?php } elseif ($body_classes === 'particle_tails_two') { ?>
	<canvas id="tails-two"></canvas>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/particle-tails/dat.gui.min.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/particle-tails/tails-two.js"></script>

<?php } elseif ($body_classes === 'ribbons') { ?>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/p5.min.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/ribbons/ribbon.js"></script>

<?php } elseif ($body_classes === 'rotate_spiral') { ?>
	<canvas id="spiral-rotate"></canvas>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/rotate-spiral/spiral.js"></script>

<?php } elseif ($body_classes === 'birds_three') { ?>
	<canvas id="birds-canv"></canvas>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/birds/birds-two.js"></script>

<?php } elseif ($body_classes === 'particle_random') { ?>
	<canvas id="particle_random" width="1920" height="937"></canvas>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/random-particle/rand-particle.js"></script>

<?php } elseif ($body_classes === 'physics_particle') { ?>
	<canvas class="p-canvas-webgl" id="phycis_particles"></canvas>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/physics-particle/three.min.js"></script>
	<script id="vs-physics-renderer" type="x-shader/x-vertex">
		varying vec2 vUv;

		void main(void) {
		vUv = uv;
		gl_Position = vec4(position, 1.0);
	}
</script>
<script id="fs-physics-renderer-velocity-init" type="x-shader/x-fragment">
	uniform sampler2D velocity;
	varying vec2 vUv;
	void main(void) {
	gl_FragColor = texture2D(velocity, vUv);
}
</script>
<script id="fs-physics-renderer-velocity" type="x-shader/x-fragment">
	uniform sampler2D velocity;
	uniform sampler2D acceleration;
	uniform float time;
	varying vec2 vUv;
	vec3 polar(float radian1, float radian2, float radius) {
	return vec3(
	cos(radian1) * cos(radian2) * radius,
	sin(radian1) * radius,
	cos(radian1) * sin(radian2) * radius
	);
}
void main(void) {
vec3 v = texture2D(acceleration, vUv).xyz + texture2D(velocity, vUv).xyz;
float vStep = step(1000.0, length(v));
gl_FragColor = vec4(
v * (1.0 - vStep) + normalize(v + polar(time, -time, 1.0)) * 80.0 * vStep,
1.0
);
}
</script>
<script id="fs-physics-renderer-acceleration" type="x-shader/x-fragment">
	uniform vec2 resolution;
	uniform sampler2D velocity;
	uniform sampler2D acceleration;
	uniform float time;
	uniform vec2 vTouchMove;
	varying vec2 vUv;
	vec3 mod289(vec3 x)
	{
		return x - floor(x * (1.0 / 289.0)) * 289.0;
	}
	vec4 mod289(vec4 x)
	{
		return x - floor(x * (1.0 / 289.0)) * 289.0;
	}
	vec4 permute(vec4 x)
	{
		return mod289(((x*34.0)+1.0)*x);
	}
	vec4 taylorInvSqrt(vec4 r)
	{
		return 1.79284291400159 - 0.85373472095314 * r;
	}
	vec3 fade(vec3 t) {
	return t*t*t*(t*(t*6.0-15.0)+10.0);
}
// Classic Perlin noise
float cnoise(vec3 P)
{
	vec3 Pi0 = floor(P); // Integer part for indexing
	vec3 Pi1 = Pi0 + vec3(1.0); // Integer part + 1
	Pi0 = mod289(Pi0);
	Pi1 = mod289(Pi1);
	vec3 Pf0 = fract(P); // Fractional part for interpolation
	vec3 Pf1 = Pf0 - vec3(1.0); // Fractional part - 1.0
	vec4 ix = vec4(Pi0.x, Pi1.x, Pi0.x, Pi1.x);
	vec4 iy = vec4(Pi0.yy, Pi1.yy);
	vec4 iz0 = Pi0.zzzz;
	vec4 iz1 = Pi1.zzzz;

	vec4 ixy = permute(permute(ix) + iy);
	vec4 ixy0 = permute(ixy + iz0);
	vec4 ixy1 = permute(ixy + iz1);

	vec4 gx0 = ixy0 * (1.0 / 7.0);
	vec4 gy0 = fract(floor(gx0) * (1.0 / 7.0)) - 0.5;
	gx0 = fract(gx0);
	vec4 gz0 = vec4(0.5) - abs(gx0) - abs(gy0);
	vec4 sz0 = step(gz0, vec4(0.0));
	gx0 -= sz0 * (step(0.0, gx0) - 0.5);
	gy0 -= sz0 * (step(0.0, gy0) - 0.5);

	vec4 gx1 = ixy1 * (1.0 / 7.0);
	vec4 gy1 = fract(floor(gx1) * (1.0 / 7.0)) - 0.5;
	gx1 = fract(gx1);
	vec4 gz1 = vec4(0.5) - abs(gx1) - abs(gy1);
	vec4 sz1 = step(gz1, vec4(0.0));
	gx1 -= sz1 * (step(0.0, gx1) - 0.5);
	gy1 -= sz1 * (step(0.0, gy1) - 0.5);

	vec3 g000 = vec3(gx0.x,gy0.x,gz0.x);
	vec3 g100 = vec3(gx0.y,gy0.y,gz0.y);
	vec3 g010 = vec3(gx0.z,gy0.z,gz0.z);
	vec3 g110 = vec3(gx0.w,gy0.w,gz0.w);
	vec3 g001 = vec3(gx1.x,gy1.x,gz1.x);
	vec3 g101 = vec3(gx1.y,gy1.y,gz1.y);
	vec3 g011 = vec3(gx1.z,gy1.z,gz1.z);
	vec3 g111 = vec3(gx1.w,gy1.w,gz1.w);

	vec4 norm0 = taylorInvSqrt(vec4(dot(g000, g000), dot(g010, g010), dot(g100, g100), dot(g110, g110)));
	g000 *= norm0.x;
	g010 *= norm0.y;
	g100 *= norm0.z;
	g110 *= norm0.w;
	vec4 norm1 = taylorInvSqrt(vec4(dot(g001, g001), dot(g011, g011), dot(g101, g101), dot(g111, g111)));
	g001 *= norm1.x;
	g011 *= norm1.y;
	g101 *= norm1.z;
	g111 *= norm1.w;

	float n000 = dot(g000, Pf0);
	float n100 = dot(g100, vec3(Pf1.x, Pf0.yz));
	float n010 = dot(g010, vec3(Pf0.x, Pf1.y, Pf0.z));
	float n110 = dot(g110, vec3(Pf1.xy, Pf0.z));
	float n001 = dot(g001, vec3(Pf0.xy, Pf1.z));
	float n101 = dot(g101, vec3(Pf1.x, Pf0.y, Pf1.z));
	float n011 = dot(g011, vec3(Pf0.x, Pf1.yz));
	float n111 = dot(g111, Pf1);

	vec3 fade_xyz = fade(Pf0);
	vec4 n_z = mix(vec4(n000, n100, n010, n110), vec4(n001, n101, n011, n111), fade_xyz.z);
	vec2 n_yz = mix(n_z.xy, n_z.zw, fade_xyz.y);
	float n_xyz = mix(n_yz.x, n_yz.y, fade_xyz.x);
	return 2.2 * n_xyz;
}

#define PRECISION 0.000001
vec3 drag(vec3 a, float value) {
return normalize(a * -1.0 + PRECISION) * length(a) * value;
}

void main(void) {
vec3 v = texture2D(velocity, vUv).xyz;
vec3 a = texture2D(acceleration, vUv).xyz;
vec3 d = drag(a, 0.02);
float fx = cnoise(vec3(time * 0.1, v.y / 500.0, v.z / 500.0));
float fy = cnoise(vec3(v.x / 500.0, time * 0.1, v.z / 500.0));
float fz = cnoise(vec3(v.x / 500.0, v.y / 500.0, time * 0.1));
vec3 f1 = vec3(fx, fy, fz) * 0.12;
vec3 f2 = vec3(vTouchMove * 10.0, 0.0);
gl_FragColor = vec4(a + f1 + f2 + d, 1.0);
}
</script>
<script id="vs-points" type="x-shader/x-vertex">
	attribute vec3 position;
	attribute vec2 uvVelocity;
	uniform mat4 modelViewMatrix;
	uniform mat4 projectionMatrix;
	uniform float time;
	uniform sampler2D acceleration;
	uniform sampler2D velocity;
	varying vec3 vAcceleration;
	void main() {
	vec3 a = texture2D(acceleration, uvVelocity).xyz;
	vec3 v = texture2D(velocity, uvVelocity).xyz;
	vec4 mvPosition = modelViewMatrix * vec4(v, 1.0);
	vAcceleration = a;
	gl_PointSize = 1.0 * (1200.0 / length(mvPosition.xyz));
	gl_Position = projectionMatrix * mvPosition;
}
</script>
<script id="fs-points" type="x-shader/x-fragment">
	precision highp float;
	uniform float time;
	varying vec3 vAcceleration;
	vec3 convertHsvToRgb(vec3 c) {
	vec4 K = vec4(1.0, 2.0 / 3.0, 1.0 / 3.0, 3.0);
	vec3 p = abs(fract(c.xxx + K.xyz) * 6.0 - K.www);
	return c.z * mix(K.xxx, clamp(p - K.xxx, 0.0, 1.0), c.y);
}
void main() {
float start = smoothstep(time, 0.0, 1.0);
vec3 n;
n.xy = gl_PointCoord * 2.0 - 1.0;
n.z = 1.0 - dot(n.xy, n.xy);
if (n.z < 0.0) discard;
float aLength = length(vAcceleration);
vec3 color = convertHsvToRgb(vec3(aLength * 0.08 + time * 0.05, 0.5, 0.8));
gl_FragColor = vec4(color, 0.4 * start);
}
</script>
<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/physics-particle/physics.js"></script>

<?php }

if($head_animate === 'bouncy_right_text') { ?>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/animate/animate.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/animate/bouncy-right.js"></script>

<?php } elseif ($head_animate === 'moving_text') { ?>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/animate/animate.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/animate/moving-letters.js"></script>

<?php } elseif ($head_animate === 'slowmo_text') { ?>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/animate/animate.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/animate/slow-letters.js"></script>

<?php } elseif ($head_animate === 'bottom_wavy_text') { ?>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/animate/animate.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/animate/bottom-wavy.js"></script>

<?php } elseif ($head_animate === 'corner_down') { ?>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/animate/animate.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/animate/corner-bottom.js"></script>

<?php } elseif ($head_animate === 'bounce_slide_up') { ?>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/animate/animate.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/animate/bounce-slide-up.js"></script>

<?php } elseif ($head_animate === 'slide_in_left') { ?>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/animate/animate.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/animate/slide-in-left.js"></script>

<?php } elseif ($head_animate === 'rising') { ?>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/animate/animate.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/animate/rising.js"></script>

<?php } elseif ($head_animate === 'fade_in_slide') { ?>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/animate/animate.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/animate/fade-in-slide.js"></script>


<?php }

if ($counter_animate === 'flip_counter') { ?>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/flip-counter/jquery-flip-counter.min.js"></script>
	<script type="text/javascript">
		if (jQuery("#countdown").length>0) {
			var launch_date = "<?php echo $launch_date; ?>";
			var countDownDate = new Date(launch_date);
			var displayDate = countDownDate.getFullYear() + '/' + (countDownDate.getMonth()+1) + '/' + (countDownDate.getDate()) + ' ' +(countDownDate.getHours()) + ':' +  (countDownDate.getMinutes()) + ':' +(countDownDate.getSeconds());
			jQuery("#countdown").flipTimer({
				date: displayDate,
				dayTextNumber:"auto",
				bgColor:"#fff",
				dividerColor:"#666",
				digitColor:"#333",
				textColor:"#fff",
				boxShadow:false,

			            //Expire
			            expireType:"message", //message, hide, redirect
			            message:"Sorry, you are too late!",
			            redirect:""
			        });
		}
	</script>

<?php } elseif ($counter_animate === 'flip_counter_two') { ?>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/flip-counter/flip-counter-two.js"></script>
	<script type="text/javascript">
		function handleTickInit(tick) {
			var launch_date = "<?php echo $launch_date; ?>";
			var countDownDate = new Date(launch_date);
			var displayDate = countDownDate.getFullYear() + '-' + (countDownDate.getMonth()+1) + '-' + (countDownDate.getDate());

			Tick.count.down(displayDate).onupdate = function(value) {
				tick.value = value;
			};
		}
	</script>

<?php }  elseif($counter_animate === 'circle_line_counter' || $counter_animate === 'fill_circle_line') { ?>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/circle-counter/jquery.knob.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/circle-counter/circle-counter.js"></script>
	<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/circle-counter/init.js"></script>
	<script type="text/javascript">
		var launch_date = "<?php echo $launch_date; ?>";
		var countDownDate = new Date(launch_date);
		var displayDate = (countDownDate.getHours()) + ':' +  (countDownDate.getMinutes());

		$(".la-count-down").ccountdown(countDownDate.getFullYear(),(countDownDate.getMonth()+1),(countDownDate.getDate()),displayDate);

	</script>

<?php }  elseif($counter_animate === 'countdown_clock') { ?>
	<script type="text/javascript">
		var launch_date = "<?php echo $launch_date; ?>";
				var target_date = new Date(launch_date).getTime(); // set the countdown date
			</script>
			<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/clock-countdown/clock.js"></script>

		<?php } ?>
		
		<script src="<?php echo LASOON_PUBLIC_PATH; ?>assets/js/lasoon-public.js"></script>
		<script type="text/javascript">
                   	// The data/time we want to countdown to
                   	var launch_date = "<?php echo $launch_date; ?>";
                   	var countDownDate = new Date(launch_date).getTime();

   					 // Run myfunc every second
   					 var myfunc = setInterval(function() {

   					 	var now = new Date().getTime();
   					 	var timeleft = countDownDate - now;

   					 // Calculating the days, hours, minutes and seconds left
   					 var days = Math.floor(timeleft / (1000 * 60 * 60 * 24));
   					 var hours = Math.floor((timeleft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
   					 var minutes = Math.floor((timeleft % (1000 * 60 * 60)) / (1000 * 60));
   					 var seconds = Math.floor((timeleft % (1000 * 60)) / 1000);

   					 // Result is output to the specific element
   					 document.getElementById("day").innerHTML = days;
   					 document.getElementById("hours").innerHTML = hours;
   					 document.getElementById("minutes").innerHTML = minutes;
   					 document.getElementById("seconds").innerHTML = seconds;

    				// Display the message when countdown is over
    				if (timeleft < 0) {
    					clearInterval(myfunc);
    					document.getElementById("day").innerHTML = "0"
    					document.getElementById("hours").innerHTML = "0" 
    					document.getElementById("minutes").innerHTML = "0"
    					document.getElementById("seconds").innerHTML = "0"
    					document.getElementById("end").innerHTML = "TIME UP!!";
    					document.getElementById("end").style.display = "block";
    				}
    			}, 1000);
    		</script>

    	</body>
    	</html>