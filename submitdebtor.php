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
	$company = $_POST["company"];
	$contact = $_POST["contact"];
	$paid = $_POST["paid"];
	$amount = $_POST["amount"];
	$date = date('Y-m-d',strtotime($_POST["date"]));
	$balance = $amount-$paid;
	$bankname = $_POST["bankname"];
	$chequeno = $_POST["chequeno"];
	$chequedate = date('Y-m-d',strtotime($_POST["chequedate"]));
	$chequestatus = $_POST["chequestatus"];


 
 if (isset($_POST['submit']))
 {
   switch ($_POST['submit'])
   {
     case 'Update':
      if (Trim($ID) != "" && Trim($company) != "")
      {
		 
       $query_update = "UPDATE `debtor` SET `Company` = '$company',`Contact Person`='$contact', `Paid`='$paid',`Amount`='$amount',
          `Date`='$date',`Balance`='$balance',`Bank Name`='$bankname',`Cheque No`='$chequeno',`Cheque Date`='$chequedate',`Cheque Status`='$chequestatus' WHERE `ID` = '$ID'";
        $result_update = mysql_query($query_update) or die(mysql_error());

         $customer = "SELECT * 
					FROM  `customers` WHERE name = '$contact' OR name = '$company'";
		$result_cust = mysql_query($customer) or die(mysql_error());
		if(mysql_num_rows($result_cust)>0)
		{
		$query = "
		UPDATE `customers` 
			SET `creditbalance`=(creditbalance + $paid),`outstandingpayment`=(outstandingpayment - $paid)
		WHERE name = '$contact' OR name = '$company'";	
		$resulta = mysql_query($query) or die(mysql_error());
		}  
		   
		   
		   #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn) or die(mysql_error());
            $rows = mysql_fetch_array($result) or die(mysql_error());

            $query_update_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Debtors Schedule','Modified Debtors record for : " . $date . ", Company: " . $company . "')";
            $result_update_Log = mysql_query($query_update_Log) or die(mysql_error());
            ###### 

        $tval="Your record has been updated.";
        header("location:debtors.php?ID=$ID&tval=$tval");
      }
      else
      {
        $tval="Please enter the Company before updating.";
        header("location:debtorsdetail.php?ID=$ID&tval=$tval");
      }
      break;
     case 'Save':
      if (Trim($company) != "")
      { 
        $query_insert = "Insert into debtor (`Company`,`Contact Person`, `Paid`,`Amount`,`Date`,`Balance`,`Bank Name`,`Cheque No`,`Cheque Date`,`Cheque Status`)
               VALUES ('$company','$contact','$paid','$amount','$date','$balance','$bankname','$chequeno','$chequedate','$chequestatus')";
        $result_insert = mysql_query($query_insert);

            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn);
            $rows = mysql_fetch_array($result);

            $query_insert_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Debtors Schedule','Added Debtors record for: " . $date . ", Company: " . $company . "')";
            $result_insert_Log = mysql_query($query_insert_Log);
            ###### 

        $tval="Your record has been saved.";
        header("location:debtors.php?ID=$ID&tval=$tval");
      }
      else
      {
        $tval="Please enter the Company before saving.";
        header("location:debtorsdetail.php?ID=$ID&tval=$tval");
      }
      break;
     case 'Delete':
      {
       $query_delete = "DELETE FROM debtor WHERE `ID` = '$ID'";
       $result_delete = mysql_query($query_delete);          

            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn);
            $rows = mysql_fetch_array($result);

            $query_delete_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Debtors Schedule','Deleted Debtors record for: " . $date . ", Company: " . $company . "')";
            $result_delete_Log = mysql_query($query_delete_Log);
            ###### 

        $tval="Your record has been deleted.";
        header("location:debtors.php?ID=$ID&tval=$tval");
      }
      break;     
   }
 }
?>