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
<tr><td align='center'><h2>CHEQUE REPORT</h2></td></tr>
<tr><td align='left'><?php echo "<strong>Date</strong> : ".date('d/m/Y')." </u></b>"; ?></td></tr>
    <td>
    <br>

<table class="listtable" width="100%" id="AutoNumber2">
<?php
if (empty($filter))
{
  $result = mysql_query ("SELECT `ID`,`Date`,Type, Bank, Amount,`Particulars`,`Cheque No` FROM cheque order by `Date` Desc"); 
} else {
  $result = mysql_query ("SELECT `ID`,`Date`, Type, Bank, Amount,`Particulars`,`Cheque No` FROM cheque order by `Date` Desc"); 
}
  echo "
  <TR>
  <TH width='5%'>CHEQUE NO &nbsp;</TH>
  <TH width='15%'>DATE &nbsp;</TH>
  <TH width='15%'>TYPE &nbsp;</TH>
  <TH width='15%'>BANK &nbsp;</TH>
  <TH width='15%'>AMOUNT &nbsp;</TH>
  <TH width='25%'>PARTICULARS &nbsp;</TH>
  </TR>";

	$n=0;
	while(list($id,$date,$type,$bank,$amount,$particulars,$cheque)=mysql_fetch_row($result))
	{
		$n++;
      echo "<TR>
	  <TD width='5%'>$cheque</TD>
	  <TD width='15%'>$date</TD>
	  <TD width='15%'>".strtoupper($type)." </TD>
	  <TD width='15%'>".strtoupper($bank)." </TD>
	  <TD width='15%'>$amount</TD>
	  <TD width='25%'>".strtoupper($particulars)."</TD>
	  

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
        </td>
    </tr>
</table>
<script type="text/javascript">
/* function my_code()
{
window.print();
}

window.onload=my_code();
window.location = "requisition.php";*/
</script>	 		