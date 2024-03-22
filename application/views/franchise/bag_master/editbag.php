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
                              <h4 class="card-title">Update Bag</h4>  
                          </div>
                          <div class="card-body">
                                <div class="card-body">
						   <div class="row">                                           
                            <div class="col-12">
								<form role="form" action="admin/update-bag" method="post" enctype="multipart/form-data">

								<div class="form-group row">
									<label  class="col-sm-2 col-form-label">Bag Id</label>
									<div class="col-sm-2">
										<input type="text" class="form-control" name="bag_id" id="col-sm-1 col-form-label" placeholder="Enter Id" value="<?php echo $Bag_info->bag_id; ?>" >
										<input type="hidden" class="form-control" name="inc_id" value="<?php echo $Bag_info->inc_id;?>">
									</div>

									<label  class="col-sm-2 col-form-label">Manifiest Date</label>
									<div class="col-sm-2">
										<input type="date" class="form-control" name="datetime" id="col-sm-1 col-form-label"  value="<?php echo $Bag_info->date_added; ?>">
									</div>
									 <label  class="col-sm-2 col-form-label">Forworder Name</label>
									<div class="col-sm-2">
										<select name="forwarder_name" class="form-control" id="forwarderName" required>
											<option value="">Select Forworder Name</option>
											<?php foreach ($courier_company as $value) {
												?>
												<option value="<?php echo $value['c_company_name'];?>" <?php echo ($value['c_company_name'] == $Bag_info->forwarder_name)?'selected':''; ?>><?php echo $value['c_company_name'];?></option>
												<?php 
											} ?>
										</select>
									</div>
									
								    <label  class="col-sm-2 col-form-label">Mode</label>
									<div class="col-sm-2">
									<select name="forwarder_mode" class="form-control" id="forwarder_mode" required>
										<option value="">Select Forworder Mode</option>
										<option value="All" <?php if( trim('All') == trim($Bag_info->forwarder_mode) ){echo "selected";} ?>>All</option>
										<?php foreach ($mode_list as $value) {
										?>
										<option value="<?php echo $value['mode_name'];?>" <?php if( trim($value['mode_name']) == trim($Bag_info->forwarder_mode) ){echo "selected";} ?>><?php echo $value['mode_name'];?></option>
										<?php 
									} ?>
									</select>
									</div>
								</div>
								
								<div class="form-group row">
								
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
								<th>AWB No.</th>
								<th>Shipper Name </th> 
								<th>Consignee</th>
								<th>Mode</th>
								<th>Booking From</th>
								<th>Destination</th>
								<th>To Pay</th>
								<th>Qty/Pcs</th>
								<th>Actual Wt</th>
								<th>Charged Wt</th>
								</tr>
								</thead>
								<tbody id="change_status_id">
  <?php 
				  if (!empty ($pod))
				  {
					foreach ($pod as  $value) 
					{
						$resAct			=	$this->db->query("select city from city where id='$value->reciever_city'");
						$resActt			=	$this->db->query("select city from city where id='$value->sender_city'");
						$city_reciever	= 	$resAct->row()->city;
						$city_sender	= 	$resActt->row()->city;
						
							if($value->dispatch_details == 'ToPay')
			{
				$topay = "<td>".$value->grand_total;
			}
			else
			{
				$topay = "0";
			}
							 
							 $query_result= $this->db->query("select * from tbl_domestic_weight_details where booking_id = '$value->booking_id'")->row();
						  ?>
                <tr>
						  <td><input type="checkbox" checked class="cb" name="pod_no[]"  data-tp="<?php echo $value->total_pcs;?>" data-tw="<?php echo $value->total_weight;?>" value="<?php echo $value->pod_no."|".$value->total_weight."|".$value->total_pcs;?>"></td>
						  <td><?php echo $value->pod_no;?><input type='hidden' readonly name='branch_name' id='branch_name'  class='form-control' value="<?php echo $value->destination_branch; ?>"></td>
						  <td><?php echo $value->sender_name;?></td>
						  <td><?php echo $value->reciever_name;?></td>
						  <td><?php echo $value->forworder_name;?></td>
						  <input type='hidden' readonly name='forwarder_name' id='forwarder_name'  class='form-control' value='<?php echo $value->forworder_name; ?>'/>
						  <td><?php echo $city_reciever;?></td>
						  <td><?php echo $city_sender;?></td>
						  <td><?php echo $topay;?></td>
						  <td><?php echo $query_result->no_of_pack;?></td>
						  <td><?php echo $query_result->actual_weight;?></td>
						  <td><?php echo $query_result->chargable_weight;?></td>
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
					 	url: "Admin_domestic_bag/awbnodata",
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
              url:'<?php echo base_url()?>Bag/getPODDetails',
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