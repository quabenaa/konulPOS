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


if(isset($_REQUEST['date']) || !empty($_REQUEST['date'])){
	//list($sdate, $edate) = explode("-", $_REQUEST['date']);
	$date = $_REQUEST['date'];
	$val="date(`Sales Date`) = '" . date('Y-m-d', strtotime($date)) . "'";		
	$queryM="SELECT distinct(`Entered By`) as staff, `ID`,`Sales Date` FROM sales  WHERE ". $val . " group by `Entered By`";
		
}else{
	$date = date('Y-m-d');
	$val="date(`Sales Date`) = '" . date('Y-m-d', strtotime($date)) . "'";		
	$queryM="SELECT distinct(`Entered By`) as staff, `ID`,`Sales Date` FROM sales  WHERE ". $val . " group by `Entered By`";
}
 $resultM=mysql_query($queryM) or die(mysql_error());

 
 $filename = "individualsales_" . date('Ymd',strtotime($date)) . ".xls";
 header("Content-Disposition: attachment; filename=\"$filename\"");
 header("Content-Type: application/vnd.ms-excel"); 

?>
   <div align="center">
	<table border="0" width="100%" id="table1" bgcolor="#FFFFFF">
		<tr>
			<td>
			<div align="center">
				<table border="0" width="100%" id="table2">
					<tr>
						<td>

<TABLE width='80%' border='0' cellpadding='1' cellspacing='1' align='center' id="table3">
<table border="1" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="100%" id="AutoNumber2">
<?php
  echo "<h1><center>TEST SUPERMARKET</center></h1> ";
   echo "<h2><center>INDIVIDUALS' SALES REPORT</center></h2> ";
  echo "<font style='font-size: 9pt'><b>Date: " . date('d-m-Y',strtotime($date)) . "</b></font>&nbsp; &nbsp &nbsp; &nbsp &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp";

   echo "
   <thead>
	 <TR bgcolor='#C0C0C0'>
	 <TH>S/No &nbsp;</TH>
	 <TH>Stock Name</TH>
	 <TH>Qnty Sold</TH>
     <TH>Unit Price</TH>
	 <TH>Total Sales</TH>
	 <TH>Discount</TH>
	 <TH>Sales Amount</TH>
	 </TR>
	 </thead> <tbody>";
	if(mysql_num_rows($resultM) > 0){
   while(list($staf, $idd,$datt)=mysql_fetch_row($resultM))
   {     
	 echo "<TR>
	   <TD colspan='7'>
	   <b><font color='#1BCD50' style='font-size: 10pt'>Today's Sales By " . strtoupper($staf) . "</font></b>
	   </TD>
	   </TR>";	  
	 
     $query="
	 SELECT distinct(`Stock Name`) as stock, `ID`,`Sales Date` FROM sales  WHERE `Entered By` ='".$staf."' AND `Sales Date`='" . $datt . "'group by `Stock Name`";
     
	 $results=mysql_query($query) or die(mysql_error());
     $i=0;
     while(list($name,$id,$date)=mysql_fetch_row($results))
     {
	$query="
	 SELECT `ID`,`Sales Date`,`Stock Name`,`Stock Code`,SUM(`Qnty Sold`) AS qnty, `Unit Cost`, SUM(`Deposit`) AS depot,SUM(`Discount`) AS disc,`Paid` 
	 FROM sales
	 WHERE `Stock Name`='$name' AND `Entered By` ='$staf' and `Sales Date`='" . $datt . "'";
	 $result=mysql_query($query) or die(mysql_error());
	 
	 list($id,$date,$name,$code,$qnty,$price,$deposit,$discount,$paid)=mysql_fetch_row($result);

       $total=number_format(($qnty*$price),2);
       $bal= number_format((($qnty*$price)-$deposit-$discount),2);
       $qnty=number_format($qnty,0);
       $price=number_format($price,2);
       $deposit=number_format($deposit,2);
       $discount=number_format($discount,2);
 
       $i=$i+1;
       echo "<TR>
	   <TD>$i</TD>
	   <TD>$name</TD>
	   <TD>$qnty</TD>
	   <TD>$price</TD>
	   <TD>$total</TD>
       <TD>$discount</TD>
	   <TD>$bal</TD>
	   </TR>";
     }
     $queryy="SELECT sum(`Qnty Sold`) as Qnty,sum(`Deposit`) as Deposit,sum(`Discount`) as Discount,sum((`Qnty Sold`*`Unit Cost`)) as Totsum,sum((`Qnty Sold`*`Unit Cost`)-`Discount`) as balsum FROM sales WHERE `Entered By` ='$staf' and `Sales Date`='" . $datt . "'";
     $resultt=mysql_query($queryy);
     $roww = mysql_fetch_array($resultt);
     $totsum=number_format($roww['Totsum'],2);
     $balsum= number_format($roww['balsum'],2);
     $qqnty=number_format($roww['Qnty'],0);
     $ddeposit=number_format($roww['Deposit'],2);
     $ddiscount=number_format($roww['Discount'],2);
     echo "<TR bgcolor='#DFDFDF'>
	 <TH>&nbsp;</TH>
	 <TH><font color='#006FDD'>TOTAL SALES</font></TH>
	 <TH><font color='#006FDD'>$qqnty</font></TH>
     <TH>&nbsp;</TH>
	 <TH><font color='#006FDD'>$totsum</font></TH>
	 <TH><font color='#006FDD'>$ddiscount</font></TH>
	 <TH><font color='#006FDD'>$balsum</font></TH>
	 </TR> "; 
	 echo '</tbody>';
   }
	}else{
	echo '<TR><TH colspan="7">No Record available in table</TH>';	
	}
?>
</table>
</table>

<br>
</td>
					</tr>
		
				</table>
			</div>
			</td>
		</tr>
