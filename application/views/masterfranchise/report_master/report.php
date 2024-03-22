<?php include(dirname(__FILE__).'/../franchise_shared/franchise_header.php'); ?>
<?php include(dirname(__FILE__).'/../franchise_shared/franchise_sidebar.php'); ?>
<main>
    <!-- START: Card Data-->
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-body">
                <div class="row p-2">
                    <div class="col-md-6">
                        <h6 class=""> <i class="fa-solid fa-star"></i> Tracking</h6>
                    </div>
                    <div class="col-md-12">
                        <a href="" class="btn btn-light">Custom Reports</a>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-12">
                        <label>Date Range</label>
                        <div class="form-group">
                            <input type="date" class="form-contol"> TO <input type="date" class="form-contol">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <style>
                        .custom-control-label-new {
                            position: relative;
                            margin-bottom: 0;
                            vertical-align: top;
                        }
                    </style>

                    <div class="custom-control custom-checkbox custom-control-inline">
                        <input class="form-check-input" type="checkbox" value="" id="select_all_checkboxes">
                        <label class="" for="select_all_checkboxes">
                            Select All
                        </label>
                    </div>

                    <div class="col-sm-12 mb-1 pb-1 mt-1">
                        <b>Orders</b>
                    </div>
                    <div class="col-sm-12 border-bottom pb-4 mb-4">
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="order_number" class="custom-control-input multiple_checkboxes" id="customCheckDisabledorder_number">
                            <label class="custom-control-label" for="customCheckDisabledorder_number">#Number</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="order_date" class="custom-control-input multiple_checkboxes" id="customCheckDisabledorder_date">
                            <label class="custom-control-label" for="customCheckDisabledorder_date">Order Date</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="order_amount" class="custom-control-input multiple_checkboxes" id="customCheckDisabledorder_amount">
                            <label class="custom-control-label" for="customCheckDisabledorder_amount">Amount</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="order_payment_type" class="custom-control-input multiple_checkboxes" id="customCheckDisabledorder_payment_type">
                            <label class="custom-control-label" for="customCheckDisabledorder_payment_type">Payment Type</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="shipping_fname" class="custom-control-input multiple_checkboxes" id="customCheckDisabledshipping_fname">
                            <label class="custom-control-label" for="customCheckDisabledshipping_fname">First Name</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="shipping_lname" class="custom-control-input multiple_checkboxes" id="customCheckDisabledshipping_lname">
                            <label class="custom-control-label" for="customCheckDisabledshipping_lname">Last Name</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="shipping_address" class="custom-control-input multiple_checkboxes" id="customCheckDisabledshipping_address">
                            <label class="custom-control-label" for="customCheckDisabledshipping_address">Address 1</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="shipping_address_2" class="custom-control-input multiple_checkboxes" id="customCheckDisabledshipping_address_2">
                            <label class="custom-control-label" for="customCheckDisabledshipping_address_2">Address 2</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="shipping_phone" class="custom-control-input multiple_checkboxes" id="customCheckDisabledshipping_phone">
                            <label class="custom-control-label" for="customCheckDisabledshipping_phone">Phone</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="shipping_city" class="custom-control-input multiple_checkboxes" id="customCheckDisabledshipping_city">
                            <label class="custom-control-label" for="customCheckDisabledshipping_city">City</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="shipping_state" class="custom-control-input multiple_checkboxes" id="customCheckDisabledshipping_state">
                            <label class="custom-control-label" for="customCheckDisabledshipping_state">State</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="shipping_zip" class="custom-control-input multiple_checkboxes" id="customCheckDisabledshipping_zip">
                            <label class="custom-control-label" for="customCheckDisabledshipping_zip">Pincode</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="package_weight" class="custom-control-input multiple_checkboxes" id="customCheckDisabledpackage_weight">
                            <label class="custom-control-label" for="customCheckDisabledpackage_weight">Weight</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="package_length" class="custom-control-input multiple_checkboxes" id="customCheckDisabledpackage_length">
                            <label class="custom-control-label" for="customCheckDisabledpackage_length">Length</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="package_height" class="custom-control-input multiple_checkboxes" id="customCheckDisabledpackage_height">
                            <label class="custom-control-label" for="customCheckDisabledpackage_height">Height</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="package_breadth" class="custom-control-input multiple_checkboxes" id="customCheckDisabledpackage_breadth">
                            <label class="custom-control-label" for="customCheckDisabledpackage_breadth">Breadth</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="fulfillment_status" class="custom-control-input multiple_checkboxes" id="customCheckDisabledfulfillment_status">
                            <label class="custom-control-label" for="customCheckDisabledfulfillment_status">Order Status</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="shipping_charges" class="custom-control-input multiple_checkboxes" id="customCheckDisabledshipping_charges">
                            <label class="custom-control-label" for="customCheckDisabledshipping_charges">Shipping Charges</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="discount" class="custom-control-input multiple_checkboxes" id="customCheckDisableddiscount">
                            <label class="custom-control-label" for="customCheckDisableddiscount">Discount Applied</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="products" class="custom-control-input multiple_checkboxes" id="customCheckDisabledproducts">
                            <label class="custom-control-label" for="customCheckDisabledproducts">Product Details</label>
                        </div>
                    </div>
                    <div class="col-sm-12 mb-1 pb-1 mt-1">
                        <b>Shipment</b>
                    </div>
                    <div class="col-sm-12 border-bottom pb-4 mb-4">
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="shipment_date" class="custom-control-input multiple_checkboxes" id="customCheckDisabledshipment_date">
                            <label class="custom-control-label" for="customCheckDisabledshipment_date">Shipment Date</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="awb_number" class="custom-control-input multiple_checkboxes" id="customCheckDisabledawb_number">
                            <label class="custom-control-label" for="customCheckDisabledawb_number">AWB Number</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="ship_status" class="custom-control-input multiple_checkboxes" id="customCheckDisabledship_status">
                            <label class="custom-control-label" for="customCheckDisabledship_status">Shipment Status</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="remittance_id" class="custom-control-input multiple_checkboxes" id="customCheckDisabledremittance_id">
                            <label class="custom-control-label" for="customCheckDisabledremittance_id">Remittance ID</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="pickup_time" class="custom-control-input multiple_checkboxes" id="customCheckDisabledpickup_time">
                            <label class="custom-control-label" for="customCheckDisabledpickup_time">Pickup Time</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="delivered_time" class="custom-control-input multiple_checkboxes" id="customCheckDisableddelivered_time">
                            <label class="custom-control-label" for="customCheckDisableddelivered_time">Delivered Time</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="charged_weight" class="custom-control-input multiple_checkboxes" id="customCheckDisabledcharged_weight">
                            <label class="custom-control-label" for="customCheckDisabledcharged_weight">Charged Weight</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="zone" class="custom-control-input multiple_checkboxes" id="customCheckDisabledzone">
                            <label class="custom-control-label" for="customCheckDisabledzone">Zone</label>
                        </div>
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="status_updated_at" class="custom-control-input multiple_checkboxes" id="customCheckDisabledstatus_updated_at">
                            <label class="custom-control-label" for="customCheckDisabledstatus_updated_at">Last Status Updated</label>
                        </div>
                    </div>
                    <div class="col-sm-12 mb-1 pb-1 mt-1">
                        <b>Ndr</b>
                    </div>
                    <div class="col-sm-12 border-bottom pb-4 mb-4">
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="checkbox" name="fields[]" value="attempts" class="custom-control-input multiple_checkboxes" id="customCheckDisabledattempts">
                            <label class="custom-control-label" for="customCheckDisabledattempts">NDR Attempts Info</label>
                        </div>
                    </div>

                    <div class="row mb-1 mt-1">
                        <div class="col-sm-12 text-center">
                            <button type="submit" class="btn btn-sm ml-4 btn-success">Generate</button>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
<!-- END: Card DATA-->
</div>
</main>
<?php include(dirname(__FILE__).'/../franchise_shared/franchise_footer.php'); ?>