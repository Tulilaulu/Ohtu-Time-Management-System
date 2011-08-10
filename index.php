<?php
header('Content-Type: text/html; charset=utf8');
include_once "askname.php";
include_once "ProjectFile.php";

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html>
<head>
<title>Ohtu Time Management System</title>
<link href="kirjuri.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js"></script>
<script type="text/javascript" src="kirjuri.js"></script>
<head>
<body>

<?php include_once "vasen.php"; ?>

</div>
<div class="projektit" >
<br/>
<h1>People on the project:</h1>
<ul class="index">
<?php	
	$totaltime = 0;
    if (ProjectFile::getProjects()==null){
	echo "No people added";
    }
    else {
         foreach(ProjectFile::getProjects() as $file) {
        
             $name = $file->getName();
	    	$time = round($file->getTotaltime()/60);
		$totaltime += $time;

		$time = $time . ' hour' ;
		if ($time != 1) $time .= 's';

             echo "<li><a href='project.php?project=$name'>$name</a>
		(yht. $time)
		</li>\n";
    }
    }
?>
</ul>
<p>
Total work <?php echo $totaltime; ?> hour(s)
</p>
<form action="project.php" method="POST">
<div class="luoprojekti" >
Add new person:
<input type="text" name="newperson" />
<input type="submit" value="Add" />
</div>
</form>
</div>
</body>
</html>
