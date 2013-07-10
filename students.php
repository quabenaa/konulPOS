<?php
	//include("check_login.php"); 
	require_once("db.php"); 
 	require_once("functions.php"); 
//	check_user(ADMIN);
//fetch drop down data from table into result sets
$country_result=select_all_sorted('countries','name');
$region_result=select_all('region');
$title_result=select_all('title');
$gender_result=select_all('gender');
$marital_status_result=select_all('marital_status');
$guardian_title_result=select_all('title');
$religion_result=select_all('religion');
$sponsorship_result=select_all('sponsor');
$certificate_result=select_all('certificate');
$program_result=select_all('program');
$student_mode_result=select_all('student_mode');
$relationship_result=select_all('relationship');
$level_result=select_all('level');
//code execute if 	GET is set
if(isset($_GET['sn'])){
	//set student number from GET
    $student_number=$_GET['sn'];
	//array of fields and values use to search student record
    $data = array("student_number" => $student_number);
	//select_by_fields is a function that select record base on the table name and array of table fields
    $result=select_by_fields("student", $data);    
    $count=mysql_num_rows($result);
    $row=mysql_fetch_assoc($result);   
	//initialize student varables
    $title = $row['title_id'];
    $surname = $row['surname']; 
    $other_name = $row['other_name'];
    $gender = $row['gender_id'];
    $date_of_birth = $row['date_of_birth'];
    $nationality = $row['country_code'];
    $region = $row['region_id'];
    $hometown = $row['hometown'];
    $place_of_birth = $row['place_of_birth']; 
    $residential_address = $row['residential_address'];    
    $picture = $row['picture'];    
    $email =$row['email'];
	$last_school = $row['last_school'];
    $mobile_number = $row['mobile_number'];
    $disability = $row['disability'];
    $marital_status = $row['marital_status_id'];
    $religion = $row['religion_id'];
    $sponsor = $row['sponsor_id'];
    $certificate_use = $row['certificate_id'];
    $program = $row['program_id'] ;	
    $student_mode = $row['student_mode_id'];
    $date_registered = $row['date_registered'];
    $postal_address = $row['postal_address'];
	$level=$row['level_id'];
	$date_completed=$row['date_completed'];  
	//array of fields and values use to search guardian record
    $data = array("id" => $student_number);
	//select_by_fields is a function that select record base on the table name and array of table fields
    $result=select_by_fields("guardian", $data);     
    $row=mysql_fetch_assoc($result);
   	//initialize guardian varables
    $guardian_title=$row['title_id'];  
    $guardian_surname=$row['surname'];    
    $guardian_other_name=$row['other_name'];
    $relationship=$row['relationship_id'];
    $occupation=$row['occupation'];    
    $guardian_email=$row['email'];
    $guardian_mobile_number=$row['mobile_number']; 
    $guardian_residential_address = $row['residential_address'];
}
//code execute if a save or update button is clicked
else if(isset($_POST['submit'])=='Save' || isset($_POST['submit'])=='Update'){ 		
	//get the values post into variables
    $student_number = mysql_real_escape_string($_POST['student_number']);
	//picture details
    if(isset($_FILES['picture'])){
		//die("You have not uploaded your picture");
		$name=$_FILES['picture']['name'];
		$type=$_FILES['picture']['type'];
		$size=$_FILES['picture']['size'];
		$temp_name=$_FILES['picture']['tmp_name'];
		$error=$_FILES['picture']['error'];    
				
			if($error>0){
			   // die("Error uploading file! Code $error."); 
			}
			else{
			$type = substr($name, strpos($name,'.'), strlen($name)-1);
				if(!($type==".jpeg" || $type==".jpg" || $type==".png" || $type==".gif" || $type==".bmp" )){
				die("The file type is not supported");
				}
				elseif($size>2000000){
				die("File size is to big to upload");
				}
				else{  
				$fp=fopen($temp_name,'r');
				$content=fread($fp,filesize($temp_name));
				$content=addslashes($content);
				fclose($fp);
				//this code will move the selected image to the images folder
				move_uploaded_file($temp_name,"images/passport/students/".$name);
				//rename image
				rename("images/passport/students/".$name, "images/passport/students/$student_number".$type);   
				//set image name for database
				$picture=$student_number."".$type;
				}        
			}
    }

    $surname = ucwords(mysql_real_escape_string($_POST['surname'])); 
    $other_name = ucwords(mysql_real_escape_string($_POST['othername']));
    $gender = $_POST['gender'];
    $date_of_birth = $_POST['date_of_birth'];
    $nationality = $_POST['nationality'];
    $region = $_POST['region'];
    $hometown = ucwords(mysql_real_escape_string($_POST['hometown']));
	$last_school = ( mysql_real_escape_string( $_POST['last_sch']));
    $disability = ucwords(mysql_real_escape_string($_POST['disability']));
    $religion = $_POST['religion'];
    $sponsor = $_POST['sponsor'];
    $program = $_POST['program'] ;	
    $level=$_POST['level'];
	
    $guardian_title=$_POST['guardian_title'];  
    $guardian_surname=ucwords(mysql_real_escape_string($_POST['guardian_surname']));    
    $guardian_other_name=ucwords(mysql_real_escape_string($_POST['guardian_other_name']));
    $relationship=$_POST['relationship'];
    $occupation=ucwords(mysql_real_escape_string($_POST['occupation']));    
    $guardian_email=mysql_real_escape_string($_POST['guardian_email']);
    $guardian_mobile_number=mysql_real_escape_string($_POST['guardian_mobile_number']); 
    $guardian_residential_address = ucwords(mysql_real_escape_string($_POST['guardian_residential_address']));	

 	//check invalid level
 	$duration =get_column('program','duration','id',$program);	
	if($level>$duration && $level<>20){
		die("Invalid level selected");
	}


//array of fields and values use to save or update student information
    $student_data = array(
	"student_number" => $student_number ,
	"title_id" => 1 ,
	"surname" => $surname ,
	"other_name" => $other_name ,
	"gender_id" => $gender ,
	"date_of_birth" => $date_of_birth ,
	"country_code" => $nationality ,
	"region_id" => $region ,
	"hometown" => $hometown ,	
	//"email" => $email ,
	"disability" => $disability ,
	"marital_status_id" => 1 ,
	"religion_id" => $religion ,
	"sponsor_id" => $sponsor ,
	"certificate_id" => 1 ,
	"program_id" => $program ,
	"student_mode_id" => 1 ,
	"level_id" => $level,
	"last_school" => $last_school	 
    );
    //append picture column if an image is selected
     if(isset($_FILES['picture']) && $_FILES['picture']['error']<=0){
	$student_data = array_merge($student_data , array("picture" => $picture));	
    }
	  
   //array of fields and values use to save or update guardian information  
   $guardian_data = array(
		"id" => $student_number ,
		"title_id" => $guardian_title , 
		"surname" => $guardian_surname ,
		"other_name" => $guardian_other_name ,
		"relationship_id" => $relationship ,
		"occupation" =>  $occupation ,
		"email" => $guardian_email ,
		"mobile_number" => $guardian_mobile_number ,
		"residential_address" => $guardian_residential_address
	);
    
    //print_r($student_data);
    if(isset($_POST['submit']) && $_POST['submit']=='Save'){
		//append date registered column if saving for the first time
		$date_registered=date('Y-m-d H:i:s');
		$student_data = array_merge($student_data , array("date_registered" => $date_registered,"deleted"=>0));
			
	    $guardian_query = mysql_insert_array("guardian", $guardian_data, $password_field = "");
        $student_query = mysql_insert_array("student", $student_data, $password_field = ""); 
    	mysql_query($guardian_query) or die(mysql_error());
		mysql_query($student_query)or die(mysql_error());
		    
    }    
    if(isset($_POST['submit']) && $_POST['submit']=='Update'){
	$guardian_query = mysql_update_array("guardian", $guardian_data, "id", $student_number);
    $student_query = mysql_update_array("student", $student_data, "student_number",   					$student_number);
	
	if(mysql_query($guardian_query) && mysql_query($student_query)){
	    //echo("data updated");
	    $message="Data Updated Successfully";
	    $message_flag="success";
		$name=(get_column('student','surname','student_number',$student_number))." ".$othername=(get_column('student','other_name',			                'student_number',$student_number));
				monitor("Admission Record", "Updated Admission Record for Student No: ".$student_number." Name: ".$name);
	}else{
	    //echo("could not save data ".mysql_error());
	    $message="Could not update data. ".mysql_error();
	    $message_flag="fail";
	}	
  } 
}
 //code for delete button
