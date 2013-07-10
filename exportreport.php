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

global $Tit;
@$Tit=$_REQUEST['Tit'];
@$store=$_REQUEST['store'];


if(isset($_REQUEST["cmbReport"]) && !empty($_REQUEST["cmbReport"])){
 $cmbReport = $_REQUEST["cmbReport"];
  }else{$cmbReport='';}
  
   if(isset($_REQUEST['cmbTable']) && !empty($_REQUEST['cmbTable'])){
  $cmbTable=$_REQUEST['cmbTable']; 
  }else{$cmbTable='';}
   
   if(isset($_REQUEST["filter"]) && !empty($_REQUEST["filter"])){
 $filter=$_REQUEST["filter"];
  }else{$filter='';} 

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
	<h1><center>TEST SUPERMARKET</center></h1>
			<?php
if (trim($cmbReport) == "- Select Report-" or trim($cmbTable) == "- Select Criteria -")
{
echo "<b>Please Select a Report and a Criteria from the drop-down box and click 'Open'.<b>";        
}
else if (trim($cmbReport)=="Sales Report")
{  
if (trim($cmbTable)=="Daily")
{
   @$val="`Sales Date`='" . date('Y-m-d') . "'"; 
}
else if (trim($cmbTable)=="Weekly")
{
  @$val="date(`Sales Date`)>'" . date('Y-m-d', strtotime('-1 week')) . "' and date(`Sales Date`)<'" . date('Y-m-d', strtotime('+1 day')) . "'";
}
else if (trim($cmbTable)=="Monthly")
{
   @$val="date(`Sales Date`)>'" . date('Y-m-d', strtotime('-1 month')) . "' and date(`Sales Date`)<'" . date('Y-m-d', strtotime('+1 day')) . "'";
}
else if (trim($cmbTable)=="Quarterly")
{
  @$val="date(`Sales Date`)>'" . date('Y-m-d', strtotime('-3 month')) . "' and date(`Sales Date`)<'" . date('Y-m-d', strtotime('+1 day')) . "'";
}
else if (trim($cmbTable)=="Yearly")
{
  @$val="date(`Sales Date`)>'" . date('Y-m-d', strtotime('-12 month')) . "' and date(`Sales Date`)<'" . date('Y-m-d', strtotime('+1 day')) . "'";
}
if (empty($val) or @$val=="")
{  
  @$val="`Sales Date`='" . date('Y-m-d') . "'";
}

   echo "
   <thead>
   <TR>
   <TH>Stock Code</TH>
   <TH>Stock Name</TH>
   <TH>Qnty Sold </TH>
   <TH>Sales Price</TH>
   <TH>Total Sales</TH>
   <TH>Discount</TH>
   <TH>Balance</TH>
   <TH>Payment</TH>
   </TR>
   </thead><tbody>";

   $result = mysql_query ("SELECT `ID`,`Sales Date`,`Stock Name`,`Stock Code`,`Qnty Sold`,`Unit Cost`,`Deposit`,`Discount`,`Paid` From `sales` where " . @$val . " order by `Stock Code`"); 


    while(list($id,$date,$name,$code,$qnty,$price,$deposit,$discount,$paid)=mysql_fetch_row($result)) 
    {	
     $total=number_format(($qnty*$price),2);
     $bal= number_format((($qnty*$price)-$discount),2);
     $qnty=number_format($qnty,0);
     $price=number_format($price,2);
     $deposit=number_format($deposit,2);
     $discount=number_format($discount,2);

     echo "
	 <TR>
	 <TD >$code</TD>
	 <TD> $name</TD>
	 <TD>$qnty</td>
	 <TD>$price</TD>
	 <TD>$total</TD>
	 <TD>$discount</TD>
	 <TD>$bal</TD>
	 <TD>$paid</TD>
	 </TR>";
    }
    $queryy="SELECT sum(`Qnty Sold`) as Qnty,sum(`Deposit`) as Deposit,sum(`Discount`) as Discount,sum((`Qnty Sold`*`Unit Cost`)) as Totsum,sum((`Qnty Sold`*`Unit Cost`)-`Discount`) as balsum From `sales` WHERE " . @$val;
    $resultt=mysql_query($queryy);
    $roww = mysql_fetch_array($resultt);

    $totsum=number_format($roww['Totsum'],2);
    $balsum= number_format($roww['balsum'],2);
    $qqnty=number_format($roww['Qnty'],0);
    $ddeposit=number_format($roww['Deposit'],2);
    $ddiscount=number_format($roww['Discount'],2);
    echo "
	</tbody><tfoot>
	<TR>
	<TH>&nbsp;</TH>
	<TH>TOTAL SALES</TH>
	<TH>$qqnty</TH>
	<TH>&nbsp;</TH>
	<TH>$totsum </TH>
	<TH>$ddiscount</span></TH>
	<TH>$balsum </TH>
	<TH>&nbsp;</TH>
	</TR>
	</tfoot>";  
    
 }

