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
                        <h6 class="">Booking Shipment</h6>
                    </div>
                    <div class="col-md-6 d-flex justify-content-end">
                       <a href="<?php echo base_url();?>franchise/add-shipment"> <button type="button" class="btn btn-primary text-white mr-1">ADD Shipment</button></a>
                       <a href="<?php echo base_url();?>franchise/cancel-shipment-list"> <button type="button" class="btn btn-danger text-white mr-1">Cancel Shipment</button></a>
                    </div>
                </div>
                <div class="row p-2">
                    <div class="col-md-12">
                      <form role="form" action="<?php echo base_url();?>franchise/shipment-list" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-1">
                             <label for="">Courier</label>
                                <select class="form-control" name="courier_company" id="courier_company">
                                    <option value="ALL">ALL</option>
                                    <?php foreach ($courier_company as $cc) { ?>   
                                    <option value="<?php echo $cc['c_id']; ?>" <?php echo (isset($courier_companyy) && $courier_companyy == $cc['c_id'])?'selected':''; ?>><?php echo $cc['c_company_name']; ?></option>
                                  <?php  }  ?>
                                </select>
                             </div>
                             <div class="col-md-1">
                                <label>Mode</label>
                                <select class="form-control" name="mode_name" id="mode_name">
                                    <option value="ALL">ALL</option>
                                    <?php foreach($mode_details as $mn): ?>
                                        <?php $selected = (isset($mn['transfer_mode_id']) && isset($transfer_mode_id) && $transfer_mode_id == $mn['transfer_mode_id']) ? 'selected' : ''; ?>
                                        <option value="<?= $mn['transfer_mode_id']; ?>" <?= $selected; ?>><?= $mn['mode_name']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <label>ALL Filter</label>
                                <select class="form-control" name="filter">
                                    <option>Select Filter</option>
                                    <option value="pod_no" <?= isset($_POST['filter']) && $_POST['filter'] == 'pod_no' ? 'selected' : ''; ?>>Pod No</option>
                                    <!--<option value="forwording_no" <?php echo($_POST['filter'] == 'forwording_no')?'selected':'';?> >Forwording No</option>-->
                                    <option value="sender_name" <?= isset($_POST['filter']) && $_POST['filter'] == 'sender_name' ? 'selected' : ''; ?>>Sender Name</option>
                                    <option value="receiver_name" <?= isset($_POST['filter']) && $_POST['filter'] == 'receiver_name' ? 'selected' : ''; ?>>Receiver Name</option>
                                    <option value="origin" <?= isset($_POST['filter']) && $_POST['filter'] == 'origin' ? 'selected' : ''; ?>>ORIGIN</option>
                                    <option value="destination" <?= isset($_POST['filter']) && $_POST['filter'] == 'destination' ? 'selected' : ''; ?>>Destination</option>
                                    <option value="pickup" <?= isset($_POST['filter']) && $_POST['filter'] == 'pickup' ? 'selected' : ''; ?>>Pickup</option>
                                    <option value="waking_customer" <?= isset($_POST['filter']) && $_POST['filter'] == 'waking_customer' ? 'selected' : ''; ?>> Waking Customer</option>
                                    <option value="company_customer" <?= isset($_POST['filter']) && $_POST['filter'] == 'company_customer' ? 'selected' : ''; ?>> Company Customer</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                              <label>Filter Value</label>
                             	<input type="text" class="form-control"  value="<?php echo !empty($_POST['filter_value'])?$_POST['filter_value']:''; ?>" name="filter_value" />
                             </div>
                             
        <!--                    <div class="col-md-1">-->
        <!--                       <label>Customer</label>-->
        <!--                       <select class="form-control" name="user_name" id="user_name">-->
								<!--<option value="" >Selecte Customer</option>-->
							  <?php // if(!empty($customer)){foreach($customer as $key => $values)-->
							//	{  ?>
								<option value="<?php //echo $values['customer_id']; ?>"  <?php //echo (isset($user_id) && $user_id == $values['customer_id'])?'selected':''; ?>><?php //echo $values['customer_name']; ?></option><?php // } } ?>
								<!--</select>-->
        <!--                     </div>-->
                            <div class="col-md-1">
                              <label>From Date</label>
                              <input type="date" name="from_date"  value="<?php echo !empty($_POST['from_date']) ?$_POST['from_date']:''; ?>" id="from_date" autocomplete="off" class="form-control">
                             </div>
                            <div class="col-md-1">
                              <label>To Date</label>
                               <input type="date" name="to_date"  value="<?php echo !empty($_POST['to_date'])?$_POST['to_date']:''; ?>" id="to_date" autocomplete="off" class="form-control">   
                             </div>
                             <div class="col-md-3 mt-4">
                              <input type="submit" value="Search" class="btn btn-primary">
                              <input type="submit" class="btn btn-outline-success" name="download_report" value="Excel">
                              <a href="<?php echo base_url('franchise/shipment-list');?>" class="btn btn-danger pt-2 pb-2">Reset</a>
                             </div>                           
                         </div>
                    </div>
                  </form>
                </div>
                <hr>
                <?php if ($this->session->flashdata('notify') != '') { ?>
                                        <div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored"><?php echo $this->session->flashdata('notify'); ?></div>
                                    <?php unset($_SESSION['class']);
                                        unset($_SESSION['notify']);
                                    } ?>
           <div class="table-responsive">
                        <table id="id1" class="display table  table-responsive table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>SR NO</th>                              
                                <th>AWB.No</th>
                                <th>Sender</th>
                                <th>Receiver Name</th>
                                <th>Pincode	Receiver</th>
                                <th>Type</th>
                                <!-- <th>Courier Company</th> -->
                                <th>Sender City</th>
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
                                <th>Eway No</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                               
                                <?php if(!empty($shipment_list)){ ?>
                                <?php $count =0; foreach( $shipment_list as $value ):  $count++;?>
                                 <?php $dd = $this->db->query("SELECT branch_name FROM `tbl_branch` WHERE branch_id =".$value->branch_id)->row_array(); ?>
                                 <?php $dd1 = $this->db->query("SELECT * FROM `city` WHERE id =".$value->reciever_city)->row_array(); ?>
                                 <?php $dd2 = $this->db->query("SELECT * FROM `city` WHERE id =".$value->sender_city)->row_array(); ?>
                                 <?php $tbl_domestic_weight_details = $this->db->query("SELECT * FROM `tbl_domestic_weight_details` WHERE booking_id =".$value->booking_id)->row(); ?>
                                 <?php $courier_company = $this->db->query("SELECT c_company_name FROM `courier_company` WHERE c_id =".$value->courier_company_id)->row_array(); ?>
                            <tr>
                                <td><?=  $count; ?></td>
                                <td> <a href="<?= base_url('franchise/edit-shipment/'.$value->booking_id); ?>" target="_blank" style="color:blue;"><?= $value->pod_no ;?> </a></td>
                                <td><?= $value->sender_name ;?></td>
                                <td><?= $value->reciever_name ;?></td>
                                <td><?= $value->reciever_pincode ;?></td>
                                <td><?= $value->doc_nondoc ;?></td>
                                <!-- <td><?= $courier_company['c_company_name'];?></td> -->
                                <td><?= $dd2['city'] ;?></td>
                                <td><?= $dd1['city'];?></td>
                                <td><?= $value->booking_date ;?></td>
                                <td><?= $value->mode_dispatch ;?></td>
                                <td><?= $value->dispatch_details ;?></td>
                                <td><?= $value->total_amount ;?></td>
                                <td><?= $tbl_domestic_weight_details->actual_weight ;?> </td>
                                <td><?= $tbl_domestic_weight_details->no_of_pack;?> </td>
                                <td><?= $value->invoice_no;?></td>
                                <td><?= $value->invoice_value;?></td>
                                <?php $domestic = $this->db->query("SELECT * FROM `tbl_domestic_tracking` where booking_id = '$value->booking_id' limit 1")->row();?>
                                <td><?= $domestic->branch_name;?></td>
                                <td><?= $value->eway_no ;?></td>
                                <?php if($value->booking_type == 1){?>
                                <td>  <button type="button" class="btn btn-outline-success btn-sm">Booked</button>
                                <?php } 
                                    $city_id2 = $value->sender_city;
                                    $resAct = $this->db->query("select * from tbl_city where city_id='$city_id2'");
                                    if ($resAct->row() !== null) {
                                        $city_sender = $resAct->row()->city_name;
                                    } else {
                                        $city_sender = "Unknown"; 
                                        error_log("No row fetched from the query in shipment_list.php");
                                    }
                                    $city_id3 = $value->reciever_city;
                                    $resActs = $this->db->query("select * from tbl_city where city_id='$city_id3'"); 
                                    if ($resActs->row() !== null) {  
                                        $city_receiver = $resActs->row()->city_name;
                                    } else {   
                                        $city_receiver = "Unknown"; 
                                        error_log("No row fetched from the query in shipment_list.php");
                                    }
                                    $city_reciver = "";  
                                    $city_reciver = "Some City"; 
                                    $no_of_pack = isset($value->no_of_pack) ? $value->no_of_pack : 'N/A';
                                    $print_string = $value->pod_no . '#|#' . $city_sender . '#|#' . $city_reciver . '#|#' . $value->mode_dispatch . '#|#' . $no_of_pack . '#|#' . $value->reciever_address;
                                    $print_string = base64_encode($print_string);
                                    $print_string = rtrim($print_string, '=');
                                    if($value->pickup_in_scan == '0' && $value->branch_in_scan == '0'){
                                ?>

                                <button type="button" relid = "<?= $value->booking_id ;?>"  class="btn btn-outline-danger view_shipment btn-sm mt-1">Cancel</button> <?php } ?></td>
                                <td><a href=" <?= base_url();?>franchise/print-label-franchise/<?php echo $value->booking_id; ?>" target="_blank" title="Print"><i class="fas fa-print" style="color:var(--success)"></i></a>  |  
                                <a target="_blank" href="<?php echo base_url(); ?>franchise/franchise-printlabel/<?php echo $print_string; ?>"><i class="ion-printer"></i></a> &nbsp;
                                </td>
                                </tr>
                                <?php endforeach; ?>
                                 <?php } else { ?>
                                 <tr><td colspan="20" style="color:red;text-align:center;">No Records founds</td></tr>
                                 <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
					<div class="col-md-6">
							<?php echo $this->pagination->create_links(); ?>
					</div>
				  </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Card DATA-->
</div>
</main>
<div class="modal fade" id="show_shipment_data">
<div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">
               Booking Data
            </h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <i class="icon-close"></i>
            </button>
        </div>
        <form  action="#" method="POST">
          <div class="modal-body">
            <table class="table">
                <tr>
                   <td> Total Amount     </td> <td> <p id="total_amount"></p> </td>
                   <td> Dispatch Details </td> <td> <p id="dispatch_details"></p></td>
                    <td> Booking Date  </td> <td><p id="booking_date"></p></td>
                <!--</tr>-->
                <!--<tr>-->
                </tr>
                <tr>
                   <td> Receiver Zone </td> <td><p id="receiver_zone"></p></td>
                   <td> Receiver GST No. </td> <td> <p id="receiver_gstno"></p></td>
                   <td> Receiver Contact  </td> <td><p id="reciever_contact"></p></td>
                </tr>
                <tr>
                   <td> Receiver Pincode </td> <td> <p id="reciever_pincode"></p></td>
                   <td> Receiver State </td> <td> <p id="reciever_state"></p></td>
                   <td> Receiver City  </td> <td><p id="reciever_city"></p></td>
                </tr>
                <tr>
                   <td> Receiver Name  </td> <td><p id="reciever_name"></p></td>
                   <td> Receiver Address  </td> <td><p id="reciever_address"></p></td>
                   <td> Contact person </td> <td> <p id="contactperson_name"></p></td>
                </tr>
              
                <tr>
                   <td> Sender Name </td> <td> <p id="sender_name"></p></td>
                   <td> Sender GST No. </td> <td> <p id="sender_gstno"></p></td>
                   <td> Sender Contact Number  </td> <td><p id="sender_contactno"></p></td>
                </tr>
                <tr>
                    <td> Sender Pincode </td> <td> <p id="sender_pincode"></p></td>
                    <td> Sender City </td> <td> <p id="sender_city"></p></td>
                    <td> Sender State  </td> <td><p id="sender_state"></p></td>
                </tr>
                <tr>
                   <td> Sender Address  </td> <td><p id="sender_address"></p></td>
                    <td> Bill Type </td> <td> <p id="mode_dispatch"></p></td>
                    <td> Risk Type  </td> <td><p id="risk_type"></p></td>
                </tr>
                <tr>
                   <td> Company Type  </td> <td><p id="company_type"></p></td>
                    <td> POD No.  </td> <td><p id="pod_no"></p></td>
                </tr>
                <tr>
                    <td>Enter Cancel Reason</td>
                    <td>  <textarea name = "cancel_msg" id="cancel_msg" rows="4" cols="50" placeholder="Enter Comment.." required></textarea></td>
                </tr>                
            </table>
          </div>
            <div class="modal-footer">
                <input type="hidden" id="booking_id" name="booking_id">
                <button type="button" class="btn btn-primary update_shipment add-todo">Submit</button>
            </div>
        </form>
    </div>
    </div>
</div>

<style>
    td p{color:#000204;font-weight:bold;}
    @media (min-width: 576px){
  .modal-dialog {
    max-width: 900px;
    margin: 1.75rem auto;
}}
</style>

<?php include(dirname(__FILE__).'/../franchise_shared/franchise_footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.12.15/dist/sweetalert2.all.min.js"></script> 
<script>
    $(document).ready(function(){
        
        $('.view_shipment').click(function(){
            var booking_id = $(this).attr('relid');
          //  alert(booking_id);
            
           $.ajax({
                
                url :"<?php echo base_url(); ?>Franchise_manager/view_booking_shipment",
                data:{booking_id : booking_id},
                method:'POST',
                dataType:'json',
                success:function(response) {
                 // console.log(response);
                 
                      var total_amount         = response[0].total_amount;
                      var dispatch_details     = response[0].dispatch_details;
                      var booking_date         = response[0].booking_date;
                      var booking_id           = response[0].booking_id;
                      var special_instruction  = response[0].special_instruction;
                      var receiver_zone        = response[0].receiver_zone;
                      var receiver_gstno       = response[0].receiver_gstno;
                      var receiver_zone        = response[0].receiver_zone;
                      var reciever_state       = response[0].reciever_state;
                      var reciever_city        = response[0].reciever_city;
                      var reciever_pincode     = response[0].reciever_pincode;
                      var reciever_address     = response[0].reciever_address;
                      var contactperson_name   = response[0].contactperson_name;
                      var reciever_name        = response[0].reciever_name;
                      var sender_gstno         = response[0].sender_gstno;
                      var sender_contactno     = response[0].sender_contactno;
                      var sender_pincode       = response[0].sender_pincode;
                      var sender_state         = response[0].sender_state;
                      var sender_city          = response[0].sender_city;
                      var sender_address       = response[0].sender_address;
                      var sender_name          = response[0].sender_name;
                      var risk_type            = response[0].risk_type;
                      var mode_dispatch       = response[0].mode_dispatch;
                      var company_type        = response[0].company_type;
                      var pod_no              = response[0].pod_no;
                      var reciever_contact              = response[0].reciever_contact;
                      
                     // alert(total_amount);
                   $('#total_amount').html(total_amount);
                   $('#booking_id').val(booking_id);
                   $('#dispatch_details').html(dispatch_details);
                   $('#booking_date').html(booking_date);
                   $('#special_instruction').html(special_instruction);
                   $('#receiver_zone').html(receiver_zone);
                   $('#receiver_gstno').html(receiver_gstno);
                   $('#reciever_contact').html(reciever_contact);
                   $('#reciever_state').html(reciever_state);
                   $('#reciever_city').html(reciever_city);
                   $('#reciever_pincode').html(reciever_pincode);
                   $('#reciever_address').html(reciever_address);
                   $('#contactperson_name').html(contactperson_name);
                   $('#reciever_name').html(reciever_name);
                   $('#sender_gstno').html(sender_gstno);
                   $('#sender_contactno').html(sender_contactno);
                   $('#sender_pincode').html(sender_pincode);    
                   $('#sender_state').html(sender_state);    
                   $('#sender_city').html(sender_city);    
                   $('#sender_address').html(sender_address);    
                   $('#sender_name').html(sender_name);    
                   $('#risk_type').html(risk_type);    
                   $('#mode_dispatch').html(mode_dispatch);    
                   $('#company_type').html(company_type);    
                   $('#pod_no').html(pod_no);    
                  $('#show_shipment_data').modal({backdrop: 'static', keyboard: true, show: true}); 
                  
                }
           });
            
        });
        
        
         $('.update_shipment').on('click',function(){
           var booking_id   = $('#booking_id').val();
           var cancel_msg   = $('#cancel_msg').val(); 

           if(cancel_msg != ''){
        //   alert(booking_id);
            var baseurl = '<?php echo base_url();?>';
       	swal({
		  	title: 'Are you sure?',
		  	text: "You won't be able to revert this!",
		  	icon: 'warning',
		  	showCancelButton: true,
		  	confirmButtonColor: '#3085d6',
		  	cancelButtonColor: '#d33',
		  	confirmButtonText: 'Yes, Cancel it!',
		}).then((result) => {
		  	if (result.value){
		  	  
		  		$.ajax({
			   		url: baseurl+'Franchise_manager/cancel_shipment',
			    	type: 'POST',
			       	data: {booking_id : booking_id, cancel_msg :cancel_msg },
			       	dataType: 'json'
			    })
			     .done(function(response){
			         //console.log(response);
			     	swal('Cancel!', response.message, response.status)
			     	 
                   .then(function(){ 
                    location.reload();
                   })
			     
			    })
			    .fail(function(){
			     	swal('Oops...', 'Something went wrong with ajax !', 'error');
			    });
		  	}
 
		})
    }else{
          alert("Cancel Reason Filled are required");
    }
	});        
        
        
        
        
    });
</script>    