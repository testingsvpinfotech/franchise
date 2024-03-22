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
                        <h6 class="">PRQ List </h6>
                    </div>
                   
                <?php if ($this->session->flashdata('notify') != '') { ?>
                    <div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored"><?php echo $this->session->flashdata('notify'); ?></div>
                <?php unset($_SESSION['class']);
                    unset($_SESSION['notify']);
                } ?>
           
           <div class="table-responsive">
            <table id="id1" class="display table  table-responsive table-striped table-bordered">
          <thead>
                  <tr>
                        <th  scope="col">Sr.no</th>
                        <th  scope="col">Consigner Name</th>
                        <th  scope="col">Pickup Request_id</th>
                        <th  scope="col">Consigner Contact</th>
                        <th  scope="col">Consigner Address1</th>
                        <th  scope="col">Consigner Address2</th>
                        <th  scope="col">Consigner Address3</th>
                        <th  scope="col">Consigner Email</th>
                        <th  scope="col">Pickup Pincode</th>
                        <th  scope="col">Destination Pincode</th>
                        <th  scope="col">Pickup Location</th>
                        <th  scope="col">Pickup Date</th>
                        <th  scope="col">City</th>
                        <th  scope="col">Instruction</th>
                        <th  scope="col">Mode</th>
                        <th  scope="col">Weight</th>
                        <th  scope="col">Type Of Package</th>
                        <th  scope="col">NOP </th>
                        <th  scope="col">Pickup Generate Date </th>
                       
                  </tr>
              </thead>
              <tbody>
         <?php 
            if (!empty($all_request))
            {
                $cnt = 1;
                foreach ($all_request as $value) 
                {

            ?>
                    <tr>
                        <td><?php echo $cnt++; ?></td>
                        <td><?php echo $value->consigner_name; ?></td>
                        <td><?php echo $value->pickup_request_id; ?></td>
                        <td><?php echo $value->consigner_contact; ?></td>
                        <td><?php echo $value->consigner_address1; ?></td>
                        <td><?php echo $value->consigner_address2; ?></td>
                        <td><?php echo $value->consigner_address3; ?></td>
                        <td><?php echo $value->consigner_email; ?></td>
                        <td><?php echo $value->pickup_pincode; ?></td>
                        <td><?php echo $value->destination_pincode; ?></td>
                        <td><?php echo $value->pickup_location; ?></td>
                        <td><?php echo $value->pickup_date; ?></td>
                        <td><?php echo $value->city; ?></td>
                        <td><?php echo $value->instruction; ?></td>
                        <?php $mode_id =$value->mode_id; $DD = $this->db->query("select mode_name from transfer_mode where transfer_mode_id = '$mode_id'")->row();?>
                        <td><?php echo $DD->mode_name; ?></td>
                        <td><?php echo $value->actual_weight; ?></td>
                        <td><?php echo $value->type_of_package; ?></td>
                        
                        <td><?php echo $value->no_of_pack; ?></td>
                        <td><?php echo $value->create_date; ?></td>
                        
                     </tr>
            <?php 
                
                }
            }else{?>
                <tr><td colspan="12" style="color:red;">No Data Found</td></tr>
          <?php  } ?>
            
         </tbody>
         <!-- <input type="hidden" name="selected_campaing" id="selected_campaingss" value=""> -->
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


