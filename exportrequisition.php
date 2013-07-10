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
  echo "<h2><center>RE-STOCKING REPORT</center></h2> ";

if (empty($filter))
{
  $result = mysql_query ("SELECT `ID`,`Stock Name`,`Stock Code`,`Stock Date`,`Qnty Given`,`Request By`,`Given By`,Destination 
FROM requisition order by `Stock Date` Desc"); 
} else {
  $result = mysql_query ("SELECT `ID`,`Stock Name`,`Stock Code`,`Stock Date`,`Qnty Given`,`Request By`,`Given By`,Destination 
FROM requisition order by `Stock Date` Desc"); 
}
  echo "<font style='font-size: 9pt'><b>Date: " . date('d/m/Y') . "</b></font>&nbsp; &nbsp &nbsp; &nbsp &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp";
  echo "
  <TR>
  <TH bgcolor='#C0C0C0' width='5%'> <font style='font-size: 9pt'>NO </font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='15%'> <font style='font-size: 9pt'>REQUISITION DATE </font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='15%'><font style='font-size: 9pt'>PRODUCT NAME</font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='15%'><font style='font-size: 9pt'>QUANTITY GIVEN</font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='15%'><font style='font-size: 9pt'>REQUESTED BY</font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='25%'><font style='font-size: 9pt'>GIVEN BY</font>&nbsp;</TH>
  </TR>";

	$n=0;
	while(list($id,$name,$code,$dates,$given,$reqby,$givenby,$loc)=mysql_fetch_row($result))
	{
		$n++;
      echo "<TR>
	  <TH width='5%'> <font style='font-size: 8pt'>$n</font>&nbsp;</TH>
	  <TH width='15%'> <font style='font-size: 8pt'>$dates </font>&nbsp;</TH>
	  <TH width='15%'><font style='font-size: 8pt'>$name </font>&nbsp;</TH>
	  <TH width='15%'><font style='font-size: 8pt'>$given</font>&nbsp;</TH>
	  <TH width='35%'><font style='font-size: 8pt'>$reqby</font>&nbsp;</TH>
	  <TH width='35%'><font style='font-size: 8pt'>$givenby</font>&nbsp;</TH>
	  </TR>";
   }

  /* $sql_tt = "SELECT COUNT(`ID`) as amt From `dispensary_stocks`"; 
  $result_tt = mysql_query($sql_tt,$conn) or die('Could not list value; ' . mysql_error());
  $row = mysql_fetch_array($result_tt);
  $nt=$row['amt'];
   echo "<TR><TH colspan='7'>&nbsp;</TH></TR>";
   echo "
   <TR>
   <TH colspan='7'><b>TOTAL NUMBER OF PRODUCTS : $nt</b></TH> 
   </TR><p>"; */
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