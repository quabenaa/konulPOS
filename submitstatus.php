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

 @$stat = $_POST["stat"];
 @$id = $_POST["id"];
 
if (isset($_POST['submit']))
 {
   switch ($_POST['submit'])
   {
     case 'Update':
      if (Trim($stat) != "")
      {
        $query_update = "UPDATE `status` SET `Status`='$stat' WHERE `id` = '$id';";
        $result_update = mysql_query($query_update) or die(mysql_error());

            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn) or die('Could not fetch data; ' . mysql_error());
            $rows = mysql_fetch_array($result);

            $query_update_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Status','Modified Status: " . $stat . "')";
            $result_update_Log = mysql_query($query_update_Log) or die(mysql_error());
            ###### 

        $val="Status";
        $tval="Your record has been updated.";
        header("location:settings.php?cmbTable=$val&tval=$tval");
      }
      else
      {
	  $val="status";
        $tval="Please enter Status before updating.";
        header("location:setting.php?$val=1&ID=$id&tval=$tval");
      }
      break;
     case 'Save':
      if (Trim($stat) != "")
      {
        $query_insert = "Insert into `status` (`Status`) 
               VALUES ('$stat')";
        $result_insert = mysql_query($query_insert) or die(mysql_error());

            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn) or die('Could not fetch data; ' . mysql_error());
            $rows = mysql_fetch_array($result);

            $query_insert_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Status','Added Status: " . $stat . "')";
            $result_insert_Log = mysql_query($query_insert_Log) or die(mysql_error());
            ###### 
        $val="Status";
        $tval="Your record has been saved.";
        header("location:settings.php?cmbTable=$val&tval=$tval");
      }
      else
      {
	   $val="status";
        $tval="Please enter Status before saving.";
        header("location:setting.php?$val=1&ID=$id&tval=$tval");
      }
      break;
     case 'Delete':
      {
       $query_delete = "DELETE FROM `status` WHERE `id` = '$id';";
       $result_delete = mysql_query($query_delete) or die(mysql_error());          

            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn) or die('Could not fetch data; ' . mysql_error());
            $rows = mysql_fetch_array($result);

            $query_delete_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Status','Deleted Status: " . $stat . "')";
            $result_delete_Log = mysql_query($query_delete_Log) or die(mysql_error());
            ###### 

        $val="Status";
        $tval="Your record has been deleted.";
        header("location:settings.php?cmbTable=$val&tval=$tval");
      }
      break;   
   }
 }
?>