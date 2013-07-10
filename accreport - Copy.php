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


global $Tit;
@$Tit=$_REQUEST['Tit'];
@$store=$_REQUEST['store'];


if(isset($_REQUEST["cmbReport"]) && !empty($_REQUEST["cmbReport"])){
 $cmbReport = $_REQUEST["cmbReport"];
  }else{$cmbReport='';}
  
   if(isset($_REQUEST['cmbTable']) && !empty($_REQUEST['cmbTable'])){
  $cmbTable=$_REQUEST['cmbTable']; 
  }else{$cmbTable='';}
   
   if(isset($_REQUEST["filter"]) && !empty($_REQUEST["filter"])){
 $filter=$_REQUEST["filter"];
  }else{$filter='';} 

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
	<h3 class="header smaller lighter blue">List of Accounting Reports </h3>
    <p>
    <form  action="accreport.php" method="GET">
Select a Report :<br />
 <select size="1" name="cmbReport">
  <option selected>- Select Report-</option>
  <option>Monthly Sales</option>
  <option>Daily Cash</option>
  <option>Weekly Cash</option>
  <option>Monthly Cash Summary</option>
  <option>Yearly Cash Summary</option>
  <option>Daily Cheque</option>
  <option>Monthly Cheque</option>
  <option>Yearly Cheque</option>
  <!--<option>Monthly Expenditure</option> 
  <option>Analysis</option>
  <option>Profit & Loss</option>
  <option>Balance Sheet</option>-->
 </select><br />
   <input type="submit" value="Open" class="btn btn-info btn-large" name="submit">
</form>
    </p>
	<div class="table-header">
		Results for "
       <?php
if (trim($cmbReport)=="Monthly Expenditure"){  
	echo "[Monthly Expenditure Report]";
}else if (trim($cmbReport)=="Monthly Sales"){
	 echo "[Monthly Sales Report]";
}else if (trim($cmbReport)=="Daily Cash"){
	echo "[Daily Cash Report]";
}else if (trim($cmbReport)=="Weekly Cash"){
	echo "[Weekly Cash Report]";
}else if ($cmbReport == "Monthly Cash Summary"){
	echo "[Monthly Cash Report]";
}else if ($cmbReport == "Yearly Cash Summary"){
	echo "[Yearly Cash Report]";
}else if (trim($cmbReport)=="Daily Cheque"){
	echo "[Daily Cheque Report]";
}else if (trim($cmbReport)=="Monthly Cheque"){  
	echo "[Monthly Cheque Report]";
}else if (trim($cmbReport)=="Yearly Cheque"){  
	echo "[Yearly Cheque Report]";
}
       ?>"
	</div>
	
		<table id="table_report" class="table table-striped table-bordered table-hover">
			<?php
						
