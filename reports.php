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

 $cmbReport = @$_REQUEST["cmbReport"];
 $cmbTable=@$_REQUEST['cmbTable']; 
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Reports - KONUL [ POS Management System ]</title>
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
					
					<li class="active">
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
							<li class="active">Reports</li>
						</ul><!--.breadcrumb-->
					</div><!--#breadcrumbs-->



					<div id="page-content" class="clearfix">
						
						<div class="page-header position-relative">
							<h1>Reports<small><i class="icon-double-angle-right"></i></small></h1>
				</div>
    <!--/page-header-->

						

<div class="row-fluid">
<!-- PAGE CONTENT BEGINS HERE -->

<div class="row-fluid">
	<h3 class="header smaller lighter blue">List of Reports </h3>
    <p>
    <form  action="reports.php" method="GET" class="headers">
<table border="0">
  <tr>
    <td><span class="font">Select Report:</span>&nbsp;
      <br />
      <select name="cmbReport" size="1" class="select">
        <option selected>Select Report</option>
        <option>Sales Report</option>
      <!--  <option>Stock Report</option>
        <option>Sales Report (Daily)</option>        
        <option>Suppliers Report</option>-->
        <option>Daily Profit Report</option>
        <option>Requisition Report</option>
        <option>Re-Stock Report</option>
      </select></td>
    <td>&nbsp;<span class="font">Select Criteria: &nbsp;</span>
      <br />
      <select name="cmbTable" size="1" class="select">
        <option selected>Select Criteria</option>
        <option>Daily</option>
        <!--<option>Date Range</option>-->
        <option>Weekly</option>
        <option>Monthly</option>
        <option>Quarterly</option>
        <option>Yearly</option>
      </select></td>
  </tr>
  <tr>
    <td colspan="2">
    	<input name="submit" type="submit" class="btn btn-info btn-large" value="Open" />
    </td>
  </tr>
</table>
</form>
    </p>
	<div class="table-header">
		Results for "[Monthly Expenditure Report] "
	</div>	
		<table id="table_report" class="table table-striped table-bordered table-hover">
			<?php
############################################################################################################################

