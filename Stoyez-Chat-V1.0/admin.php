<?php
	session_start();

	include('settings.php');
	
	$username = $_SESSION['username'];
	
	//establish database connection
	$mysqli = mysqli_connect($host, $dbuser, $dbpass, $dbname);
	
	$result = $mysqli->query("SELECT * FROM members WHERE nickname='$username'") or die($mysqli->error());
	
	$level = $result->fetch_assoc();
	
	$userLevel = (int)$level['level'];



if ($_SESSION['username'] == null || $userLevel != 8) {
	header('Location: login.php');
}

	if(isset($_POST['admin'])) {
		echo "<p>Feature Coming soon...</p>";
	} 
	
	if(isset($_POST['register'])) {
		
		$guest = $_POST['register_guest'];
		
		$result = $mysqli->query("UPDATE members SET level='2' WHERE nickname='$guest'") or die($mysqli->error());
			
		$sql = $mysqli->query("SELECT * FROM members WHERE nickname='$guest'") or die($mysqli->error());
	
		$guestLevel = $sql->fetch_assoc();
	
		$guestUserLevel = (int)$guestLevel['level'];
		
		if($guestUserLevel == '2') {
			echo "Successfully Registered " .$guest;
		} else {
			echo "Registration Failed on.... " .$guest;
		}
	}
	
	if(isset($_POST['change'])) {
		$rank = $_POST['rank'];
		$member = $_POST['member'];
		
		if($rank == 1) {
			$result = $mysqli->query("UPDATE members SET level='1' WHERE nickname='$member'");
		}
		if($rank == '2') {
			$result = $mysqli->query("UPDATE members SET level='2' WHERE nickname='$member'");
		}
		if($rank == '3') {
			$result = $mysqli->query("UPDATE members SET level='3' WHERE nickname='$member'");
		}
		if($rank == '4') {
			$result = $mysqli->query("UPDATE members SET level='4' WHERE nickname='$member'");
		}
			
		$sql = $mysqli->query("SELECT * FROM members WHERE nickname='$member'") or die($mysqli->error());
	
		$guestLevel = $sql->fetch_assoc();
	
		$guestUserLevel = (int)$guestLevel['level'];
		
		//check if passes
		
		if($guestUserLevel == '1' && $rank == '1') {
			echo " Successfully switched ".$member." to Guest.";
		}else if($guestUserLevel == '2' && $rank == '2') {
			echo " Successfully switched ".$member." to registered member.";
		} else if($guestUserLevel == '3' && $rank == '3') {
			echo " Successfully switched ".$member." to Moderator.";
		}else if($guestUserLevel == '4' && $rank == '4') {
			echo " Successfully switched ".$member." to Admin.";
		} else {
			echo " Failed to switch ".$member." to the selected level.";
		}
		
	}
	
?>
<html>
<head>
	<link rel = "stylesheet" type = "text/css" href = "css/admin_stylesheet.css" />
	<?php echo 	'<title>'.$chatname?>
	<?php echo " - Admin </title>"; ?>
</head>
<body>
	<div class="options">
		<h2>Administrative Functions</h2>
		<tr><td><hr></td></tr>
		<form method="post" action="admin.php">
			<button type="submit" name="admin" id="admin">Admin Setup</button>
		</form>
		<tr><td><hr></td></tr>
		<p>Clean Messages</p>
		<tr><td><hr></td></tr>
		<p>Kick Chatter</p>
		<tr><td><hr></td></tr>
		<p>logout Inactive Chatter</p>
		<tr><td><hr></td></tr>
		<p>View Active Sessions:</p>
			<?php 
			$sql = $mysqli->query("SELECT * FROM members");
					while($row = mysqli_fetch_array($sql)){   //Creates a loop to loop through results
						$online_name = (string)$row['nickname'];
						$online_status = (string)$row['status'];
						if($online_status == '1') {					
							echo $row['nickname'] . ", "; 
						}
					}
			?>
		<tr><td><hr></td></tr>
		<p>Change Guest Access</p>
		<tr><td><hr></td></tr>
		<p>Change Members Level:</p>
		<div class="adjust_member">
			<form id="change" action="admin.php" method="post" target="_self">
				<select name="member" style="width:100;text-align: center; background-color: black; color: white;">
					<option value="">(choose)</option>
					<?php
					$sql = $mysqli->query("SELECT * FROM members");
					while($row =  mysqli_fetch_array($sql)){
						$nickname = $row['nickname'];
						$online_status = (string)$row['status'];
						$account_level = (string)$row['level'];
						
						if($online_status == '1' && $account_level == '2' || $online_status == '1' && $account_level == '3'|| $online_status == '1' && $account_level == '4') {
							echo "<option value=".$nickname.">".$nickname."</option>";
						}
					}
					?>
				</select>
				<select name="rank" style="width:200;text-align: center; background-color: black; color: white;">
					<option value="">(choose)</option>
					<option value="1">Guest</option>
					<option value="2">Set to regular member</option>
					<option value="3">Set to moderator (M)</option>
					<option value="4">Set to admin (A)</option>
				</select>
				<button type="submit" name="change" id="change">Change</button>
			</form>
		</div>
		<tr><td><hr></td></tr>
		<p>Register Guest</p>
		<div class="register_guest">
			<form id="register" action="admin.php" method="post" target="_self">
				<select name="register_guest" style="text-align: center; background-color: black; color: white;">
					<option value="">(choose)</option>
					<?php
					$sql = $mysqli->query("SELECT * FROM members");
					while($row =  mysqli_fetch_array($sql)){
						$nickname = $row['nickname'];
						$online_status = (string)$row['status'];
						$account_level = (string)$row['level'];
						
						if($online_status == '1' && $account_level == '1') {
							echo "<option value=".$nickname.">".$nickname."</option>";
						}
					}
					mysqli_close($mysqli);
					?>
				</select>
				
				<button type="submit" name="register" id="register">Register</button>
			</form>
		</div>
		<tr><td><hr></td></tr>
		<p>Register New Member</p>
		<tr><td><hr></td></tr>
	</div>
</body>
</html>