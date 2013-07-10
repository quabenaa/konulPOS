<?php
session_start();
require_once 'conn.php';

if (isset($_GET['data'])) {

	if($_GET['data']=='dlysl'){	   
		
		$queryy="SELECT SUM((`Qnty Sold`*`Unit Cost`)-`Discount`) as balsum FROM sales WHERE `Sales Date`='".date('Y-m-d',time())."'";
		$resultt=mysql_query($queryy);
	
		 $roww = mysql_fetch_array($resultt);
		 
		 $balsum= number_format($roww['balsum'],2);
	
		 echo "GH&cent; ".$balsum;
		 
	}else if($_GET['data']=='dlypft'){
		
		 $query="SELECT `ID`,`Sales Date`,`Stock Name`,`Stock Code`,`Qnty Sold` FROM sales WHERE `Sales Date`='".date('Y-m-d',time())."'";
		 $result=mysql_query($query);
		 $i=0; $sumuprofit=0; $sumtprofit=0;$sumqnty=0;$sumTcost =0; $sumTsales = 0;$sumUcost = 0; $sumSprice = 0;
		 while(list($id,$date,$name,$code,$qnty)=mysql_fetch_row($result))
		 {
		   $queryR="SELECT `ID`,`Stock Code`,`Unit Cost`,`Selling Price` FROM `stock` WHERE `Stock Code`='" . $code . "'";
		   $resultR=mysql_query($queryR);
		   $rowr = mysql_fetch_array($resultR);
	
		   $ucost=$rowr['Unit Cost']; $sprice=$rowr['Selling Price'];
	
		   $totalc=$qnty*$ucost;
		   $totals=$qnty*$sprice;
		   $tprofit=$totals-$totalc;
		   $totalprofit=number_format($tprofit,2);
		   $sumtprofit += $totalprofit;
	
		 }	
		 echo   "GH&cent; ".$sumtprofit;
	}else if($_GET['data']=='proexp'){
		
		 $valexp="date(`Expiry Date`) < '" . date('Y-m-d', strtotime('+2 month')) . "'";
		 $query="SELECT COUNT(`ID`) AS total FROM stock WHERE " . $valexp . "";
		 $result=mysql_query($query);
		 $row = mysql_fetch_array($result);
		 $total = $row['total'];
		 echo   $total.' - <a class="white" href="stocklist.php?exp=1">View List</a>';
	 
	}else if($_GET['data']=='relvl'){
		
		 $query="SELECT COUNT(`ID`) AS total FROM stock WHERE (`Reorder Level` > `Units in Stock` OR `Reorder Level` = `Units in Stock`)";
		 $result=mysql_query($query);
		 $row = mysql_fetch_array($result);
		 $total = $row['total'];
		 echo   $total.' - <a class="white" href="stocklist.php?relvl=1">View List</a>';
	 
	}else if($_GET['data']=='user'){
		
		 $val="`Sales Date`='" . date('Y-m-d') . "'";
		$query="
		SELECT distinct(`Entered By`) as staff,`Sales Date` 
	   		FROM sales  WHERE ". $val . " group by `Entered By`";
   		$result=mysql_query($query);
		$row = mysql_num_rows($result);
		$total = $row;
		echo   $total;
	 
	}else {
		 echo '0'; 
	}
}else {
		 echo '0'; 
}
?>