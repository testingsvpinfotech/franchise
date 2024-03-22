     <?php $this->load->view('admin/admin_shared/admin_header'); ?>
    <!-- END Head-->

    <!-- START: Body-->
    <body id="main-container" class="default">

        
        <!-- END: Main Menu-->
    <?php $this->load->view('admin/admin_shared/admin_sidebar');
   // include('admin_shared/admin_sidebar.php'); ?>
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
                              <h4 class="card-title">Edit Outgoing</h4>  
                          </div>
                          <div class="card-body">
                                <div class="card-body">
						   <div class="row">                                           
                            <div class="col-12">
								<form role="form" action="admin/updateoutgoing/<?php echo $outword[0]['id'];?>" method="post" enctype="multipart/form-data">

								<div class="box-body"> 
								
								<div class="form-group row">
								<label class="col-sm-1 col-form-label">Menifiest Id:</label>
								<div class="col-sm-2">
								<select class="form-control"  name="manifiest_id" required>
									<option value="">Select Menifiest Id</option>
									<?php 
										if (count ($menifiest)){
										  foreach ($menifiest as $row ) { 
														   
									?>
										<option value="<?php echo $row['manifiest_id'];?>" <?php echo ($row['manifiest_id'] == $outword[0]['manifiest_id'])?'selected':'';?>>
																  
									<?php echo $row['manifiest_id'];?> 
									   </option>
									<?php 
									}
								 }
								  else{
										echo "<p>No Data Found</p>";
									 }
									?>
								</select>
							</div>
							  <label class="col-sm-1 col-form-label">Airway No.:</label>
							<div class="col-sm-2">
							  <select class="form-control"  name="airway_no" required>
									<option value="">Airway No</option>
									<?php 
										if (count ($menifiest)){
										  foreach ($menifiest as $rows ) { 
														   
									?>
										<option value="<?php echo $rows['pod_no'];?>" <?php echo ($rows['pod_no'] == $outword[0]['airway_no'])?'selected':'';?>>
																  
									<?php echo $rows['pod_no'];?> 
									   </option>
									<?php 
									}
								 }
								  else{
										echo "<p>No Data Found</p>";
									 }
									?>
								</select>
							</div>
							
							  <label class="col-sm-1 col-form-label">Status:</label>
							<div class="col-sm-2">
								<select class="form-control"  name="status" required>
									<option value="">Select Status</option>
									<option value="recieved" <?php echo ('recieved' == $outword[0]['status'])?'selected':'';?>>Recieved</option>
									<option value="pending" <?php echo ('pending' == $outword[0]['status'])?'selected':'';?>>Pending</option>
								</select>
							</div>
								</div>
								<div class="col-md-3">
								<div class="box-footer pull right">
								<button type="submit" name="submit"  class="btn btn-primary">Submit</button>
								</div>
								</div>
								<!--  col-sm-4--> 

								<!--  box body-->
								</div>
								</form>  
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
    <?php $this->load->view('admin/admin_shared/admin_footer');
     //include('admin_shared/admin_footer.php'); ?>
    <!-- START: Footer-->
</body>
<!-- END: Body-->


          <script type="text/javascript">
           $(document).ready(function()
		  {
			  
			  $("#serach_data").blur(function()
				{
					 var awb_no=$(this).val();
			
				$.ajax({
					   url: "Admin_deliverysheet/awbnodata",
					   type: 'POST',
					   dataType: "html",
					   data: {awb_no: awb_no},
					   error: function() {
						  alert('Please Try Again Later');
					   },
					   success: function(data) {
						//console.log(data);
						
						if(data !=""){
						  $("#change_status_id").append(data);  
						  var array = []; 
						
							tw=0;
							tp =0;
				
						$("input.cb[type=checkbox]:checked").each(function() { 
							
							tw = tw + parseFloat($(this).attr("data-tw"));
							tp = tp + parseFloat($(this).attr("data-tp"));
						
								 }); 
						 
							document.getElementById('total_weight').value = tw;
							document.getElementById('total_pcs').value = tp;
						}
						else{
						  $("#change_status_id").append('');  
						}
							
							//alert("Record added successfully");  
					   }
					});
					
						
			});


        $("#podbox").change(function(){
        
        var podno=$(this).val();
        if (podno!=null || podno!='') {
            
            $.ajax({
              type:'POST',
              url:'Admin_deliverysheet/getPODDetails',
              data:'podno='+podno,
              success:function(d)
              {
                //alert(d);
                  var x=d.split("-");
                //alert(x);
                   $(".consignername").val(x[0]);
                  
                   $(".pieces").val(x[2]);
                   $(".weight").val(x[3]);
              }
            });
        }else{

        }

    });
    
  });

</script>