<?php
header('Content-Type: text/html; charset=utf8');
include_once "askname.php";
include_once "ProjectFile.php";
define("CHARTDIR", "charts/");
$sprint = $days = $bananas = 0;
if (isset($_POST['new'])){
	$sprint = $_POST['number'];
	$days = $_POST['days'];
	$bananas = $_POST['bananas'];
	$start = strtotime($_POST['start']);
	$sprint = urlencode($sprint);
        $sprint = str_replace('.', '%.' , $sprint);
	
        $filename= CHARTDIR.$sprint.'.txt';
        $startFilename = CHARTDIR.$sprint.'.start';
        if (!file_exists($filename)) {
            file_put_contents($filename, "$sprint\n$days\n$bananas\n$start");
        }
}
if (isset($_GET['chart'])){
	$sprint=$_GET['chart'];
	$contents=explode("\n",file_get_contents("charts/".$sprint.".txt"));
	if (count($contents>3)){
		$sprint=$contents[0];
		$days=$contents[1];
		$bananas=$contents[2];
		$start=$contents[3];
	}
}
if (isset($_GET['delete'])) {
	$deletable=CHARTDIR.$_GET['delete'].".txt";
	if(file_exists($deletable)){
		unlink($deletable);
	}
	header('Location: burndownchart.php');
	die();
}
 ?><html>
<head>
<title>Ohtu Time Management System</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js"></script>
<script type="text/javascript" src="kirjuri.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
	var days = <?php echo $days; ?>;
	var bananas = <?php echo $bananas; ?>;
	var bananaValues = [<?php echo implode(",", ProjectFile::getSprint($sprint)); ?>];
	for (var b in bananaValues){
		bananaValues[b] = (bananaValues[b]/(4*60));
	}
	var ourBananas = bananas;
	var start = new Date (<?php echo $start; ?>*1000);
        data.addColumn('string', 'Days');
        data.addColumn('number', 'Optimal');
        data.addColumn('number', 'You');
        data.addRows(days);
	var i=0;
	var optimal = 0;
	while (i < days){
		optimal = bananas - bananas * i/(days-1);
		data.setValue(i, 0, start.getDate()+"."+(start.getMonth()+1)); 
	        data.setValue(i, 1, optimal);
	        data.setValue(i, 2, ourBananas);
		i++;
		ourBananas = ourBananas - bananaValues[i];
		start.setDate(start.getDate()+1);
	}

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, {width: 900, height: 440, title: 'Burndown of sprint <?php echo $sprint; ?>'});
      }
    </script>
<link href="kirjuri.css" rel="stylesheet" type="text/css" />
<head>
<body>
<?php include_once('vasen.php');?>

<div class="projektit">

<div id="chart_div"></div>




</div>
</body>
</html>
