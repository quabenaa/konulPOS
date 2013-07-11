<?php
session_start();
require_once 'conn.php';

//check to see if user has logged in with a valid password
if (!isset($_SESSION['user_id']) && !isset($_SESSION['access_lvl']))
{
header("Location:./");
}
else if($_SESSION['access_lvl'] != 2 && $_SESSION['access_lvl'] != 4){
	header("Location:error-401.php?/access=denied/");
die();
}

if(!isset($_SESSION['cust_return'])){
$_SESSION['cust_return'] = '';	
}
if(isset($_REQUEST["yourText"]) && !empty($_REQUEST["yourText"])){
 $_SESSION['cust_return'] = $_REQUEST["yourText"];
}
 
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Retail Sales - POS Management System</title>
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
 
	<style type="text/css"><!--
	
	        /* style the auto-complete response */
	        li.ui-menu-item { font-size:12px !important; z-index:10 !important;}
	
	--></style>

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
         <script type="text/javascript" language="javascript">

function autocalc(oText)
{
if (isNaN(oText.value)) //filter input
{
alert('Numbers only!');
oText.value = '';
}
var field, val, oForm = oText.form, total = a = 0;
for (a; a < arguments.length; ++a) //loop through text elements
{
field = arguments[a];
val = parseFloat(field.value); //get value
if (!isNaN(val)) //number?
{
total += val; //accumulate
}
}
oForm.total.value = total.toFixed(2); //out
}

$(document).ready(function(){
	       
		$('#cash').hide();
		$('#credit').hide();	
		
		$('#payment').change(function(){				
			if(document.getElementById('payment').value==""){
				$('#cash').hide();
				$('#credit').hide();			
			}
			else if(document.getElementById('payment').value=="cash"){
				$('#cash').show();
				$('#credit').hide();			
			}
			else if(document.getElementById('payment').value=="credit"){
				$('#cash').hide();
				$('#credit').show();			
			}
			
});//save		
});//end ready function

</script>
	</head>

	<body onLoad="document.sales.yourText.focus()" onFocus="document.sales.yourText.focus()">
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

			<div id="sidebar" class="menu-min">
				
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

			</div><!--/#sidebar-->

		
			<div id="main-content" class="clearfix">
					
					<div id="breadcrumbs">
						<ul class="breadcrumb">
							<li><i class="icon-home"></i> <a href="#">Home</a><span class="divider"><i class="icon-angle-right"></i></span></li>
							<li class="active">Point-of-Sales</li>
						</ul><!--.breadcrumb-->
					</div><!--#breadcrumbs-->



					<div id="page-content" class="clearfix">
						
						<div class="page-header position-relative">
							<h1>Return Sales<small></small>
                            <a class="btn btn-app btn-success btn-mini pull-right" href="sales.php"><i class="icon-shopping-cart"></i>Sales</a>
                            </h1>
						</div><!--/page-header-->

			<div class="row-fluid">
				<div class="span12">
                   <form action="return.php" method="post" name="sales" class="font" id="sales" on="on">
                  <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td>
                        <label class="small-labels">Enter Reciept No. :</label>
                        <span class="input-icon"><i class="icon-shopping-cart"></i>
                        <input id="yourText" name="yourText" type="text" class="input-xxlarge" style="height:30" onBlur="filtery(this.value,this.form.code)" size="15" />
                        </span><br>
                        <button type="submit" name="submit" value="GO" class="btn btn-info btn-primary"><i class="icon-search"></i> Go</button>
                        <input type="hidden" name="id2" size="31" value="<?php if(isset($_REQUEST['id'])) echo $_REQUEST['id']; ?>" />
                        </td>
                      </tr>
                </table>
               </form>
             </div>
          </div>
<form id="subsales" action="returns.php" method="post">
<div class="row-fluid">
<!-- PAGE CONTENT BEGINS HERE -->
<div class="span12">
<?php
    $queryy="SELECT sum((`Total Cost`)-`Discount`) as balsum FROM `sales` WHERE `cust_id` = '" . $_SESSION['cust_return'] . "'";
    $resultt=mysql_query($queryy);
    $roww = mysql_fetch_array($resultt);
    $balsum= $roww['balsum'];
    $balsumm= number_format($roww['balsum'],2);
?>
  <table cellpadding="5" cellspacing="8">
	<tr>
  <td colspan="2" align="left" class="well">Total Sales:<input name="t2" style="color:#1596C2; font-weight:bold" type="hidden" onKeyUp="return autocalc(this,t1)" value="<?php echo ((-1)*$balsum) ?>">
  <?php echo "<strong class='green'>" . number_format($balsum,2) . "</strong>";?> &nbsp;
    <button name="submit" type="submit"  class="btn btn-info btn-mini" value="edit" align="top">Edit Sales</button>
    <button name="submit" type="submit"  class="btn btn-danger btn-mini" value="return" align="top">Return All</button>
    <button name="submit" type="submit"  class="btn btn-success btn-mini" value="cancel" align="top">Cancel</button>
    </td>
  </tr>
  <tr>
    <td colspan="3" align="left">
      </td>
  </tr>
  
  </table>
</div>
</div>
    </form>
 
<div class="row-fluid">
<div class="span12">