if(isset($_POST['submit'])=='Delete'){
   $student_number=$_POST['student_number'];	
   $student_query = mysql_update_array("student", array("deleted"=>1), "student_number", $student_number);  
	if(mysql_query($student_query)){
	   $message="Data Deleted Successfully";
	    $message_flag="success";
		$name=(get_column('student','surname','student_number',$student_number))." ".$othername=(get_column('student','other_name',			                'student_number',$student_number));				
		monitor("Admission Record", "Deleted Admission Record for Student No: ".$student_number." Name: ".$name);	
		$student_number="";
	}
	else{
	     //echo("could not delete data ".mysql_error());
	    $message="Could not delete data. ".mysql_error();
	    $message_flag="fail";		
	} 
} 

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8" />
		<title>Students Info - School Management System</title>
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

	</head>

	<body>
		<div class="navbar navbar-inverse">
		  <div class="navbar-inner">
		   <div class="container-fluid">
			  <a class="brand" href="#"><small><i class="icon-leaf"></i> Test International School</small> </a>
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
					  <a href="typography.html">
						<i class="icon-text-width"></i>
						<span>Typography</span>
						
					  </a>
					</li>

					
					<li>
					  <a href="#" class="dropdown-toggle" >
						<i class="icon-desktop"></i>
						<span>UI Elements</span>
						<b class="arrow icon-angle-down"></b>
					  </a>
					  <ul class="submenu">
						<li><a href="elements.html"><i class="icon-double-angle-right"></i> Elements</a></li>
						<li><a href="buttons.html"><i class="icon-double-angle-right"></i> Buttons & Icons</a></li>
					  </ul>
					</li>

					
					<li>
					  <a href="tables.html">
						<i class="icon-list"></i>
						<span>Tables</span>
						
					  </a>
					</li>

					
					<li class="active open">
					  <a href="#" class="dropdown-toggle" >
						<i class="icon-edit"></i>
						<span>Forms</span>
						<b class="arrow icon-angle-down"></b>
					  </a>
					  <ul class="submenu">
						<li class="active"><a href="form-elements.html"><i class="icon-double-angle-right"></i> Form Elements</a></li>
						<li><a href="form-wizard.html"><i class="icon-double-angle-right"></i> Wizard & Validation</a></li>
					  </ul>
					</li>

					
					<li>
					  <a href="widgets.html">
						<i class="icon-list-alt"></i>
						<span>Widgets</span>
						
					  </a>
					</li>

					
					<li>
					  <a href="calendar.html">
						<i class="icon-calendar"></i>
						<span>Calendar</span>
						
					  </a>
					</li>

					
					<li>
					  <a href="gallery.html">
						<i class="icon-picture"></i>
						<span>Gallery</span>
						
					  </a>
					</li>

					
					<li>
					  <a href="grid.html">
						<i class="icon-th"></i>
						<span>Grid</span>
						
					  </a>
					</li>

					
					<li>
					  <a href="#" class="dropdown-toggle" >
						<i class="icon-file"></i>
						<span>Other Pages</span>
						<b class="arrow icon-angle-down"></b>
					  </a>
					  <ul class="submenu">
						<li><a href="pricing.html"><i class="icon-double-angle-right"></i> Pricing Tables</a></li>
						<li><a href="invoice.html"><i class="icon-double-angle-right"></i> Invoice</a></li>
						<li><a href="index.php"><i class="icon-double-angle-right"></i> Login & Register</a></li>
						<li><a href="error-401.php"><i class="icon-double-angle-right"></i> Error 404</a></li>
						<li><a href="error-500.html"><i class="icon-double-angle-right"></i> Error 500</a></li>
						<li><a href="blank.html"><i class="icon-double-angle-right"></i> Blank Page</a></li>
					  </ul>
					</li>

					
				</ul><!--/.nav-list-->

				<div id="sidebar-collapse"><i class="icon-double-angle-left"></i></div>


			</div><!--/#sidebar-->

		
			<div id="main-content" class="clearfix">
					
					<div id="breadcrumbs">
						<ul class="breadcrumb">
							<li><i class="icon-home"></i> <a href="#">Home</a><span class="divider"><i class="icon-angle-right"></i></span></li>
							<li><a href="#">Students</a> <span class="divider"><i class="icon-angle-right"></i></span></li>
							<li class="active">Registration</li>
						</ul><!--.breadcrumb-->

						<div id="nav-search">
									<span class="green active">
									<?php echo date('F d, Y',time()); ?>
									<?php echo date('l, G:i',time())." GMT"; ?>
									</span>
						</div><!--#nav-search-->
					</div><!--#breadcrumbs-->



					<div id="page-content" class="clearfix">
						
						<div class="page-header position-relative">
							<h1>Students <small><i class="icon-double-angle-right"></i> Registration</small></h1>
						</div><!--/page-header-->

						

						<div class="row-fluid">
