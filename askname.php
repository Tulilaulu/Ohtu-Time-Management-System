<?php
$passwd = 'derp'; //Set the password for the project here
session_set_cookie_params(36000, '/aikakirjuri');
session_start();
session_cache_expire(60*60*24);

	if (isset($_GET['logout'])) {
	$_SESSION['login'] = false;
	} else if (isset($_POST['word']) && strtolower($_POST['word']) == $passwd) {
	$_SESSION['login'] = true;
	}
if (isset($_SESSION['login'])){
	if ($_SESSION['login']!=null){
		define("LOGIN", $_SESSION['login']);
	}
}
session_write_close();

if (!defined("LOGIN")) :
?>
<html>
<head><title>Ohtu Time Management System</title><head>
<body>
<form action="index.php" method="post">
<h3>You are not logged in!</h3>
<div>Password:</div>
<div>
<input type="password" name="word" />
<input type="submit" value="ok" /></div>
</form>
</body>
</html>
<?php
die();
endif;
?>
