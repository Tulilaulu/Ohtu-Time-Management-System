<?php
header('Content-Type: text/html; charset=utf8');
include_once "askname.php";
include_once "ProjectFile.php";
$index = 1;
$file = file(PROJECTDIR.basename($_GET['file']));
$filename = substr($_GET[file], 7);
header("Content-Disposition: attachment; filename='$filename'");
foreach ($file as $row){
    if ($index<3) {
        echo "$row";
    }
    else {
        $index++;
        list($date,$group,$sprint,$minutes,$description) = explode("\t", trim($row));
	    $date = date('d.m.Y', strtotime($date));
	    $hours = 2*(round(2*($minutes/60)));
	    $description = substr($description, 0, 50);
	    echo "$date\t$group\t$hours\t$description\n";
    }
}
?>