<!-- PAGE CONTENT BEGINS HERE -->

	<form action="students.php" method="POST" class='form-horizontal' id="bb" enctype="multipart/form-data">
	
		<div class="control-group">
                                <table width="auto" border="0" cellpadding="3" class="control-group">
                                <tbody>
								        <tr>
								          <td class="controls controls-row">
                                          <input multiple type="file" id="id-input-file-2" name="picture" />
                                          </td>
								          <td colspan="2" valign="bottom" class="controls controls-row">
                                          
							              </td>
								       
							            </tr>
								        <tr>
								          <td class="controls controls-row"><span class="input-icon"><i class="icon-key"></i>
                                          <input value="<?php echo @$student_number; ?>" type="text" name="student_number" id="student_number" class="input-large" <?php if(isset($student_number)){echo'readonly="readonly"';}?> data-rule-required="true" /></span>
								            <br><label class="small-labels">Student No.</label></td>
							            </tr>
                                        </tbody></table>
                                        <h4 class="header smaller lighter green">Personal Information</h4>
                                        <table>
                                	<tbody>
								        <tr>
								          <td class="controls controls-row">
                                          <span class="input-icon"><i class="icon-edit"></i>
                                           <input type="text" name="surname" value="<?php echo @$surname; ?>" id="surname" class="input-large" data-rule-required="true" /></span>
							              <label class="small-labels">Surname</label></td>
								          <td class="controls controls-row">
                                          <span class="input-icon"><i class="icon-edit"></i>
                                          <input type="text" name="othername" value="<?php echo @$other_name; ?>" id="othername" class="input-large" data-rule-required="true" /></span>
								            <label class="small-labels">Othername</label></td>
								          <td class="controls controls-row">&nbsp;</td>
							            </tr>
								        <tr>
								          <td class="controls controls-row">
                                          <select name="gender" id="gender" class='input-large' data-rule-required="true">
								            <option value="" disabled> ...Select Gender... </option>
                      <?php while ($row = mysql_fetch_assoc($gender_result)){
							echo("<option value=\"{$row['id']}\""); 
								if(@$gender==$row['id']){echo "SELECTED";}
							echo(">{$row['name']}</option>");   
							}		 
					?>
								            </select><br/>
								            <label class="small-labels">Gender</label></td>
								          <td class="controls controls-row">
                                          <span class="input-icon"><i class="icon-calendar"></i>
                                          <input type="text" name="date_of_birth" id="id-date-picker-1" value="<?php echo @$date_of_birth; ?>" class="date-picker" data-rule-required="true"></span>
								          <label class="small-labels">Date of Birth</label></td>
								          <td class="controls controls-row">&nbsp;</td>
							            </tr>
                                        <tr>
								          <td class="controls controls-row">
                                          <select name="nationality" id="nationality" class='input-large' data-rule-required="true">
								            <option value="" disabled>...Select Nationality...</option>
										  <?php while ($row = mysql_fetch_assoc($country_result)){
												  $code=$row['code'];$country=$row['name'];
												  if($code==@$nationality){
													echo "<option value='$code' selected='selected'>$country</option>"; 
												  }else if($row['code']=='GH'){
													echo "<option value='$code' selected='selected'>$country</option>";  
												  }else{
													 echo "<option value='$code'>$country</option>";  
												  }
											  }		 
                                           ?></select>
								            <label class="small-labels">Nationality</label></td>
								          <td class="controls controls-row">
                                          <span class="input-icon"><i class="icon-home"></i>
                                          <input type="text" name="hometown" value="<?php echo @$hometown ?>" id="hometown" class="input-large" data-rule-required="true"></span><br/>
							              <label class="small-labels">Hometown</label></td>
								          <td class="controls controls-row">&nbsp;</td>
							            </tr>
                                        <tr>
								          <td class="controls controls-row">
                                          <select name="region" id="region" class='input-large' data-rule-required="true">
                                           <option value="" disabled>...Select Region...</option>
											  <?php while ($row = mysql_fetch_assoc($region_result)){
                                                echo("<option value=\"{$row['id']}\""); 
                                                    if(@$region==$row['id']){echo "SELECTED";}
                                                echo(">{$row['name']}</option>");   
                                                }		 
                                                ?>
								            </select>
								            <br>
								            <label class="small-labels">Region</label></td>
								          <td class="controls controls-row">
                                           <select name="religion" id="religion" class='input-large' data-rule-required="true">
								            <option value="" disabled>...Select Religion...</option>
										  <?php while ($row = mysql_fetch_assoc($religion_result)){
												echo("<option value=\"{$row['id']}\""); 
													if(@$religion==$row['id']){echo "SELECTED";}
												echo(">{$row['name']}</option>");   
												}		 
                                            ?>
								            </select><br>
                                            <label class="small-labels">Religion</label></td>
								          <td class="controls controls-row">&nbsp;</td>
							            </tr>
                                  <tr>
						            <td class="controls controls-row">
                                    <span class="input-icon"><i class=" icon-medkit"></i>
                                    <input type="text" name="disability" id="disability" value="<?php echo @$disability; ?>" class="input-large" data-rule-required="false">
						            <br></span><label class="small-labels">Disability</label></td>
								          <td class="controls controls-row">
                                           <select name="sponsor" id="sponsor" class='input-medium'>
								            <option value="" disabled>...Select Sponsor...</option>
											  <?php while ($row = mysql_fetch_assoc($sponsorship_result)){
                                                echo("<option value=\"{$row['id']}\""); 
                                                    if(@$sponsor==$row['id']){echo "SELECTED";}
                                                echo(">{$row['name']}</option>");   
                                                }		 
                                                ?>
								            </select><br>
                                            <label class="small-labels">Sponsor</label></td>
								          <td class="controls controls-row">&nbsp;</td>
							            </tr>
                                  <tr>
								          <td class="controls controls-row">
                                          <select name="program" id="program" class='input-large'>
								            <option value="" disabled>...Select Program...</option>
														  <?php while ($row = mysql_fetch_assoc($program_result)){
                                                echo("<option value=\"{$row['id']}\""); 
                                                    if(@$program==$row['id']){echo "SELECTED";}
                                                echo(">{$row['name']}</option>");   
                                                }		 
                                                ?>
								            </select>
								            <br>
								            <label class="small-labels">Group</label></td>
								          <td class="controls controls-row">
                                           <select name="level" id="level" class='input-medium'>
								            <option value="">...Select Level...</option>
											  <?php while ($row = mysql_fetch_assoc($level_result)){
												echo("<option value=\"{$row['id']}\""); 
													if(@$level==$row['id']){echo "SELECTED";}
												echo(">{$row['level']}</option>");   
												}		 
											 ?>
								            </select><br>
                                            <label class="small-labels">Class</label></td>
								          <td class="controls controls-row">&nbsp;</td>
					              </tr>
                                  <tr>
								   <td colspan="2" class="controls controls-row">
                                   <span class="input-icon"><i class="icon-book"></i>
                                  <input type="text" name="last_sch" id="last_sch" value="<?php echo @$last_school ; ?>" class="input-xxlarge"></span>
						            <br><label class="small-labels">Last School Attended</label>
                                    </td>
                                  </tr></tbody></table>
                                  <h4 class="header smaller lighter blue">Guardien Information</h4>
                                <table>
                                	<tbody>
								        <tr>
								          <td class="controls controls-row">
                                          <select name="guardian_title" id="guardian_title" class='input-small'>
								             <option value="" disabled>...Select Title...</option>
												  <?php while ($row = mysql_fetch_assoc($guardian_title_result)){
                                                echo("<option value=\"{$row['id']}\""); 
                                                    if(@$guardian_title==$row['id']){echo "SELECTED";}
                                                echo(">{$row['name']}</option>");   
                                                }		 
                                                ?>
								            </select>
								            <br>
								            <label class="small-labels">Title</label>
                                          </td>
								          <td class="controls controls-row"></td>
								          <td class="controls controls-row"></td>
							            </tr>
                                        <tr>
								          <td class="controls controls-row">
                                          <span class="input-icon"><i class=" icon-edit"></i>
              <input type="text" name="guardian_surname" value="<?php echo @$guardian_surname; ?>" id="guardian_surname" class="input-large">
								            </span><br>
								            <label class="small-labels">Surname</label>
                                          </td>
								          <td class="controls controls-row">
                                          <span class="input-icon"><i class=" icon-edit"></i>
           <input type="text" value="<?php echo @$guardian_other_name; ?>" name="guardian_other_name" id="guardian_other_name" class="input-large"></span><br>
								            <label class="small-labels">Othername</label>
                                          </td>
								          <td class="controls controls-row"></td>
							            </tr>
								        <tr>
								          <td class="controls controls-row">
                                          <span class="input-icon"><i class="icon-envelope"></i>
          <input type="text" value="<?php echo @$guardian_email; ?>" name="guardian_email" id="guardian_email" class="input-large">
								            </span><br>
							              <label class="small-labels">Email</label></td>
								          <td class="controls controls-row">
                                          <span class="input-icon"><i class="icon-phone-sign"></i>
                                          <input type="text" name="guardian_mobile_number" value="<?php echo @$guardian_mobile_number; ?>" id="guardian_mobile_number" class="input-large input-mask-phone"></span><br />
							              <label class="small-labels">Mobile No.</label></td>
								          <td class="controls controls-row">&nbsp;</td>
							            </tr>
                                        <tr>
						            <td class="controls controls-row">
                                     <select name="relationship" id="relationship" class='input-medium'>
								      <option value="" disabled> ...Select Relationship... </option>
										  <?php while ($row = mysql_fetch_assoc($relationship_result)){
                                        echo("<option value=\"{$row['id']}\""); 
                                            if(@$relationship==$row['id']){echo "SELECTED";}
                                        echo(">{$row['name']}</option>");   
                                        }		 
                                        ?>
								   </select>     
						            <br><label class="small-labels">Relationship</label></td>
								          <td class="controls controls-row">
                                         <span class="input-icon"><i class=" icon-briefcase"></i>
          <input value="<?php echo @$occupation; ?>" type="text" name="occupation" id="occupation" class="input-large ">
                                           </span><br>
                                          <label class="small-labels">Occupation</label></td>
								          <td class="controls controls-row">&nbsp;</td>
							            </tr>
                                        <tr>
                                          <td colspan="2" class="controls controls-row">
