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
<tr><td align='center'><h2>RE-STOCKING REPORT</h2></td></tr>
<tr><td align='left'><?php echo "<strong>Date</strong> : ".date('d/m/Y')." </u></b>"; ?></td></tr>
    <td>
    <br>

<table class="listtable" width="100%" id="AutoNumber2">
<?php
if (empty($filter))
{
  $result = mysql_query ("SELECT `ID`,`Stock Name`,`Stock Date`,`Qnty Added`,`Received By`,`Supplier`,`Category` FROM `restock` order by `Stock Date` Desc"); 
} else {
  $result = mysql_query ("SELECT `ID`,`Stock Name`,`Stock Date`,`Qnty Added`,`Received By`,`Supplier`,`Category` FROM `restock` order by `Stock Date` Desc"); 
}
  echo "
  <TR>
  <TH width='5%'>No</TH>
  <TH width='15%'>RESTOCK DATE</TH>
  <TH width='25%'>PRODUCT NAME</TH>
  <TH width='15%'>CATEGORY</TH>
  <TH width='15%'>QUANTITY ADDED</TH>
  <TH width='15%'>RECIEVED BY</TH>
  <TH width='15%'>SUPPLIED BY</TH>  
  </TR>";

	$n=0;
	while(list($id,$name,$dates,$added,$recby,$suppby,$cat)=mysql_fetch_row($result))
	{
		$n++;
      echo "<TR>
	  <TD width='5%'>$n</TD>
	  <TD width='15%'>$dates</TD>
	  <TD width='15%'>".strtoupper($name)." </TD>
	  <TD width='15%'>".strtoupper($cat)."</TD>
	  <TD width='15%'>$added</TD>
	  <TD width='15%'>".strtoupper($recby)."</TD>
	  <TD width='25%'>".strtoupper($suppby)."</TD>
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
window.location = "restock.php";
</script>			