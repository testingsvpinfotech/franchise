<?php include(dirname(__FILE__).'/../admin_shared/admin_header.php'); ?>
    <!-- END Head-->

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
                              <h4 class="card-title">All Shipment</h4>
                              
                          </div>
                          <div class="card-body">
                              <div class="table-responsive">
                                  <table class="table layout-primary bordered">
                                      <thead>
                                          <tr>
												 <th>Date</th>
											   <th style="text-align: center;">Airway No</th>
											   <th style="text-align: center;">Network</th>
											   <th style="text-align: center;">ForwordingNo</th>
											   <th style="text-align: center;">Destination</th>
											   <th style="text-align: center;">Receiver</th>
											   <th style="text-align: center;">Doc/Non-doc</th>
											   <th>Print</th>
                                          </tr>
                                      </thead>
                                     <tbody>
								<tr>
									<?php 
									if(!empty($list)){
										foreach($list as $value)
										{
									?>
								    	<td><?php echo date("d-m-Y",strtotime($value->booking_date));?></td>
								    	<td><?php //echo $value->pod_no;
								    	echo '<a href="'.base_url().'users/track_shipment?pod_no='.$value->pod_no.'&submit=1" >'.$value->pod_no.'</a>';?></td>
										<td><?php echo  $value->forworder_name; ?></td>
                                        <td><?php echo  $value->forwording_no; ?></td>
                                        <td><?php echo  $value->country_name; ?></td> 
                                        <td><?php echo  $value->reciever_name; ?></td>
                                        <td><?php echo  $value->doc_nondoc; ?></td>
                                        <td>
		                                        <a href="<?php echo base_url(); ?>User_panel/international_printpod/<?php echo $value->booking_id;?>" target="_blank" >Print</a>
                                        </td>
									
									
								</tr>
								<?php
							        }
								}
							?>
								<?php 
									if(!empty($list_d)){
										foreach($list_d as $value_d)
										{
									?>
									
									    <td><?php echo date("d-m-Y",strtotime($value_d->booking_date));?></td>
								    	<td><?php //echo $value->pod_no;
								    	echo '<a href="'.base_url().'users/track_shipment?pod_no='.$value_d->pod_no.'&submit=1" >'.$value_d->pod_no.'</a>';?></td>
										<td><?php echo  $value_d->forworder_name; ?></td>
                                        <td><?php echo  $value_d->forwording_no; ?></td>
                                        <td><?php echo  $value_d->city; ?></td> 
                                        <td><?php echo  $value_d->reciever_name; ?></td>
                                        <td><?php echo  $value_d->doc_nondoc; ?></td>
                                        <td>
		                                        <a href="<?php echo base_url(); ?>User_panel/domestic_printpod/<?php echo $value_d->booking_id;?>" target="_blank" >Print</a>
                                        </td>
								</tr>
								<?php
						        	}
							}
							?>
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
<script >
			// this function is use for redirecting page on preseleted campaing on schedule page  
			
			function checkAll(ele) {
     var checkboxes = document.getElementsByTagName('input');
     if (ele.checked) {
         for (var i = 0; i < checkboxes.length; i++) {
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = true;
             }
         }
     } else {
         for (var i = 0; i < checkboxes.length; i++) {
             console.log(i)
             if (checkboxes[i].type == 'checkbox') {
                 checkboxes[i].checked = false;
             }
         }
     }
 }
 
	$('.multiple_campings').click(function()
	{
		var new_sel_cam		 	= $(this).val();
		var pre_selected_cam 	= $('#selected_campaingss').val();
		if($(this).prop("checked") == true)
		{
			$('#selected_campaingss').val(new_sel_cam+'-'+pre_selected_cam);
		}
		else if($(this).prop("checked") == false)
		{
			pre_selected_cam = pre_selected_cam.replace(new_sel_cam+'-','');
			$('#selected_campaingss').val(pre_selected_cam);
		}
		
	});
	
	// this function is use for redirecting page on preseleted campaing on schedule page  
	$('#select_multiple_camp').click(function()
	{
		var pre_selected_cam 	= $('#selected_campaingss').val();
		
		if(pre_selected_cam !== null)
		{
			var nes = 		pre_selected_cam.slice(0,-1);
			var favorite = [];
            $.each($("input[name='multiple_delete[]']:checked"), function(){            
                favorite.push($(this).val());
            });
			favorite = favorite.join("-");
			
			if(favorite != '')
			{
				window.location = 'generatepod/all_printpod/'+favorite+'-'+nes;
			}
			else
			{
				alert('Pleaese choose at least one Shipment');
			}	
		}
		else
		{
			var favorite = [];
            $.each($("input[name='multiple_delete[]']:checked"), function(){            
                favorite.push($(this).val());
            });
			favorite = favorite.join("-");
			
			if(favorite != '')
			{
				window.location = 'generatepod/all_printpod/'+favorite;
			}
			else
			{
				alert('Pleaese choose at least one Shipment');
			}
		}
		
			
	});
	
  
/* $("#filterpod").validate({
   rules: {
		from_date: "required",
		to_date: "required"
	},
	errorPlacement: function(error, element) {
		error.insertAfter(element);
	},
	messages: {
		 //email: "Please provide email address"       
	},      
	submitHandler: function(form)
	{
		form.submit();
	}     
}); */
</script>
<div class="modal fade in" id="modal-default" style="padding-right: 17px;">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Print Bulk Shipment</h4>
              </div>
                <form name="filterpod" id="filterpod" action="generatepod/all_printpod" method="POST">
              <div class="modal-body">
						<div class="col-md-4">
							<label>Customer</label>
							<select class="form-control" name="user_id">
							<?php if(!empty($customer))
							{
								foreach($customer as $key => $values)
								{ ?>
									<option value="<?php echo $values->customer_id; ?>" ><?php echo $values->customer_name; ?></option>
									<?php
								}
							} ?>
							</select>

						</div>
						<div class="col-md-4">
							<label>From Date</label>
							<input type="date" class="form-control" name="from_date" value="<?php echo $_GET['from_date'];?>" />
						</div>
						<div class="col-md-4">
							<label>To Date</label>
							<input type="date" class="form-control" name="to_date" value="<?php echo $_GET['to_date'];?>" />
						</div>
						<div class="col-md-4">
							
						</div>
              </div>
			  <div class="modal-footer">
				<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
				<button type="submit" class="btn btn-primary">Print</button>
			  </div>
					</form>
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
		