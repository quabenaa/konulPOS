<?php
require_once 'conn.php';
if ( !isset($_REQUEST['term']) )
    exit;

$rs = mysql_query('SELECT `cust_numb`,`name` FROM `customers` 
WHERE (`name` like "'. mysql_real_escape_string($_REQUEST['term']) .'%" or `cust_numb` like "'. mysql_real_escape_string($_REQUEST['term']) .'%")
AND status = 1 order by `name` asc limit 0,10');

$data = array();
if ( $rs && mysql_num_rows($rs) )
{
    while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
    {
        $data[] = array(
            'label' => $row['cust_numb'].' - '.$row['name'] ,
            'value' =>  $row['name'].' - '. $row['cust_numb']
        );
    }
}

echo json_encode($data);
flush();