if (trim($cmbReport) == "- Select Report-" or trim($cmbTable) == "- Select Criteria -")
{
echo "<b>Please Select a Report and a Criteria from the drop-down box and click 'Open'.<b>";        
}
else if (trim($cmbReport)=="Sales Report")
{  
if (trim($cmbTable)=="Daily")
{
   @$val="`Sales Date`='" . date('Y-m-d') . "'"; 
   $purl = "printreport.php?cmbReport=Sales+Report&cmbTable=Daily&submit=Open";
   $eurl = "exportreport.php?cmbReport=Sales+Report&cmbTable=Daily&submit=Open";
   $queryM="SELECT distinct(`Stock Name`) as stock, `ID`,`Sales Date` FROM sales  WHERE ". $val . " group by `Stock Name`";
}
else if (trim($cmbTable)=="Weekly")
{
	$purl = "printreport.php?cmbReport=Sales+Report&cmbTable=Weekly&submit=Open";
   $eurl = "exportreport.php?cmbReport=Sales+Report&cmbTable=Weekly&submit=Open";
  @$val="(date(`Sales Date`)>'" . date('Y-m-d', strtotime('-1 week')) . "' and date(`Sales Date`)<'" . date('Y-m-d', strtotime('+1 day')) . "')";
  $queryM="SELECT distinct(`Stock Name`) as stock, `ID`,`Sales Date` FROM sales  WHERE ". $val . " group by `Stock Name`";
}
else if (trim($cmbTable)=="Monthly")
{
	$purl = "printreport.php?cmbReport=Sales+Report&cmbTable=Monthly&submit=Open";
   $eurl = "exportreport.php?cmbReport=Sales+Report&cmbTable=Monthly&submit=Open";
   @$val="(date(`Sales Date`)>'" . date('Y-m-d', strtotime('-1 month')) . "' and date(`Sales Date`)<'" . date('Y-m-d', strtotime('+1 day')) . "')";
   $queryM="SELECT distinct(`Stock Name`) as stock, `ID`,`Sales Date` FROM sales  WHERE ". $val . " group by `Stock Name`";
}
else if (trim($cmbTable)=="Quarterly")
{
	$purl = "printreport.php?cmbReport=Sales+Report&cmbTable=Quarterly&submit=Open";
   $eurl = "exportreport.php?cmbReport=Sales+Report&cmbTable=Quarterly&submit=Open";
  @$val="(date(`Sales Date`)>'" . date('Y-m-d', strtotime('-3 month')) . "' and date(`Sales Date`)<'" . date('Y-m-d', strtotime('+1 day')) ."')";
  $queryM="SELECT distinct(`Stock Name`) as stock, `ID`,`Sales Date` FROM sales  WHERE ". $val . " group by `Stock Name`";
}
else if (trim($cmbTable)=="Yearly")
{
	$purl = "printreport.php?cmbReport=Sales+Report&cmbTable=Yearly&submit=Open";
   $eurl = "exportreport.php?cmbReport=Sales+Report&cmbTable=Yearly&submit=Open";
  @$val="(date(`Sales Date`)>'" . date('Y-m-d', strtotime('-12 month')) . "' and date(`Sales Date`)<'" . date('Y-m-d', strtotime('+1 day')) ."')";
  $queryM="SELECT distinct(`Stock Name`) as stock, `ID`,`Sales Date` FROM sales  WHERE ". $val . " group by `Stock Name`";
}
if (empty($val) or @$val=="")
{  
  @$val="`Sales Date`='" . date('Y-m-d') . "'";
  $purl = "printreport.php?cmbReport=Sales+Report&cmbTable=Daily&submit=Open";
   $eurl = "exportreport.php?cmbReport=Sales+Report&cmbTable=Daily&submit=Open";
   $queryM="SELECT distinct(`Stock Name`) as stock, `ID`,`Sales Date` FROM sales  WHERE ". $val . " group by `Stock Name`";
}
 $resultM=mysql_query($queryM) or die(mysql_error());
   echo "
   <thead>
   <TR>
   <TH>Stock Code</TH>
   <TH>Stock Name</TH>
   <TH>Qnty Sold <span class='label label-success'>Sales Price</span></TH>
   <TH>Total Sales</TH>
   <TH>Deposit Amount <span class='label label-success'>Discount</span></TH>
   <TH>Balance</TH>
   <TH>Payment</TH>
   </TR>
   </thead><tbody>";
   
 while(list($stock, $idd,$datt)=mysql_fetch_row($resultM))
   {
   $result = mysql_query ("SELECT `ID`,`Sales Date`,`Stock Name`,`Stock Code`,SUM(`Qnty Sold`) AS qnty,`Unit Cost`,`Deposit`,SUM(`Discount`),`Paid` From `sales` where `Stock Name` = '$stock' AND ".$val ); 

	list($id,$date,$name,$code,$qnty,$price,$deposit,$discount,$paid)=mysql_fetch_row($result) ;	
     $total=number_format(($qnty*$price),2);
     $bal= number_format((($qnty*$price)-$discount),2);
     $qnty=number_format($qnty,0);
     $price=number_format($price,2);
     $deposit=number_format($deposit,2);
     $discount=number_format($discount,2);

     echo "
	 <TR>
	 <TD >$code</TD>
	 <TD> $name</TD>
	 <TD>$qnty <span class='label label-success'>$price</span></TD>
	 <TD>$total</TD>
	 <TD>$deposit <span class='label label-success'>$discount</span></TD>
	 <TD>$bal</TD>
	 <TD>$paid</TD>
	 </TR>";
 
}
    $queryy="SELECT sum(`Qnty Sold`) as Qnty,sum(`Deposit`) as Deposit,sum(`Discount`) as Discount,sum((`Qnty Sold`*`Unit Cost`)) as Totsum,sum((`Qnty Sold`*`Unit Cost`)-`Discount`) as balsum From `sales` WHERE " . @$val;
    $resultt=mysql_query($queryy);
    $roww = mysql_fetch_array($resultt);

    $totsum=number_format($roww['Totsum'],2);
    $balsum= number_format($roww['balsum'],2);
    $qqnty=number_format($roww['Qnty'],0);
    $ddeposit=number_format($roww['Deposit'],2);
    $ddiscount=number_format($roww['Discount'],2);
    echo "
	</tbody><tfoot>
	<TR>
	<TH>&nbsp;</TH>
	<TH>TOTAL SALES</TH>
	<TH>$qqnty</TH>
	<TH>$totsum </TH>
	<TH>$ddeposit <span class='label label-success'>$ddiscount</span></TH>
	<TH>$balsum </TH>
	<TH>&nbsp;</TH>
	</TR>
	</tfoot>";  
  
 }

