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


if(isset($_POST['total']) && !empty($_POST['total'])){$total = $_POST['total'];}else{$total =0.00;}

if(isset($_POST['deposit']) && !empty($_POST['deposit'])){$deposit = $_POST['deposit'];}else{$deposit =0.00;}

if(isset($_POST['id']) && !empty($_POST['id'])){$id = $_POST['id'];}else{$id ='';}

if(isset($_POST["idd"]) && !empty($_POST["idd"])){$idd = $_POST["idd"];}else{$idd ='';}
 
if(isset($_POST["cod"]) && !empty($_POST["cod"])){$cod = $_POST["cod"];}else{$cod ='';}
 
if(isset($_POST["code"]) && !empty($_POST["code"])){$code = $_POST["code"];}else{$code ='';}

if(isset($_POST["qntyinit"]) && !empty($_POST["qntyinit"])){$qntyinit = $_POST["qntyinit"];}else{$qntyinit ='';}
 
if(isset($_POST["stockname"]) && !empty($_POST["stockname"])){$stockname = $_POST["stockname"];}else{$stockname ='';}
 
if(isset($_POST["price"]) && !empty($_POST["price"])){$price = $_POST["price"];}else{$price =0.00;}
 
if(isset($_POST["qntysold"]) && !empty($_POST["qntysold"])){$qntysold = $_POST["qntysold"];}else{$qntysold ='';}

if(isset($_POST["totalcost"]) && !empty($_POST["totalcost"])){$totalcost = $_POST["totalcost"];}else{$totalcost =0.00;}
 
if(isset($_POST["balance"]) && !empty($_POST["balance"])){$balance = $_POST["balance"];}else{$balance =0.00;}

if(isset($_POST["soldby"]) && !empty($_POST["soldby"])){$soldby = $_POST["soldby"];}else{$soldby ='';}

if(isset($_POST["location"]) && !empty($_POST["location"])){$location = $_POST["location"];}else{$location ='';}

if(isset($_POST["payment"]) && !empty($_POST["payment"])){$paid = $_POST["payment"];}else{$paid ='cash';}

if(isset($_POST["creditorsname"]) && !empty($_POST["creditorsname"])){$soldto = $_POST["creditorsname"];}else{$soldto ='';}

if(isset($_POST["creditorsname"]) && !empty($_POST["creditorsname"])){$soldname = $_POST["creditorsname"];}else{$soldname ='';}

if(isset($_POST["enteredby"]) && !empty($_POST["enteredby"])){$enteredby = $_POST["enteredby"];}else{$enteredby ='';}
 
if(isset($_POST["discount"]) && !empty($_POST["discount"])){$discount = $_POST["discount"];}else{$discount =0.00;}
 


