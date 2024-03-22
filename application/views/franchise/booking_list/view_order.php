 <?php include(dirname(__FILE__).'/../franchise_shared/franchise_header.php'); ?>
<?php include(dirname(__FILE__).'/../franchise_shared/franchise_sidebar.php'); ?>
 <!-- START: Card Data-->
<main>
    
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-body">
                <div class="row p-2">
                    <div class="col-md-6">
                        <h6 class=""> <i class="fas mr-2fa-star"></i>Franchise Orders</h6>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <a href=""><button type="button" class="btn btn-outline-success  mr-1">Booked</button></a>
                        <button type="button" class="btn btn-outline-primary mr-1">Clone</button>
                    </div>
                </div>
                <hr>


                <div>
                    <div class="row">
                        <div class="col-md-3" style="border-right: 1px solid #ddd;">
                            <h5>Order</h5>
                            <div class="pb-1">
                                <strong>Order No:</strong>
                                <span>MN65</span>
                            </div>
                            <div class="pb-1">
                                <strong>Date:</strong>
                                <span>Sep 29, 2022</span>
                            </div>
                            <div class="pb-1">
                                <strong>Payment Type:</strong>
                                <span>Prepaid</span>
                            </div>
                            <div class="pb-1">
                                <strong>Order Weight:</strong>
                                <span>0.1 Kg</span>
                            </div>
                            <div class="">
                                <strong>Dimension:</strong>
                                <span>
                                    10 x 10 x 10 </span>
                            </div>
                            <hr>
                            <div>
                                <strong>Courier:</strong>
                                <span>OM COURIER SERVICES FRANCHISE B2C</span><br>
                                <span>AWB : <a class="text-info" href="#" target="blank">151239870199668</a></span>
                            </div>

                        </div>
                        <div class="col-md-3" style="border-right: 1px solid #ddd;">
                            <h5>Warehouse/RTO Details</h5>

                            <address class="m-t-10">
                                OM COURIER SERVICES<br>
                                9029200429<br>
                                SHOP NO 1 OM DEEP SAI POOJA CHS<br>
                                <br>
                                THANE, Maharashtra<br>
                                India, 400601<br>
                                <strong>GST No:</strong> 27ADAPN6620J1ZJ
                            </address>
                        </div>


                        <div class="col-md-3" style="border-right: 1px solid #ddd;">
                            <h5>
                                <!-- Consignee Details -->
                                Consignee Details
                            </h5>
                            <address class="m-t-10" id="oldinfo">
                                SVP Infotech <br>
                                1b 109 phoenix paragon plaza,<br>
                                Mumbai, maharashtra 400072<br>
                                India<br>
                                9022062666<br>
                                Alternate Phone <br>
                                <b>GST No:</b> <br>
                            </address>
                            <div style="display:none;" id="updatedname">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>First Name</label>
                                            <input type="text" name="firstname" class="form-control" value="SVP Infotech" placeholder="Enter First Name">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Last Name</label>
                                            <input type="text" name="lastname" value="" class="form-control" placeholder="Enter Last Name">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>First Address</label>
                                            <input type="text" class="form-control" name="firstadd" value="1b 109 phoenix paragon plaza" placeholder="Enter First Address">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Second Address</label>
                                            <input type="text" name="secondadd" value="" class="form-control" placeholder="Enter Second Address">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>City</label>
                                            <input type="text" class="form-control" name="customercity" value="Mumbai" placeholder="Enter Customer City">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>State</label>
                                            <input type="text" name="customerstate" value="maharashtra" class="form-control" placeholder="Enter Customer State">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Country</label>
                                            <input type="text" class="form-control" name="customercountry" value="India" placeholder="Enter Customer Country">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Zip Code</label>
                                            <input type="text" name="customerzipcode" value="400072" class="form-control" placeholder="Enter Zip Code.">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Phone</label>
                                            <input type="hidden" name="orderid" value="MN65">
                                            <input type="text" name="customercell" value="9022062666" class="form-control" placeholder="Enter Customer Phone">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-3" style="border-right: 1px solid #ddd;">
                            <h5> Consigner Details </h5>
                            <address class="m-t-10" id="oldinfo">
                                Mohini Enterprises <br>
                                SHOP NO 1 OM DEEP SAI POOJA CHS<br>
                                THANE, maharashtra 400601<br>
                                India<br>
                                9022062666<br>
                                <b>GST No:</b> <br>
                            </address>
                            <div style="display:none;" id="updatedname">
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>First Name</label>
                                            <input type="text" name="firstname" class="form-control" value="SVP Infotech" placeholder="Enter First Name">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Last Name</label>
                                            <input type="text" name="lastname" value="" class="form-control" placeholder="Enter Last Name">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>First Address</label>
                                            <input type="text" class="form-control" name="firstadd" value="1b 109 phoenix paragon plaza" placeholder="Enter First Address">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Second Address</label>
                                            <input type="text" name="secondadd" value="" class="form-control" placeholder="Enter Second Address">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>City</label>
                                            <input type="text" class="form-control" name="customercity" value="Mumbai" placeholder="Enter Customer City">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>State</label>
                                            <input type="text" name="customerstate" value="maharashtra" class="form-control" placeholder="Enter Customer State">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Country</label>
                                            <input type="text" class="form-control" name="customercountry" value="India" placeholder="Enter Customer Country">
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label>Zip Code</label>
                                            <input type="text" name="customerzipcode" value="400072" class="form-control" placeholder="Enter Zip Code.">
                                        </div>
                                    </div>
                                    <div class="form-row">
                                        <div class="form-group col-md-6">
                                            <label>Phone</label>
                                            <input type="hidden" name="orderid" value="MN65">
                                            <input type="text" name="customercell" value="9022062666" class="form-control" placeholder="Enter Customer Phone">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- //row end -->

                    <div class="table-responsive mt-4">
                        <table class="table m-t-50">
                            <thead>
                                <tr>
                                    <th class="text-left">Product Description</th>
                                    <th class="text-left">Quanitity</th>
                                    <th class="text-left">SKU</th>
                                    <th class="text-left">HSN Code</th>
                                    <th class="text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-left">documents </td>
                                    <td class="text-left">1</td>
                                    <td class="text-left"></td>
                                    <td class="text-left"></td>
                                    <td class="text-right">10</td>
                                </tr>


                                <tr class="bg-dark text-white">
                                    <td colspan="4" class="text-right">Grand Total</td>
                                    <td colspan="4" class="text-right">10 Rs.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>



                </div>
            </div>
        </div>

    </div>
</div>
<!-- END: Card DATA-->

</main>

<?php include(dirname(__FILE__).'/../franchise_shared/franchise_footer.php'); ?>