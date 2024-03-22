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
            <div class="col-xl-3">
                <div class="hk-sec-wrapper">
                    <div class="title">
                        <h5 class="hk-sec-title">Quick Info</h5>
                        <p>quitck information about tickets.</p>
                        <hr>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Open Ticket:
                            <span class="badge badge-primary badge-pill"><?php  echo (!empty($total_open_ticket))?$total_open_ticket->total_ticket:0; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Replied:
                            <span class="badge badge-primary badge-pill"><?php echo (!empty($total_replied_ticket))?$total_replied_ticket->total_ticket:0; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Coustmor Replied:
                            <span class="badge badge-primary badge-pill"><?php echo (!empty($total_creplied_ticket))?$total_creplied_ticket->total_ticket:0; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Closed Ticket:
                            <span class="badge badge-primary badge-pill"><?php echo (!empty($total_closed_ticket))?$total_closed_ticket->total_ticket:0;?></span>
                        </li>
                    </ul>
                    

                    <a href="" class="btn btn-block btn-rounded btn-primary mt-20">Back to Tickets</a>
                </div>
            </div>

            <div class="col-xl-9">
                <section class="hk-sec-wrapper">
                    <h5 class="hk-sec-title">Open New Ticket</h5>
                    <p class="mb-10">open new support ticket here.</p>
                    <hr>

                    <form action="User_panel/insert_ticket" method="post" enctype="multipart/form-data">
                        <div class="form-group row">
							<div class="col-md-4">
                                <label class="mt-10">Select POD:</label>
                                <select class="form-control" required="" name="pod_no">
								 <optgroup label="Domestic Shipment">
								<?php if(!empty($domstic_pod))
										{
											foreach($domstic_pod  as $key => $values)
											{
												echo "<option value='$values->pod_no'>$values->pod_no</option>";
											}
										}
											?>
											</option>
											 <optgroup label="International Shipment">
											 	<?php if(!empty($international_pod))
										{
											foreach($international_pod  as $key => $values)
											{
												echo "<option value='$values->pod_no'>$values->pod_no</option>";
											}
										}
											?>
											</option>
                                </select>
                            </div>
						
                            <div class="col-md-2">
                                <label class="mt-10">Priority:</label>
                                <select class="form-control" required="" name="priorty">
                                    <option value="High">High</option>
                                    <option value="Medium">Medium</option>
                                    <option value="Low" selected="">Low</option>
                                </select>
                            </div>
							
                            <div class="col-md-7">
                                <label class="mt-10">Subject:</label>
                                <input type="text" name="subject" class="form-control" placeholder="Enter Your Subject" required="">
                            </div>

                            <div class="col-md-12">
                                <label class="mt-10">Message:</label>
                                <textarea class="textarea form-control wysihtml5-textarea" name="message" placeholder="Enter text ..."  style="height:100px; width:100%;"></textarea>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="mt-10">Add Attechment:</label>
                                    <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">Upload</span>
                                        </div>
                                        <div class="form-control text-truncate" data-trigger="fileinput"><i class="glyphicon glyphicon-file fileinput-exists"></i> <span class="fileinput-filename"></span></div>
                                        <span class="input-group-append">
                                                <span class=" btn btn-primary btn-file"><span class="fileinput-new">Select file</span><span class="fileinput-exists">Change</span>
                                        <input type="file" name="file">
                                        </span> 
                                        </span>
                                    </div>
                                </div>    
                            </div>

                            <div class="col-md-12 text-right">
                                <hr>
                                <a href="" class="btn btn-rounded btn-secondary">Back</a>
                                <button type="submit" class="btn btn-rounded btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
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
		