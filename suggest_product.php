<?php
require_once 'conn.php';
if ( !isset($_REQUEST['term']) )
    exit;

$rs = mysql_query('SELECT `Stock Code`,`Stock Name` FROM `stock` WHERE `Stock Name` like "'. mysql_real_escape_string($_REQUEST['term']) .'%" order by `Stock Name` asc limit 0,10');

$data = array();
if ( $rs && mysql_num_rows($rs) )
{
    while( $row = mysql_fetch_array($rs, MYSQL_ASSOC) )
    {
        $data[] = array(
            'label' => $row['Stock Name'] ,
            'value' =>  $row['Stock Name'] 
        );
    }
}

echo json_encode($data);
flush();

