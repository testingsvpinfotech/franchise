/* Document.ready Start */
// var base_url = window.location.origin + "/";
var base_url = 'http://localhost/franchise/';
jQuery(document).ready(function () {

	/*############################## Basic Validation start ################################## */
	(function ($) {
		$.fn.inputFilter = function (callback, errMsg) {
			return this.on("input keydown keyup mousedown mouseup select contextmenu drop focusout", function (e) {
				if (callback(this.value)) {
					// Accepted value
					if (["keydown", "mousedown", "focusout"].indexOf(e.type) >= 0) {
						$(this).removeClass("input-error");
						this.setCustomValidity("");
					}
					this.oldValue = this.value;
					this.oldSelectionStart = this.selectionStart;
					this.oldSelectionEnd = this.selectionEnd;
				} else if (this.hasOwnProperty("oldValue")) {
					// Rejected value - restore the previous one
					$(this).addClass("input-error");
					this.setCustomValidity(errMsg);
					this.reportValidity();
					this.value = this.oldValue;
					this.setSelectionRange(this.oldSelectionStart, this.oldSelectionEnd);
				} else {
					// Rejected value - nothing to restore
					this.value = "";
				}
			});
		};
	}(jQuery));

	// Integer value allowed only 
	$("#sender_pincode,#sender_contactno,#reciever_pincode,#reciever_contact,#no_of_pack1,.per_box_weight,.manifest_driver_contact,.manifest_coloader_contact").inputFilter(function (value) {
		return /^\d*$/.test(value);    // Allow digits only, using a RegExp
	}, "Only Numbers allowed");

	// Decimal value allowed only 
	$('#invoice_value,#actual_weight,#chargable_weight,.length,.breath,.height,.valumetric_actual').keypress(function (event) {
		if (((event.which != 46 || (event.which == 46 && $(this).val() == '')) ||
			$(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57) || $(this).val().indexOf('.') !== -1 && event.keyCode == 190) {
			event.preventDefault();
		}
	}).on('paste', function (event) {
		event.preventDefault();
	});

	/*############################## Basic Validation End ################################## */
	/*############################## Add/Edit shipment start ############################### */

	// Final Submission 
// Rate calculate on submit 
$("#desabledBTN").click(function() {	
		var bill_type = $('#dispatch_details').val();
		var frieht = $('#frieht').val();
		if(bill_type!='' && frieht !='')
		{
			$('#desabledBTN').prop('disabled', true);
			
			getZone();
			ChargableWeightCalcu();
			ValumetricRowcalcu();
			calculateTotalWeight();
			var franchise_type = $('#franchise_type').val();
			if(franchise_type ==1 || franchise_type ==3){
				if ($('#company_customer').is(':checked') && $('#customer_account_id').val()!='') {
					getBNFCustomerRate(0);
				}else{
					getRate(0);			
				}
			}else{
				getRate(0);
			}
			fuelCalculate();
			$('#desabledBTN').prop('disabled', true);
		}	
});
	// by default value matric row hide 
	$("#volumetric_table").hide();
	// focusing lr only 
	$("#awn").focus();
	

	var courier_company_name = $("#courier_company option:selected").attr('data-id');
	$('#forworder_name').val(courier_company_name);

	$("#courier_company").change(function () {
		var courier_company_name = $("#courier_company option:selected").attr('data-id');
		$('#forworder_name').val(courier_company_name);
	});

	// Add/Edit shipment invoice and FOC validation or Notification 	
	$('#invoice_value').blur(function () {
		var bill_type = $('#dispatch_details').val();
		if (bill_type != 'FOC') {
			var inv = $(this).val();
			if (parseInt(inv) > 0 && 10000000 > parseInt(inv)) {

			}
			else {

				alertify.alert("Invoice value Alert!", "Invoice value should be greater than 0 and less than 1 Crore. </br> If not available please enter 1 ₹",
					function () {
						alertify.success('Ok');
					});
				// $('#invoice_value').val('');
			}
			$('#invoice_value').prop('required', true);

		}
		else {
			var customer_account_id = $('#customer_account_id').val();
			if (customer_account_id == '118') {
				$('#frieht').prop('required', false);
			}
			$('#invoice_value').prop('required', false);
		}
	});

	// getting customer info 
	$("#customer_account_id").change(function () {
		var customer_name = $(this).val();
		if (customer_name != null || customer_name != '') {
			$.ajax({
				type: 'POST',
				dataType: "json",
				url: base_url + 'Franchise_manager/getsenderdetails',
				data: 'customer_name=' + customer_name,
				success: function (data) {
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
					var dispatch_details = $("#dispatch_details").val();
					// CFT Calcultaion 
					if (dispatch_details != "Cash") {
						var cft = parseFloat($("#cft").val());
						if (isNaN(cft)) { var cft = 0; }
						if (cft == 0 || cft == '') {
							calculate_cft();
						}
					}
					document.getElementById("reciever").focus();
				}
			});
		}
	});

	////Consignee   Pincode
	$("#sender_pincode").on('blur', function () {
		var pincode = $(this).val();
		if (pincode != '') {
			$.ajax({
				type: 'POST',
				url: base_url + 'Franchise_manager/getState',
				data: 'pincode=' + pincode,
				dataType: "json",
				success: function (d) {
					var option;
					option += '<option value="' + d.result3.id + '">' + d.result3.state + '</option>';
					$('#sender_state').html(option);
				}
			});

			$.ajax({
				type: 'POST',
				url: base_url + 'Franchise_manager/getCityList',
				data: 'pincode=' + pincode,
				dataType: "json",
				success: function (d) {
					// console.log(d.result2.city);     
					var option;
					option += '<option value="' + d.id + '">' + d.city + '</option>';
					$('#sender_city').html(option);
				}
			});
		}
	});

	//Consignee  Pincode
	$("#reciever_pincode").on('blur', function () {
		var pincode = $(this).val();
		if (pincode != '') {
			$.ajax({
				type: 'POST',
				url: base_url + 'Franchise_manager/getState',
				data: 'pincode=' + pincode,
				dataType: "json",
				success: function (d) {
					var option;
					option += '<option value="' + d.result3.id + '">' + d.result3.state + '</option>';
					$('#reciever_state').html(option);
					var oda = '';
					oda += '<span style="color:red;">Service Type : ' + d.oda + '</span>';
					$('#oda').html(oda);
				},
				error: function () {
					$('#oda').html('<p>Service Not Available</p>');
				}
			});

			$.ajax({
				type: 'POST',
				url: base_url + 'Franchise_manager/getCityList',
				data: 'pincode=' + pincode,
				dataType: "json",
				success: function (d) {
					// console.log(d.result2.city);     
					var option;
					option += '<option value="' + d.id + '">' + d.city + '</option>';
					$('#reciever_city').html(option);
					getZone();
					var cft = parseFloat($("#cft").val());
						if (isNaN(cft)) { var cft = 0; }
						if (cft == 0 || cft == '') {
							calculate_cft();
						}
				}
			});
		}
	});


	//Consignee  Zone
	function getZone() {
		var reciever_state = $("#reciever_state").val();
		var reciever_city = $("#reciever_city").val();
		$.ajax({
			type: 'POST',
			url: base_url + 'Franchise_manager/getZone',
			data: { reciever_state: reciever_state, reciever_city: reciever_city },
			dataType: "json",
			success: function (d) {
				if (d) {
					$("#receiver_zone_id").val(d.region_id);
					$("#receiver_zone").val(d.region_name);
				} else {
					$("#receiver_zone_id").val(0);
					$("#receiver_zone").val('N/A');
				}

			}
		});
	}

	//   value matric show
	$("#doc_typee").on('change', function () {
		var doc_typee = $('#doc_typee').val();
		if (doc_typee != '') {
			$("#volumetric_table").show();
		}
		else {
			$("#volumetric_table").hide();
		}
	});

	// Gst No validated formate 
	$('#receiver_gstno').on('blur', function() {
        var gstin = $(this).val();
        var regex = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[0-9A-Z]{1}[Z]{1}[0-9A-Z]{1}$/;
        if(gstin !=''){
		if (regex.test(gstin)) {
            $('#validation_message').text('Valid GSTIN').css('color', 'green');
        } else {
            $('#validation_message').text('Invalid GSTIN').css('color', 'red');
        }
		}
    });

	/* Add Shipment and also edit shipment in BNF PORTAL
		it's Creadting value matric row according no_of_pack (PKT) in details =>
		1. add shipment case first entering no_of_pack and value matric details 
		2. and change no_of_pack according no_of_pack genrate and remove are working but is_valuemetric check box checked required.
		3. edit shipment check box not check that's why not working  
		*/

	// Remove method it's work dynamicly count and status 
	function RemoveRow(rowCount, pkt, totalRow, status) {

		if (status == 0) {
			sum = 0;
			for (var jk = 1; jk < (totalRow); jk++) {
				console.log(jk);
				qty = $('#per_box_weight' + jk).val()
				if (qty < pkt) {
					sum += qty;
					continue;
				} else {
					$('#volumetric_table_row').find('tr:last').remove();
				}
				if (sum <= pkt) {
					sum += qty;
					continue;
				} else {
					$('#volumetric_table_row').find('tr:last').remove();
				}
				//totalRow--;
			}
		} else {
			for (let i = 1; i <= rowCount; i++) {
				d3 = $('#per_box_weight' + i).val();
				if (!d3 || d3 == '' || d3 == '0') {
					$('#per_box_weight' + i).closest('tr').remove();
				}

			}
		}
	}


	//  add row dynamicly value matric row 
	function AddRow(rowCount) {
		for (var i = 0; i < rowCount; i++) {

			var allTrs = $('table.weight-table tbody').find('tr');

			var lastTr = allTrs[allTrs.length - 1];
			var $clone = $(lastTr).clone();
			var countrows = $(".height").length;
			// console.log(countrows);
			$clone.find('td').each(function () {
				var el = $(this).find(':first-child');
				var id = el.attr('id') || null;
				if (id) {
					var i = id.substr(id.length - 1);

					var nextElament = countrows; //parseInt(i)+1;
					var remove = 1;
					if (countrows > 10) {
						var remove = 2;
					}
					var removeChar = (id.length - remove);
					var prefix = id.substr(0, removeChar);


					//console.log('prefix:::' + prefix + '::::' + id + '::::' + removeChar);
					el.attr('id', prefix + (nextElament));
					el.attr('data-attr', (nextElament));
					el.attr('id', prefix + (nextElament));
					el.attr('data-attr', (nextElament));
					el.prop('required', true);
					el.val('');

				}
			});
			$clone.find('input:text').val('');
			$('table.weight-table tbody').append($clone);
			var totalRow = $('table.weight-table tbody').find('tr').length;

			if (totalRow > 1) {
				$('.remove-weight-row').show();
			} else {
				$('.remove-weight-row').hide();
			}
		}
	}

	//  get blank row in value matric rows
	function row_sum(id) {
		sum = $('#per_box_weight' + id).val() + $('#length' + id).val() + $('#breath' + id).val() + $('#height' + id).val() + $('#valumetric_weight' + id).val() + $('#valumetric_actual' + id).val() + $('#valumetric_chageable' + id).val()
		return parseInt(sum);
	}

	// this function is use for getting charges acording chargeble weight input field 
	$("#valumetric_chageable").blur(function () {
		var franchise_type = $('#franchise_type').val();
		if(franchise_type ==1 || franchise_type ==3){
			if ($('#company_customer').is(':checked') && $('#customer_account_id').val()!='') {
				getBNFCustomerRate(0);
			}else{
				getRate(0);				
			}
		}else{
			getRate(0);
		}
	});


	function getBNFCustomerRate(update) {
		if ($('#is_appointment').is(':checked')) {
			var is_appointment = 1;
		}
		else {
			var is_appointment = 0;
		}
		if ($('#door_delivery').is(':checked')) {
			var door_delivery = 1;
		}
		else {
			var door_delivery = 0;
		}
	
		var customer_id = $('#customer_account_id').val();
		var c_courier_id = $('#courier_company').val();
		var mode_id = $('#mode_dispatch').val();
		var sender_state = $("#sender_state").val();
		var sender_city = $("#sender_city").val();
		var state = $("#reciever_state").val();
		var city = $("#reciever_city").val();
		var doc_type = $("#doc_typee").val();
		var franchise_id = $("#franchise_id").val();
		var receiver_zone_id = $("#receiver_zone_id").val();
		var receiver_gstno = $("#receiver_gstno").val();
		var booking_date = $('#booking_date').val();
		var dispatch_details = $('#dispatch_details').val();
		var invoice_value = $('#invoice_value').val();
		var invoice_value = parseFloat(($('#invoice_value').val() != '') ? $('#invoice_value').val() : 0);

		var chargable_weight = parseFloat($('#chargable_weight').val()) > 0 ? $('#chargable_weight').val() : 0;
		var actual_weight = parseFloat($('#actual_weight').val()) > 0 ? $('#actual_weight').val() : 0;
		let packet = $("#no_of_pack1").val();
		if (dispatch_details == 'ToPay' && invoice_value == '') {
			alert('Please Fillup Inv. Value*');
		}
		// if (chargable_weight > 0) {
		if (customer_id != '' && mode_id != '') {
			$.ajax({
				type: 'POST',
				url:  base_url + 'Franchise_manager/getBNFCustomerRate',
				data: 'packet=' + packet + '&customer_id=' + customer_id + '&c_courier_id=' + c_courier_id + '&mode_id=' + mode_id + '&state=' + state + '&city=' + city + '&chargable_weight=' + chargable_weight + '&receiver_zone_id=' + receiver_zone_id + '&receiver_gstno=' + receiver_gstno + '&booking_date=' + booking_date + '&invoice_value=' + invoice_value + '&dispatch_details=' + dispatch_details + '&sender_state=' + sender_state + '&sender_city=' + sender_city + '&is_appointment=' + is_appointment + '&actual_weight=' + actual_weight+'&door_delivery='+door_delivery+'&franchise_id='+franchise_id,
				dataType: "json",
				success: function (data) {

					console.log(data);
					$('#frieht').val(data.frieht);
					if (update) {

					} else {

					}
					if (data.frieht == '0') {
						$('#frieht').val('');
						// alert(data.frieht);
						var table_row = $('#volumetric_table_row tr').length;
						getPerBox_fright(table_row);
					} else {
						$('#rate_display').html('Rate Apply Successfully').css('color','green');
                        //  charges 
						$('#frieht1').val(data.frieht);
						$('#awb_charges1').val(data.docket_charge);
						$('#rate').val(data.rate);
						$('#fov_charges').val(data.fov)
						$('#appt_charges').val(data.appt_charges);
						$('#fuel_charges').val(data.final_fuel_charges);
						$('#green_tax').val(data.to_pay_charges);
						$('#courier_charges').val(data.cod);

						// commision master 
						
						$('#booking_comission').val(data.booking_commsion);
						$('#delivery_commission').val(data.delivery_commission);
						$('#door_delivery_share').val(data.door_delivery_share);
						$('#booking_charges').val(data.booking_charges);
						$('#pickup_charges').val(data.pickup_charges);
						$('#delivery_ccharges').val(data.delivery_charges);
						$('#door_delivery_charges').val(data.door_delivery_charges);
						$('#fuel_charges1').val(data.final_fuel_charges);
						$('#sub_total').val(data.sub_total);
						$('#amount1').val(data.amount);
						$('#cgst').val(data.cgst);
						$('#sgst').val(data.sgst);
						$('#igst').val(data.igst);
						$('#grand_total').val(data.grand_total);
						$('#cft').val(data.cft);
						$('#isMinimumValue').html(data.isMinimumValue);
						if (data.fovExpiry) {
							alert(data.fovExpiry);
							// $("#desabledBTN").attr();
							$('#desabledBTN').prop('disabled', true);

						} else {
							// $('#desabledBTN').prop('disabled', false);
						}


					}
				},
				error: function () {
					$('#frieht').val('');
					// alert(data.frieht);
					var table_row = $('#volumetric_table_row tr').length;
					getPerBox_fright(table_row);
				}
			});
		}
		else {
			$('#frieht').val();
		}
	}

	function getPerBox_fright(update) {
		if (update > 0) {
			var non_of_pack = [];
			var actual_w = [];

			for (var jk = 1; jk <= update; jk++) {

				if ($('#valumetric_actual' + jk).val() != '' || $('#valumetric_actual' + jk).val() != null) {
					var a_w = [];
					a_w[jk] = $('#valumetric_actual' + jk).val();
					actual_w.push(a_w);
				}
				if ($('#per_box_weight' + jk).val() != '' || $('#per_box_weight' + jk).val() != null) {
					var no = [];
					no[jk] = $('#per_box_weight' + jk).val();
					non_of_pack.push(no);
				}
			}
			// ChargableWeightCalcu();
			if ($('#is_appointment').is(':checked')) {
				var is_appointment = 1;
			}
			else {
				var is_appointment = 0;
			}
			if ($('#door_delivery').is(':checked')) {
				var door_delivery = 1;
			}
			else {
				var door_delivery = 0;
			}
			
			var customer_id = $('#customer_account_id').val();
			var c_courier_id = $('#courier_company').val();
			var mode_id = $('#mode_dispatch').val();
			var sender_state = $("#sender_state").val();
			var sender_city = $("#sender_city").val();
			var state = $("#reciever_state").val();
			var city = $("#reciever_city").val();
			var doc_type = $("#doc_typee").val();
			var franchise_id = $("#franchise_id").val();
			var receiver_zone_id = $("#receiver_zone_id").val();
			var receiver_gstno = $("#receiver_gstno").val();
			var booking_date = $('#booking_date').val();
			var dispatch_details = $('#dispatch_details').val();
			var invoice_value = $('#invoice_value').val();
			var invoice_value = parseFloat(($('#invoice_value').val() != '') ? $('#invoice_value').val() : 0);
	
			var chargable_weight = parseFloat($('#chargable_weight').val()) > 0 ? $('#chargable_weight').val() : 0;
			var actual_weight = parseFloat($('#actual_weight').val()) > 0 ? $('#actual_weight').val() : 0;
			let packet = $("#no_of_pack1").val();
			if (dispatch_details == 'ToPay' && invoice_value == '') {
				alert('Please Fillup Inv. Value*');
			}
			// if (chargable_weight > 0) {
			if (customer_id != '' && mode_id != '') {
				$.ajax({
					type: 'POST',
					url: base_url + 'Franchise_manager/get_perbox_rate',
					data: 'packet=' + packet + '&customer_id=' + customer_id + '&c_courier_id=' + c_courier_id + '&mode_id=' + mode_id + '&state=' + state + '&city=' + city + '&chargable_weight=' + chargable_weight + '&receiver_zone_id=' + receiver_zone_id + '&receiver_gstno=' + receiver_gstno + '&booking_date=' + booking_date + '&invoice_value=' + invoice_value + '&dispatch_details=' + dispatch_details + '&sender_state=' + sender_state + '&sender_city=' + sender_city + '&is_appointment=' + is_appointment+ '&per_box=' + non_of_pack + '&perBox_actual=' + actual_w + '&actual_weight=' + actual_weight+'&door_delivery='+door_delivery+'&franchise_id='+franchise_id,
					dataType: "json",
					success: function (data) {
						if (data.rate_message != '') {
							alert(data.rate_message);
						}
						if (data.Message == 'Rate Not defined Please check Rate') {
							alert(data.Message);
						}
						else {

							$('#frieht').val(data.frieht);
							
							if (data.frieht > 0) {
										 //  charges 
									$('#rate_display').html('Rate Apply Successfully').css('color','green');
									$('#frieht1').val(data.frieht);
									$('#awb_charges1').val(data.docket_charge);
									$('#rate').val(data.rate);
									$('#fov_charges').val(data.fov)
									$('#appt_charges').val(data.appt_charges);
									$('#fuel_charges').val(data.final_fuel_charges);
									$('#green_tax').val(data.to_pay_charges);
									$('#courier_charges').val(data.cod);

									// commision master 
									
									$('#booking_comission').val(data.booking_commsion);
									$('#delivery_commission').val(data.delivery_commission);
									$('#door_delivery_share').val(data.door_delivery_share);
									$('#booking_charges').val(data.booking_charges);
									$('#pickup_charges').val(data.pickup_charges);
									$('#delivery_ccharges').val(data.delivery_charges);
									$('#door_delivery_charges').val(data.door_delivery_charges);
									$('#fuel_charges1').val(data.final_fuel_charges);
									$('#sub_total').val(data.sub_total);
									$('#amount1').val(data.amount);
									$('#cgst').val(data.cgst);
									$('#sgst').val(data.sgst);
									$('#igst').val(data.igst);
									$('#grand_total').val(data.grand_total);
									$('#cft').val(data.cft);
									$('#isMinimumValue').html(data.isMinimumValue);
									var actual_weight = $('#actual_weight').val();
									var chargable_weight = $('#chargable_weight').val();
									var val_actual = $('#valumetric_actual').val();
									if (chargable_weight == '') {

									if (val_actual > actual_weight) {
										$('#chargable_weight').val(val_actual);
									}
									else if (actual_weight < data.min_weight) {
										$('#chargable_weight').val(data.min_weight);
									}
									else {
										$('#chargable_weight').val(actual_weight);
									}

								}
								
								if (data.fovExpiry) {
									alert(data.fovExpiry);
									// $("#desabledBTN").attr();
									$('#desabledBTN').prop('disabled', true);

								}
							} else {
								$('#frieht').val('');
							}
						}
					},
					error: function () {
						$('#frieht').val('');
						$('#rate_display').html('Rate Not Define. please contact to customer.').css('color','red');
						resetCharges();
					}
				});
			}
			else {
				$('#frieht').val();
			}
			// } else {
			// 	$('#frieht').val('');
			// }
		}
	}

	function resetCharges(){
		$('#frieht1').val('');
		$('#awb_charges1').val('');
		$('#rate').val('');
		$('#fov_charges').val('');
		$('#appt_charges').val('');
		$('#fuel_charges').val('');
		$('#green_tax').val('');
		$('#courier_charges').val('');
		// commision master 
		$('#booking_comission').val('');
		$('#delivery_commission').val('');
		$('#door_delivery_share').val('');
		$('#booking_charges').val('');
		$('#pickup_charges').val('');
		$('#delivery_ccharges').val('');
		$('#door_delivery_charges').val('');
		$('#fuel_charges1').val('');
		$('#sub_total').val('');
		$('#amount1').val('');
		$('#cgst').val('');
		$('#sgst').val('');
		$('#igst').val('');
		$('#grand_total').val('');
	}

    // walking customer and franchise prepaid rate
	function getRate(update) {
		
		var customer_id = $('#customer_account_id').val();
		var c_courier_id = $('#courier_company').val();
		var mode_id = $('#mode_dispatch').val();
		var sender_state = $("#sender_state").val();
		var sender_city = $("#sender_city").val();
		var state = $("#reciever_state").val();
		var city = $("#reciever_city").val();
		var doc_type = $("#doc_typee").val();
		var receiver_zone_id = $("#receiver_zone_id").val();
		var receiver_gstno = $("#receiver_gstno").val();
		var booking_date = $('#booking_date').val();
		var dispatch_details = $('#dispatch_details').val();
		var region_id = $('#region_id').val();
		var invoice_value = parseFloat(($('#invoice_value').val() != '') ? $('#invoice_value').val() : 0);
		var chargable_weight = parseFloat($('#chargable_weight').val()) > 0 ? $('#chargable_weight').val() : 0;

		var franchise_type = $('#franchise_type').val();
		var bnf_customer = $('.bnf_customer').val();

		if(franchise_type== 2){
			if (mode_id != '') {
				$.ajax({
					type: 'POST',
					url: base_url + 'Franchise_manager/add_new_rate_domestic',
					data: 'customer_id=' + customer_id + '&c_courier_id=' + c_courier_id + '&mode_id=' + mode_id + '&state=' + state + '&city=' + city + '&chargable_weight=' + chargable_weight + '&receiver_zone_id=' + receiver_zone_id + '&receiver_gstno=' + receiver_gstno + '&booking_date=' + booking_date + '&invoice_value=' + invoice_value + '&dispatch_details=' + dispatch_details + '&sender_state=' + sender_state + '&sender_city=' + sender_city + "&region_id=" + region_id + "&doc_type=" + doc_type,
					dataType: "json",
					success: function (data) {
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
						$('#cgst').val(data.cgst);
						$('#sgst').val(data.sgst);
						$('#igst').val(data.igst);
						$('#awb_charges').val(data.docket_charge);
						$('#fov_charges').val(data.fov);
						$('#appt_charges').val(data.appt_charges);
						$('#grand_total').val(data.grand_total);
						$('#cft').val(data.cft);
						$('#delivery_date').val(data.tat_date);
						// shipmentGST_calcu();

					}
				});
			}
			else {
				$('#frieht').val();
				alertify.alert("Shipment Mode Alert!","Please Select mode",
				function () {
					alertify.success('Ok');
				});
			}
	    }else{
			if (mode_id == '') {
					$('#frieht').val();
					alertify.alert("Shipment Mode Alert!","Please Select mode",
					function () {
						alertify.success('Ok');
					});
			}else{
				if(bnf_customer=="" && $('#company_customer').is(":checked")){				
					alertify.alert("Shipment Rate Alert!","Customer Not Selected . <br>Please define customer franchise customer or BNF.",
					function () {
						alertify.success('Ok');
					});
			    }else{				
					$.ajax({
						type: 'POST',
						url: base_url + 'Franchise_manager/add_new_rate_domestic',
						data: 'customer_id=' + customer_id + '&c_courier_id=' + c_courier_id + '&mode_id=' + mode_id + '&state=' + state + '&city=' + city + '&chargable_weight=' + chargable_weight + '&receiver_zone_id=' + receiver_zone_id + '&receiver_gstno=' + receiver_gstno + '&booking_date=' + booking_date + '&invoice_value=' + invoice_value + '&dispatch_details=' + dispatch_details + '&sender_state=' + sender_state + '&sender_city=' + sender_city + "&region_id=" + region_id + "&doc_type=" + doc_type,
						dataType: "json",
						success: function (data) {
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
							$('#cgst').val(data.cgst);
							$('#sgst').val(data.sgst);
							$('#igst').val(data.igst);
							$('#awb_charges').val(data.docket_charge);
							$('#fov_charges').val(data.fov);
							$('#appt_charges').val(data.appt_charges);
							$('#grand_total').val(data.grand_total);
							$('#cft').val(data.cft);
							$('#delivery_date').val(data.tat_date);
						}
					});
				}
			}
			
		}
	}

	// charges or fuel calculate 
	$("#frieht,#transportation_charges,#pickup_charges,#delivery_charges,#courier_charges,#awb_charges,#other_charges,#insurance_charges,#green_tax,#appt_charges").change(function () {
		fuelCalculate();
	});
	function fuelCalculate() {
		var frieht = parseFloat(($('#frieht').val() != '') ? $('#frieht').val() : 0);
		var delivery_charges = parseFloat(($('#delivery_charges').val() != '') ? $('#delivery_charges').val() : 0);
		var courier_charges = parseFloat(($('#courier_charges').val() != '') ? $('#courier_charges').val() : 0);
		var awb_charges = parseFloat(($('#awb_charges').val() != '') ? $('#awb_charges').val() : 0);
		var other_charges = parseFloat(($('#other_charges').val() != '') ? $('#other_charges').val() : 0);
		var fov_charges = parseFloat(($('#fov_charges').val() != '') ? $('#fov_charges').val() : 0);
		var totalAmount = (
			frieht + delivery_charges + courier_charges + awb_charges + other_charges + fov_charges
		);
		$('#amount').val(totalAmount);
		var courier_id = $('#courier_company').val();
		var booking_date = $('#booking_date').val();
		var customer_id = $('#customer_account_id').val();
		var dispatch_details = $('#dispatch_details').val();
		// 	alert(dispatch_details);
		$.ajax({
			type: 'POST',
			url: base_url + 'Franchise_manager/getFuelcharges',
			data: 'courier_id=' + courier_id + '&sub_amount=' + totalAmount + '&booking_date=' + booking_date + '&customer_id=' + customer_id + '&dispatch_details=' + dispatch_details,
			dataType: "json",
			success: function (data) {

				$('#fuel_charges').val(data.final_fuel_charges);
				$('#sub_total').val(data.sub_total);
				$('#cgst').val(data.cgst);
				$('#sgst').val(data.sgst);
				$('#igst').val(data.igst);
				$('#grand_total').val(data.grand_total);

			}
		});
	}


	// valuematrix calculation 
	function ValumetricRowcalcu() {
		var idNo = $(this).attr('data-attr');
		var id = $(this).attr('id');
		// calculating value matric weight 

		calculateTotalWeight();
		ChargableWeightCalcu();
		if (id == 'per_box_weight' + idNo) {
			var table2 = $(this).closest('table');
			var rowCount2 = $('#volumetric_table #volumetric_table_row tr').length;
			val = parseInt($('#' + id).val());
			tot = parseInt($('#no_of_pack1').val());
			// +"  -- row "+idNo
			var sum = 0;
			// this getting sum of value matric packet order by desc row 
			for (let i = idNo; i > 0; i--) {
				sum = sum + parseInt($('#per_box_weight' + i).val());
			}
			// if sum are greater that case remove Row Last TR one by one
			if (sum >= tot) {
				dd = sum - tot;
				if (val > dd) { $(this).val(val - dd); }
				if (dd > val) { $(this).val(dd - val); }

				var rm_tr = tot - idNo;
				if (rm_tr) {
					for (let i = 0; i < rm_tr; i++) {
						$(this).closest('tr').next().remove();
					}
				}
			}
			else {
				var table = $(this).closest('table');
				var rowCount = $('#volumetric_table #volumetric_table_row tr').length;
				if (tot > rowCount) {
					var totalRow = $('#volumetric_table_row').find('tr').length;
					console.log(totalRow);
					var sum = 0;
					var total_blank_row = 0;
					var checkValForEmprtyRow
					// this getting sum of value matric packet order by desc row 
					for (let i = totalRow; i > 0; i--) {
						var totalbox = parseInt($('#per_box_weight' + i).val());
						var chk_blank = row_sum(i);
						if (isNaN(totalbox)) { totalbox = 0; }
						if (isNaN(chk_blank)) { total_blank_row += 1; }
						sum = sum + totalbox;
					}
					chk_blank = null;
					// alert(total_blank_row); 
					var pkt = parseFloat($("#no_of_pack1").val());
					var pktDiff = Math.abs(pkt - sum);
					if (pktDiff >= total_blank_row) {
						addRowCount = pktDiff - total_blank_row;
						AddRow(addRowCount);
					}
					if (pkt != '' && pkt != 0 && totalRow > pktDiff) {
						rowCount = total_blank_row - pktDiff;
						// RemoveRow(rowCount, 1);
						RemoveRow(rowCount, pkt, totalRow, 1);
					}

				}
			}
		}
		$('#per_box_weight' + (idNo + 1)).trigger('blur');
	}

	// chkceing duplicate number
	$("#awn").blur(function () {
		var pod_no = $(this).val();
		if (pod_no != null || pod_no != '') {
			$.ajax({
				type: 'POST',
				dataType: "json",
				url: base_url + 'Franchise_manager/check_duplicate_awb_no',
				data: 'pod_no=' + pod_no,
				success: function (data) {
					if (data.msg != "") {
						$('#awn').focus();
						$('#awn').val("");
						alert(data.msg);
					} else {
					}

				}
			});
		}
	});

	// doc type change 
	$("#doc_typee").change(function () {
		var shipment = $("#doc_typee").val();
		if (shipment == 1) {
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
		} else {
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

	$("#reciever").blur(function () {
		var reciever = $(this).val();
		$('#contactperson_name').val(reciever);

	});

	// /this is use for getting receiver city info by pincode
	$("#no_of_pack1").on('blur', function () {
		var no_of_pack1 = $('#no_of_pack1').val();
		if (no_of_pack1 != 0 || no_of_pack1 != '0') {
			$("#volumetric_table").show();
			var totalRow = $('#volumetric_table_row').find('tr').length;
			console.log(totalRow);
			var sum = 0;
			var total_blank_row = 0;
			var checkValForEmprtyRow
			// this getting sum of value matric packet order by desc row 
			for (let i = totalRow; i > 0; i--) {
				var totalbox = parseInt($('#per_box_weight' + i).val());
				var chk_blank = row_sum(i);
				if (isNaN(totalbox)) { totalbox = 0; }
				if (isNaN(chk_blank)) { total_blank_row += 1; }
				sum = sum + totalbox;
			}
			chk_blank = null;
			// alert(total_blank_row); 
			var pkt = parseFloat($("#no_of_pack1").val());
			var pktDiff = Math.abs(pkt - sum);
			if (pktDiff >= totalRow) {
				addRowCount = pktDiff - total_blank_row;
				AddRow(addRowCount);
			}

			if (pkt != '' && pkt != 0 && totalRow > pktDiff) {
				//  rowCount = totalRow - pktDiff;
				rowCount = total_blank_row - pktDiff;
				// rowCount = (total_blank_row == pktDiff) ? 0 : total_blank_row - pktDiffs	
				RemoveRow(rowCount, pkt, totalRow, 0);
			}
		}
	});

	// valumetric grater weight calculate 
	$(document).on("blur", '.valumetric_actual', function () {

		var idNo = $(this).attr('data-attr');
		var id = $(this).attr('id');
		var val = $(this).val();

		if (!val) {
			val = 0;
		}

		val = parseFloat(val);

		valumetric_weight = parseFloat($("#valumetric_weight" + idNo).val());

		if (valumetric_weight > val) {
			$('#valumetric_chageable' + idNo).val(valumetric_weight);
		} else {
			$('#valumetric_chageable' + idNo).val(val);
		}

	});


	$(document).on("blur", '.per_box_weight, .length, .breath, .height', function () {
		var idNo = $(this).attr('data-attr');
		var id = $(this).attr('id');
		// calculating value matric weight 

		calculateTotalWeight();
		ChargableWeightCalcu();
		if (id == 'per_box_weight' + idNo) {
			var table2 = $(this).closest('table');
			var rowCount2 = $('#volumetric_table #volumetric_table_row tr').length;
			val = parseInt($('#' + id).val());
			tot = parseInt($('#no_of_pack1').val());
			// +"  -- row "+idNo
			var sum = 0;
			// this getting sum of value matric packet order by desc row 
			for (let i = idNo; i > 0; i--) {
				sum = sum + parseInt($('#per_box_weight' + i).val());
			}
			// if sum are greater that case remove Row Last TR one by one
			if (sum >= tot) {
				dd = sum - tot;
				if (val > dd) { $(this).val(val - dd); }
				if (dd > val) { $(this).val(dd - val); }

				var rm_tr = tot - idNo;
				if (rm_tr) {
					for (let i = 0; i < rm_tr; i++) {
						$(this).closest('tr').next().remove();
					}
				}
			}
			else {
				var table = $(this).closest('table');
				var rowCount = $('#volumetric_table #volumetric_table_row tr').length;
				if (tot > rowCount) {
					var totalRow = $('#volumetric_table_row').find('tr').length;
					console.log(totalRow);
					var sum = 0;
					var total_blank_row = 0;
					var checkValForEmprtyRow
					// this getting sum of value matric packet order by desc row 
					for (let i = totalRow; i > 0; i--) {
						var totalbox = parseInt($('#per_box_weight' + i).val());
						var chk_blank = row_sum(i);
						if (isNaN(totalbox)) { totalbox = 0; }
						if (isNaN(chk_blank)) { total_blank_row += 1; }
						sum = sum + totalbox;
					}
					chk_blank = null;
					// alert(total_blank_row); 
					var pkt = parseFloat($("#no_of_pack1").val());
					var pktDiff = Math.abs(pkt - sum);
					if (pktDiff >= total_blank_row) {
						addRowCount = pktDiff - total_blank_row;
						AddRow(addRowCount);
					}

					if (pkt != '' && pkt != 0 && totalRow > pktDiff) {
						rowCount = total_blank_row - pktDiff;
						// RemoveRow(rowCount, 1);
						RemoveRow(rowCount, pkt, totalRow, 1);
					}

				}
			}
		}

		$('#per_box_weight' + (idNo + 1)).trigger('blur');
	});

	//  calcualte min weight auto 
	function ChargableWeightCalcu() {
		var data = parseFloat($("#min_weight").val());
		var chargable_weight = parseFloat($("#chargable_weight").val());
		var valumetric_chageable = parseFloat($("#valumetric_chageable").val());
		var actual_weight = parseFloat($("#actual_weight").val());
		if (isNaN(data)) {
			var data = 0;
		}
		if (isNaN(actual_weight)) {
			var actual_weight = 0;
		}
		if (isNaN(chargable_weight)) {
			var chargable_weight = 0;
		}
		if (isNaN(valumetric_chageable)) {
			var valumetric_chageable = 0;
		}
		// Admin edit shipment 
		if (valumetric_chageable != 0) {
			if (data >= actual_weight && data >= valumetric_chageable) {
				$("#chargable_weight").val(data);
			}
			else {
				if (actual_weight >= valumetric_chageable) {
					if (data <= actual_weight) {
						$("#chargable_weight").val(actual_weight);
					}
					else {
						$("#chargable_weight").val(data);
					}
				}
				else {
					if (data <= valumetric_chageable) {
						$("#chargable_weight").val(valumetric_chageable);
					}
					else {
						$("#chargable_weight").val(data);
					}
				}
			}
		}
		else {
			if (data <= actual_weight) {
				$("#chargable_weight").val(actual_weight);
			}
			else {
				$("#chargable_weight").val(data);
			}
		}
	}

	// value matric weight calculation and total weight 
	function calculateTotalWeight() {
		var totalRow = $('table.weight-table tbody').find('tr').length;
		var totalActualWeight = 0;
		var totalValumetricWeight = 0;
		var totalLength = 0;
		var totalBreath = 0;
		var totalHeight = 0;
		var totalOneCftKg = 0;
		var totalNoOfPack = 0;
		var totalPerBoxWeight = 0;
		var valumetric_chageable = 0;
		var valumetric_actual = 0;

		var mode_dispatch = $('#mode_dispatch').val();
		var currentActualWeight = $('#actual_weight').val();


		for (var i = 1; i <= totalRow; i++) {

			var perBoxWeightCurrent = $('#per_box_weight' + i).val();
			var length = $("#length" + i).val();
			var breath = $("#breath" + i).val();
			var height = $("#height" + i).val();

			if (length != '' && breath != '' && height != '') {

				if (mode_dispatch != 1) {
					cft = $('#cft').val();
					// if (cft==0 || cft=='0') {cft=7}
					valumetric_weight = (((length * breath * height) / 27000) * cft) * perBoxWeightCurrent;
				}
				else {
					air_cft = $('#air_cft').val();
					// if (air_cft==0 || air_cft=='0') {air_cft=5000}
					valumetric_weight = ((length * breath * height) / air_cft) * perBoxWeightCurrent;
				}

				total_valumetric_weight = valumetric_weight.toFixed(2);
				$("#valumetric_weight" + i).val(total_valumetric_weight);

				dd = $("#valumetric_actual" + i).val();

				if (!dd) {
					$("#valumetric_actual" + i).val(total_valumetric_weight);
				}

			}
			else {
				$("#valumetric_weight" + i).val('');
			}

			totalValumetricWeight = parseFloat(totalValumetricWeight) + parseFloat(($('#valumetric_weight' + i).val() != '') ? $('#valumetric_weight' + i).val() : 0);
			totalPerBoxWeight = parseFloat(totalPerBoxWeight) + parseFloat(($('#per_box_weight' + i).val() != '') ? $('#per_box_weight' + i).val() : 0);
			totalLength = parseFloat(totalLength) + parseFloat(($('#length' + i).val() != '') ? $('#length' + i).val() : 0);
			totalBreath = parseFloat(totalBreath) + parseFloat(($('#breath' + i).val() != '') ? $('#breath' + i).val() : 0);
			totalHeight = parseFloat(totalHeight) + parseFloat(($('#height' + i).val() != '') ? $('#height' + i).val() : 0);
			valumetric_chageable = parseFloat(valumetric_chageable) + parseFloat(($('#valumetric_chageable' + i).val() != '') ? $('#valumetric_chageable' + i).val() : 0);
			valumetric_actual = parseFloat(valumetric_actual) + parseFloat(($('#valumetric_actual' + i).val() != '') ? $('#valumetric_actual' + i).val() : 0);
		}

		var totalActualWeight = $('#actual_weight').val();
		var totalchargable_weight = $('#chargable_weight').val();

		if (totalValumetricWeight) {
			var roundoff_type = $("#roundoff_type").val();
			// $('#valumetric_weight').val(totalValumetricWeight); ttttttt
			if (roundoff_type == '1') {
				$('#valumetric_weight').val(totalValumetricWeight);
			}
			else {
				$('#valumetric_weight').val(totalValumetricWeight);
			}
		}
		var totalActualWeight = $('#actual_weight').val();
		ChargableWeightCalcu();
		if (totalNoOfPack) {
			$('#no_of_pack').val(totalNoOfPack);
		}
		if (totalPerBoxWeight) {
			$('#per_box_weight').val(totalPerBoxWeight);
		}
		if (totalActualWeight) {
			$('#actual_weight').val(totalActualWeight);
		}
		if (totalValumetricWeight) {
			var roundoff_type = $("#roundoff_type").val();
			if (roundoff_type == '1') {
				$('#valumetric_weight').val(totalValumetricWeight.toFixed(2));
			}
			else {
				$('#valumetric_weight').val(totalValumetricWeight.toFixed(2));
			}
		}
		$('#length').val(totalLength.toFixed(2));
		$('#breath').val(totalBreath.toFixed(2));
		$('#height').val(totalHeight.toFixed(2));
		$('#valumetric_weight').val(totalValumetricWeight.toFixed(2));
		$('#valumetric_chageable').val(Math.ceil(valumetric_chageable.toFixed(2)));
		$('#valumetric_actual').val(valumetric_actual.toFixed(2));
	}


	$("#awn").focus();

	$("#invoice_value").blur(function () {
		var invoice_bavalue = $(this).val();
		if (invoice_bavalue > 50000) {
			$('#eway_no').prop('required', true);
		}
		else {
			$('#eway_no').prop('required', false);
		}
	});

	// for edit section
	var shipment = $("#doc_typee").val();
	if (shipment == 1) {
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
	} else {
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


function calculate_cft() {
	var courier_id = parseFloat(($('#courier_company').val() != '') ? $('#courier_company').val() : 0);
	var booking_date = $('#booking_date').val();
	var customer_account_id = $('#customer_account_id').val();
	var franchise_type = $('#franchise_type').val();
	var cft = $('#cft').val();
	if(cft==""){
        if(franchise_type ==1 || franchise_type == 3){
             if($('.bnf_customer').val()=="" && $('#company_customer').is(":checked")){
				var customer_account_id = $('#customer_account_id').val();
			 }else{
				var customer_account_id = $('#franchise_id').val();
			 }
		}else{
			var customer_account_id = $('#franchise_id').val();
		}
		$.ajax({
			type: 'POST',
			url: base_url + 'Franchise_manager/available_cft',
			data: 'courier_id=' + courier_id + '&booking_date=' + booking_date + '&customer_id=' + customer_account_id,
			dataType: "json",
			success: function (data) {				
				$('#cft').val(data.cft_charges);
				$('#air_cft').val(data.air_cft);
			},
			error: function () {
				$('#cft').val(7);
			}
		});
	}
}


// Notify bill_type code remider
function NotifySubmission()
{
	var bill_type = $('#dispatch_details').val();
	var frieht = $('#frieht').val();
	if(bill_type!='' && frieht !='')
	{
		$('#spinner').show();
		$('#desabledBTN').prop('disabled', true);
		setTimeout(
			function() 
			{
				$("#submit_notify").modal('show');
			}, 1000);
	}
}

$('#cancel_model').click(function(){
	$('#spinner').hide();
	$('#desabledBTN').prop('disabled', false);
});

//  check submission validation value matric check PKT or total no of pice 
function checkForTheCondition() {
	// if ($('#is_volumetric').is(':checked')) 
	// {
	$('volumetric_table_row').find('input').prop('required', true);
	$('volumetric_table_row').find('input').attr("required", "required");
	no_of_pack1 = $('#no_of_pack1').val();
	per_box_weight = $('#per_box_weight').val();

	if (per_box_weight == no_of_pack1) {
		$('#submit1').click();
	} else {
		// alert('Please enter the volumetric details of all '+no_of_pack1+' No Of Box Packets!');
		alertify.alert("Please enter the volumetric details of all " + no_of_pack1 + " No Of Box Packets!",
			function () {
				alertify.success('Ok');
			});
	}
}

// credit case walking customer or bnf customer blocking system  start
// bnf customer 
$('.bnf_customer').change(function(){
   var bnf_cut = $(this).val();
   if(bnf_cut!=''){
	//   $("#company_customer").prop("disabled", true);
   }else
   {
	$('#sender_name').val('');
	$('#sender_address').val('');
	$('#sender_pincode').val('');
	$('#sender_contactno').val('');
	$('#sender_gstno').val('');
	$('#sender_state').html('<option value="">Select State</option>');
	$('#sender_city').html('<option value="">Select City</option>');
	$("#company_customer").prop("disabled", false);
   }
});

$('#company_customer').change(function(){
   if($('#company_customer').is(":checked")){
	$(".bnf_customer").prop("disabled", false);
	$("#is_appointment").prop("disabled", false);
	$("#door_delivery").prop("disabled", false);
	$('.charges').hide();
	$('.showcharges').show();
   }else
   {
	
	$("#is_appointment").prop("disabled", true);
	$("#door_delivery").prop("disabled", true);
	$('.charges').show();
	$('.showcharges').hide();
	$('#sender_name').val('');
	$('#sender_address').val('');
	$('#sender_pincode').val('');
	$('#sender_contactno').val('');
	$('#sender_gstno').val('');
	$('.bnf_customer').val('');
	$('#sender_state').html('<option value="">Select State</option>');
	$('#sender_city').html('<option value="">Select City</option>');
	$("#company_customer").prop("disabled", false);
	$(".bnf_customer").prop("disabled", true);
   }
});
// credit case walking customer or bnf customer blocking system  end