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

 @$bank = $_POST["bank"];
 @$id = $_POST["id"];
 
echo $_POST['submit'];
 
 if (isset($_POST['submit']))
 {
   switch ($_POST['submit'])
   {
     case 'Update':
      if (trim($bank) != "")
      {
        $query_update = "UPDATE `bank` SET `Name`='$bank' WHERE `BankID` = '$id';";
        $result_update = mysql_query($query_update) or die(mysql_error());

            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn) or die('Could not fetch data; ' . mysql_error());
            $rows = mysql_fetch_array($result);

            $query_update_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Bank','Modified Bank: " . $bank . "')";
            $result_update_Log = mysql_query($query_update_Log) or die(mysql_error());
            ###### 

        $val="Bank";
        $tval="Your record has been updated.";
        header("location:settings.php?cmbTable=$val&tval=$tval");
      }
      else
      {
	   $val="bank";
       $tval="Please enter Bank before updating.";
       header("location:setting.php?$val=1&ID=$id&tval=$tval");
      }
      break;
     case 'Save':
      if (Trim($bank) != "")
      {
        $query_insert = "Insert into `bank` (`Name`) 
               VALUES ('$bank')";
        $result_insert = mysql_query($query_insert) or die(mysql_error());

            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn) or die('Could not fetch data; ' . mysql_error());
            $rows = mysql_fetch_array($result);

            $query_insert_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Bank','Added Bank: " . $bank . "')";
            $result_insert_Log = mysql_query($query_insert_Log) or die(mysql_error());
            ###### 
        $val="Bank";
        $tval="Your record has been saved.";
        header("location:settings.php?cmbTable=$val&tval=$tval");
      }
      else
      {
	    $val="bank";
        $tval="Please enter Bank before saving.";
        header("location:setting.php?$val=1&ID=$id&tval=$tval");
      }
      break;
     case 'Delete':
      {
       $query_delete = "DELETE FROM `bank` WHERE `BankID` = '$id';";
       $result_delete = mysql_query($query_delete) or die(mysql_error());          

            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn) or die('Could not fetch data; ' . mysql_error());
            $rows = mysql_fetch_array($result);

            $query_delete_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Bank','Deleted Bank: " . $bank . "')";
            $result_delete_Log = mysql_query($query_delete_Log) or die(mysql_error());
            ###### 

        $val="Bank";
        $tval="Your record has been deleted.";
        header("location:settings.php?cmbTable=$val&tval=$tval");
      }
      break;   
   }
 }
?>