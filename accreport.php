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
	$val="date(`Sales Date`) = '" . date('Y-m-d', strtotime($date)) . "'";		
	$queryM="SELECT distinct(`Entered By`) as staff, `ID`,`Sales Date` FROM sales  WHERE ". $val . " group by `Entered By`";
	$export ="exportinvsales.php?date=$date";    
	$print ="rptinvsales.php?date=$date";
		
}else{
	$date = date('Y-m-d');
	$val="date(`Sales Date`) = '" . date('Y-m-d', strtotime($date)) . "'";		
	$queryM="SELECT distinct(`Entered By`) as staff, `ID`,`Sales Date` FROM sales  WHERE ". $val . " group by `Entered By`";
	$export ="exportinvsales.php";    
	$print ="rptinvsales.php";
}
 $resultM=mysql_query($queryM) or die(mysql_error());
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Stock - POS Management System</title>
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
					<li class="open active">
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
						<li class="active"><a href="accreport.php"><i class="icon-double-angle-right"></i> Reports</a></li>
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
							<li class="active">System Log</li>
						</ul><!--.breadcrumb-->
					</div><!--#breadcrumbs-->



					<div id="page-content" class="clearfix">
						
						<div class="page-header position-relative">
							<h1>Accounts <small><i class="icon-double-angle-right"></i> Reports</small></h1>
				</div>
    <!--/page-header-->

						

<div class="row-fluid">
<!-- PAGE CONTENT BEGINS HERE -->

<div class="row-fluid">
	<h3 class="header smaller lighter blue">Today's Sales </h3>
 </div>
<div class="row-fluid">
	<div class="span8">    
    <form action="accreport.php" method="get" >
    <label class="small-labels">Search By Date :</label>
    <span class="input-icon"><i class="icon-calendar"></i>
	<input id="date" class="input-large" name="date" type="text" data-date-format="yyyy-mm-dd"></input>
	</span><br/>
    <button class="btn btn-small btn-info" value="submit" name="action" type="submit">Submit</button> 
    </form>
    </div>
    <div class="span4">   
      <p>
    <a class="btn btn-small btn-yellow" href="<?php echo $export?>"><i class="icon-table"></i><span> Excel Report</span></a>
    <a class="btn btn-small btn-info" href="<?php echo $print ?>"><i class="icon-print"></i><span> Print Report</span></a>
    </p>
    </div>
  </div>
 <div class="row-fluid">
	<div class="table-header">
		Results for "Individual Sales Report (<?php echo " $date "; ?>)"
	</div>
	
		<table id="table_bug_report" class="table table-striped table-bordered table-hover">
			<?php
   echo "
   <thead>
	 <TR>
	 <TH>S/No &nbsp;</TH>
	 <TH>Stock Name</TH>
	 <TH>Qnty Sold</TH>
     <TH>Unit Price</TH>
	 <TH>Total Sales</TH>
	 <TH>Discount</TH>
	 <TH>Sales Amount</TH>
	 </TR>
	 </thead> <tbody>";

   while(list($staf, $idd,$datt)=mysql_fetch_row($resultM))
   {     
	 echo "<TR>
	   <TD colspan='7'>
	   <b><font color='#1BCD50' style='font-size: 10pt'>Today's Sales By " . strtoupper($staf) . "</font></b>
	   </TD>
	   </TR>";	  
	 
     $query="
	 SELECT distinct(`Stock Name`) as stock, `ID`,`Sales Date` FROM sales  WHERE `Entered By` ='".$staf."' AND `Sales Date`='" . $datt . "'group by `Stock Name`";
     
	 $results=mysql_query($query) or die(mysql_error());
     $i=0;
     while(list($name,$id,$date)=mysql_fetch_row($results))
     {
	$query="
	 SELECT `ID`,`Sales Date`,`Stock Name`,`Stock Code`,SUM(`Qnty Sold`) AS qnty, `Unit Cost`, SUM(`Deposit`) AS depot,SUM(`Discount`) AS disc,`Paid` 
	 FROM sales
	 WHERE `Stock Name`='$name' AND `Entered By` ='$staf' and `Sales Date`='" . $datt . "'";
	 $result=mysql_query($query) or die(mysql_error());
	 
	 list($id,$date,$name,$code,$qnty,$price,$deposit,$discount,$paid)=mysql_fetch_row($result);
	 
       $total=number_format(($qnty*$price),2);
       $bal= number_format((($qnty*$price)-$deposit-$discount),2);
       $qnty=number_format($qnty,0);
       $price=number_format($price,2);
       $deposit=number_format($deposit,2);
       $discount=number_format($discount,2);
 
       $i=$i+1;
       echo "<TR>
	   <TD>$i</TD>
	   <TD>$name</TD>
	   <TD>$qnty</TD>
	   <TD>$price</TD>
	   <TD>$total</TD>
       <TD>$discount</TD>
	   <TD>$bal</TD>
	   </TR>";
     }
	 
     $queryy="SELECT sum(`Qnty Sold`) as Qnty,sum(`Deposit`) as Deposit,sum(`Discount`) as Discount,sum((`Qnty Sold`*`Unit Cost`)) as Totsum,sum((`Qnty Sold`*`Unit Cost`)-`Discount`) as balsum FROM sales WHERE `Entered By` ='$staf' and `Sales Date`='" . $datt . "'";
     $resultt=mysql_query($queryy);
     $roww = mysql_fetch_array($resultt);
     $totsum=number_format($roww['Totsum'],2);
     $balsum= number_format($roww['balsum'],2);
     $qqnty=number_format($roww['Qnty'],0);
     $ddeposit=number_format($roww['Deposit'],2);
     $ddiscount=number_format($roww['Discount'],2);
     echo "<TR>
	 <TH>&nbsp;</TH>
	 <TH>TOTAL SALES</TH>
	 <TH>$qqnty</TH>
     <TH>&nbsp;</TH>
	 <TH>$totsum</TH>
	 <TH>$ddiscount</TH>
	 <TH>$balsum</TH>
	 </TR> "; 
	 echo '</tbody>';
   }
			?>
		</table>

</div>
<hr></hr>
<p>&nbsp;</p>            
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
