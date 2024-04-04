<?php $this->load->view('masterfranchise/master_franchise_shared/admin_header.php'); ?>
<?php $this->load->view('masterfranchise/master_franchise_shared/admin_sidebar.php'); ?>
<style>
    tbody td {
        padding:5px !important;
        text-align:center;
    }
</style>


<main>
    <div class="row">
        <div class="col-12 mt-3">
            <div class="card">
            <div class="card-body">
                <div class="row p-4">
                    <div class="col-md-6">
                        <h6 class="">View Commision</h6>
                    </div>
                   
                <?php if ($this->session->flashdata('notify') != '') { ?>
                    <div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored"><?php echo $this->session->flashdata('notify'); ?></div>
                <?php unset($_SESSION['class']);
                    unset($_SESSION['notify']);
                } ?>
           
           <div class="table-responsive">
<!-- 
           <form action="<?php echo base_url('franchise/view-Commision'); ?>" method="GET">
                        <div class="form-row mb-3">
                            <div class="col-md-1">
                                <label for="">Filter</label>
                                <select class="form-control" name="filter">
                                    <option selected disabled>Select Filter</option>
                                    <option value="pod_no" <?php echo (isset($filter) && $filter == 'pod_no') ? 'selected' : ''; ?>>Pod No</option>
                                    <option value="booking_id" <?php echo (isset($filter) && $filter == 'booking_id') ? 'selected' : ''; ?>>Booking Id</option>
                                    <option value="customer_id" <?php echo (isset($filter) && $filter == 'customer_id') ? 'selected' : ''; ?>>Customer Id</option>
                                    <option value="franchise_id" <?php echo (isset($filter) && $filter == 'franchise_id') ? 'selected' : ''; ?>>Franchise Id</option>
                                </select>
                            </div>

                            <div class="col-md-1">
                                <label for="">Filter Value</label>
                                <input type="text" class="form-control" value="<?php echo (isset($filter_value)) ? $filter_value : ''; ?>" name="filter_value" />
                            </div>
                            <div class="col-sm-1">
                                <label for="">From Date</label>
                                <input type="date" name="from_date" value="<?php echo (isset($from_date)) ? $from_date : ''; ?>" id="from_date" autocomplete="off" class="form-control">
                            </div>

                            <div class="col-sm-1">
                                <label for="">To Date</label>
                                <input type="date" name="to_date" value="<?php echo (isset($to_date)) ? $to_date : ''; ?>" id="to_date" autocomplete="off" class="form-control">
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-primary" name="submit">Filter</button>
                                <button type="submit" class="btn btn-primary" name="download_report">Download Report</button>
                                <a href="<?php echo base_url('controller_name/view-shipment'); ?>" class="btn btn-info">Reset</a>
                            </div>
                        </div>
                    </form> -->

            <table id="id1" class="display table  table-responsive table-striped table-bordered">
          <thead>
                  <tr>
                        <th  scope="col">Sr.no</th>
                        <th  scope="col">Booking Id</th>
                        <th  scope="col">Franchise Id</th>
                        <th  scope="col">Customer Id</th>
                        <th  scope="col">Pod No</th>
                        <th  scope="col">Booking Commision</th>
                        <th  scope="col">Delivery Commision</th>
                        <th  scope="col">Door Delivery Share</th>
                        <th  scope="col">Booking Commision Charges</th>
                        <th  scope="col">Delivery Commision Charges</th>
                        <th  scope="col">Door Delivery Charges</th>
                        <th  scope="col">Lable 1</th>
                        <th  scope="col">Charges 1</th>
                        <th  scope="col">Lable 2</th>
                        <th  scope="col">Charges 2</th>
                        <th  scope="col">Lable 3</th>
                        <th  scope="col">Charges 3</th>
                        <th  scope="col">Total Charges</th>
                        <th  scope="col">Booking Date </th>
                        <th  scope="col">Booking Type </th>
                       
                  </tr>
              </thead>
              <tbody>
              <?php 
if (!empty($shipment_info))
{
    $cnt = 1;
    // Initialize a variable to store the total charges
    $total_all_charges = 0;

    foreach ($shipment_info as $value) 
    {
?>
    <tr>
        <td><?php echo $cnt++; ?></td>
        <td><?php echo $value['booking_id']; ?></td>
        <td><?php echo $value['franchise_id']; ?></td>
        <td><?php echo $value['customer_id']; ?></td>
        <td><?php echo $value['pod_no']; ?></td>
        <td><?php echo $value['booking_commision']; ?></td>
        <td><?php echo $value['delivery_commision']; ?></td>
        <td><?php echo $value['door_delivery_share']; ?></td>
        <td><?php echo $value['booking_commision_charges']; ?></td>
        <td><?php echo $value['delivery_commision_charges']; ?></td>
        <td><?php echo $value['door_delivery_charges']; ?></td>
        
        <?php 
       
        $total_charges = $value['total_charges'] + $value['charges1'] + $value['charges2']+ $value['charges3'];
        
        $total_all_charges += $total_charges;
        ?>
        
       
        <td><?php echo $value['lable1']; ?></td>
        <td><?php echo $value['charges1']; ?></td>
        <td><?php echo $value['lable2']; ?></td>
        <td><?php echo $value['charges2']; ?></td>
        <td><?php echo $value['lable3']; ?></td>
        <td><?php echo $value['charges3']; ?></td>
        <td><?php echo $total_charges; ?></td>
        <td><?php echo $value['booking_date']; ?></td>
        <td><?php echo $value['booking_type']; ?></td>
    </tr>

<?php 
    }
?>
    <tr>
        <td colspan="17"></td>
        <td><b>Total :</b><?php echo $total_all_charges; ?></td>
    </tr>
<?php 
}
else
{
?>
    <tr><td colspan="12" style="color:red;">No Data Found</td></tr>
<?php
}
?>

            
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

<?php $this->load->view('masterfranchise/master_franchise_shared/admin_footer.php'); ?>