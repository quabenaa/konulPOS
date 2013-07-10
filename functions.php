<?php
function addClass($class){
	//validating Entry	---
	$checkValidation = mysql_query("SELECT * FROM class Where class_name='$class' ")or die(mysql_error());
	if(mysql_num_rows($checkValidation)<=0){
		mysql_query("INSERT INTO class(class_name) VALUES ('$class')")or die(mysql_error());
		//Getting the current Added subj_id---
		$getID = mysql_query("SELECT class_id FROM class WHERE class_name='$class'")or die(mysql_error());
		$row=mysql_fetch_array($getID);
		$class_id = $row['class_id'];	
	}
	return $class_id;
}

function setClass($class_id,$numberOfClass){
	$alphabet = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	for($i=0;$i<$numberOfClass;$i++){
		$letter=$alphabet[$i];
		mysql_query("INSERT INTO classLetters(class_id, letter) values ('$class','$letter')") or die(MYSQL_ERROR());
	}
}

function addSubject($subject){
	//validating Entry	---
	$checkValidation = mysql_query("SELECT * FROM subject Where subject_name='$subject' ")or die(mysql_error());
	if(mysql_num_rows($checkValidation)<=0){
		mysql_query("INSERT INTO subject(subject_name) VALUES ('$subject')")or die(mysql_error());
		//Getting the current Added subj_id---
		$getID = mysql_query("SELECT subj_id FROM subject Where subject_name='$subject'")or die(mysql_error());
		$row=mysql_fetch_array($getID);
		$subject_id = $row['subj_id'];	
	}
	return $subject_id;
}

function setSubject($class_id, $subject_id){
	$numberOfClass=count($class_id);
	for($i=0;$i<$numberOfClass;$i++){
		$checkDuplicate=mysql_query("SELECT * FROM  class_subjects where subject_id='$subject_id' AND class_id='$class_id[$i]'")or die(MYSQL_ERROR());
		if(mysql_num_rows($checkDuplicate)<=0){
		 mysql_query("INSERT INTO class_subjects(subject_id, class_id) values ('$subject_id','$class_id[$i]')")or die(MYSQL_ERROR());
		}
	}
}

function login($username, $password){
	if(empty($username) || empty($password)){
	$success_message='0';
	}else{
		$check_login=mysql_query("SELECT * FROM login WHERE username='$username'")or die(MYSQL_ERROR());
		if(mysql_num_rows($check_login)>0){
			  $row=mysql_fetch_array($check_login);
				if(md5($password)==$row['password']){
					session_start();
					$_SESSION['username'] = $row['username'];
					$_SESSION['access_level'] = $row['access_level'];
					$success_message='1';
				}else{
				$success_message='0';
				}
		}
	}
	return $success_message;
}

function addUser($name,$email,$username,$password,$access_level){
mysql_query("INSERT INTO login (name,email,username,password,access_level) VALUES('$name','$email','$username','$password','$access_level')")or die(MYSQL_ERROR());
}

function countUsers($table){

$sql = mysql_query("SELECT * FROM $table");
$record_count=mysql_num_rows($sql);
//Display count.........
echo $record_count;	
	
}
function systemLogs($user,$string){
	$date=time();
	mysql_error("INSERT INTO systemLogs ('username','activity','date') values ('$user','$string','$date')") or die(MYSQL_ERROR());
}

   // Basic functions 
function insertPicture(){
  if(isset($_FILES['picture'])){	  
	 $pictureSource='images/'.$_FILES['picture']['name'];
  }else{
	  $pictureSource="images/student.png";
  }
  echo $pictureSource;
}

function mysql_prep($value){    
    $magic_quotes_active = get_magic_quotes_gpc();
    $new_enough_php = function_exists("mysql_real_escape_string");// i.e.PHP >= v4.3.0
    if($new_enough_php){// PHP v4.3.0 or higher
        //undo any magic quote effects so mysql_real_escape_string can do the work
        if($magic_quotes_active){
            $value=stripslashes($value);
        }
        $value=mysql_real_escape_string($value);
    }
    else{// before PHP v4.3.0 or higher
        // if magic quotes are not already on then add slashes manully
        if(!$magic_quotes_active){
            $value=addslashes($value);           
        }
        // if magic quotes are active, then the slashes already exist        
    }
    return $value;
}
   
function comfirm_query($result_set){
    if(!$result_set){
        die("Database Query Failed: " .mysql_error());
    }  
}
   