<textarea id="guardian_residential_address" class="input-block-level" rows="5" name="guardian_residential_address"><?php echo @$guardian_residential_address; ?></textarea>
                                      <br>
                                    <label class="small-labels">Address</label></td>
                                          <td class="controls controls-row">&nbsp;</td>
                                        </tr>
                                </tbody>
            </table>
                                  </div>
	
		<div class="form-actions">
			<button class="btn btn-info" type="submit" name="submit" value="submit"><i class="icon-ok"></i> Submit</button>
			&nbsp; &nbsp; &nbsp;
			<button class="btn" type="reset"><i class="icon-undo"></i> Reset</button>
		</div>
		
	 </form>

<!-- PAGE CONTENT ENDS HERE -->
						 </div><!--/row-->
	
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
					var sizing = ['', 'input-mini', 'input-small', 'input-medium', 'input-large', 'input-xlarge', 'input-xxlarge'];
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

			
			/**
			var stubDataSource = {
			data: function (options, callback) {

				setTimeout(function () {
					callback({
						data: [
							{ name: 'Test Folder 1', type: 'folder', additionalParameters: { id: 'F1' } },
							{ name: 'Test Folder 1', type: 'folder', additionalParameters: { id: 'F2' } },
							{ name: 'Test Item 1', type: 'item', additionalParameters: { id: 'I1' } },
							{ name: 'Test Item 2', type: 'item', additionalParameters: { id: 'I2' } }
						]
					});
				}, 0);

			}
			};
			$('#MyTree').tree({ dataSource: stubDataSource , multiSelect:true })
			*/
			
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
