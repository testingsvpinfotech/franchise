<?php $this->load->view('masterfranchise/master_franchise_shared/admin_header.php'); ?>
    <!-- END Head-->
<style>
  	.input:focus {
    outline: outline: aliceblue !important;
    border:2px solid red !important;
    box-shadow: 2px #719ECE;
  }
  </style>
	<?php $this->load->view('masterfranchise/master_franchise_shared/admin_sidebar.php');
   // include('admin_shared/admin_sidebar.php'); ?>
<main>
    
<!-- START: Card Data-->
<div class="row">
    <div class="col-12 mt-3">
        <div class="card">
            <div class="card-body">
                                
                <hr>
                <div class="row p-2">
                    <div class="col-md-6">
                        <h6 class="">Billing Transaction</h6>
                    </div>
                </div>


                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="card-body">
                                    <div class="row">
                                        <!-- <div class="col-md-3">
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
                                        </div> -->


                                        <div class="col-sm-5 text-center">
                                            <?php $customer_id = $this->session->userdata('customer_id');
                                              $wallet = $this->db->query("select wallet from tbl_customers where customer_id = '$customer_id'")->row();
                                              $credit_limit = $this->db->query("Select * from tbl_franchise where fid = '$customer_id'")->row();
                                            ?>
                                        </div>
                                        
                                    </div>

                                    <table class="table table-sm mt-4 ">
                                    <a href="<?= base_url('master-franchise/statment-d');?>" class="btn btn-primary" >Download</a>
                                        <thead>
                                            <tr style = "background:#ddd;">
                                                <th colspan="5">Credit Balance: ₹<?php echo $credit_limit->credit_limit - $credit_limit->credit_limit_utilize;?></th>
                                                <th colspan="5">Wallet Balance: ₹<?php echo $wallet->wallet ;?></th>
                                                <!-- <th>Description</th> -->
                                            </tr>
                                        </thead>
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
<?php $this->load->view('masterfranchise/master_franchise_shared/admin_footer.php');
     //include('admin_shared/admin_footer.php'); ?>