<TABLE width='100%' class="table table-striped table-bordered" align='center'>
 <?php
 

    echo "<thead>
	<TR><TH colspan='9'><small>Receipt Number <font class='red'>".@$_SESSION['cust_return']."</font></small></TH><TR>
	<TR><TH><small>#</small> </TH><TH align='left'><small>Barcode</small></TH><TH align='left'><small>Product</small> </TH>
      <TH align='right'><small>Qnty Sold</small> </TH><TH align='right'><small>Price</small> </TH><TH align='right' class='hidden-phone'><small>Total Sales</small></TH><TH align='right'><small>Discount</small> </TH><TH align='right'><small>Amount To Pay</small> </TH><TH align='left' class='hidden-phone'><small>Payment Type</small></TH></TR></thead><tbody>";

   $query="SELECT `ID`,`Sales Date`,`Stock Name`,`Stock Code`,`Qnty Sold`,`Unit Cost`,`Total Cost`,`Deposit`,`Discount`,`Paid` FROM `sales` WHERE `cust_id` = '" .$_SESSION['cust_return'] . "' order by `ID` desc";
   $result=mysql_query($query);

$i=0;
    while(list($id,$date,$name,$code,$qnty,$price,$totcost,$deposit,$discount,$paid)=mysql_fetch_row($result))
    {//($qnty*$price 
     $total=number_format($totcost,2);
     $bal= number_format(($totcost-$discount),2);
     $qnty=number_format($qnty,0);
     $price=number_format($price,2);
     $deposit=number_format($deposit,2);
     $discount=number_format($discount,2);

     $i=$i+1;
     echo "<TR><TD>$i &nbsp;</TD><TD align='left'><a href = 'return.php?id=$id&code=$code'>$code</a></TD><TD align='left'> $name &nbsp;</TD><TD align='right'>$qnty &nbsp;</TD>
      <TD align='right'>$price &nbsp;</TD><TD align='right' class='hidden-phone'>$total &nbsp;</TD><TD align='right'>$discount &nbsp;</TD><TD align='right'>$bal &nbsp;</TD><TD align='left' class='hidden-phone'>$paid &nbsp;</TD></TR>";
    }

    $queryy="SELECT sum(`Qnty Sold`) as Qnty,sum(`Deposit`) as Deposit,sum(`Discount`) as Discount,sum((`Qnty Sold`*`Unit Cost`)) as Totsum,sum((`Qnty Sold`*`Unit Cost`)-`Discount`-`Deposit`) as balsum FROM sales WHERE `cust_id` = '" . $_SESSION['cust_return'] . "'";
    $resultt=mysql_query($queryy);
    $roww = mysql_fetch_array($resultt);

    $totsum=number_format($roww['Totsum'],2);
    $qqnty=number_format($roww['Qnty'],0);
    $ddeposit=number_format($roww['Deposit'],2);
    $ddiscount=number_format($roww['Discount'],2);
	$balsumm= number_format($totsum-$ddiscount,2);
    echo "<TR>
	<TD>&nbsp;</TD><TD align='left'></TD>
	<TD align='left'><strong>TOTAL SALES</strong> </TD>
	<TD align='right'><strong class='blue'>$qqnty</strong></TD>
    <TD align='right'>&nbsp;</TD>
	<TD align='right' class='hidden-phone'><strong class='blue'>$totsum</strong></TD>
	<TD align='right'><strong class='blue'>$ddiscount</strong></TD>
	<TD align='right'><strong class='blue'>$balsumm</strong></TD>
	<TD align='right' class='hidden-phone'>&nbsp;</TD>
	</TR>";  
?>
</tbody>
</table>
</div>
</div>
<div class="hr hr32 hr-dotted" ></div>
<span class="blue"><?php echo "Copyright (c) ".date("Y",time())."</span>, <span class='green'>SmartView Technology"; ?></span>
<!-- PAGE CONTENT ENDS HERE -->
		  </div><!--/row-->
	
					</div><!-- #main-content -->

		<a href="#" id="btn-scroll-up" class="btn btn-small btn-inverse">
			<i class="icon-double-angle-up icon-only"></i>
		</a>
		<!-- inline scripts related to this page -->
		
<script language="JavaScript" type="text/javascript">
<!--
function filtery(pattern, list){

  if (!list.bak){

    list.bak = new Array();
    for (n=0;n<list.length;n++){
      list.bak[list.bak.length] = new Array(list[n].value, list[n].text);
    }
  }

  match = new Array();
  nomatch = new Array();
  for (n=0;n<list.bak.length;n++){
    if(list.bak[n][1].toLowerCase().indexOf(pattern.toLowerCase())!=-1){
      match[match.length] = new Array(list.bak[n][0], list.bak[n][1]);
    }else{
      nomatch[nomatch.length] = new Array(list.bak[n][0], list.bak[n][1]);
    }
  }

  for (n=0;n<match.length;n++){
    list[n].value = match[n][0];
    list[n].text = match[n][1];
  }
  for (n=0;n<nomatch.length;n++){
    list[n+match.length].value = nomatch[n][0];
    list[n+match.length].text = nomatch[n][1];
  }

  list.selectedIndex=0;
}
// -->
</script>
	</body>
</html>
