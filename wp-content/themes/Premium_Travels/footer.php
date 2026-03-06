<footer>
    <div class="container clearfix">
        <div class="row clearfix">

            <!-- Company Links -->
            <div class="col-sm-3 clearfix">
                <h4>Company</h4>
                <?php wp_nav_menu(array(
                    'theme_location' => 'footer_company',
                    'menu_class' => 'menu',
                    'container_class' => 'menu-footer-company-container',
                    'fallback_cb' => false,
                )); ?>
            </div>

            <!-- Services Links -->
            <div class="col-sm-3 clearfix">
                <h4>Our Services</h4>
                <?php wp_nav_menu(array(
                    'theme_location' => 'footer_services',
                    'menu_class' => 'menu',
                    'container_class' => 'menu-footer-service-container',
                    'fallback_cb' => false,
                )); ?>
            </div>

            <!-- Partner + Payment -->
            <div class="col-sm-3 clearfix">
                <h4>Partner With Us</h4>
                <?php wp_nav_menu(array(
                    'theme_location' => 'footer_partner',
                    'menu_class' => 'menu',
                    'container_class' => 'menu-footer-partner-container',
                    'fallback_cb' => false,
                )); ?>
                <div class="clearfix"></div>
                <div class="recognisedby" style="padding-top:0;border-top:none;margin:14px 0 0;border-bottom:none;">
                    <h5 style="border-bottom:1px solid rgba(255,255,255,.1);padding-bottom:8px;margin-bottom:12px;">
                        <i class="fa fa-credit-card" style="color:var(--primary);margin-right:6px;"></i>Payment Options
                    </h5>
                    <!-- Payment icon via Font Awesome chips since images may vary -->
                    <div style="display:flex;flex-wrap:wrap;gap:6px;margin-top:8px;">
                        <span
                            style="background:rgba(255,255,255,.08);color:#fff;font-size:11px;padding:4px 10px;border-radius:6px;border:1px solid rgba(255,255,255,.12);">
                            <i class="fab fa-cc-visa" style="color:#1a56e0;font-size:16px;"></i> Visa
                        </span>
                        <span
                            style="background:rgba(255,255,255,.08);color:#fff;font-size:11px;padding:4px 10px;border-radius:6px;border:1px solid rgba(255,255,255,.12);">
                            <i class="fab fa-cc-mastercard" style="color:#e0221a;font-size:16px;"></i> Mastercard
                        </span>
                        <span
                            style="background:rgba(255,255,255,.08);color:#fff;font-size:11px;padding:4px 10px;border-radius:6px;border:1px solid rgba(255,255,255,.12);">
                            <i class="fab fa-cc-paypal" style="color:#003087;font-size:16px;"></i> PayPal
                        </span>
                        <span
                            style="background:rgba(255,255,255,.08);color:#fff;font-size:11px;padding:4px 10px;border-radius:6px;border:1px solid rgba(255,255,255,.12);">
                            <i class="fa fa-mobile-alt" style="color:#25D366;font-size:14px;"></i> UPI
                        </span>
                        <span
                            style="background:rgba(255,255,255,.08);color:#fff;font-size:11px;padding:4px 10px;border-radius:6px;border:1px solid rgba(255,255,255,.12);">
                            <i class="fa fa-university" style="color:#ffd700;font-size:13px;"></i> Net Banking
                        </span>
                    </div>
                    <!-- Real payment images as bonus (only show if they exist) -->
                    <a href="#" style="display:block;margin-top:10px;">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/payment.png" alt="Payment Options"
                            class="poptions" onerror="this.style.display='none'">
                    </a>
                </div>
            </div>

            <!-- Address -->
            <div class="col-sm-3 clearfix ftcontact">
                <h4>Address</h4>
                <h5><i class="fa fa-building" style="color:var(--primary);margin-right:5px;"></i>Registered Office:</h5>
                <p><i class="fa fa-map-marker-alt"></i>Plot No. 1151, 1st Floor, Royal Palace Campus, Tankapani Road,
                    Near Rabi Talkies, Bhubaneswar, Odisha – 751018</p>
                <p><i class="fa fa-phone-alt"></i><a href="tel:+916743558890">+91-674-355 8890</a></p>

                <h5><i class="fa fa-building" style="color:var(--primary);margin-right:5px;"></i>New Delhi Branch:</h5>
                <p><i class="fa fa-map-marker-alt"></i>B/4, 3rd Floor, Utkalika Building, Baba Kharag Singh Marg,
                    Connaught Place, New Delhi – 110001</p>

                <h5><i class="fa fa-phone-volume" style="color:var(--primary);margin-right:5px;"></i>Support:</h5>
                <p><i class="fa fa-phone-alt"></i><a href="tel:+918337911111">+91 83379 11111</a></p>
                <p><i class="fa fa-phone-alt"></i>Toll Free: <a href="tel:18001208464">1800 120 8464</a></p>
            </div>

        </div><!-- /.row -->

        <!-- Recognised By -->
        <div class="recognisedby">
            <div class="row clearfix">
                <div class="col-sm-7">
                    <h5>
                        <span style="color:var(--primary);display:block;font-size:13px;">Approved By</span>
                        Ministry of Tourism, Govt. of India &amp; Department of Tourism, Govt. of Odisha
                    </h5>
                    <a href="#" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/ministry-of-tourism-govt-of-india-Patra-Tours-And-Travels.jpg"
                            alt="Ministry of Tourism" onerror="this.style.display='none'">
                    </a>
                    <img src="<?php echo get_template_directory_uri(); ?>/images/Odisha-Tourism-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg"
                        alt="Odisha Tourism" onerror="this.style.display='none'">
                </div>
                <div class="col-sm-5">
                    <h5>
                        <span style="color:var(--primary);display:block;font-size:13px;">Recognized By</span>
                        IATO · BMC · EcoTour Odisha · EKTTA
                    </h5>
                    <a href="#" target="_blank">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/IATO-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg"
                            alt="IATO" onerror="this.style.display='none'">
                    </a>
                    <img src="<?php echo get_template_directory_uri(); ?>/images/BMC-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg"
                        alt="BMC" onerror="this.style.display='none'">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/EcoTour-Odisha-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg"
                        alt="EcoTour" onerror="this.style.display='none'">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/Ektta-Approved-Tour-Operator-Patra-Tours-And-Travels.jpg"
                        alt="EKTTA" onerror="this.style.display='none'">
                </div>
            </div>
        </div>

        <!-- Copyright Bar -->
        <div class="copyright clearfix">
            <div class="row">
                <div class="col-sm-6">
                    <div class="smo-icon">
                        <p style="margin:0;display:flex;align-items:center;gap:4px;flex-wrap:wrap;">
                            <span style="color:rgba(255,255,255,.5);font-size:12px;margin-right:6px;">Connect:</span>
                            <a href="https://www.facebook.com/patratoursandtravels" target="_blank" title="Facebook"><i
                                    class="fab fa-facebook-f"></i></a>
                            <a href="https://x.com/patra_travels" target="_blank" title="X"><i
                                    class="fab fa-x-twitter"></i></a>
                            <a href="https://www.instagram.com/patratravels" target="_blank" title="Instagram"><i
                                    class="fab fa-instagram"></i></a>
                            <a href="https://www.youtube.com/@PatraTravelsIndia" target="_blank" title="YouTube"><i
                                    class="fab fa-youtube"></i></a>
                            <a href="https://www.pinterest.com/patratravels" target="_blank" title="Pinterest"><i
                                    class="fab fa-pinterest-p"></i></a>
                        </p>
                    </div>
                </div>
                <div class="col-sm-6 cpy" style="text-align:right;">
                    <p style="margin:0;color:rgba(255,255,255,.45);font-size:12px;">
                        Copyright &copy; <?php echo date('Y'); ?> PATRA TRAVELS PVT LTD | CIN: U79120OD2026PTC052252
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- WhatsApp Floating Button -->
<div class="phone-call">
    <a id="whatsappDynamic" href="https://wa.me/918337911111" target="_blank" title="Chat on WhatsApp">
        <i class="fab fa-whatsapp"></i>
    </a>
