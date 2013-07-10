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

if(isset($_REQUEST['code']) && !empty($_REQUEST['code'])){
$code=$_REQUEST['code'];
$sql="SELECT `stock`.`Stock Code`,`stock`.* FROM stock WHERE `stock`.`Stock Code`='$code'";
$result = mysql_query($sql,$conn) or die('Could not look up user data; ' . mysql_error());
$row = mysql_fetch_array($result);
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Stock Record - POS Management System</title>
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
		<link rel="stylesheet" href="assets/css/colorpicker.css" />
        <!-- Plupload -->
		<link rel="stylesheet" href="assets/css/jquery.plupload.queue.css">

		<!-- ace styles -->
		<link rel="stylesheet" href="assets/css/ace.min.css" />
		<link rel="stylesheet" href="assets/css/ace-responsive.min.css" />
		<link rel="stylesheet" href="assets/css/ace-skins.min.css" />
		<!--[if lt IE 9]>
		  <link rel="stylesheet" href="assets/css/ace-ie.min.css" />
		<![endif]-->
        <script type="text/javascript" language="javascript">

function autocalc(oText)
{
 if (isNaN(oText.value)) //filter input
 {
  alert('Numbers only!');
  oText.value = '';
 }
 var field, val, oForm = oText.form, sellingprice = a = 0;

 for (a; a < arguments.length; ++a) //loop through text elements
 {
  field = arguments[a];
  val = parseFloat(field.value); //get value
  if (!isNaN(val)) //number?
  {
   sellingprice = val+(val*sellingprice/100); //accumulate
  }
 }

 oForm.sellingprice.value = sellingprice.toFixed(2); //out
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
						<li class="active"><a href="stocklist.php"><i class="icon-double-angle-right"></i> Main Stocks</a></li>
						<li><a href="restock.php"><i class="icon-double-angle-right"></i> Re-stock</a></li>
						<li><a href="requisition.php"><i class="icon-double-angle-right"></i> Requisition</a></li>
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
							<li><a href="#">Stock</a> <span class="divider"><i class="icon-angle-right"></i></span></li>
							<li class="active">Records</li>
						</ul><!--.breadcrumb-->
					</div><!--#breadcrumbs-->



					<div id="page-content" class="clearfix">
						
						<div class="page-header position-relative">
							<h1>Retail Stock <small><i class="icon-double-angle-right"></i> Records</small></h1>
						</div><!--/page-header-->
						<form action="submitstock.php" method="post">
							<div class="row-fluid">
								<!-- PAGE CONTENT BEGINS HERE -->
								<div class="span4">
											<table width="auto" border="0" cellpadding="3">
                                <tbody>
								        <tr>
								          <td class="controls controls-row">
                                          <label class="small-labels">Stock Code</label>
                                          <span class="input-icon"><i class="icon-barcode"></i>
                                          <input type="text" name="code" id="code" class="input-large" value="<?php echo @$code; ?>">
        								   </span>                                          
                                          <input type="hidden" name="id"  size="20" value="<?php echo @$row['ID']; ?>"></td>
						          </tr>
								        <tr>
								          <td class="controls controls-row">
                                        <label class="small-labels">Stock Name</label>
                                       	<span class="input-icon"> <i class="icon-resize-vertical"></i>
										<input type="text" class="input-large" name="stockname" value="<?php echo @$row['Stock Name']; ?>" />
										</span>
								            </td>
							            </tr>
								        <tr>
								          <td class="controls controls-row">
                                          <label class="small-labels">Category</label>
                                          <select name="category" size="1" class='input-large' value="<?php echo @$row['Category']; ?>">
									  <?php  
                                       echo '<option selected>' . @$row['Category'] . '</option>';
                                       $sql = "SELECT * FROM `Stock Category`";
                                       $result_status = mysql_query($sql,$conn) or die('Could not list value; ' . mysql_error());
                                       while ($rows = mysql_fetch_array($result_status)) 
                                       {
                                         echo '<option>' . $rows['Category'] . '</option>';
                                       }
                                      ?> 
                                     </select>
                                          <br>
                                          </td>
						          		</tr>
                                        <tr>
								          <td class="controls controls-row"> 
                                           <label class="small-labels">Supplier</label>
                                          <select  name="supplier" class="input-large" width="31" value="<?php echo @$row['Supplier']; ?>">  
													  <?php  
                                                       echo '<option selected>' . @$row['Supplier'] . '</option>';
                                                       $sql = "SELECT * FROM `supplier`";
                                                       $result_cl = mysql_query($sql,$conn) or die('Could not list value; ' . mysql_error());
                                                       while ($rows = mysql_fetch_array($result_cl)) 
                                                       {
                                                         echo '<option>' . $rows['Supplier'] . '</option>';
                                                       }
                                                      ?> 
                                                     </select>
											        </span>											      
                                          </td>
						          		</tr>
                                        <tr>
								          <td class="controls controls-row">
                               <label class="small-labels">Units in Stock</label>
                                <span class="input-icon"><i class="icon-inbox"></i>
                               <input type="text" class="input-large" name="stockunit" width="20" value="<?php echo @$row['Units in Stock']; ?> "/>
                                          </span><br>
                                          
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
											      <td class="controls controls-row">
                                                  <label class="small-labels">Re-Order Level</label>
                                                  <span class="input-icon"><i class="icon-inbox"></i>
                                          <input type="text" class="input-large" name="reorderlevel" width="20" value="<?php echo @$row['Reorder Level']; ?>" />
                                          </span><br>
                                          
                                                  </td>
										        </tr>
											    <tr>
											      <td class="controls controls-row"> <label class="small-labels">Unit Cost Price</label>
                                                  <span class="input-icon"> <i class="icon-money"></i>
											     <input type="text" class="input-large" name="unitcost" size="20" value="<?php echo @$row['Unit Cost']; ?>" onKeyUp="return autocalc(this,margin)">
											        </span>
											       </td>
										        </tr>											    
											    <tr>
											      <td class="controls controls-row">
                                                  <label class="small-labels">Profit Margin (%)</label>
											        <span class="input-icon"><i class="icon-money"></i>
                                          <input type="text" class="input-large" name="margin" size="20" value="<?php echo @$row['Margin']; ?>" onKeyUp="return autocalc(this,unitcost)">
                                          </span><br>
                                                    </td>
										        </tr><tr>
											      <td class="controls controls-row"><label class="small-labels">Unit Selling Price</label>
                                                  <span class="input-icon"><i class="icon-money"></i>
											        <input type="text" class="input-large" name="sellingprice" readonly tabindex="-1" size="20" value="<?php echo @$row['Selling Price']; ?>" >
											        </span>
											        </td>
										        </tr>
											    <tr>
											      <td class="controls controls-row"> 
                                                  <label class="small-labels">Expiring </label>
                                                  <span class="input-icon"> <i class="icon-calendar"></i>
											         <input id="inputField" class="input-large date-picker" type="text" name="expiry" width="20" value="<?php echo @date('d-m-Y',strtotime($row['Expiry Date'])); ?>"> 
											        </span><br />
											       </td>
										        </tr>
										      </tbody>
										    </table>
		
                                          
								</div>
                            </div><!--/row-->   
                                <div class="form-actions">
                                <?php
								if(!isset($code)){
								?>
								<button class="btn btn-success" type="submit" name="submit" value="save">
                                <i class="icon-save"></i>Save<span class="badge badge-transparent"></span></button>
								<button class="btn btn-warning" type="reset">
                                <i class="icon-undo"></i>Reset<span class="badge badge-transparent"></span></button>
								<?php } 
								 else { ?>
								<button class="btn btn-success" type="submit" name="submit" value="update">
                                <i class="icon-save"></i>Update<span class="badge badge-transparent"></span></button>
								<button class="btn btn-danger" type="submit" name="submit" value="delete">
                                <i class="icon-trash"></i>Delete<span class="badge badge-transparent"></span></button>
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
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script type="text/javascript">
		window.jQuery || document.write("<script src='assets/js/jquery-1.9.1.min.js'>\x3C/script>");
		</script>
		
		<script src="assets/js/bootstrap.min.js"></script>

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
			$('#id-disable-check').on('click', function() {
				var inp = $('#form-input-readonly').get(0);
				if(inp.hasAttribute('disabled')) {
					inp.setAttribute('readonly' , 'true');
					inp.removeAttribute('disabled');
					inp.value="This text field is readonly!";
				}
				else {
					inp.setAttribute('disabled' , 'disabled');
					inp.removeAttribute('readonly');
					inp.value="This text field is disabled!";
				}
			});
		
		
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
			
			$.mask.definitions['~']='[+-]';
			$('.input-mask-date').mask('99/99/9999');
			$('.input-mask-phone').mask('(999) 999-9999');
			$('.input-mask-eyescript').mask('~9.99 ~9.99 999');
			$(".input-mask-product").mask("a*-999-a999",{placeholder:" ",completed:function(){alert("You typed the following: "+this.val());}});
			
			
			
			$( "#input-size-slider" ).css('width','200px').slider({
				value:1,
				range: "min",
				min: 1,
				max: 6,
				step: 1,
				slide: function( event, ui ) {
					var sizing = ['', 'input-mini', 'input-small', 'input-large', 'input-large', 'input-xlarge', 'input-xxlarge'];
					var val = parseInt(ui.value);
					$('#form-field-4').attr('class', sizing[val]).val('.'+sizing[val]);
				}
			});

			$( "#input-span-slider" ).slider({
				value:1,
				range: "min",
				min: 1,
				max: 11,
				step: 1,
				slide: function( event, ui ) {
					var val = parseInt(ui.value);
					$('#form-field-5').attr('class', 'span'+val).val('.span'+val).next().attr('class', 'span'+(12-val)).val('.span'+(12-val));
				}
			});
			
			
			var $tooltip = $("<div class='tooltip right in' style='display:none;'><div class='tooltip-arrow'></div><div class='tooltip-inner'></div></div>").appendTo('body');
			$( "#slider-range" ).css('height','200px').slider({
				orientation: "vertical",
				range: true,
				min: 0,
				max: 100,
				values: [ 17, 67 ],
				slide: function( event, ui ) {
					var val = ui.values[$(ui.handle).index()-1]+"";
					
					var pos = $(ui.handle).offset();
					$tooltip.show().children().eq(1).text(val);		
					$tooltip.css({top:pos.top - 10 , left:pos.left + 18});
					
					//$(this).find('a').eq(which).attr('data-original-title' , val).tooltip('show');
				}
			});
			$('#slider-range a').tooltip({placement:'right', trigger:'manual', animation:false}).blur(function(){
				$tooltip.hide();
				//$(this).tooltip('hide');
			});
			//$('#slider-range a').tooltip({placement:'right', animation:false});
			
			$( "#slider-range-max" ).slider({
				range: "max",
				min: 1,
				max: 10,
				value: 2,
				//slide: function( event, ui ) {
				//	$( "#amount" ).val( ui.value );
				//}
			});
			//$( "#amount" ).val( $( "#slider-range-max" ).slider( "value" ) );
			
			$( "#eq > span" ).css({width:'90%', float:'left', margin:'15px'}).each(function() {
				// read initial values from markup and remove that
				var value = parseInt( $( this ).text(), 10 );
				$( this ).empty().slider({
					value: value,
					range: "min",
					animate: true
					
				});
			});

			
			$('#id-input-file-1 , #id-input-file-2').ace_file_input({
				no_file:'No File ...',
				btn_choose:'Choose',
				btn_change:'Change',
				droppable:false,
				onchange:null,
				thumbnail:false, //| true | large
				//whitelist:'gif|png|jpg|jpeg'
				//blacklist:'exe|php'
				//onchange:''
				//
			});
			
			$('#id-input-file-3').ace_file_input({
				style:'well',
				btn_choose:'Drop files here or click to choose',
				btn_change:null,
				no_icon:'icon-cloud-upload',
				droppable:true,
				onchange:null,
				thumbnail:'small',
				before_change:function(files, dropped) {
					/**
					if(files instanceof Array || (!!window.FileList && files instanceof FileList)) {
						//check each file and see if it is valid, if not return false or make a new array, add the valid files to it and return the array
						//note: if files have not been dropped, this does not change the internal value of the file input element, as it is set by the browser, and further file uploading and handling should be done via ajax, etc, otherwise all selected files will be sent to server
						//example:
						var result = []
						for(var i = 0; i < files.length; i++) {
							var file = files[i];
							if((/^image\//i).test(file.type) && file.size < 102400)
								result.push(file);
						}
						return result;
					}
					*/
					return true;
				}
				/*,
				before_remove : function() {
					return true;
				}*/

			}).on('change', function(){
				//console.log($(this).data('ace_input_files'));
				//console.log($(this).data('ace_input_method'));
			});			
			$('#spinner1').ace_spinner({value:0,min:0,max:200,step:10, btn_up_class:'btn-info' , btn_down_class:'btn-info'})
			.on('change', function(){
				//alert(this.value)
			});
			$('#spinner2').ace_spinner({value:0,min:0,max:10000,step:100, icon_up:'icon-caret-up', icon_down:'icon-caret-down'});
			$('#spinner3').ace_spinner({value:0,min:-100,max:100,step:10, icon_up:'icon-plus', icon_down:'icon-minus', btn_up_class:'btn-success' , btn_down_class:'btn-danger'});
			
			$('.date-picker').datepicker();
			$('#timepicker1').timepicker({
				minuteStep: 1,
				showSeconds: true,
				showMeridian: false
			});			
			$('#id-date-range-picker-1').daterangepicker();			
			$('#colorpicker1').colorpicker();
			$('#simple-colorpicker-1').ace_colorpicker();					
			$(".knob").knob();
			});

		</script>

	</body>
</html>
