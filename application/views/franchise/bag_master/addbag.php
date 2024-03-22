   <?php $this->load->view('franchise/franchise_shared/franchise_header'); ?>
    <!-- END Head-->
<style>
  	.input:focus {
    outline: outline: aliceblue !important;
    border:2px solid red !important;
    box-shadow: 2px #719ECE;
  }
  </style>
    <!-- START: Body-->
    <body id="main-container" class="default">

        
        <!-- END: Main Menu-->
   <?php $this->load->view('franchise/franchise_shared/franchise_sidebar'); ?>
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
                              <h4 class="card-title">Add Bag</h4>  
                          </div>
                          <div class="card-body">
                          	 <?php if($this->session->flashdata('notify') != '') {?>
  <div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored"><?php echo $this->session->flashdata('notify'); ?></div>
  <?php  unset($_SESSION['class']); unset($_SESSION['notify']); } ?>                             
						   <div class="row">                                           
                            <div class="col-12">
								<form role="form" action="<?= base_url('franchise/insert-bag'); ?>" method="post" enctype="multipart/form-data">

								<div class="form-group row" >
									
									<label  class="col-sm-2 col-form-label">Bag Date</label>
									<div class="col-sm-2">
									<?php 
									$datec = date('Y-m-d H:i');

											// $tracking_data[0]['tracking_date'] = date('Y-m-d H:i',strtotime($tracking_data[0]['tracking_date']));
                                      		$datec  = str_replace(" ", "T", $datec); ?>
										<input type="datetime-local" required class="form-control" name="datetime" value="<?php echo $datec;?>" id="col-sm-1 col-form-label" >
									</div>
									 <label  class="col-sm-2 col-form-label">Forworder Name</label>
									<div class="col-sm-2">
										<select name="forwarder_name" class="form-control" id="forwarderName" required>
											<option value="">Select Forworder Name</option>
											<option value="SELF" selected>SELF</option>
										</select>
									</div>
									
									<label  class="col-sm-2 col-form-label">Mode</label>
									<div class="col-sm-2">
									<select name="forwarder_mode" class="form-control" id="forwarder_mode" required>
										<option value="">Select Forworder Mode</option>
										<option value="All">All</option>
										<?php foreach ($mode_list as $value) {
										?>
										<option value="<?php echo $value['mode_name'];?>"><?php echo $value['mode_name'];?></option>
										<?php 
									} ?>
									</select>
									</div>
								
								</div>
								
								<div class="form-group row">
								
									<label  class="col-sm-2 col-form-label">Bag By</label>
									<div class="col-sm-2">										
										<input type="text" readonly name="username" required value="<?= $_SESSION['customer_name'];?>" class="form-control"/>
									</div>
									<label  class="col-sm-2 col-form-label">Total Pcs</label>
									<div class="col-sm-2">										
										<input type="text" readonly name="total_pcs" required id="total_pcs" class="form-control"/>
									</div>
									<label  class="col-sm-2 col-form-label">Total Weight</label>
									<div class="col-sm-2">
										<input type="text" readonly name="total_weight" required id="total_weight" class="form-control"/>
									</div>
									<label  class="col-sm-2 col-form-label">Remark</label>
									<div class="col-sm-2">
										<textarea class="form-control" name="note"> </textarea>
									</div>
								
								</div>
								
								<div class="col-md-3">
								<div class="box-footer pull right">
								<button type="submit" name="submit"  class="btn btn-primary">Submit</button>
								</div>

								</div>
								<div class="col-md-12" id="search" style="display: none;">
								<input type="text" id="search_data" placeholder="Enter AWB No" style="float: right;" >
								<input type="button" id="btn_search" style="float: right;"  value="Search">
								</div>
								<br>
								<div class="col-md-12">
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
    <!-- END: Content--> <?php ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');
error_reporting(E_ALL); ?>
    <!-- START: Footer-->
   <?php $this->load->view('franchise/franchise_shared/franchise_footer');
     //include('admin_shared/admin_footer.php'); ?>
    <!-- START: Footer-->
