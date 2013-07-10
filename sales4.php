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

if (!isset($_SESSION['cust_token']))
{
$_SESSION['cust_token'] = time();
}

if(isset($_REQUEST['Tit'])){
@$Tit=$_SESSION['Tit'];
}else{$Tit='';}

if(isset($_REQUEST['code'])){
@$code=$_REQUEST['code'];
$codd=$_REQUEST["code"];
 }else{$code=0;
 $codd=0;
 }

if(isset($_REQUEST['id'])){
@$id=$_REQUEST['id'];
 }else{$id = '';}

if(isset($_GET['tval'])){
@$tval=$_REQUEST['tval'];
 }else{$tval='';}
 
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
    <!-- autoComplete-->
		<link rel="stylesheet" href="assets/css/smoothness/jquery-ui-1.8.2.custom.css" /> 
	<!-- autoComplete-->
		<script type="text/javascript" src="assets/js/jquery-ui-1.8.2.custom.min.js"></script> 
	<script type="text/javascript"> 
 
		jQuery(document).ready(function(){
			$('#yourText').autocomplete({source:'suggest_product.php', minLength:2});
			$('#creditorsname').autocomplete({source:'suggest_customer.php', minLength:2});	
		});
	</script>
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
	       
		$('#cash').show();
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
							<h1>Point of Sales<small></small></h1>
						</div><!--/page-header-->

			<div class="row-fluid">
				<div class="span4">
                   <form action="tempautosale.php" method="post" name="sales" class="font" id="sales" on="on">
                  <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
                      <tr>
                        <td>
                        <label class="small-labels">Enter Barcode / Product Name :</label>
                        <span class="input-icon"><i class="icon-barcode"></i>
                        <input id="yourText" name="yourText" type="text" class="input-xlarge" style="height:30" onBlur="filtery(this.value,this.form.code)" size="15" />
                        </span><br>
                        <button type="submit" name="submit" value="GO" class="btn btn-info btn-primary"><i class="icon-search"></i> Go</button>
                        <input type="hidden" name="id2" size="31" value="<?php if(isset($_REQUEST['id'])) echo $_REQUEST['id']; ?>" />
                        </td>
                      </tr>
                </table>
               </form>
             </div>
             <?php
@$balsum=$_REQUEST["balsum"];
@$cod=$_REQUEST["code"];	
@$codd=$_REQUEST["code"];	
@$yourText=$_REQUEST["yourText"];
  
if(isset($_POST['stk_cod'])){
@$stk_cod = $_POST['stk_cod'];
}else{$stk_cod =' ';}
if(isset($yourText) && !empty($yourText))
{
$sql="SELECT * FROM stock WHERE `Stock Code` = '$yourText'";
$result = mysql_query($sql,$conn) or die('Could not look up user data; ' . mysql_error());
$row = mysql_fetch_array($result);
}

if(isset($_REQUEST['id'])){
$id = $_REQUEST['id'];
$sql2="SELECT * FROM `tempsales` WHERE ID='$id'";
$result2 = mysql_query($sql2) or die('Could not look up user data; ' . mysql_error());
$row2 = mysql_fetch_array($result2);
}
if (isset($id) && !empty($id)){
?>
<div class="span4">
<form id="subsales" action="submitsales.php" method="post">
<table cellpadding="6" cellspacing="8" width="100%" class="alert alert-info">
      <tr>
        <td>Stock Code:<br>
		  <font style='font-size: 10pt' color="#00A452"><small><b> <?php echo @$row2['Stock Code']; ?>  </b></small></font>
          <input type="hidden" name="id" size="31" value="<?php echo @$row2['ID']; ?>">
          <input type="hidden" name="cod" size="31" value="<?php echo @$row2['Stock Code']; ?>">
          </td>
        <td>Stock Name:<br>
		  <font style='font-size: 10pt' color="#00A452"><small><b><?php echo @$row2['Stock Name']; ?></b></small></font>
          <input type="hidden" name="stockname" size="31" value="<?php echo @$row2['Stock Name']; ?>"> 
          </td>
       </tr>
      <tr>
        <td>Unit Price:<br>
		  <font style='font-size: 10pt' color="#00A452"><small><b><?php echo @$row2['Unit Cost']; ?>  </b></small></font>
          <input type="hidden" name="price" width="31" value="<?php echo @$row2['Unit Cost']; ?>"></td>
        <td>Entered By:<br>
			<font class='red'  style='font-size: 10pt'><small><b><?php echo strtoupper($_SESSION['name']); ?></b></small></font>
          <input type="hidden" name="enteredby" size="31" value="<?php echo strtoupper($_SESSION['name']); ?>">
         </td>
        </tr>
      <tr>
        <td>Quantity Sold:<br>
          <input name="qntysold" type="text" class="input-medium" style="height:25" onBlur="$_REQUEST['qntysold'];" value="<?php echo @$row2['Qnty Sold']; ?>" size="15" >
          <br>
          <input type="hidden" name="qntyinit" width="31" value="<?php echo @$row2['Qnty Sold']; ?>">
          <input type="hidden" name="id3" size="31" value="<?php echo @$id; ?>">
        </td>
        <td>Discount Amount:<br>
          <input name="discount" type="text" class="input-medium" style="height:25" value="<?php echo @$row2['Discount']; ?>" width="31"></td>
           </tr>
      <tr>
        <td height="28" colspan="2" align="left">
		<?php
                echo ' <button class="btn btn-success btn-mini" type="submit" value="Update" name="submit">
                      <i class="icon-save"></i><span class="badge badge-transparent">Update</span></button>
                      <button class="btn btn-danger btn-mini" type="submit" value="Delete" name="submit">
                      <i class="icon-trash"></i><span class="badge badge-transparent">Delete</span></button>';         
        ?></td>
        </tr>
          </table>
          </form>
        </div> 
    
	<?php } ?>
             <div class="span4">
 <form id="subsales" action="submitsales.php" method="post">
