<?php 
session_start();
require_once 'conn.php';

if (!isset($_SESSION['user_id']) && !isset($_SESSION['access_lvl']))
{
header("Location:./");
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Dashboard - KONUL [ POS Management System ]</title>
        <link rel="icon" href="assets/images/favico.ico">
		<meta name="description" content="overview & stats" />

		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<!-- basic styles -->
		<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
		<link href="assets/css/bootstrap-responsive.min.css" rel="stylesheet" />

		<link rel="stylesheet" href="assets/css/font-awesome.min.css" />
        <link rel="stylesheet" href="assets/css/font-awesome-ie7.min" />
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
                       <?php if ($_SESSION['access_lvl'] == 2){  ?>
							<li><a href="settings.php"><i class="icon-cog"></i> Settings</a></li>
                      		<?php } ?>
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
                    	<a class="btn btn-small btn-purple" data-rel="tooltip" href="dashboard.php" title="Dashboard" data-placement="left">
                        <i class="icon-dashboard"></i></a>
						<a class="btn btn-small btn-success" data-rel="tooltip" href="sales.php" title="Retail Sales" data-placement="left">
                        <i class="icon-shopping-cart"></i></a>
						<a class="btn btn-small btn-info" data-rel="tooltip" title="Reports" href="reports.php" data-placement="left">
                        <i class="icon-paste"></i></a>
						<a class="btn btn-small btn-yellow" data-rel="tooltip" href="systemLogs.php" title="Monitor System Logs"
                        data-placement="left">
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
					
					<li  class="active">
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
                    <div class="span9">
						<ul class="breadcrumb ">
							<li><i class="icon-home"></i> <a href="#">Home</a><span class="divider"><i class="icon-angle-right"></i></span></li>
							<li class="active">Dashboard</li>
						</ul>
                    </div>    
                        <!--.breadcrumb-->
					</div><!--#breadcrumbs-->



					<div id="page-content" class="clearfix">
						
						<div class="page-header position-relative">
							<h1>Dashboard <small><i class="icon-double-angle-right"></i> overview & stats</small></h1>
						</div><!--/page-header-->

						

						<div class="row-fluid">
<!-- PAGE CONTENT BEGINS HERE -->

<div class="alert alert-block alert-success">
 <i class="icon-ok green"></i> Welcome to <strong class="green">User <small></small></strong>
</div>

<div class="space-6"></div>
<?php if ($_SESSION['access_lvl'] == 2 || $_SESSION['access_lvl'] == 5){?>
<div class="row-fluid">

 <div class="span12 infobox-container">
   
	<div class="infobox infobox-medium infobox-dark infobox-green">
		<div class="infobox-icon"><i class="icon-user"></i></div>
		<div class="infobox-data">
			<span class="infobox-content"><small><b> Users Active</b></small></span>
			<br />
			<span class="infobox-content" id="users"></span>
		</div>
	</div>
	
	<div class="infobox infobox-medium infobox-dark infobox-blue">
		<div class="infobox-icon"><i class="icon-money"></i></div>
		<div class="infobox-data">
			<span class="infobox-content"><small><b> Daily Sales</b></small></span>
			<br />
			<span class="infobox-content" id="sales"></span>
		</div>
	</div>
	
	<div class="infobox infobox-medium infobox-dark infobox-orange">
		<div class="infobox-icon"><i class="icon-money"></i></div>
		<div class="infobox-data">
			<span class="infobox-content"><small><b>Daily Profit</b></small></span>
			<br />
			<span class="infobox-content" id="profit"></span>
		</div>
	</div>
	<?php } if ($_SESSION['access_lvl'] == 2 ){?>
  <div class="infobox infobox-wood infobox-dark infobox-red">
		<div class="infobox-icon"><i class="icon-barcode"></i></div>
		<div class="infobox-data">
			<span class="infobox-content"><small><b>Expiring Products</b></small></span>
			<br />
			<span class="infobox-content" id="expire"></span><br />
		</div>
	</div>	

	
	<div class="infobox infobox-medium infobox-dark infobox-green2">
		<div class="infobox-icon"><i class="icon-barcode"></i></div>
		<div class="infobox-data">
			<span class="infobox-content"><small><b>Re-Order</b></small></span>
			<br />
			<span class="infobox-content" id="reorder"></span>
		</div>
	</div>
	
	</div>
 </div>
 <?php }?>
</div><!--/row-->

<div class="space-6"></div>
<div class="alert alert-block alert-info">
 <i class=" icon-info-sign green"></i> This is <strong>CheckOut</strong>, <br>
An Inventory Management System, a robust application for the management of stock records as well as the automated operations of the inventory systems. Before you can use the application, you have to be a registered user with valid authentication details.
</div>

<div class="hr hr32 hr-dotted"></div>
<span class="blue"><?php echo "Copyright (c) ".date("Y",time())."</span>, <span class='green'>SmartView Technology"; ?></span>

<!-- PAGE CONTENT ENDS HERE -->
						 </div><!--/row-->
	
					</div><!--/#page-content-->
					
			</div><!-- #main-content -->
            
		</div><!--/.fluid-container#main-container-->




		<a href="#" id="btn-scroll-up" class="btn btn-small btn-inverse">
			<i class="icon-double-angle-up icon-only"></i>
		</a>


		<!-- basic scripts 
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>-->
		<script src='assets/js/jquery-1.9.1.min.js'></script>
		
		<script src="assets/js/bootstrap.min.js"></script>

		<!-- page specific plugin scripts -->
		
		<!--[if lt IE 9]>
		<script type="text/javascript" src="assets/js/excanvas.min.js"></script>
		<![endif]-->

		<script type="text/javascript" src="assets/js/jquery-ui-1.10.2.custom.min.js"></script>

		<script type="text/javascript" src="assets/js/jquery.ui.touch-punch.min.js"></script>

		<script type="text/javascript" src="assets/js/jquery.slimscroll.min.js"></script>

		<script type="text/javascript" src="assets/js/jquery.easy-pie-chart.min.js"></script>

		<script type="text/javascript" src="assets/js/jquery.sparkline.min.js"></script>

		<script type="text/javascript" src="assets/js/jquery.flot.min.js"></script>

		<script type="text/javascript" src="assets/js/jquery-1.9.1.min.js"></script>

		<script type="text/javascript" src="assets/js/jquery.flot.resize.min.js"></script>


		<!-- ace scripts -->
		<script src="assets/js/ace-elements.min.js"></script>
		<script src="assets/js/ace.min.js"></script>


		<!-- inline scripts related to this page -->
		
		<script type="text/javascript">
		setInterval(
		function()
		{
			$('#users').load('dashboarddata.php?data=user');
			$('#sales').load('dashboarddata.php?data=dlysl');
			$('#profit').load('dashboarddata.php?data=dlypft');
			$('#expire').load('dashboarddata.php?data=proexp');
			$('#reorder').load('dashboarddata.php?data=relvl');
		
		}, 1000);

		</script>

	</body>
</html>
