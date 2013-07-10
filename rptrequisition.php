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
<tr><td align='center'><h2>REQUISITION REPORT</h2></td></tr>
<tr><td align='left'><?php echo "<strong>Date</strong> : ".date('d/m/Y')." </u></b>"; ?></td></tr>
    <td>
    <br>

<table class="listtable" width="100%" id="AutoNumber2">
<?php
if (empty($filter))
{
  $result = mysql_query ("SELECT `ID`,`Stock Name`,`Stock Code`,`Stock Date`,`Qnty Given`,`Request By`,`Given By`,Destination 
FROM requisition order by `Stock Date` Desc"); 
} else {
  $result = mysql_query ("SELECT `ID`,`Stock Name`,`Stock Code`,`Stock Date`,`Qnty Given`,`Request By`,`Given By`,Destination 
FROM requisition order by `Stock Date` Desc"); 
}
  echo "
  <TR>
 <TH width='5%'>NO </font>&nbsp;</TH>
  <TH width='15%'> REQUISITION DATE &nbsp;</TH>
  <TH width='15%'>PRODUCT NAME &nbsp;</TH>
  <TH width='15%'>QUANTITY GIVEN &nbsp;</TH>
  <TH width='15%'>REQUESTED BY &nbsp;</TH>
  <TH width='25%'>GIVEN BY &nbsp;</TH>
  </TR>";

	$n=0;
	while(list($id,$name,$code,$dates,$given,$reqby,$givenby,$loc)=mysql_fetch_row($result))
	{
		$n++;
      echo "<TR>
	  <TD width='5%'>$n</TD>
	  <TD width='15%'>$dates</TD>
	  <TD width='15%'>".strtoupper($name)." </TD>
	  <TD width='15%'>$given</TD>
	  <TD width='15%'>".strtoupper($reqby)."</TD>
	  <TD width='25%'>".strtoupper($givenby)."</TD>
	  </TR>";
   }

  /*$sql_tt = "SELECT COUNT(`ID`) as amt From `dispensary_stocks`"; 
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
        </td>
    </tr>
</table>
<script type="text/javascript">
function my_code()
{
window.print();
}

window.onload=my_code();
window.location = "requisition.php";
</script>			