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
                        <h6 class="">Billing - B2B Pricing</h6>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <label for="">From Date:</label>
                                            <input type="date" autocomplete="off"  class="form-control date-range-picker col-sm-12">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="email">Recharge Type:</label>
                                            <div class="form-group">
                                                <select class="form-control" name="filter[txn_for]" id="filter_wallet_txn">
                                                    <option selected="" value="">Show All</option>
                                                    <option value="cod">COD Adjustments</option>
                                                    <option value="recharge">Recharge - Razorpay</option>
                                                    <option value="neft">Recharge - NEFT</option>
                                                    <option value="shipment">Shipments</option>
                                                    <option value="shipment_refund">Shipments - Refunds</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <label for="email">AWB NO(s):</label>
                                            <input type="text" autocomplete="off" name="filter[awb_no]" value="" class="form-control" placeholder="AWB no(s) separated by comma">
                                        </div>
                                        <div class="col-sm-3 m-t-30">
                                            <button type="submit" class="btn btn-sm mt-4 btn-outline-success">Apply</button>
                                            <a href="#" class="btn btn-sm mt-4 btn-outline-primary">Clear</a>
                                        </div>


                                        <div class="col-sm-5 text-center">
                                            <?php $customer_id = $this->session->userdata('customer_id');
                                              $wallet = $this->db->query("select wallet from tbl_customers where customer_id = '$customer_id'")->row();
                                            ?>
                                            <h6>Wallet Balance: <b>₹<?php echo $wallet->wallet ;?> </b></h6>
                                        </div>
                                    </div>

                                    <table class="table table-sm mt-4 ">
                                        <thead>
                                            <tr style = "background:#ddd;">
                                                <th>Date</th>
                                                <th>Txn Type</th>
                                                <th>Ref No#</th>
                                                <th>Transaction ID</th>
                                                <th>Credit(₹)</th>
                                                <th>Debit(₹)</th>
                                                <th>Closing Balance(₹)</th>
                                                <!-- <th>Description</th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                       
                                            <?php if(!empty($transaction_data)){?>
                                            <?php foreach( $transaction_data as  $value):?>   
                                           <tr>    
                                            <td><?php echo $value->c_date ;?> </td>
                                            <td><?php echo $value->payment_mode ;?> </td>
                                            <td><?php echo $value->refrence_no ;?> </td>
                                            <td><?php echo $value->transaction_id ;?> </td>
                                            <td><?php echo $value->credit_amount ;?> </td>
                                            <td><?php echo $value->debit_amount ;?> </td>
                                            <td><?php echo $value->balance_amount ;?> </td>
                                            <!-- <td><?php echo $value->c_date ;?> </td> -->
                                              
                                            </tr>
                                            <?php endforeach ;?>
                                            <?php }?>
                                       
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