</div>

<!-- ===== ENQUIRY POPUP MODAL ===== -->
<div id="enqui-modal" class="pt-modal-overlay" role="dialog" aria-modal="true" aria-labelledby="enquiModalTitle">
    <div class="pt-modal-box">
        <!-- Header -->
        <div class="pt-modal-header">
            <div>
                <h4 id="enquiModalTitle"><i class="fa fa-headset" style="margin-right:8px;"></i>GET IN TOUCH</h4>
                <p>
                    <i class="fab fa-whatsapp" style="margin-right:4px;"></i>
                    <a href="tel:18001208464">1800 120 8464</a> /
                    <a href="tel:+918337911111">+91 83379 11111</a>
                    <span style="font-size:12px;opacity:.85;">&nbsp;(9AM TO 9PM)</span>
                </p>
            </div>
            <button class="pt-modal-close" id="enquiClose" aria-label="Close">&times;</button>
        </div>
        <!-- Body -->
        <div class="pt-modal-body">
            <form id="enquiryForm" onsubmit="ptSubmitEnquiry(event)">
                <div class="pt-form-row">
                    <input type="text" name="first_name" placeholder="First Name *" required>
                    <input type="text" name="last_name" placeholder="Last Name">
                </div>
                <div class="pt-form-row">
                    <input type="email" name="email" placeholder="Your Email *" required>
                    <input type="tel" name="phone" placeholder="Phone Number *" required>
                </div>
                <textarea name="message" rows="4" placeholder="Message..." style="width:100%;"></textarea>
                <div class="pt-form-footer">
                    <span class="pt-mandatory"><i class="fa fa-asterisk"
                            style="color:var(--primary);font-size:10px;"></i> Marked Fields Are Mandatory</span>
                    <button type="submit" class="df-button1" style="padding:10px 28px;">SUBMIT</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php wp_footer(); ?>
