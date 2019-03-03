<?php	
	include('settings.php');
	
	session_start();
	
	//establish database connection
	$mysqli = mysqli_connect($host, $dbuser, $dbpass, $dbname);
	
	$result = $mysqli->query("SELECT * FROM messages") or die($mysqli->error());
	
	$username = $_SESSION['username'];
	
	if(isset($_POST['submit'])) {
		if(empty($_POST['text'])) {
			header("Refresh: 0");
		} else {
			$mysqli2 = mysqli_connect($host, $dbuser, $dbpass, $dbname);
		
			$text = $_POST['text'];
			$textCleaned = $mysqli2->escape_string($_POST['text']);
			
			$send_to = $_POST['sent_to'];
		
			$date = date("Y-m-d H:i:s");
		
			$result2 = "INSERT INTO messages(poster, text, postdate, poststatus) VALUES ('$username', '$textCleaned', '$date', '$send_to')";
		
			mysqli_query($mysqli2, $result2);
		
			mysqli_close($mysqli2);
			$_SESSION['last_time'] = time();
			header('Refresh: 0');
		}
	} 
	
	//Get Account Level 
	
	$mysqli = mysqli_connect($host, $dbuser, $dbpass, $dbname);
	
	$getLevel = $mysqli->query("SELECT * FROM members WHERE nickname='$username'") or die($mysqli->error());
	
	$level = $getLevel->fetch_assoc();
		
	$userLevel = (int)$level['level'];

?>

<html>
<head>
	<link rel = "stylesheet" type = "text/css" href = "css/view_stylesheet.css" />
	<?php echo 	'<title>'.$chatname?>
	<?php echo " - Post </title>"; ?>

</head>
<body>
	<div class="post_parent">
			<form id="textToTalk" action="post.php" method="post" target="_self">
				<div class="post_objects">
					<label for="username"><b><?php echo $username . " :";?></b></label>
					<input type="text" placeholder="" name="text" id="username">
					<button type="submit" name="submit" id="submit">Send To</button>
					<select name="sent_to" style="width: 120px; text-align: center; background-color: black; color: white;">
						<option value="1">-Everyone-</option>
						<?php 
						if($userLevel >= 2) {
							echo "<option value='2'>-Members-</option>";
						}
						?>
						<?php 
						if($userLevel >= 3) {
							echo "<option value='3'>-Mod-</option>";
						}
						?>
						<?php 
						if($userLevel >= 4) {
							echo "<option value='4'>-Admin-</option>";
						}
						?>
					</select> 
				</div>
					<!--<?php $date = date("Y-m-d H:i:s"); echo "Current Time: " . $date;?>-->
			</form>
	</div>