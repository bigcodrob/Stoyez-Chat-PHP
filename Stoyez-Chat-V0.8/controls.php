<?php
	session_start();

	include('settings.php');
	include('version.php');
	
	$username = $_SESSION['username'];
	$mysqli = mysqli_connect($host, $dbuser, $dbpass, $dbname);
	
	$result = $mysqli->query("SELECT * FROM members WHERE nickname='$username'") or die($mysqli->error());
	
	$level = $result->fetch_assoc();
		
	$userLevel = (int)$level['level'];

if ($_SESSION['username'] == null) {
	header('Location: login.php');
} 

?>

<html>
<head>
	<link rel = "stylesheet" type = "text/css" href = "css/view_stylesheet.css" />
	<?php echo 	'<title>'.$chatname?>
	<?php echo " - Controls </title>"; ?>
</head>
<body>
	<div class="controls_parent">
		<div class="controls_objects">
			<?php
				if($userLevel == 8) {
					echo "<form method='post' action='admin.php'>
							<button id='admin' type='submit' formtarget='view'>Admin</button>
						</form>";
				}
			?>
			<form method="post" action="logout.php">
				<button id="exit" type="submit" formtarget="_parent">Exit Chat</button>
			</form>
		</div>
		<div class="version">
			<p><?php echo $version;?></p>
		</div>
	</div>
</body>
</html>