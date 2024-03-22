<?php  $this->load->view('masterfranchise/master_franchise_shared/admin_header.php');?>
<?php $this->load->view('masterfranchise/master_franchise_shared/admin_sidebar.php');?>
 
 <main>

 <style>
    .form-control:disabled, .form-control[readonly] {
    background-color: #c9cccf!important;
    opacity: 1;
}
 </style>
<div class="container-fluid site-width">
    <!-- START: Breadcrumbs-->
    <div class="row ">
        <div class="col-12  align-self-center">
            <div class="sub-header mt-3 align-self-center d-sm-flex w-100 rounded">
                <div class="w-sm-100 mr-auto">
                    <br><br>
                    <h5 class="mb-0">Edit Shipment</h5>
                </div>
            </div>
        </div>
    </div>
    <!-- END: Breadcrumbs-->

    <!-- START: Card Data-->
    <div class="row">
        <div class="col-12 col-sm-12">
            <div class="row">
                <div class="col-12 col-md-12 mt-3">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="id1">
                         
                               <form  action="<?php echo base_url('master-franchise/update-shipment/'.$booking->booking_id);?>" method="POST" id="formSubmit">
                <div class="row">
                    <div class="col-md-4 col-sm-12 mt-3">
                        <!-- Shipment Info -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Shipment Info</h4>
                                <!--<span style="float: right;"><a href="admin/view-domestic-shipment" class="btn btn-primary">View Domestic Shipment</a></span>-->
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <?php if ($this->session->flashdata('notify') != '') { ?>
                                        <div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored"><?php echo $this->session->flashdata('notify'); ?></div>
                                    <?php unset($_SESSION['class']);
                                        unset($_SESSION['notify']);
                                    } ?>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Date<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">



                                            <?php
                                            $datec = date('Y-m-d H:i');

                                            // $tracking_data[0]['tracking_date'] = date('Y-m-d H:i',strtotime($tracking_data[0]['tracking_date']));
                                            $datec  = str_replace(" ", "T", $datec);
                                            if ($this->session->userdata('booking_date') != '') { ?>

                                                <input type="datetime-local" name="booking_date"  id="booking_date" class="form-control" value="<?= date('Y-m-d\TH:i:s', strtotime($booking->booking_date . ' ' . $booking->booking_time)) ; ?>">
                                            <?php
                                            } else { ?>
                                                <input type="datetime-local" name="booking_date" id="booking_date" class="form-control" value="<?= date('Y-m-d\TH:i:s', strtotime($booking->booking_date . ' ' . $booking->booking_time)) ; ?>">
                                            <?php } ?>
                                        </div>
                                    </div>
                                   
                                    
                                    <div class="form-group row">
                                       <label class="col-sm-4 col-form-label">Airway No<span class="compulsory_fields">*</span></label>
                                       <div class="col-sm-8">
                                            <input type="text" name="awn"  class="form-control" readonly value="<?php echo $booking->pod_no ;?>">
                                            <input type="hidden" name="courier_company" id="courier_company" class="form-control" value="51">
                                       </div>
                                    </div>
                                    
                                    
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Mode<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control mode_dispatch" name="mode_dispatch" id="mode_dispatch">
                                                <option value="">-Select Mode-</option>
                                                <?php
                                                if (!empty($transfer_mode)) {
                                                    foreach ($transfer_mode as $row) {
                                                ?>
                                                        <option value='<?php echo $row->transfer_mode_id; ?>' <?php if($booking->mode_dispatch == $row->transfer_mode_id){echo 'selected';} ?>><?php echo $row->mode_name; ?></option>
                                                <?php
                                                    }
                                                }
                                                ?>

                                            </select>
                                        </div>
                                    </div>
                                    <!--<div class="form-group row">-->
                                   
                                    <!--    <label class="col-sm-4 col-form-label">EDD</label>-->
                                    <!--    <div class="col-sm-8">-->
                                    <!--        <input type="date" id="delivery_date" name="delivery_date" value="<?php echo date('d-m-Y'); ?>"  class="form-control">-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                  
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Desc.</label>
                                        <div class="col-sm-8">
                                            <textarea name="special_instruction" class="form-control my-colorpicker1"><?= $booking->special_instruction; ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Risk Type<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="risk_type" id="risk_type">
                                                 <option value="Customer" <?php if($booking->risk_type == 'Customer'){echo 'selected';} ?>>Customer</option>
                                                <option value="Carrier" <?php if($booking->risk_type == 'Carrier'){echo 'selected';} ?>>Carrier</option>
                                            </select>
                                        </div>
                                        <label class="col-sm-4 col-form-label">Bill Type<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="dispatch_details" id="dispatch_details">
                                                <option value="">-Select-</option>
                                                <option value="COD" <?php if($booking->dispatch_details == 'COD'){echo 'selected';} ?>>COD</option>
                                                <option value="PrePaid" <?php if($booking->dispatch_details == 'PrePaid'){echo 'selected';} ?>>Pre-Paid</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Product<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" name="doc_type" id="doc_typee">
                                                <option value="">-Select-</option>
                                                <option value="1" <?php if($booking->doc_type == '1'){echo 'selected';} ?>>Non-Doc</option>
                                                <option value="0" <?php if($booking->doc_type == '0'){echo 'selected';} ?>>Doc</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="div_inv_row" style="display: none;">
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">INV No.</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="invoice_no" id="invoice_no" value="<?= $booking->invoice_no ?>" class="form-control my-colorpicker1">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Inv. Value<span class="compulsory_fields">*</span></label>
                                            <div class="col-sm-8">
                                                <input type="number" name="invoice_value" id="invoice_value" value="<?= $booking->invoice_value ?>" class="form-control my-colorpicker1">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Eway No</label>
                                            <div class="col-sm-8">
                                                <input type="text" name="eway_no" value="<?= $booking->eway_no ?>" minlength="12" maxlength="12" size="12" id="eway_no" class="form-control">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label">Eway Expiry date</label>
                                            <div class="col-sm-8">
                                                <input type="datetime-local" name="eway_expiry_date" value="<?= $booking->eway_expiry_date ?>" id="eway_no" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                        <!-- Shipment Info -->
                    </div>
                    <div class="col-md-4 col-sm-12 mt-3">
                        <!-- Consigner Detail -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Consigner Detail</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Customer</label>
                                        <div class="col-sm-8" id="credit_div">
                                            <select class="form-control" name="customer_account_id" id="customer_account_id">
                                                <option value="">Select Customer</option>
                                                <?php
                                                if (count($customers)) {
                                                    foreach ($customers as $rows) {
                                                ?>
                                                        <option value="<?php echo $rows['customer_id']; ?>" <?php if($rows['customer_id'] == $booking->customer_id){ echo 'selected';} ?>>
                                                            <?php echo $rows['customer_name']; ?>--<?php echo $rows['cid']; ?>
                                                        </option>
                                                <?php
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label" id="credit_div_label">Name<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="sender_name" id="sender_name" value="<?= $booking->sender_name ?>" class="form-control my-colorpicker1">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Address</label>
                                        <div class="col-sm-8">
                                            <textarea name="sender_address" id="sender_address" class="form-control"><?= $booking->sender_address ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Pincode<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="sender_pincode" readonly id="sender_pincode" value="<?= $booking->sender_pincode ?>" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">State<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="sender_state" name="sender_state" readonly  required>
                                                <option value="">Select State</option>
                                                <?php
                                                if (count($states)) {
                                                    foreach ($states as $st) {
                                                ?>
                                                        <option value="<?php echo $st['id']; ?>" <?php if($st['id'] == $booking->sender_state){echo 'selected';} ?>>
                                                            <?php echo $st['state']; ?>
                                                        </option>
                                                <?php }
                                                }
                                                ?>
                                            </select>
                                            <input type="hidden" name="region_id" id="region_id" readonly required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">City<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="sender_city" readonly name="sender_city"  required>
                                                <option value="">Select City</option>
                                                <?php
                                                if (count($cities)) {
                                                    foreach ($cities as $rows) {
                                                ?>
                                                        <option value="<?php echo $rows['id']; ?>" <?php if($rows['id'] == $booking->sender_city){echo 'selected';} ?>>
                                                            <?php echo $rows['city']; ?>
                                                        </option>
                                                <?php }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">ContactNo.</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="sender_contactno"  id="sender_contactno" value="<?= $booking->sender_contactno ?>" class="form-control ">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">TypeOfDoc<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-4">
                                            <select name="type_of_doc" class="form-control">
                                                <option value="GSTIN" <?php if($booking->type_of_doc == 'GSTIN'){echo 'selected';} ?>>GSTIN</option>
                                                <option value="GSTIN(Govt.)" <?php if($booking->type_of_doc == 'GSTIN(Govt.)'){echo 'selected';} ?>>GSTIN(Govt.)</option>
                                                <option value="GSTIN(Diplomats)" <?php if($booking->type_of_doc == 'GSTIN(Diplomats)'){echo 'selected';} ?>>GSTIN(Diplomats)</option>
                                                <option value="PAN" <?php if($booking->type_of_doc == 'PAN'){echo 'selected';} ?>>PAN</option>
                                                <option value="TAN" <?php if($booking->type_of_doc == 'TAN'){echo 'selected';} ?>>TAN</option>
                                                <option value="Passport" <?php if($booking->type_of_doc == 'Passport'){echo 'selected';} ?>>Passport</option>
                                                <option value="Aadhaar" <?php if($booking->type_of_doc == 'Aadhaar'){echo 'selected';} ?>>Aadhaar</option>
                                                <option value="Voter Id" <?php if($booking->type_of_doc == 'Voter Id'){echo 'selected';} ?>>Voter Id</option>
                                                <option value="IEC" <?php if($booking->type_of_doc == 'IEC'){echo 'selected';} ?>>IEC</option>
                                            </select>
                                            </select>
                                        </div>
                                        <div class="col-sm-4">
                                            <input type="text" name="sender_gstno" value="<?= $booking->sender_gstno; ?>"  id="sender_gstno" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Consigner Detail -->
                    </div>
                    <div class="col-md-4 col-sm-12 mt-3">
                        <!-- Consignee Detail -->
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title">Consignee Detail</h6>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Name<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" name="reciever_name" id="reciever" value="<?= $booking->reciever_name ?>" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Company<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" value="<?= $booking->contactperson_name ?>" name="contactperson_name" id="contactperson_name" required />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Address</label>
                                        <div class="col-sm-8">
                                            <textarea name="reciever_address" id="reciever_address" class="form-control" autocomplete="off"><?= $booking->reciever_address ?></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Pincode<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <input type="number" class="form-control" readonly value="<?= $booking->reciever_pincode ?>" name="reciever_pincode" id="reciever_pincode" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">state<span class="compulsory_fields">*</span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="reciever_state"  readonly  name="reciever_state" >
                                                
                                                     <option value="">Select State</option>           
                                                     <?php
                                                if (count($states)) {
                                                    foreach ($states as $st) {
                                                ?>
                                                        <option value="<?php echo $st['id']; ?>" <?php if($st['id'] == $booking->reciever_state){echo 'selected';} ?>>
                                                            <?php echo $st['state']; ?>
                                                        </option>
                                                <?php }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">City<span class="compulsory_fields">*</span>&nbsp;&nbsp;&nbsp;&nbsp;<span id="oda"></span></label>
                                        <div class="col-sm-8">
                                            <select class="form-control" id="reciever_city" readonly  name="reciever_city" >
                                             <option value="">Select City</option>      
                                             <?php
                                                if (count($cities)) {
                                                    foreach ($cities as $rows) {
                                                ?>
                                                        <option value="<?php echo $rows['id']; ?>" <?php if($rows['id'] == $booking->reciever_city){echo 'selected';} ?>>
                                                            <?php echo $rows['city']; ?>
                                                        </option>
                                                <?php }
                                                }
                                                ?>     
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">Zone</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="receiver_zone" id="receiver_zone" readonly value="<?= $booking->receiver_zone;?>" class="form-control">
                                            <input type="hidden" name="receiver_zone_id" id="receiver_zone_id" value="<?= $booking->receiver_zone_id;?>" class="form-control">
                                            <input type="hidden" id="gst_charges" class="form-control">
                                            <input type="hidden" id="cft" class="form-control">
                                            <input type="hidden" id="air_cft" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">ContactNo.</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control" value="<?= $booking->reciever_contact;?>" name="reciever_contact" />
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label">GST NO.</label>
                                        <div class="col-sm-8">
                                            <input type="text" name="receiver_gstno" id="receiver_gstno" value="<?= $booking->receiver_gstno;?>" class="form-control">
                                        </div>
                                    </div>
                                  
                                </div>
                            </div>
                        </div>
                        <!-- Consignee Detail -->
                    </div>
                </div>
                <div class="row">




                    <div class="col-md-6 col-sm-12 mt-3">
                        <!-- Measurement Units -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Measurement Units</h4>
                                <a  id="calculator" style="color: #007bff; cursor:pointer; float:left;">Centimeter Calculator</a>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <label class="col-sm-2 col-form-label">PKT</label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="no_of_pack" class="form-control my-colorpicker1 no_of_pack" data-attr="1" id="no_of_pack1" value="<?php echo $weight->no_of_pack; ?>" required="required">
                                                </div>
                                                <label class="col-sm-2 col-form-label">Actual Weight</label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="actual_weight" class="form-control my-colorpicker1 actual_weight" data-attr="1" id="actual_weight" value="<?php echo $weight->actual_weight; ?>" required="required">
                                                </div>
                                                <label class="col-sm-2 col-form-label">Chargeable Weight</label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="chargable_weight" class="form-control my-colorpicker1 chargable_weight" data-attr="1" id="chargable_weight" value="<?php echo $weight->chargable_weight; ?>" required="required">
                                                </div>
                                                <label class="col-sm-2 col-form-label">Is Volumetric</label>
                                                <div class="col-sm-4">
                                                        <?php $length_detail =  json_decode($weight->length_detail); ?>
                                                    <input type="checkbox" id="is_volumetric" name="fav_language" <?php if($booking->doc_type=='1'){echo 'checked'; } ?><?php echo (!empty($length_detail[0])) ? 'checked' : ''; ?> >

                                                </div>
                                            </div>
                                            <div id="volumetric_table">
                                                <table class="weight-table">
                                                    <thead>
                                                        <tr><input type="hidden" class="form-control" name="length_unit" id="length_unit" class="custom-control-input" value="cm">
                                                            <th>Per Box Pack</th>
                                                            <th class="length_th">L ( Cm )</th>
                                                            <th class="breath_th">B ( Cm )</th>
                                                            <th class="height_th">H ( Cm )</th>
                                                            <th class="volumetric_weight_th">Valumetric Weight</th>
                                                            <th class="volumetric_weight_th">Actual Weight</th>
                                                            <th class="volumetric_weight_th">Chargeable Weight</th>

                                                        </tr>
                                                        <thead>
                                                        <tbody id="volumetric_table_row">

                                                        <?php

															$length_detail =  json_decode($weight->length_detail);
															$breath_detail =  json_decode($weight->breath_detail);
															$height_detail =  json_decode($weight->height_detail);
															$valumetric_weight_detail =  json_decode($weight->valumetric_weight_detail);
															$per_box_weight_detail =  json_decode($weight->per_box_weight_detail);
															$weight_details =  json_decode($weight->weight_details, true);

															// echo "<pre>";
															// print_r($weight_details);exit();

															for ($jd = 0; $jd < count($valumetric_weight_detail); $jd++) {
															?>
                                                            <tr>
                                                                <td><input type="text" name="per_box_weight_detail[]" class="form-control per_box_weight valid" data-attr="<?php echo ($jd + 1); ?>" id="per_box_weight<?php echo ($jd + 1); ?>"  aria-invalid="false" value="<?php echo $per_box_weight_detail[$jd]; ?>"></td>
                                                                <td class="length_td"><input type="text" name="length_detail[]" class="form-control length"  data-attr="<?php echo ($jd + 1); ?>" id="length<?php echo ($jd + 1); ?>" value="<?php echo $length_detail[$jd]; ?>" ></td>
                                                                <td class="breath_td"><input type="text" name="breath_detail[]" class="form-control breath" data-attr="<?php echo ($jd + 1); ?>" id="breath<?php echo ($jd + 1); ?>" value="<?php echo $breath_detail[$jd]; ?>" ></td>
                                                                <td class="height_td"><input type="text" name="height_detail[]" class="form-control height" data-attr="<?php echo ($jd + 1); ?>" id="height<?php echo ($jd + 1); ?>" value="<?php echo $height_detail[$jd]; ?>" ></td>
                                                                <td class="volumetic_weight_td"><input type="text" name="valumetric_weight_detail[]" readonly class="form-control valumetric_weight" data-attr="<?php echo ($jd + 1); ?>" id="valumetric_weight<?php echo ($jd + 1); ?>" value="<?php echo $valumetric_weight_detail[$jd]; ?>"></td>

                                                                <td class="volumetic_weight_td"><input type="text" name="valumetric_actual_detail[]" class="form-control valumetric_actual" data-attr="<?php echo ($jd + 1); ?>" id="valumetric_actual<?php echo ($jd + 1); ?>" value="<?php echo $weight_details['valumetric_actual_detail'][$jd]; ?>"></td>

                                                                <td class="volumetic_weight_td"><input type="text" name="valumetric_chageable_detail[]" readonly class="form-control valumetric_chageable" data-attr="<?php echo ($jd + 1); ?>" id="valumetric_chageable<?php echo ($jd + 1); ?>" value="<?php echo $weight_details['valumetric_chageable_detail'][$jd]; ?>"></td>
                                                            </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    <tfoot>

                                                    </tfoot>
                                                </table>
                                                <table>
                                                    <tr>

                                                        <th><input type="text" name="per_box_weight" readonly="readonly" class="form-control  per_box_weight" id="per_box_weight" value="<?php echo $weight->per_box_weight; ?>"></th>
                                                        <th class="length_td"><input type="text" name="length" readonly="readonly" class="form-control length" id="length" value="<?php echo $weight->length; ?>"></th>
                                                        <th class="breath_td"><input type="text" name="breath" readonly="readonly" class="form-control breath" id="breath" value="<?php echo $weight->breath; ?>"></th>
                                                        <th class="height_td"><input type="text" name="height" readonly="readonly" class="form-control height" id="height" value="<?php echo $weight->height; ?>"></th>
                                                        <th class="volumetic_weight_td"><input type="text" name="valumetric_weight" readonly="readonly" class="form-control my-colorpicker1 valumetric_weight" id="valumetric_weight" value="<?php echo $weight->valumetric_weight; ?>"></th>

                                                        <th class="volumetic_weight_td"><input type="text" name="valumetric_actual" readonly="readonly" class="form-control my-colorpicker1 valumetric_weight" id="valumetric_actual" value="<?php echo $weight_details['valumetric_actual']; ?>"></th>

                                                        <th class="volumetic_weight_td"><input type="text" name="valumetric_chageable" readonly="readonly" class="form-control my-colorpicker1 valumetric_weight" id="valumetric_chageable" value="<?php echo $weight_details['valumetric_chageable']; ?>"></th>
                                                        <!-- <td><input type="text" name="one_cft_kg" readonly="readonly" class="form-control my-colorpicker1 one_cft_kg" id="one_cft_kg"></td> -->
                                                    </tr>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Measurement Units -->
                    </div>








                    <div class="col-md-6 col-sm-12 mt-3">
                        <!-- Charges -->
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Charges</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group row">
                                                <label class="col-sm-3 col-form-label">Freight</label>
                                                <div class="col-sm-3">
                                                    <input type="number" min="1" name="frieht" value="<?php echo $booking->frieht; ?>" class="form-control" required  id="frieht" readonly />
                                                </div>
                                                <label class="col-sm-3 col-form-label">ODA Charge</label>
                                                <div class="col-sm-3">
                                                    <input type="number" name="delivery_charges" class="form-control"  value="<?php echo $booking->delivery_charges; ?>" id="delivery_charges" readonly>
                                                </div>
                                                <!--<label class="col-sm-3 col-form-label">Handling Charge</label>-->
                                                <!--<div class="col-sm-3">-->
                                                <!--    <input type="number" name="transportation_charges" class="form-control" value="0" id="transportation_charges">-->
                                                <!--</div>-->
                                            </div>
                                            <div class="form-group row">
                                                <!--<label class="col-sm-3 col-form-label">Pickup</label>-->
                                                <!--<div class="col-sm-3">-->
                                                <!--    <input type="number" name="pickup_charges" class="form-control" value="0" id="pickup_charges">-->
                                                <!--</div>-->
                                                 <label class="col-sm-3 col-form-label">COD</label>
                                                <div class="col-sm-3">
                                                    <input type="number" name="courier_charges" class="form-control" value="<?php echo $booking->courier_charges; ?>" id="courier_charges" readonly>
                                                </div>
                                                 <label class="col-sm-3 col-form-label">AWB Ch.</label>
                                                <div class="col-sm-3">
                                                    <input type="number" name="awb_charges" class="form-control" value="<?php echo $booking->awb_charges; ?>" id="awb_charges" readonly>
                                                </div>
                                            </div>
                                            <!--<div class="form-group row">-->
                                                <!--<label class="col-sm-3 col-form-label">Insurance</label>-->
                                                <!--<div class="col-sm-3">-->
                                                <!--    <input type="number" name="insurance_charges" class="form-control" id="insurance_charges">-->
                                                <!--</div>-->
                                               
                                            <!--</div>-->
                                            <div class="form-group row">



                                               
                                                <label class="col-sm-3 col-form-label">Other Ch.</label>
                                                <div class="col-sm-3">
                                                    <input type="number" name="other_charges" class="form-control" value="<?php echo $booking->other_charges; ?>" id="other_charges" readonly>
                                                </div>
                                                <label class="col-sm-3 col-form-label">Fov Charges</label>
                                                <div class="col-sm-3">
                                                    <input type="number"  class="form-control" name="fov_charges" id="fov_charges" value="<?php echo $booking->fov_charges; ?>" readonly>
                                                </div>
                                            </div>

                                            <!--<div class="form-group row">-->



                                                <!--<label class="col-sm-3 col-form-label">Green Tax.</label>-->
                                                <!--<div class="col-sm-3">-->
                                                <!--    <input type="number" name="green_tax" class="form-control" value="0" id="green_tax">-->
                                                <!--</div>-->
                                                <!--<label class="col-sm-3 col-form-label">Appt Ch.</label>-->
                                                <!--<div class="col-sm-3">-->
                                                <!--    <input type="number" name="appt_charges" class="form-control" value="0" id="appt_charges">-->
                                                <!--</div>-->
                                            <!--</div>-->
                                            <div class="form-group row">

                                                
                                                <label class="col-sm-3 col-form-label">Total</label>
                                                <div class="col-sm-3">
                                                    <input type="number"  name="amount" required class="form-control" value="<?php echo $booking->total_amount; ?>" id="amount" readonly />
                                                </div>
                                                <label class="col-sm-3 col-form-label">Fuel Surcharge</label>
                                                <div class="col-sm-3">
                                                    <input type="number"  class="form-control" name="fuel_subcharges" value="<?php echo $booking->fuel_subcharges; ?>" id="fuel_charges" readonly>
                                                </div>

                                            </div>
                                            <!--<div class="form-group row">-->

                                                
                                            <!--</div>-->

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-header">
                                <h4 class="card-title">Final Charge</h4>
                            </div>
                            <div class="card-content">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group row" id="payby" style="display:none;">
                                                        <label class="col-sm-2 col-form-label">Pay By<span class="compulsory_fields">*</span></label>
                                                        <div class="col-sm-4">
                                                            <select class="form-control" name="payment_method" id="payment_method">
                                                                <option>-Select-</option>
                                                                <?php foreach ($payment_method as $pm) { ?>
                                                                    <option value="<?php echo $pm['id']; ?>"><?php echo $pm['method']; ?></option>
                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group row" id="Refno" style="display:none;">
                                                        <label class="col-sm-3 col-form-label">Ref No</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" name="ref_no" class="form-control" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Sub Total</label>
                                                        <div class="col-sm-9">
                                                            <input type="number" readonly name="sub_total" class="form-control" value="<?php echo $booking->sub_total; ?>" id="sub_total" />
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">CGST Tax</label>
                                                        <div class="col-sm-9">
                                                            <input class="form-control" type="number" id="cgst" step="any" name="cgst" value="<?php echo $booking->cgst; ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">SGST Tax</label>
                                                        <div class="col-sm-9">
                                                            <input class="form-control" type="number" id="sgst" step="any" name="sgst" value="<?php echo $booking->sgst; ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">IGST Tax</label>
                                                        <div class="col-sm-9">
                                                            <input class="form-control" type="number" id="igst" step="any" name="igst" value="<?php echo $booking->igst; ?>" readonly>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group row">
                                                        <label class="col-sm-3 col-form-label">Grand Total</label>
                                                        <div class="col-sm-9">
                                                            <input type="text" readonly class="form-control" name="grand_total" value="<?php echo $booking->grand_total; ?>" id="grand_total" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group row mt-3">
                                                <div class="col-sm-12"  >
                                                <!-- id="submit" -->
                                                    <button type="submit" class="btn btn-primary">Submit</button> &nbsp;
                                                    <button type="button"  onclick="return open_new_page()" class="btn btn-primary">New</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Charges -->


                    </div>

                </div>
            </form>
                        </div>












                        <!-- *******************************Summary Details tab-4 **************************************************** -->



                        <div class="tab-pane fade" id="id4">
                             <div class="row">
                                    <div class="col-12 col-sm-12">
                                        <div class="row">
                                            <div class="col-12 col-md-4 mt-3">
                                                <div class="card mb-4">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h4 class="card-title">Order Information</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                          <div class="col-md-6">
                                                            <div><p>Order ID : </p></div>
                                                            <div><p>Order Type : </p> </div>
                                                         </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="col-12 col-md-4 mt-3">
                                                <div class="card">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h4 class="card-title">Consignee Information</h4>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">
                                                          <div class="col-md-6">
                                                          <div><p>Name : </p></div>
                                                            <div><p>Phone Number : </p> </div>
                                                            <div><p>Pincode : </p> </div>
                                                            <div><p>City : </p> </div>
                                                            <div><p> Consignee Address : </p> </div>
                                                         </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12 col-md-4 mt-3">
                                                <div class="card">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h4 class="card-title">Consigner Information</h4>
                                                    </div>
                                                   <div class="card-body">
                                                        <div class="row">
                                                          <div class="col-md-6">
                                                            <div><p>Name : </p></div>
                                                            <div><p>Phone Number : </p> </div>
                                                            <div><p>Pincode : </p> </div>
                                                            <div><p>City : </p> </div>
                                                            <div><p> Consignee Address : </p> </div>
                                                         </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                           <div class="col-12 col-md-12 mt-3">
                                                <div class="card">
                                                    <div class="card-header d-flex justify-content-between align-items-center">
                                                        <h4 class="card-title">Item Details</h4>
                                                    </div>
                                                   <div class="card-body">
                                                        <table class ="table table-bordered">
                                                            <tbody>
                                                                <tr style="background-color:#ddd;color:#333;">
                                                                    <th>Item Name</th>
                                                                    <th>Item Quantity</th>
                                                                    <th>Item Price</th>
                                                                    <th>Item SKU</th>
                                                                </tr>
                                                                <tr>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                            </tbody>
                                                        <table class ="table table-bordered">
                                                            <tbody>
                                                                <tr style="background-color:#ddd;color:#333;">
                                                                    <th>Weight</th>
                                                                    <th>Length</th>
                                                                    <th>Breadth</th>
                                                                    <th>Height</th>
                                                                </tr>
                                                                <tr>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                    <td></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                         <div class="d-flex">
                                            <button type="button" class="btn btn-primary prevtab">Previous</button>
                                            <button type="button" class="btn btn-primary nexttab ml-auto">Next</button>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    <div class="tab-pane fade" id="id5">
                            <div class="row">
                            <div class="col-12 col-md-12 mt-3 ">
                                <div class="card mb-12 pb-2">
                                    <div class="card-header d-flex justify-content-between align-items-center">
                                        <h4 class="card-title">Genrate Report</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-10">
                                            <label>Bill Genrate To XB</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control">
                                            </div>
                                            </div>
                                            <div class="col-md-10">
                                            <label>Invoice Genrated To Customer</label>
                                            <div class="form-group">
                                                <input type="text" class="form-control">
                                            </div>
                                            </div>
                                        </div>
                                        <div class="d-flex" >
                                        <button type="button"  class="btn btn-primary prevtab">Previous</button>
                                        <button type="submit" name="submit"  class="btn btn-primary nexttab ml-auto">Submit</button>
                                    </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                   </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</main>
<div class="main"> <i style="float: right; margin-right:20px; margin-top:10px; font-size:17px;cursor:pointer;" id="close" class="fa fa-times" aria-hidden="true"></i>
  <style>
    .main{
      position: absolute;
      top: 100%;
      left: 20%;
      z-index: 9;
      background-color: #fff;
      width: 600px;
      height: 250px;
      display: none;
      box-shadow: 5px 5px 30px grey;
      border-radius: 5px;
      
    } 
    .calculate{
      padding: 10% 20px;
    }

  </style>
  <div class="calculate">
  <h1 class="text-center">Calculator</h1>
       <div class="row">
        <div class="col-md-5">
         <label for="Inch">Inch</label><br>
          <input type="number" id="input" class="form-control" placeholder="Inch"></div>
        <div class="col-md-1"><br><br> = </div>
        <div class="col-md-5">
        <label for="Inch">Centimeter</label><br>  
        <input type="text" id="result" class="form-control"></div>
       </div>
  </div>

  <?php  $this->load->view('masterfranchise/master_franchise_shared/admin_footer.php');?>
	 <script src="<?php echo base_url();?>assets/franchise_assets/domestic_shipment.js"></script>
	  <!--<script src="assets/js/domestic_shipment.js"></script>-->
      <Script>
          $(document).ready(function(){
           // alert('hello');
           $('.main').hide();
            $('#calculator').click(function(){
              $('.main').show();
              

            });
            $('#close').click(function(){
              $('.main').hide();
              

            });
            $('#input').keyup(function(){
              var input = $('#input').val();
              
              var result = input * 2.54;
              // alert(result);
              $('#result').val(result);
            });
          });
        </Script>
	 <script>

        $("#submit").on('click', function () {
            // e.preventDefault();
            frieht = $('#frieht').val();
            if(frieht){
                frieht=frieht.trim();
                if (frieht==0 || frieht=="") {
                    alert("This Service is Not Available!");
                    return false;
                }
            }else{
                alert("This Service is Not Available!");
                return false;
            }

            $('#formSubmit').submit();
            
        });
	
	     // ***************franchise persnal Details use Pincode
  $("#reciever_pincode").on('blur', function () 
  {
    var pincode = $(this).val();
    if (pincode != null || pincode != '') {

       
      $.ajax({
        type: 'POST',
        url: '<?php echo base_url();?>Franchise_manager/getCityList',
        data: 'pincode=' + pincode,
        dataType: "json",
        success: function (d) {    
            // console.log(d.result2.city);     
          var option;         
          option += '<option value="' + d.id + '">' + d.city + '</option>';
          $('#reciever_city').html(option);
          
        }
      });
      $.ajax({
        type: 'POST',
        url: '<?php echo base_url();?>Franchise_manager/getState',
        data: 'pincode=' + pincode,
        dataType: "json",
        success: function (d) {         
          var option;         
          option += '<option value="' + d.result3.id + '">' + d.result3.state + '</option>';
          $('#reciever_state').html(option);          
          var oda = '';         
          oda += '<span style="color:red;">'+d.oda.isODA+'</span>';
          $('#oda').html(oda);          
        },
        error: function () {
					$('#oda').html('<p>Service Not Available</p>');	
				}
      });
    }
  }); 


  $("#reciever_state, #reciever_city").blur(function () 
	{
		var reciever_state =$("#reciever_state").val();
		var reciever_city =$("#reciever_city").val();

      $.ajax({
        type: 'POST',
        url: '<?php echo base_url();?>Franchise_manager/getZone',
        data: {reciever_city:reciever_city,reciever_state:reciever_state},
        dataType: "json",
        success: function (d) {         
                    $("#receiver_zone_id").val(d.region_id);						
					$("#receiver_zone1").val(d.region_name);	
          
        }
      });
    
}); 




//   alert('hello');
//  $("#volumetric_table").hide();
 $('#fov_charges').hide();

	$("#risk_type").change(function () 
	{
		var risk_type = $(this).val();
		if(risk_type=='Carrier'){
		    $('#fov_charges').show();
		}else{
		    $('#fov_charges').hide();
		}

	});
	 $('#submit').hide();
	$("#sub_total").blur(function (){
	      var frieht = $('#sub_total').val();
     
    if (frieht == '0' || frieht=='') {
        $('#submit').hide();
    }else{
        $('#submit').show();
    }
	});
	$("#amount").blur(function (){
	      var frieht = $('#amount').val();
     
    if (frieht == '0' || frieht=='') {
        $('#submit').hide();
    }else{
        $('#submit').show();
    }
	});
	
	 
	$("#frieht").blur(function (){
	      var frieht = $('#frieht').val();
     
    if (frieht == '0' || frieht=='') {
        $('#submit').hide();
    }else{
        $('#submit').show();
    }
	});

	
	
	//customer
		$("#customer_account_id").change(function () 
	{
		var customer_name = $(this).val();
	//	alert(customer_name);
		if (customer_name != null || customer_name != '') 
		{
			$.ajax({
				type: 'POST',
				dataType: "json",
				url: '<?php echo base_url();?>Franchise_manager/getsenderdetails',
				data: 'customer_name=' + customer_name,
				success: function (data) 
				{
					$("#sender_name").val(data.user.customer_name);
					$("#sender_address").val(data.user.address);
					$("#sender_pincode").val(data.user.pincode);
					$("#sender_contactno").val(data.user.phone);
					$("#sender_gstno").val(data.user.gstno);
					$("#gst_charges").val(data.user.gst_charges);
					// $("#sender_city").val(data.user.city);
					// $("#sender_state").val(data.user.state);					
					$("#customer_account_id").val(customer_name);

					 var option;					
					 option += '<option value="' + data.user.city_id + '">' + data.user.city_name + '</option>';
					$('#sender_city').html(option);

					 var option1;					
					 option1 += '<option value="' + data.user.state_id + '">' + data.user.state_name + '</option>';
					$('#sender_state').html(option1);
					var dispatch_details =$("#dispatch_details").val();
				
					document.getElementById("reciever").focus();								
				}
			});
		}
	});
	
		// chkceing duplicate number
	$("#awn").blur(function () {
        var pod_no = $(this).val();
        if (pod_no != null || pod_no != '') {
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: '<?php echo base_url();?>Franchise_manager/check_duplicate_awb_no',
                data: 'pod_no=' + pod_no,
                success: function (data) {
                    if(data.msg!=""){       
                    		 $('#awn').focus();
                    		 $('#awn').val("");
                    		 alert(data.msg);
                    }else{
                    }
                    
                }
            });
        }
    });
    
    
    //doc and nondoc
    	$("#doc_typee").change(function ()
	{
			var shipment =$("#doc_typee").val();
			if(shipment==1)
			{
				$('#div_inv_row').show();

				$(".length_td").show();
                $(".height_td").show();
                $(".breath_td").show();
                $(".volumetic_weight_td").show();
                $(".cft_th").show();                                                    
                $(".volumetric_weight_th").show();
                $(".length_th").show();
                $(".breath_th").show();
                $(".height_th").show();
			}else{
				$('#div_inv_row').hide();
				$('#invoice_no').val("");
				$('#invoice_value').val("");
				$('#eway_no').val("");

				$(".length_td").hide();
                $(".height_td").hide();
                $(".breath_td").hide();
                $(".volumetic_weight_td").hide();
                $(".cft_th").hide();                                                    
                $(".volumetric_weight_th").hide();
                $(".length_th").hide();
                $(".breath_th").hide();
                $(".height_th").hide();  
			}
	});
	

	 </script>
    