<?php
header('Content-Type: text/html; charset=utf8');
include_once "askname.php";
include_once "ProjectFile.php";
define("CHARTDIR", "charts/");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html>
<head>
<title>Ohtu Time Management System</title>
<link href="kirjuri.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js"></script>
<script type="text/javascript" src="kirjuri.js"></script>
<head>
<body>
<?php include_once "vasen.php";
echo "<div class='projektit'>";
echo "<h1>Burn down charts</h1><ul class='index'>";
if (count(scandir(CHARTDIR)) == 2){
	echo "No charts available yet";
}
else {
	foreach (scandir(CHARTDIR) as $file){
		if (substr($file, -4) !== '.txt') continue;
		$file = substr($file, 0, -4);
		echo "<li><a href='chart.php?chart=$file'>Sprint $file</a> (<a href='chart.php?delete=$file' onclick='return confirm(\"Are you sure you want to delete this chart?\");'>delete</a>)</li>";
	}
}
?>
</ul>
<h3>Start a new sprint</h3>
<form method="POST" action="chart.php">
<label>Number of sprint:</label>
<input type="text" name="number"/><br/>
<label>How many days:</label>
<input type="text" name="days"/><br/>
<label>How many workdays (4h):</label>
<input type="text" name="bananas"/><br/>
<label>The starting date of the sprint:</label>
<input type="text" name="start"/><br/>
<input type="submit" value="Start" name="new"/></form>
</div>
</body>
</html>
