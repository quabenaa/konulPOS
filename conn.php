<?php
define('SQL_HOST','localhost');
define('SQL_USER','root');
define('SQL_PASS','!smartview13#');
define('SQL_DB','_store');
$conn = mysql_connect(SQL_HOST,SQL_USER,SQL_PASS)
or die('Could not connect to the database; ' . mysql_error());
mysql_select_db(SQL_DB,$conn)
or die('Could not select database; ' . mysql_error());

//VALIDATE !smartview13#
$sql="SELECT * FROM `sysinfo`";
$result = mysql_query($sql,$conn) or die('Could not look up user data; ' . mysql_error());
if(mysql_num_rows($result)>0){
$row = mysql_fetch_array($result) or die('Could not look up user data; ' . mysql_error());
$company_name=$row['developer'];  
if($company_name != "SmartView Technology")
{
echo "<h1>Pirated Software!!... This software is not licenced to you please!</h1>";
exit;
}
}else{
echo "<h1>Pirated Software!!... This software is not licenced to you please!</h1>";	
}
?>