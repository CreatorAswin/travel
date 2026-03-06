<?php
/**
 * Template Name: Visa Assistance
 */

get_header();
?>

<div class="main-contant">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading text-center">
                    <h1 class="title">Visa <span class="text-color">Assistance</span></h1>
                    <p>We help you get your visa processed quickly and hassle-free</p>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 30px;">
            <div class="col-md-6">
                <h3>Our Visa Services</h3>
                <ul style="font-size: 16px; line-height: 2;">
                    <li><i class="fa fa-check text-color"></i> Tourist Visa</li>
                    <li><i class="fa fa-check text-color"></i> Business Visa</li>
                    <li><i class="fa fa-check text-color"></i> Student Visa</li>
                    <li><i class="fa fa-check text-color"></i> Work Visa</li>
                    <li><i class="fa fa-check text-color"></i> Transit Visa</li>
                    <li><i class="fa fa-check text-color"></i> Document Verification</li>
                    <li><i class="fa fa-check text-color"></i> Application Assistance</li>
                    <li><i class="fa fa-check text-color"></i> Interview Preparation</li>
                </ul>

                <h3 style="margin-top: 30px;">Popular Destinations</h3>
                <div class="row">
                    <div class="col-md-6">
                        <ul style="font-size: 16px; line-height: 2;">
                            <li>USA</li>
                            <li>UK</li>
                            <li>Canada</li>
                            <li>Australia</li>
                            <li>Schengen Countries</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul style="font-size: 16px; line-height: 2;">
                            <li>Singapore</li>
                            <li>Dubai (UAE)</li>
                            <li>Thailand</li>
                            <li>Malaysia</li>
                            <li>New Zealand</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="visa-form" style="background: #f5f5f5; padding: 30px; border-radius: 5px;">
                    <h3>Visa Assistance Form</h3>
                    <form action="#" method="POST">
                        <div class="form-group">
                            <label>Full Name (as per Passport) *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Phone *</label>
                            <input type="tel" name="phone" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Destination Country *</label>
                            <select name="country" class="form-control selectpicker" data-live-search="true" required>
                                <option value="">Select Country</option>
                                <option value="USA">USA</option>
                                <option value="UK">United Kingdom</option>
                                <option value="Canada">Canada</option>
                                <option value="Australia">Australia</option>
                                <option value="Schengen">Schengen Countries</option>
                                <option value="Singapore">Singapore</option>
                                <option value="UAE">Dubai (UAE)</option>
                                <option value="Thailand">Thailand</option>
                                <option value="Malaysia">Malaysia</option>
                                <option value="New Zealand">New Zealand</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Visa Type *</label>
                            <select name="visa_type" class="form-control" required>
                                <option value="">Select Visa Type</option>
                                <option value="Tourist">Tourist Visa</option>
                                <option value="Business">Business Visa</option>
                                <option value="Student">Student Visa</option>
                                <option value="Work">Work Visa</option>
                                <option value="Transit">Transit Visa</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Intended Travel Date</label>
                            <input type="date" name="travel_date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Do you have a valid passport?</label>
                            <select name="passport_status" class="form-control">
                                <option value="Yes">Yes</option>
                                <option value="No">No</option>
                                <option value="Expired">Expired</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Additional Information</label>
                            <textarea name="message" class="form-control" rows="3"
                                placeholder="Any specific requirements or questions..."></textarea>
                        </div>
                        <button type="submit" class="df-button1 btn-block">Submit Application</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 50px;">
            <div class="col-md-12">
                <h3 class="text-center">Why Choose Our Visa Services?</h3>
            </div>
            <div class="col-md-3 text-center">
                <i class="fa fa-users" style="font-size: 48px; color: #f8580e;"></i>
                <h4>Expert Guidance</h4>
                <p>Experienced visa consultants</p>
            </div>
            <div class="col-md-3 text-center">
                <i class="fa fa-clock-o" style="font-size: 48px; color: #f8580e;"></i>
                <h4>Quick Processing</h4>
                <p>Fast-track visa services</p>
            </div>
            <div class="col-md-3 text-center">
                <i class="fa fa-shield" style="font-size: 48px; color: #f8580e;"></i>
                <h4>High Success Rate</h4>
                <p>Proven track record</p>
            </div>
            <div class="col-md-3 text-center">
                <i class="fa fa-headphones" style="font-size: 48px; color: #f8580e;"></i>
                <h4>24/7 Support</h4>
                <p>Always here to help</p>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>