function get_next_id($column_name){
	global $connection;
   //$tenant_id ="";
	// This could be supplied by a user, for example
//$column_name = 'next';
$id  = 'fix';
// Formulate Query
// This is the best way to perform an SQL query
// For more examples, see mysql_real_escape_string()
	$query = sprintf("SELECT {$column_name} FROM next_id 
    WHERE id='%s'",mysql_real_escape_string($id));
// Perform Query
$result = mysql_query($query);

// Check result
// This shows the actual query sent to MySQL, and the error. Useful for debugging.
if (!$result) {
    $message  = 'Invalid query: ' . mysql_error() . "\n";
    $message .= 'Whole query: ' . $query;
    die($message);
}

// Use result
// Attempting to print $result won't allow access to information in the resource
// One of the mysql result functions must be used
// See also mysql_result(), mysql_fetch_array(), mysql_fetch_row(), etc.
while ($row = mysql_fetch_assoc($result)) {
   $return_id=$row[$column_name];
}

// Free the resources associated with the result set
// This is done automatically at the end of the script
mysql_free_result($result);
return $return_id;
}
 
function set_next_id($column_name){
  global $connection;  
  $id  = 'fix';
  $value = get_next_id($column_name) + 1;
  $query = sprintf("UPDATE next_id SET {$column_name} = '%s' WHERE id='%s'",$value,$id);
  $result = mysql_query($query);
  if (mysql_affected_rows()!=1) {
    $message  = 'Invalid query: ' . mysql_error() . "\n";
    $message .= 'Whole query: ' . $query;
    die($message);
  }  
}

function get_column($table,$column_name,$id_field,$id){
	global $connection;  
	$query = sprintf("SELECT {$column_name} FROM {$table} 
    WHERE {$id_field}='%s'", mysql_real_escape_string($id));
$result = mysql_query($query);
if (!$result) {
    $message  = 'Invalid query: ' . mysql_error() . "\n";
    $message .= 'Whole query: ' . $query;
    die($message);
}
while ($row = mysql_fetch_assoc($result)) {
   $return_column=$row[$column_name];
}
mysql_free_result($result);
return @$return_column;
}

function filled_with_zero($value){
  while(strlen($value)<5){
    $value='0'.$value;
  }
  return $value;
}
  
function mysql_insert_array($table, $data, $password_field = "") {
	foreach ($data as $field=>$value) {
		$fields[] = '`' . $field . '`';
		
		if ($field == $password_field) {
			$values[] = "PASSWORD('" . mysql_real_escape_string($value) . "')";
		} else {
			$values[] = "'" . mysql_real_escape_string($value) . "'";
		}
	}
	$field_list = join(',', $fields);
	$value_list = join(', ', $values);
	
	$query = "INSERT INTO `" . $table . "` (" . $field_list . ") VALUES (" . $value_list . ")";
	
	return $query;
	} 

function mysql_update_array($table, $data, $id_field, $id_value){
	foreach($data as $field=>$value){
	   $fields[] = sprintf("`%s` = '%s'", $field, mysql_real_escape_string($value));
	}
	$field_list = join(',', $fields);
	
	$query = sprintf ("UPDATE `%s` SET %s WHERE `%s` = '%s'", $table, $field_list, $id_field, $id_value);
	//echo"{$query}";
	return $query;
}
function mysql_delete($table, $id_field, $id_value){
  $query = sprintf("DELETE FROM `%s` WHERE `%s` = '%s'",$table,$id_field,$id_value);
  //echo"{$query}";
  return $query;
}
function select_all($table_name){
    global $connection;   
    $query = sprintf("SELECT * FROM %s",$table_name); 
    $result = mysql_query($query);    
    if (!$result) {
	$message  = 'Invalid query: ' . mysql_error() . "\n";
	$message .= 'Whole query: ' . $query;
	die($message);
    }
    else{
	  return $result;
    }
}
function select_all_using_like($table_name, $column_name, $column_value){
	//SELECT * FROM cartoon_characters WHERE "roger" LIKE '%name%' 
    global $connection;   
    $query = sprintf("SELECT * FROM %s WHERE %s LIKE %s ", $table_name, $column_name, $column_value); 
    $result = mysql_query($query);    
    if (!$result) {
	$message  = 'Invalid query: ' . mysql_error() . "\n";
	$message .= 'Whole query: ' . $query;
	die($message);
    }
    else{
	  return $result;
    }
}

function select_all_sorted($table_name,$sort_field){//SELECT name, birth FROM pet ORDER BY birth
  global $connection;   
    $query = sprintf("SELECT * FROM %s ORDER BY %s",$table_name,$sort_field); 
    $result = mysql_query($query);    
    if (!$result) {
	$message  = 'Invalid query: ' . mysql_error() . "\n";
	$message .= 'Whole query: ' . $query;
	die($message);
    }
    else{
	  return $result;
    }
}
function select_by_fields($table, array $pair){
  //just init cond array:
  $condition = array();

  foreach ( $pair as $key => $value){
    //oh yeah, you can also automatically prevent SQL injection  
    $value = mysql_real_escape_string($value);

    $condition[] = "{$key} = '{$value}'";
  } 
 //Prepare for WHERE clause: 
 $condition = join(' AND ', $condition);
 //Return prepared string:
$query = "SELECT * FROM {$table} WHERE {$condition}";
 //echo $query;
 $result = mysql_query($query);
 return $result;
}

//print: SELECT * FROM your_table WHERE user = 'some' AND age = '10'
//print sql_select(array('user' => 'some', 'age' => 10));
function calculate_grade($value){
	$grade="";
	    if($value > 69){$grade= "A";}
	elseif($value > 59){$grade= "B";}
	elseif($value > 49){$grade= "C";}	
	elseif($value > 39){$grade= "D";}
	else{$grade= "F";}
	return($grade);
}
/*
Class Interval	Letter Grade	Grade Point
80-100	A+	4.00
75-79	A	3.75
70-74	A-	3.50
65-69	B+	3.25
60-64	B	3.00
55-59	B-	2.75
50-54	C+	2.50
45-49	C	2.25
40-44	C-	2.00
0-39	F	0.00
*/
function calculate_GPA($value){
	$grade="";
	    if($value > 69){$grade= 4.00;}	
	elseif($value > 64){$grade= 3.75;}
	elseif($value > 59){$grade= 3.50;}
	elseif($value > 54){$grade= 3.00;}
	elseif($value > 49){$grade= 2.50;}
	elseif($value > 44){$grade= 2.00;}
	elseif($value > 39){$grade= 1.50;}
	elseif($value > 34){$grade= 1.00;}
	else{$grade= 0.00;}
	return($grade);
}
function calculate_gradeGPA($value){
	$grade="";
	if($value > 69){$grade= "A";}	
	elseif($value > 64){$grade= "A-";}
	elseif($value > 59){$grade= "B+";}
	elseif($value > 54){$grade= "B";}
	elseif($value > 49){$grade= "B-";}
	elseif($value > 44){$grade= "C+";}
	elseif($value > 39){$grade= "C";}
	elseif($value > 34){$grade= "D";}
	else{$grade= "F";}
	return($grade);
}
function cal_FGPA($student_number){
	$total_cridit=0;
	$total_gpa=0; 	
	for($level=1;$level<=4;$level++){
		for($semester=1;$semester<=2;$semester++){			
			$exam_data=array(
				"student_no" => $student_number,
				"semester_id" => $semester,
				"level_id" => $level
			);
			$exam_result = select_by_fields('exam',$exam_data);
			$count = mysql_num_rows($exam_result);							
			if($count > 0){	
				$total_cridit_semenster=0;
				$total_gpa_semenster=0;
				while ($row = mysql_fetch_assoc($exam_result)){
					//get couse name
					$course=get_column('course','name','code',$row['course_code']);
					//get credit hours id
					$credit_hours_id=get_column('course','credit_hours_id','code',$row['course_code']);
					//get credit hours
					$credit_hours=get_column('credit_hours','value','id',$credit_hours_id);
					//accumulate cridit and gpa for semester
					$total_cridit_semenster += $credit_hours;
					$total_gpa_semenster += $row['gpa_by_weight'];
				}//end while
				
				$cgpa=$total_gpa_semenster / $total_cridit_semenster;
				$cgpa = number_format($cgpa, 2, '.', '');
				
				//accumulate cridit and gpa for ground total
				$total_cridit += $total_cridit_semenster;
				$total_gpa += $total_gpa_semenster;
				 				
			}//end if					
		}//end for semester
	}//end for level
	
	//ground totals
	if($total_gpa>0){
		$gpa=number_format($total_gpa / $total_cridit, 2, '.', '');
	}else{
		$gpa=0.00;
	}
	return $gpa;
}

function classification($value){
	$class="";
	    if($value > 3.74){$class = "DISTINCTION";}	
	elseif($value > 3.48){$class = "CREDIT";}
	elseif($value > 1.49){$class = "PASS";}	
	                 else{$class = "FAIL";}
	return $class;	
}

function calculateArrears($sn){
		$debit_data = array(
			"student_number" => $sn	
		);	
		$debit_result=select_by_fields('debit',$debit_data);
		$debit_total=0;
		$credit_total=0;		
		while($drow = mysql_fetch_assoc($debit_result)){				
			$debit_total += $drow['amount'];
			$debit_id=$drow['id'];
			$credit_data = array(
				"debit_id" => $debit_id				
			);
			$credit_result=select_by_fields('credit',$credit_data);			
			while($crow = mysql_fetch_assoc($credit_result)){				
				$credit_total += $crow['amount'];							
			}
		}
		$diff=$debit_total - $credit_total;
		return $diff;
}
	
function convert_string_date_to_numeric($value){
	$arr=explode(" ",$value);
	$day=$arr['0'];
	$month=str_replace(",","",$arr['1']);
	$month=convert_string_month_to_numeric($month);
	$year=$arr['2'];
	return $year."-".$month."-".$day;
}

function convert_string_month_to_numeric($value){
	Switch ($value) {
		case "January":
		   return "01";
		   break;
		case "February":
		   return "02";
		   break;
		case "March":
		   return "03";
		   break;
		case "April":
		   return "04";
		   break;
		case "May":
		   return "05";
		   break;
		case "June":
		   return "06";
		   break;
		case "July":
		   return "07";
		   break;
		case "August":
		   return "08";
		   break;
		case "September":
		   return "09";
		   break;
		case "October":
		   return "10";
		   break;
		case "November":
		   return "11";
		   break;
		case "December":
		   return "12";
		   break;       
		}
}
function convert_numeric_date_to_string($value){
	$arr=explode("-",$value);
	$day=$arr['2'];
	//$month=str_replace(",","",$arr['1']);
	$month=convert_numeric_month_to_string($arr['1']);
	$year=$arr['0'];
	return $day." ".$month.", ".$year;
}
function convert_numeric_month_to_string($value){
	Switch ($value) {
		case "01":
		   return "January";
		   break;
		case "02":
		   return "February";
		   break;
		case "03":
		   return "March";
		   break;
		case "04":
		   return "April";
		   break;
		case "05":
		   return "May";
		   break;
		case "06":
		   return "June";
		   break;
		case "07":
		   return "July";
		   break;
		case "08":
		   return "August";
		   break;
		case "09":
		   return "September";
		   break;
		case "10":
		   return "October";
		   break;
		case "11":
		   return "November";
		   break;
		case "12":
		   return "December";
		   break;       
		}
}
//backup_tables('localhost','root','',DB_NAME);
    /* backup the db OR just a table */
	//table='*' => backup all tables
	//table='name' => backup table name
	//table='name1,name2' => backup table name1,name2
    function backup_tables($host,$user,$pass,$name,$tables = 'student')
    {
	$return="";//"CREATE DATABASE /*!32312 IF NOT EXISTS*/`wg_school` /*!40100 DEFAULT CHARACTER SET latin1 */;
	//USE `wg_school`;";
    $link = mysql_connect($host,$user,$pass);
    mysql_select_db($name,$link);
    //get all of the tables
    if($tables == '*') {
    $tables = array();
    $result = mysql_query('SHOW TABLES');
    while($row = mysql_fetch_row($result)) {
    $tables[] = $row[0];
    }
    } else {
    $tables = is_array($tables) ? $tables : explode(',',$tables);
    }	
    //cycle through
    foreach($tables as $table) {
    $result = mysql_query('SELECT * FROM '.$table);
    $num_fields = mysql_num_fields($result);	
    //$return.= 'DROP TABLE '.$table.';';
    $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE '.$table));
    $return.= "\n\n".$row2[1].";\n\n";
    for ($i = 0; $i < $num_fields; $i++) {
    while($row = mysql_fetch_row($result)) {
    $return.= 'INSERT INTO '.$table.' VALUES(';
    for($j=0; $j<$num_fields; $j++) {
    $row[$j] = addslashes($row[$j]);
    $row[$j] = str_replace("\n","\\n",$row[$j]);
    if (isset($row[$j])) { $return.= '"'.$row[$j].'"' ; } else { $return.= '""'; }
    if ($j<($num_fields-1)) { $return.= ','; }
    }
    $return.= ");\n";
    }
    }
    $return.="\n\n\n";
    }
    //save file
    if(!is_dir("backup")) {
    mkdir("backup",0777);
    }	
    //$handle = fopen('./backup/db-backup-'.time().'-'.(md5(implode(',',$tables))).'.sql','w+');
	//$handle = fopen('./backup/db-backup-'.time().'.sql','w+');
	$handle = fopen('./backup/db-backup-'.date('Y-m-d-H-i-s').'.sql','w+');
    fwrite($handle,$return);
    fclose($handle);
    }
	
	function monitor($file_use, $detail){
		//processing system log data
			$datetime=date('Y-m-d H:i:s');
			$dt = explode(" ",$datetime);		
			$monitor_data=array(
					//"UserCategory" => $_SESSION['permission_id'],								
					//"UserName" => $_SESSION['username'],
					"DateLoggedOn" => $dt['0'],								
					"TimeLoggedOn" => $dt['1'],
					"FileUsed" => $file_use,
					"Details" => $detail
			);		
			$monitor_query = mysql_insert_array("monitor", $monitor_data, $password_field = "");
			if(mysql_query($monitor_query)){													    
			}
			else{
				die("Could not save data. ".mysql_error());				   
			}			
	}
	


?>