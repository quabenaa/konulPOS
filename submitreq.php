<?php
session_start();
//check to see if user has logged in with a valid password
/* if (!isset($_SESSION['USER_ID']) & ($_SESSION['access_lvl'] != 2))
{
 if ($_SESSION['access_lvl'] != 1){
$redirect = $_SERVER['PHP_SELF'];
header("Refresh: 5; URL=login.php?redirect=$redirect");
echo "Sorry, but you don’t have permission to view this page! You are being redirected to the login page!<br>";
echo "(If your browser doesn’t support this, " .
"<a href=\"login.php?redirect=$redirect\">click here</a>)";
die();
}
} */

 $id = $_POST["id"];
 $code = $_POST["code"];
# $reqdate = $_POST["reqdate"];
 $stockname = $_POST["stockname"];
 $units = $_POST["units"];
 $qntyreq = $_POST["qntyreq"];
 $qntygiven = $_POST["qntygiven"];
 $approvedby = $_POST["approvedby"];
 $requestby = $_POST["requestby"];
 $givenby = $_POST["givenby"];
 $location = $_POST["location"];
 $colour = $_POST["colour"];
 $destination = $_POST["destination"];


if ($_POST['reqdate'] !='0000-00-00' and $_POST['reqdate'] !='')
{
$rdate = $_POST['reqdate'];
list($dayy, $monthh, $yearr) = explode('-', $rdate);
$reqdate = $yearr . '-' . $monthh . '-' . $dayy;
#echo $dob;
}


 require_once 'conn.php';
 
 if (isset($_POST['submit']))
 {
   switch ($_POST['submit'])
   {
     case 'Update Only':
      if (Trim($code) != "")
      {
        $query_update = "UPDATE requisition SET `Stock Code` = '$code',`Stock Name`='$stockname',
          `Stock Date`='$reqdate',`Request By`='$requestby',`Location`='$location',`Category`='$colour', `Destination`='$destination'
          ,`Qnty Req`='$qntyreq',`Approved By`='$approvedby',`Given By`='$givenby',`Qnty Given`='$qntygiven' WHERE `ID` = '$id'";

        $result_update = mysql_query($query_update);

            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn);
            $rows = mysql_fetch_array($result);

            $query_update_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Requisition Record','Modified Requisition Record for Stock: " . $code . ", " . $stockname . "')";

            $result_update_Log = mysql_query($query_update_Log);
            ###### 

        $tval="Your record has been updated.";
        header("location:requisition.php?code=$code&tval=$tval&redirect=$redirect");
      }
      else
      {
        $tval="Please enter Stock Code before updating.";
        header("location:requisitionupdate.php?id=$id&code=$code&tval=$tval&redirect=$redirect");
      }
      break;
     case 'Save Only':
      if (Trim($code) != "")
      { 
        $query_insert = "Insert into requisition (`Stock Code`,`Stock Name`,`Stock Date`,`Request By`,`Location`,`Category`,`Qnty Req`,`Approved By`,`Given By`,`Qnty Given`,`Destination`) 
               VALUES ('$code','$stockname','$reqdate','$requestby','$location','$colour','$qntyreq','$approvedby','$givenby','$qntygiven','$destination')";

        $result_insert = mysql_query($query_insert);

            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn);
            $rows = mysql_fetch_array($result);

            $query_insert_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Requisition Record','Added Requisition Record for Stock: " . $code . ", " . $stockname . "')";

            $result_insert_Log = mysql_query($query_insert_Log);
            ###### 

        $tval="Your record has been saved.";
        header("location:requisition.php?code=$code&tval=$tval&redirect=$redirect");
      }
      else
      {
        $tval="Please enter Stock Code before saving.";
        header("location:requisitionupdate.php?id=$id&code=$code&tval=$tval&redirect=$redirect");
      }
      break;
     case 'Save and Reconcile':
      if (Trim($code) != "")
      { 
        $query_update = "UPDATE `warehouse` SET `Units in Stock` = (`Units in Stock` - " . $qntygiven . ") WHERE `Stock Code`='$code'";
        $result_update = mysql_query($query_update);

       if (Trim($destination) == "Retail")
       { 
        $query_updater = "UPDATE `stock` SET `Units in Stock` = (`Units in Stock` + " . $qntygiven . ") WHERE `Stock Code`='$code'";
        $result_updater = mysql_query($query_updater);
       } 
       else if (Trim($destination) == "Wholesale")
       { 
        $query_updater = "UPDATE `wholesale` SET `Units in Stock` = (`Units in Stock` + " . $qntygiven . ") WHERE `Stock Code`='$code'";
        $result_updater = mysql_query($query_updater);
       } 

        $query_insert = "Insert into requisition (`Stock Code`,`Stock Name`,`Stock Date`,`Request By`,`Location`,`Category`,`Qnty Req`,`Approved By`,`Given By`,`Qnty Given`,`Destination`) 
               VALUES ('$code','$stockname','$reqdate','$requestby','$location','$colour','$qntyreq','$approvedby','$givenby','$qntygiven','$destination')";

        $result_insert = mysql_query($query_insert);

            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn);
            $rows = mysql_fetch_array($result);

            $query_insert_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Requisition Record','Added Requisition Record for Stock: " . $code . ", " . $stockname . "')";

            $result_insert_Log = mysql_query($query_insert_Log);
            ###### 

        $tval="Your record has been saved.";
        header("location:requisition.php?code=$code&tval=$tval&redirect=$redirect");
      }
      else
      {
        $tval="Please enter Stock Code before saving.";
        header("location:requisitionupdate.php?id=$id&code=$code&tval=$tval&redirect=$redirect");
      }
      break;
     case 'Delete':
      {
       $query_delete = "DELETE FROM requisition WHERE `ID` = '$id';";

       $result_delete = mysql_query($query_delete);          

            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn);
            $rows = mysql_fetch_array($result);

            $query_delete_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Requisition Record','Deleted Requisition Record for Stock: " . $code . ", " . $stockname . "')";

            $result_delete_Log = mysql_query($query_delete_Log);
            ###### 

        $tval="Your record has been deleted.";
        header("location:requisition.php?code=$code&tval=$tval&redirect=$redirect");
      }
      break;     
     case 'Update and Reconcile':
      if (Trim($code) != "")
      {
        $query_update = "UPDATE `stock` SET `Units in Stock` = (`Units in Stock` - " . $qntygiven . ") WHERE `Stock Code`='$code'";
        $result_update = mysql_query($query_update);

       if (Trim($destination) == "Retail")
       { 
        $query_updater = "UPDATE `stock` SET `Units in Stock` = (`Units in Stock` + " . $qntygiven . ") WHERE `Stock Code`='$code'";
        $result_updater = mysql_query($query_updater);
       } 
       else if (Trim($destination) == "Wholesale")
       { 
        $query_updater = "UPDATE `wholesale` SET `Units in Stock` = (`Units in Stock` + " . $qntygiven . ") WHERE `Stock Code`='$code'";
        $result_updater = mysql_query($query_updater);
       } 

        $query_update2 = "UPDATE `requisition` SET `Stock Code` = '$code',`Stock Name`='$stockname',
          `Stock Date`='$reqdate',`Request By`='$requestby',`Location`='$location',`Category`='$colour',`Destination`='$destination'
          ,`Qnty Req`='$qntyreq',`Approved By`='$approvedby',`Given By`='$givenby',`Qnty Given`='$qntygiven' WHERE `ID` = '$id'";

        $result_update2 = mysql_query($query_update2);

            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn);
            $rows = mysql_fetch_array($result);

            $query_update_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Requisition Record','Modified Requisition Record for Stock: " . $code . ", " . $stockname . "')";

            $result_update_Log = mysql_query($query_update_Log);
            ###### 

        $tval="Your record has been updated.";
        header("location:requisition.php?code=$code&tval=$tval&redirect=$redirect");
      }
      else
      {
        $tval="Please enter Stock Code before updating.";
        header("location:requisitionupdate.php?id=$id&code=$code&tval=$tval&redirect=$redirect");
      }
      break;
   }
 }
?>