<?php
    $queryy="SELECT sum((`Total Cost`)-`Discount`) as balsum FROM `tempsales` WHERE `Entered By` ='" . strtoupper($_SESSION['name']) . "' and `Sales Date`='" . date('Y-m-d') . "' and `cust_id` = '" . $_SESSION['cust_token'] . "'";
    $resultt=mysql_query($queryy);
    $roww = mysql_fetch_array($resultt);
    $balsum= $roww['balsum'];
    $balsumm= number_format($roww['balsum'],2);
?>
  <table cellpadding="5" cellspacing="8" width="100%">
  <tr>
  <td colspan="2" class="alert alert-info">
    <label>Payment Type : </label>
     <select name="payment" id="payment">
        <option value="cash" selected>Cash</option>
        <option value="credit">Credit</option>
	</select>
	</td>
  </tr>
  <!-- pass field reference ('this') and other field references -->
 
  <tr  id="cash">
  <td align="left" class="alert alert-success">
  <label>Cash Given</label>
  <span class="input-icon"><i class="icon-money"></i>
  <input name="deposit" type="text" class="input-small" onKeyUp="return autocalc(this,t2)" size="10"></span></td>
  <td align="left" class="alert alert-success">Change<br>
    <input name="total" type="text" class="input-small" value="0" size="10" readonly></td>
  </tr>
  
  <tr id="credit">
  <td colspan="2" align="left" class="alert alert-success"><label>Creditor Name:</label>
  <span class="input-icon"><i class="icon-user"></i>
      <input name="creditorsname" type="text" class="input-large" id="creditorsname" /></span>
	  </td>
  </tr>
  
	<tr>
  <td colspan="2" align="left" class="well">Total Sales:<input name="t2" style="color:#1596C2; font-weight:bold" type="hidden" onKeyUp="return autocalc(this,t1)" value="<?php echo ((-1)*$balsum) ?>">
  <?php echo "<strong class='green'>" . number_format($balsum,2) . "</strong>";?> &nbsp;
    <button name="submit" type="submit"  class="btn btn-info btn-mini" value="Complete Sales" align="top">Complete Sales </button>
    <button name="submit" type="submit"  class="btn btn-success btn-mini" value="New Sales" align="top">New Sales </button></td>
  </tr>
  <tr>
    <td colspan="3" align="left">
      </td>
  </tr>
  
  </table>
</form>
</div>
</div> 
<?php
@$balsum=$_REQUEST["balsum"];
@$cod=$_REQUEST["code"];	
@$codd=$_REQUEST["code"];	
@$yourText=$_REQUEST["yourText"];
  
if(isset($_POST['stk_cod'])){
@$stk_cod = $_POST['stk_cod'];
}else{$stk_cod =' ';}
if(isset($yourText) && !empty($yourText))
{
$sql="SELECT * FROM stock WHERE `Stock Code` = '$yourText'";
$result = mysql_query($sql,$conn) or die('Could not look up user data; ' . mysql_error());
$row = mysql_fetch_array($result);
}

