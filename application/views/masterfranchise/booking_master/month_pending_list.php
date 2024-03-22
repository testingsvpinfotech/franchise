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
                        <h6 class="">Total Month Pending Shipment </h6>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                       <a href="<?php echo base_url();?>franchise/add-shipment"> <button type="button" class="btn btn-primary text-white mr-1">ADD Shipment</button></a>
                       <a href="<?php echo base_url();?>franchise/shipment-list"> <button type="button" class="btn btn-success text-white mr-1"> View Shipment</button></a>
                      
                    </div>
                </div>
                <hr>
                <?php if ($this->session->flashdata('notify') != '') { ?>
                    <div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored"><?php echo $this->session->flashdata('notify'); ?></div>
                <?php unset($_SESSION['class']);
                    unset($_SESSION['notify']);
                } ?>
           
             
                    <div class="table-responsive">
                        <table id="example" class="display table  table-responsive table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>SR NO.</th>                               
                                <th>AWB.No</th>
                                <th>Sender</th>
                                <th>Pincode	Receiver</th>
                                <th>Receiver City</th>
                                <th>Booking date</th>
                                <th>Mode</th>
                                <th>Pay Mode</th>
                                <th>Amount</th>
                                <th>Weight</th>
                                <th>NOP</th>
                                <th>Invoice No</th>
                                <th>Invoice Amount</th>
                                <th>Branch Name </th>
                                <th>Sender City</th>
                                <th>Eway No</th>
                            </tr>
                            </thead>
                            <tbody>
                               
                                <?php if(!empty($shipment_list)){?>
                                <?php $i =1; foreach( $shipment_list as $value ):?>
                                 <?php $dd = $this->db->query("SELECT branch_name FROM `tbl_branch` WHERE branch_id =".$value->branch_id)->row_array(); ?>
                                 <?php $dd1 = $this->db->query("SELECT * FROM `city` WHERE id =".$value->reciever_city)->row_array(); ?>
                                 <?php $dd2 = $this->db->query("SELECT * FROM `city` WHERE id =".$value->sender_city)->row_array(); ?>
                                 <?php $domestic_weight_details = $this->db->query("SELECT * FROM `tbl_domestic_weight_details` WHERE booking_id =".$value->booking_id)->row_array(); ?>
                            <tr>
                                <td><?= $i++;?></td>
                                <td><?= $value->pod_no ;?></td>
                                <td><?= $value->sender_name ;?></td>
                                <td><?= $value->reciever_pincode ;?></td>
                                <td><?= $dd1['city'];?></td>
                                <td><?= $value->booking_date ;?></td>
                                <td><?= $value->mode_dispatch ;?></td>
                                <td><?= $value->dispatch_details ;?></td>
                                <td><?= $value->total_amount ;?></td>
                                <td><?= $domestic_weight_details['actual_weight'] ;?> </td>
                                <td><?= $domestic_weight_details['no_of_pack'] ;?></td>
                                <td><?= $value->invoice_no;?></td>
                                <td><?= $value->invoice_value;?></td>
                                <td><?= $dd['branch_name'];?></td>
                                <td><?= $dd2['city'] ;?></td>
                                <td><?= $value->eway_no ;?></td>
                              
                                </tr>
                                <?php endforeach; ?>
                                 <?php }else { ?> <tr><td colspan="16">Data Not Found</td></tr> <?php } ?>
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


