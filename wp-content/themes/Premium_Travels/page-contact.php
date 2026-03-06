<?php
/**
 * Template Name: Contact Us
 */

get_header();
?>

<div class="main-contant">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading text-center">
                    <h1 class="title">Contact <span class="text-color">Us</span></h1>
                    <p>Get in touch with us for any queries or assistance</p>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 30px;">
            <!-- Contact Form -->
            <div class="col-md-8">
                <div class="contact-form" style="background: #f5f5f5; padding: 30px; border-radius: 5px;">
                    <h3>Send us a Message</h3>
                    <form action="#" method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>First Name *</label>
                                    <input type="text" name="first_name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Last Name *</label>
                                    <input type="text" name="last_name" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email *</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone *</label>
                                    <input type="tel" name="phone" class="form-control" required>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Subject *</label>
                            <input type="text" name="subject" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Message *</label>
                            <textarea name="message" class="form-control" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="df-button1">Send Message</button>
                    </form>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="col-md-4">
                <div class="contact-info">
                    <h3>Contact Information</h3>

                    <div class="info-item" style="margin-bottom: 20px;">
                        <h4><i class="fa fa-map-marker text-color"></i> Office Address</h4>
                        <p>Plot No-1249/2488, Nayapalli<br>
                            Bhubaneswar, Odisha - 751012<br>
                            India</p>
                    </div>

                    <div class="info-item" style="margin-bottom: 20px;">
                        <h4><i class="fa fa-phone text-color"></i> Phone</h4>
                        <p>Toll Free: 1800 120 8464<br>
                            Mobile: +91 83379 11111</p>
                    </div>

                    <div class="info-item" style="margin-bottom: 20px;">
                        <h4><i class="fa fa-envelope text-color"></i> Email</h4>
                        <p>info@patratravels.com<br>
                            support@patratravels.com</p>
                    </div>

                    <div class="info-item" style="margin-bottom: 20px;">
                        <h4><i class="fa fa-clock-o text-color"></i> Working Hours</h4>
                        <p>Monday - Sunday<br>
                            9:00 AM - 9:00 PM</p>
                    </div>

                    <div class="social-links" style="margin-top: 30px;">
                        <h4>Follow Us</h4>
                        <a href="#" style="font-size: 24px; margin-right: 15px; color: #3b5998;"><i
                                class="fa fa-facebook"></i></a>
                        <a href="#" style="font-size: 24px; margin-right: 15px; color: #1da1f2;"><i
                                class="fa fa-twitter"></i></a>
                        <a href="#" style="font-size: 24px; margin-right: 15px; color: #e1306c;"><i
                                class="fa fa-instagram"></i></a>
                        <a href="#" style="font-size: 24px; margin-right: 15px; color: #0077b5;"><i
                                class="fa fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Map Section -->
        <div class="row" style="margin-top: 50px;">
            <div class="col-md-12">
                <h3 class="text-center">Find Us on Map</h3>
                <div class="map-container"
                    style="margin-top: 20px; height: 400px; background: #ddd; border-radius: 5px;">
                    <!-- Google Maps iframe would go here -->
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3742.2!2d85.8!3d20.3!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjDCsDE4JzAwLjAiTiA4NcKwNDgnMDAuMCJF!5e0!3m2!1sen!2sin!4v1234567890"
                        width="100%" height="400" style="border:0; border-radius: 5px;" allowfullscreen=""
                        loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>