<?php
header('Content-Type: text/csv; charset=utf8');
include_once "askname.php";
include_once "ProjectFile.php";
$file = file(PROJECTDIR.basename($_GET['file']));
$filename = substr($_GET['file'], 7, -4).".csv";
header("Content-Disposition: attachment; filename='$filename'");
$index = 1;
foreach ($file as $row){
    if ($index == 3){
        echo "#DATE,TASK,SPRINT,MINUTES,EXPLANATION\n";
    }
    else {
        $row = str_replace(",", ".", $row);
	    echo str_replace("\t", ",", $row);
    }
    $index++;
}
?>