############################################################################################################################
 else if ($cmbReport == "Daily Profit Report")
 {
if (trim($cmbTable)=="Daily")
{
   @$val="`Sales Date`='" . date('Y-m-d') . "'"; 
   $purl = "printreport.php?cmbReport=Daily+Profit+Report&cmbTable=Daily&submit=Open";
   $eurl = "exportreport.php?cmbReport=Daily+Profit+Report&cmbTable=Daily&submit=Open";
   $queryM="SELECT distinct(`Stock Name`) as stock, `ID`,`Sales Date` FROM sales  WHERE ". $val . " group by `Stock Name`";
}
else if (trim($cmbTable)=="Weekly")
{
  @$val="date(`Sales Date`)>'" . date('Y-m-d', strtotime('-1 week')) . "' and date(`Sales Date`)<'" . date('Y-m-d', strtotime('+1 day')) . "'";
  $purl = "printreport.php?cmbReport=Daily+Profit+Report&cmbTable=Weekly&submit=Open";
   $eurl = "exportreport.php?cmbReport=Daily+Profit+Report&cmbTable=Weekly&submit=Open";
   $queryM="SELECT distinct(`Stock Name`) as stock, `ID`,`Sales Date` FROM sales  WHERE ". $val . " group by `Stock Name`";
}
else if (trim($cmbTable)=="Monthly")
{
   @$val="date(`Sales Date`)>'" . date('Y-m-d', strtotime('-1 month')) . "' and date(`Sales Date`)<'" . date('Y-m-d', strtotime('+1 day')) . "'";
   $purl = "printreport.php?cmbReport=Daily+Profit+Report&cmbTable=Monthly&submit=Open";
   $eurl = "exportreport.php?cmbReport=Daily+Profit+Report&cmbTable=Monthly&submit=Open";
   $queryM="SELECT distinct(`Stock Name`) as stock, `ID`,`Sales Date` FROM sales  WHERE ". $val . " group by `Stock Name`";
}
else if (trim($cmbTable)=="Quarterly")
{
  @$val="date(`Sales Date`)>'" . date('Y-m-d', strtotime('-3 month')) . "' and date(`Sales Date`)<'" . date('Y-m-d', strtotime('+1 day')) . "'";
  $purl = "printreport.php?cmbReport=Daily+Profit+Report&cmbTable=Quarterly&submit=Open";
   $eurl = "exportreport.php?cmbReport=Daily+Profit+Report&cmbTable=Quarterly&submit=Open";
   $queryM="SELECT distinct(`Stock Name`) as stock, `ID`,`Sales Date` FROM sales  WHERE ". $val . " group by `Stock Name`";
}
else if (trim($cmbTable)=="Yearly")
{
  @$val="date(`Sales Date`)>'" . date('Y-m-d', strtotime('-12 month')) . "' and date(`Sales Date`)<'" . date('Y-m-d', strtotime('+1 day')) . "'";
  $purl = "printreport.php?cmbReport=Daily+Profit+Report&cmbTable=Yearly&submit=Open";
   $eurl = "exportreport.php?cmbReport=Daily+Profit+Report&cmbTable=Yearly&submit=Open";
   $queryM="SELECT distinct(`Stock Name`) as stock, `ID`,`Sales Date` FROM sales  WHERE ". $val . " group by `Stock Name`";
}
if (empty($val) or @$val=="")
{  
  @$val="`Sales Date`='" . date('Y-m-d') . "'";
  $purl = "printreport.php?cmbReport=Daily+Profit+Report&cmbTable=Daily&submit=Open";
   $eurl = "exportreport.php?cmbReport=Daily+Profit+Report&cmbTable=Daily&submit=Open";
   $queryM="SELECT distinct(`Stock Name`) as stock, `ID`,`Sales Date` FROM sales  WHERE ". $val . " group by `Stock Name`";
}
 $resultM=mysql_query($queryM) or die(mysql_error());
 
     echo "
	 <thead>
	 <TR>
	 <TH>S/No</TH>
	 <TH>Stock Code - Stock Name</TH>
	 <TH>Qnty Sold</TH>
	 <TH><span class='alert alert-block alert-info'>Cost Price </span><span class='alert alert-block alert-success'>Selling Price</span></TH>
	 <TH><span class='alert alert-block alert-info'>Total Cost</span><span class='alert alert-block alert-success'>Total Sales</span></TH>
	 <TH>Unit Profit</TH>
	 <TH>Total Profit</TH>
	 </TR>
	 </thead><tbody>";
$i=0; $sumuprofit=0; $sumtprofit=0;$sumqnty=0;$sumTcost =0; $sumTsales = 0;$sumUcost = 0; $sumSprice = 0;
 while(list($stock, $idd,$datt)=mysql_fetch_row($resultM))
   {
   $result = mysql_query ("SELECT `ID`,`Sales Date`,`Stock Name`,`Stock Code`,SUM(`Qnty Sold`) AS qnty,`Unit Cost`,`Deposit`,SUM(`Discount`),`Paid` From `sales` where `Stock Name` = '$stock' AND ".$val ); 


     $query="SELECT `ID`,`Sales Date`,`Stock Name`,`Stock Code`, SUM(`Qnty Sold`) AS qnty FROM `sales` where `Stock Name` = '$stock' AND ". $val . " ";
     $result=mysql_query($query);
     
     list($id,$date,$name,$code,$qnty)=mysql_fetch_row($result);	      
       $queryR="SELECT `ID`,`Stock Code`,`Unit Cost`,`Selling Price` FROM `stock` WHERE `Stock Code`='" . $code . "'";
       $resultR=mysql_query($queryR);
       $rowr = mysql_fetch_array($resultR);

       $ucost=$rowr['Unit Cost'];
       $sprice=$rowr['Selling Price'];
       $uprofit=$sprice-$ucost;

       $totalc=$qnty*$ucost;
       $totals=$qnty*$sprice;
       $tprofit=$totals-$totalc;

       $totalcost=number_format(($qnty*$ucost),2);
       $totalsales=number_format(($qnty*$sprice),2);
       $totalprofit=number_format($tprofit,2);
       $unitprofit=number_format($uprofit,2);

       $qnty=number_format($qnty,0);
       $sprice=number_format($sprice,2);
       $ucost=number_format($ucost,2);
 
       $i=$i+1;

       echo "	  
	   <TR>
	   <TD>$i</TD>
	   <TD>$code - $name</TD>
	   <TD>$qnty</TD>
	   <TD><span class='alert alert-block alert-info'>$ucost </span><span class='alert alert-block alert-success'>$sprice</span></TD>
	   <TD><span class='alert alert-block alert-info'>$totalcost</span><span class='alert alert-block alert-success'>$totalsales</span></TD>
	   <TD>$unitprofit</TD>
	   <TD>$totalprofit</TD>
	   </TR>";
       $sumuprofit += $unitprofit;
       $sumtprofit += $totalprofit;
	   $sumqnty += $qnty;
	   $sumTcost += $totalcost;
	   $sumTsales += $totalsales;
	   $sumUcost += $ucost;
	   $sumSprice += $sprice;
     }
	 

     $totsumUP=number_format($sumuprofit,2);
     $totsumTP= number_format($sumtprofit,2);
     echo "
	 </tbody><tfoot>
	 <TR>
	 <TH>&nbsp;</TH>
	 <TH>TOTAL</TH>
	 <TH>$sumqnty</TH>
	 <TH><span class='alert alert-block alert-info'>$sumUcost</span><span class='alert alert-block alert-success'>$sumSprice</span></TH>
	 <TH><span class='alert alert-block alert-info'>$sumTcost</span><span class='alert alert-block alert-success'>$sumTsales</span></TH>
	 <TH>".@$totsumUP ."</TH>
	 <TH>".@$totsumTP." &nbsp;</TH>
	 </TR> </tfoot>";
 }
