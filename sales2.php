<?php
session_start();
require_once 'conn.php';
//check to see if user has logged in with a valid password
/*if (!isset($_SESSION['USER_ID']) & ($_SESSION['access_lvl'] != 2))
{
 if ($_SESSION['access_lvl'] != 1){
$redirect = $_SERVER['PHP_SELF'];
header("Refresh: 5; URL=login.php?redirect=$redirect");
echo "Sorry, but you don’t have permission to view this page! You are being redirected to the login page!<br>";
echo "(If your browser doesn’t support this, " .
"<a href=\"login.php?redirect=$redirect\">click here</a>)";
die();
@$cmbFilter="None";
@$filter="";
}
}*/

if (!isset($_SESSION['cust_token']))
{
# $_SESSION['cust_token'] = uniqid( );
 $_SESSION['cust_token'] = rand(10000000,99999999);
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
</script>
	</head>

	<body>
		<div class="navbar navbar-inverse">
		  <div class="navbar-inner">
		   <div class="container-fluid">
			  <a class="brand" href="#"><small><i class="icon-shopping-cart"></i> Test Supermarket</small> </a>
			  <ul class="nav ace-nav pull-right">
					<li class="grey">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="icon-tasks"></i>
							<span class="badge">4</span>
						</a>
						<ul class="pull-right dropdown-navbar dropdown-menu dropdown-caret dropdown-closer">
							<li class="nav-header">
								<i class="icon-ok"></i> 4 Tasks to complete
							</li>
							
							<li>
								<a href="#">
									<div class="clearfix">
										<span class="pull-left">Software Update</span>
										<span class="pull-right">65%</span>
									</div>
									<div class="progress progress-mini"><div class="bar" style="width:65%"></div></div>
								</a>
							</li>
							
							<li>
								<a href="#">
									<div class="clearfix">
										<span class="pull-left">Hardware Upgrade</span>
										<span class="pull-right">35%</span>
									</div>
									<div class="progress progress-mini progress-danger"><div class="bar" style="width:35%"></div></div>
								</a>
							</li>
							
							<li>
								<a href="#">
									<div class="clearfix">
										<span class="pull-left">Unit Testing</span>
										<span class="pull-right">15%</span>
									</div>
									<div class="progress progress-mini progress-warning"><div class="bar" style="width:15%"></div></div>
								</a>
							</li>
							
							<li>
								<a href="#">
									<div class="clearfix">
										<span class="pull-left">Bug Fixes</span>
										<span class="pull-right">90%</span>
									</div>
									<div class="progress progress-mini progress-success progress-striped active"><div class="bar" style="width:90%"></div></div>
								</a>
							</li>
							
							<li>
								<a href="#">
									See tasks with details
									<i class="icon-arrow-right"></i>
								</a>
							</li>
						</ul>
					</li>


					<li class="purple">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="icon-bell-alt icon-animated-bell icon-only"></i>
							<span class="badge badge-important">8</span>
						</a>
						<ul class="pull-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-closer">
							<li class="nav-header">
								<i class="icon-warning-sign"></i> 8 Notifications
							</li>
							
							<li>
								<a href="#">
									<div class="clearfix">
										<span class="pull-left"><i class="icon-comment btn btn-mini btn-pink"></i> New comments</span>
										<span class="pull-right badge badge-info">+12</span>
									</div>
								</a>
							</li>
							
							<li>
								<a href="#">
									<i class="icon-user btn btn-mini btn-primary"></i> Bob just signed up as an editor ...
								</a>
							</li>
							
							<li>
								<a href="#">
									<div class="clearfix">
										<span class="pull-left"><i class="icon-shopping-cart btn btn-mini btn-success"></i> New orders</span>
										<span class="pull-right badge badge-success">+8</span>
									</div>
								</a>
							</li>
							
							<li>
								<a href="#">
									<div class="clearfix">
										<span class="pull-left"><i class="icon-twitter btn btn-mini btn-info"></i> Followers</span>
										<span class="pull-right badge badge-info">+4</span>
									</div>
								</a>
							</li>
																
							<li>
								<a href="#">
									See all notifications
									<i class="icon-arrow-right"></i>
								</a>
							</li>
						</ul>
					</li>


					<li class="green">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">
							<i class="icon-envelope-alt icon-animated-vertical icon-only"></i>
							<span class="badge badge-success">5</span>
						</a>
						<ul class="pull-right dropdown-navbar dropdown-menu dropdown-caret dropdown-closer">
							<li class="nav-header">
								<i class="icon-envelope"></i> 5 Messages
							</li>
							
							<li>
								<a href="#">
									<img alt="Alex's Avatar" class="msg-photo" src="assets/avatars/avatar.png" />
									<span class="msg-body">
										<span class="msg-title">
											<span class="blue">Alex:</span>
											Ciao sociis natoque penatibus et auctor ...
										</span>
										<span class="msg-time">
											<i class="icon-time"></i> <span>a moment ago</span>
										</span>
									</span>
								</a>
							</li>
							
							<li>
								<a href="#">
									<img alt="Susan's Avatar" class="msg-photo" src="assets/avatars/avatar3.png" />
									<span class="msg-body">
										<span class="msg-title">
											<span class="blue">Susan:</span>
											Vestibulum id ligula porta felis euismod ...
										</span>
										<span class="msg-time">
											<i class="icon-time"></i> <span>20 minutes ago</span>
										</span>
									</span>
								</a>
							</li>
							
							<li>
								<a href="#">
									<img alt="Bob's Avatar" class="msg-photo" src="assets/avatars/avatar4.png" />
									<span class="msg-body">
										<span class="msg-title">
											<span class="blue">Bob:</span>
											Nullam quis risus eget urna mollis ornare ...
										</span>
										<span class="msg-time">
											<i class="icon-time"></i> <span>3:15 pm</span>
										</span>
									</span>
								</a>
							</li>
							
							<li>
								<a href="#">
									See all messages
									<i class="icon-arrow-right"></i>
								</a>
							</li>									
	
						</ul>
					</li>


					<li class="light-blue user-profile">
						<a class="user-menu dropdown-toggle" href="#" data-toggle="dropdown">
							<img alt="Jason's Photo" src="assets/avatars/user.jpg" class="nav-user-photo" />
							<span id="user_info">
								<small>Welcome,</small> Jason
							</span>
							<i class="icon-caret-down"></i>
						</a>
						<ul id="user_menu" class="pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-closer">
							<li><a href="#"><i class="icon-cog"></i> Settings</a></li>
							<li><a href="#"><i class="icon-user"></i> Profile</a></li>
							<li class="divider"></li>
							<li><a href="#"><i class="icon-off"></i> Logout</a></li>
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
						<button class="btn btn-small btn-success"><i class="icon-signal"></i></button>
						<button class="btn btn-small btn-info"><i class="icon-pencil"></i></button>
						<button class="btn btn-small btn-warning"><i class="icon-group"></i></button>
						<button class="btn btn-small btn-danger"><i class="icon-cogs"></i></button>
					</div>
					<div id="sidebar-shortcuts-mini">
						<span class="btn btn-success"></span>
						<span class="btn btn-info"></span>
						<span class="btn btn-warning"></span>
						<span class="btn btn-danger"></span>
					</div>
				</div><!-- #sidebar-shortcuts -->

				<ul class="nav nav-list">
					
					<li>
					  <a href="dashboard.php">
						<i class="icon-dashboard"></i>
						<span>Dashboard</span>
						
					  </a>
					</li>
					
                   <li>
					  <a href="#" class="dropdown-toggle">
						<i class="icon-truck"></i>
						<span>Stocks</span>
						<b class="arrow icon-angle-down"></b>
					  </a>
					<ul class="submenu">
						<li><a href="stocklist.php"><i class="icon-double-angle-right"></i> Main Stocks</a></li>
						<li><a href="restock.php"><i class="icon-double-angle-right"></i> Re-stock</a></li>
						<li><a href="requisition.php"><i class="icon-double-angle-right"></i> Requisition</a></li>
						<li><a href="buttons.html"><i class="icon-double-angle-right"></i> Wastage</a></li>
					  </ul>
					</li>
					
										
				  <li class="active">
					  <a href="#">
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
						<li><a href="elements.html"><i class="icon-double-angle-right"></i> Daily Cash Book</a></li>
						<li><a href="buttons.html"><i class="icon-double-angle-right"></i> Sales</a></li>
						<li><a href="elements.html"><i class="icon-double-angle-right"></i> Cheque Register</a></li>
						<li><a href="buttons.html"><i class="icon-double-angle-right"></i> Fixed Assets</a></li>
						<li><a href="elements.html"><i class="icon-double-angle-right"></i> Creditors Schedule</a></li>
						<li><a href="buttons.html"><i class="icon-double-angle-right"></i> Debtors Schedule</a></li>
						<li><a href="elements.html"><i class="icon-double-angle-right"></i> Reports</a></li>
					  </ul>
					</li>
					
					<li>
					  <a href="tables.html">
						<i class="icon-paste"></i>
						<span>Reports</span>
						
					  </a>
					</li>

					<li>
					  <a href="widgets.html">
						<i class="icon-eye-open"></i>
						<span>System Logs</span>						
					  </a>
					</li>

					<li>
					  <a href="calendar.html">
						<i class="icon-wrench"></i>
						<span>Sysyem Settings</span>
						
					  </a>
					</li>
					
				</ul><!--/.nav-list-->

				<div id="sidebar-collapse"><i class="icon-double-angle-left"></i></div>


			</div><!--/#sidebar-->

		
			<div id="main-content" class="clearfix">
					
					<div id="breadcrumbs">
						<ul class="breadcrumb">
							<li><i class="icon-home"></i> <a href="#">Home</a><span class="divider"><i class="icon-angle-right"></i></span></li>
							<li class="active">Point-of-Sales</li>
						</ul><!--.breadcrumb-->

						<div id="nav-search">
							<form class="form-search">
									<span class="input-icon">
										<input autocomplete="off" id="nav-search-input" type="text" class="input-small search-query" placeholder="Search ..." />
										<i id="nav-search-icon" class="icon-search"></i>
									</span>
							</form>
						</div><!--#nav-search-->
					</div><!--#breadcrumbs-->



					<div id="page-content" class="clearfix">
						
						<div class="page-header position-relative">
							<h1>Point of Sales<small></small></h1>
						</div><!--/page-header-->

						

<div class="row-fluid">
<!-- PAGE CONTENT BEGINS HERE -->

<div class="row-fluid">
    <div class="span9">
        <div class="widget-box light-border">
            <div class="widget-header header-color-green">
                <h5 class="smaller"><i class="icon-shopping-cart"></i> Point of Sales</h5>
            </div>
            <div class="widget-body">
               		<div class="widget-main">
<table border="0" cellspacing="5" class="login">
    <tr>
      <td align="center">
          <form action="tempautosale.php" method="post" name="sales" class="font" id="sales" on="on">
          <table width="100%" align="center" border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td>
                <label class="small-labels">Enter Stock Code / Name :</label>
                <span class="input-icon"><i class="icon-barcode"></i>
                <input name="yourText" type="text" class="input-xxlarge" style="height:30" onBlur="filtery(this.value,this.form.code)" size="15" />
                </span><br>
                <button type="submit" name="submit" value="GO" class="btn btn-info btn-primary"><i class="icon-search"></i> Search</button>
                <input type="hidden" name="id2" size="31" value="<?php if(isset($_REQUEST['id'])) echo $_REQUEST['id']; ?>" />
                </td>
              </tr>
		</table>
          </form>
      
      </td>
    </tr>
   <form id="subsales" action="submitsales.php" method="post">
    <tr>
      <td>
		  <?php
            $queryy="SELECT sum((`Total Cost`)-`Discount`) as balsum FROM `tempsales` WHERE `Entered By` ='" . strtoupper($_SESSION['name']) . "' and `Sales Date`='" . date('Y-m-d') . "' and `cust_id` = '" . $_SESSION['cust_token'] . "'";
            $resultt=mysql_query($queryy);
            $roww = mysql_fetch_array($resultt);
            $balsum= $roww['balsum'];
            $balsumm= number_format($roww['balsum'],2);
        ?>    

          <table width="100%" cellpadding="5" cellspacing="0" class="alert">
          <tr>
          <td align="center" colspan="3">
          <strong>CUSTOMER CASH</strong>
          </td>
          </tr>
          <tr>
            <td  align="left">Payment Type<br>
              <select name="paid" class="input-medium" value="<?php echo @$row2['Paid']; ?>">
                <?php  
                   echo '<option selected>Cash</option>';
                   echo '<option>Cheque</option>';
                   echo '<option>Credit</option>';
                   echo '<option>Deposit</option>';
                  ?>
              </select></td>
          <!-- pass field reference ('this') and other field references -->
          <td align="left">
            Cash Given<br>
            <input name="deposit" type="text" class="input-medium" onKeyUp="return autocalc(this,t2)" size="10"></td>
          <td width="60" colspan="3">Change<br>
          <input name="total" type="text" class="input-medium" value="0" size="10" readonly></td>
          </tr>
          <tr>
          <td align="left">Total Sales: 
            <input name="t2" type="hidden" onKeyUp="return autocalc(this,t1)" value="<?php echo ((-1)*$balsum) ?>" tabindex="2">
            <?php echo "<strong class='green'>" . number_format($balsum,2) . "</strong>";?>
          </td>
          <td colspan="4" align="left">
          <button name="submit" type="submit"  class="btn btn-success btn-mini" value="Complete Sales" align="top">Complete Sales </button>
           <button name="submit" type="submit"  class="btn btn-success btn-mini" value="New Sales" align="top">New Sales </button>
            <?php
$sqlP="SELECT * FROM `suspend` WHERE `Entered By` ='" . strtoupper($_SESSION['name']) . "' and `Sales Date`='" . date('Y-m-d') . "'";
$resultP = mysql_query($sqlP,$conn) or die('Could not look up user data; ' . mysql_error());
$rowP = mysql_fetch_array($resultP);
$trowP = mysql_num_rows($resultP);
if(!$trowP)
{
?>
            <button name="submit" type="submit"  class="btn btn-success btn-mini" value="Suspend" align="top"> Suspend</button>
            <?php
} else {
?>
            <button name="submit" type="submit"  class="btn btn-success btn-mini" value="Resume" align="top"> Resume</button>
            <?php
}
?></td>
          </tr>
          <tr>
              <td></td>
  		  </tr>
          </table>
  </td>
    </tr>
    <tr>
      <td>
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
?>     
  
    <table width="100%" cellpadding="5" class="alert alert-info">
      <tr>
        <td width="32%" height="28">Sales Date:<br>
          <input name="sdate" type="text" class="input-medium" style="height:25" value="<?php echo date('d-m-Y'); ?>" readonly width="31">
          </td>
        <td width="35%" height="28">
          Discount Amount:<br>
          <input name="discount" type="text" class="input-medium" style="height:25" value="<?php echo @$row2['Discount']; ?>" width="31">
          </td>
        </tr>
      <tr>
        <td width="32%" height="28">Stock Code: <font style='font-size: 10pt' color="#00A452"><b> <?php echo @$row2['Stock Code']; ?>  </b></font>
          <input type="hidden" name="id" size="31" value="<?php echo @$row2['ID']; ?>">
          <input type="hidden" name="cod" size="31" value="<?php echo @$row2['Stock Code']; ?>">
          </td>
        <td width="35%" height="28">Stock Name: <font style='font-size: 9pt' color="#00A452"><b><?php echo @$row2['Stock Name']; ?></b></font>
          <input type="hidden" name="stockname" size="31" value="<?php echo @$row2['Stock Name']; ?>"> 
          </td>
        </tr>
      <tr>
        <td width="32%" height="28">Unit Price: <font style='font-size: 10pt' color="#00A452"><b><?php echo @$row2['Unit Cost']; ?>  </b></font>
          <input type="hidden" name="price" width="31" value="<?php echo @$row2['Unit Cost']; ?>">
          </td>
        <td width="35%" height="28">
          Entered By: <font color="#0399D1"><?php echo strtoupper($_SESSION['name']); ?></font>
          <input type="hidden" name="enteredby" size="31" value="<?php echo strtoupper($_SESSION['name']); ?>">
          </td>
        </tr>
      <tr>
        <td height="28" colspan="2">Quantity Sold:<br>
          <input name="qntysold" type="text" class="input-medium" style="height:25" onBlur="$_REQUEST['qntysold'];" value="<?php echo @$row2['Qnty Sold']; ?>" size="15" ><br>
          <input type="hidden" name="qntyinit" width="31" value="<?php echo @$row2['Qnty Sold']; ?>"> <input type="hidden" name="id" size="31" value="<?php echo @$id; ?>">
          <?php
        if (isset($id) && !empty($id)){
                echo ' <button class="btn btn-success btn-mini" type="submit" value="Update" name="submit">
                      <i class="icon-save"></i><span class="badge badge-transparent">Update</span></button>
                      <button class="btn btn-danger btn-mini" type="submit" value="Delete" name="submit">
                      <i class="icon-trash"></i><span class="badge badge-transparent">Delete</span></button>';
        } 
        ?>
          </td>
        </tr>
      </table>

       </td>
   </tr>
  </form>
<tr>
<td height="35" align="right">
<?php   echo "<tr><td colspan=10 align='center'><b><font color='#FF0000' style='font-size: 10pt'>This is Sales by " . strtoupper($_SESSION['name']) . " </font></b></td></tr>"; ?>
<TABLE width='100%' class="table table-striped table-bordered table-hover" align='center'>
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

    echo "<thead><TR><TH><small>S/No</small> </TH><TH align='left'><small>StockCode</small></TH><TH align='left'><small>StockName</small> </TH>
      <TH align='right'><small>Qnty Sold</small> </TH><TH align='right'><small>Price</small> </TH><TH align='right'><small>Total Sales</small></TH><TH align='right'><small>Discount</small> </TH><TH align='right'><small>Amount To Pay</small> </TH><TH align='left'><small>Payment Type</small></TH></TR></thead><tbody>";

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
      <TD align='right'>$price &nbsp;</TD><TD align='right'>$total &nbsp;</TD><TD align='right'>$discount &nbsp;</TD><TD align='right'>$bal &nbsp;</TD><TD align='left'>$paid &nbsp;</TD></TR>";
    }

    $queryy="SELECT sum(`Qnty Sold`) as Qnty,sum(`Deposit`) as Deposit,sum(`Discount`) as Discount,sum(`Total Cost`) as Totsum,sum((`Unit Cost`)-`Discount`) as balsum FROM `tempsales` WHERE `Entered By` ='" . strtoupper($_SESSION['name']) . "' and `Sales Date`='" . date('Y-m-d') . "' and `cust_id` = '" . $_SESSION['cust_token'] . "'";
    $resultt=mysql_query($queryy);
    $roww = mysql_fetch_array($resultt);

    $totsum=number_format($roww['Totsum'],2);
    $balsumm= number_format($roww['balsum'],2);
    $qqnty=number_format($roww['Qnty'],0);
    $ddeposit=number_format($roww['Deposit'],2);
    $ddiscount=number_format($roww['Discount'],2);

    echo "<TR>
	<TD>&nbsp;</TD><TD align='left'></TD>
	<TD align='left'><strong>TOTAL SALES</strong> </TD>
	<TD align='right'><strong class='blue'>$qqnty</strong></TD>
    <TD align='right'>&nbsp;</TD>
	<TD align='right'><strong class='blue'>$totsum</strong></TD>
	<TD align='right'><strong class='blue'>$ddiscount</strong></TD>
	<TD align='right'><strong class='blue'>$totsum</strong></TD>
	<TD align='right'>&nbsp;</TD>
	</TR>";  
?>
</tbody>
</table>

</td></tr>
</table>

                    </div>
        	</div>
        </div>
      </div>
</div>

<div class="hr hr32 hr-dotted" ></div>
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
