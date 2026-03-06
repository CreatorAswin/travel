<?php
/**
 * Template Name: Cancel Reservation
 */

get_header();
?>

<div class="main-contant">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading text-center">
                    <h1 class="title">Cancel <span class="text-color">Reservation</span></h1>
                    <p>We're sorry to see you go. Please fill out the form below to cancel your booking.</p>
                </div>
            </div>
        </div>

        <div class="row" style="margin-top: 30px;">
            <div class="col-md-8 col-md-offset-2">
                <div class="cancellation-form" style="background: #f5f5f5; padding: 30px; border-radius: 5px;">
                    <form action="#" method="POST">
                        <div class="form-group">
                            <label>Booking Reference Number *</label>
                            <input type="text" name="booking_reference" class="form-control"
                                placeholder="Enter your booking reference" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Full Name *</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email *</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone Number *</label>
                                    <input type="tel" name="phone" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Booking Date</label>
                                    <input type="date" name="booking_date" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Reason for Cancellation *</label>
                            <select name="cancellation_reason" class="form-control" required>
                                <option value="">Select Reason</option>
                                <option value="Change of Plans">Change of Plans</option>
                                <option value="Found Better Deal">Found Better Deal</option>
                                <option value="Emergency">Emergency</option>
                                <option value="Service Issue">Service Issue</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Additional Comments</label>
                            <textarea name="comments" class="form-control" rows="4"
                                placeholder="Please provide any additional details..."></textarea>
                        </div>

                        <div class="form-group">
                            <label class="checkbox-inline">
                                <input type="checkbox" name="agree_terms" required> I have read and agree to the <a
                                    href="#cancellation-policy">cancellation policy</a>
                            </label>
                        </div>

                        <button type="submit" class="df-button1 btn-block">Submit Cancellation Request</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Cancellation Policy -->
        <div class="row" style="margin-top: 50px;" id="cancellation-policy">
            <div class="col-md-10 col-md-offset-1">
                <div class="policy-box"
                    style="background: #fff; border: 1px solid #ddd; padding: 30px; border-radius: 5px;">
                    <h3 class="text-center">Cancellation Policy</h3>

                    <h4 style="margin-top: 30px;">Cancellation Charges</h4>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Time Before Journey</th>
                                <th>Cancellation Charges</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>More than 48 hours</td>
                                <td>10% of booking amount</td>
                            </tr>
                            <tr>
                                <td>24-48 hours</td>
                                <td>25% of booking amount</td>
                            </tr>
                            <tr>
                                <td>12-24 hours</td>
                                <td>50% of booking amount</td>
                            </tr>
                            <tr>
                                <td>Less than 12 hours</td>
                                <td>100% of booking amount (No Refund)</td>
                            </tr>
                        </tbody>
                    </table>

                    <h4 style="margin-top: 30px;">Important Notes</h4>
                    <ul style="line-height: 2;">
                        <li>Refunds will be processed within 7-10 working days</li>
                        <li>Cancellation charges are applicable as per the time of cancellation request</li>
                        <li>For package tours, different cancellation policies may apply</li>
                        <li>In case of no-show, full booking amount will be forfeited</li>
                        <li>Refund will be made to the original payment method</li>
                        <li>For any queries, please contact our customer support</li>
                    </ul>

                    <div class="text-center" style="margin-top: 30px;">
                        <p><strong>Need Help?</strong> Contact us at <a href="tel:18001208464">1800 120 8464</a> or <a
                                href="mailto:support@patratravels.com">support@patratravels.com</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>