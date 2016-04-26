<?php

$host="localhost";//hostname
$username="root";//username
$password="";//password
$db_name="leaderboards1";//database name

// connect to database
mysql_connect("$host","$username","$password")or die("cannot connect to server");
mysql_select_db("leaderboards1")or die("cannot select DB");

$con = mysql_connect("localhost","root","");
if (!$con){
die("Can not connect: " . mysql_error());
}

mysql_select_db("leaderboards1",$con);

$sql = "INSERT INTO highscores (Name, moves, time, score) VALUES ('$_GET[name]','$_GET[moves]','$_GET[time]','$_GET[score]')";

mysql_query($sql,$con);

mysql_close($con);

header( 'Location: results.php?status=success') ;

?>