/* Document.ready Start */	
jQuery(document).ready(function() {
	
	var dispatch_details =$('#dispatch_details').val();
	//alert(dispatch_details);
	if(dispatch_details=="Credit")
	{
		$("#credit_div").show();
		$("#credit_div_label").show();
		$("#payby").hide();
		$("#Refno").hide();
		
		$("#sub_total").attr("readonly", true);
		$("#grand_total").attr("readonly", true);
	}else if(dispatch_details=="Cash")
	{
		$("#credit_div").show();
		$("#credit_div_label").show();
		$("#Refno").show();
	
		$("#payby").show();
		$("#sender_name").attr("readonly", false); 
		$("#sender_address").attr("readonly", false); 
		$("#sender_city").attr("readonly", false); 
		$("#sender_pincode").attr("readonly", false);
		$("#sender_contactno").attr("readonly", false);
		$("#sender_gstno").attr("readonly", false);
		
		$("#sub_total").attr("readonly", false);
		$("#grand_total").attr("readonly", false);
		
	}
	
	var export_import_type =$('#export_import_type').val();
	if(export_import_type=='Export')
	{
		 $("#Consigner_div").html("Consigner");
		 $("#Consignee_div").html("Consignee");
	}else if(export_import_type=='Import')
	{
		$("#Consigner_div").html("Consignee");
		$("#Consignee_div").html("Consigner");
	}
	
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
	
	$(function() 
	{
		$('input:radio[name="export_import_type"]').change(function() {				
			if ($(this).val() == 'Export') 
			{
			   $("#Consigner_div").html("Consigner");
			   $("#Consignee_div").html("Consignee");
			}
			else 
			{
				$("#Consigner_div").html("Consignee");
				$("#Consignee_div").html("Consigner");
			} 
		});
	});
	
	$(function() 
	{
		$("form[name='generatePOD']").validate({  
		rules: {    
		  booking_date: "required", 
		  forworder_name: "required",
		  courier_company:"required",
		  export_import_type:"required",
		  doc_type: "required", 
		  dispatch_details: "required",
		  sender_pincode:"required",
		  reciever_name:"required",
		  reciever_country_id: "required", 
		  frieht: "required",
		  transportation_charges:"required",
		  destination_charges:"required",
		  clearance_charges:"required",
		  ecs:"required",
		  other_charges:"required",
		  amount:"required",
		  sub_total:"required",
		  igst:"required",
		  grand_total:"required",
		  sender_gstno:"required",
		},
		// Specify validation error messages
		messages: {
			 booking_date: "Required", 
			  forworder_name: "Required",
			  courier_company:"Required",
			  export_import_type:"Required",
			  doc_type: "Required", 
			  dispatch_details: "Required",
			  sender_pincode:"Required",
			  reciever_name:"Required",
			  reciever_country_id: "Required", 
			  frieht: "Required",
			  transportation_charges:"Required",
			  destination_charges:"Required",
			  clearance_charges:"Required",
			  ecs:"Required",
			  other_charges:"Required",
			  amount:"Required",
			  sub_total:"Required",
			  igst:"Required",
			  grand_total:"Required",
			  sender_gstno:"Required",
		},
		 errorPlacement: function(error, element) {
				if (element.attr("type") == "radio") {
					error.insertBefore(element);
				} else {
					error.insertAfter(element);
				}
			},		   
		submitHandler: function(form) {
		  form.submit();
		}
	  });
	});
		
	$("#reciever_email").on('blur', function () 
	{
		document.getElementById("no_of_pack1").focus();	
	});
	
	$("#no_of_pack").on('blur', function () 
	{
		document.getElementById("frieht").focus();	
	});
	
	$("#sender_pincode").on('blur', function () 
	{
		var pincode = $(this).val();
		if (pincode != null || pincode != '') {

		
		$.ajax({
				type: 'POST',
				url: 'Admin_domestic_shipment_manager/getCityList',
				data: 'pincode=' + pincode,
				dataType: "json",
				success: function (data) {					
					$('#sender_city').html(data.option);					
				}
			});
			$.ajax({
				type: 'POST',
				url: 'Admin_domestic_shipment_manager/getState',
				data: 'pincode=' + pincode,
				dataType: "json",
				success: function (data) {					
					// var option;					
					// option += '<option value="' + d.id + '">' + d.state + '</option>';
					$('#sender_state').html(data);					
				}
			});

		}
	});
	
	
	$("#dispatch_details").change(function ()
	{
		var dispatch_details =$("#dispatch_details").val();
		//alert(dispatch_details);
		if(dispatch_details=="Credit")
		{
			$("#credit_div").show();
			$("#credit_div_label").show();
			$("#payby").hide();
			$("#Refno").hide();
			
			$("#sub_total").attr("readonly", true);
			$("#grand_total").attr("readonly", true);
		}else if(dispatch_details=="Cash")
		{
			$("#credit_div").show();
			$("#credit_div_label").show();
			$("#Refno").show();
			
		//	$("#credit_div").hide();
		//	$("#credit_div_label").hide();
		
			$("#payby").show();
			$("#sender_name").attr("readonly", false); 
			$("#sender_address").attr("readonly", false); 
			$("#sender_city").attr("readonly", false); 
			$("#sender_pincode").attr("readonly", false);
			$("#sender_contactno").attr("readonly", false);
			$("#sender_gstno").attr("readonly", false);
			$("#sub_total").attr("readonly", false);
			$("#grand_total").attr("readonly", false);
			
		}
	});
	
	$("#customer_account_id").change(function () 
	{
		var customer_name = $(this).val();
		if (customer_name != null || customer_name != '') {
			$.ajax({
				type: 'POST',
				dataType: "json",
				url: 'Admin_international_shipment_manager/getsenderdetails',
				data: 'customer_name=' + customer_name,
				success: function (data) {
					$("#sender_name").val(data.user.customer_name);
					$("#sender_address").val(data.user.address);
					$("#sender_pincode").val(data.user.pincode);
					$("#sender_contactno").val(data.user.phone);
					$("#sender_gstno").val(data.user.gstno);
					$("#gst_charges").val(data.user.gst_charges);
					$("#sender_city").val(data.user.city);
					$("#sender_state").val(data.user.state);					
					$("#customer_account_id").val(customer_name);	
					 document.getElementById("reciever").focus();				
				}
			});
		}
	});
	
	$("#forwording_no").blur(function () {
        var forwording_no = $(this).val();
        if (forwording_no != null || forwording_no != '') {
            $.ajax({
                type: 'POST',
                dataType: "json",
                url: 'Admin_international_shipment_manager/check_duplicate_forwording_no',
                data: 'forwording_no=' + forwording_no,
                success: function (data) {
                    if(data.msg!=""){ 
					
                        	 $('#forwording_no').focus();
                    		 $('#forwording_no').val("");
                             alert(data.msg); 
                    }
					else
					{
						
                    }
                    
                }
            });
        }
    });
	
	
	$("#doc_typee").change(function (){
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
	
	$("#reciever_country_id").change(function () 
	{
		var zone_id = $("#reciever_country_id option:selected").attr('data-id');
		$('#reciever_zone_id').val(zone_id);
	});
	
	$("#courier_company").change(function () {
			var courier_company_name = $("#courier_company option:selected").attr('data-id');
			$('#forworder_name').val(courier_company_name);
			
			var courier_id = parseFloat(($('#courier_company').val() != '') ? $('#courier_company').val() : 0);
			var booking_date =$('#booking_date').val();
		
			$.ajax({
					type: 'POST',
					url: 'Admin_international_shipment_manager/available_Fuelcharges',
					data: 'courier_id='+courier_id+'&booking_date='+booking_date,
					dataType: "json",
					success: function(data) {	
					    if(data.fuel_charges=="0")
					    {
								alert("Please entered fuel Percentage");
					    }
				
					}
			});	
	});
	
	$("#courier_company").on('change', function () 
	{
		var courier_company = $(this).val();
			$.ajax({
				type: 'POST',
				url: 'Admin_international_shipment_manager/getCountry',
				data: 'courier_company=' + courier_company,
				dataType: "json",
				success: function (d) {
					console.log(d);
					var option;
					option ='<option value="">-Select-</option>';
					for(var i=0;i < d.length;i++)
					{
					option += '<option value="' + d[i].z_id + '" data-id="'+ d[i].zone_name +'" >' + d[i].country_name + '</option>';
				}
					$('#reciever_country_id').html(option);
				
			
				}
			});
	});

	$("#customer_account_id").select2();
	$("#reciever_country_id").select2();
	
	$(".no_of_pack, .per_box_weight, .actual_weight, .length, .breath, .height, .one_cft_kg").change(function () 
	{
		var totalRow = $('table.weight-table tbody').find('tr').length;	
		var totalActualWeight = 0;
		var totalValumetricWeight = 0;
		var totalLength = 0;
		var totalBreath = 0;
		var totalHeight = 0;
		var totalOneCftKg = 0;
		var totalNoOfPack = 0;
		var totalPerBoxWeight = 0;
	
		for (var i = 1; i <= totalRow; i++) {
			var noOfPackCurrent = parseFloat(($('#no_of_pack' + i).val() != '') ? $('#no_of_pack' + i).val() : 0);
			var perBoxWeightCurrent = parseFloat(($('#per_box_weight' + i).val() != '') ? $('#per_box_weight' + i).val() : 0);
			
			var currentActualWeight = noOfPackCurrent * perBoxWeightCurrent;
			if (currentActualWeight > 0) {
				$('#actual_weight' + i).val(currentActualWeight.toFixed(2));
				$("#chargable_weight" + i).val(currentActualWeight.toFixed(2));
			}

			var length = $("#length" + i).val();
			var breath = $("#breath" + i).val();
			var height = $("#height" + i).val();
			if (length != '' && breath != '' && height != '' && noOfPackCurrent != '') 
			{		
					valumetric_weight = ((length * breath * height) / 5000) * noOfPackCurrent;	
					//console.log('valumetric_weight' + valumetric_weight);	

					total_valumetric_weight = valumetric_weight.toFixed(2);

					$("#valumetric_weight" + i).val(total_valumetric_weight);

					if(total_valumetric_weight > currentActualWeight)
					{
						$("#chargable_weight" + i).val(total_valumetric_weight);
					}else{
						$("#chargable_weight" + i).val(currentActualWeight);
					}

					//calculateTotalWeight();
					//calulateTotal();
			} else {
					$("#valumetric_weight" + i).val('');
			}

			totalNoOfPack = parseFloat(totalNoOfPack) + parseFloat(($('#no_of_pack' + i).val() != '') ? $('#no_of_pack' + i).val() : 0);
			totalPerBoxWeight = parseFloat(totalPerBoxWeight) + parseFloat(($('#per_box_weight' + i).val() != '') ? $('#per_box_weight' + i).val() : 0);
			totalActualWeight = parseFloat(totalActualWeight) + parseFloat(($('#actual_weight' + i).val() != '') ? $('#actual_weight' + i).val() : 0);
			totalValumetricWeight = parseFloat(totalValumetricWeight) + parseFloat(($('#valumetric_weight' + i).val() != '') ? $('#valumetric_weight' + i).val() : 0);

			if(totalValumetricWeight > totalActualWeight)
			{
				$("#chargable_weight").val(totalValumetricWeight.toFixed(2));
			}else{
				$("#chargable_weight").val(totalActualWeight.toFixed(2));
			}

			totalLength = parseFloat(totalLength) + parseFloat(($('#length' + i).val() != '') ? $('#length' + i).val() : 0);
			totalBreath = parseFloat(totalBreath) + parseFloat(($('#breath' + i).val() != '') ? $('#breath' + i).val() : 0);
			totalHeight = parseFloat(totalHeight) + parseFloat(($('#height' + i).val() != '') ? $('#height' + i).val() : 0);
		}
		if (totalNoOfPack) {
			$('#no_of_pack').val(totalNoOfPack);
		}
		if (totalPerBoxWeight) {
			$('#per_box_weight').val(totalPerBoxWeight.toFixed(2));
		}
		if (totalActualWeight) {
			$('#actual_weight').val(totalActualWeight.toFixed(2));
		}
		if (totalValumetricWeight) {
			var roundoff_type = $("#roundoff_type").val();
			// $('#valumetric_weight').val(totalValumetricWeight); ttttttt
			if (roundoff_type == '1') {
				$('#valumetric_weight').val(totalValumetricWeight.toFixed(2));
			} else {
				$('#valumetric_weight').val(totalValumetricWeight.toFixed(2));
			}
		}
		$('#length').val(totalLength.toFixed(2));
		$('#breath').val(totalBreath.toFixed(2));	
		$('#height').val(totalHeight.toFixed(2));
	});	
	
	
	$(".chargable_weight").blur(function () 
	{
		var weight = $("#per_box_weight").val();
		var qty = $("#no_of_pack").val();
		var doc = $("#doc_typee").val();
		//var export_import_type = $("#export_import_type").val();
		var type_export_import = $('input[name="export_import_type"]:checked').val();
		var courier_company_id = $("#courier_company").val();
		var reciever_country_id = $("#reciever_country_id").val();
		var reciever_zone_id = $("#reciever_zone_id").val();
		var customer_id = $('#customer_account_id').val();			
		var customer_name = $('#customer_account_id').val();
		var dispatch_details =$('#dispatch_details').val();
		var actual_weight = (parseFloat($('#actual_weight').val()) > 0) ? $('#actual_weight').val() : 0;
		var valumetric_weight = parseFloat($('#valumetric_weight').val()) > 0 ? $('#valumetric_weight').val() : 0;
		var chargable_weight = parseFloat($('#chargable_weight').val()) > 0 ? $('#chargable_weight').val() : 0;		
		var no_of_pack = parseFloat($('#no_of_pack').val()) > 0 ? $('#no_of_pack').val() : 0;
		var booking_date =$('#booking_date').val();
		if(customer_id!=''   && weight != '' && reciever_country_id!='')
		{
			$.ajax({
				type: 'POST',
				url: 'Admin_international_shipment_manager/add_new_rate_international',
				data: 'courier_company_id=' + courier_company_id +'&reciever_country_id=' + reciever_country_id + '&reciever_zone_id=' + reciever_zone_id + '&weight=' + weight + '&customer_id=' + customer_id + '&qty=' + qty + '&doc=' + doc + '&no_of_pack=' + no_of_pack +'&chargable_weight='+chargable_weight+'&type_export_import='+type_export_import+'&booking_date='+booking_date+'&dispatch_details='+dispatch_details,
				dataType: "json",
				success: function (data) {	
					console.log("weight_range====="+data.db_weight);					
					$('#frieht').val(data.frieht);
					$('#amount').val(data.amount);
					$('#fuel_charges').val(data.final_fuel_charges);
					$('#sub_total').val(data.sub_total);
					$('#cgst').val(data.cgst);
					$('#sgst').val(data.sgst);
					$('#igst').val(data.igst);
					$('#grand_total').val(data.grand_total);
				}
			});
		}else{
			$('#frieht').val();
		}



	});
	var ecs		 = $('#ecs').val();
	$("#frieht,#transportation_charges,#destination_charges,#clearance_charges,#ecs,#other_charges").change(function () 
	{
		var frieht = parseFloat(($('#frieht').val() != '') ? $('#frieht').val() : 0);
		var transportation_charges 	 = parseFloat(($('#transportation_charges').val() != '') ? $('#transportation_charges').val() : 0);
		var destination_charges = parseFloat(($('#destination_charges').val() != '') ? $('#destination_charges').val() : 0);
		var clearance_charges  = parseFloat(($('#clearance_charges').val() != '') ? $('#clearance_charges').val() : 0);
		var ecs		 = parseFloat(($('#ecs').val() != '') ? $('#ecs').val() : 0);
		var other_charges 	 = parseFloat(($('#other_charges').val() != '') ? $('#other_charges').val() : 0);

		var totalAmount = (frieht + transportation_charges + destination_charges + clearance_charges +ecs + other_charges);
		$('#amount').val(totalAmount);

		var courier_id = parseFloat(($('#courier_company').val() != '') ? $('#courier_company').val() : 0);
		var booking_date =$('#booking_date').val();
		var type_export_import = $('input[name="export_import_type"]:checked').val();
		var customer_id   = $('#customer_account_id').val();
		var dispatch_details =$('#dispatch_details').val();

		$.ajax({
				type: 'POST',
				url: 'Admin_international_shipment_manager/getFuelcharges',
				data: 'courier_id='+courier_id +'&sub_amount='+totalAmount+'&booking_date='+booking_date+'&type_export_import='+type_export_import+'&customer_id='+customer_id+'&dispatch_details='+dispatch_details,
				dataType: "json",
				success: function(data) {					
					$('#fuel_charges').val(data.final_fuel_charges);	
					$('#sub_total').val(data.sub_total);
					$('#cgst').val(data.cgst);
					$('#sgst').val(data.sgst);
					$('#igst').val(data.igst);
					$('#grand_total').val(data.grand_total);		
			
				}
			});		
	});
	
	// 	// this is use for adding the new row for in Measurement Units
	$(".add-weight-row").on('click', function () 
	{
		var allTrs = $('table.weight-table tbody').find('tr');
		var lastTr = allTrs[allTrs.length - 1];
		var $clone = $(lastTr).clone();

		var countrows = $(".height").length;
		$clone.find('td').each(function () 
		{
			var el = $(this).find(':first-child');
			var id = el.attr('id') || null;
			if (id) 
			{
				var i = id.substr(id.length - 1);

				var nextElament = countrows; //parseInt(i)+1;
				var remove = 1;
				if (countrows > 10) 
				{
					var remove = 2;
				}
				var removeChar = (id.length - remove);
				var prefix = id.substr(0, removeChar);

				
				//console.log('prefix:::' + prefix + '::::' + id + '::::' + removeChar);
				el.attr('id', prefix + (nextElament));
				el.attr('data-attr', (nextElament));
			}
		});
		$clone.find('input:text').val('');
		$('table.weight-table tbody').append($clone);
		var totalRow = $('table.weight-table tbody').find('tr').length;
		
		if (totalRow > 1) 
		{
			$('.remove-weight-row').show();			
		} else {
			$('.remove-weight-row').hide();
		}
		weightChangeEvent();
	});
		// this fucntion is use for removing the row from table 
	$(".remove-weight-row").on('click', function () 
	{
		var totalRow = $('table.weight-table tbody').find('tr').length;
		if (totalRow > 1) {
			$('table.weight-table tbody').find('tr:last').remove();
			totalRow--;

		}
		if (totalRow == 1) {
			$(this).hide();
		}
		weightChangeEvent();
	
	});
	
	// this function is use for refresh the chrages when weight is changed in input box 
	function weightChangeEvent() 
	{

		$(".no_of_pack, .per_box_weight, .actual_weight, .length, .breath, .height, .one_cft_kg").change(function () {
			
			var idNo = $(this).attr('data-attr');			
				calculateTotalWeight();
				//calulate_charges(idNo);
				//add_doc_rate();
			
		});
		$("#frieht,#transportation_charges,#destination_charges,#clearance_charges,#ecs,#other_charges").change(function () {
			
			var frieht = parseFloat(($('#frieht').val() != '') ? $('#frieht').val() : 0);
			var transportation_charges 	 = parseFloat(($('#transportation_charges').val() != '') ? $('#transportation_charges').val() : 0);
			var destination_charges = parseFloat(($('#destination_charges').val() != '') ? $('#destination_charges').val() : 0);
			var clearance_charges  = parseFloat(($('#clearance_charges').val() != '') ? $('#clearance_charges').val() : 0);
			var ecs		 = parseFloat(($('#ecs').val() != '') ? $('#ecs').val() : 0);
			var other_charges 	 = parseFloat(($('#other_charges').val() != '') ? $('#other_charges').val() : 0);

			var totalAmount = (frieht + transportation_charges + destination_charges + clearance_charges +ecs + other_charges);
			$('#amount').val(totalAmount);

			var courier_id = parseFloat(($('#courier_company').val() != '') ? $('#courier_company').val() : 0);
			var booking_date =$('#booking_date').val();
			var type_export_import = $('input[name="export_import_type"]:checked').val();
			var customer_id   = $('#customer_account_id').val();
			var dispatch_details =$('#dispatch_details').val();

			$.ajax({
					type: 'POST',
					url: 'Admin_international_shipment_manager/getFuelcharges',
					data: 'courier_id='+courier_id +'&sub_amount='+totalAmount+'&booking_date='+booking_date+'&type_export_import='+type_export_import+'&customer_id='+customer_id+'&dispatch_details='+dispatch_details,
					dataType: "json",
					success: function(data) {					
						$('#fuel_charges').val(data.final_fuel_charges);	
						$('#sub_total').val(data.sub_total);
						$('#cgst').val(data.cgst);
						$('#sgst').val(data.sgst);
						$('#igst').val(data.igst);
						$('#grand_total').val(data.grand_total);		
				
					}
				});		

		});
		$("#amount").blur(function () {
			var courier_id = parseFloat(($('#courier_company').val() != '') ? $('#courier_company').val() : 0);			
			var amount = parseFloat(($('#amount').val() != '') ? $('#amount').val() : 0);
				var booking_date =$('#booking_date').val();
				var type_export_import = $('input[name="export_import_type"]:checked').val();
				var customer_id   = $('#customer_account_id').val();
			var dispatch_details =$('#dispatch_details').val();
			
				$.ajax({
					type: 'POST',
					url: 'Admin_international_shipment_manager/getFuelcharges',
					data: 'courier_id='+courier_id +'&sub_amount='+amount+'&booking_date='+booking_date+'&type_export_import='+type_export_import+'&customer_id='+customer_id+'&dispatch_details='+dispatch_details,
					dataType: "json",
					success: function(data) {					
						$('#fuel_charges').val(data.final_fuel_charges);			
				
					}
				});		
			
		});
		
		$("#fuel_charges").blur(function () {			
			var amount = parseFloat(($('#amount').val() != '') ? $('#amount').val() : 0);	
			var fuel_charges = parseFloat(($('#fuel_charges').val() != '') ? $('#fuel_charges').val() : 0);				
			var sub_total =(amount + fuel_charges);		
			var customer_id   = $('#customer_account_id').val();
			var dispatch_details =$('#dispatch_details').val();

			var booking_date =$('#booking_date').val();
			var type_export_import = $('input[name="export_import_type"]:checked').val();

				$.ajax({
					type: 'POST',
					url: 'Admin_international_shipment_manager/getGstCharges',
					data: 'sub_amount='+sub_total+'&booking_date='+booking_date+'&type_export_import='+type_export_import+'&customer_id='+customer_id+'&dispatch_details='+dispatch_details,
					dataType: "json",
					success: function(data) {	
						$('#sub_total').val(sub_total.toFixed(2));				
						$('#cgst').val(data.cgst.toFixed(2));
						$('#sgst').val(data.sgst.toFixed(2));
						$('#igst').val(data.igst.toFixed(2));
						$('#grand_total').val(data.grand_total.toFixed(2));
					}
				});		

		});
			$("#sub_total").blur(function () {			
			var sub_total = parseFloat(($('#sub_total').val() != '') ? $('#sub_total').val() : 0);	
			var booking_date =$('#booking_date').val();
			var type_export_import = $('input[name="export_import_type"]:checked').val();
			var customer_id   = $('#customer_account_id').val();
			var dispatch_details =$('#dispatch_details').val();

				$.ajax({
					type: 'POST',
					url: 'Admin_international_shipment_manager/getGstCharges',
					data: 'sub_amount='+sub_total+'&booking_date='+booking_date+'&type_export_import='+type_export_import+'&customer_id='+customer_id+'&dispatch_details='+dispatch_details,
					dataType: "json",
					success: function(data) {	
						$('#sub_total').val(sub_total.toFixed(2));				
						$('#cgst').val(data.cgst.toFixed(2));
						$('#sgst').val(data.sgst.toFixed(2));
						$('#igst').val(data.igst.toFixed(2));
						$('#grand_total').val(data.grand_total.toFixed(2));
					}
				});		
		});

	}	
	
	function calculateTotalWeight() 
	{
		var totalRow = $('table.weight-table tbody').find('tr').length;	
		var totalActualWeight = 0;
		var totalValumetricWeight = 0;
		var totalLength = 0;
		var totalBreath = 0;
		var totalHeight = 0;
		var totalOneCftKg = 0;
		var totalNoOfPack = 0;
		var totalPerBoxWeight = 0;
	
		for (var i = 1; i <= totalRow; i++) {
			var noOfPackCurrent = parseFloat(($('#no_of_pack' + i).val() != '') ? $('#no_of_pack' + i).val() : 0);
			var perBoxWeightCurrent = parseFloat(($('#per_box_weight' + i).val() != '') ? $('#per_box_weight' + i).val() : 0);
			
			var currentActualWeight = noOfPackCurrent * perBoxWeightCurrent;
			if (currentActualWeight > 0) {
				$('#actual_weight' + i).val(currentActualWeight.toFixed(2));
				$("#chargable_weight" + i).val(currentActualWeight.toFixed(2));
			}

			var length = $("#length" + i).val();
			var breath = $("#breath" + i).val();
			var height = $("#height" + i).val();
			if (length != '' && breath != '' && height != '' && noOfPackCurrent != '') 
			{		
					valumetric_weight = ((length * breath * height) / 5000) * noOfPackCurrent;	
					//console.log('valumetric_weight' + valumetric_weight);	

					total_valumetric_weight = valumetric_weight.toFixed(2);

					$("#valumetric_weight" + i).val(total_valumetric_weight);

					if(total_valumetric_weight > currentActualWeight)
					{
						$("#chargable_weight" + i).val(total_valumetric_weight);
					}else{
						$("#chargable_weight" + i).val(currentActualWeight);
					}

					//calculateTotalWeight();
					//calulateTotal();
			} else {
					$("#valumetric_weight" + i).val('');
			}

			totalNoOfPack = parseFloat(totalNoOfPack) + parseFloat(($('#no_of_pack' + i).val() != '') ? $('#no_of_pack' + i).val() : 0);
			totalPerBoxWeight = parseFloat(totalPerBoxWeight) + parseFloat(($('#per_box_weight' + i).val() != '') ? $('#per_box_weight' + i).val() : 0);
			totalActualWeight = parseFloat(totalActualWeight) + parseFloat(($('#actual_weight' + i).val() != '') ? $('#actual_weight' + i).val() : 0);
			totalValumetricWeight = parseFloat(totalValumetricWeight) + parseFloat(($('#valumetric_weight' + i).val() != '') ? $('#valumetric_weight' + i).val() : 0);

			if(totalValumetricWeight > totalActualWeight)
			{
				$("#chargable_weight").val(totalValumetricWeight.toFixed(2));
			}else{
				$("#chargable_weight").val(totalActualWeight.toFixed(2));
			}

			totalLength = parseFloat(totalLength) + parseFloat(($('#length' + i).val() != '') ? $('#length' + i).val() : 0);
			totalBreath = parseFloat(totalBreath) + parseFloat(($('#breath' + i).val() != '') ? $('#breath' + i).val() : 0);
			totalHeight = parseFloat(totalHeight) + parseFloat(($('#height' + i).val() != '') ? $('#height' + i).val() : 0);
		}
		if (totalNoOfPack) {
			$('#no_of_pack').val(totalNoOfPack);
		}
		if (totalPerBoxWeight) {
			$('#per_box_weight').val(totalPerBoxWeight.toFixed(2));
		}
		if (totalActualWeight) {
			$('#actual_weight').val(totalActualWeight.toFixed(2));
		}
		if (totalValumetricWeight) {
			var roundoff_type = $("#roundoff_type").val();
			// $('#valumetric_weight').val(totalValumetricWeight); ttttttt
			if (roundoff_type == '1') {
				$('#valumetric_weight').val(totalValumetricWeight.toFixed(2));
			} else {
				$('#valumetric_weight').val(totalValumetricWeight.toFixed(2));
			}
		}
			$('#length').val(totalLength.toFixed(2));
			$('#breath').val(totalBreath.toFixed(2));	
			$('#height').val(totalHeight.toFixed(2));
	}
	
	
});
