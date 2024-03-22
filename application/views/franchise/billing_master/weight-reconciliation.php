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
                        <h6 class="">Billing - Weight Reconciliation</h6>
                    </div>
                    <div class="col-md-6">
                        <button class="btn float-right btn-sm btn-primary">Export</button>
                    </div>
                </div>

                <?php  include(dirname(__FILE__).'/../billing_master/billing_tab.php'); ?>
                <hr>


                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="card-body">
                                    <form method="get" action="#">
                                        <div class="row mt-4">

                                            <div class="col-sm-2">
                                                <label for="email">From Date:</label>
                                                <input type="date" class="form-control">
                                            </div>
                                            <div class="col-sm-2">
                                                <label class="font-secondary">AWB</label>
                                                <input type="text" autocomplete="off" class="form-control form-control-sm">
                                            </div>
                                            <div class="form-group col-sm-2" style="margin-top:2px;">
                                                <label for="email">Product Name:</label>
                                                <input type="text" class="form-control form-control-sm" placeholder="Product name to search">
                                            </div>
                                            <div class="form-group col-sm-3" style="margin-top:2px;">
                                                <label for="email">Courier Name:</label>
                                                <select class="form-control">
                                                    <option>All</option>
                                                </select>
                                            </div>
                                            <div class="col-sm-3" style="margin-top:29px;">
                                                <button type="submit" class="btn btn-sm btn-outline-success">Apply</button>
                                                <a href="#" class="btn btn-sm btn-outline-primary">Clear</a>
                                            </div>

                                        </div>
                                    </form>
                                </div>

                                <table class="table table-sm mt-4 ">
                                    <thead>
                                        <tr style="background-color:#ddd;">
                                            <th><input data-switch="true" id="select_all_checkboxes" type="checkbox"></th>
                                            <th>Weight Applied Date</th>
                                            <th>Courier</th>
                                            <th>AWB Number</th>
                                            <th>Entered Weight</th>
                                            <th>Applied Weight</th>
                                            <th>Weight Charges</th>
                                            <th>Product</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input value="2321406" type="checkbox" class="multiple_checkboxes" name="shipping_ids[]"></td>
                                            <td>2022-09-17</td>
                                            <td>OM COURIER SERVICES FRANCHISE B2C </td>
                                            <td>
                                                OM COURIER SERVICES FRANCHISE B2C
                                                151239870199748 </td>
                                            <td style="min-width:100px;"><b>Entered Weight 500g </b><br>
                                                <b>Charged Weight </b> 500<br> <b>LxBxH</b>: 10x12x11
                                            </td>
                                            <td> 750g
                                            </td>
                                            <td>
                                                <b>Forward</b> : 18.8<br>
                                            </td>
                                            <td>
                                                <span data-toggle="tooltip" data-html="true" title="" data-original-title="CLOTH">
                                                    CLOTH </span>
                                            </td>
                                            <td><b>Accepted</b></td>

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
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