$sdate = date("Y-m-d",time());
 
 if (isset($_POST['submit']))
 { 
 switch ($_POST['submit'])
   {
	 ################################################## UPDATE SALES ITEM ###################################################################
     case 'Update':
      if (trim($cod) != "")
      {
        $query_up = mysql_query("SELECT * FROM `stock` WHERE `Stock Code`='$cod'");
		$res_val = mysql_fetch_array($query_up);
		$UC = $res_val['Selling Price'];
		$new_Sval = ($qntysold * $UC)-$discount;
		$tot_val = $new_Sval;

        $query_update = "UPDATE `tempsales` SET `Stock Code` = '$cod',`Stock Name`='$stockname',
          `Sales Date`='$sdate',`Unit Cost`='$UC',`Location`='$location',
          `Qnty Sold`='$qntysold',`Discount`='$discount',`Deposit`='$deposit',`Sold By`='$soldby',
          `Paid`='$paid',`Sold To`='$soldto',`Sold Name`='$soldname',`Entered By`='$enteredby',`Total Cost`='$tot_val' WHERE `ID` = '$id'";

        $result_update = mysql_query($query_update);

            ##################### MONITOR #####################
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn);
            $rows = mysql_fetch_array($result);

            $query_update_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Sales Record','Modified Sales Record for Stock: " . $cod . ", " . $stockname . "')";

            $result_update_Log = mysql_query($query_update_Log);
            ##################### END MONITOR ##################### 

        $tval="Your record has been updated.";
        header("location:sales.php?cod=$cod&tval=$tval&redirect=$redirect");
      }
      else
      {
        $tval="Please enter Stock Code before updating.";
        header("location:sales.php?id=$id&cod=$cod&tval=$tval&redirect=$redirect");
      }
      break;
	  
     case 'Suspend':
      { 
        $query_insert = "Insert into `suspend` (`Stock Code`,`Stock Name`,`Sales Date`,`Unit Cost`,`Location`,`Total Cost`
          ,`Qnty Sold`,`Discount`,`Deposit`,`Sold By`,`Balance`,`Paid`,`Sold To`,`Sold Name`,`Entered By`,`cust_id`) 
          select `Stock Code`,`Stock Name`,`Sales Date`,`Unit Cost`,`Location`,`Total Cost`
          ,`Qnty Sold`,`Discount`,`Deposit`,`Sold By`,`Balance`,`Paid`,`Sold To`,`Sold Name`,`Entered By`,`cust_id`  from `tempsales`  WHERE `Entered By` ='" . strtoupper($_SESSION['name']) . "' and `Sales Date`='" . date('Y-m-d') . "' and `cust_id` = '" . $_SESSION['cust_token'] . "'";
        $result_insert = mysql_query($query_insert);

            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn);
            $rows = mysql_fetch_array($result);

            $query_insert_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Sales Record','Added Sales Record for Stock: " . $cod . ", " . $stockname . "')";

            $result_insert_Log = mysql_query($query_insert_Log);
            ###### 

        $tval="Sales Suspended!";
        $_SESSION['cust_token'] = time();
        header("location:sales.php?tval=$tval");
      }
      break;

     case 'Resume':
      { 
       $sqlP="SELECT * FROM `suspend` WHERE `Entered By` ='" . strtoupper($_SESSION['name']) . "' and `Sales Date`='" . date('Y-m-d') . "'";
       $resultP = mysql_query($sqlP,$conn) or die('Could not look up user data; ' . mysql_error());
       $rowP = mysql_fetch_array($resultP);
       $ccode=$rowP['Stock Code'];
       $ccustid=$rowP['cust_id'];

       $query_delete = "DELETE FROM `suspend` WHERE `Entered By` ='" . strtoupper($_SESSION['name']) . "' and `Sales Date`='" . date('Y-m-d') . "'";
       $result_delete = mysql_query($query_delete);          

            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn);
            $rows = mysql_fetch_array($result);

            $query_insert_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Sales Record','Added Sales Record for Stock: " . $cod . ", " . $stockname . "')";

            $result_insert_Log = mysql_query($query_insert_Log);
            ###### 

        $tval="Sales Resumed!";
        $_SESSION['cust_token'] = $ccustid;
        header("location:sales.php?code=$ccode&tval=$tval&redirect=$redirect");
      }
      break;
	  
	################################################## START NEW SALES ###################################################################
     case 'New Sales': 

	$query_dstup = mysql_query("
	DELETE FROM `tempsales` 
		WHERE `Entered By` ='" . strtoupper($_SESSION['name']) ."' 
	AND `Sales Date`='" . date('Y-m-d') . "' 
	AND `cust_id` = '" . $_SESSION['cust_token'] . "' 
	") or die(mysql_error());
	
    $_SESSION['cust_token'] = time();

    header("location:sales.php");
    break;
	  
	################################################## COMPLETE SALES ###################################################################
    
	 case 'Complete Sales':
      if ($_SESSION['cust_token'])
      {  
	 ################### CUSTOMER ENQURY ###################  
	   if ($paid=='credit')
      {
		  if(empty($soldto)){
			$val = "Please Enter Customer's Name";
			header("Location:sales.php?cVal=$val");
			die(); 
		  }
		list($cname, $custno) = explode(" - ", $soldto);
		 
	  	$customer = "SELECT * 
					FROM  `customers` WHERE name = '$cname' AND cust_numb = '$custno'";
		$result_cust = mysql_query($customer)or die(mysql_error());
		if(mysql_num_rows($result_cust)>0)
		{
		$bal=-$_REQUEST['t2'];
		$rowc = mysql_fetch_array($result_cust, MYSQL_ASSOC) or die(mysql_error());
		
			if(($rowc['creditlimit'] <= $rowc['outstandingpayment']) || ($rowc['creditbalance'] <= 0) || ( $rowc['creditbalance'] - $bal < 0)){
				$val = "$soldto's Credit Limit Exceeded. <br />
					Current Credit Balance = GHS ".$rowc['creditbalance'];
				header("Location:sales.php?cVal=$val"); 
				die();
			}else{
		echo $query = "
		UPDATE `customers` 
			SET `creditbalance`=(creditbalance - $bal),`outstandingpayment`=(outstandingpayment + $bal)
		WHERE name = '$cname' AND cust_numb = '$custno'";
		$resulta = mysql_query($query)or die(mysql_error());
			}
		}else{
			
			$val = "Customer $soldto Does not Exist";
			header("Location:sales.php?cVal=$val");
			die();
		}

	  }	
	  ################### END CUSTOMER ENQURY ###################	  
	  
	  mysql_query("UPDATE `tempsales`SET `Paid` = '$paid Sales' WHERE `Entered By` ='" . strtoupper($_SESSION['name']) . "' 
		AND `Sales Date`='" . date('Y-m-d') . "' AND `cust_id` = '" . $_SESSION['cust_token'] . "'
		") or die(mysql_error());
		
        $query_insert = "INSERT INTO sales 
		(`Stock Code`,`Stock Name`,`Sales Date`,`Unit Cost`,`Location`,`Total Cost`,`Qnty Sold`,`Discount`,`Deposit`,`Sold By`, `Balance`, `Paid`, `Sold To`, `Sold Name`,`Entered By`,`cust_id`) 
          SELECT 
		  `Stock Code`,`Stock Name`,`Sales Date`,`Unit Cost`,`Location`,`Total Cost`,`Qnty Sold`,`Discount`,`Deposit`,`Sold By`, `Balance`, `Paid`, `Sold To`, `Sold Name`, `Entered By`,`cust_id` 
			 FROM `tempsales` 
		 WHERE `Entered By` ='" . strtoupper($_SESSION['name']) . "' 
			 AND `Sales Date`='" . date('Y-m-d') . "' 
			 AND `cust_id` = '" . $_SESSION['cust_token'] . "'";

        $result_insert = mysql_query($query_insert) or die(mysql_error());
		
		################### STOCK UPDATE ###################
        $query_stup = "SELECT `Stock Code`,`Stock Name`,`Sales Date`,`Balance`,`Qnty Sold`,`cust_id`  
		FROM `tempsales`
          WHERE `Entered By` ='" . strtoupper($_SESSION['name']) . "' 
		 AND `Sales Date`='" . date('Y-m-d') . "' 
		 AND `cust_id` = '" . $_SESSION['cust_token'] . "'";
         $result_stup = mysql_query($query_stup)or die(mysql_error());
		 
       while(list($stcd,$stnam,$stdat,$abl,$qsold,$cdd)=mysql_fetch_row($result_stup))
       {
        $query_update = "UPDATE `stock` SET `Units in Stock` = (`Units in Stock` - " . $qsold . "),`stocksold` = (`stocksold` + " .$qsold . ") WHERE `Stock Code`='$stcd'";
        $result_update = mysql_query($query_update);
       }
	   ################### END STOCK UPDATE ###################
	   
	   
	  ################### IF CREDIT SALES ################### 		 
		
      if ($paid=='credit')
      { 
	  	$bal=-$_REQUEST['t2']; 
        $query_crd = "Insert into `debtor` (`Company`,`Date`,`Contact Person`,`Amount`,`Balance`) 
               VALUES ('$soldto','$sdate','$soldname','$bal','$bal')";  
        $result_crd = mysql_query($query_crd) or die(mysql_error());
		
		$query_csh = "select `Stock Code`,`Stock Name`,`Sales Date`,`Balance`,`Sold To`,`Sold Name`,`cust_id`  from `tempsales`
          WHERE `Entered By` ='" . strtoupper($_SESSION['name']) . "' and `Sales Date`='" . date('Y-m-d') . "' and `cust_id` = '" . $_SESSION['cust_token'] . "'";
         $result_csh = mysql_query($query_csh) or die(mysql_error());
       while(list($stcod,$stname,$stdate,$abal,$stto,$buyn,$cid)=mysql_fetch_row($result_csh))
       {
        $pat= $stname . " Credit Sales to Receipt #" . $_SESSION['cust_token'] . " For " . $soldto ." by ".strtoupper($_SESSION['name']);
        $query_crd = "INSERT INTO `cash` (`Type`,`Date`,`Classification`,`Particulars`,`Amount`,`Source`) 
               VALUES ('Income','$stdate','Credit Sales','$pat','$abal','cash')";
        $result_crd = mysql_query($query_crd) or die(mysql_error());
       }
		
		$url="reciept.php?credit=$soldto";
		
      }
	################### END CREDIT SALES ###################  
	  
	  
	################### IF CASH SALES ###################
      if ($paid=='cash')
      { 
	  $query_csh = "select `Stock Code`,`Stock Name`,`Sales Date`,`Balance`,`Sold To`,`Sold Name`,`cust_id`  from `tempsales`
          WHERE `Entered By` ='" . strtoupper($_SESSION['name']) . "' and `Sales Date`='" . date('Y-m-d') . "' and `cust_id` = '" . $_SESSION['cust_token'] . "'";
         $result_csh = mysql_query($query_csh) or die(mysql_error());
       while(list($stcod,$stname,$stdate,$abal,$stto,$buyn,$cid)=mysql_fetch_row($result_csh))
       {
        $pat= $stname . " Cash Sales to Receipt #" . $_SESSION['cust_token'] . " by " . strtoupper($_SESSION['name']);
        $query_crd = "INSERT INTO `cash` (`Type`,`Date`,`Classification`,`Particulars`,`Amount`,`Source`) 
               VALUES ('Income','$stdate','Cash Sales','$pat','$abal','cash')";
        $result_crd = mysql_query($query_crd) or die(mysql_error());
       }
	   $url="reciept.php?cash=$deposit&change=$total&redirect=$redirect";
      } 
	 ################### END CASH SALES ###################
	  
	  		##################### MONITOR #####################
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn);
            $rows = mysql_fetch_array($result);

            $query_update_Log = "Insert into `monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Sales Record', 'Complete $paid Sales Transaction for Receipt #: " . $_SESSION['cust_token'] . "')";

            $result_update_Log = mysql_query($query_update_Log);
           ##################### END MONITOR #####################
	  
        echo $tval=$handle;
        header("location:".$url);
      }
      break;
	  
	  
	############################################# DELETE SALES ITEM ################################################################

     case 'Delete':
      {
       $query_delete = "DELETE FROM `tempsales` WHERE `ID` = '$id';";

       $result_delete = mysql_query($query_delete);          

            ##################### MONITOR #####################
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn);
            $rows = mysql_fetch_array($result);

            $query_delete_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Sales Record','Deleted Sales Record for Stock: " . $cod . ", " . $stockname . "')";

            $result_delete_Log = mysql_query($query_delete_Log);
            ##################### END MONITOR #####################

        $tval="Your record has been deleted.";
        header("location:sales.php?cod=$cod&tval=$tval&redirect=$redirect");
      }
      break;     
   }
 }

