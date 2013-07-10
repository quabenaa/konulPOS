<?php 
include('db.php');
include('functions.php');
$username=$_REQUEST['username']; 
$password=$_REQUEST['password'];
echo login($username,$password);
?>