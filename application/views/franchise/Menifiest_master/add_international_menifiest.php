     <?php $this->load->view('admin/admin_shared/admin_header'); ?>
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
                              <h4 class="card-title">Add International Menifiest</h4>  
                          </div>
                          <div class="card-body">
                          	 <?php if($this->session->flashdata('notify') != '') {?>
  <div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored"><?php echo $this->session->flashdata('notify'); ?></div>
  <?php  unset($_SESSION['class']); unset($_SESSION['notify']); } ?>                             
						   <div class="row">                                           
                            <div class="col-12">
								<form role="form" action="admin/insert-international-menifiest" method="post" enctype="multipart/form-data">

								<div class="form-group row">
									<label  class="col-sm-2 col-form-label">Manifiest Id</label>
									<div class="col-sm-2">
										<input type="text" class="form-control" name="manifiest_id" id="col-sm-1 col-form-label" placeholder="Enter Id" value="<?php echo $mid; ?>" >
									</div>

									<label  class="col-sm-2 col-form-label">Manifiest Date</label>
									<div class="col-sm-2">
										<input type="date" class="form-control" name="datetime" required id="col-sm-1 col-form-label" >
									</div>
									<label  class="col-sm-2 col-form-label">Vehicle No</label>
									<div class="col-sm-2">
										<input type="text" name="lorry_no" class="form-control" />
									</div>
								</div>
								<div class="form-group row">
																	
									<label  class="col-sm-2 col-form-label">Driver Name</label>
									<div class="col-sm-2">
										<input type="text" name="driver_name" class="form-control" />
									</div>
									<label  class="col-sm-2 col-form-label">Contact No</label>
									<div class="col-sm-2">
										<input type="text" name="contact_no" class="form-control" />
									</div>
									<label  class="col-sm-2 col-form-label">Coloader</label>
									<div class="col-sm-2">
										<select name="coloader" class="form-control" id="coloader" >
											<option value="">Select Coloader </option>
											<?php foreach ($coloader_list as $value) {
												?>
												<option value="<?php echo $value['coloader_name'];?>"><?php echo $value['coloader_name'];?></option>
												<?php 
											} ?>
										</select>
									</div>	
								</div>
								<div class="form-group row">				
 <label  class="col-sm-2 col-form-label">Forworder Name</label>
									<div class="col-sm-2">
										<select name="forwarder_name" class="form-control" id="forwarderName" required>
											<option value="">Select Forworder Name</option>
											<?php foreach ($courier_company as $value) {
												?>
												<option value="<?php echo $value['c_company_name'];?>"><?php echo $value['c_company_name'];?></option>
												<?php 
											} ?>
										</select>
									</div>
									
									<input type="hidden" readonly name="forwarder_mode" id="forwarder_mode" value="Air" class="form-control"/>

									<label  class="col-sm-2 col-form-label">Total Pcs</label>
									<div class="col-sm-2">
										<input type="text" readonly name="total_pcs" id="total_pcs" required class="form-control"/>
									</div>
									<label  class="col-sm-2 col-form-label">Total Weight</label>
									<div class="col-sm-2">
										<input type="text" readonly name="total_weight" id="total_weight" required class="form-control"/>
									</div>
								<!-- 	
									<label  class="col-sm-2 col-form-label">Pod csv</label>
									<div class="col-sm-2">
										<input type="file" class="form-control" id="jq-validation-email" name="csv_zip" accept=".csv" placeholder="Slider Image">
									</div> -->
								</div>
								
								<div class="col-md-3">
								<div class="box-footer pull right">
								<button type="submit" name="submit"  class="btn btn-primary">Submit</button>
								</div>

								</div>
								<div class="col-md-12">
								<input type="text" id="search_data" placeholder="Enter DOC No" style="float: right;" >
								<input type="button" id="btn_search" style="float: right;"  value="Search">
								<br>
								<!--  col-sm-4--> 
									<table class="table table-bordered table-striped">
										<thead>
										<tr>
											<th></th>
											<th>AWB No.</th>
											<!-- <th>Sender Name </th> -->
											<th>Consignee</th>
											<th>Forwarding No</th>
											<th>Destination</th>
											<th>Forwader</th>											
											<th>Pcs</th>
											<th>Weight</th>
											<th>Dimension</th>
										</tr>
										</thead>
										<tbody id="change_status_id">
										</tbody>

									</table> 
								<!--  box body-->
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
    <?php $this->load->view('admin/admin_shared/admin_footer');
     //include('admin_shared/admin_footer.php'); ?>
    <!-- START: Footer-->
