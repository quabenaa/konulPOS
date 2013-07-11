<?php
session_start();
require_once 'conn.php';

//check to see if user has logged in with a valid password
if (!isset($_SESSION['user_id']) && !isset($_SESSION['access_lvl']))
{
header("Location:./");
}
else if($_SESSION['access_lvl'] != 2 && $_SESSION['access_lvl'] != 4){
	header("Location:error-401.php?/access=denied/");
die();
}

 
if(isset($_REQUEST["change"])){
$change = $_REQUEST["change"];
$change=number_format($change,2);
}else{$change='0.00';}
$sqr="SELECT * FROM `company info`";
$reslt = mysql_query($sqr,$conn) or die('Could not look up user data; ' . mysql_error());
$rw = mysql_fetch_array($reslt);
$coy=$rw['Company Name'];
$ady=$rw['Address'];
$loc=$rw['City'];
$phn=$rw['Phone'];
$vt=$rw['VAT No'];

?>
<style>
.recipe {
    border: 1px solid crimson;
	border-collapse: collapse;
}
</style>
<table width='100%'>
<tr><td align='center'><img src='images/logo.jpg' width='37' height='40'></td></tr>
<tr><td align='center'><font style='font-size: 10pt'><b><?php echo $coy; ?></b></font></td></tr>
<tr><td align='center'><font style='font-size: 9pt'><b><?php echo $ady . ', ' . $loc; ?></b></font></td></tr>
<tr><td align='center'><font style='font-size: 9pt'><b><?php echo 'TEL: '.$phn; ?></b></font></td></tr>
<tr><td align='center'><font style='font-size: 9pt'><b><?php echo  date('d/m/Y   H:i:s',time()) ?></b></font></td></tr>


<tr><td align='center'><font style='font-size: 10pt'><b>SALES RECEIPT</b></font></td></tr>
<tr><td align='left'><font style='font-size: 9pt'><b>Receipt #: <?php echo $_SESSION['cust_token']; ?></b></font></td></tr>
<tr><td align='left'><font style='font-size: 9pt'><b>VAT Registration: <?php echo $vt; ?></b></font></td></tr>
<?php if(isset($_REQUEST["credit"])){
?><tr><td align='left'><font style='font-size: 9pt'><b>Customer #: <?php echo $_SESSION['cust_token']; ?></b></font></td></tr>
<tr><td align='left'><font style='font-size: 9pt'><b>Customer: <?php echo $_REQUEST['credit']; ?></b></font></td></tr>
<?php 
$payment = "Credit Sales";
}else{
$payment = "Cash Sales";
	}
?>
<tr>
<td>
<TABLE width='100%' border='0' cellpadding='0' cellspacing='0' align='left' id="table3">
 <?php
  echo "<tr><td colspan=4 align='left'><font color='#000000' style='font-size: 11pt'>
  <hr/>
  </font></td></tr>";
  echo "<TR><TH align='left' width='20%'><font color='#000000' style='font-size: 11pt'>Qty</font></TH>
  <TH align='left' width='40%'><font color='#000000' style='font-size: 10pt'>Description</font></TH>
  <TH align='right' width='20%'><font color='#000000' style='font-size: 10pt'>Price</font></TH>
  <TH align='right' width='20%'><font color='#000000' style='font-size: 10pt'>Total</font></TH></TR>";

echo "<tr><td colspan=4 align='left'><font color='#000000' style='font-size: 11pt'>
 <hr/>
  </font></td></tr>";

  $query="SELECT `ID`,`Stock Name`,`Qnty Sold`,`Unit Cost`,`Entered by` FROM sales WHERE `cust_id` = '" . $_SESSION['cust_return'] . "' order by `ID` desc";
  $result=mysql_query($query) or die(mysql_error());
	$soldby='';
  while(list($id,$name,$qnty,$price,$by)=mysql_fetch_row($result))
  {
     $total=number_format((1*$price),2);
     $qnty=number_format($qnty,0);
     $price=number_format($price,2);
     $name=substr($name,0,16);
	 $soldby=$by;
     echo "<TR>
	 <TD align='left' widTD='20%'><font color='#000000' style='font-size: 8pt'>$qnty </font></TD>
	 <TD align='left' widTD=40%'><font color='#000000' style='font-size: 9pt'>$name </font></TD>
	 <TD align='right' widTD='20%'><font color='#000000' style='font-size: 9pt'>$price </font></TD>
	 <TD align='right' widTD='20%'><font color='#000000' style='font-size: 9pt'>$total</font></TD></TR>";
  }
    $queryy="SELECT sum(`Qnty Sold`) as Qnty,sum(`Total Cost`) as Totsum FROM sales WHERE `Entered By` ='" . strtoupper($_SESSION['name']) . "' and `Sales Date`='" . date('Y-m-d') . "' and `cust_id` = '" . $_SESSION['cust_token'] . "'";
    $resultt=mysql_query($queryy);
    $roww = mysql_fetch_array($resultt);
	if(isset($_REQUEST['cash']) && !empty($_REQUEST['cash'])){
$cash = $_REQUEST['cash'];
//$cash = number_format($cash_, 2);
}else{$cash =0.00;}
    $totsum=number_format($roww['Totsum'],2);
    $qqnty=number_format($roww['Qnty'],0);
    
	echo "<tr><td colspan=4 align='left'><font color='#000000' style='font-size: 11pt'>
  <hr/>
  </font></td></tr>";
    echo "<TR><TH colspan='2' align='right' width='50'><font color='#000000' style='font-size: 10pt'>Sub Total :</font> </TH>
	<TH align='right' colspan='2' align='right' width='50'><font color='#000000' style='font-size: 11pt'>$totsum</font></TH></TR>"; 
	 
    echo "<TR><TH colspan='2' align='right' width='50'><font color='#000000' style='font-size: 10pt'>Amount Paid :</font> </TH>
	<TH colspan='2' align='right' width='50'><font color='#000000' style='font-size: 10pt'> $cash </font></TH></TR>";  
	
    echo "<TR><TH colspan='2' align='right' width='50'><font color='#000000' style='font-size: 10pt'>Change :</font> </TH>
	<TH colspan='2' align='right' width='50'><font color='#000000' style='font-size: 10pt'>$change </font></TH></TR>";
	echo "<TR><TH colspan='2' align='right' width='50'><font color='#000000' style='font-size: 10pt'>Payment Type :</font> </TH>
	<TH colspan='2' align='right' width='50'><font color='#000000' style='font-size: 10pt'>$payment </font></TH></TR>";   
	 echo "<tr><td colspan=4 align='left'><font color='#000000' style='font-size: 11pt'>
  <hr/>
  </font></td></tr>";
  echo "<tr><td colspan=4 align='left'><font color='#000000' style='font-size: 10pt'>
 Cashier : " . strtoupper($soldby) ."
  </font></td></tr>";		
    echo "<tr><td colspan='4' align='center'>
	<br><font color='#000000' style='font-size: 10pt'> 12.5% VAT & 2.5% NHIS INCLUSIVE
	<br>THANK YOU FOR SHOPPING WITH US <br></font><br />
	<font color='#000000' style='font-size: 8pt'>POWERED BY SMARTVIEW TECHNOLOGY (0266293122)</font>
	</td></tr>";
?>
</table>
</td></tr>
</table>

<script type="text/javascript">
function my_code()
{
window.print();
}

window.onload=my_code();
window.location = "return.php?tval=$tval";
</script>