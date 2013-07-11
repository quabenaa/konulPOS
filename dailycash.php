<?php
session_start();
require_once 'conn.php';

//check to see if user has logged in with a valid password
if (!isset($_SESSION['user_id']) && !isset($_SESSION['access_lvl']))
{
header("Location:./");
}
else if($_SESSION['access_lvl'] != 2 && $_SESSION['access_lvl'] != 5){
	header("Location:error-401.php?/access=denied/");
die();
}


if(isset($_REQUEST['date']) || !empty($_REQUEST['date'])){
	//list($sdate, $edate) = explode("-", $_REQUEST['date']);
	$date = $_REQUEST['date'];
	$valexp="date(`Date`) = '" . date('Y-m-d', strtotime($date)) . "'";	
	$query="SELECT `ID`,`Date`,Type,Classification,Amount,`Particulars` FROM cash WHERE ".$valexp."order by `Date` desc";
	$export ="exportcashbook.php?date=$date";    
	$print ="rptcashbook.php?date=$date";
		
}else{
	$date = date('Y-m-d');
	$query="SELECT `ID`,`Date`,Type,Classification,Amount,`Particulars` FROM cash WHERE `Date` ='$date' ORDER BY `Date` desc";
	$export ="exportcashbook.php?date=$date";    
	$print ="rptcashbook.php?date=$date";
}
 $result  = mysql_query($query) or die(mysql_error()); 
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Daily Cash - KONUL [ POS Management System ]</title>
        <link rel="icon" href="assets/images/favico.ico">
		<meta name="description" content="Static & Dynamic Tables" />

		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<!-- basic styles -->
        <link rel="stylesheet" href="assets/css/jquery-ui-1.10.2.custom.min.css" />
		<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
		<link href="assets/css/bootstrap-responsive.min.css" rel="stylesheet" />        
		<link rel="stylesheet" href="assets/css/chosen.css" />
		<link rel="stylesheet" href="assets/css/datepicker.css" />
		<link rel="stylesheet" href="assets/css/bootstrap-timepicker.css" />
		<link rel="stylesheet" href="assets/css/daterangepicker.css" />
		<link rel="stylesheet" href="assets/css/colorpicker.css" />
        
        <!-- dataTables -->
		<link rel="stylesheet" href="assets/css/TableTools.css">

		<link rel="stylesheet" href="assets/css/font-awesome.min.css" />
		<!--[if IE 7]>
		  <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css" />
		<![endif]-->


		<!-- page specific plugin styles -->
		

		<!-- ace styles -->
		<link rel="stylesheet" href="assets/css/ace.min.css" />
		<link rel="stylesheet" href="assets/css/ace-responsive.min.css" />
		<link rel="stylesheet" href="assets/css/ace-skins.min.css" />
		<!--[if lt IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->

	<!-- basic scripts -->
		<script type="text/javascript" src='assets/js/jquery-1.9.1.min.js'></script>
		
		<script src="assets/js/bootstrap.min.js"></script>

		<!-- page specific plugin scripts -->
        <script type="text/javascript" src="assets/js/jquery-ui-1.10.2.custom.min.js"></script>

		<script type="text/javascript" src="assets/js/jquery.ui.touch-punch.min.js"></script>

		<script type="text/javascript" src="assets/js/chosen.jquery.min.js"></script>

		<script type="text/javascript" src="assets/js/fuelux.spinner.js"></script>

		<script type="text/javascript" src="assets/js/bootstrap-datepicker.min.js"></script>

		<script type="text/javascript" src="assets/js/bootstrap-timepicker.min.js"></script>

		<script type="text/javascript" src="assets/js/date.js"></script>

		<script type="text/javascript" src="assets/js/daterangepicker.min.js"></script>

		<script type="text/javascript" src="assets/js/jquery.dataTables.min.js"></script>
		<script type="text/javascript" src="assets/js/jquery.dataTables.bootstrap.js"></script>
        
		<!-- dataTables -->
		<script src="assets/js/TableTools.min.js"></script>
        <script src="assets/js/ColReorder.min.js"></script>
        <script src="assets/js/ColVis.min.js"></script>

		<!-- ace scripts -->
		<script src="assets/js/ace-elements.min.js"></script>
		<script src="assets/js/ace.min.js"></script>
	</head>

	<body>
		<div class="navbar navbar-inverse">
		  <div class="navbar-inner">
		   <div class="container-fluid">
			  <a class="brand" href="#"><small><i class="icon-shopping-cart"></i> Test Supermarket</small> </a>
			  <ul class="nav ace-nav pull-right">
					<li class="white">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="icon-calendar"></i>
							<span><?php echo date('l F d, Y',time());?></span>
						</a>
					</li>
					<li class="light-blue user-profile">
						<a class="user-menu dropdown-toggle" href="#" data-toggle="dropdown">
							<img alt="Jason's Photo" src="assets/avatars/user.jpg" class="nav-user-photo" />
							<span id="user_info">
								<small>Welcome,</small><?php echo strtoupper($_SESSION['name']); ?>
							</span>
							<i class="icon-caret-down"></i>
						</a>
						<ul id="user_menu" class="pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer">
							<li><a href="#"><i class="icon-cog"></i> Settings</a></li>
							<li><a href="#"><i class="icon-user"></i> Profile</a></li>
							<li class="divider"></li>
							<li><a href="transact-user.php?action=Logout"><i class="icon-off"></i> Logout</a></li>
						</ul>
					</li>
			  </ul><!--/.ace-nav-->
		   </div><!--/.container-fluid-->
		  </div><!--/.navbar-inner-->
		</div><!--/.navbar-->

		<div class="container-fluid" id="main-container">
			<a href="#" id="menu-toggler"><span></span></a><!-- menu toggler -->

			<div id="sidebar">
				
				<div id="sidebar-shortcuts">
					<div id="sidebar-shortcuts-large">
                    	<a class="btn btn-small btn-purple" data-rel="tooltip" href="dashboard.php" title="Dashboard" data-placement="right">
                        <i class="icon-dashboard"></i></a>
						<a class="btn btn-small btn-success" data-rel="tooltip" href="sales.php" title="Retail Sales" data-placement="right">
                        <i class="icon-shopping-cart"></i></a>
						<a class="btn btn-small btn-info" data-rel="tooltip" title="Reports" href="reports.php" data-placement="right">
                        <i class="icon-paste"></i></a>
						<a class="btn btn-small btn-yellow" data-rel="tooltip" href="systemLogs.php" title="Monitor System Logs"
                        data-placement="right">
                        <i class="icon-eye-open"></i></a>						
					</div>
					<div id="sidebar-shortcuts-mini">
                    <span class="btn btn-purple"></span>
						<span class="btn btn-success"></span>
						<span class="btn btn-info"></span>
						<span class="btn btn-yellow"></span>
						
					</div>
				</div><!-- #sidebar-shortcuts -->

				<ul class="nav nav-list">
					
					<li>
					  <a href="dashboard.php">
						<i class="icon-dashboard"></i>
						<span>Dashboard</span>
						
					  </a>
					</li>
					<?php if (isset($_SESSION['user_id'])) 
					   {	
					   
						if ($_SESSION['access_lvl'] == 2){ 
					   ?>
                   <li>
					  <a href="#" class="dropdown-toggle">
						<i class="icon-barcode"></i>
						<span>Products</span>
						<b class="arrow icon-angle-down"></b>
					  </a>
					<ul class="submenu">
						<li><a href="stocklist.php"><i class="icon-double-angle-right"></i> Main Stocks</a></li>
						<li><a href="restock.php"><i class="icon-double-angle-right"></i> Re-stock</a></li>
						<!--<li><a href="requisition.php"><i class="icon-double-angle-right"></i> Requisition</a></li> -->
						<li><a href="wastage.php"><i class="icon-double-angle-right"></i> Wastage</a></li>
				     </ul>
				  </li>
					 <?php }
				if ($_SESSION['access_lvl'] == 2 || $_SESSION['access_lvl'] == 4){  ?>										
				  <li>
					  <a href="sales.php">
						<i class="icon-shopping-cart"></i>
						<span>Retail Sales</span>
					  </a>
				  </li>
				<?php }
			if ($_SESSION['access_lvl'] == 2 || $_SESSION['access_lvl'] == 5){?>
					<li class="open active">
					  <a href="#" class="dropdown-toggle" >
						<i class="icon-money"></i>
						<span>Accounts</span>
						<b class="arrow icon-angle-down"></b>
					  </a>
					  <ul class="submenu">
						<li class="active" ><a href="dailycash.php"><i class="icon-double-angle-right"></i> Daily Cash Book</a></li>
						<li><a href="actsales.php"><i class="icon-double-angle-right"></i> Sales</a></li>
						<li><a href="cheque.php"><i class="icon-double-angle-right"></i> Cheque Register</a></li>
						<!--<li><a href="buttons.html"><i class="icon-double-angle-right"></i> Fixed Assets</a></li> -->
						<li><a href="creditors.php"><i class="icon-double-angle-right"></i> Creditors Schedule</a></li>
						<li><a href="debtors.php"><i class="icon-double-angle-right"></i> Debtors Schedule</a></li>
						<li><a href="accreport.php"><i class="icon-double-angle-right"></i> Reports</a></li>
					  </ul>
					</li>
					
					<li>
					  <a href="reports.php">
						<i class="icon-paste"></i>
						<span>Reports</span>						
					  </a>
					</li>
				<?php }
				if ($_SESSION['access_lvl'] == 2){?>
					<li>
					  <a href="systemLogs.php">
						<i class="icon-eye-open"></i>
						<span>System Logs</span>						
					  </a>
					</li>

					<li>
					  <a href="#" class="dropdown-toggle">
						<i class="icon-cogs"></i>
						<span>Configurations</span>
						<b class="arrow icon-angle-down"></b>
					  </a>
					<ul class="submenu">
						<li><a href="userslist.php"><i class="icon-double-angle-right"></i> Users</a></li>
						<li><a href="settings.php"><i class="icon-double-angle-right"></i> System</a></li>
                        <li><a href="customerslist.php"><i class="icon-double-angle-right"></i> Customers</a></li>
				     </ul>
				  </li>
				<?php
				}
			 } ?>
				</ul><!--/.nav-list-->
			
            		<div id="sidebar-collapse"><i class="icon-double-angle-left"></i></div>
			
            </div><!--/#sidebar-->

		
			<div id="main-content" class="clearfix">
					
					<div id="breadcrumbs">
						<ul class="breadcrumb">
							<li><i class="icon-home"></i> <a href="#">Home</a><span class="divider"><i class="icon-angle-right"></i></span></li>
							<li>Accounts <span class="divider"><i class="icon-angle-right"></i></span></li>
							<li class="active">Daily Cash Book</li>
						</ul><!--.breadcrumb-->
					</div><!--#breadcrumbs-->



					<div id="page-content" class="clearfix">
						
						<div class="page-header position-relative">
							<h1>Daily Cash Book <small><i class="icon-double-angle-right"></i> List of Daily Transactions</small></h1>
						</div><!--/page-header-->

						

<div class="row-fluid">
<!-- PAGE CONTENT BEGINS HERE -->

<div class="row-fluid">
	<h3 class="header smaller lighter blue">Cash Book Index </h3>
</div>
<div class="row-fluid">    
    <form action="dailycash.php" method="get" >
    <label class="small-labels">Search By Date :</label>
    <span class="input-icon"><i class="icon-calendar"></i>
	<input id="date" class="input-large" name="date" type="text" data-date-format="yyyy-mm-dd"></input>
	</span><br/>
    <button class="btn btn-small btn-info" value="submit" name="action" type="submit">Submit</button> 
    </form>
  </div>
<div class="row-fluid">    
	<div class="table-header">
		Results for "Cash Book"
	</div>
	
		<table id="table_report" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="center"><small>S/No.</small></th>
					<th class="hidden-480"><small>Date</small></th>
					<th>
                    <small>Type</small>
                    </th>
					<th class="hidden-480"><small>Classification</small></th>
					<th>
                    <small>Amount</small>
                    </th>
					<th>
                    <small>Particulars</small>
                    </th>
					<th></th>
				</tr>
			</thead>
									
			<tbody>
				
                <?php  
				$n=0;
				while(list($id,$date,$type,$classification,$amount,$particulars)=mysql_fetch_row($result))
				{$n++;
				 $table="<TR><TD>$n</TD><TD class='hidden-480'>".date('d-m-Y',strtotime($date))."</TD><td>$type</td>
					<td class='hidden-480'>$classification</td>
					<td>$amount</td>
					<td>$particulars</td>
					<TD></TD>";
				echo $table;
				}
				?>
				
				</tbody>
		</table>
	
</div><br/>
<div class="row-fluid">
    <p>
    <a class="btn btn-small btn-yellow" href="<?php echo $export; ?>"><i class=" icon-table"></i><span> Excel Report</span></a>
    <a class="btn btn-small btn-info" href="<?php echo $print; ?>"><i class="icon-print"></i><span> Print Report</span></a>
    </p>
</div>
<div class="hr hr32 hr-dotted"></div>
<span class="blue"><?php echo "Copyright (c) ".date("Y",time())."</span>, <span class='green'>SmartView Technology"; ?></span>

</div> 
<!-- PAGE CONTENT ENDS HERE -->
						 </div><!--/row-->
	
					</div><!--/#page-content-->

			</div><!-- #main-content -->


		</div><!--/.fluid-container#main-container-->




		<a href="#" id="btn-scroll-up" class="btn btn-small btn-inverse">
			<i class="icon-double-angle-up icon-only"></i>
		</a>
		<!-- inline scripts related to this page -->
		
		<script type="text/javascript">
		$(function() {
		
			var oTable1 = $('#table_report').dataTable( {
			"aoColumns": [
			  { "bSortable": false },
			  null, null,null, null, null,
			  { "bSortable": false }
			] } );
			
			$('[data-rel=tooltip]').tooltip();
		
		})

		$('#date').datepicker();
		//$('#date').daterangepicker();	
		</script>
	</body>
</html>
