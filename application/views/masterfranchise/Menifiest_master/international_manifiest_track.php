     <?php $this->load->view('admin/admin_shared/admin_header'); ?>
    <!-- END Head-->

    <!-- START: Body-->
    <body id="main-container" class="default">
<!-- <style>
  .card .card-body {
  font-weight: bold;
  font-size: 20px;  
}
</style> -->
       
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
										<?php if(!empty($manifiest)){ ?>
										<a href="admin/download-international-tracking-menifiest/<?php echo $manifiest[0]['manifiest_id']; ?>"  class="btn btn-info">Download </a>	
										<?php } ?>
																				
							  </form>  
							</span>
        </div>
						  <div id="printableArea" >
                          <div class="card-body" style="font-size: 14px;"> 
                            <div class="row" >    
                                <div class="col-12"> 
                                      <center><strong style="font-size: 20px;" >Security Declaration</strong></center>
                                 </div>
                            </div>
                            <div class="row">                                           
                              <div class="col-12">
                                 <div class="card border-0 border-top1px table-responsive" style="margin-bottom:0px;">
                                            <div class="card-body table-responsive" style="border:1px solid;">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td >
                                                              <strong style="font-size: 35px;"> <?php echo $company_details->company_name;?> </strong><br>
                                                                    <p style="width: 50%;" class="fontm"><?php echo $company_details->address;?></p> 
                                                                <p style="font-size: 19px;color: black;">  
                                                                <b>Telephone:</b><?php echo $company_details->phone_no;?><br>
                                                                <!-- <b>E-Mail:</b><?php //echo $company_details->email;?><br>                       
                                                                <b>Web Site:</b> www.omcourier.net<br> -->
                                                               </p>
                                                              </td>
                                                            <td><img class="logo-css" src="<?php echo base_url();?>./assets/company/<?php echo $company_details->logo; ?>" alt="" style="height: 160px;width: 270px;margin-right: 91px;">
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                              </div>
                            </div>
                           <div class="row">                                           
                              <div class="col-12"> 
                              <div class="table-responsive">
                                  <table style="width:100%;border: 1px solid;">
                                       <thead>
                                          <tr>  
                                               <th>DATE &nbsp;:&nbsp;<?php if(!empty($manifiest)){ echo date("d-m-Y",strtotime($manifiest[0]['date_added']));} ?><br>                       
                                                                MANIFEST NO &nbsp;:&nbsp; <?php if(!empty($manifiest)){ echo $manifiest[0]['manifiest_id'];} ?><br></th>                                               
                                                <th>COLOADER &nbsp;:&nbsp; <?php if(!empty($manifiest)){echo $manifiest[0]['coloader'];} ?></th>
                                                <th>TOTAL NO OF SHIPMENT &nbsp;:&nbsp;<?php if(!empty($manifiest)){echo $total_pcs;} ?><br>                       
                                                                TOTAL WEIGHT &nbsp;:&nbsp;<?php if(!empty($manifiest)){echo $total_weightt;} ?><br></th>
                                                                                               
                                          </tr>
                                      </thead>
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
                                               <th style="border:1px solid;text-align: center!important;">SR.NO.</th>                                               
                                                <th style="border:1px solid;text-align: center!important;">AWB NO</th>
                                                <th style="border:1px solid;text-align: center!important;">FOWARDING NO</th>
                                                <th style="border:1px solid;text-align: center!important;">DESTINATION</th>
                                                <th style="border:1px solid;text-align: center!important;">NETWORK</th>
                                                <th style="border:1px solid;text-align: center!important;">CONSIGNEE</th>
                                                <th style="border:1px solid;">NOP</th> 
                                                <th style="border:1px solid;text-align: center!important;">WEIGHT</th>
                                                <th style="border:1px solid;text-align: center!important;">PROD</th>
                                                <th style="border:1px solid;text-align: center!important;">DIM</th>                                                
												  <th style="border:1px solid;text-align: center!important;">DATE</th>     
                                                <th style="border:1px solid;text-align: center!important;">PINCODE</th>     
                                                <th style="border:1px solid;text-align: center!important;">MODE</th>     
                                                <th style="border:1px solid;text-align: center!important;">DESC.</th>     
                                                <th style="border:1px solid;text-align: center!important;">Inv. Value.</th>     	
                                          </tr>
                                      </thead>
                                      <tbody>  
                                          <?php
										                          if(!empty($manifiest)){ 
                                              $j=1;
                                                foreach ($manifiest as  $value) { ?> 
                                                  <tr>           
                                                    <td align="center" style="border:1px solid;"><?php echo $j; $j++; ?></td>
                                                    <td align="center" style="border:1px solid;"><?php echo $value['pod_no'];?></td>
                                                    <td align="center" style="border:1px solid;"><?php echo $value['forwording_no'];?></td>
                                                    <td align="center" style="border:1px solid;"><?php 
                                                    $whr =array("z_id"=>$value['reciever_country_id']);
                                                    $country_details =$this->basic_operation_m->get_table_row("zone_master",$whr);
                                                    echo $country_details->country_name;               

  
																										
                                                    ?></td>
                                                    <td align="center" style="border:1px solid;"><?php echo $value['forworder_name'];?></td>
                                                    <td align="center" style="border:1px solid;"><?php echo $value['reciever_name'];?></td>
                                                    <td style="border:1px solid;"><?php echo $value['total_pcs'];?></td>
                                                    <td align="center" style="border:1px solid;"><?php echo $value['total_weight'];?></td>
                                                    <td align="center" style="border:1px solid;"><?php if($value['doc_type']=='0'){echo "D";}else{echo "ND";};?></td>
                                                    <td align="center" style="border:1px solid;"><?php echo $value['dimension'];?></td>   
													<td align="center" style="border:1px solid;"><?php echo $value['booking_date'];?></td>          
                                                    <td align="center" style="border:1px solid;"><?php echo $value['reciever_pincode'];?></td>          
                                                    <td align="center" style="border:1px solid;"><?php echo $value['mode_dispatch'];?></td>          
                                                    <td align="center" style="border:1px solid;"><?php echo $value['special_instruction'];?></td>          
                                                    <td align="center" style="border:1px solid;"><?php echo $value['invoice_value'];?></td>          													
                                                    </tr>
                                              <?php                                                 
                                                }
          										  }

                             ?>
                             <tr class="border-bottom">
                                 <td colspan="15" style="border:1px solid;" >
                                    <b>NOTE:-</b><br>
(1)whose reliability we do not doubt.<br>
(2)These shipments have been protected from the time it  connected and accepted by at our acceptance location.<br>
(3)we further declae that these goods do not contain hazardous commodity as per current edtion of the IATA Manual.<br>
(4)All these shipments are subject to 100% phisical check at the time of acceptance.<br><br><br><br><br><br>


FOR OM COURIER SERVICE.<br>

                                </td>                             
                              </tr>
          										</tbody>
          										</table>
                          </div>
                        </div>
                      </div>

							</div><!-- end card body==================-->
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

