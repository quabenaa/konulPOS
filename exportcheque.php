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


@$filename = "restockreport_" . date('Ymd') . $filter . ".xls";
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
  echo "<h2><center>CHEQUE REPORT</center></h2> ";

if (empty($filter))
{
  $result = mysql_query ("SELECT `ID`,`Date`,Type,Bank,Amount,`Particulars`,`Cheque No` FROM cheque order by `Date` Desc"); 
} else {
  $result = mysql_query ("SELECT `ID`,`Date`,Type,Bank,Amount,`Particulars`,`Cheque No` FROM cheque order by `Date` Desc"); 
}
  echo "<font style='font-size: 9pt'><b>Date: " . date('d/m/Y') . "</b></font>&nbsp; &nbsp &nbsp; &nbsp &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp";
  echo "
  <TR>
  <TH bgcolor='#C0C0C0' width='5%'> <font style='font-size: 9pt'>CHEQUE NO </font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='15%'> <font style='font-size: 9pt'>DATE </font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='15%'><font style='font-size: 9pt'>TYPE</font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='15%'><font style='font-size: 9pt'>BANK</font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='15%'><font style='font-size: 9pt'>AMOUNT</font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='25%'><font style='font-size: 9pt'>PARTICULARS</font>&nbsp;</TH>
  </TR>";

	$n=0;
	while(list($id,$date,$type,$bank,$amount,$particulars,$cheque)=mysql_fetch_row($result))
	{
	 $n++;
      echo "<TR>
	  <TH width='5%'> <font style='font-size: 8pt'>$cheque</font>&nbsp;</TH>
	  <TH width='15%'> <font style='font-size: 8pt'>$date </font>&nbsp;</TH>
	  <TH width='15%'><font style='font-size: 8pt'>$type </font>&nbsp;</TH>
	  <TH width='15%'><font style='font-size: 8pt'>$bank</font>&nbsp;</TH>
	  <TH width='35%'><font style='font-size: 8pt'>$amount</font>&nbsp;</TH>
	  <TH width='35%'><font style='font-size: 8pt'>$particulars</font>&nbsp;</TH>
	  </TR>";
   }

 $sql_tt = "SELECT SUM(`Amount`) as amt From `cheque`"; 
  $result_tt = mysql_query($sql_tt,$conn) or die('Could not list value; ' . mysql_error());
  $row = mysql_fetch_array($result_tt);
  $nt=$row['amt'];
   echo "<TR><TH colspan='6'>&nbsp;</TH></TR>";
   echo "
   <TR>
   <TH colspan='4' align='right'><b>TOTAL AMOUNT :</b></TH>
   <TH colspan='2' align='left'><b>GHS $nt</b></TH> 
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