#################################################################################################################################

 else if ($cmbReport == "Requisition Report")
 {

  if (trim($cmbTable)=="Daily")
  {
	 @$val="`Stock Date`='" . date('Y-d-m') . "'"; 
	 $purl = "printreport.php?cmbReport=Requisition+Report&cmbTable=Daily&submit=Open";
   	 $eurl = "exportreport.php?cmbReport=Requisition+Report&cmbTable=Daily&submit=Open";
  }
  else if (trim($cmbTable)=="Weekly")
  {
	@$val="`Stock Date`>'" . date('Y-d-m', strtotime('-3 month')) . "' and `Stock Date`<'" . date('Y-d-m', strtotime('+1 day')) . "'";  
   $purl = "printreport.php?cmbReport=Requisition+Report&cmbTable=Weekly&submit=Open";
   	 $eurl = "exportreport.php?cmbReport=Requisition+Report&cmbTable=Weekly&submit=Open";
  }
  else if (trim($cmbTable)=="Monthly")
  {
	@$val="`Stock Date`>'" . date('Y-d-m', strtotime('-3 month')) . "' and `Stock Date`<'" . date('Y-d-m', strtotime('+1 day')) . "'";  
   $purl = "printreport.php?cmbReport=Requisition+Report&cmbTable=Monthly&submit=Open";
   	 $eurl = "exportreport.php?cmbReport=Requisition+Report&cmbTable=Monthly&submit=Open";
  }
  else if (trim($cmbTable)=="Quarterly")
  {
   @$val="`Stock Date`>'" . date('Y-d-m', strtotime('-3 month')) . "' and `Stock Date`<'" . date('Y-d-m', strtotime('+1 day')) . "'";
   $purl = "printreport.php?cmbReport=Requisition+Report&cmbTable=Quarterly&submit=Open";
   $eurl = "exportreport.php?cmbReport=Requisition+Report&cmbTable=Quarterly&submit=Open";
  }
  else if (trim($cmbTable)=="Yearly")
  {
   @$val="`Stock Date`>'" . date('Y-d-m', strtotime('-12 month')) . "' and `Stock Date`<'" . date('Y-d-m', strtotime('+1 day')) . "'";
   $purl = "printreport.php?cmbReport=Requisition+Report&cmbTable=Yearly&submit=Open";
   $eurl = "exportreport.php?cmbReport=Requisition+Report&cmbTable=Yearly&submit=Open";
  }

   echo "
   <thead>
   <TR>
   <TH>Stock Code</TH>
   <TH>Stock Name</TH>
   <TH>Category </TH>
   <TH>Requisition Date</TH>
   <TH>Qnty Given</TH>
   <TH>Given To</TH>
   <TH>Location </TH>
   </TR>
   </thead><tbody>";
 
   @$result = mysql_query ("SELECT `Stock Code`, `Stock Name`,`Category`,`Stock Date`, `Qnty Given`, `Request By`, `Location` From `requisition` where " . $val . " order by `Stock Code`"); 


    while(list($stockcode,$stockname,$weight,$reqdate,$qntygiven,$reqby,$loc)=mysql_fetch_row(@$result)) 
    {	
      echo "<TR>
	  <TD>$stockcode </TD>
	  <TD>$stockname</TD>
	  <TD>$weight</TD>
	  <TD>$reqdate</TD>
	  <TD>$qntygiven</TD>
	  <TD>$reqby</TD>
	  <TD>$loc</TD>
	  </TR>";
    }
	echo "</tbody>";
 }
 ############################################################################################################################
 else if ($cmbReport == "Re-Stock Report")
 {
@$filter=$_REQUEST["filter"];

   if (trim($cmbTable)=="Daily")
  {
	 @$val="`Stock Date`='" . date('Y-d-m') . "'"; 
	 $purl = "printreport.php?cmbReport=Re-Stock+Report&cmbTable=Daily&submit=Open";
   	 $eurl = "exportreport.php?cmbReport=Re-Stock+Report&cmbTable=Daily&submit=Open";
  }
  else if (trim($cmbTable)=="Weekly")
  {
	@$val="`Stock Date`>'" . date('Y-d-m', strtotime('-3 month')) . "' and `Stock Date`<'" . date('Y-d-m', strtotime('+1 day')) . "'";  
   $purl = "printreport.php?cmbReport=Re-Stock+Report&cmbTable=Weekly&submit=Open";
   	 $eurl = "exportreport.php?cmbReport=Re-Stock+Report&cmbTable=Weekly&submit=Open";
  }
  else if (trim($cmbTable)=="Monthly")
  {
	@$val="`Stock Date`>'" . date('Y-d-m', strtotime('-3 month')) . "' and `Stock Date`<'" . date('Y-d-m', strtotime('+1 day')) . "'";  
   $purl = "printreport.php?cmbReport=Re-Stock+Report&cmbTable=Monthly&submit=Open";
   	 $eurl = "exportreport.php?cmbReport=Re-Stock+Report&cmbTable=Monthly&submit=Open";
  }
  else if (trim($cmbTable)=="Quarterly")
  {
   @$val="`Stock Date`>'" . date('Y-d-m', strtotime('-3 month')) . "' and `Stock Date`<'" . date('Y-d-m', strtotime('+1 day')) . "'";
   $purl = "printreport.php?cmbReport=Re-Stock+Report&cmbTable=Quarterly&submit=Open";
   $eurl = "exportreport.php?cmbReport=Re-Stock+Report&cmbTable=Quarterly&submit=Open";
  }
  else if (trim($cmbTable)=="Yearly")
  {
   @$val="`Stock Date`>'" . date('Y-d-m', strtotime('-12 month')) . "' and `Stock Date`<'" . date('Y-d-m', strtotime('+1 day')) . "'";
   $purl = "printreport.php?cmbReport=Re-Stock+Report&cmbTable=Yearly&submit=Open";
   $eurl = "exportreport.php?cmbReport=Re-Stock+Report&cmbTable=Yearly&submit=Open";
  }

   echo "
   <thead>
   <TR>
   <TH>Stock Code - Stock Name</TH>
   <TH>Category</TH>
   <TH>Re-Stock Date</TH>
   <TH>Qnty Acquired</TH>
   <TH>Unit Cost</TH>
   <TH>Total Cost</TH>
   <TH>Location</TH>
   </TR>
   </thead><tbody>";
 
   $result = mysql_query ("SELECT `Stock Code`, `Stock Name`,`Category`,`Stock Date`, `Qnty Added`, `Unit Cost`, `Location` From `restock` where " . @$val . " order by `Stock Code`"); 


    while(list($stockcode,$stockname,$colour,$restockdate,$qntyadded,$unitcost,$loc)=mysql_fetch_row($result)) 
    {	$totv=$unitcost*$qntyadded;
      echo "<TR>
	  <TD>$stockcode - $stockname</TD>
	  <TD>$colour</TD>
	  <TD>$restockdate</TD>
	  <TD>$qntyadded</TD>
	  <TD>$unitcost</TD>
	  <TD>$totv</TD>
	  <TD>$loc</TD>
	  </TR>";
    }
echo "</tbody>";
 }
 else if ($cmbReport == "Stock Report")
 {
  
 if (trim($cmbTable)=="Weekly")
  {
   @$val="date(`Stock Date`)>'" . date('Y-m-d', strtotime('-1 week')) . "' and date(`Stock Date`)<'" . date('Y-m-d', strtotime('+1 day')) . "'";
  }
  else if (trim($cmbTable)=="Monthly")
  {
   @$val="date(`Stock Date`)>'" . date('Y-m-d', strtotime('-1 month')) . "' and date(`Stock Date`)<'" . date('Y-m-d', strtotime('+1 day')) . "'";
  }
  else if (trim($cmbTable)=="Quarterly")
  {
   @$val="date(`Stock Date`)>'" . date('Y-m-d', strtotime('-3 month')) . "' and date(`Stock Date`)<'" . date('Y-m-d', strtotime('+1 day')) . "'";
  }
  else if (trim($cmbTable)=="Yearly")
  {
   @$val="date(`Stock Date`)=" . date('Y');
  }

   echo "
   <thead>
   <TR>
   <TH>Stock Code</TH>
   <TH>Stock Name</TH>
   <TH>Unit Price</TH>
   <TH>Unit in Stock</TH>
   <TH>Re-Order Level</TH>
   <TH>Category</TH>
   <TH>Location</TH>
   </TR>
   </thead><tbody>";

   $result = mysql_query ("SELECT stock.`Stock Code`, `stock`.`stock Name`,`stock`.`category`,stock.`Units in Stock` , stock.`Location` ,  `Selling Price` , `Reorder Level` FROM `stock` GROUP BY stock.`Stock Code`");

    while(list($stockcode,$name,$colour,$unit,$loc,$price,$reorder)=mysql_fetch_row($result)) 
    {
      echo "
	  <TR>
	  <TD>$stockcode</TD>
	  <TD>$name</TD>
	  <TD>$price</TD>
	  <TD>$unit</TD>
	  <TD>$reorder</TD>
	  <TD>$colour</TD>
	  <TD>$loc</TD>
	  </TR>";
    }
echo "</tbody>";
 }
 ##########################################################################################################################
 else if ($cmbReport == "Non-Moving Stock Report")
 {
  if (trim($cmbTable)=="Daily")
  {
   @$val="`Sales Date`=date('$filter')";
  }
  else if (trim($cmbTable)=="Weekly")
  {
   @$val="date(`Sales Date`)=" . date('W');
  }
  else if (trim($cmbTable)=="Monthly")
  {
   @$val="date(`Sales Date`)=" . date('m');
  }
  else if (trim($cmbTable)=="Yearly")
  {
   @$val="date(`Sales Date`)=" . date('Y');
  }
   echo "<TR bgcolor='#006699'><TH><b> Stock Code </b>&nbsp;</TH><TH><b> Stock Name </b>&nbsp;</TH><TH><b> Sales Date </b>&nbsp;</TH><TH><b> Qnty Sold </b>&nbsp;</TH><TH><b> Unit Cost </b>&nbsp;</TH><TH><b> Total Cost </b>&nbsp;</TH><TH><b> Paid </b>&nbsp;</TH></TR>";
 
   $result = mysql_query ("SELECT `Stock Code`, `Stock Name`,`Sales Date`, `Qnty Sold`, `Unit Cost`, `Total Cost`, `Paid` From `sales` where " . @$val . " order by `Stock Code`"); 

   if(mysql_num_rows($result) == 0)
   { 
        echo("<span class='font'>Nothing to Display!</span><br>"); 
   } 
    @$val='Daily';

    while(list($stockcode,$stockname,$salesdate,$qntysold,$unitcost,$totalcost,$paid)=mysql_fetch_row($result)) 
    {	
      echo "<TR><TD>$stockcode </TD><TD>$stockname</TD><TD>$salesdate</TD><TD>$qntysold</TD><TD>$unitcost</TD><TD>$totalcost</TD><TD>$paid</TD></TR>";
    }

 }

  ?>
		</table>
        
</div>
<hr></hr>
<p>
<?php
if (isset($cmbReport) && isset($cmbTable))
{
	if (trim($cmbReport) != "- Select Report-" or trim($cmbTable) != "- Select Criteria -")
	{
		echo "<a href='$purl' class='btn btn-small btn-info'><i class='icon-print'></i><span>Print Report</span></a>"; 
		echo " <a href='$eurl' class='btn btn-small btn-success'><span>Excel Report</span></a>";      
	}     
}  

?>
</p>
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

		</script>
	</body>
</html>
