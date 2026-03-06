<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <!-- Page Loader -->
    <div class="loader"></div>

    <div class="main-wrapper">
        <!-- =============== TOP HEADER =============== -->
        <div class="container hedr">
            <header id="header">
                <div class="inner-header">
                    <div class="tophead-container">

                        <!-- Logo -->
                        <div class="col-md-4 col-sm-4 col-xs-12" style="padding:0;">
                            <h1 class="logo" style="margin:0;">
                                <a href="<?php echo esc_url(home_url('/')); ?>">
                                    <img src="<?php echo get_template_directory_uri(); ?>/images/PatraTravelsLogo.png"
                                        alt="<?php bloginfo('name'); ?>" style="max-height:70px;width:auto;"
                                        onerror="this.onerror=null;this.style.display='none';this.parentNode.innerHTML+='<span class=\'logo-text-fallback\'>Patra<span>Travels</span></span>';">
                                </a>
                            </h1>
                        </div>

                        <!-- Contact & Social -->
                        <div class="col-md-6 col-sm-7 col-xs-12 head-cont">
                            <div class="pull">
                                <div class="col-md-1 col-sm-12 col-xs-12 mnss-hid">&nbsp;</div>
                                <div class="col-md-6 col-sm-7 col-xs-12 cs-spt-sec">
                                    <p class="phonehdr phonehdr1"><span>Customer Support (Toll Free)</span></p>
                                    <p class="phonehdr"><a href="tel:18001208464"><i class="fa fa-phone-alt"
                                                style="color:var(--primary,#f8580e);margin-right:5px;"></i>1800 120
                                            8464</a></p>
                                    <p class="whatsapphdr">
                                        <a href="tel:+918337911111">
                                            <i class="fab fa-whatsapp" style="color:#25D366;margin-right:5px;"></i>+91
                                            83379 11111
                                        </a>
                                    </p>
                                </div>
                                <div class="col-md-5 col-sm-5 col-xs-12 phonehdr1">
                                    <div style="clear:both;"></div>
                                    <ul class="social-icons">
                                        <li><a href="https://www.facebook.com/patratoursandtravels" target="_blank"
                                                title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                                        <li><a href="https://x.com/patra_travels" target="_blank" title="Twitter/X"><i
                                                    class="fab fa-x-twitter"></i></a></li>
                                        <li><a href="https://www.instagram.com/patratravels" target="_blank"
                                                title="Instagram"><i class="fab fa-instagram"></i></a></li>
                                        <li><a href="https://www.youtube.com/@PatraTravelsIndia" target="_blank"
                                                title="YouTube"><i class="fab fa-youtube"></i></a></li>
                                        <li><a href="https://www.pinterest.com/patratravels" target="_blank"
                                                title="Pinterest"><i class="fab fa-pinterest-p"></i></a></li>
                                    </ul>
                                    <div style="margin-top:6px;padding-left:2px;">
                                        <a href="mailto:sales@patratravels.com"
                                            style="font-size:12px;color:#555;text-decoration:none;">
                                            <i class="fa fa-envelope"
                                                style="color:var(--primary,#f8580e);margin-right:5px;"></i>sales@patratravels.com
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- WhatsApp Button -->
                        <div class="col-md-2 col-sm-1 col-xs-12 pp" style="margin-top:18px;padding:0;">
                            <a href="https://wa.me/918337911111" target="_blank" class="wappicon">
                                <i class="fab fa-whatsapp"></i>WhatsApp
                            </a>
                        </div>

                    </div>
                </div>
            </header>
        </div>
        <div style="clear:both;"></div>

        <!-- =============== NAVIGATION BAR =============== -->
        <div class="bottom-bar">
            <div class="container">
                <div class="navigation-outer">
                    <div class="dropdowns">
                        <!-- Mobile toggle -->
                        <a class="toggleMenu" href="#" id="pt-mobile-toggle">
                            <i class="fa fa-bars"></i> Menu
                        </a>

                        <?php
wp_nav_menu(array(
    'theme_location' => 'primary',
    'menu_class' => 'nav',
    'container' => false,
    'fallback_cb' => false,
));
?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Floating Sidebar Buttons (stacked, no overlap) -->
        <div class="pt-side-btns">
            <div class="sid-btn">
                <button type="button" class="enqui-btn" data-toggle="modal" data-target="#enqui-modal">Enquiry</button>
            </div>
        </div>