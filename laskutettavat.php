<?php
header('Content-Type: text/html; charset=utf8');
include_once "ProjectFile.php";

?><html>
<head>
<title>Työtuntikirjuri</title>
<head>
<body>
<div>
<h1>Laskuttamattomia projekteja</h1>
</div>
<ul>
<?php	
	$totaltime = 0;
    foreach(ProjectFile::getProjects('valmiit') as $file) {
        
        $name = $file->getName();
		$time = round($file->getTotaltime()/60);
		$totaltime += $time;

		$time = $time . ' tunti' ;
		if ($time != 1) $time .= 'a';

        echo "<li>$name
		(yht. $time)
		</li>\n";
    }
?>
</ul>
<p>
Työtä laskuttamatta yhteensä <?php echo $totaltime; ?> tuntia
</p>
</body>
</html>
