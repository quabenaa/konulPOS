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
	$valexp="date(`Sales Date`) = '" . date('Y-m-d', strtotime($date)) . "'";	
	$query="SELECT `ID`,`Sales Date`,`Stock Name`,`Stock Code`,`Qnty Sold`,`Unit Cost`,`Deposit`,`Discount`,`Paid` FROM sales WHERE ".$valexp."order by `Sales Date` desc";
	$sql_tt = "SELECT SUM(`Total Cost`) AS tcost, SUM(`Deposit`) AS udept, SUM(`Discount`) AS udct,  SUM(`Qnty Sold`) AS tqnty, SUM(`Balance`) as 		bal FROM sales WHERE ".$valexp."";
	
		
}else{
	$date = date('Y-m-d');
	$query="SELECT `ID`,`Sales Date`,`Stock Name`,`Stock Code`,`Qnty Sold`,`Unit Cost`,`Deposit`,`Discount`,`Paid` FROM sales WHERE `Sales Date`='$date'";
	$sql_tt = "SELECT SUM(`Total Cost`) AS tcost, SUM(`Deposit`) AS udept, SUM(`Discount`) AS udct,  SUM(`Qnty Sold`) AS tqnty, SUM(`Balance`) as bal FROM sales WHERE `Sales Date`='$date'";
}
$result  = mysql_query($query) or die(mysql_error()); 

 
 @$filename = "sales_" . date('Ymd',strtotime($date)) . ".xls";
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
   echo "<h2><center>SALES REPORT</center></h2> ";
  echo "<font style='font-size: 9pt'><b>Date: " . date('d/m/Y') . "</b></font>&nbsp; &nbsp &nbsp; &nbsp &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp";
  echo "
  <TR bgcolor='#C0C0C0'>
  	  <TH ><small>S/No</small></TH><TH ><small>Sales Date</small></TH>
	  <TH ><small>Receipt No</small></TH><TH ><small>Stock Code</small></TH>
	  <TH ><small>Stock Name</small></TH><TH ><small>Qnty Sold</small></TH>
	  <TH ><small>Sales Price</small></TH><TH ><small>Discount</small></TH>
	  <TH ><small>Total Sales</small></TH><TH ><small>Deposit Amount</small></TH>
	  <TH ><small>Balance</small></TH><TH ><small>Payment </small></TH>
  </TR>";

	$n=0;
	while(list($id,$date,$name,$code,$qnty,$price,$deposit,$discount,$paid)=mysql_fetch_row($result))
    {
	 $total=number_format(($qnty*$price)- $discount,2);
     $bal= number_format(($total-$deposit),2);
     $qnty=number_format($qnty,0);
     $price=number_format($price,2);
     $deposit=number_format($deposit,2);
     $discount=number_format($discount,2);

     $n=$n+1;
 echo "<TR>
	  	 <TD ><small>$n</small></TD><TD ><small>" . date('d-m-Y',strtotime($date)) . "</small></TD>
		 <TD ><small>$id</small></TD><TD ><small>$code</small></TD>
		 <TD ><small>$name</small></TD><TD ><small>$qnty</small></TD>
		 <TD ><small>$price</small></TD><TD ><small>$discount</small></TD>
		 <TD ><small>$total</small></TD><TD ><small>$deposit</small></TD>
		 <TD ><small>$bal</small></TD><TD ><small>$paid </small></TD>
	  </TR>";
	}

   
  $result_tt = mysql_query($sql_tt,$conn) or die('Could not list value; ' . mysql_error());
  $row = mysql_fetch_array($result_tt);
  $tcost=number_format($row['tcost'],2);
  $udept=number_format($row['udept'],2);
  $udct=number_format($row['udct'],2);
  $bal=number_format($row['tcost']-$row['udept'],2);
  $tqnty=$row['tqnty'];
   echo "<TR><TH colspan='12'>&nbsp;</TH></TR>";
   echo "<TR>
   <TH bgcolor='#EAEAEA' colspan='5'><font style='font-size: 8pt'><b>Total</b>&nbsp;</font></TH>
   <TH bgcolor='#EAEAEA'><font style='font-size: 8pt'>$tqnty</font></TH>
   <TH bgcolor='#EAEAEA'></TH>   
   <TH bgcolor='#EAEAEA' align='center'><font style='font-size: 8pt'><b>$udct</b></font></TH>
   <TH bgcolor='#EAEAEA'><font style='font-size: 8pt'>$tcost&nbsp;</font></TH>
   <TH bgcolor='#EAEAEA'><font style='font-size: 8pt'>$udept</font></TH>
   <TH bgcolor='#EAEAEA'><font style='font-size: 8pt'>$bal</font></TH>  
   <TH bgcolor='#EAEAEA'><font style='font-size: 8pt'></font></TH>   
   </TR><p>";
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