</body>
<script type="text/javascript" src="<?php echo base_url();?>assets/jQueryScannerDetectionmaster/jquery.scannerdetection.js"></script>
<script type="text/javascript">
$(document).scannerDetection({
	timeBeforeScanTest: 200, // wait for the next character for upto 200ms
	startChar: [120], // Prefix character for the cabled scanner (OPL6845R)
	endChar: [13], // be sure the scan is complete if key 13 (enter) is detected
	avgTimeByChar: 40, // it's not a barcode if a character takes longer than 40ms
	
	onComplete: function(barcode, qty){ 
		var forwording_no= barcode;
			
			var forwarderName  = $("#forwarderName").val();
			var forwarder_mode  = $("#forwarder_mode").val();

			var message = '';

				$("input[name='pod_no[]']").map(function(){
					var numbers = $(this).val();

					var number = numbers.split("|");

					if (number[0]==forwording_no) {
						message ='This Forwording No Already Exist In The List!';
						// return false;
					}
				}).get();

				if (message!='') {
					alert(message);
					return false;
				}
				$.ajax({
					   url: "<?php  echo base_url().'Admin_franchise_bag/awbnodata'; ?>",
					   type: 'POST',
					   dataType: "html",
					   data: {forwording_no: forwording_no,forwarderName:forwarderName,forwarder_mode:forwarder_mode},
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
						 
						}
						else{
						  $("#change_status_id").prepend('');  
						}
						 $("#search_data").val('');  
							$( "#search_data" ).focus();
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
<!-- END: Body-->
        <script type="text/javascript">
          $(document).ready(function()
		  {
			  
			   $(window).keydown(function(event)
			  {
				if(event.keyCode == 13) 
				{
					 //var awb_no=$(this).val();
					var forwording_no=$("#search_data").val();
					var forwarderName  = $("#forwarderName").val();
					var forwarder_mode  = $("#forwarder_mode").val();
			
					if(forwording_no!=""){	 


						var message = '';

				$("input[name='pod_no[]']").map(function(){
					var numbers = $(this).val();

					var number = numbers.split("|");

					if (number[0]==forwording_no) {
						message ='This Forwording No Already Exist In The List!';
						// return false;
					}
				}).get();

				if (message!='') {
					alert(message);
					return false;
				}
					 $.ajax({
					 	url: "<?php  echo base_url().'Admin_franchise_bag/awbnodata'; ?>",
					    type: 'POST',
					    dataType: "html",
					    data: {forwording_no: forwording_no,forwarderName:forwarderName,forwarder_mode:forwarder_mode},
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
						$("#search_data").val('');  
						}

					 });
					 
				}else{
				    alert("Please enter Forwording no");
				}
				
				}
			  });
			  
			  
			   $("#btn_search").click(function()
				{
					 //var awb_no=$(this).val();
					var forwording_no=$("#search_data").val();
					var forwarderName  = $("#forwarderName").val();
					var forwarder_mode  = $("#forwarder_mode").val();

					

					// console.log(all);
			
					if(forwording_no!=""){	

						forwording_no = forwording_no.trim(); 

						var message = '';

						$("input[name='pod_no[]']").map(function(){
							var numbers = $(this).val();

							var number = numbers.split("|");

							if (number[0]==forwording_no) {
								message ='This Forwording No Already Exist In The List!';
								// return false;
							}
						}).get();

						if (message!='') {
							alert(message);
							return false;
						}
					 $.ajax({
					 	url: "<?php  echo base_url().'Admin_franchise_bag/awbnodata'; ?>",
					    type: 'POST',
					    dataType: "html",
					    data: {forwording_no: forwording_no,forwarderName:forwarderName,forwarder_mode:forwarder_mode},
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
										 $("#search_data").val('');  
									}
									else{
									  $("#change_status_id").prepend('');  
									}
									$( "#search_data" ).focus();
									
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

$("#forwarder_mode").change(function(){

$('#search').show();
});

</script>

<?php 

function dateTimeValue($timeStamp)
{
    $date = date('d-m-Y',$timeStamp);
    $time = date('H:i:s',$timeStamp);
    return $date.'T'.$time;
}

?>