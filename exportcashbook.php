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
	$valexp="date(`Date`) = '" . date('Y-m-d', strtotime($date)) . "'";	
	$query="SELECT `ID`,`Date`,Type,Classification,Amount,`Particulars` FROM cash WHERE ".$valexp."order by `Date` desc";
	$sql_tt = "SELECT SUM(`Amount`) as amt From `cash` WHERE ".$valexp." "; 
		
}else{
	$date = date('Y-m-d');
	$query="SELECT `ID`,`Date`,Type,Classification,Amount,`Particulars` FROM cash WHERE `Date` ='$date' ORDER BY `Date` desc";
	$sql_tt = "SELECT SUM(`Amount`) as amt From `cash` WHERE `Date` ='$date'";
}
 $result  = mysql_query($query) or die('Could not list value; ' . mysql_error());
 $result_tt = mysql_query($sql_tt,$conn) or die('Could not list value; ' . mysql_error()); 

 @$filename = "cashbook_" . date('Ymd') . $filter . ".xls";
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
   echo "<h2><center>DAILY CASHBOOK</center></h2> ";

  echo "<font style='font-size: 9pt'><b>Date: " . date('d/m/Y') . "</b></font>&nbsp; &nbsp &nbsp; &nbsp &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp";
  echo "
  <TR>
  <TH bgcolor='#C0C0C0' width='5%'> <font style='font-size: 9pt'>No </font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='15%'> <font style='font-size: 9pt'>Date </font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='15%'><font style='font-size: 9pt'>Type</font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='15%'><font style='font-size: 9pt'>Classification</font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='15%'><font style='font-size: 9pt'>Amount</font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='35%'><font style='font-size: 9pt'>Particulars</font>&nbsp;</TH>
  </TR>";

	$n=0;
	while(list($id,$date,$type,$classification,$amount,$particulars)=mysql_fetch_row($result))
	{
		$n++;
     $amount=number_format($amount,2);
      echo "<TR>
	  <TH width='5%'> <font style='font-size: 8pt'>$n</font>&nbsp;</TH>
	  <TH width='15%'> <font style='font-size: 8pt'>$date </font>&nbsp;</TH>
	  <TH width='15%'><font style='font-size: 8pt'>$type </font>&nbsp;</TH>
	  <TH width='15%'><font style='font-size: 8pt'>$classification</font>&nbsp;</TH>
	  <TH width='15%'><font style='font-size: 8pt'>$amount</font>&nbsp;</TH>
	  <TH width='35%'><font style='font-size: 8pt'>$particulars</font>&nbsp;</TH>
	  </TR>";
   }

  
  $row = mysql_fetch_array($result_tt);
  $nt=$row['amt'];
  $nt=number_format($nt,2);
   echo "<TR><TH>&nbsp;</TH></TR>";
   echo "<TR>
   <TH bgcolor='#EAEAEA'><font style='font-size: 8pt'>&nbsp;</font></TH>
   <TH bgcolor='#EAEAEA'><font style='font-size: 8pt'>&nbsp;</font></TH>
   <TH bgcolor='#EAEAEA'><font style='font-size: 8pt'>&nbsp;</font></TH>
   <TH bgcolor='#EAEAEA'><font style='font-size: 8pt'><b>Total</b></font></TH>
   <TH bgcolor='#EAEAEA' align='center'><font style='font-size: 8pt'><b>$nt</b></font></TH>   
   <TH bgcolor='#EAEAEA'><font style='font-size: 8pt'>&nbsp;</font></TH>
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
