<?php
//name this conn.php
$host = 'courses';
$user = 'z1754862';
$password='1994Jul30';
$db = 'z1754862';
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
$conn = new PDO("mysql:host=$host;dbname=$db",$user,$password);
try
{
  	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 	   
	$conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
}
catch(PDOException $e)
{
  echo 'ERROR: ' . $e->getMessage();
}
?>