</div><!-- .main-wrapper -->

<!-- ===== SCRIPTS: Loader + Dropdown Fix ===== -->
<script type="text/javascript">
    jQuery(window).on('load', function () {
        jQuery('.loader').fadeOut('slow');
    });

    /* ── NAVBAR DROPDOWN ──
       Works with wp_nav_menu() output:
         ul.nav > li > a  (top-level)
         ul.nav > li > ul.sub-menu  (dropdown)
       We add/remove class 'hover' on li; CSS shows ul via opacity/visibility.
    */
    (function ($) {
        var BREAK = 1026; // mobile breakpoint

        function isMobile() { return document.body.clientWidth < BREAK; }

        // Mark items that have sub-menus so CSS can style the arrow
        $('.dropdowns .nav > li').each(function () {
            if ($(this).children('ul').length > 0) {
                $(this).children('a').addClass('parent');
            }
        });

        // Desktop: hover in/out
        function bindDesktop() {
            $('.dropdowns .nav > li').off('mouseenter mouseleave')
                .on('mouseenter', function () { $(this).addClass('hover'); })
                .on('mouseleave', function () { $(this).removeClass('hover'); });
            // unbind click from parent anchors
            $('.dropdowns .nav > li > a.parent').off('click.mobile');
        }

        // Mobile: click to toggle submenu
        function bindMobile() {
            $('.dropdowns .nav > li').off('mouseenter mouseleave');
            $('.dropdowns .nav > li > a.parent').off('click.mobile').on('click.mobile', function (e) {
                e.preventDefault();
                var $li = $(this).parent('li');
                $li.toggleClass('hover');
                $li.siblings().removeClass('hover');
            });
        }

        function adjustMenu() {
            if (isMobile()) {
                $('.toggleMenu').show();
                if (!$('.toggleMenu').hasClass('active')) {
                    $('.dropdowns .nav').hide().removeClass('open');
                }
                bindMobile();
            } else {
                $('.toggleMenu').hide().removeClass('active');
                $('.dropdowns .nav').show().addClass('open');
                $('.dropdowns .nav > li').removeClass('hover');
                bindDesktop();
            }
        }

        // Mobile toggle button
        $('#pt-mobile-toggle').on('click', function (e) {
            e.preventDefault();
            $(this).toggleClass('active');
            $('.dropdowns .nav').slideToggle(250);
        });

        adjustMenu();
        $(window).on('resize orientationchange', function () { adjustMenu(); });

    })(jQuery);
