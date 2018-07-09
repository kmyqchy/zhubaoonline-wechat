<?php

$con = mysql_connect ( "localhost", "gemsunet_gemsu", "su4jew" );
$a = mysql_select_db ( "gemsunet_wechat", $con );
mysql_query ( "set names utf8" );

/*function sql_select($sql_name)
{
	$sql=mysql_query("SELECT * FROM '$sql_name' WHERE username='$fromUsername'");
	return $sql;
}

function sql_insert($sql_name)
{
	$sql = mysql_query ("INSERT INTO '$sql_name' (`username`)VALUES ('$fromUsername')");
	return $sql;
} 

function sql_update($clock,$word,$zhi)
{
	$sql = mysql_query ( "UPDATE '$clock' SET '$word'='$zhi' WHERE username='$fromUsername'" );
}*/
?>
