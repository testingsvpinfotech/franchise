<?php include(dirname(__FILE__).'/../admin_shared/admin_header.php'); ?>
    <!-- END Head-->
<style>
  .buttons-copy{display: none;}
  .buttons-csv{display: none;}
  /*.buttons-excel{display: none;}*/
  .buttons-pdf{display: none;}
  .buttons-print{display: none;}
  /*#example_filter{display: none;}*/
  .input-group{
    width: 60%!important;
  }
</style>
    <!-- START: Body-->
    <body id="main-container" class="default">

        
        <!-- END: Main Menu-->
   
    <?php include(dirname(__FILE__).'/../admin_shared/admin_sidebar.php'); ?>
        <!-- END: Main Menu-->
    
        <!-- START: Main Content-->
        <main>
            <div class="container-fluid site-width">
                <!-- START: Listing-->
                <div class="row">                 
                  <div class="col-12  align-self-center">
                      <div class="col-12 col-sm-12 mt-3">
                      <div class="card">
                          <div class="card-header justify-content-between align-items-center">                               
                              <h4 class="card-title">Pickup Pending List</h4>
                           
                          </div>

						   <div class="card-header justify-content-between align-items-center">                             
							 
                          </div>
                          <div class="card-body">
                       
                              <div class="table-responsive">
                                 <table class="display table dataTable table-striped table-bordered layout-primary" data-sorting="true"><!-- id="example"-->
                                      <thead>
                                          <tr>
												<th  scope="col">Customer Name</th>
											    <th  scope="col">AWB.No</th>
											    <th  scope="col">Sender</th>
											    <th  scope="col">Sender Pincode</th>
											    <th  scope="col">Receiver </th>
											    <th  scope="col">Receiver City</th>
											    <th  scope="col">Receiver Pincode</th>
											    <th  scope="col">Forwarding No</th>
											    <th  scope="col">Forwarder Name</th>
											    <th  scope="col">Booking date</th>
												<th  scope="col">Mode</th>
												<th  scope="col">Pay Mode</th>
												<th  scope="col">Amount</th>
												<th  scope="col">Weight</th>
												<th  scope="col">NOP</th>
												<th  scope="col">Invoice No</th>
												<th  scope="col">Invoice Amount</th>
												<th scope="col">Branch Name</th>
												<th scope="col">Eway No</th>
												
                                          </tr>
                                      </thead>
                                      <tbody>
                                      	<?php if(!empty($pickup_list)){ ?>
                                      	 <?php foreach($pickup_list as $value )	:?>
                                      	<tr>
                                          <td><?= $value['customer_name'] ;?></td>
                                          <td><?= $value['pod_no'] ;?></td>
                                          <td><?= $value['sender_name'] ;?></td>
                                          <td><?= $value['sender_pincode'] ;?></td>
                                          <td><?= $value['reciever_name'] ;?></td>
                                          <td><?= $value['reciever_city'] ;?></td>
                                          <td><?= $value['reciever_pincode'] ;?></td>
                                          <td><?= $value['forwording_no'] ;?></td>
                                          <td><?= $value['forworder_name'] ;?></td>
                                          <td><?= $value['booking_date'] ;?></td>
                                          <td><?= $value['mode_dispatch'] ;?></td>
                                          <td><?= $value['payment_method'] ;?></td>
                                          <td><?= $value['total_amount'] ;?></td>
                                          <td></td>
                                          <td></td>
                                          <td><?= $value['invoice_no'] ;?></td>
                                          <td><?= $value['invoice_value'] ;?></td>
                                          <td><?= $value['branch_id'] ;?></td>
                                          <td><?= $value['eway_no'] ;?></td>
                                        
                                           


                                        </tr>
                                       <?php endforeach;?> 
                                    <?php }?>
                                     </tbody>
                              
                                 </table> 
                              </div>
                            
                          </div>
                        </div> 

                    </div>
                    </div>
                </div>
                <!-- END: Listing-->
            </div>
        </main>
        <!-- END: Content-->
        <!-- START: Footer-->
        
        <?php  include(dirname(__FILE__).'/../admin_shared/admin_footer.php'); ?>
        <!-- START: Footer-->
    </body>
    <!-- END: Body-->
</html>


<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.css" rel="stylesheet"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.js"></script>
<script>
function matchStart (term, text) {
  if (text.toUpperCase().indexOf(term.toUpperCase()) == 0) {
    return true;
  }
 
  return false;
}
 
$.fn.select2.amd.require(['select2/compat/matcher'], function (oldMatcher) {
  $("#user_id").select2({
    matcher: oldMatcher(matchStart)
  })

});
</script>

<script>
    $(document).ready(function() {
      $('.deletedata').click(function(){
        var getid = $(this).attr("relid");
      // alert(getid);
       var baseurl = '<?php echo base_url();?>'
       	swal({
		  	title: 'Are you sure?',
		  	text: "You won't be able to revert this!",
		  	icon: 'warning',
		  	showCancelButton: true,
		  	confirmButtonColor: '#3085d6',
		  	cancelButtonColor: '#d33',
		  	confirmButtonText: 'Yes, delete it!',
		}).then((result) => {
		  	if (result.value){
		  		$.ajax({
			   		url: baseurl+'Admin_domestic_shipment_manager/delete_domestic_shipment',
			    	type: 'POST',
			       	data: 'getid='+getid,
			       	dataType: 'json'
			    })
			    .done(function(response){
			     	swal('Deleted!', response.message, response.status)
			     	 
                   .then(function(){ 
                    location.reload();
                   })
			     
			    })
			    .fail(function(){
			     	swal('Oops...', 'Something went wrong with ajax !', 'error');
			    });
		  	}
 
		})
 
	});
       
 });
</script>