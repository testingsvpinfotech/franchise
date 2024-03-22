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
                        <h6 class="">Booking</h6>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <button type="button" class="btn btn-outline-primary mr-1">Export</button>
                        <button type="button" class="btn btn-outline-secondary mr-1">Import</button>
                        <button type="button" class="btn btn-outline-success  mr-1">Filter</button>
                    </div>
                </div>
                <hr>
               
              <?php include(dirname(__FILE__).'/../booking_list/b2b_booking_tab.php'); ?>
               
                    <div class="table-responsive">
                        <table id="id1" class="display table  table-striped table-bordered">
                            <thead>
                            <tr>
                                <th><input data-switch="true" id="select_all_checkboxes" type="checkbox"></th>                               
                                <th>Channel</th>
                                <th>Order</th>
                                <th>Date</th>
                                <th>Product</th>
                                <th>Payment</th>
                                <th>Collectable Amount</th>
                                <th>Method</th>
                                <th style="width: 10%;">Customer</th>
                                <th>Zip Code</th>
                                <th>Channel Tags </th>
                                <th>Tags</th>
                                <th>Weight</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td></td>
                                <td>Customer</td>
                                <td><a style="color: #4c66fb;" target="_blank" href="<?= base_url('franchise/show-order');?>">MN65</a></td>
                                <td>Sep 29, 2022</td>
                                <td><span data-toggle="tooltip" data-html="true" title="" data-original-title="documents()">documents()</span></td>
                                        <td>10</td>
                                        <td></td>
                                        <td>PREPAID</td>
                                        <td> <span data-toggle="tooltip" data-html="true" title="" data-original-title="SVP Infotech <br>9022062666<br>1b 109 phoenix paragon plaza <br>Mumbai<br>maharashtra"> SVP Infotech </span></td>
                                        <td>400072 </td>
                                        <td></td>
                                        <td></td>
                                        <td> 0.1 Kg</td>
                                        <td> <button type="button" class="btn btn-outline-success btn-sm">Booked</button></td>
                                    </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <!-- END: Card DATA-->
</div>
</main>

<?php include(dirname(__FILE__).'/../franchise_shared/franchise_footer.php'); ?>
    