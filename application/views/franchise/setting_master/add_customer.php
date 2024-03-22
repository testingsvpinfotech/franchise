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
                        <h6 class=""><i class="fas fa mr-2 fa-user"></i>Consigner / Customer</h6>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                        <a href="#" class="bg-primary py-2 px-2 rounded ml-auto text-white text-center" data-toggle="modal" data-target="#newcontact">
                            <i class="icon-plus align-middle text-white"></i> <span class="d-none d-xl-inline-block">Add New Consigner / Customer</span>
                        </a>
                    </div>
                    
                                <?php if ($this->session->flashdata('notify') != '') { ?>
                                        <div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored"><?php echo $this->session->flashdata('notify'); ?></div>
                                    <?php unset($_SESSION['class']);
                                        unset($_SESSION['notify']);
                                    } ?>
                    <hr>

                </div>

                <div class="table-responsive mt-4">
                    <table id="id1" class="display table  table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Sr. No.</th>
                                <th>Customer Id</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Email</th>
                                <th>State</th>
                                <th>City</th>
                                <th>Address</th>
                                <th>Pincode</th>
                                <th>Edit</th>
                            </tr>
                        </thead>
                        <tbody>
                            
                            <?php if(!empty($show_customer_list)){ ?>
                               <?php $i=1; foreach( $show_customer_list as $value):?>
                            <tr>
                                <td><?= $i++ ; ?></td>
                                <td><?= $value->cid;?></td>
                                <td><?= $value->customer_name;?></td>
                                <td><?= $value->phone ;?></td>
                                <td><?= $value->email;?></td>
                                <td><?= $value->state;?></td>
                                <td><?= $value->city;?></td>
                                <td><?= $value->address;?> </td>
                                <td><?= $value->pincode;?> </td>
                                <td><button data-employee-id="39485" class="btn btn-sm btn-outline-info edit-button"><i class="mdi mdi-pencil"></i> Edit</button></td>
                            </tr>
                          <?php endforeach;?>
                          <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- END: Card DATA-->

<!-- Add Contact -->
<div class="modal fade" id="newcontact">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="icon-pencil"></i> Add Customer
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i class="icon-close"></i>
                </button>
            </div>
            <form  action="<?php echo base_url('Franchise_customer_manager/add_customer');?>" method="POST">
                <div class="modal-body">
             
                    <div class="row">
                        <div class="col-md-6">
                            <div class="contact-name">
                                <label for="contact-name" class="col-form-label">Customer Code</label>
                                <input type="text" value="<?php echo $CId; ?>" name="CId" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="contact-name">
                                <label for="contact-name" class="col-form-label">customer Name</label>
                                <input type="text"name="customer_name" class="form-control" required="">
                            </div>
                        </div>
                    </div>
                        <div class="row">
                         <div class="col-md-6">
                            <div class="contact-name">
                                <label for="contact-name" class="col-form-label">Contact Person</label>
                                <input type="text" name="contact_person" class="form-control" required="">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="contact-name">
                                <label for="contact-name" class="col-form-label">Phone</label>
                                <input type="text"name="contact_number" class="form-control" required="">
                            </div>
                        </div>
                       </div> 
                    <div class="row">
                        <div class="col-md-6">
                            <div class="contact-email">
                                <label for="contact-email" class="col-form-label">Email</label>
                                <input type="text" name="email" class="form-control" required="">
                            </div>
                        </div>
                         <div class="col-md-6">
                            <div class="contact-phone">
                                <label for="contact-phone" class="col-form-label">Address</label>
                                <input type="text" name="address"  class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="contact-occupation">
                                <label for="contact-occupation" class="col-form-label">Password</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                           <div class="form-group">
                            <label class="control-label">Pin Code</label>
                            <input type="text"  name="pincode" id="pincode"  value="<?php echo set_value('pincode') ?>"  class="form-control" placeholder="Enter Pincode Number">
                            <span class="errormsg" id="errormsg" style="color: #8b0001;font-weight: bold;"></span>
                          </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                        <label class="control-label">State</label>
                         <select class="form-control"  name="customer_state_id"  id="franchaise_state">
                            <option value="">Select State</option>           
                        </select>
                       </div>
                         <div class="col-md-6">
                            <label class="control-label">City</label>
                            <select class="form-control"  name="customer_city_id" id="franchaise_city" >
                                <option value="">Select City</option>           
                            </select>
                          </div>
                    </div>
                    <div class="row">
                      
                        <div class="col-md-12">
                            <div class="contact-phone">
                                <label for="contact-phone" class="col-form-label">Comapany</label>
                                
                                <select name="company_id" class="form-control">
                                    <?php if(!empty($company_list)){?>
                                    <?php foreach($company_list as $value):?>
                                    <option value="<?= $value->id ;?>"> <?= $value->company_name;?> </option>
                                    <?php endforeach ;?>
                                    <?php }?>
                                </select>
                            </div>
                         </div>
                     </div>
                     <div class="row">
                        <div class="col-md-6">
                            <div class="contact-occupation">
                                <label for="contact-occupation" class="col-form-label">GST Number</label>
                                <input type="text" name="gst_number" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="contact-occupation mt-2">
                                <label for="contact-occupation" class="col-form-label">GST Charges</label>
                                <input type="radio" name="gst_charges" value= "1">Yes
                                <input type="radio" value = "0" >No
                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" name="save" class="btn btn-primary add-todo">Add Contact</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Edit Contact -->
</main>
<?php include(dirname(__FILE__).'/../franchise_shared/franchise_footer.php'); ?>

    <script type="text/javascript">
  //======================

// ***************franchise persnal Details use Pincode
  $("#pincode").on('blur', function () 
  {
    var pincode = $(this).val();
    if (pincode != null || pincode != '') {

    
      $.ajax({
        type: 'POST',
        url: '<?php echo base_url();?>Franchise_customer_manager/getCityList',
        data: 'pincode=' + pincode,
        dataType: "json",
        success: function (d) {         
          var option;         
          option += '<option value="' + d.id + '">' + d.city + '</option>';
          $('#franchaise_city').html(option);
          
        }
      });
      $.ajax({
        type: 'POST',
        url: '<?php echo base_url();?>Franchise_customer_manager/getState',
        data: 'pincode=' + pincode,
        dataType: "json",
        success: function (d) {         
          var option;         
          option += '<option value="' + d.id + '">' + d.state + '</option>';
          $('#franchaise_state').html(option);          
        }
      });
    }
  }); 
  
  </script>