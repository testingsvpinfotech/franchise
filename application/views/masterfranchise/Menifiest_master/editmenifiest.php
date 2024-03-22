<?php $this->load->view('franchise/franchise_shared/franchise_header'); ?>
    <!-- END Head-->
<style>
  	.form-control{
  		color:black!important;
  		border: 1px solid var(--sidebarcolor)!important;
  		height: 27px;
  		font-size: 10px;
  }
  </style>
    <!-- START: Body-->
    <body id="main-container" class="default">

        
        <!-- END: Main Menu-->
		<?php $this->load->view('franchise/franchise_shared/franchise_sidebar');
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
                              <h4 class="card-title">Update Menifiest</h4>  
                          </div>
                          <div class="card-body">
                                <div class="card-body">
						   <div class="row">                                           
                            <div class="col-12">
								<form role="form" action="admin/update-menifiest" method="post" enctype="multipart/form-data">

								<div class="form-group row">
									<label  class="col-sm-2 col-form-label">Manifiest Id</label>
									<div class="col-sm-2">
										<input type="text" class="form-control" name="manifiest_id" id="col-sm-1 col-form-label" placeholder="Enter Id" value="<?php echo $menifiest_info->manifiest_id; ?>" >
										<input type="hidden" class="form-control" name="inc_id" value="<?php echo $menifiest_info->inc_id;?>">
									</div>

									<label  class="col-sm-2 col-form-label">Manifiest Date</label>
									<div class="col-sm-2">
										<input type="date" class="form-control" name="datetime" id="col-sm-1 col-form-label"  value="<?php echo $menifiest_info->date_added; ?>">
									</div>
									<label  class="col-sm-2 col-form-label">Vehicle No</label>
									<div class="col-sm-2">
										<input type="text" name="lorry_no" class="form-control" value="<?php echo $menifiest_info->lorry_no; ?>"/>
									</div>	
									<label  class="col-sm-2 col-form-label">Route Name</label>
									<div class="col-sm-2">
										<select name="route_id" class="form-control" id="route_id" required>
											<option>Select Route</option>
											<?php foreach ($allroute as $value) {
												?>
												<option value="<?php echo $value['route_id'];?>"  <?php echo ($value['route_id'] == $menifiest_info->route_id)?'selected':''; ?>><?php echo $value['route_name'];?></option>
												<?php 
											} ?>
										</select>
									</div>
								</div>
								<div class="form-group row">																
									<label  class="col-sm-2 col-form-label">Driver Name</label>
									<div class="col-sm-2">
										<input type="text" name="driver_name" class="form-control"  value="<?php echo $menifiest_info->driver_name; ?>"/>
									</div>
									<label  class="col-sm-2 col-form-label">Contact No</label>
									<div class="col-sm-2">
										<input type="text" name="contact_no" class="form-control" value="<?php echo $menifiest_info->contact_no; ?>"  />
									</div>
									<label  class="col-sm-2 col-form-label">Destination Branch</label>
									<div class="col-sm-2">
										<select name="destination_branch" class="form-control" id="destination_branch" required>
											<option>Select Branch</option>
											<?php foreach ($all_branch as $value) {
												?>
												<option value="<?php echo $value->branch_name;?>" <?php echo ($value->branch_name == $menifiest_info->destination_branch)?'selected':''; ?>><?php echo $value->branch_name;?></option>
												<?php 
											} ?>
										</select>
									</div>
									
								</div>
								<div class="form-group row">
								<label  class="col-sm-2 col-form-label">Vendor</label>
									<div class="col-sm-2">
										<select name="vendor_id" class="form-control" id="vendor_id" required>
											<option>Select Vendor</option>
											<?php foreach ($all_vendor as $value) {
												?>
												<option value="<?php echo $value->tv_id;?>" <?php echo ($value->tv_id == $menifiest_info->vendor_id)?'selected':''; ?>><?php echo $value->vendor_name;?></option>
												<?php 
											} ?>
										</select>
									</div>
									 <label  class="col-sm-2 col-form-label">Forworder Name</label>
									<div class="col-sm-2">
										<select name="forwarder_name" class="form-control" id="forwarderName" required>
											<option value="">Select Forworder Name</option>
											<?php foreach ($courier_company as $value) {
												?>
												<option value="<?php echo $value['c_company_name'];?>" <?php echo ($value['c_company_name'] == $menifiest_info->forwarder_name)?'selected':''; ?>><?php echo $value['c_company_name'];?></option>
												<?php 
											} ?>
										</select>
									</div>
										<label  class="col-sm-2 col-form-label">Coloader</label>
									<div class="col-sm-2">
										<select name="coloader" class="form-control" id="coloader" >
											<option value="">Select Coloader </option>
											<?php foreach ($coloader_list as $value) {
												?>
												<option value="<?php echo $value['coloader_name'];?>" <?php echo ($value['coloader_name'] == $menifiest_info->coloader)?'selected':''; ?>><?php echo $value['coloader_name'];?></option>
												<?php 
											} ?>
										</select>
									</div>	
									<label  class="col-sm-2 col-form-label">Coloader Contact</label>
									<div class="col-sm-2">
										<input type="text" name="coloder_contact"  value="<?php echo $menifiest_info->coloder_contact; ?>"  class="form-control" />
									</div>	
								    <label  class="col-sm-2 col-form-label">Mode</label>
									<div class="col-sm-2">
									<select name="forwarder_mode" class="form-control" id="forwarder_mode" required>
										<option value="">Select Forworder Mode</option>
											<option value="All"  <?php if( 'All' == trim($menifiest_info->forwarder_mode) ){echo "selected";} ?>>All</option>
										<?php foreach ($mode_list as $value) {
										?>
										<option value="<?php echo $value['mode_name'];?>" <?php if( trim($value['mode_name']) == trim($menifiest_info->forwarder_mode) ){echo "selected";} ?>><?php echo $value['mode_name'];?></option>
										<?php 
									} ?>
									</select>
									</div>
									<label  class="col-sm-2 col-form-label">Total Pcs</label>
									<div class="col-sm-2">										
										<input type="text" readonly name="total_pcs" id="total_pcs" required class="form-control" value="<?php echo $total_pcs; ?>" />
									</div>	
									<label  class="col-sm-2 col-form-label">Total Weight</label>
									<div class="col-sm-2">
										<input type="text" readonly name="total_weight" id="total_weight" required class="form-control" value="<?php echo $total_weight; ?>" />
									</div>
								</div>								
							
								
								<div class="col-md-3">
								<div class="box-footer pull right">
								<button type="submit" name="submit"  class="btn btn-primary">Submit</button>
								</div>
								</div>
								<div class="col-md-12">
								<input type="text" id="search_data" placeholder="Enter Forwording No" style="float: right;" >
								<input type="button" id="btn_search" style="float: right;"  value="Search">
								<br>
								<!--  col-sm-4--> 
								<table class="table table-bordered table-striped">
								<thead>
								<tr>
								<th></th>
								<th>Bag No.</th>
								<th>Weight</th>
								<th>Mode</th>
								<th>NOP</th>
								</tr>
								</thead>
								<tbody id="change_status_id">
  <?php 
				  if (!empty ($pod))
				  {
					foreach ($pod as  $value) 
					{	 
						  ?>
                <tr>
						  <td><input type="checkbox" checked class="cb" name="bag_no[]"  data-tp="<?php echo $value->total_pcs;?>" data-tw="<?php echo $value->total_weight;?>" value="<?php echo $value->bag_no."|".$value->total_weight."|".$value->total_pcs;?>"></td>
						  <td><?php echo $value->bag_no;?><input type='hidden' readonly name='branch_name' id='branch_name'  class='form-control' value="<?php echo $value->destination_branch; ?>"></td>
						  <td><?php echo $value->total_weight;?></td>
						  <td><?php echo $value->forwarder_mode;?></td>
						  <td><?php echo $value->total_pcs;?></td>
				</tr>
							<?php 
					
						
				    }
				}
				else
				{
					echo "<p>No Data Found</p>";
				}
						?>
								</tbody>

								</table> 
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
        </div>
        </div>
        </div>
    </main>
    <!-- END: Content-->
    <!-- START: Footer-->
	<?php $this->load->view('franchise/franchise_shared/franchise_footer');
     //include('admin_shared/admin_footer.php'); ?>
    <!-- START: Footer-->
