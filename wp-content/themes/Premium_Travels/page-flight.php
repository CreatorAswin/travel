<?php
/**
 * Template Name: Flight Booking
 */

get_header();
?>

<div class="main-contant">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-heading text-center">
                    <h1 class="title">Flight <span class="text-color">Booking</span></h1>
                    <p>Book domestic and international flights at the best prices</p>
                </div>
            </div>
        </div>

        <!-- Flight Search Form -->
        <div class="row" style="margin-top: 30px;">
            <div class="col-md-10 col-md-offset-1">
                <div class="flight-search-form"
                    style="background: #fff; border: 2px solid #f8580e; padding: 30px; border-radius: 10px;">
                    <form action="#" method="POST">
                        <!-- Trip Type -->
                        <div class="form-group">
                            <label class="radio-inline">
                                <input type="radio" name="trip_type" value="one_way" checked> One Way
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="trip_type" value="round_trip"> Round Trip
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="trip_type" value="multi_city"> Multi City
                            </label>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>From (City or Airport) *</label>
                                    <input type="text" name="from" class="form-control"
                                        placeholder="e.g., Bhubaneswar (BBI)" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>To (City or Airport) *</label>
                                    <input type="text" name="to" class="form-control" placeholder="e.g., Delhi (DEL)"
                                        required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Departure Date *</label>
                                    <input type="date" name="departure_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Return Date</label>
                                    <input type="date" name="return_date" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Adults *</label>
                                    <select name="adults" class="form-control" required>
                                        <option value="1">1 Adult</option>
                                        <option value="2">2 Adults</option>
                                        <option value="3">3 Adults</option>
                                        <option value="4">4 Adults</option>
                                        <option value="5">5 Adults</option>
                                        <option value="6">6+ Adults</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Children (2-12 yrs)</label>
                                    <select name="children" class="form-control">
                                        <option value="0">0 Children</option>
                                        <option value="1">1 Child</option>
                                        <option value="2">2 Children</option>
                                        <option value="3">3 Children</option>
                                        <option value="4">4+ Children</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Infants (Below 2 yrs)</label>
                                    <select name="infants" class="form-control">
                                        <option value="0">0 Infants</option>
                                        <option value="1">1 Infant</option>
                                        <option value="2">2 Infants</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Class *</label>
                                    <select name="class" class="form-control" required>
                                        <option value="Economy">Economy</option>
                                        <option value="Premium Economy">Premium Economy</option>
                                        <option value="Business">Business Class</option>
                                        <option value="First">First Class</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Preferred Airline</label>
                                    <select name="airline" class="form-control">
                                        <option value="">Any Airline</option>
                                        <option value="Air India">Air India</option>
                                        <option value="IndiGo">IndiGo</option>
                                        <option value="SpiceJet">SpiceJet</option>
                                        <option value="Vistara">Vistara</option>
                                        <option value="GoAir">GoAir</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="df-button1" style="padding: 12px 50px;">Search Flights</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Why Book With Us -->
        <div class="row" style="margin-top: 50px;">
            <div class="col-md-12">
                <h3 class="text-center">Why Book Flights With <span class="text-color">Patra Travels</span></h3>
            </div>
            <div class="col-md-3 col-sm-6 text-center" style="margin-top: 30px;">
                <i class="fa fa-tag" style="font-size: 48px; color: #f8580e;"></i>
                <h4>Best Prices</h4>
                <p>Competitive fares and exclusive deals</p>
            </div>
            <div class="col-md-3 col-sm-6 text-center" style="margin-top: 30px;">
                <i class="fa fa-plane" style="font-size: 48px; color: #f8580e;"></i>
                <h4>Wide Network</h4>
                <p>Access to all major airlines</p>
            </div>
            <div class="col-md-3 col-sm-6 text-center" style="margin-top: 30px;">
                <i class="fa fa-clock-o" style="font-size: 48px; color: #f8580e;"></i>
                <h4>Quick Booking</h4>
                <p>Fast and easy booking process</p>
            </div>
            <div class="col-md-3 col-sm-6 text-center" style="margin-top: 30px;">
                <i class="fa fa-headphones" style="font-size: 48px; color: #f8580e;"></i>
                <h4>24/7 Support</h4>
                <p>Round the clock customer assistance</p>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>