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
<tr><td align='center'><h2>STOCK REPORT</h2></td></tr>
<tr><td align='left'><?php echo "<strong>Date</strong> : ".date('d/m/Y')." </u></b>"; ?></td></tr>
    <td>
    <br>

<table class="listtable" width="100%" id="AutoNumber2">
<?php
  echo "
  <TR>
  <TH width='5%'>No</TH>
  <TH width='15%'>BARCODE</TH>
  <TH width='25%'>PRODUCT NAME</TH>
  <TH width='15%'>CATEGORY</TH>
  <TH width='15%'>UNIT IN STOCK</TH>
  <TH width='15%'>RE-ORDER LEVEL</TH>
  <TH width='15%'>EXPIRY DATE</TH>
  </TR>";

	$n=0;
	while(list($code,$name,$attribute,$units,$re_order_lvl,$expiry)=mysql_fetch_row($result))
	{
		$n++;
      echo "<TR>
	  <TD width='5%'>$n</TD>
	  <TD width='15%'>$code</TD>
	  <TD width='15%'>".strtoupper($name)." </TD>
	  <TD width='15%'>".strtoupper($attribute)."</TD>
	  <TD width='15%'>$units</TD>
	  <TD width='15%'>$re_order_lvl</TD>
	  <TD width='25%'>$expiry</TD>
	  </TR>";
   }

  $result_tt = mysql_query($sql_tt,$conn) or die('Could not list value; ' . mysql_error());
  $row = mysql_fetch_array($result_tt);
  $nt=$row['amt'];
   echo "<TR><TH colspan='7'>&nbsp;</TH></TR>";
   echo "
   <TR>
   <TH colspan='7'><b>TOTAL NUMBER OF PRODUCTS : $nt</b></TH> 
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
window.location = "<?php echo $url; ?>";
</script>			