if(isset($_REQUEST['id'])){
$id = $_REQUEST['id'];
$sql2="SELECT * FROM `tempsales` WHERE ID='$id'";
$result2 = mysql_query($sql2) or die('Could not look up user data; ' . mysql_error());
$row2 = mysql_fetch_array($result2);
}
if (isset($ids) && !empty($ids)){
?>
<form id="subsales" action="submitsales.php" method="post">
<div class="row-fluid">
<div class="span12">

<table width="70%" align="left" cellpadding="10" cellspacing="8" class="alert alert-info">
      <tr>
        <td>Stock Code: <font style='font-size: 10pt' color="#00A452"><small><b> <?php echo @$row2['Stock Code']; ?>  </b></small></font>
          <input type="hidden" name="id" size="31" value="<?php echo @$row2['ID']; ?>">
          <input type="hidden" name="cod" size="31" value="<?php echo @$row2['Stock Code']; ?>">
          </td>
        <td>Stock Name: <font style='font-size: 10pt' color="#00A452"><small><b><?php echo @$row2['Stock Name']; ?></b></small></font>
          <input type="hidden" name="stockname" size="31" value="<?php echo @$row2['Stock Name']; ?>"> 
          </td>
        <td>Unit Price: <font style='font-size: 10pt' color="#00A452"><small><b><?php echo @$row2['Unit Cost']; ?>  </b></small></font>
          <input type="hidden" name="price" width="31" value="<?php echo @$row2['Unit Cost']; ?>"></td>
        <td>Entered By: <font class='red'  style='font-size: 10pt'><small><b><?php echo strtoupper($_SESSION['name']); ?></b></small></font>
          <input type="hidden" name="enteredby" size="31" value="<?php echo strtoupper($_SESSION['name']); ?>">
         </td>
        </tr>
      <tr>
        <td>Quantity Sold:<br>
          <input name="qntysold" type="text" class="input-medium" style="height:25" onBlur="$_REQUEST['qntysold'];" value="<?php echo @$row2['Qnty Sold']; ?>" size="15" >
          <br>
          <input type="hidden" name="qntyinit" width="31" value="<?php echo @$row2['Qnty Sold']; ?>">
          <input type="hidden" name="id3" size="31" value="<?php echo @$id; ?>">
        </td>
        <td>Discount Amount:<br>
          <input name="discount" type="text" class="input-medium" style="height:25" value="<?php echo @$row2['Discount']; ?>" width="31"></td>
        <td height="28" colspan="2" align="left">
		<?php
                echo ' <button class="btn btn-success btn-mini" type="submit" value="Update" name="submit">
                      <i class="icon-save"></i><span class="badge badge-transparent">Update</span></button>
                      <button class="btn btn-danger btn-mini" type="submit" value="Delete" name="submit">
                      <i class="icon-trash"></i><span class="badge badge-transparent">Delete</span></button>';         
        ?></td>
        </tr>
          </table>
        </div>
      </div> 
    </form>
	<?php } ?>
<div class="row-fluid">
<div class="span12">

<TABLE width='100%' class="table table-striped table-bordered" align='center'>
 <?php
 
 if(isset($_GET['tval'])){
 $tval=$_GET['tval'];
 }else{$tval=0;}
 
 $limit      = 50;

if(isset($_GET['page'])){
 $page=$_GET['page'];
   }else{$page=0;}

if(isset($_REQUEST["cmbFilter"]) && !empty($_REQUEST["cmbFilter"])){
 $cmbFilter=$_REQUEST["cmbFilter"];
 }else{$cmbFilter='';}


   $query_count    = "SELECT * FROM `tempsales` WHERE `Entered By` ='" . strtoupper($_SESSION['name']) . "' and `Sales Date`='" . date('Y-m-d') . "' and `cust_id` = '" . $_SESSION['cust_token'] . "'";
   $result_count   = mysql_query($query_count);     
   $totalrows  = mysql_num_rows($result_count);

    echo "<thead>
	<TR><TH colspan='9'><small>Thi is Sales By ".strtoupper($_SESSION['name'])."</small></TH><TR>
	<TR><TH><small>#</small> </TH><TH align='left'><small>Barcode</small></TH><TH align='left'><small>Product</small> </TH>
      <TH align='right'><small>Qnty Sold</small> </TH><TH align='right'><small>Price</small> </TH><TH align='right' class='hidden-phone'><small>Total Sales</small></TH><TH align='right'><small>Discount</small> </TH><TH align='right'><small>Amount To Pay</small> </TH><TH align='left' class='hidden-phone'><small>Payment Type</small></TH></TR></thead><tbody>";

   $query="SELECT `ID`,`Sales Date`,`Stock Name`,`Stock Code`,`Qnty Sold`,`Unit Cost`,`Total Cost`,`Deposit`,`Discount`,`Paid` FROM `tempsales` WHERE `Entered By` ='" . strtoupper($_SESSION['name']) . "' and `Sales Date`='" . date('Y-m-d') . "' and `cust_id` = '" . $_SESSION['cust_token'] . "' order by `ID` desc";
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
     echo "<TR><TD>$i &nbsp;</TD><TD align='left'><a href = 'sales.php?id=$id&code=$code'>$code</a></TD><TD align='left'> $name &nbsp;</TD><TD align='right'>$qnty &nbsp;</TD>
      <TD align='right'>$price &nbsp;</TD><TD align='right' class='hidden-phone'>$total &nbsp;</TD><TD align='right'>$discount &nbsp;</TD><TD align='right'>$bal &nbsp;</TD><TD align='left' class='hidden-phone'>$paid &nbsp;</TD></TR>";
    }

    $queryy="SELECT sum(`Qnty Sold`) as Qnty,sum(`Deposit`) as Deposit,sum(`Discount`) as Discount,sum(`Total Cost`) as Totsum,sum((`Unit Cost`)-`Discount`) as balsum FROM `tempsales` WHERE `Entered By` ='" . strtoupper($_SESSION['name']) . "' and `Sales Date`='" . date('Y-m-d') . "' and `cust_id` = '" . $_SESSION['cust_token'] . "'";
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
