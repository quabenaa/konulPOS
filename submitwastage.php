<?php
session_start();
//check to see if user has logged in with a valid password
if (!isset($_SESSION['USER_ID']) & ($_SESSION['access_lvl'] != 2))
{
 if ($_SESSION['access_lvl'] != 1){
$redirect = $_SERVER['PHP_SELF'];
header("Refresh: 5; URL=login.php?redirect=$redirect");
echo "Sorry, but you don’t have permission to view this page! You are being redirected to the login page!<br>";
echo "(If your browser doesn’t support this, " .
"<a href=\"login.php?redirect=$redirect\">click here</a>)";
die();
}
}

 @$id = $_POST["id"];
 $code = $_POST["code"];
 $category = $_POST["category"];
 $wdate = $_POST["wdate"];
 $stockname = $_POST["stockname"];
 $units = $_POST["units"];
 $qnty = $_POST["qnty"];
 $balance = $_POST["balance"];
 $detectedby = $_POST["detectedby"];
 $approvedby = $_POST["approvedby"];

if ($_POST['wdate'] !='0000-00-00' and $_POST['wdate'] !='')
{
$rdate = $_POST['wdate'];
list($dayy, $monthh, $yearr) = explode('-', $rdate);
$rrdate = $yearr . '-' . $monthh . '-' . $dayy;
#echo $dob;
}

 require_once 'conn.php';
 
 if (isset($_POST['submit']))
 {
   switch ($_POST['submit'])
   {
     case 'Save':
      if (Trim($code) != "")
      { 
       $query_update = "UPDATE `stock` SET `Units in Stock` = (`Units in Stock` - " . $qnty . ") WHERE `Stock Code`='$code' OR `Stock Name`='$stockname'";
        $result_update = mysql_query($query_update) or die(mysql_error());

        $query_insert = "Insert into wastage (`Stock Code`,`Category`,`Stock Name`, `Wastage Date`,`Approved By`,`Detected By`,`Qnty`) 
               VALUES ('$code','$category','$stockname','$rrdate','$approvedby','$detectedby','$qnty')";
        $result_insert = mysql_query($query_insert) or die(mysql_error());

            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn) or die(mysql_error());
            $rows = mysql_fetch_array($result) or die(mysql_error());

            $query_insert_Log = "Insert into `monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Re-Stock Record','Added Re-Stock Record for Stock: " . $code . ", " . $stockname . "')";

            $result_insert_Log = mysql_query($query_insert_Log) or die(mysql_error());
            ###### 

        $tval="Your record has been saved.";
        header("location:wastage.php?code=$code&tval=$tval&redirect=$redirect");
      }
      else
      {
        $tval="Please enter Stock Code before saving.";
        header("location:wastageupdate.php?id=$id&code=$code&tval=$tval&redirect=$redirect");
      }
      break;
     case 'Delete':
      {
       $query_delete = "DELETE FROM `wastage` WHERE `ID` = '$id';";
       $result_delete = mysql_query($query_delete);          

            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn);
            $rows = mysql_fetch_array($result);

            $query_delete_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Re-Stock Record','Deleted Re-Stock Record for Stock: " . $code . ", " . $stockname . "')";

            $result_delete_Log = mysql_query($query_delete_Log);
            ###### 

        $tval="Your record has been deleted.";
        header("location:wastage.php?code=$code&tval=$tval&redirect=$redirect");
      }
      break;     
     case 'Update':
      if (Trim($code) != "")
      {
        $query_update = "UPDATE `stock` SET `Units in Stock` = (`Units in Stock` - " . $qntyadded . ") WHERE `Stock Code`='$code'";
        $result_update = mysql_query($query_update);

        $query_update2 = "UPDATE wastage SET `Stock Code` = '$code',`Category`='$category',`Stock Name`='$stockname',
          `Wastage Date`='$rrdate',`Approved By`='$approvedby',`Unit Cost`='$unitcost',`Detected By`='$detectedby',`Qnty`='$qnty' WHERE `ID` = '$id'";
        $result_update2 = mysql_query($query_update2);

            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn);
            $rows = mysql_fetch_array($result);

            $query_update_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Re-Stock Record','Modified Re-Stock Record for Stock: " . $code . ", " . $stockname . "')";

            $result_update_Log = mysql_query($query_update_Log);
            ###### 

        $tval="Your record has been updated.";
        header("location:wastage.php?code=$code&tval=$tval&redirect=$redirect");
      }
      else
      {
        $tval="Please enter Stock Code before updating.";
        header("location:wastageupdate.php?id=$id&code=$code&tval=$tval&redirect=$redirect");
      }
      break;
   }
 }
?>