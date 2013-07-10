<?php
session_start();
//check to see if user has logged in with a valid password
if (!isset($_SESSION['USER_ID']) & ($_SESSION['access_lvl'] != 2))
{
 if ($_SESSION['access_lvl'] != 4)
 {
  $redirect = $_SERVER['PHP_SELF'];
  header("Refresh: 5; URL=login.php?redirect=$redirect");
  echo "Sorry, but you don’t have permission to view this page! You are being redirected to the login page!<br>";
  echo "(If your browser doesn’t support this, " . "<a href=\"login.php?redirect=$redirect\">click here</a>)";
  die();
 }
}

 $id = $_POST["id"];
 $cod = $_POST["cod"];
 $qntyinit = $_POST["qntyinit"];
 $stockname = $_POST["stockname"];
 $price = $_POST["price"];
 $qntysold = $_POST["qntysold"];
 $totalcost = $_POST["totalcost"];
 $deposit = $_POST["deposit"];
 $balance = $_POST["balance"];
 $soldby = $_POST["soldby"];
 $location = $_POST["location"];
 $paid = $_POST["paid"];
 $soldto = $_POST["soldto"];
 $soldname = $_POST["soldname"];
 $enteredby = $_POST["enteredby"];
 $discount = $_POST["discount"];
 $trans = $_POST["trans"];

if ($_POST['sdate'] !='0000-00-00' and $_POST['sdate'] !='')
{
 $rsdate = $_POST['sdate'];
 list($dayy, $monthh, $yearr) = explode('-', $rsdate);
 $sdate = $yearr . '-' . $monthh . '-' . $dayy;
}
 require_once 'conn.php';
 if (isset($_POST['submit']))
 { 
 switch ($_POST['submit'])
   {
     case 'Return':
      if (Trim($cod) != "")
      { 
        $query_update = "UPDATE `stock` SET `Units in Stock` = (`Units in Stock` + " . $qntysold . ") WHERE `Stock Code`='$cod'";
        $result_update = mysql_query($query_update);

        $totcost=$price*$qntysold;
        $bal=($price*$qntysold)-$discount; 
/*        $query_insert = "Insert into sales (`Stock Code`,`Stock Name`,`Sales Date`,`Unit Cost`,`Location`,`Total Cost`
          ,`Qnty Sold`,`Discount`,`Deposit`,`Sold By`,`Balance`,`Paid`,`Sold To`,`Sold Name`,`Entered By`,`cust_id`) 
               VALUES ('$cod','$stockname','$sdate','$price','$location','$totcost'
          ,'$qntysold','$discount','$deposit','$soldby','$bal','$paid','$soldto','$soldname','$enteredby','" . $_SESSION['cust_token'] . "')";

        $result_insert = mysql_query($query_insert);
*/
      if ($paid=='Cheque')
      {
        $query_cheq = "Insert into `cheque` (`Type`,`Date`,`Bank`,`Cheque No`,`Amount`,`Particulars`) 
               VALUES ('Expenditure','$sdate','$bank','$chqno','$bal','Sales')";
        $result_cheq = mysql_query($query_cheq);
      }
      if ($paid=='Credit')
      {
        $query_crd = "Insert into `debtor` (`Company`,`Date`,`Contact Person`,`Amount`) 
               VALUES ('$soldto','$sdate','$soldname','$bal')";
        $result_crd = mysql_query($query_crd);
      }
      if ($paid=='Cash')
      {
        $query_crd = "Insert into `cash` (`Type`,`Date`,`Classification`,`Particulars`,`Amount`,`Source`) 
               VALUES ('Expenditure','$sdate','Sales Return','Sales Return with id $trans','$bal','cash')";
        $result_crd = mysql_query($query_crd);
      }
            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn);
            $rows = mysql_fetch_array($result);

            $query_insert_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Sales Record','Added Sales Record for Stock: " . $cod . ", " . $stockname . "')";

            $result_insert_Log = mysql_query($query_insert_Log);
            ###### 

        $tval="Your record has been saved.";
        header("location:return.php?code=$cod&yourText=$trans&tval=$tval&redirect=$redirect");
      }
      else
      {
        $tval="Please enter Stock Code before saving.";
        header("location:return.php?id=$id&code=$cod&yourText=$trans&tval=$tval&redirect=$redirect");
      }

      break;
################################

   }
 }
?>