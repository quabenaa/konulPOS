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
?>
<style>
.listtable
{
	font-size:12px;
	border:1px solid black;
	border-collapse:collapse;
	width:100%;
}
.listtable th
{
	border:1px solid black;
	height:10px;
	padding:10px;
}
.listtable td
{
	border:1px solid black;
	height:10px;
	vertical-align:bottom;
	padding:10px;
}
</style>
<table width='100%'>
<tr><td align='center'><h1>TEST SUPERMARKET</h1></td></tr>
<!--<tr><td align='center'><h2>Tafo Hospital</h2></td></tr> -->
<tr><td align='center'><h2>DAILY CASHBOOK</h2></td></tr>
<tr><td align='left'><?php echo "<strong>Date</strong> : ".date('d/m/Y')." </u></b>"; ?></td></tr>
    <td>
    <br>

<table class="listtable" width="100%" id="AutoNumber2">
<?php

  echo "
  <TR>
  <TH width='5%'>No</TH>
  <TH width='15%'>Date</TH>
  <TH width='15%'>Type</TH>
  <TH width='15%'>Classification</TH>
  <TH width='15%'>Amount</TH>
  <TH width='35%'>Particulars</TH>
  </TR>";

	$n=0;
	while(list($id,$date,$type,$classification,$amount,$particulars)=mysql_fetch_row($result))
	{
		$n++;
     $amount=number_format($amount,2);
      echo "<TR>
	  <TD width='5%'>$n</TD>
	  <TD width='15%'>$date </font>&nbsp;</TD>
	  <TD width='15%'>$type </font>&nbsp;</TD>
	  <TD width='15%'>$classification</font>&nbsp;</TD>
	  <TD width='15%'>$amount</font>&nbsp;</TD>
	  <TD width='35%'>$particulars</font>&nbsp;</TD>
	  </TR>";
   }

  $row = mysql_fetch_array($result_tt);
  $nt=$row['amt'];
  $nt=number_format($nt,2);
   echo "<TR><TH colspan='6'>&nbsp;</TH></TR>";
   echo "<TR>
   <TH>&nbsp;</TH>
   <TH>&nbsp;</TH>
   <TH>&nbsp;</TH>
   <TH><b>Total</b></TH>
   <TH align='center'><b>$nt</b></TH>   
   <TH>&nbsp;</TH>
   </TR><p>";
?>
</table>
        </td>
    </tr>
</table>
<script type="text/javascript">
function my_code()
{
window.print();
}

window.onload=my_code();
window.location = "dailycash.php?tval=$tval&redirect=$redirect";
</script>			