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
                        <h6 class="">Franchise Billing Transection</h6>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-12 col-sm-12">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="card-body">                                    
                                    <?php $customer_id = $this->session->userdata('customer_id');
                                        $wallet = $this->db->query("select wallet from tbl_customers where customer_id = '$customer_id'")->row();
                                        $credit_limit = $this->db->query("Select * from tbl_franchise where fid = '$customer_id'")->row();
                                    ?>                                    
                                    <table class="table table-sm mt-4 ">
                                    <a href="<?= base_url('franchise/statment-d');?>" class="btn btn-primary" >Download</a>
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
<?php include(dirname(__FILE__).'/../franchise_shared/franchise_footer.php'); ?>