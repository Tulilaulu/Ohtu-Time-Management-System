<?php
header('Content-Type: text/html; charset=utf8');
include_once "askname.php";
include_once "ProjectFile.php";

$project = null;
$delete = false;
if (isset($_GET['project'])) $project = $_GET['project'];
if (isset($_POST['project'])) $project = urldecode($_POST['project']);
if (isset($_POST['newperson']) && !empty($_POST['newperson'])) $project = $_POST['newperson'];
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
    $project = $_GET['delete'];
    $delete = true;
}
//delete mark stuff...
if (isset($_POST['info'])){
    $info = htmlspecialchars($_POST['info']);
    $contents = array();
    if ($_POST['info']!=null) {
        $myfile = 'people/'.$project.'.txt';
        $fh = fopen($myfile, 'rt') or die("");
        if ($fh){
            while(!feof($fh)){
                $stuff = fgets($fh);
                if (is_string($stuff)) array_push($contents, $stuff);
            }
            fclose($fh);
        }
    }
    for($i = 0; $i < count($contents); $i++){
        if(rtrim($contents[$i]) == rtrim($info)) $contents[$i] = '';
    }        
    $file_write = fopen($myfile, 'wt');       
    if($file_write){
        fwrite($file_write, implode("", $contents));
        fclose($file_write);
    }
}

if (!$project) {
	header('Location: index.php');
	die();
}

//Filename generation comes here
$file = new ProjectFile($project);
$projecturl = urlencode($project);
if($delete) {
    $file->delete();
}
if(!$project || $delete) {
    header('Location: index.php');
    die();
}
if (isset($_GET['startwork']) ) {
    $file->startWork();
}
if (isset($_GET['stopwork'])) {
    $file->endWork();
}

if (isset($_POST['minuutit'])) {
	$date = $_POST['pvm'];
	$time = $_POST['minuutit'];
	$descr = $_POST['kuvaus'];
	$task = $_POST['task'];
	$sprint = $_POST['sprint'];
    $file->addMark($time, $date, $descr, $task, $sprint);
}


 ?><html>
<head>
<title>Ohtu Time Management System</title>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.0/jquery.min.js"></script>
<script type="text/javascript" src="kirjuri.js"></script>
<link href="kirjuri.css" rel="stylesheet" type="text/css" />
<head>
<body>
<?php include_once('vasen.php');?>

<div class="projektit">

<h1><?php echo $project; ?></h1>
<h3>Work hours</h3> 
export as <a href="text.php?file=<?php echo $file->getFilename(); ?>">.txt</a> (compatible with <a href="http://tkt_ohtu.users.cs.helsinki.fi/">this</a>) or as <a href="csv.php?file=<?php echo $file->getFilename(); ?>">.csv</a>

<pre>
<?php 
$days = $file->getLinesGroupedByDay();
$line = "";
foreach($days as $date => $day) {
	foreach($day['lines'] as $line) {
        if (substr($line, 0, 1) == '#'){
            echo $line."\n";
        }
        else {
    		echo $line."&nbsp;&nbsp;<form style='display:inline;' id='deleteline' method='post' action='project.php?project=$project'><input type='hidden' name='info' value='$line'><input type='submit' value='delete row'></form>\n";
        }
	}
	if ($date != 'header') echo "---\n";
	if (isset($day['count']) && $day['count'] > 1) {
		$total = ProjectFile::formatTime($day['total'], 0);
		echo "$date total: \t$total\n---\n";
	}
}

?>

<?php $totalminutes = $file->getTotalTime(); ?>
Total <?php echo $totalminutes; ?> minute(s) <?php
$total = ProjectFile::formatTime($totalminutes);
echo " ($total)";

?>
</pre>
</div>
</body>
</html>
