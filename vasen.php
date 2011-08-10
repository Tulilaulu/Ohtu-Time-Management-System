<div style="float: right;">
	<a href="index.php?logout=true">Logout</a>
</div>
<div class="navi">
<?php
	echo "<h3>Navigation</h3><ul>";
	echo "<li><a href='index.php'>Front page</a></li>";
	$totaltime=0;
	foreach(ProjectFile::getProjects() as $f){
             $name = $f->getName();
	    	$time = round($f->getTotaltime()/60);
		$totaltime += $time;

		$time = $time . ' hour' ;
		if ($time != 1) $time .= 's';

		echo "<li><a href='project.php?project=$name'>$name</a> ($time)</li>\n";
	}
?>

<li><a href="burndownchart.php">Burn down charts</a></li>
<li><a href='issuetracker.php'>Issue tracker</a></li>
</ul><br/><br/>

<?php 
if (strpos($_SERVER['REQUEST_URI'], "/project.php") !== false):
?>
<div class="kansiot" >
	<h2>Management</h2>
	<a href="project.php?delete=<?php echo $projecturl; ?>"
  onclick="return confirm('Are you sure you want to delete this person?');" >
Delete person</a>

	<?php
	$minutes=0;
	if ($starttime = $file->getStartTime()) {
		$minutes = time() -$starttime;
		$minutes = (int)($minutes/60);
		echo "<div>Working started at <span id='workstarted'>".date('H:i j.n.Y',$starttime)."</span></div>\n";
		echo "<div>You have worked <span id='minutes'>$minutes</span> minuuttia </div>\n";
		echo "<div><a href='project.php?project=$projecturl&amp;stopwork=true'>Discard minutes</a></div>\n";
	} else {
		$hours = 1;
		echo "<div><a href='project.php?project=$projecturl&amp;startwork=true'>Start working</a></div>";
	}
	?>
	<form id="addwork" action="project.php?project=<?php echo $projecturl; ?>" method="post">
	<h3>Add workhours</h3>

     <table id="add"><tr><td colspan="2">

	<label>Date</label>
	<?php 
	$reportDate = time();
	?>
	<input size="8" type="text" name="pvm" value="<?php echo date('j.n.Y', $reportDate); ?>" />

     </td></tr><tr><td>

	<label>Minutes</label>
	<input size="8" type="text" id="minutesfield" name="minuutit" value="<?php echo $minutes; ?>" />
     </td><td>

	<label>Sprint #</label>
	<input size="4" type="text" id="sprint" name="sprint" value="" />
	
    </td></tr><tr><td colspan="2">

	<label>Task</label>
	<select name="task" id="task">
	  <option value="PROJ">PROJ : Project Planning</option>
	  <option value="VAAT">VAAT : Requirements Specification</option>
	  <option value="SUUN">SUUN : Planning</option>
	  <option value="TOTE">TOTE : Implementation</option>
	  <option value="TEST">TEST : Testing</option>
	  <option value="KOKO">KOKO : Meetings</option>
  	  <option value="TYOK">TYOK : Tools</option>
	  <option value="TUTU">TUTU : Researching the problem</option>
	  <option value="MUUT">MUUT : Others</option>
	  <option value="KÄLI">KÄLI : User Interface</option>
	</select>

    </td></tr><tr><td colspan="2">

	<label>Description</label>
	<textarea cols="20" rows="4" name="kuvaus" value=""></textarea>
	<input type="hidden" name="project" value="<?php echo $projecturl; ?>" />
	<input type="submit" value="ok" />

    </td></tr></table>

	</form>
</div>

<?php
endif;
?>

<br/><br/>

	<div class="copyt">
	&copy;Aurora Tulilaulu &amp; David Consuegra<br/>
	Lisenced under <a href="http://creativecommons.org/licenses/by-nc/3.0/">cc-by-nc</a>
	</div>
</div>
