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
<tr><td align='center'><h2>CREDITORS REPORT</h2></td></tr>
<tr><td align='left'><?php echo "<strong>Date</strong> : ".date('d/m/Y')." </u></b>"; ?></td></tr>
    <td>
    <br>

<table class="listtable" width="100%" id="AutoNumber2">
<?php
if (empty($filter))
{
  $query="SELECT `ID`,`Date`,Company,`Contact Person`,Amount,`Paid`,`Balance` FROM creditor order by `Date` desc";
} else {
  $query="SELECT `ID`,`Date`,Company,`Contact Person`,Amount,`Paid`,`Balance` FROM creditor order by `Date` desc";
}
$result = mysql_query($query) or die(mysql_error());

  echo "
  <TR>
  <TH bgcolor='#C0C0C0' width='5%'> <font style='font-size: 9pt'>S/NO </font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='15%'> <font style='font-size: 9pt'>DATE </font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='15%'><font style='font-size: 9pt'>COMPANY NAME</font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='15%'><font style='font-size: 9pt'>CONTACT PERSON</font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='15%'><font style='font-size: 9pt'>AMOUNT</font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='15%'><font style='font-size: 9pt'>AMOUNT PAID</font>&nbsp;</TH>
  <TH bgcolor='#C0C0C0' width='25%'><font style='font-size: 9pt'>BALANCE TO PAY</font>&nbsp;</TH>
  </TR>";
if(mysql_num_rows($result)>0){
	$n=0;
	while(list($id,$date,$coy,$contact,$amount,$paid,$bal)=mysql_fetch_row($result))
	{
	 $n++;
      echo "<TR>
	  <TH > <font style='font-size: 8pt'>$n</font>&nbsp;</TH>
	  <TH > <font style='font-size: 8pt'>$date </font>&nbsp;</TH>
	  <TH ><font style='font-size: 8pt'>$coy </font>&nbsp;</TH>
	  <TH ><font style='font-size: 8pt'>$contact</font>&nbsp;</TH>
	  <TH ><font style='font-size: 8pt'>".number_format($amount,2)."</font>&nbsp;</TH>
	  <TH ><font style='font-size: 8pt'>".number_format($paid,2)."</font>&nbsp;</TH>
	  <TH ><font style='font-size: 8pt'>".number_format($bal,2)."</font>&nbsp;</TH>
	  </TR>";
   }

 $sql_tt = "SELECT SUM(Amount) as amt, SUM(`Paid`) as paid, SUM(`Balance`) as bal FROM creditor"; 
  $result_tt = mysql_query($sql_tt,$conn) or die('Could not list value; ' . mysql_error());
  $row = mysql_fetch_array($result_tt);
  $amt=$row['amt'];
  $paid=$row['paid'];
  $bal=$row['bal'];
   echo "<TR><TH colspan='7'>&nbsp;</TH></TR>";
   echo "
   <TR  bgcolor='#C0C0C0'>
   <TH colspan='3'><b>TOTAL :</b></TH>
   <TH></TH>
   <TH ><b> $amt</b></TH>
   <TH ><b> $paid</b></TH> 
   <TH ><b> $bal</b></TH>  
   </TR><p>";
	}else{		
	echo "<TR><TH colspan='7'>No data available in table</TH></TR>";	
	}
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
window.location = "creditors.php";
</script>	 		