/*
case 'Save Only':
      if (trim($cod) != "")
      { 
        $totcost=('$price'*'$qntysold');
        $bal=('$price'*'$qntysold')-'$deposit'-$discount; 
        $query_insert = "Insert into sales (`Stock Code`,`Stock Name`,`Sales Date`,`Unit Cost`,`Location`,`Total Cost`
          ,`Qnty Sold`,`Discount`,`Deposit`,`Sold By`,`Balance`,`Paid`,`Sold To`,`Sold Name`,`Entered By`) 
               VALUES ('$cod','$stockname','$sdate','$price','$location',('$price'*'$qntysold')
          ,'$qntysold','$discount','$deposit','$soldby',('$price'*'$qntysold')-'$deposit'-'$discount','$paid','$soldto','$soldname','$enteredby')";

        $result_insert = mysql_query($query_insert);

            #######
            $sql = "SELECT * FROM cms_access_levels Where access_lvl='" . $_SESSION['access_lvl'] ."'";
            $result = mysql_query($sql,$conn);
            $rows = mysql_fetch_array($result);

            $query_insert_Log = "Insert into `Monitor` (`User Category`, `User Name`,`Date Logged on`, `Time Logged on`,`File Used`,`Details`) 
                  VALUES ('" . $rows['access_name'] . "','" . ucfirst($_SESSION['name']) . "', '" . date('Y/m/d') . "', '" . date('h:i A') . "','Sales Record','Added Sales Record for Stock: " . $cod . ", " . $stockname . "')";

            $result_insert_Log = mysql_query($query_insert_Log);
            ###### 

        $tval="Your record has been saved.";
        header("location:sales.php?code=$cod&tval=$tval&redirect=$redirect");
      }
      else
      {
        $tval="Please enter Stock Code before saving.";
        header("location:sales.php?id=$id&code=$cod&tval=$tval&redirect=$redirect");
      }
      break;
*/