</script>

<script type="text/javascript">
/* ===== ENQUIRY MODAL (custom, no Bootstrap dependency) ===== */
(function () {
    var overlay = document.getElementById('enqui-modal');
    if (!overlay) return;

    /* Open: any button/link with data-target="#enqui-modal" OR class ".enqui-btn" */
    document.addEventListener('click', function (e) {
        var btn = e.target.closest('[data-target="#enqui-modal"], .enqui-btn');
        if (btn) { e.preventDefault(); openModal(); }
    });

    /* Close: × button */
    var closeBtn = document.getElementById('enquiClose');
    if (closeBtn) closeBtn.addEventListener('click', closeModal);

    /* Close: click on overlay */
    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) closeModal();
    });

    /* Close: Escape key */
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeModal();
    });

    function openModal() {
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeModal() {
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    /* Form submit — show thank-you message */
    window.ptSubmitEnquiry = function (e) {
        e.preventDefault();
        var body = document.querySelector('.pt-modal-body');
        body.innerHTML = '<div style="text-align:center;padding:40px 20px;">'
            + '<i class="fa fa-check-circle" style="color:#25D366;font-size:56px;display:block;margin-bottom:16px;"></i>'
            + '<h4 style="color:var(--secondary);margin:0 0 8px;">Thank You!</h4>'
            + '<p style="color:var(--text-mid);font-size:14px;">We have received your enquiry.<br>Our team will contact you shortly.</p>'
            + '</div>';
        setTimeout(closeModal, 3200);
    };
})();

/* ===== TESTIMONIAL CAROUSEL — Auto-scroll + Arrows ===== */
(function () {
    var track = document.getElementById('testiTrack');
    var prev  = document.getElementById('testiPrev');
    var next  = document.getElementById('testiNext');
    if (!track) return;

    var CARD_W   = 318;  // card width (300px) + gap (18px)
    var INTERVAL = 3500; // ms between auto-scrolls
    var timer;
    var paused = false;

    function scrollNext() {
        var maxScroll = track.scrollWidth - track.clientWidth;
        if (track.scrollLeft >= maxScroll - 2) {
            /* Reached end — smoothly jump back to start */
            track.scrollTo({ left: 0, behavior: 'smooth' });
        } else {
            track.scrollBy({ left: CARD_W, behavior: 'smooth' });
        }
    }

    function startAuto() {
        timer = setInterval(function () {
            if (!paused) scrollNext();
        }, INTERVAL);
    }

    function stopAuto() { clearInterval(timer); }

    /* Pause on hover */
    track.addEventListener('mouseenter', function () { paused = true; });
    track.addEventListener('mouseleave', function () { paused = false; });

    /* Pause on touch */
    track.addEventListener('touchstart', function () { paused = true; }, { passive: true });
    track.addEventListener('touchend',   function () {
        setTimeout(function () { paused = false; }, 2000);
    }, { passive: true });

    /* Pause when enquiry modal is open */
    document.addEventListener('click', function (e) {
        if (e.target.closest('#enqui-modal')) paused = true;
    });

    /* Arrow buttons */
    if (next) next.addEventListener('click', function () { paused = true; track.scrollBy({ left: CARD_W, behavior: 'smooth' }); setTimeout(function(){ paused = false; }, 2500); });
    if (prev) prev.addEventListener('click', function () { paused = true; track.scrollBy({ left: -CARD_W, behavior: 'smooth' }); setTimeout(function(){ paused = false; }, 2500); });

    startAuto();
})();
</script>

</body>

</html>
