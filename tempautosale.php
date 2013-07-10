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
//$redirect = 'sales.php';
if(isset($_POST['submit'])){
	if(trim($_POST['yourText'])){
echo $yourText = $_POST['yourText'];
$sdate = date('Y-m-d');
$sql=mysql_query("SELECT * FROM `stock` WHERE `Stock Code`='$yourText' or `Stock Name`='$yourText'");
if(mysql_num_rows($sql)>0){
$row = mysql_fetch_array($sql);
$stkcode = $row['Stock Code'];
$stkname = $row['Stock Name'];
$loc = $row['Location'];
$qntysold = 1;
$discount = 0.00;
$price = $row['Selling Price'];
$totcost=$price*$qntysold;
$bal=($price*$qntysold)-$discount;
$check = mysql_query("SELECT * FROM `tempsales` WHERE `cust_id`='" . $_SESSION['cust_token'] ."' and `Stock Code`='$stkcode' and `Sales Date`='$sdate' ") or die(mysql_query());
$val = mysql_fetch_array($check);
if($val){
//if a value is found then update the quantity of the product along with the selling price
$qty = $val['Qnty Sold'];
$UC = $val['Unit Cost'];
$TC = $val['Total Cost'];

//make update for qty
$totqty = 0;
$totqty = $qty + 1; 

//make update for sales price
//$usale = 0.00;
$usale = $UC + $price;

//make update for total sales
//$totsale = 0.00;
$totsale = $price*$totqty;

//now make the update for the the table tempsales
$update_tempS = mysql_query("UPDATE `tempsales` SET `Unit Cost`='$price', `Total Cost`='$totsale',`Qnty Sold`='$totqty' WHERE`cust_id`='" . $_SESSION['cust_token'] . "' and `Stock Code`='$stkcode' and `Sales Date`='$sdate' ") or die(mysql_query());

 #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn);
            $rows = mysql_fetch_array($result);

            $query_insert_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Sales Record','Added Sales Record for Stock: " . $yourText . ", " . $stkname . "')";

            $result_insert_Log = mysql_query($query_insert_Log);
            ######

$tval="Your record has been saved.";
        header("location:sales.php?code=$yourText&tval=$tval");
}else{
//if no result is found then insert a new record into the table
$query_insert = "Insert into tempsales (`Stock Code`,`Stock Name`,`Sales Date`,`Unit Cost`,`Location`,`Total Cost`
,`Qnty Sold`,`Discount`,`Balance`,`Entered By`,`cust_id`,`Paid`)
               VALUES ('$stkcode','$stkname','$sdate','$price','$loc','$totcost','$qntysold','$discount','$bal','".strtoupper($_SESSION['name'])."','" . $_SESSION['cust_token'] . "','Cash')";

$result_insert = mysql_query($query_insert) or die (mysql_error()); 

 #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn);
            $rows = mysql_fetch_array($result);

            $query_insert_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Sales Record','Added Sales Record for Stock: " . $yourText . ", " . $stkname . "')";

            $result_insert_Log = mysql_query($query_insert_Log);
            ######

$tval="Your record has been saved.";
        header("location:sales.php?code=$yourText&tval=$tval");
}
//

	}else{
		$cVal="Product Name or Product Code not found.";
        header("location:sales.php?code=$yourText&cVal=$cVal");
	}
 }else{
		$cVal="Please enter Product Name or Product Code before saving.";
        header("location:sales.php?code=$yourText&cVal=$cVal");
 }
}
?>