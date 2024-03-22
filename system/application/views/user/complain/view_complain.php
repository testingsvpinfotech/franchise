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
            <div class="col-xl-12">
                <section class="hk-sec-wrapper">
                    <h5 class="hk-sec-title">Support
                    	<span class="pull-right">
                    		<a href="User_panel/open_ticket" class="btn btn-primary">Open New Ticket</a>
                    	</span>
                    </h5>
                    <p class="mb-10">all information of each support tickets.</p>
                    <hr>

                    <div class="row">
                        <div class="col-sm">
                            <div class="table-wrap">
                                <table id="datable_1" class="table table-bordered table-striped pb-30">
                                    <thead class="thead-primary">
                                        <tr>
                                        	<th>Sr.</th>
                                            <th>Ticket ID</th>
                                            <th>Coustmor Name</th>
                                            <th>Subject</th>
                                            <th>Date</th>                                          
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
								<?php if(!empty($all_ticket))
										{
											$cnt = 1;
											foreach($all_ticket  as $key => $values)
											{
												?>
                                        <tr>
                                           <td><?php echo $cnt; ?></td>
											<td>#<?php echo $values->ticket_id; ?></td>
                                            <td><?php echo $values->customer_name; ?></td>
											<td><?php echo $values->subject; ?></td>
											<td><?php echo $values->time; ?></td>
                                            <td><span class="badge badge-<?php echo ($values->action == 'Close')?'warning':'success'; ?> badge-pill"><?php echo $values->action; ?></span></td>
                                            <td>
                                            	<a href="User_panel/ticket_detail/<?php echo $values->ticket_id; ?>" title="View" class="btn btn-xs btn-primary" target="_blank"><i class="fa fa-eye"></i></a>
                                            	<a href="User_panel/ticket_close/<?php echo $values->ticket_id; ?>" title="Close Tickte" class="btn btn-xs btn-danger" onclick="return confirm('Are you sure you want to Close This Ticket?');"><i class="ion-trash-b"></i></a>
												<?php if($this->session->userdata('user_id') == 1)
													{ ?>
														<a href="User_panel/delete_ticket/<?php echo $values->ticket_id; ?>" title="Delete" class="btn btn-xs btn-danger"><i class="ion-trash-b"></i></a>
													<?php 
													} 
													?>
                                            </td>
                                        </tr>
										
										<?php 
												$cnt++;
												}
											}
											?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
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
		