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
	$val="date(`Sales Date`) = '" . date('Y-m-d', strtotime($date)) . "'";		
	$queryM="SELECT distinct(`Entered By`) as staff, `ID`,`Sales Date` FROM sales  WHERE ". $val . " group by `Entered By`";
	$print ="accreport.php?date=$date";	
}else{
	$date = date('Y-m-d');
	$val="date(`Sales Date`) = '" . date('Y-m-d', strtotime($date)) . "'";		
	$queryM="SELECT distinct(`Entered By`) as staff, `ID`,`Sales Date` FROM sales  WHERE ". $val . " group by `Entered By`";
	$print ="accreport.php?date=$date";
}
 $resultM=mysql_query($queryM) or die(mysql_error());

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
<tr><td align='center'><h2>INDIVIDUALS' SALES REPORT</h2></td></tr>
<tr><td align='left'><?php echo "<strong>Date</strong> : ".date('d-m-Y',strtotime($date))." </u></b>"; ?></td></tr>
    <td>
    <br>

<table class="listtable" width="100%">
			<?php
   echo "
   <thead>
	 <TR>
	 <TH>S/No &nbsp;</TH>
	 <TH>Stock Name</TH>
	 <TH>Qnty Sold</TH>
     <TH>Unit Price</TH>
	 <TH>Total Sales</TH>
	 <TH>Discount</TH>
	 <TH>Sales Amount</TH>
	 </TR>
	 </thead> <tbody>";
	if(mysql_num_rows($resultM )> 0){
   while(list($staf, $idd,$datt)=mysql_fetch_row($resultM))
   {     
	 echo "<TR>
	   <TD colspan='7'>
	   <b><font color='#1BCD50' style='font-size: 10pt'>Today's Sales By " . strtoupper($staf) . "</font></b>
	   </TD>
	   </TR>";	  
	 
     $query="
	 SELECT distinct(`Stock Name`) as stock, `ID`,`Sales Date` FROM sales  WHERE `Entered By` ='".$staf."' AND `Sales Date`='" . $datt . "'group by `Stock Name`";
     
	 $results=mysql_query($query) or die(mysql_error());
     $i=0;
     while(list($name,$id,$date)=mysql_fetch_row($results))
     {
	$query="
	 SELECT `ID`,`Sales Date`,`Stock Name`,`Stock Code`,SUM(`Qnty Sold`) AS qnty, `Unit Cost`, SUM(`Deposit`) AS depot,SUM(`Discount`) AS disc,`Paid` 
	 FROM sales
	 WHERE `Stock Name`='$name' AND `Entered By` ='$staf' and `Sales Date`='" . $datt . "'";
	 $result=mysql_query($query) or die(mysql_error());
	 
	 list($id,$date,$name,$code,$qnty,$price,$deposit,$discount,$paid)=mysql_fetch_row($result);

       $total=number_format(($qnty*$price),2);
       $bal= number_format((($qnty*$price)-$deposit-$discount),2);
       $qnty=number_format($qnty,0);
       $price=number_format($price,2);
       $deposit=number_format($deposit,2);
       $discount=number_format($discount,2);
 
       $i=$i+1;
       echo "<TR>
	   <TD>$i</TD>
	   <TD>$name</TD>
	   <TD>$qnty</TD>
	   <TD>$price</TD>
	   <TD>$total</TD>
       <TD>$discount</TD>
	   <TD>$bal</TD>
	   </TR>";
     }
     $queryy="SELECT sum(`Qnty Sold`) as Qnty,sum(`Deposit`) as Deposit,sum(`Discount`) as Discount,sum((`Qnty Sold`*`Unit Cost`)) as Totsum,sum((`Qnty Sold`*`Unit Cost`)-`Discount`) as balsum FROM sales WHERE `Entered By` ='$staf' and `Sales Date`='" . $datt . "'";
     $resultt=mysql_query($queryy);
     $roww = mysql_fetch_array($resultt);
     $totsum=number_format($roww['Totsum'],2);
     $balsum= number_format($roww['balsum'],2);
     $qqnty=number_format($roww['Qnty'],0);
     $ddeposit=number_format($roww['Deposit'],2);
     $ddiscount=number_format($roww['Discount'],2);
     echo "<TR>
	 <TH>&nbsp;</TH>
	 <TH>TOTAL SALES</TH>
	 <TH>$qqnty</TH>
     <TH>&nbsp;</TH>
	 <TH>$totsum</TH>
	 <TH>$ddiscount</TH>
	 <TH>$balsum</TH>
	 </TR> "; 
	 echo '</tbody>';
   }
   }else{
	echo '<TR><TH colspan="7">No Record available in table</TH>';	
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
window.location = "<?php echo $print; ?>";
</script>			