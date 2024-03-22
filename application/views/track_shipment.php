<?php include 'shared/web_header.php'; ?>
     
<body class="home  header-v4 hide-topbar-mobile">
    <div id="page">

        <!-- Preloader-->
       

        <?php include 'shared/web_menu.php'; ?>
        <!-- masthead end -->

       
      
<div class="page-title">
        <div class="container">
            <div class="padding-tb-120px">
                <h1>TRACK SHIPMENT</h1>
                <ol class="breadcrumb">
                    <li><a href="#">Home</a></li>
                    <li class="active">TRACK SHIPMENT</li>
                </ol>
            </div>
        </div>
    </div>


        <!--contact pagesec-->
        <section class="contactpagesec secpadd">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="fh-section-title clearfix f25 text-left version-dark paddbtm40">
                            <h2>Track Shipment</h2>
                        </div>
                        <p class="margbtm30">If you have any questions about what we offer for consumers or for business, you can always email us or call us via the below details. We’ll reply within 24 hours.</p>
                        <div class="row">
                             <div class="col-md-11">
                        <div class="search-bx col-md-6">
                            
                            
                            <form role="search" method="get" action="<?php echo base_url();?>users/track_shipment">
                                <div class="input-group">
                                        <input name="pod_no" type="text" class="form-control" placeholder="Airway no" value="<?php if(isset($_GET['pod_no'])){echo $_GET['pod_no'];}?>">
                                        <span class="input-group-btn">
                                            <button type="submit" name="submit" class="btn btn-primary">Search</button>
                                        </span> 
                                </div>
                                </form>
                                    
                

           </div>
           <br>
           <?php  
            //print_r($pod);die;
                        if (!empty ($pod))
                        {
                            ?>
                            
                            <table id="example1" class="table table-bordered " style="padding:0px;">
                             <tr><th style="padding:0px;">AWB NO.</th><td style="padding:0px;"><?=$info->pod_no?></td></tr>  
                             <tr><th style="padding:0px;">Consigner Name</th><td style="padding:0px;"><?php echo $info->sender_name; ?></td></tr>  
                              <tr><th style="padding:0px;">Consignee Name</th><td style="padding:0px;"><?php echo $info->reciever_name; ?></td></tr> 
                             <tr><th style="padding:0px;">Origin</th><td style="padding:0px;"><?php echo $info->sender_city_name; ?></td></tr> 
                             <tr><th style="padding:0px;">Destination</th><td style="padding:0px;"><?php echo $info->reciever_country_name; ?></td></tr> 
                             <tr><th style="padding:0px;">Booking Date</th><td style="padding:0px;"><?php echo date('d/m/Y', strtotime($info->booking_date)); ?></td></tr>  
                             <tr><th style="padding:0px;">Status</th><td style="padding:0px;">
                                 <?php 
								    if($info->is_delhivery_complete == "1")
								    {
								        echo 'Delivered';
								    }
								    else
								    {
								        echo 'Intransit';
								    }
							 ?>
                             </td></tr> 
                             <tr><th style="padding:0px;">Delivery Date & Time</th><td style="padding:0px;"> 
                             <?php 
                                if(isset($delivery_date))
        					   	{
        							echo date('d/m/Y',strtotime($delivery_date));
        						} ?>
        						</td></tr> 
                            <!-- <tr><th style="padding:0px;">Forwording No</th><td style="padding:0px;"><?php
                             $courier_array =array('1','2','3','23');
                             if(in_array($forwording_track->c_id,$courier_array))
                             {
                                 $tracking_url =$forwording_track->tracking_url."".$info->forwording_no;
                             }else if($forwording_track->c_id=='26' || $forwording_track->c_id=='32')
                             {
                                 $tracking_url =$forwording_track->tracking_url."".$info->pod_no."&submit=1";
                             }else{
                                 $tracking_url =$forwording_track->tracking_url;
                             }
                             ?>
                             <a href="<?php echo $tracking_url; ?>"  target="_blank"><?php echo $info->forwording_no; ?></a>
                             </td></tr>    -->
                              
					</tr>
				</table>
				<br>
				<div class="poddata">
					<div>
					<?php
						/* print_r($delivery_pod);
						print_r($podimg); */
						
						if(isset($delivery_pod[0]))
						{
					?>
					<center>
					<a href="javascript:void(0);" target="_blank" style="font-size: 20px;"><img id="myImg" src="<?php echo $delivery_pod[0]; ?>" alt="Snow" style="width:100%;max-width:300px"></a>
					

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- The Close Button -->
  <span class="close">&times;</span>

  <!-- Modal Content (The Image) -->
  <img class="modal-content" id="img01">

  <!-- Modal Caption (Image Text) -->
  <div id="caption"></div>
</div>
					</center>
					<?php	
						}
					?>
					</div>
				</div>	

				<table><tr><td>
<center><b>Shipment Progress History</b></center></td></tr>
</table>
				
                    <table class="table  table-bordered">
                        <thead>
                          <tr>
                             <th> Date</th>
                             <th>Time</th>
				            <th>Location</th>
                   <th>Status Description </th>
                            
                        </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <?php 
                      if(empty($delhivery_data)) {
						  
						  
                      foreach ($pod as  $value) 
                      {
                        if (isset($value->city_name)) {
                          $value->branch_name = $value->city_name;
                        }
                      ?>
                        <td><?php echo date('d/m/Y', strtotime($value->tracking_date)); ?></td>
                        <td><?php echo date('H:i:s', strtotime($value->tracking_date)); ?></td>
                        <td>
                            <?php
                        
                        if ($value->status=='shifted')
                        {
                            echo  $value->added_branch;
                            // echo " ".str_replace("B4 EXPRESS-","",$value->branch_name);
                        }else if($value->forworder_name=='DHL'){ 
                            echo $value->status; 
                        }else{
                            echo str_replace("B4 EXPRESS-","",$value->branch_name);
                        } ?></td>  
                        <td>
                        
                         <?php
						
						if ($value->status=='shifted')
						{
							echo "In transit To ".$value->comment;
							 //echo "<br>";
								echo " ".str_replace("B4 EXPRESS-","",$value->branch_name);
						}
						elseif ($value->status=='forworded')
						{
							echo "In transit To ".$value->comment;
							 //echo "<br>";
								 echo " ".str_replace("B4 EXPRESS-","",$value->branch_name);
						}
						elseif ($value->status=='recieved')
						{
							echo "Recieved In";
							// echo "<br>";
							echo " ".str_replace("B4 EXPRESS-","",$value->branch_name);
						}
						elseif ($value->status=='booked')
						{
								echo "Booking At ".$value->comment;
								// echo "<br>";
								echo " ".str_replace("B4 EXPRESS-","",$value->branch_name);
						}
						elseif ($value->status=='Delivered' || $value->status=='DELIVERED')
						{
							echo "Delivered ".$value->comment;
							 //echo "<br>";
							 if(!empty($podimg))
							 { ?>
							
								<a href="javascript:void(0);" style="font-size: 20px;"><img id="myImg" src="<?php echo base_url(); ?>assets/pod/<?php echo $podimg->image; ?>" alt="Snow" style="width:100%;max-width:100px"></a>
					

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- The Close Button -->
  <span class="close">&times;</span>

  <!-- Modal Content (The Image) -->
  <img class="modal-content" id="img01">

  <!-- Modal Caption (Image Text) -->
  <div id="caption"></div>
</div>

							 <?php  }
						}
						else
						{
						    
						     if($value->forworder_name=='Aramex'){ 
                                //echo '<b>'.ucfirst($value->status).' </b> &nbsp;:&nbsp; '.$value->comment;
                                echo ucfirst($value->status);
						     }
						     elseif($value->forworder_name=='DHL'){ 
                                //echo '<b>'.ucfirst($value->status).' </b> &nbsp;:&nbsp; '.$value->comment;
                                echo ucfirst($value->comment);
						     }else
						     {
                                echo $value->status;
						     }
                            
						}
				  ?>
                        </td>
                        
                          
                      
                    </tr>
                    <?php
                }
                } else { 
                    foreach($delhivery_data as $delhivery) {
                     $trackingData = json_decode($delhivery->details);
                    ?>
                       <tr>
                        <td><?=$trackingData->timestamp?></td>
                        <td><?= 'Status:-'. $trackingData->status.',' ?> <?= 'Location:-'. $trackingData->location.',' ?> <?= 'Remark:-'. $trackingData->scan_remark;?></td>
                        </tr>
                    <?php }
                    
                }
                ?>
                
                    </tbody>
                </table>
                  <?php
          }
              ?>
              
              <style>
                  #example1 th,td{
                      padding:5px !important;
                  }
              </style> <br><br>
              <?php if(!empty($ftl)){ //print_r($ftl); ?> 
               <table id="example1" class="table table-bordered " style="padding:0px;">
                             <tr><th style="padding:0px;">Lr NO.</th><td style="padding:0px;"><?=$ftl['lr_number'];?></td></tr>  
                             <tr><th style="padding:0px;">Order Number</th><td style="padding:0px;"><?php echo $ftl['order_number']; ?></td></tr>  
                              <tr><th style="padding:0px;">Lorry Number</th><td style="padding:0px;"><?php echo $ftl['lorry_number']; ?></td></tr> 
                                
                             <tr><th style="padding:0px;">Consigner Name</th><td style="padding:0px;"><?php echo $ftl['sender_name']; ?></td></tr> 
                             <tr><th style="padding:0px;">Origin</th><td style="padding:0px;"><?php $whr_c = array("id"=>$ftl['sender_city']);
                                    $city_details = $this->basic_operation_m->get_table_row("city",$whr_c);
                                  echo  $senderCity = $city_details->city; ?></td></tr> 
                             <tr><th style="padding:0px;">Consignee Name</th><td style="padding:0px;"><?php echo $ftl['reciever_name']; ?></td></tr> 
                             <tr><th style="padding:0px;">Destination</th><td style="padding:0px;"><?php $whr_c1 = array("id"=>$ftl['reciever_city']);
                                    $city_details = $this->basic_operation_m->get_table_row("city",$whr_c1);
                                  echo  $senderCity = $city_details->city; ?></td></tr> 
                             <tr><th style="padding:0px;">Booking Date</th><td style="padding:0px;"><?php echo date('d/m/Y', strtotime($ftl['booking_date'])); ?></td></tr>  
                                
                    </table>
              
              <h2 class="text-center">Product Details</h2>
                   <table class="table  table-bordered">
                        <thead>
                          <tr>
                             <th> NO.r</th>
                             <th> Product Name</th>
                             <th>Product Weight</th>
				            <th>Product Qty</th>
				            <th>Declare Weight</th>
                            <th>Chargable Weight</th>
                            <th>Frieht Charge</th>
                            <th>Total Charge</th>
                            <th>Gst</th>
                            <th>Grand Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i=1; if($lr){ //print_r($lr); 
                        foreach($lr as $value){ ?>
                        <td><?= $i++; ?></td>
                        <td><?= $value['product_name']; ?></td>
                        <td><?= $value['product_weight']; ?></td>
                        <td><?= $value['product_qty']; ?></td>
                        <td><?= $value['declare_weight']; ?></td>
                        <td><?= $value['chargable_weight']; ?></td>
                        <td><?= $value['frieht_charge']; ?></td>
                        <td><?= $value['total_charge']; ?></td>
                        <td><?= $value['gst_charge']; ?>%</td>
                        <td><?= $value['grand_total']; ?></td>
                        <?php } } ?>
                        
                    </tbody>
                    </table>
              
              <?php } ?>
            </div>
                        </div>
                    </div>
                   
                </div>
            </div>
        </section>
        <!--contact end-->

        <!--google map end-->

<?php include 'shared/web_footer.php'; ?>