############################################################################################################################
 else if ($cmbReport == "Daily Profit Report")
 {
if (trim($cmbTable)=="Daily")
{
   @$val="`Sales Date`='" . date('Y-m-d') . "'"; 
}
else if (trim($cmbTable)=="Weekly")
{
  @$val="date(`Sales Date`)>'" . date('Y-m-d', strtotime('-1 week')) . "' and date(`Sales Date`)<'" . date('Y-m-d', strtotime('+1 day')) . "'";
}
else if (trim($cmbTable)=="Monthly")
{
   @$val="date(`Sales Date`)>'" . date('Y-m-d', strtotime('-1 month')) . "' and date(`Sales Date`)<'" . date('Y-m-d', strtotime('+1 day')) . "'";
}
else if (trim($cmbTable)=="Quarterly")
{
  @$val="date(`Sales Date`)>'" . date('Y-m-d', strtotime('-3 month')) . "' and date(`Sales Date`)<'" . date('Y-m-d', strtotime('+1 day')) . "'";
}
else if (trim($cmbTable)=="Yearly")
{
  @$val="date(`Sales Date`)>'" . date('Y-m-d', strtotime('-12 month')) . "' and date(`Sales Date`)<'" . date('Y-m-d', strtotime('+1 day')) . "'";
}
if (empty($val) or @$val=="")
{  
  @$val="`Sales Date`='" . date('Y-m-d') . "'";
}

     echo "
	 <thead>
	 <TR>
	 <TH>S/No</TH>
	 <TH>Stock Code</TH>
	 <TH>Stock Name</TH>
	 <TH>Qnty Sold</TH>
	 <TH>Cost Price </TH>
	 <TH>Selling Price</TH>
	 <TH>Total Sales</TH>
	 <TH>Total Cost</TH>
	 <TH>Unit Profit</TH>
	 <TH>Total Profit</TH>
	 </TR>
	 </thead><tbody>";

     $query="SELECT `ID`,`Sales Date`,`Stock Name`,`Stock Code`,`Qnty Sold` FROM sales WHERE " . $val . " order by `ID` desc";
     $result=mysql_query($query);
     $i=0; $sumuprofit=0; $sumtprofit=0;$sumqnty=0;$sumTcost =0; $sumTsales = 0;$sumUcost = 0; $sumSprice = 0;
     while(list($id,$date,$name,$code,$qnty)=mysql_fetch_row($result))
     {
       $queryR="SELECT `ID`,`Stock Code`,`Unit Cost`,`Selling Price` FROM `stock` WHERE `Stock Code`='" . $code . "'";
       $resultR=mysql_query($queryR);
       $rowr = mysql_fetch_array($resultR);

       $ucost=$rowr['Unit Cost'];
       $sprice=$rowr['Selling Price'];
       $uprofit=$sprice-$ucost;

       $totalc=$qnty*$ucost;
       $totals=$qnty*$sprice;
       $tprofit=$totals-$totalc;

       $totalcost=number_format(($qnty*$ucost),2);
       $totalsales=number_format(($qnty*$sprice),2);
       $totalprofit=number_format($tprofit,2);
       $unitprofit=number_format($uprofit,2);

       $qnty=number_format($qnty,0);
       $sprice=number_format($sprice,2);
       $ucost=number_format($ucost,2);
 
       $i=$i+1;

       echo "	  
	   <TR>
	   <TD>$i</TD>
	   <TD>$code</TD>
	   <TD>$name</TD>
	   <TD>$qnty</TD>
	   <TD>$ucost</TD>
	   <TD>$sprice</TD>
	   <TD>$totalsales</TD>
	   <TD>$totalcost</TD>
	   <TD>$unitprofit</TD>
	   <TD>$totalprofit</TD>
	   </TR>";
       $sumuprofit += $unitprofit;
       $sumtprofit += $totalprofit;
	   $sumqnty += $qnty;
	   $sumTcost += $totalcost;
	   $sumTsales += $totalsales;
	   $sumUcost += $ucost;
	   $sumSprice += $sprice;
     }
	 

     $totsumUP=number_format($sumuprofit,2);
     $totsumTP= number_format($sumtprofit,2);
     echo "
	 </tbody><tfoot>
	 <TR>
	 <TH>&nbsp;</TH>
	 <TH>&nbsp;</TH>
	 <TH>TOTAL</TH>
	 <TH>$sumqnty</TH>
	 <TH>$sumUcost</TH>
	 <TH>$sumSprice</TH>
	 <TH>$sumTsales</TH>
	 <TH>$sumTcost</TH>
	 <TH>".@$totsumUP ."</TH>
	 <TH>".@$totsumTP." &nbsp;</TH>
	 </TR> </tfoot>";
 }
