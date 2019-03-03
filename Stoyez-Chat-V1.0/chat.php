<?php
	include('settings.php');
	session_start();

	if(isset($_SESSION['username'])) {
		if((time() - $_SESSION['last_time']) > 600){      //destroys session after ADMIN SET VALUE OR DEFAULT VALUE OF 600 SECONDS(10MINUES).
			$_SESSION['message'] = '<p>Error: Invalid/expired session</p>';
			header('Location: error.php');
		} else{
			$_SESSION['last_time'] = time();
		}
	} else {
		header("Location: login.php");
	}
	
?>
<html>
<head>
	<link rel = "stylesheet" type = "text/css" href = "css/chat_stylesheet.css" />
	<?php echo 	'<title>'.$chatname. ' - Chat </title>'; ?>
	<meta charset="UTF-8" http-equiv="refresh" content="900;url=chat.php">
</head>
<frameset rows="100px, *, 62px" framespacing="3" border="3" frameborder="3" >
	<frame src="post.php" scrolling="no" >
	<frame src="view.php" name="view" >
	<frame src="controls.php" scrolling="no" >
</frameset>
</html>