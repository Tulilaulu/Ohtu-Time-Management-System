<?php
define("PROJECTDIR", 'people/');

class ProjectFile {
    
    private $name, $encodedname;
    private $filename;
    private $startFilename;
    private $contents;
	
    public static function getProjects($dir = '') {
        $projects = array();
        foreach(scandir(PROJECTDIR.$dir) as $file) {
            if (substr($file, -4) !== '.txt') continue;
            $file = substr($file, 0, -4);
            $clearfile = str_replace('%.', '.' , $file);
            $clearfile = urldecode($clearfile);
            $projects[] = new self($clearfile, $dir);
        }
        return $projects;
    }
    
    public static function getSprint($sprint){
        $minutes = array();
        foreach(ProjectFile::getProjects() as $file) {
            foreach(explode("\n", $file->contents) as $line) {
                $line = explode("\t", $line);
                if (count($line)<2) continue;

                list($day, $task, $currentsprint, $currentminutes, $descr) = $line;
                if ($currentsprint==$sprint){
                    $minutes[$day]= (isset($minutes[$day]) ? $minutes[$day] : 0) + $currentminutes;
                }
            }
        }
        return $minutes;
    }

    public function __construct($name) {
        $this->name = $name; 
        $name = urlencode($name);
        $name = str_replace('.', '%.' , $name);
		
        $this->filename= PROJECTDIR.$name.'.txt';
        $this->startFilename = PROJECTDIR.$name.'.start';
        if (!file_exists($this->filename)) {
            file_put_contents($this->filename, $this->name."\n\n#DATE\t\tTASK\tSPRINT\tMINUTES\tEXPLANATION");
        }
        $this->contents = file_get_contents($this->filename);
    }
    public function delete() {
        unlink($this->filename);
        @unlink($this->startFilename);
    }
    public function getName() {
        return $this->name;
    }
    public function getFilename() {
        return $this->filename;
    }

    public function startWork() {
        if (!file_exists($this->startFilename)) {
            touch($this->startFilename);
        }
    }
    public function endWork() {

        @unlink($this->startFilename);
    }
    public function addMark($minutes, $date, $description, $task, $sprint) {
      if (!strptime($date , '%e.%m.%Y')) {
        throw new Exception('Date in wrong format.');
      }
      $description = str_replace(array("\n", "\t"), ' ', $description);
      $this->contents .= "\n$date\t$task\t$sprint\t$minutes\t$description";
      file_put_contents($this->filename, $this->contents);
      $this->endWork();
    }


    public function getTotalTime() {    
        $total = 0;
        foreach(explode("\n", $this->contents) as $line) {
            $line = explode("\t", $line);
	    if (count($line) < 4) continue;
            $minute = $line[3];
            $total += $minute;
        }
        return $total;

    }
    public function getStartTime() {
        if (file_exists($this->startFilename)) {
            return filemtime($this->startFilename);
        } else return 0;
    }

    public function __toString() {
        return $this->contents;
    }

    public function getLinesGroupedByDay() {
        $days = array('header' => array());
        foreach(explode("\n", $this->contents) as $line) {
            $line_exp = explode("\t", $line);
	    if (count($line_exp)<4) continue;
            $minute = $line_exp[3];
            $day = $line_exp[0];
	    if($minute > 0) {
	  	    if (!isset($days[$day])) $days[$day] = array('lines' => array(), 'count' => 0, 'total' => 0);		
		    $days[$day]['lines'][] = $line;
		    $days[$day]['count']++;
		    $days[$day]['total'] += $minute;
	    } else { $days['header']['lines'][] = $line; }
        }
        return $days;
    }

    public static function formatTime($minutes) {
        $m = $minutes%60;
        $h = ($minutes-$m)/60;
        return "$h hour(s) $m minute(s)";
    }
}

?>