#################################################################################################################################

 else if ($cmbReport == "Requisition Report")
 {

  if (trim($cmbTable)=="Daily")
  {
	 @$val="`Stock Date`='" . date('Y-d-m') . "'"; 
  }
  else if (trim($cmbTable)=="Weekly")
  {
	@$val="`Stock Date`>'" . date('Y-d-m', strtotime('-3 month')) . "' and `Stock Date`<'" . date('Y-d-m', strtotime('+1 day')) . "'";  
   //@$val="`Stock Date`>'" . date('Y-d-m', strtotime('-1 week')) . "' and `Stock Date`<'" . date('Y-d-m', strtotime('+1 day')) . "'";
  }
  else if (trim($cmbTable)=="Monthly")
  {
	@$val="`Stock Date`>'" . date('Y-d-m', strtotime('-3 month')) . "' and `Stock Date`<'" . date('Y-d-m', strtotime('+1 day')) . "'";  
   //@$val="`Stock Date`>'" . date('Y-d-m', strtotime('-1 month')) . "' and `Stock Date`<'" . date('Y-d-m', strtotime('+1 day')) . "'";
  }
  else if (trim($cmbTable)=="Quarterly")
  {
   @$val="`Stock Date`>'" . date('Y-d-m', strtotime('-3 month')) . "' and `Stock Date`<'" . date('Y-d-m', strtotime('+1 day')) . "'";
  }
  else if (trim($cmbTable)=="Yearly")
  {
   @$val="`Stock Date`>'" . date('Y-d-m', strtotime('-12 month')) . "' and `Stock Date`<'" . date('Y-d-m', strtotime('+1 day')) . "'";
  }

   echo "
   <thead>
   <TR>
   <TH>Stock Code</TH>
   <TH>Stock Name</TH>
   <TH>Category </TH>
   <TH>Requisition Date</TH>
   <TH>Qnty Given</TH>
   <TH>Given To</TH>
   <TH>Location </TH>
   </TR>
   </thead><tbody>";
 
   @$result = mysql_query ("SELECT `Stock Code`, `Stock Name`,`Category`,`Stock Date`, `Qnty Given`, `Request By`, `Location` From `requisition` where " . $val . " order by `Stock Code`"); 


    while(list($stockcode,$stockname,$weight,$reqdate,$qntygiven,$reqby,$loc)=mysql_fetch_row(@$result)) 
    {	
      echo "<TR>
	  <TD>$stockcode </TD>
	  <TD>$stockname</TD>
	  <TD>$weight</TD>
	  <TD>$reqdate</TD>
	  <TD>$qntygiven</TD>
	  <TD>$reqby</TD>
	  <TD>$loc</TD>
	  </TR>";
    }
	echo "</tbody>";
 }
 ############################################################################################################################
 else if ($cmbReport == "Re-Stock Report")
 {
@$filter=$_REQUEST["filter"];

  if (trim($cmbTable)=="Daily")
  {
	@$val="`Stock Date`='" . date('Y-m-d',time()) . "'"; 	  
  }
  else if (trim($cmbTable)=="Weekly")
  {
   @$val="date(`Stock Date`)>'" . date('Y-d-m', strtotime('-1 week')) . "' and date(`Stock Date`)<'" . date('Y-d-m', strtotime('+1 day')) . "'";
  }
  else if (trim($cmbTable)=="Monthly")
  {
   @$val="date(`Stock Date`)>'" . date('Y-m-d', strtotime('-1 month')) . "' and date(`Stock Date`)<'" . date('Y-m-d', strtotime('+1 day')) . "'";
  }
  else if (trim($cmbTable)=="Quarterly")
  {
   @$val="date(`Stock Date`)>'" . date('Y-m-d', strtotime('-3 month')) . "' and date(`Stock Date`)<'" . date('Y-m-d', strtotime('+1 day')) . "'";
  }
  else if (trim($cmbTable)=="Yearly")
  {
   @$val="`Stock Date`>'" . date('Y-d-m', strtotime('-12 month')) . "' and `Stock Date`<'" . date('Y-d-m', strtotime('+1 day')) . "'";
  }

   echo "
   <thead>
   <TR>
   <TH>Stock Code</TH>
   <TH>Stock Name</TH>
   <TH>Category</TH>
   <TH>Re-Stock Date</TH>
   <TH>Qnty Acquired</TH>
   <TH>Unit Cost</TH>
   <TH>Total Cost</TH>
   <TH>Location</TH>
   </TR>
   </thead><tbody>";
 
  $result = mysql_query ("SELECT `Stock Code`, `Stock Name`,`Category`,`Stock Date`, `Qnty Added`, `Unit Cost`, `Location` From `restock` where " . $val . " order by `Stock Code`") or die(mysql_error()); 


    while(list($stockcode,$stockname,$colour,$restockdate,$qntyadded,$unitcost,$loc)=mysql_fetch_row($result)) 
    {	$totv=$unitcost*$qntyadded;
      echo "<TR>
	  <TD>$stockcode </TD>
	  <TD>$stockname</TD>
	  <TD>$colour</TD>
	  <TD>$restockdate</TD>
	  <TD>$qntyadded</TD>
	  <TD>$unitcost</TD>
	  <TD>$totv</TD>
	  <TD>$loc</TD>
	  </TR>";
    }
 }
 else if ($cmbReport == "Stock Report")
 {
  
 if (trim($cmbTable)=="Weekly")
  {
   @$val="date(`Stock Date`)>'" . date('Y-m-d', strtotime('-1 week')) . "' and date(`Stock Date`)<'" . date('Y-m-d', strtotime('+1 day')) . "'";
  }
  else if (trim($cmbTable)=="Monthly")
  {
   @$val="date(`Stock Date`)>'" . date('Y-m-d', strtotime('-1 month')) . "' and date(`Stock Date`)<'" . date('Y-m-d', strtotime('+1 day')) . "'";
  }
  else if (trim($cmbTable)=="Quarterly")
  {
   @$val="date(`Stock Date`)>'" . date('Y-m-d', strtotime('-3 month')) . "' and date(`Stock Date`)<'" . date('Y-m-d', strtotime('+1 day')) . "'";
  }
  else if (trim($cmbTable)=="Yearly")
  {
   @$val="date(`Stock Date`)=" . date('Y');
  }

   echo "
   <thead>
   <TR>
   <TH>Stock Code</TH>
   <TH>Stock Name</TH>
   <TH>Unit Price</TH>
   <TH>Unit in Stock</TH>
   <TH>Re-Order Level</TH>
   <TH>Category</TH>
   <TH>Location</TH>
   </TR>
   </thead><tbody>";

   $result = mysql_query ("SELECT stock.`Stock Code`, `stock`.`stock Name`,`stock`.`category`,stock.`Units in Stock` , stock.`Location` ,  `Selling Price` , `Reorder Level` FROM `stock` GROUP BY stock.`Stock Code`");

    while(list($stockcode,$name,$colour,$unit,$loc,$price,$reorder)=mysql_fetch_row($result)) 
    {
      echo "
	  <TR>
	  <TD>$stockcode</TD>
	  <TD>$name</TD>
	  <TD>$price</TD>
	  <TD>$unit</TD>
	  <TD>$reorder</TD>
	  <TD>$colour</TD>
	  <TD>$loc</TD>
	  </TR>";
    }
echo "</tbody>";
 }
 ##########################################################################################################################
 else if ($cmbReport == "Non-Moving Stock Report")
 {
  if (trim($cmbTable)=="Daily")
  {
   @$val="`Sales Date`=date('$filter')";
  }
  else if (trim($cmbTable)=="Weekly")
  {
   @$val="date(`Sales Date`)=" . date('W');
  }
  else if (trim($cmbTable)=="Monthly")
  {
   @$val="date(`Sales Date`)=" . date('m');
  }
  else if (trim($cmbTable)=="Yearly")
  {
   @$val="date(`Sales Date`)=" . date('Y');
  }
   echo "<TR bgcolor='#006699'><TH><b> Stock Code </b>&nbsp;</TH><TH><b> Stock Name </b>&nbsp;</TH><TH><b> Sales Date </b>&nbsp;</TH><TH><b> Qnty Sold </b>&nbsp;</TH><TH><b> Unit Cost </b>&nbsp;</TH><TH><b> Total Cost </b>&nbsp;</TH><TH><b> Paid </b>&nbsp;</TH></TR>";
 
   $result = mysql_query ("SELECT `Stock Code`, `Stock Name`,`Sales Date`, `Qnty Sold`, `Unit Cost`, `Total Cost`, `Paid` From `sales` where " . @$val . " order by `Stock Code`"); 

   if(mysql_num_rows($result) == 0)
   { 
        echo("<span class='font'>Nothing to Display!</span><br>"); 
   } 
    @$val='Daily';

    while(list($stockcode,$stockname,$salesdate,$qntysold,$unitcost,$totalcost,$paid)=mysql_fetch_row($result)) 
    {	
      echo "<TR><TD>$stockcode </TD><TD>$stockname</TD><TD>$salesdate</TD><TD>$qntysold</TD><TD>$unitcost</TD><TD>$totalcost</TD><TD>$paid</TD></TR>";
    }

 }

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