<!--@include('layoutMaster.header')-->










<!-- START: Main Menu-->
<div class="sidebar">
	<div class="site-width">
		<!-- START: Menu-->
		<ul id="side-menu" class="sidebar-menu" style="overflow-y: auto;">


			<?php
			$userType = $this->session->userdata("userType");
			$customer_id = $this->session->userdata("customer_id");

			$currentURL = current_url();
			$menu_url = str_replace(base_url(), "", $currentURL);

			//	$check_menu_rights = $this->basic_operation_m->get_query_result("select * from menu_allotment left join all_menu on all_menu.am_id = menu_allotment.am_id where menu_allotment.user_id = '$userId' AND menu_url='$menu_url' order by menu_seq,menu_sub_seq asc");
			$check_menu_rights = $this->db->query("select * from all_menu where customer_id = '$customer_id' AND menu_status = '2' AND menu_url='$menu_url' order by menu_seq,menu_sub_seq asc");
			// echo $this->db->last_query();die(); 
			if (empty($check_menu_rights)) {
				// echo "<script>alert('this menu Not assigned to you!.');window.location.replace('".base_url('admin')."');</script>";exit();
			}

			//	$company_details = $this->basic_operation_m->get_query_result("select * from menu_allotment left join all_menu on all_menu.am_id = menu_allotment.am_id where menu_allotment.user_id = '$userId' order by menu_seq,menu_sub_seq asc"); 
			$company_details = $this->db->query("select * from all_menu where menu_status = '2' order by menu_seq,menu_sub_seq asc")->result();
			// echo $this->db->last_query();die(); 
			// 


			// if (1) {
			// 	// code...
			// }



			?>

			<li class="dropdown active">
				<ul>
					<?php
					$close_status		 = false;
					$menu_name		 = 'Dashboard';

					foreach ($company_details as $key => $values) {
						if ($menu_name != $values->menu_name) {
							if ($close_status == true) {
								echo '</ul>';
								echo '</ll>';
							}
							echo '<li class="dropdown"><a href="#">' . $values->menu_name, $values->menu_title . '</a>';
							echo '<ul class="sub-menu">';
							echo '<li><a href="' . base_url('franchise/'.$values->menu_url) . '"> ' . $values->menu_subtitle, $values->menu_title . ' </a></li>';
							echo '</ul>';
							echo '</li>';
						}

						$menu_name		 = $values->menu_name;
						if ($values->menu_name == 'Dashboard') {
							// print_r($values->menu_name);exit;
							echo '<li class="active"><a href="' .base_url('franchise/'.$values->menu_url) . '">' . $values->menu_name, $values->menu_title . '</a></li>';
						} else {
							//echo '<li><a href="'.$values->menu_url.'">'.$values->menu_title.'</a></li>';
						}


					} ?>
					<li class="dropdown"><a href="#">Operation</a>
						<ul class="sub-menu">
							<li><a href="<?= base_url('franchise/list-bag'); ?>">Bag Master</a></li>
							<li><a href="<?= base_url('franchise/list-domestic-menifiest'); ?>">Menifiest Master</a></li>
							<!-- <li><a href="<?= base_url('franchise/inscan'); ?>">In-scan</a></li> -->
							<li><a href="<?= base_url('franchise/pickup-in-scan'); ?>">Pickup In-scan</a></li>
							<!-- <li><a href="<?= base_url('franchise/miss-route'); ?>">Miss Route</a></li> -->
							<!-- <li><a href="<?= base_url('franchise/franchise-in-scan'); ?>">Franchise In-scan</a></li> -->
							<li><a href="<?= base_url('franchise/view-incoming'); ?>">Franchise View Incoming</a></li>
							<li><a href="<?= base_url('franchise_bag/list-incoming-bag'); ?>">Franchise Bag Incoming</a></li>
						</ul>
					</li>
					<li><a href="<?= base_url('franchise/awb-available-stock'); ?>">Available Stock</a></li>
					<li class="dropdown"><a href="#">DRS</a>
						<ul class="sub-menu">
							<li><a href="<?= base_url('franchise/list-deilverysheet'); ?>">View Delivery Sheet</a></li>
							<li><a href="<?= base_url('franchise/add-deliverysheet'); ?>">Create DRS</a></li>
							<li><a href="<?= base_url('franchise/single-delivery-update'); ?>">Single Delivery Update</a></li>
							<li><a href="<?= base_url('franchise/upload-pod'); ?>">view Pod </a></li>
							<li><a href="<?= base_url('franchise/add-pod'); ?>">Add Pod </a></li>
							
						</ul>
					</li>
					<li><a href="<?= base_url('franchise-payment-transaction')?>">Wallet Transactions</a></li>
					<li><a href="<?= base_url('franchise/view-Commision')?>">View Commission</a></li>
					<li class="dropdown"><a href="#">Reports</a>
						<ul class="sub-menu">
							<li><a href="<?= base_url('franchise/list-mis-report'); ?>">MIS Repors</a></li>							
						</ul>
					</li>
					<li class="dropdown"><a href="#">PRQ</a>
						<ul class="sub-menu">
							<li><a href="<?= base_url('Franchise_prq/pickup_request'); ?>">Add PRQ</a></li>							
							<li><a href="<?= base_url('Franchise_prq/view_pickup_request'); ?>">View PRQ</a></li>							
						</ul>
					</li>

				</ul>
			</li>


			<!--    <li class="dropdown active"><a href="{{route('dashboard')}}"> Dashboard<i class="icon-home"></i></a></li>-->
			<!--    <li class=""><a href="{{route('booking-shipment')}}">  New Booking<i class="icon-organization"></i></a></li>-->
			<!--    <li class="dropdown">-->
			<!--        <ul>-->
			<!--            <li class="dropdown"><a href="#"><span> Booking</span><i class="icon-grid"></i></a>-->
			<!--                <ul class="sub-menu">-->
			<!--                    <li><a href="{{route('all-order-list')}}">B2B<i class="icon-energy"></i></a></li>-->
			<!--                    <li><a href="{{route('B2C-all-list')}}">B2C<i class="icon-disc"></i></a></li>-->
			<!--                </ul>-->
			<!--            </li>-->
			<!--        </ul>-->
			<!--    </li>-->
			<!--    <li class="dropdown">-->
			<!--        <ul>-->
			<!--            <li class="dropdown"><a href="#"><span>Tracking</span><i class="icon-grid"></i></a>-->
			<!--                <ul class="sub-menu">-->
			<!--                    <li><a href="{{route('B2B-tracking-list')}}"> B2B<i class="icon-energy"></i></a></li>-->
			<!--                    <li><a href="{{ route('B2C-tracking-list') }}">B2C<i class="icon-disc"></i></a></li>-->
			<!--                </ul>-->
			<!--            </li>-->
			<!--        </ul>-->
			<!--    </li>-->
			<!--    <li class=""><a href="{{ route('ndr-all-list') }}">NDR<i class="icon-organization"></i></a></li>-->

			<!--    <li class=""><a href="{{route('rate-calculator')}}"> Billing<i class="icon-doc"></i></a></li>-->
			<!--    <li class=""><a href="{{route('report')}}">Report<i class="icon-support"></i></a></li>                  -->
			<!--    <li class=""><a href="{{ route('pincode-services') }}">Addon<i class="icon-support"></i></a></li>                  -->
			<!--    <li class=""><a href="{{ route('main-setting') }}">Settings<i class="icon-support"></i></a></li>                  -->
			<!--    <li class=""><a href="{{route('escalations')}}">Support<i class="icon-support"></i></a></li> -->




			<!--</ul>-->
			<!-- END: Menu-->
	</div>
</div>