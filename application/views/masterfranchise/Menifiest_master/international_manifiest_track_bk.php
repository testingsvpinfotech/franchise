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
            <div class="container-fluid site-width" >
                <!-- START: Listing-->
                <div class="row">                 
                  <div class="col-12  align-self-center">
                      <div class="col-12 col-sm-12 mt-3">
						<div class="card">
                          <div class="card-header justify-content-between align-items-center">
                              <h4 class="card-title">International Menifiest Tracking</h4>  
							<span style="float: right;">
							 <form method='post' action="admin/tracking-international-menifiest/post">
										<input type="text" name="manifiest_id" placeholder="Menifiest Number" style="height: 31px;"/>
										 <button type="submit" name="submit" class="btn btn-primary">Track </button>
										<input type="button" onclick="printDiv('printableArea')" class="btn btn-info" value="print" />
										<?php if(!empty($manifiest)){
                      
                     ?>
										<a href="admin/download-international-tracking-menifiest/<?php echo $manifiest[0]['manifiest_id']; ?>"  class="btn btn-info">Download </a>	
										<?php } ?>
																				
							  </form>  
							</span>
                          </div>
						  <div id="printableArea">
                          <div class="card-body">
                            <div class="row">                                           
                              <div class="col-12">
                                 <div>
                                    <p><b><center>OM COURIER SERVICES</center></b></p>
                                    <p><center>ADDRESS : <?php echo $sender_address; ?></center></p>
                                 </div>
                              </div>
                            </div>
                            <div class="row">                                           
                              <div class="col-12"> 
                              <div class="table-responsive">
                                  <table style="width:100%;border:1px solid;">
                                   
                                      <tbody>  
                                        <tr>
                                        <td style="border:1px solid;padding: 5px;">MANIFEST NO</td>
                                          <td style="border:1px solid;"><?php if(!empty($manifiest)){ 
                                                       $file = Zend_Barcode::draw('code128', 'image', array('text' =>$manifiest[0]['manifiest_id']), array());
                                                       imagepng($file,FCPATH."assets/barcode/label/".$manifiest[0]['manifiest_id'].".png"); ?>
                                                      <img src="<?php echo base_url(); ?>assets/barcode/label/<?php echo  $manifiest[0]['manifiest_id']; ?>.png" height="50%">

                                              <?php

                                        } ?></td>
                                           <td style="border:1px solid;padding: 5px;">FLIGHT /Lorry NO</td>
                                          <td style="border:1px solid;"><?php if(!empty($manifiest)){ echo $manifiest[0]['lorry_no'];} ?></td>
                                      </tr>
                                      <tr>
                                          <td style="border:1px solid;padding: 5px;">MANIFEST DATE</td>
                                          <td style="border:1px solid;"><?php if(!empty($manifiest)){ echo date("d-m-Y",strtotime($manifiest[0]['date_added']));} ?></td>
                                           <td style="border:1px solid;padding: 5px;">CO LOADER</td>
                                          <td style="border:1px solid;"><?php if(!empty($manifiest)){ echo $manifiest[0]['coloader'];} ?></td>
                                      </tr>
                                      <tr>
                                          <td style="border:1px solid;padding: 5px;">ORIGIN</td>
                                          <td style="border:1px solid;"><?php if(!empty($manifiest)){ echo $manifiest[0]['source_branch'];} ?></td>
                                           <td style="border:1px solid;padding: 5px;">MODE</td>
                                          <td style="border:1px solid;"><?php if(!empty($manifiest)){ echo $manifiest[0]['forwarder_mode'];} ?></td>
                                      </tr>
                                      <tr>
                                          <td style="border:1px solid;padding: 5px;">DESTINATION HUB</td>
                                          <td style="border:1px solid;"><?php if(!empty($manifiest)){ echo $manifiest[0]['destination_branch'];} ?></td>
                                           <td style="border:1px solid;padding: 5px;">No. Of Pcs</td>
                                          <td style="border:1px solid;"><?php if(!empty($manifiest)){echo $total_pcs;} ?></td>
                                      </tr>
                                      <tr>
                                          <td style="border:1px solid;padding: 5px;">Total Weight</td>
                                          <td style="border:1px solid;"><?php if(!empty($manifiest)){echo $total_weightt;} ?></td>
                                      </tr>

                                    </tbody>
                              </table> 
                          </div>
                        </div>
                      </div>
							<div class="row">                                           
                              <div class="col-12"> 
                              <div class="table-responsive">
                                  <table style="width:100%;border:1px solid;">
                                       <thead>
                                          <tr>  
                                               <th style="border:1px solid;">Sr. No.</th>                                               
                                                <th style="border:1px solid;">AWB No</th>
                                                <th style="border:1px solid;">Forwording No</th>
                                                <th style="border:1px solid;">Consignee Name</th>
                                                <th style="border:1px solid;">NOP</th>
                                                <th style="border:1px solid;">A.Weight</th>
                                                <th style="border:1px solid;">Dimension</th>
                                                <th style="border:1px solid;">Origin Branch</th>
                                                <th style="border:1px solid;">Destination</th>
                                                <th style="border:1px solid;">Destination zipcode</th>
                                          </tr>
                                      </thead>
                                      <tbody>  
                                          <?php
										                          if(!empty($manifiest)){ 
                                              $j=1;
                                                foreach ($manifiest as  $value) { ?> 
                                                  <tr>           
                                                    <td style="border:1px solid;"><?php echo $j; $j++; ?></td>
                                                    <td style="border:1px solid;"><?php echo $value['pod_no'];?></td>
                                                    <td style="border:1px solid;"><?php echo $value['forwording_no'];?></td>
                                                    <td style="border:1px solid;"><?php echo $value['reciever_name'];?></td>
                                                    <td style="border:1px solid;"><?php echo $value['total_pcs'];?></td>
                                                    <td style="border:1px solid;"><?php echo $value['total_weight'];?></td>
                                                    <td style="border:1px solid;"><?php echo $value['dimension'];?></td>
                                                     <td style="border:1px solid;"><?php echo $value['source_branch'];?></td>
                                                    <td style="border:1px solid;"><?php 
                                                    $whr =array("z_id"=>$value['reciever_country_id']);
                                                    $country_details =$this->basic_operation_m->get_table_row("zone_master",$whr);
                                                    echo $country_details->country_name;                                                    
                                                    ?></td>
                                                    <td style="border:1px solid;"><?php echo $value['rec_pincode'];?></td>
          
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
     <script type="text/javascript">  
       function printDiv(divName) {
     var printContents = document.getElementById(divName).innerHTML;
     var originalContents = document.body.innerHTML;

     document.body.innerHTML = printContents;

     window.print();

     document.body.innerHTML = originalContents;
}
    </script>  
    <!-- START: Footer-->
</body>
<!-- END: Body-->