</body>
<!-- END: Body-->
<script type="text/javascript" src="<?php echo base_url();?>assets/jQueryScannerDetectionmaster/jquery.scannerdetection.js"></script>
<script type="text/javascript">
$(document).scannerDetection({
	timeBeforeScanTest: 200, // wait for the next character for upto 200ms
	startChar: [120], // Prefix character for the cabled scanner (OPL6845R)
	endChar: [13], // be sure the scan is complete if key 13 (enter) is detected
	avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
	onComplete: function(barcode, qty){ 
		var awb_no= barcode;
		var forwarderName  = $("#forwarderName").val();
		var forwarder_mode  ='Air';
			
				$.ajax({
					   url: "<?php  echo base_url().'Admin_international_menifiest/awbnodata'; ?>",
					   type: 'POST',
					   dataType: "html",
					   data: {awb_no: awb_no,forwarderName:forwarderName,forwarder_mode:forwarder_mode},
					   error: function() {
						  alert('Please Try Again Later');
					   },
					   success: function(data) {
						console.log(data);
						
						if(data !=""){
						  $("#change_status_id").prepend(data);  
						  var array = []; 
						
							tw=0;
							tp =0;
				
						$("input.cb[type=checkbox]:checked").each(function() { 
							
							tw = tw + parseFloat($(this).attr("data-tw"));
							tp = tp + parseFloat($(this).attr("data-tp"));
						
								 }); 
						 
							document.getElementById('total_weight').value = tw;
							document.getElementById('total_pcs').value = tp;
						  $("#search_data").val('');  
						}
						else{
						  $("#change_status_id").prepend('');  
						}
							
							//alert("Record added successfully");  
					   },
					   error:function(response)
						{
							console.log(response);
						}
					});
		} // main callback function	
});
</script>

        <script type="text/javascript">
          $(document).ready(function()
		  {
			  
			  $("#btn_search").click(function()
				{
					
					 var awb_no=$("#search_data").val();
					 var forwarderName  = $("#forwarderName").val();
					var forwarder_mode  = $("#forwarder_mode").val();



				if(awb_no!=""){	 
					// alert(awb_no);

					awb_no = awb_no.trim(); 

						var message = '';

						$("input[name='pod_no[]']").map(function(){
							var numbers = $(this).val();

							var number = numbers.split("|");

							if (number[0]==awb_no) {
								message ='This Forwording No Already Exist In The List!';
								// return false;
							}
						}).get();

						if (message!='') {
							alert(message);
							return false;
						}
					
					 $.ajax({
					 	url: "Admin_international_menifiest/awbnodata",
					    type: 'POST',
					    dataType: "html",
					    data: {awb_no: awb_no,forwarderName:forwarderName,forwarder_mode:forwarder_mode},
					    success: function(data) {
							console.log(data);							
									if(data !=""){
									  $("#change_status_id").prepend(data);  
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
									  $("#change_status_id").prepend('');  
									}
						}

					 });
					
				}else{
				    alert("Please enter DOC no");
				}
					
					
						
			});

        $("#podbox").change(function(){
        
        var podno=$(this).val();
        if (podno!=null || podno!='') {
            
            $.ajax({
              type:'POST',
              url:'<?php echo base_url()?>Admin_international_menifiest/getPODDetails',
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