</body>
<!-- END: Body-->


        <script type="text/javascript">
          $(document).ready(function()
		  {
			  
			  $("#btn_search").click(function()
				{
					 //var awb_no=$(this).val();
					 var forwording_no=$("#search_data").val();
					 var forwarderName  = $("#forwarderName").val();
			var forwarder_mode  = $("#forwarder_mode").val();
				if(forwording_no!=""){	 
					 $.ajax({
					 	url: "Admin_domestic_menifiest/bagdata",
					    type: 'POST',
					    dataType: "html",
					    data: {forwording_no: forwording_no,forwarderName:forwarderName,forwarder_mode:forwarder_mode},
					    success: function(data) {
							console.log(data);							
									if(data !=""){
									  $("#change_status_id").append(data);  
									  var array = []; 
									
										tw=0;
										tp =0;
							
									$("input.cb[type=checkbox]:checked").each(function() { 
										
										tw = tw + parseFloat($(this).attr("data-tw"));
										tp = tp + parseFloat($(this).attr("data-tp"));
									
											 }); 
									 
										document.getElementById('total_weight').value = tw.toFixed(2);
										document.getElementById('total_pcs').value = tp;
									}
									else{
									  $("#change_status_id").append('');  
									}
						}

					 });
				}else{
				    alert("Please enter Forwording no");
				}
			});
			
        $("#podbox").change(function(){
        
        var podno=$(this).val();
        if (podno!=null || podno!='') {
            
            $.ajax({
              type:'POST',
              url:'<?php echo base_url()?>menifiest/getPODDetails',
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
    
    
    var tw ;
    var tp ;
    
	$(document).on("click", ".cb", function () {
     
         
            var array = []; 
            
			tw=0;
			tp =0;
    
            $("input.cb[type=checkbox]:checked").each(function() { 
                
                tw = tw + parseFloat($(this).attr("data-tw"));
                tp = tp + parseFloat($(this).attr("data-tp"));
		
            
                     }); 
             
                document.getElementById('total_weight').value = tw;
                document.getElementById('total_pcs').value = tp;

        });
        
        
    
     $('#example1').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : true
      
    });
  });
   $(document).keypress(
  function(event){
    if (event.which == '13') {
      event.preventDefault();
    }
});

</script>