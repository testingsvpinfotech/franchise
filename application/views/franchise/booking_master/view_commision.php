<?php include (dirname(__FILE__) . '/../franchise_shared/franchise_header.php'); ?>
<?php include (dirname(__FILE__) . '/../franchise_shared/franchise_sidebar.php'); ?>
<style>
    tbody td {
        padding: 5px !important;
        text-align: center;
    }
</style>


<main>
    <div class="row">
        <div class="col-12 mt-3">
            <div class="card">
                <div class="card-body">
                    <div class="row p-2">
                        <div class="col-md-6">
                            <h6 class="">View Commision</h6>
                        </div>

                        <?php if ($this->session->flashdata('notify') != '') { ?>
                            <div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored">
                                <?php echo $this->session->flashdata('notify'); ?>
                            </div>
                            <?php unset($_SESSION['class']);
                            unset($_SESSION['notify']);
                        } ?>

                        <div class="table-responsive">

                            <form action="<?php echo base_url('franchise/view-Commision'); ?>" method="GET">
                                <div class="row mb-3" style="margin-left:0px;margin-right:0px;">
                                    <div class="col-md-2">
                                        <label for="">Filter</label>
                                        <select class="form-control" name="filter">
                                            <option value="">-- select --</option>
                                            <option value="pod_no" <?php echo (isset($filter) && $filter == 'pod_no') ? 'selected' : ''; ?>>Pod No</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="">Filter Value</label>
                                        <input type="text" class="form-control"
                                            value="<?php echo (isset($filter_value)) ? $filter_value : ''; ?>"
                                            name="filter_value" />
                                    </div>
                                    <div class="col-sm-2">
                                        <label for="">From Date</label>
                                        <input type="date" name="from_date"
                                            value="<?php echo (isset($from_date)) ? $from_date : ''; ?>" id="from_date"
                                            autocomplete="off" class="form-control">
                                    </div>

                                    <div class="col-sm-2">
                                        <label for="">To Date</label>
                                        <input type="date" name="to_date"
                                            value="<?php echo (isset($to_date)) ? $to_date : ''; ?>" id="to_date"
                                            autocomplete="off" class="form-control">
                                    </div>
                                    <div class="col-sm-3">
                                        <button type="submit" class="btn btn-primary" name="submit">Filter</button>
                                        <a href="<?php echo base_url('franchise/view-Commision'); ?>"
                                            class="btn btn-info">Reset</a>
                                    </div>
                                </div>
                            </form>

                            <table id="id1" class="display table  table-responsive table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Sr.no</th>
                                        <th scope="col">Pod No</th>
                                        <th scope="col">Customer Name</th>
                                        <th scope="col">Booking Date </th>
                                        <th scope="col">Booking(% of Basic Freight)</th>
                                        <th scope="col">Delivery(% of Basic Freight)</th>
                                        <th scope="col">Door Delivery(% of Basic Freight)</th>
                                        <th scope="col">Booking Charges</th>
                                        <th scope="col">Delivery Charges</th>
                                        <th scope="col">Door Delivery Charges</th>
                                        <th scope="col">Lable</th>
                                        <th scope="col">Charges</th>
                                        <th scope="col">Lable</th>
                                        <th scope="col">Charges</th>
                                        <th scope="col">Lable</th>
                                        <th scope="col">Charges</th>
                                        <th scope="col">Total Charges</th>
                                      
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($shipment_info)) {
                                        $cnt = 1;
                                        // Initialize a variable to store the total charges
                                        $total_all_charges = 0;

                                        foreach ($shipment_info as $value) {
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $cnt++; ?>
                                                </td>
                                                <td>
                                                    <?php echo $value['pod_no']; ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                      if($value['bnf_customer_id']!=0){
                                                         $cust = $this->db->query("SELECT * FROM tbl_customers WHERE customer_id ='".$value['bnf_customer_id']."'")->row();
                                                         echo $cust->customer_name.' -- '.$cust->cid;
                                                      }
                                                     ?>
                                                </td>
                                                <td>
                                                    <?php echo $value['booking_date']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $value['booking_commision']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $value['delivery_commision']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $value['door_delivery_share']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $value['booking_commision_charges']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $value['delivery_commision_charges']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $value['door_delivery_charges']; ?>
                                                </td>

                                                <?php

                                                $total_charges = $value['total_charges'] + $value['charges1'] + $value['charges2'] + $value['charges3'];

                                                $total_all_charges += $total_charges;
                                                ?>


                                                <td>
                                                    <?php echo $value['lable1']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $value['charges1']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $value['lable2']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $value['charges2']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $value['lable3']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $value['charges3']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $total_charges; ?>
                                                </td>
                                             
                                            </tr>

                                        <?php
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="16"><b style="float:right;">Total Charges :</b></td>
                                            <td>
                                                <?php echo number_format((float)$total_all_charges, 2, '.', ''); ?>
                                            </td>
                                        </tr>
                                    <?php
                                    } else {
                                        ?>
                                        <tr>
                                            <td colspan="12" style="color:red;">No Data Found</td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                  <br>
                                  <br>
                                  <br>

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

<?php include (dirname(__FILE__) . '/../franchise_shared/franchise_footer.php'); ?>