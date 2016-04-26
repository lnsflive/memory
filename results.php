<style type="text/css">
body{
font-family:arial;
background:black;
}
table, th, td
{
background:black;
color:white;
text-align: center;
margin-left: auto;
margin-right: auto;
}

td{
border: 1px solid white;
}
</style>

<?php


// connect to database
$con = mysql_connect("localhost","root","")or die("cannot connect to server");
mysql_select_db("leaderboards1")or die("cannot select DB");


mysql_select_db("leaderboards1") or die ("DB not found");

$query = "SELECT * FROM highscores ORDER by Score DESC LIMIT 30";

$result = mysql_query($query);
$num_results = mysql_num_rows($result); 

echo "<table style='width: 600px'>";
echo "<th colspan='4' style='padding-bottom:10px;'>High Scores - Top 30</th>";
echo "<tr><td>Name</td><td>Time</td><td>Moves</td><td>Score</td></tr>";
for($i = 0; $i < $num_results; $i++)
    {
         $row = mysql_fetch_array($result);
         // the echo command is used as the returned value for our Unity Script
         echo "<tr><td>" . $row['name']. "</td><td>"  .  $row['time'] . "</td><td>"  . $row['moves'] . "</td><td>"    . $row['score'] . "</td></tr>";
    }
	
mysql_close($con);
echo "<td colspan='4'><form method='post' action='game.html'><input type='submit' value='Play Again' style='width:100%;  height: 3em;'></form></td>";
echo "</table>";
?>
