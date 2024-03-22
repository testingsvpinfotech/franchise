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
                <div class="hk-sec-wrapper p-0">
                    <div class="pt-20  pl-20 pr-20">
                        <h5 class="hk-sec-title">Quick Info</h5>
                        <p>quitck information about tickets.</p>
                        <hr>
                    </div>
                   

                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th>Ticket:</th>
                                <td class="text-right">#<?php echo $ticket_info->ticket_id; ?></td>
                            </tr>
                            <tr>
                                <th>Status:</th>
                                <td class="text-right"><span class="badge badge-<?php echo ($ticket_info->action == 'Close')?'warning':'success'; ?>"><?php echo $ticket_info->action; ?></span></td>
                            </tr>
                            <tr>
                                <th>Submitted:</th>
                                <td class="text-right">#<?php echo $ticket_info->time; ?></td>
                            </tr>
                            <tr>
                                <th>Priority:</th>
                                <td class="text-right"><span class="badge badge-pill badge-danger"><?php echo $ticket_info->priorty; ?></span></td>
                            </tr> 
                        </tbody>
                    </table>
                    <hr class="m-0">
                    
                </div>
            </div>
<style type="text/css">
.ticket-icon {
    font-size: 20px;
    line-height: 40px;
    margin-right: 15px;
    background: #ab47bc;
    color: #fff;
    width: 40px;
    height: 40px;
    text-align: center;
    border-radius: 50%;
}
</style>
            <div class="col-xl-9">
                <section class="hk-sec-wrapper">
                    <div class="">
                        <div class="card-block">
                            <h4 class="mt-0 font-18"><strong>Subject :</strong> <?php echo $ticket_info->subject; ?></h4>
                            <h4 class="mt-0 font-18"><strong>POD No :</strong> <?php echo $ticket_info->pod_no; ?></h4>
                            <hr>
							<?php 
							if(!empty($ticket_chat))
							{
								foreach($ticket_chat as $k => $va)
								{
									 ?> 
									<div class="media <?php echo ($va->user_type == 1)?'bg-light-10 pt-10 pb-10 pl-10 pr-10 mb-30':''; ?>">
										<i class="fa fa-user ticket-icon"></i>
										<div class="media-body">
											<h4 class="font-14 m-0"><?php echo ($va->user_type == 1)?'Admin':$va->customer_name; ?></h4>
											<small class="text-muted">to :<?php echo ($va->user_type == 1)?$va->to_email:'Admin'; ?></small>
										
										</div>
										<span class="pull-right text-right mr-5">
											<p><?php echo $va->m_time; ?></p>
										</span>
									</div>
									<hr>
									
									<p class="mb-30">Dear <?php echo ($va->user_type == 1)?$ticket_user_info->customer_name:'Admin'; ?>,</p>
									<p class="mb-30"><?php echo $va->message; ?>.</p>
									<p class="mb-30">Sincerly,</p>
									<hr>
									<?php if($va->attechment != '')
									{ ?>
									<div class="row">
										<a href="assets/upload/<?php echo $va->attechment; ?>" class="mr-10" download="">
											<div class="card card-sm w-200p">
												<div class="card-body d-flex align-items-center">
													<img src="assets/jpgicon.png" class="d-inline-block mr-10" alt="attachicon">
													<span class="d-inline-block mnw-0">
														<span class="d-block file-name text-truncate"><?php echo $va->attechment; ?></span>
														<small class="d-block file-size text-truncate">5.78 MB</small>
													</span>
												</div>
											</div>
										</a>
									</div>
									<?Php } ?>
							<?php 
							} } ?>
							<div class="btn-block reply-div">
                                <button class="btn btn-light btn-sm btn-rounded" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="true" aria-controls="collapseExample"><i class="fa fa-reply"></i> Reply</button>
                            </div>
                        </div>
                    </div>
                </section>

                <div class="collapse" id="collapseExample" style="">
                    <div class="card card-body">
                        <form action="User_panel/insert_replay/<?php echo $ticket_info->ticket_id; ?>" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-reply"></i></span>
                                    </div>
                                    <input type="text" class="form-control" value="<?php echo ($user_id != 1)?'Admin':$ticket_info->email; ?>" aria-label="Username" aria-describedby="basic-addon1" readonly="">
                                </div>

                                <div class="form-group">
                                    <label class="mt-10">Message:</label>
                                    <textarea class="textarea form-control wysihtml5-textarea" name="message" placeholder="Enter text ..." style="height:100px; width:100%;"></textarea>
                                </div>
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

                                <div class="form-group text-right">
                                    <hr>
                                    <button type="submit" class="btn btn-rounded btn-primary">Submit</button>
                                </div>
                            </div>

                        </form>
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
		