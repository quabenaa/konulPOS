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


@$filename = "stockreport_" . date('Ymd') . $filter . ".xls";
 header("Content-Disposition: attachment; filename=\"$filename\"");
 header("Content-Type: application/vnd.ms-excel");  
 
 if(isset($_REQUEST['exp'])){
	
	$valexp="date(`Expiry Date`) < '" . date('Y-m-d', strtotime('+2 month')) . "'";
	$query="
	SELECT `Stock Code`,`Stock Name`,`Category`,`Units in Stock`,`Reorder Level`,`Expiry Date`
		FROM `stock` 
	WHERE ".$valexp."";
	$sql_tt = "SELECT COUNT(`ID`) as amt FROM `stock` WHERE ".$valexp.""; 
	$url = "stocklist.php?exp=1";
		
}else if (isset($_REQUEST['relvl'])){

	$query="
	SELECT `Stock Code`,`Stock Name`,`Category`,`Units in Stock`,`Reorder Level`,`Expiry Date` 
		FROM `stock` 
	WHERE (`Reorder Level` > `Units in Stock` OR `Reorder Level` = `Units in Stock`)";
	$sql_tt = "SELECT COUNT(`ID`) as amt FROM `stock`WHERE (`Reorder Level` > `Units in Stock` OR `Reorder Level` = `Units in Stock`)";
	$url = "stocklist.php?relvl=1";
		
}else{

	$query="
	SELECT `Stock Code`,`Stock Name`,`Category`,`Units in Stock`,`Reorder Level`,`Expiry Date`  
		FROM `stock` ";
	$sql_tt = "SELECT COUNT(`ID`) as amt FROM `stock`";
	$url = "stocklist.php";
}
$result = mysql_query ($query) or die(mysql_error());
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
  echo "<h2><center>STOCK REPORT</center></h2> ";

  echo "<font style='font-size: 9pt'><b>Date: " . date('d/m/Y') . "</b></font>&nbsp; &nbsp &nbsp; &nbsp &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp";
  echo "
  <TR>
  <TH bgcolor='#C0C0C0' width='5%'> <font style='font-size: 9pt'>NO </font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='15%'> <font style='font-size: 9pt'>PRODUCT CODE </font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='15%'><font style='font-size: 9pt'>PRODUCT NAME</font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='15%'><font style='font-size: 9pt'>CATEGORY</font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='15%'><font style='font-size: 9pt'>UNIT IN STOCK</font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='15%'><font style='font-size: 9pt'>RE-OREDER LEVEL</font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='25%'><font style='font-size: 9pt'>EXPIRY DATE</font>&nbsp;</TH>
  </TR>";

	$n=0;
	while(list($code,$name,$attribute,$units,$re_order_lvl,$expiry)=mysql_fetch_row($result))
	{
		$n++;
      echo "<TR>
	  <TH width='5%'> <font style='font-size: 8pt'>$n</font>&nbsp;</TH>
	  <TH width='15%'> <font style='font-size: 8pt'>$code </font>&nbsp;</TH>
	  <TH width='15%'><font style='font-size: 8pt'>$name </font>&nbsp;</TH>
	  <TH width='15%'><font style='font-size: 8pt'>$attribute</font>&nbsp;</TH>
	  <TH width='15%'><font style='font-size: 8pt'>$units</font>&nbsp;</TH>
	  <TH width='35%'><font style='font-size: 8pt'>$re_order_lvl</font>&nbsp;</TH>
	  <TH width='35%'><font style='font-size: 8pt'>$expiry</font>&nbsp;</TH>
	  </TR>";
   }

 $sql_tt = "SELECT COUNT(`ID`) as amt From `stock`"; 
  $result_tt = mysql_query($sql_tt,$conn) or die('Could not list value; ' . mysql_error());
  $row = mysql_fetch_array($result_tt);
  $nt=$row['amt'];
   echo "<TR><TH colspan='7'>&nbsp;</TH></TR>";
   echo "<TR>
   <TH bgcolor='#EAEAEA' colspan='7'><font style='font-size: 8pt'><b>TOTAL NUMBER OF PRODUCTS : $nt</b></font></TH>
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