if (trim($cmbReport) == "- Select Report-")
{
  echo "<b>Please Select a Report from the drop-down box and click 'Open'.<b>";        
}
else if (trim($cmbReport)=="Monthly Expenditure")
{  
   echo "
   <thead>
   <TR>
   <TH><small> Item </small></TH>
   <TH><small> Budget </small></TH>
   <TH><small> Actual </small></TH>
   <TH><small> Variance </small></TH>
   <TH><small> Remark </small></TH>
   </TR>
   </thead>
   <tbody>
   ";
 
   $result = mysql_query ("SELECT `Classification`,`Budget`,sum(`Amount`) as amt, `Particulars` FROM `cash` inner join `budget` on `cash`.`Classification`=`budget`.`Class` where month(`cash`.`Date`)=month(`budget`.`Month`) and year(`cash`.`Date`)=year(`budget`.`Month`) group by `Classification`"); 
 
   while(list($classi,$budget,$amt,$remark)=mysql_fetch_row($result)) 
    {	
      $variance=$budget-$amt;
      $variance=number_format($variance,2);
      $budget=number_format($budget,2);
      $amt=number_format($amt,2);
      echo "	  
	  <TR>
	  <TD>$classi </TD>
	  <TD align='right'>$budget</TD>
	  <TD align='right'>$amt</TD>
	  <TD align='right'>$variance</TD>
	  <TD>$remark </TD>
	  </TR>";
    }

   $res = mysql_query ("SELECT sum(`Budget`) as budgeti,sum(`Amount`) as amti,(sum(`Budget`)-sum(`Amount`)) as vari FROM `cash` inner join `budget` on `cash`.`Classification`=`budget`.`Class` where month(`cash`.`Date`)=month(`budget`.`Month`) and year(`cash`.`Date`)=year(`budget`.`Month`) group by `Classification`"); 
   $rowsum = mysql_fetch_array($res);
   $amti=$rowsum['amti'];
   $amti=number_format($amti,2);
   $budgeti=$rowsum['budgeti'];
   $budgeti=number_format($budgeti,2);
   $vari=$rowsum['vari'];
   $vari=number_format($vari,2);
   echo "
   </tbody>
   <tfoot>
   <TR>
   <TH>Total</TH>
   <TH>$budgeti</TH>
   <TH>$amti</TH>
   <TH>$vari</TH>
   <TH></TH>
   </TR>
   </tfoot>
   ";
   }
  else if (trim($cmbReport)=="Monthly Sales")
	{
   

 echo "
 <thead>
 <TR>
 <TH>Date</TH>
 <TH>Reciept No</TH>
 <TH>Amount</TH> 
 <TH>Source</TH>
 <TH>Amount (Bank)</TH>
 <TH>Bank Name</TH>
 <TH> &nbsp;</TH>
 </TR>
 </thead><tbody>
 ";
 
   $result = mysql_query ("SELECT `Sales Date`,`Entered By`,`Qnty Sold`,`Unit Cost`, `Receipt`,`Bank Amount`,`Bank Name`,`Bank Date`,`Imprest Amount`,`Imprest Detail`,`Imprest Date`,`Slip No` FROM `sales` where month(`sales`.`Sales Date`)=month('" . Date('Y-m-d') . "') and year(`sales`.`Sales Date`)=year('" . Date('Y-m-d') . "')"); 
 
while(list($sdate,$sname,$qnty,$cost,$receipt,$bamt,$bname,$bdate,$impamt,$impdet,$impdate,$slip)=mysql_fetch_row($result)) 
    {
      $tcost=number_format(($qnty*$cost),2);
      $bamt=number_format($bamt,2);
      $impamt=number_format($impamt,2);
      echo "<TR>
	  <TD>$sdate</TD>
	  <TD>$receipt</TD>
	  <TD>$tcost</TD>
	  <TD>$sname</TD>
	  <TD>$bamt </TD>
	  <TD>$bname </TD>
	  <TD> &nbsp;</TD>
	  </TR>	  
	  ";
    }
 
   $res = mysql_query ("SELECT sum((`Qnty Sold`*`Unit Cost`)) as tsoc, sum(`Bank Amount`) as amtb,sum(`Imprest Amount`) as amti FROM `sales` where month(`sales`.`Sales Date`)=month('" . Date('Y-m-d') . "') and year(`sales`.`Sales Date`)=year('" . Date('Y-m-d') . "')"); 
   $rowsum = mysql_fetch_array($res);
   $amti=$rowsum['amti'];
   $amti=number_format($amti,2);
   $tsoc=$rowsum['tsoc'];
   $tsoc=number_format($tsoc,2);
   $amtb=$rowsum['amtb'];
   $amtb=number_format($amtb,2);
   echo "</tbody>
   <tfoot>
   <TR>
   <TH> &nbsp;</TH>
   <TH>Total</TH>
   <TH>$tsoc</TH>
   <TH> &nbsp;</TH>
   <TH> $amtb</TH>
   <TH> $amti</TH>   
   <TH> &nbsp;</TH>
   </TR>
   </tfoot>";
}else if (trim($cmbReport)=="Daily Cash")
{
  
   echo "<thead>
   <TR>
   <TH>Type</TH>
   <TH>Category </TH>
   <TH>Details</TH>
   <TH>Date</TH>
   <TH> Amount</TH>
   <TH> &nbsp;</TH>
   <TH> &nbsp;</TH>
   </TR>
   </thead></tbody>";
 
   $result = mysql_query ("SELECT `Type`,`Classification`,`Particulars`, `Date`, `Amount` FROM `cash` "); 
 

    while(list($type,$class,$details,$date,$amount)=mysql_fetch_row($result)) 
    {	 $amount=number_format($amount,2);
      echo "<TR>
	  <TD>$type </TD>
	  <TD>$class </TD>
	  <TD>$details</TD>
	  <TD>$date</TD>
	  <TD>$amount</TD>
	  <TD> &nbsp;</TD>
  	  <TD> &nbsp;</TD>	  
	  </TR>";
    }

   $res = mysql_query ("SELECT sum(`Amount`) as amt From `cash`");
   $rowsum = mysql_fetch_array($res) or die(mysql_error());
   $amt=$rowsum['amt'];
   $amt=number_format($amt,2);
   echo "
   </tbody><tfoot>
   <TR>
   <TH> &nbsp;</TH>
   <TH> &nbsp;</TH>
   <TH> &nbsp;</TH>
   <TH>Total</TH>
   <TH> $amt</TH>
   <TH> &nbsp;</TH>
   <TH> &nbsp;</TH>   
   </TR>
   </tfoot>";

}
   else if (trim($cmbReport)=="Weekly Cash")
{
	echo "
	<thead><TR>
		<TH> Type</TH>
		<TH>Date </TH>
		<TH>Category </TH>
		<TH>Details</TH>
		<TH>Amount </TH>
		<TH> &nbsp;</TH>
		<TH> &nbsp;</TH>
	</TR>
	</thead><tbody>";
  $filter2 = date('Y-m-d',time());
  $filter = date('Y-m-d',strtotime('-1 week'));
  $val="`Date` >='" . $filter . "' and `Date` <='" . $filter2 . "'"; 

   $result = mysql_query ("SELECT `Type`,`Classification`,`Particulars`, `Date`, `Amount` FROM `cash` where  " . $val . " order by `Type` desc"); 
 
	while(list($type,$class,$details,$date,$amount)=mysql_fetch_row($result)) 
    {	 $amount=number_format($amount,2);
      echo "
	  <TR>
		  <TD>$type </TD>
		  <TD>$date</TD>
		  <TD>$class </TD>
		  <TD>$details</TD>
		  <TD>$amount</TD>
		  <TD> &nbsp;</TD>
		  <TD> &nbsp;</TD>
	  </TR>";
    }

   $res = mysql_query ("SELECT sum(`Amount`) as amt From `cash` where  " . $val);
   $rowsum = mysql_fetch_array($res);
   $amt=$rowsum['amt'];
   $amt=number_format($amt,2);
   echo "
   </tbody><tfoot>
   <TR>
   	<TH> &nbsp;</TH>
	<TH> &nbsp;</TH>
	<TH> &nbsp;</TH>	
   	<TH>Total</TH>
   	<TH>$amt</TH>
	<TH> &nbsp;</TH>
	<TH> &nbsp;</TH>
   </TR>
   </tfoot>";

}else if ($cmbReport == "Monthly Cash Summary"){
 echo "
 <thead><TR>
 <TH>Type </TH>
 <TH>Category</TH>
 <TH>Details</TH>
 <TH>Date </TH>
 <TH>Amount</TH>
 <TH> &nbsp;</TH>
 <TH> &nbsp;</TH>
 </TR>
 </thead><tbody>";

 $val="`Date` like '".date('Y-m',time()) ."%'";
 
   $result = mysql_query ("SELECT `Type`,`Classification`,`Particulars`, `Date`, `Amount` FROM `cash` where " . $val .""); 
 
	while(list($type,$class,$details,$date,$amount)=mysql_fetch_row($result)) 
    {	      $amount=number_format($amount,2);
      echo "
	  <TR>
	  <TD>$type </TD>
	  <TD>$class </TD>
	  <TD>$details</TD>
	  <TD>$date</TD>
	  <TD>$amount</TD>
	  <TD> &nbsp;</TD>
	  <TD> &nbsp;</TD>
	  </TR>
	  ";
    }

   $res = mysql_query ("SELECT sum(`Amount`) as amt From `cash` where " . $val); 
   $rowsum = mysql_fetch_array($res);
   $amt=$rowsum['amt'];
   $amt=number_format($amt,2);
   echo "
   </tbody><tfoot>
   <TR>
 	<TH> &nbsp;</TH>
	<TH> &nbsp;</TH>
	<TH> &nbsp;</TH>	
   	<TH>Total</TH>
   	<TH>$amt</TH>
	<TH> &nbsp;</TH>
	<TH> &nbsp;</TH>
   </TR>
   </tfoot>";
}else if ($cmbReport == "Yearly Cash Summary"){
 echo "
 <thead><TR>
 <TH>Type </TH>
 <TH>Category</TH>
 <TH>Details</TH>
 <TH>Date </TH>
 <TH>Amount</TH>
 <TH> &nbsp;</TH>
 <TH> &nbsp;</TH>
 </TR>
 </thead><tbody>";

 $val="`Date` like '".date('Y',time()) ."%'";
 
   $result = mysql_query ("SELECT `Type`,`Classification`,`Particulars`, `Date`, `Amount` FROM `cash` where " . $val .""); 
 
	while(list($type,$class,$details,$date,$amount)=mysql_fetch_row($result)) 
    {	      $amount=number_format($amount,2);
      echo "
	  <TR>
	  <TD>$type </TD>
	  <TD>$class </TD>
	  <TD>$details</TD>
	  <TD>$date</TD>
	  <TD>$amount</TD>
	  <TH> &nbsp;</TH>
	  <TH> &nbsp;</TH>
	  </TR>
	  ";
    }

   $res = mysql_query ("SELECT sum(`Amount`) as amt From `cash` where " . $val); 
   $rowsum = mysql_fetch_array($res);
   $amt=$rowsum['amt'];
   $amt=number_format($amt,2);
   echo "
   </tbody><tfoot>
   <TR>
 	<TH> &nbsp;</TH>
	<TH> &nbsp;</TH>
	<TH> &nbsp;</TH>	
   	<TH>Total</TH>
   	<TH>$amt</TH>
	<TH> &nbsp;</TH>
	<TH> &nbsp;</TH>
   </TR>
   </tfoot>";
}else if (trim($cmbReport)=="Daily Cheque")
{
   echo "
   <thead>
   <TR>
   <TH>Type </TH> 
   <TH>Details</TH>
   <TH>Date </TH>
   <TH>Amount</TH>
   <TH>Bank</TH>
   <TH>Cheque #</TH>
   <TH>&nbsp;</TH>
   </TR>
   </thead><tbody>";
   $result = mysql_query ("SELECT `Type`,`Particulars`, `Date`, `Amount`,`Bank`,`Cheque No` FROM `cheque` "); 
 
	while(list($type,$details,$date,$amount,$bank,$cheque)=mysql_fetch_row($result)) 
    {	
      echo "
	  <TR>
	  <TD>$type </TD>
	  <TD>$details</TD>
	  <TD>$date</TD>
	  <TD>$amount</TD>
	  <TD>$bank</TD>
	  <TD>$cheque</TD>
	  <TD> &nbsp;</TD>
	  </TR>";
    }

   $res = mysql_query ("SELECT sum(`Amount`) as amt From `cheque` where `Date` like '$filter%'");
   $rowsum = mysql_fetch_array($res);
   $amt=$rowsum['amt'];
   $amt=number_format($amt,2);
   echo "
   </tbody><tfoot>
   <TR>
   <TR>
    <TH>&nbsp;</TH>
 	<TH> &nbsp;</TH>
	<TH>Total </TH>
	<TH> $amt</TH>	
   	<TH>&nbsp;</TH>
   	<TH>&nbsp;</TH>
	<TH>&nbsp;</TH>
   </TR>
	</TR>
	</tfoot>";


}
else if (trim($cmbReport)=="Monthly Cheque")
{  
	$val="`Date` like '".date('Y-m',time()) ."%'";
 
    echo "
   <thead>
   <TR>
   <TH>Type </TH> 
   <TH>Details</TH>
   <TH>Date </TH>
   <TH>Amount</TH>
   <TH>Bank</TH>
   <TH>Cheque #</TH>
   <TH>&nbsp;</TH>
   </TR>
   </thead><tbody>";
 
   $result = mysql_query ("SELECT `Type`,`Particulars`, `Date`, `Amount`,`Bank`,`Cheque No` FROM `cheque` where " . $val . " "); 
 
	while(list($type,$details,$date,$amount,$bank,$cheque)=mysql_fetch_row($result)) 
    {	 $amount=number_format($amount,2);
      echo "
	  <TR>
	  <TD>$type </TD>
	  <TD>$details</TD>
	  <TD>$date</TD>
	  <TD>$amount</TD>
	  <TD>$bank</TD>
	  <TD>$cheque</TD>
	  <TD> &nbsp;</TD>
	  </TR>";
    }

   $res = mysql_query ("SELECT sum(`Amount`) as amt From `cheque` where " . $val); 
   $rowsum = mysql_fetch_array($res);
   $amt=$rowsum['amt'];
   $amt=number_format($amt,2);
    echo "
   </tbody><tfoot>
   <TR>
   <TR>
    <TH>&nbsp;</TH>
 	<TH> &nbsp;</TH>
	<TH>Total </TH>
	<TH> $amt</TH>	
   	<TH>&nbsp;</TH>
   	<TH>&nbsp;</TH>
	<TH>&nbsp;</TH>
   </TR>
	</TR>
	</tfoot>";

}
else if (trim($cmbReport)=="Yearly Cheque")
{  
	$val="`Date` like '".date('Y',time()) ."%'"; 
    echo "
   <thead>
   <TR>
   <TH>Type </TH> 
   <TH>Details</TH>
   <TH>Date </TH>
   <TH>Amount</TH>
   <TH>Bank</TH>
   <TH>Cheque #</TH>
   <TH>&nbsp;</TH>
   </TR>
   </thead><tbody>";
 
   $result = mysql_query ("SELECT `Type`,`Particulars`, `Date`, `Amount`,`Bank`,`Cheque No` FROM `cheque` where " . $val . " "); 
 
	while(list($type,$details,$date,$amount,$bank,$cheque)=mysql_fetch_row($result)) 
    {	 $amount=number_format($amount,2);
      echo "
	  <TR>
	  <TD>$type </TD>
	  <TD>$details</TD>
	  <TD>$date</TD>
	  <TD>$amount</TD>
	  <TD>$bank</TD>
	  <TD>$cheque</TD>
	  <TD> &nbsp;</TD>
	  </TR>";
    }

   $res = mysql_query ("SELECT sum(`Amount`) as amt From `cheque` where " . $val); 
   $rowsum = mysql_fetch_array($res);
   $amt=$rowsum['amt'];
   $amt=number_format($amt,2);
    echo "
   </tbody><tfoot>
   <TR>
   <TR>
    <TH>&nbsp;</TH>
 	<TH> &nbsp;</TH>
	<TH>Total </TH>
	<TH> $amt</TH>	
   	<TH>&nbsp;</TH>
   	<TH>&nbsp;</TH>
	<TH>&nbsp;</TH>
   </TR>
	</TR>
	</tfoot>";
}
  ?>
		</table><br>
        <p>
        <?php

				if (trim($cmbReport)=="Monthly Expenditure"){ 
				echo '
				<a class="btn btn-small btn-yellow" href="exptaccreport.php?cmbReport=Monthly+Sales&submit=Open"><span>Excel</span></a>
    			<a class="btn btn-small btn-info" href="printaccreport.php?cmbReport=Monthly+Sales&submit=Open"><i class="icon-print"></i><span> Print</span></a>';
				}else if (trim($cmbReport)=="Monthly Sales"){
				echo '
				<a class="btn btn-small btn-yellow" href="exptaccreport.php?cmbReport=Monthly+Sales&submit=Open"><span>Excel</span></a>
    			<a class="btn btn-small btn-info" href="printaccreport.php?cmbReport=Monthly+Sales&submit=Open"><i class="icon-print"></i><span> Print</span></a>';
				}else if (trim($cmbReport)=="Daily Cash"){
				echo '
				<a class="btn btn-small btn-yellow" href="accreport.php?expreport=Monthly+Cash+Summary"><span>Excel</span></a>
    			<a class="btn btn-small btn-info" href="prntreport=Monthly+Cash+Summary"><i class="icon-print"></i><span> Print</span></a>';
				}else if (trim($cmbReport)=="Weekly Cash"){
				echo '
				<a class="btn btn-small btn-yellow" href="accreport.php?expreport=Monthly+Cash+Summary"><span>Excel</span></a>
    			<a class="btn btn-small btn-info" href="prntreport=Monthly+Cash+Summary"><i class="icon-print"></i><span> Print</span></a>';
				}else if ($cmbReport == "Monthly Cash Summary"){
				echo '
				<a class="btn btn-small btn-yellow" href="accreport.php?expreport=Monthly+Cash+Summary"><span>Excel</span></a>
    			<a class="btn btn-small btn-info" href="prntreport=Monthly+Cash+Summary"><i class="icon-print"></i><span> Print</span></a>';
				}else if ($cmbReport == "Yearly Cash Summary"){
				echo '
				<a class="btn btn-small btn-yellow" href="accreport.php?expreport=Monthly+Cash+Summary"><span>Excel</span></a>
    			<a class="btn btn-small btn-info" href="prntreport=Monthly+Cash+Summary"><i class="icon-print"></i><span> Print</span></a>';
				}else if (trim($cmbReport)=="Daily Cheque"){
				echo '
				<a class="btn btn-small btn-yellow" href="accreport.php?expreport=Monthly+Cash+Summary"><span>Excel</span></a>
    			<a class="btn btn-small btn-info" href="prntreport=Monthly+Cash+Summary"><i class="icon-print"></i><span> Print</span></a>';
				}else if (trim($cmbReport)=="Monthly Cheque"){  
				echo '
				<a class="btn btn-small btn-yellow" href="accreport.php?expreport=Monthly+Cash+Summary"><span>Excel</span></a>
    			<a class="btn btn-small btn-info" href="prntreport=Monthly+Cash+Summary"><i class="icon-print"></i><span> Print</span></a>';
				}else if (trim($cmbReport)=="Yearly Cheque"){  
				echo '
				<a class="btn btn-small btn-yellow" href="accreport.php?expreport=Monthly+Cash+Summary"><span>Excel</span></a>
    			<a class="btn btn-small btn-info" href="prntreport=Monthly+Cash+Summary"><i class="icon-print"></i><span> Print</span></a>';
				}
	?></p>
</div>
<hr></hr>
                
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
