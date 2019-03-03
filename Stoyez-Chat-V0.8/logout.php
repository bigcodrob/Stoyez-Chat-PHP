<html>
<head>
	<title></title>
	<link rel = "stylesheet" type = "text/css" href = "css/logout_stylesheet.css" />
</head>
<body>
	<?php
	include ('settings.php');
	
	session_start();
	
	if ( isset($_SESSION) && !isset($_SESSION['username']) ) {
		echo "<p>You are already Logged out <br><br> Redirecting to login</p>";
		header('Refresh: 5; login.php');
		echo "<div class='my_content_container'>
				<a href='login.php'>Return to Login</a>
			</div>";
	} else {
		
		//Database delete user if users rank is below 2.
		$username = $_SESSION['username'];
		
		$mysqli = mysqli_connect($host, $dbuser, $dbpass, $dbname);

		$sql = "UPDATE members SET status='0' WHERE nickname='$username'";
		
		$result = "DELETE FROM members WHERE nickname='$username'";
		
		
		//Check User level to see if its too high for deletion
		$mysqli = mysqli_connect($host, $dbuser, $dbpass, $dbname);
	
		$levelMysql= $mysqli->query("SELECT * FROM members WHERE nickname='$username'") or die($mysqli->error());
		
		$levelRaw = $levelMysql->fetch_assoc();
			
		$userLevel = (int)$levelRaw['level'];
		
		//Run Above mysql querys.
		if($mysqli->query($sql) === TRUE) {
			if($userLevel < 2) {
				if($mysqli->query($result) === TRUE) {
					echo "<p>Bye " .$_SESSION['username']. ", visit again soon!</p>";
					session_destroy();
					echo "<div class='my_content_container'><a href='login.php'>Return to Login</a></div>";				
				}		
			} else if($userLevel >= 2) {
				echo "<p>Bye " .$_SESSION['username']. ", visit again soon!</p>";
				session_destroy();
				echo "<div class='my_content_container'><a href='login.php'>Return to Login</a></div>";		
			}
		}
	}
	?>
</body>
</html>
