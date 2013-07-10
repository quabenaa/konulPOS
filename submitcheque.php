<?php
session_start();
require_once 'conn.php';

//check to see if user has logged in with a valid password
if (!isset($_SESSION['user_id']) && !isset($_SESSION['access_lvl']))
{
header("Location:./");
}
else if($_SESSION['access_lvl'] != 2 && $_SESSION['access_lvl'] != 5){
	header("Location:error-401.php?/access=denied/");
die();
}
 

 $ID = $_POST["ID"];
 $type = $_POST["type"];
 $bank = $_POST["bank"];
 $issuedby = $_POST["issuedby"];
 $particulars = $_POST["particulars"];
 $amount = $_POST["amount"];
 $date = $_POST["date"];
 $cheque = $_POST["cheque"];

if (($_POST['date'] !='0000-00-00' OR $_POST['date'] !='0000/00/00' ) and $_POST['date'] !='')
{
$rdate = $_POST['date'];

$date =date("Y-m-d",strtotime($rdate));
}
 
 if (isset($_POST['submit']))
 {
   switch ($_POST['submit'])
   {
     case 'Update':
      if (Trim($ID) != "" && Trim($type) != "")
      {
        $query_update = "UPDATE cheque SET `Type` = '$type',Bank='$bank',`issuedby`='$issuedby', Particulars='$particulars',Amount='$amount',
          `Date`='$date',`Cheque No`='$cheque' WHERE `ID` = '$ID'";

        $result_update = mysql_query($query_update);

            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn);
            $rows = mysql_fetch_array($result);

            $query_update_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Cheque Register','Modified Cheque Register for: " . $type . ", Cheque No: " . $cheque . "')";

            $result_update_Log = mysql_query($query_update_Log);
            ###### 

        $tval="Your record has been updated.";
        header("location:cheque.php?ID=$ID&tval=$tval");
      }
      else
      {
        $tval="Please enter the Transaction Type before updating.";
        header("location:chequedetail.php?ID=$ID&tval=$tval");
      }
      break;
     case 'Save':
      if (Trim($type) != "")
      { 
        $query_insert = "Insert into cheque (`Type`,Bank, `issuedby`, Particulars,Amount,`Date`,`Cheque No`) 
               VALUES ('$type','$bank', '$issuedby', $particulars','$amount','$date','$cheque')";

        $result_insert = mysql_query($query_insert);

            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn);
            $rows = mysql_fetch_array($result);

            $query_insert_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Cheque Register','Added Cheque Register for: " . $type . ", Cheque No: " . $cheque . "')";

            $result_insert_Log = mysql_query($query_insert_Log);
            ###### 

        $tval="Your record has been saved.";
        header("location:cheque.php?ID=$ID&tval=$tval");
      }
      else
      {
        $tval="Please enter the Transaction Type before saving.";
        header("location:chequedetail.php?ID=$ID&tval=$tval");
      }
      break;
     case 'Delete':
      {
       $query_delete = "DELETE FROM cheque WHERE `ID` = '$ID'";

       $result_delete = mysql_query($query_delete);          


            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn);
            $rows = mysql_fetch_array($result);

            $query_delete_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`,`company`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Cheque Register','Deleted Cheque Register for: " . $type . ", Cheque No: " . $cheque . "')";

            $result_delete_Log = mysql_query($query_delete_Log);
            ###### 

        $tval="Your record has been deleted.";
        header("location:cheque.php?ID=$ID&tval=$tval");
      }
      break;     
   }
 }
?>