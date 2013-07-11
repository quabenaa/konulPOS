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
 

if((isset($_REQUEST['code']) && isset($_REQUEST['id']))&& (!empty($_REQUEST['code']) && !empty($_REQUEST['code'])) ){
@$code=$_REQUEST["code"];
@$id=$_REQUEST['id'];
$sql="SELECT * FROM restock WHERE `restock`.`ID`='$id'";
$result = mysql_query($sql,$conn) or die('Could not look up user data; ' . mysql_error());
$row = mysql_fetch_array($result);

$sql2="SELECT * FROM stock WHERE `Stock Code`='$code' or `Stock Name`='$code'";
$result2 = mysql_query($sql2,$conn) or die('Could not look up user data; ' . mysql_error());
$row2 = mysql_fetch_array($result2);

}else if(isset($_REQUEST['code'])){
@$code=trim($_REQUEST["code"]);
$sql2="SELECT * FROM stock WHERE `Stock Code`='$code' or `Stock Name`='$code'";
$result2 = mysql_query($sql2,$conn) or die('Could not look up user data; ' . mysql_error());
$row2 = mysql_fetch_array($result2);
	
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Restock - KONUL [ POS Management System ]</title>
        <link rel="icon" href="assets/images/favico.ico">
		<meta name="description" content="Common form elements and layouts" />

		<meta name="viewport" content="width=device-width, initial-scale=1.0" />

		<!-- basic styles -->
		<link href="assets/css/bootstrap.min.css" rel="stylesheet" />
		<link href="assets/css/bootstrap-responsive.min.css" rel="stylesheet" />

		<link rel="stylesheet" href="assets/css/font-awesome.min.css" />
		<!--[if IE 7]>
		  <link rel="stylesheet" href="assets/css/font-awesome-ie7.min.css" />
		<![endif]-->


		<!-- page specific plugin styles -->
		
		<link rel="stylesheet" href="assets/css/jquery-ui-1.10.2.custom.min.css" />
		<link rel="stylesheet" href="assets/css/chosen.css" />
		<link rel="stylesheet" href="assets/css/datepicker.css" />
		<link rel="stylesheet" href="assets/css/bootstrap-timepicker.css" />
		<link rel="stylesheet" href="assets/css/daterangepicker.css" />
        <!-- Plupload -->
		<link rel="stylesheet" href="assets/css/jquery.plupload.queue.css">
        
         <!-- autoComplete-->
		<link rel="stylesheet" href="assets/css/smoothness/jquery-ui-1.8.2.custom.css" /> 

		<!-- ace styles -->
		<link rel="stylesheet" href="assets/css/ace.min.css" />
		<link rel="stylesheet" href="assets/css/ace-responsive.min.css" />
		<link rel="stylesheet" href="assets/css/ace-skins.min.css" />
		<!--[if lt IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->
        
        <style type="text/css"><!--
	
	        /* style the auto-complete response */
	        li.ui-menu-item { font-size:12px !important; z-index:10 !important;}	
	--></style>
<script type="text/javascript" language="javascript">

function autocalc(oText)
{
if (isNaN(oText.value)) //filter input
{
alert('Numbers only!');
oText.value = '';
}
var field, val, oForm = oText.form, balance = a = 0;
for (a; a < arguments.length; ++a) //loop through text elements
{
field = arguments[a];
val = parseFloat(field.value); //get value
if (!isNaN(val)) //number?
{
balance += val; //accumulate
}
}
oForm.balance.value = balance.toFixed(2); //out
}
</script>

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
						<li><a href="stocklist.php"><i class="icon-double-angle-right"></i> Main Stocks</a></li>
						<li class="active"><a href="restock.php"><i class="icon-double-angle-right"></i> Re-stock</a></li>
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
							<li><a href="#">Stock</a> <span class="divider"><i class="icon-angle-right"></i></span></li>
							<li class="active">Records</li>
						</ul><!--.breadcrumb-->
					</div><!--#breadcrumbs-->



					<div id="page-content" class="clearfix">
						
						<div class="page-header position-relative">
							<h1>Retail Stock <small><i class="icon-double-angle-right"></i> Records</small></h1>
						</div><!--/page-header-->
                         <?php if(!isset($code)){ ?>
                        <div class="row-fluid">
						  <div class="span12">
                                <form action="restockupdate.php" method="get">
                                  <table width="auto" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                      <td class="controls controls-row">
                                        <label class="small-labels">Enter Stock Name</label> 
                                       <span class="input-icon"><i class="icon-search"></i>
										<input type="text" name="code" id="code1" class="input-large" value="<?php echo @$code; ?>">
                                        </span><br>
                                       <button type="submit" name="submit" value="Go" class="btn btn-info btn-block">
                                       <i class="icon-search"></i> Search</button>
                                      </td>
                                    </tr>
                                    </table>
                            </form>
                            </div>
                         </div>
                         <div class="hr hr32 hr-dotted"></div>
                         <?php } ?>
						<form action="submitrestock.php" method="post">
							<div class="row-fluid">
								<!-- PAGE CONTENT BEGINS HERE -->
								<div class="span4">
											<table width="auto" border="0" cellpadding="3">
                                <tbody>
								        <tr>
								          <td class="controls controls-row"><label class="small-labels">Stock Code</label>
										<span class="input-icon"><i class="icon-barcode"></i>
                                          <input type="text" name="code" id="code" class="input-large" value="<?php echo @$row2['Stock Code']; ?>"></span>                                          </td>
						          </tr>
								        <tr>
								          <td class="controls controls-row"><label class="small-labels">Stock Name</label>
                                       <span class="input-icon"> <i class="icon-resize-vertical"></i>
									<input type="text" class="input-large" name="stockname" value="<?php echo @$row2['Stock Name']; ?>" /></span>
                                     </td>
							            </tr>
                                        <tr>
								          <td class="controls controls-row"><label class="small-labels">Restock Date</label>
                                       <span class="input-icon"> <i class="icon-calendar"></i>
						 <?php
							 if (!isset($id)){
						   ?>
							  <input type="text" size="31" class="input-large date-picker" name="restdate" value="<?php echo date('m-d-Y'); ?>">
						   <?php
							 }else{
						   ?>
							  <input type="text" size="31" class="input-large date-picker" name="restdate" value="<?php echo date('m-d-Y',strtotime($row['Stock Date'])); ?>">
						   <?php
							 }
						   ?></span></td>
							            </tr>
								        <tr>
								          <td class="controls controls-row"><label class="small-labels">Category</label>
                                          <select name="category" size="1" class='input-large' value="<?php echo @$row2['Category']; ?>">
									  <?php  
                                       echo '<option selected>' . @$row2['Category'] . '</option>';
                                       $sql = "SELECT * FROM `Stock Category`";
                                       $result_status = mysql_query($sql,$conn) or die('Could not list value; ' . mysql_error());
                                       while ($rows = mysql_fetch_array($result_status)) 
                                       {
                                         echo '<option>' . $rows['Category'] . '</option>';
                                       }
                                      ?> 
                                     </select>
                                          </td>
						          		</tr>
                                        <tr>
								          <td class="controls controls-row"><label class="small-labels">Location</label>
                                          <select name="location" class="input-large"  size="1" value="<?php echo @$row2['Location']; ?>">
											  <?php  
                                               echo '<option selected>' . @$row2['Location'] . '</option>';
                                               $sql = "SELECT * FROM `Location`";
                                               $result_gl = mysql_query($sql,$conn) or die('Could not list value; ' . mysql_error());
                                               while ($rowt = mysql_fetch_array($result_gl)) 
                                               {
                                                 echo '<option>' . @$rowt['Location'] . '</option>';
                                               }
                                              ?> 
                                             </select>
											        
                                          </td>
						          		</tr>
                                        <tr>
								          <td class="controls controls-row">
                                          <label class="small-labels">Payment Type</label>
                                          <select name="paid" class="input-large" size="1" value="<?php echo @$row2['Paid']; ?>">
                                           <?php  
											   echo '<option selected>Cash</option><option>Cheque</option><option>Credit</option>
											   <option>Deposit</option>';
											  ?> 
											 </select>
											        
                                          </td>
						          		</tr>
                                
                                      </tbody>
                                      </table>
                                      </div>
                                      <div class="span3">
											<table width="auto" border="0" cellpadding="3" cellspacing="0">
											  <tbody>
							           </tr>
											    <tr>
											      <td class="controls controls-row"><label class="small-labels">Unit in Stock</label>
                                                  <span class="input-icon"><i class=" icon-truck"></i>
                                          <input type="text" name="units" class="input-large" value="<?php echo @$row2['Units in Stock']; ?>" onKeyUp="return autocalc(this,qntyadded)">
                                          </span></td>
										        </tr>
											    <tr>
											      <td class="controls controls-row"><label class="small-labels">Quantity Added</label>
											        <span class="input-icon"><i class="icon-plus-sign"></i>
                                          <input type="text" name="qntyadded" class="input-large" value="<?php echo @$row['Qnty Added']; ?>" onKeyUp="return autocalc(this,units)">
                                          </span>
                                                    </td>
										        </tr>
											    <tr>
											      <td class="controls controls-row"><label class="small-labels">Stock Balance</label>
                                                  <span class="input-icon"> <i class="icon-stethoscope"></i>
											        <input type="text" name="balance" class="input-large red" value="<?php echo @$row2['Units in Stock']; ?>" type="text" readonly="readonly">
											        </span>
											        </td>
										        </tr>
											    <tr>
											      <td class="controls controls-row"><label class="small-labels">Recieved By</label>
                                                  <span class="input-icon"> <i class="icon-beaker"></i>
											       <input type="text" class="textbox" name="receivedby" size="31" value="<?php echo @$row['Received By']; ?>">
											        </span>
											       </td>
										        </tr>
											    <tr>
											      <td class="controls controls-row"><label class="small-labels">Supplied By</label>
                                                  <select name="suppliedby" class="select" size="1" value="<?php echo @$row['Supplier']; ?>">
													  <?php  
                                                       echo '<option selected>' . @$row['Supplier'] . '</option>';
                                                       $sql = "SELECT * FROM `supplier`";
                                                       $result_gl = mysql_query($sql,$conn) or die('Could not list value; ' . mysql_error());
                                                       while ($rowt = mysql_fetch_array($result_gl)) 
                                                       {
                                                         echo '<option>' . $rowt['Supplier'] . '</option>';
                                                       }
                                                      ?> 
                                                     </select>
											        </td>
										        </tr>
										      </tbody>
										    </table>
		
                                          
								</div>
                            </div><!--/row-->   
                                <div class="form-actions">
                                <?php
								if(!isset($id)){
								?>
								<button class="btn btn-success" type="submit" name="submit" value="Save and Reconcile">
                                <i class="icon-save"></i>Save<span class="badge badge-transparent"></span></button>
								<button class="btn btn-warning" type="reset">
                                <i class="icon-undo"></i>Reset<span class="badge badge-transparent"></span></button>
								<?php } 
								 else { ?>
								<button class="btn btn-success" type="submit" name="submit" value="Update and Reconcile">
                                <i class="icon-save"></i>Update<span class="badge badge-transparent"></span></button>
								<button class="btn btn-danger" type="submit" name="submit" value="Delete">
                                <i class="icon-trash"></i>Delete<span class="badge badge-transparent"></span></button>
                                <input type="hidden" name="id"  size="20" value="<?php echo @$row['ID']; ?>">
								<?php
								} ?>
							</div>
                      </form>

<!-- PAGE CONTENT ENDS HERE -->
			  <div class="hr hr32 hr-dotted"></div>
<span class="blue"><?php echo "Copyright (c) ".date("Y",time())."</span>, <span class='green'>SmartView Technology"; ?></span>
					</div><!--/#page-content-->
                    

			</div><!-- #main-content -->


		</div><!--/.fluid-container#main-container-->




		<a href="#" id="btn-scroll-up" class="btn btn-small btn-inverse">
			<i class="icon-double-angle-up icon-only"></i>
		</a>


		<!-- basic scripts -->
		<script src='assets/js/jquery-1.9.1.min.js'></script>
		
		<script src="assets/js/bootstrap.min.js"></script>
        
        <!-- autoComplete-->
		<script type="text/javascript" src="assets/js/jquery-ui-1.8.2.custom.min.js"></script> 
		<script type="text/javascript">  
		jQuery(document).ready(function(){
			$('#code1').autocomplete({source:'suggest_product.php', minLength:2});
		});
		</script>

		<!-- page specific plugin scripts -->
		
		<!--[if lt IE 9]>
		<script type="text/javascript" src="assets/js/excanvas.min.js"></script>
		<![endif]-->

		<script type="text/javascript" src="assets/js/jquery-ui-1.10.2.custom.min.js"></script>

		<script type="text/javascript" src="assets/js/jquery.ui.touch-punch.min.js"></script>

		<script type="text/javascript" src="assets/js/chosen.jquery.min.js"></script>

		<script type="text/javascript" src="assets/js/fuelux.spinner.js"></script>

		<script type="text/javascript" src="assets/js/bootstrap-datepicker.min.js"></script>

		<script type="text/javascript" src="assets/js/bootstrap-timepicker.min.js"></script>

		<script type="text/javascript" src="assets/js/date.js"></script>

		<script type="text/javascript" src="assets/js/daterangepicker.min.js"></script>

		<script type="text/javascript" src="assets/js/bootstrap-colorpicker.min.js"></script>

		<script type="text/javascript" src="assets/js/jquery.knob.min.js"></script>

		<script type="text/javascript" src="assets/js/jquery.autosize-min.js"></script>

		<script type="text/javascript" src="assets/js/jquery.inputlimiter.1.3.1.min.js"></script>

		<script type="text/javascript" src="assets/js/jquery.maskedinput.min.js"></script>

		<!-- ace scripts -->
		<script src="assets/js/ace-elements.min.js"></script>
		<script src="assets/js/ace.min.js"></script>


		<!-- inline scripts related to this page -->
		
		<script type="text/javascript">
		
		$(function() {

		
		
			$(".chzn-select").chosen(); 
			$(".chzn-select-deselect").chosen({allow_single_deselect:true}); 
			
			$('.ace-tooltip').tooltip();
			$('.ace-popover').popover();
			
			$('textarea[class*=autosize]').autosize({append: "\n"});
			$('textarea[class*=limited]').each(function() {
				var limit = parseInt($(this).attr('data-maxlength')) || 100;
				$(this).inputlimiter({
					"limit": limit,
					remText: '%n character%s remaining...',
					limitText: 'max allowed : %n.'
				});
			});
		
	
			$('.date-picker').datepicker();
	

		});

		</script>

	</body>
</html>
