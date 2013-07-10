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

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Stock - POS Management System</title>
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
						<li><a href="requisition.php"><i class="icon-double-angle-right"></i> Requisition</a></li>
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

					<li  class="active open">
					  <a href="#" class="dropdown-toggle">
						<i class="icon-cogs"></i>
						<span>Configurations</span>
						<b class="arrow icon-angle-down"></b>
					  </a>
					<ul class="submenu">
						<li><a href="userslist.php"><i class="icon-double-angle-right"></i> Users</a></li>
						<li class="active"><a href="settings.php"><i class="icon-double-angle-right"></i> System</a></li>
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
                            <li><a href="#">Configuration</a><span class="divider"><i class="icon-angle-right"></i></span></li>
							<li class="active">System</li>
						</ul><!--.breadcrumb-->
					</div><!--#breadcrumbs-->



					<div id="page-content" class="clearfix">
						
						<div class="page-header position-relative">
							<h1>Configuration <small><i class="icon-double-angle-right"></i> Settings</small></h1>
				</div>
    <!--/page-header-->

						

<div class="row-fluid">
<!-- PAGE CONTENT BEGINS HERE -->

<div class="row-fluid">
	<h3 class="header smaller lighter blue">List of Settings </h3>
    <p>
    <form  action="settings.php" method="GET">                
        Select Table and click open: <br />
		<select class="select" name="cmbTable">
          <option selected>-- Select Table Hereb--</option>
          <option>Bank</option>          
          <option>Status</option>
          <option>Stock Category</option>
          
        </select><br />
            <input type="submit" value="Open" class="btn btn-info btn-large" name="submit">
         </form>
    </p>
	<div class="table-header">
		Results for "
        <?php
								@$cmbTable=$_GET['cmbTable']; 
								  if (trim($cmbTable)=="Accounts Heads"){
									echo "Accounts Heads";
								  }else if (trim($cmbTable)=="Stock Category"){	
									echo "Stock Category";
								  }else if (trim($cmbTable)=="Asset Category"){  
								   echo "Asset Category";
								  }else if (trim($cmbTable)=="Attributes"){  
								   echo  "Attributes";
								  }else if (trim($cmbTable)=="Distributor"){  
									echo "Distributor";
								  }else if (trim($cmbTable)=="Bank"){
									echo "Bank";  
								 }else if (trim($cmbTable)=="Supplier"){
									echo "Supplier"; 
								 }else if (trim($cmbTable)=="Location"){  
									echo "Location";
								 }else if (trim($cmbTable)=="Status"){  
									echo "Status";
								 }
						 ?>
        "
	</div>
	
		<table id="table_report" class="table table-striped table-bordered table-hover">
			<?php
  @$cmbTable=$_GET['cmbTable']; 
  if (trim($cmbTable)=="- Select Table Here-")
  {
        echo "<b>Please Select a table from the drop-down box and click 'Open'.<b>";        
  }
  else if (trim($cmbTable)=="Stock Category"){	
	echo "<thead><TR><TH>Category</TH><TH>&nbsp;</TH></TR></thead><tbody>";
  }
  else if (trim($cmbTable)=="Bank")
  {  
    echo "<thead><TR><TH><b> Bank </b>&nbsp;</TH><TH>&nbsp;</TH></TR></thead><tbody>";
  }
  else if (trim($cmbTable)=="Status")
  {  
    echo "<thead><TR><TH>Status</TH><TH>&nbsp;</TH></TR></thead><tbody>";
  }	
  ?>
				</tbody>
		</table>
        
</div>
<hr></hr>
                <?php
								@$cmbTable=$_GET['cmbTable']; 
								 if (trim($cmbTable)=="Stock Category"){	
									echo "<a class='btn btn-success' href ='setting.php?cate=1'> Add New Stock Category </a>";
								  }else if (trim($cmbTable)=="Bank"){
									echo "<a class='btn btn-success' href ='setting.php?bank=1'> Add New Bank </a>";  
								 }else if (trim($cmbTable)=="Status"){  
									echo "<a class='btn btn-success' href ='setting.php?status=1'> Add New Status </a>";
								 }
						 ?>
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
if(isset($_REQUEST['cmbTable'])){
	
	if ($_REQUEST['cmbTable']=='Bank'){

	echo "
		$('#table_report').dataTable( {";
       echo'  "bProcessing": true,
        "bServerSide": true,
        "sAjaxSource": "server_processing3.php?bnk=1"
   
   
    } );
	
	';			
	}else if ($_REQUEST['cmbTable']=='Status'){
	
		echo "
			$('#table_report').dataTable( {";
		   echo'  "bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "server_processing3.php?sts=1"
	   
	   
		} );
		
		';
			
	}
	else if ($_REQUEST['cmbTable']=='Stock Category'){
	
		echo "
			$('#table_report').dataTable( {";
		   echo'  "bProcessing": true,
			"bServerSide": true,
			"sAjaxSource": "server_processing3.php?stkc=1"
	   
	   
		} );
		
		';			
	}
}
?>
		</script>
	</body>
</html>
