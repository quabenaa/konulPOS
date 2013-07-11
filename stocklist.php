<?php
session_start();
require_once 'conn.php';

//check to see if user has logged in with a valid password
if (!isset($_SESSION['user_id']) && !isset($_SESSION['access_lvl']))
{
header("Location:./");
}
else if($_SESSION['access_lvl'] != 2){
header("Location:error-401.php?/access=denied/");
die();
}


if(isset($_REQUEST['exp'])){
	
	$valexp="date(`Expiry Date`) < '" . date('Y-m-d', strtotime('+2 month')) . "'";
	$query="
	SELECT `Stock Code`,`Stock Name`,`Category`,`Units in Stock`,`Reorder Level` 
		FROM `stock` 
	WHERE ".$valexp."";
	$export = 'exportstock.php?exp=1';
	$print = 'rptstock.php?exp=1';
	$head = 'Expiring Stock';
		
}else if (isset($_REQUEST['relvl'])){

	$query="
	SELECT `Stock Code`,`Stock Name`,`Category`,`Units in Stock`,`Reorder Level` 
		FROM `stock` 
	WHERE (`Reorder Level` > `Units in Stock` OR `Reorder Level` = `Units in Stock`)";
	$export = 'exportstock.php?relvl=1';
	$print = 'rptstock.php?relvl=1';
	$head = 'Stocks that has reach their Re-Order Level';
		
}else{

	$query="SELECT `Stock Code`,`Stock Name`,`Category`,`Units in Stock`,`Reorder Level` FROM `stock` ";
	$export = 'exportstock.php';
	$print = 'rptstock.php';
	$head = 'Stocks added';

}
$result  = mysql_query($query) OR die(mysql_error()); 
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Main Stock - KONUL [ POS Management System ]</title>
        <link rel="icon" href="assets/images/favico.ico">
		<meta name="description" content="Static & Dynamic Tables" />

		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<!-- basic styles -->
		<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
		<link href="assets/css/bootstrap-responsive.min.css" rel="stylesheet" />
        
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
			  <a class="brand" href="#"><small><i class="icon-shopping-cart"></i> Konul</small> </a>
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
						<button class="btn btn-small btn-success" data-rel="tooltip" title="Retail Sales" data-placement="left">
                        <i class="icon-shopping-cart"></i></button>
						<button class="btn btn-small btn-info" data-rel="tooltip" title="Accounts" data-placement="left">
                        <i class="icon-money"></i></button>
						<button class="btn btn-small btn-yellow" data-rel="tooltip" title="Monitor System Logs" data-placement="left">
                        <i class="icon-eye-open"></i></button>
						<button class="btn btn-small btn-purple" data-rel="tooltip" title="System setting" data-placement="left">
                        <i class="icon-wrench"></i></button>
					</div>
					<div id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>
						<span class="btn btn-info"></span>
						<span class="btn btn-yellow"></span>
						<span class="btn btn-purple"></span>
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
                   <li  class="active open">
					  <a href="#" class="dropdown-toggle">
						<i class="icon-barcode"></i>
						<span>Products</span>
						<b class="arrow icon-angle-down"></b>
					  </a>
					<ul class="submenu">
						<li class="active"><a href="stocklist.php"><i class="icon-double-angle-right"></i> Main Stock</a></li>
						<li><a href="restock.php"><i class="icon-double-angle-right"></i> Re-stock</a></li>
						<!--<li><a href="requisition.php"><i class="icon-double-angle-right"></i> Requisition</a></li> -->
						<li><a href="wastage.php"><i class="icon-double-angle-right"></i> Wastage</a></li>
				     </ul>
				  </li>
					
										
				  <li>
					  <a href="sales.php">
						<i class="icon-shopping-cart"></i>
						<span>Retail Sales</span>
					  </a>
				  </li>

					<li>
					  <a href="#" class="dropdown-toggle" >
						<i class="icon-money"></i>
						<span>Accounts</span>
						<b class="arrow icon-angle-down"></b>
					  </a>
					  <ul class="submenu">
						<li ><a href="dailycash.php"><i class="icon-double-angle-right"></i> Daily Cash Book</a></li>
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
							<li class="active">Stock</li>
						</ul><!--.breadcrumb-->
					</div><!--#breadcrumbs-->



					<div id="page-content" class="clearfix">
						
						<div class="page-header position-relative">
							<h1>Stock <small><i class="icon-double-angle-right"></i> List of Stock Items</small></h1>
						</div><!--/page-header-->

						

<div class="row-fluid">
<!-- PAGE CONTENT BEGINS HERE -->

<div class="row-fluid">
	<h3 class="header smaller lighter blue">Retail Stock List </h3>
    <p class="pull-left">
    <a class="btn btn-small btn-success" href="stock.php"><span>Add New</span></a>
    <a class="btn btn-small btn-yellow" href="<?php echo $export ?>"><span>Excel</span></a>
    <a class="btn btn-small btn-info" href="<?php echo $print ?>"><span>Print</span></a>
    </p>
    <p class="pull-right">
    <a class="btn btn-small btn-danger" href="stocklist.php?exp=1"><span>View Expiring Items</span></a>
    <a class="btn btn-small btn-warning" href="stocklist.php?relvl=1" ><span>Re-Order Level Items</span></a>
    </p>
</div>
<div class="row-fluid">
	<div class="table-header">
		Results for "<?php echo $head;?>"
	</div>
	
		<table id="table_report" class="table table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th class="hidden-480"><small>Stock Code</small></th>
					<th>
                    <small>Product Name</small>
                    </th>
					<th class="hidden-480"><small>Category</small></th>
					<th>
                    <small>Units in Stock</small>
                    </th>
                    <th>
                    <small>Total Sold</small>
                    </th>
					<th>
                    <small>Re-Order Level</small>
                    </th>
                    <th>
                    </th>
				</tr>
			</thead>
									
			<tbody>
				
                
				
			</tbody>
		</table>
	
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
		
		
<?php 
if(isset($_REQUEST['exp'])){
	
	echo "
		$('#table_report').dataTable( {";
       echo'  "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "server_processing.php?exp=1"
    } );
	';
		
}else if (isset($_REQUEST['relvl'])){

	echo "
		$('#table_report').dataTable( {";
       echo'  "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "server_processing.php?relvl=1"
   
   
    } );
	
	';
		
}else{

	echo "
		$('#table_report').dataTable( {";
       echo'  "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "server_processing.php"
    } );
	';
}
?>
	
		
		</script>
	</body>
</html>
