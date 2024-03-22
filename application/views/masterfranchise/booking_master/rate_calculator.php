<?php  $this->load->view('masterfranchise/master_franchise_shared/admin_header.php');?>
<?php $this->load->view('masterfranchise/master_franchise_shared/admin_sidebar.php');?>
 <!-- START: Card Data-->
 <main>
    <div class="row">
        <div class="col-12 mt-3 " style="padding-left: 50px; padding-right:50px; padding-top:20px;">
            <div class="card">
            <div class="card-body">
                <div class="row p-2">
                    <div class="col-md-6">
                        <h6 class="">Rate Calculator Shipment </h6>
                        <a href="<?= base_url('master-franchise/rate-calculator')?>" class="btn btn-sm btn-success" style="background:#12263f!important;color:#fff; border: 1px solid #12263f!important;">Reset</a>
                    </div>
                </div>
                <hr>
                <?php if ($this->session->flashdata('notify') != '') { ?>
                    <div class="alert <?php echo $this->session->flashdata('class'); ?> alert-colored"><?php echo $this->session->flashdata('notify'); ?></div>
                <?php unset($_SESSION['class']);
                    unset($_SESSION['notify']);
                } 

                // echo "<pre>";
                // print_r($_SESSION);exit();

                ?>
           
                    <input type="hidden" name="<?php echo $_SESSION['customer_id'];?>" value="<?php echo $_SESSION['customer_id'];?>" id="customer_id">
                    
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">From Pincode<span class="compulsory_fields">*</span></label>
                        <div class="col-sm-4">
                            <input type="text" id="sender_pincode_rate"  value="<?= $balance->pincode; ?>" class="form-control">
                            <select  id="sender_state_rate" class="form-control" readonly style="display:none;">
                                <option value="">--Select--</option>
                            </select> <br>
                            <select  id="sender_city_rate" readonly class="form-control">
                                <option value="">--Select--</option>
                            </select>
                            <input type="hidden" name="receiver_zone_id" id="receiver_zone_id" class="form-control">
                            <!-- <input type="hidden"  id="sender_state_rate" value="" class="form-control"> <br>
                            <input type="text"  id="sender_city_rate" readonly value="" class="form-control"> -->
                        </div>
                        <label class="col-sm-2 col-form-label">To Pincode<span class="compulsory_fields">*</span></label>
                        <div class="col-sm-4">
                            <input type="number" class="form-control"  id="reciever_pincode_rate" autocomplete="off">
                            <select  id="reciever_state_rate" class="form-control" disabled>
                                <option value="">--Select--</option>
                            </select> <br>
                            <select  id="reciever_city_rate" readonly class="form-control">
                                <option value="">--Select--</option>
                            </select>
                            <!-- <input type="hidden" name="receiver_zone_id" id="receiver_zone_id" class="form-control"> -->
                        </div>
                    </div>
                    <input type="hidden" name="booking_date" id="booking_date" value="<?php echo date('Y-m-d');?>">
                    <div class="form-group row">
                    <div class="col-sm-2">
                        <label class="col-form-label">Mode<span class="compulsory_fields">*</span></label>
                            <select class="form-control"  id="mode_id">
                                <option value="">-Select Mode-</option>
                                <?php
                                 $mode = $this->db->query("select * from transfer_mode")->result();
                                 foreach($mode as $key => $value){
                                ?>
                                <option value="<?= $value->transfer_mode_id; ?>"><?= $value->mode_name; ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="col-sm-2">
                        <label class="col-form-label">Product<span class="compulsory_fields">*</span></label>
                            <select class="form-control" name="doc_type" id="doc_typee">
                                <option value="">-Select-</option>
                                <option value="1">Non-Doc</option>
                                <option value="0">Doc</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                        <label class=" col-form-label">PKT</label> <br>
                            <input type="text" name="no_of_pack" class="form-control my-colorpicker1 no_of_pack" data-attr="1" id="no_of_pack1" required="required">
                            <input type="hidden"  id="customer_account_id" value="<?= $_SESSION['customer_id'] ?>">
                        </div>
                        <div class="col-sm-2">
                           <label class="col-form-label">Actual Weight</label>
                            <input type="text" name="actual_weight" class="form-control my-colorpicker1 actual_weight" data-attr="1" id="actual_weight" required="required">
                        </div>
                        <div class="col-sm-2">
                        <label class="col-form-label">Chargeable Weight</label>
                            <input type="text" name="chargable_weight" class="form-control my-colorpicker1 chargable_weight" data-attr="1" id="chargable_weight" required="required">
                        </div>
                        <div class="col-sm-2">
                        <label class="col-form-label">Is Volumetric</label> <br>
                            <input type="checkbox" id="is_volumetric" name="fav_language" value="">

                        </div>

                        <div id="volumetric_table" style="display:none ! important;">
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
                                        <tr>
                                            <td><input type="text" name="per_box_weight_detail[]" class="form-control per_box_weight valid" data-attr="1" id="per_box_weight1" aria-invalid="false"></td>
                                            <td class="length_td"><input type="text" name="length_detail[]" class="form-control length" data-attr="1" id="length1"></td>
                                            <td class="breath_td"><input type="text" name="breath_detail[]" class="form-control breath" data-attr="1" id="breath1"></td>
                                            <td class="height_td"><input type="text" name="height_detail[]" class="form-control height" data-attr="1" id="height1"></td>
                                            <td class="volumetic_weight_td"><input type="text" name="valumetric_weight_detail[]" readonly class="form-control valumetric_weight" data-attr="1" id="valumetric_weight1"></td>

                                            <td class="volumetic_weight_td"><input type="text" name="valumetric_actual_detail[]" class="form-control valumetric_actual" data-attr="1" id="valumetric_actual1"></td>

                                            <td class="volumetic_weight_td"><input type="text" name="valumetric_chageable_detail[]" readonly class="form-control valumetric_chageable" data-attr="1" id="valumetric_chageable1"></td>
                                        </tr>
                                    </tbody>
                                <tfoot>

                                </tfoot>
                            </table>
                            <table>
                                <tr>

                                    <th><input type="text" name="per_box_weight" readonly="readonly" class="form-control  per_box_weight" id="per_box_weight" required="required" style="background: var(--bordercolor)"></th>
                                    <th class="length_td"><input type="text" name="length" readonly="readonly" class="form-control length" id="length"></th>
                                    <th class="breath_td"><input type="text" name="breath" readonly="readonly" class="form-control breath" id="breath"></th>
                                    <th class="height_td"><input type="text" name="height" readonly="readonly" class="form-control height" id="height"></th>
                                    <th class="volumetic_weight_td"><input type="text" name="valumetric_weight" readonly="readonly" class="form-control my-colorpicker1 valumetric_weight" id="valumetric_weight"></th>

                                    <th class="volumetic_weight_td"><input type="text" name="valumetric_actual" readonly="readonly" class="form-control my-colorpicker1 valumetric_weight" id="valumetric_actual"></th>

                                    <th class="volumetic_weight_td"><input type="text" name="valumetric_chageable" readonly="readonly" class="form-control my-colorpicker1 valumetric_weight" id="valumetric_chageable"></th>
                                    <td><input type="hidden" readonly="readonly" class="form-control my-colorpicker1 one_cft_kg" id="cft"></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="form-group row">
                      <br> <br>
                      <div class="col-sm-3">
                      <label class=" col-form-label">FINAL CHARGE (GST EXCLUDE)</label> <br>
                          <input type="text"  class="form-control my-colorpicker1 no_of_pack" id="amount">
                      </div>
                    
                    </div>
                
            </div>

        </div>
    </div>
    <!-- END: Card DATA-->
</div>
</main>

<?php  $this->load->view('masterfranchise/master_franchise_shared/admin_footer.php');?>
<script src="<?php echo base_url();?>assets/franchise_assets/domestic_shipment.js"></script>

<script>
$('#sender_city_rate , #reciever_city_rate').prop('disabled',true);
$(document).ready(function(){
    $("#sender_pincode_rate").on('blur', function () 
	{
		var pincode = $(this).val();
		if (pincode != null || pincode != '') {
		
            $.ajax({
				type: 'POST',
				url: '../Franchise_manager/getState_rate',
				data: 'pincode=' + pincode,
				dataType: "json",
				success: function (data) 
				{		
                   	
					$('#sender_state_rate').html(data);					
				}
			});
			$.ajax({
				type: 'POST',
				url: '../Franchise_manager/getCityList_rate',
				data: 'pincode=' + pincode,
				dataType: "json",
				success: function (data) {					
					$('#sender_city_rate').html(data.option);					
				}
			});
		
		}
	});	 

   
    $("#reciever_pincode_rate").on('blur', function () 
	{
		var pincode = $(this).val();
		if (pincode != null || pincode != '') {
		
            $.ajax({
				type: 'POST',
				url: '../Franchise_manager/getState_rate',
				data: 'pincode=' + pincode,
				dataType: "json",
				success: function (data) 
				{					
                    $('#reciever_state_rate').html(data);					
				}
			});
			$.ajax({
				type: 'POST',
				url: '../Franchise_manager/getCityList_rate',
				data: 'pincode=' + pincode,
				dataType: "json",
				success: function (data) {				
					$('#reciever_city_rate').html(data.option);		
                    
                    var reciever_state =$("#reciever_state_rate").val();
		            var reciever_city =$("#reciever_city_rate").val();
                        //   console.log('hello');
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

				}
			});

      
		
		}
	});	 

    // $("#reciever_state_rate, #reciever_city_rate").blur(function () 
	// {
		
        
    // }); 



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
				$('#invoice_value').val(0);
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




    $(".chargable_weight,#valumetric_chageable").blur(function () 
    {
        // if ($('#is_appointment').is(':checked')) 
        // {
        //     var is_appointment   = 1;
        // }
        // else{
            var is_appointment   = 0;
        // }
        var customer_id   = $('#customer_id').val();
        var c_courier_id  = 35;
        // var mode_id   = 18;
        var mode_id  =  $("#mode_id").val();  
        var sender_state  =  $("#sender_state_rate").val();       
        var sender_city  = $("#sender_city_rate").val();     
        var state  = $("#reciever_state_rate").val();        
        var city  = $("#reciever_city_rate").val();      
        var doc_type = $('#doc_typee').val();
        var receiver_zone_id = $("#receiver_zone_id").val();
        var receiver_gstno = 0;
        var booking_date =$("#booking_date").val();;
        var dispatch_details ="";
        var invoice_value    = 0;
        
        var chargable_weight = parseFloat($('#chargable_weight').val()) > 0 ? $('#chargable_weight').val() : 0;
        if(mode_id == ''){
            alert("Mode Selection Required");
        }
        if(doc_type == ''){
            alert("doc type Selection Required");
        }
        if(customer_id != '')
        {
            $.ajax({
                type: 'POST',
                url: '<?php echo base_url();?>Franchise_manager/calculate_rate',
                data: 'customer_id=' + customer_id  +'&c_courier_id=' + c_courier_id+'&mode_id=' + mode_id+'&state=' + state +'&city=' + city +'&chargable_weight='+chargable_weight+'&receiver_zone_id='+receiver_zone_id+'&receiver_gstno='+receiver_gstno+'&booking_date='+booking_date+'&invoice_value='+invoice_value+'&dispatch_details='+dispatch_details+'&sender_state='+sender_state+'&sender_city='+sender_city+'&is_appointment='+is_appointment,

                dataType: "json",
                success: function (data) {  
                    console.log("final_rate====="+data.query);
                    console.log("====="+data.frieht);
                    $('#frieht').val(data.frieht);
                    $('#transportation_charges').val(0);
                    $('#pickup_charges').val(0);
                    $('#delivery_charges').val(0);
                    $('#insurance_charges').val(0);
                    $('#courier_charges').val(data.cod);
                    $('#other_charges').val(data.to_pay_charges);
                    $('#amount').val(data.amount);
                    $('#fuel_charges').val(data.final_fuel_charges);
                    $('#sub_total').val(data.sub_total);
                    $('#amount').val(data.sub_total);
                    $('#cgst').val(data.cgst);
                    $('#sgst').val(data.sgst);
                    $('#igst').val(data.igst);
                    $('#awb_charges').val(data.docket_charge);
                    $('#green_tax').val(data.to_pay_charges);
                    $('#fov_charges').val(data.fov);
                    $('#appt_charges').val(data.appt_charges);
                    $('#grand_total').val(data.grand_total);
                    $('#cft').val(data.cft);
                    $('#delivery_date').val(data.tat_date);
                    // alert(data.grand_total);
                }
            });
        }
        else
        {
            $('#frieht').val();
        }
    });




});

   

</script>
