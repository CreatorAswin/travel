<?php
/**
 * Template Name: Forex Services
 */

get_header();
?>

<div class="main-contant">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading text-center">
                    <h1 class="title">Forex <span class="text-color">Services</span></h1>
                    <p>Get the best foreign exchange rates for your international travel</p>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 30px;">
            <div class="col-md-6">
                <h3>Our Forex Services</h3>
                <ul style="font-size: 16px; line-height: 2;">
                    <li><i class="fa fa-check text-color"></i> Foreign Currency Exchange</li>
                    <li><i class="fa fa-check text-color"></i> Forex Cards</li>
                    <li><i class="fa fa-check text-color"></i> Traveler's Cheques</li>
                    <li><i class="fa fa-check text-color"></i> Money Transfer Services</li>
                    <li><i class="fa fa-check text-color"></i> Competitive Exchange Rates</li>
                    <li><i class="fa fa-check text-color"></i> Quick & Hassle-free Process</li>
                </ul>

                <h3 style="margin-top: 30px;">Popular Currencies</h3>
                <div class="row">
                    <div class="col-md-6">
                        <ul style="font-size: 16px; line-height: 2;">
                            <li>US Dollar (USD)</li>
                            <li>Euro (EUR)</li>
                            <li>British Pound (GBP)</li>
                            <li>Australian Dollar (AUD)</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul style="font-size: 16px; line-height: 2;">
                            <li>Canadian Dollar (CAD)</li>
                            <li>Singapore Dollar (SGD)</li>
                            <li>UAE Dirham (AED)</li>
                            <li>Thai Baht (THB)</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="forex-form" style="background: #f5f5f5; padding: 30px; border-radius: 5px;">
                    <h3>Forex Enquiry Form</h3>
                    <form action="#" method="POST">
                        <div class="form-group">
                            <label>Full Name *</label>
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
                            <label>Currency Required *</label>
                            <select name="currency" class="form-control" required>
                                <option value="">Select Currency</option>
                                <option value="USD">US Dollar (USD)</option>
                                <option value="EUR">Euro (EUR)</option>
                                <option value="GBP">British Pound (GBP)</option>
                                <option value="AUD">Australian Dollar (AUD)</option>
                                <option value="CAD">Canadian Dollar (CAD)</option>
                                <option value="SGD">Singapore Dollar (SGD)</option>
                                <option value="AED">UAE Dirham (AED)</option>
                                <option value="THB">Thai Baht (THB)</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Amount (INR) *</label>
                            <input type="number" name="amount" class="form-control" min="1" required>
                        </div>
                        <div class="form-group">
                            <label>Service Type *</label>
                            <select name="service_type" class="form-control" required>
                                <option value="">Select Service</option>
                                <option value="Currency Exchange">Currency Exchange</option>
                                <option value="Forex Card">Forex Card</option>
                                <option value="Traveler's Cheque">Traveler's Cheque</option>
                                <option value="Money Transfer">Money Transfer</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Travel Date</label>
                            <input type="date" name="travel_date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Additional Information</label>
                            <textarea name="message" class="form-control" rows="3"></textarea>
                        </div>
                        <button type="submit" class="df-button1 btn-block">Submit Enquiry</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 50px;">
            <div class="col-md-12">
                <div class="info-box" style="background: #fff3cd; padding: 20px; border-left: 4px solid #f8580e;">
                    <h4><i class="fa fa-info-circle"></i> Important Information</h4>
                    <ul>
                        <li>Please carry valid ID proof and passport for forex transactions</li>
                        <li>Exchange rates are subject to market fluctuations</li>
                        <li>Minimum transaction amount may apply for certain currencies</li>
                        <li>For bulk orders, please contact us in advance</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>