<?php
session_start();
require_once 'conn.php';

//check to see if user has logged in with a valid password
if (!isset($_SESSION['user_id']) && !isset($_SESSION['access_lvl']))
{
header("Location:./");
}
else if($_SESSION['access_lvl'] != 2 && $_SESSION['access_lvl'] != 4){
	header("Location:error-401.php?/access=denied/");
die();
}

 $id = $_POST["id"];
 $code = $_POST["code"];
 $category = $_POST["category"];
 //$brandname = $_POST["brandname"];
 $stockname = $_POST["stockname"];
 //$description = $_POST["description"];
 //$manufacturer = $_POST["manufacturer"];
 $supplier = $_POST["supplier"];
 //$location = $_POST["location"];
 $unitcost = $_POST["unitcost"];
 $sellingprice = $_POST["sellingprice"];
 $expiry = date('Y-m-d',strtotime($_POST["expiry"]));
 $reorderlevel = $_POST["reorderlevel"];
 $stockunit = $_POST["stockunit"];
 //$weight = $_POST["weight"];
 $margin = $_POST["margin"];
 
 if (isset($_POST['submit']))
 {
	 
   switch ($_POST['submit'])
   {
     case 'update':
      if (Trim($code) != "")
      {
        $query_update = "UPDATE stock SET `Stock Code` = '$code',`Category`='$category', `Stock Name`='$stockname',
          `Supplier`='$supplier',`Unit Cost`='$unitcost',`Expiry Date`='$expiry',`Reorder Level`='$reorderlevel',`Units in Stock`='$stockunit'
          ,`Selling Price`='$sellingprice',`Margin`='$margin' WHERE `ID` = '$id'";

        $result_update = mysql_query($query_update)or die(mysql_error());

            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn);
            $rows = mysql_fetch_array($result);

            $query_update_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Stock Record','Modified Stock Record for Stock: " . $code . ", " . $stockname . "')";

            $result_update_Log = mysql_query($query_update_Log);
            ###### 

        $tval="Your record has been updated.";
        header("location:stocklist.php?code=$code&tval=$tval&store=Retail&redirect=$redirect");
      }
      else
      {
        $tval="Please enter Stock Code before updating.";
        header("location:stock.php?code=$code&tval=$tval&redirect=$redirect");
      }
      break;
     case 'save':
      if (Trim($code) != "")
      { 
	 
        $query_insert = "Insert into stock 
			(`Stock Code`,`Category`, `Stock Name`,`Supplier`,`Unit Cost`,`Expiry Date`,`Reorder Level`,`Units in Stock`,`stockbalance`, `Selling Price`,`Margin`) 
              VALUES ('$code','$category', '$stockname','$supplier','$unitcost','$expiry','$reorderlevel','$stockunit','0','$sellingprice','$margin')";

        $result_insert = mysql_query($query_insert) or die(mysql_error());
            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn);
            $rows = mysql_fetch_array($result);

            $query_insert_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Stock Record','Added Stock Record for Stock: " . $code . ", " . $stockname . "')";

            $result_insert_Log = mysql_query($query_insert_Log);
            ###### 

        $tval="Your record has been saved.";
        header("location:stocklist.php?code=$code&tval=$tval&store=Retail&redirect=$redirect");
      }
      else
      {
        $tval="Please enter Stock Code before saving.";
        header("location:stock.php?code=$code&tval=$tval&redirect=$redirect");
      }
      break;
     case 'delete':
      {
       $query_delete = "DELETE FROM stock WHERE `ID` = '$id';";

       $result_delete = mysql_query($query_delete);          

            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn);
            $rows = mysql_fetch_array($result);

            $query_delete_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Stock Record','Deleted Stock Record for Stock: " . $code . ", " . $stockname . "')";

            $result_delete_Log = mysql_query($query_delete_Log);
            ###### 

        $tval="Your record has been deleted.";
        header("location:stocklist.php?code=$code&tval=$tval&store=Retail&redirect=$redirect");
      }
      break;     
     case 'Issue Book':
      {

        header("location:stock.php?code=$code&tval=$tval&redirect=$redirect");
      }
      break;     
   }
 }
?>