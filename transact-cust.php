<?php
  session_start();
  require_once 'conn.php';
  require_once 'http.php';
  if (isset($_REQUEST['action'])) 
  {
    switch ($_REQUEST['action']) 
    {
case 'Create Account':
if (
$_POST['custnumb'] !="" 
AND $_POST['name'] !=""
AND $_POST['contact'] !="" 
#AND $_POST['address'] !="" 
AND $_POST['status'] !="" 
AND $_POST['creditlimit'] !="" 
AND $_POST['creditbalance'] !="" 
AND $_POST['payment'] !=""
)
{
$sql = "INSERT INTO customers (`cust_numb`, `name`, `address`, `contact`, `creditlimit`, `creditbalance`, `outstandingpayment`, `status`) " .
"VALUES 
('".$_POST['custnumb']."', '" .
strtoupper($_POST['name']) . "',
'" .$_POST['address']. "',
'" . $_POST['contact']. "',
'" .$_POST['creditlimit']. "',
'" . $_POST['creditbalance'] . "',
'" . $_POST['payment']. "',
'" .$_POST['status']. "')";

mysql_query($sql,$conn) or die(mysql_error());

             #######

            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn) or die('Could not fetch data; ' . mysql_error());
            $rows = mysql_fetch_array($result);

            $query_insert_Log = "Insert into `monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Created New Customer Account','Customer Account Created: " . $_POST['name'] . "')";

            $result_insert_Log = mysql_query($query_insert_Log) or die(mysql_error());
            ###### 
			
 $tval="New User Account Created!";
 header("location:customerslist.php?tval=$tval");
}
else
{
 $tval="Please fill in all parameters!";
 header("location:customer.php?tval=$tval");
}
break;
####################################################################################################
case 'Modify Account':
if (
$_POST['custnumb'] !="" 
AND $_POST['name'] !=""
AND $_POST['contact'] !="" 
#AND $_POST['address'] !="" 
AND $_POST['status'] !="" 
AND $_POST['creditlimit'] !="" 
AND $_POST['creditbalance'] !="" 
AND $_POST['payment'] !=""
AND $_POST['user_id'] !="" 
)
{


$sql = "UPDATE customers " .
"SET name ='" . strtoupper($_POST['name']) .
"', cust_numb ='" . $_POST['custnumb'] .
"', contact ='" . $_POST['contact'] .
"', address ='" . $_POST['address'] .
"', status ='" . $_POST['status'] .
"', creditlimit ='" . $_POST['creditlimit'] .
"', creditbalance ='" . $_POST['creditbalance'] .
"', outstandingpayment ='" . $_POST['payment'] . " '" .
" WHERE id=" . $_POST['user_id'];
mysql_query($sql,$conn) or die(mysql_error());
 
             #######

            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn) or die('Could not fetch data; ' . mysql_error());
            $rows = mysql_fetch_array($result);

            $query_insert_Log = "Insert into `monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Modified Customer Account','Customer Account Modified: " . $_POST['name'] . "')";

            $result_insert_Log = mysql_query($query_insert_Log) or die(mysql_error());
            ###### 
 
$tval="Customer Account Modified!"; 
 header("location:customerslist.php?cid=" . $_POST['user_id'] . "&tval=$tval");
}
else
{
 
 echo $tval="Please fill in all parameters!";
 header("location:customer.php?cid=" . $_POST['user_id'] . "&tval=$tval");
}
break;

########################################################################################################################################
case 'Delete Account':
if ($_POST['user_id'] !="")
{
$sql = "DELETE FROM `customers` WHERE `id`=" . $_POST['user_id'];
mysql_query($sql,$conn);
            #######
            session_start();
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn) or die('Could not fetch data; ' . mysql_error());
            $rows = mysql_fetch_array($result);

            $query_insert_Log = "Insert into `monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Customer Account','Customer Account Deleted: " . $_POST['name'] . "')";

            $result_insert_Log = mysql_query($query_insert_Log) or die(mysql_error());
            ###### 
 $tval="User Account Deleted!";
 header("location:customerslist.php?tval=$tval");
}
break;

}
}
?>