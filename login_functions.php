<?php
	echo	"<link rel = \"stylesheet\" type = \"text/css\" href = \"css/chat_stylesheet.css\" />";
	include ('settings.php');
	
	session_start();
	
	$title = $chatname . " - Chat";
	
	//establish database connection
	$mysqli = mysqli_connect($host, $dbuser, $dbpass, $dbname) or die($mysqli->error);
	
	// Escape all $_POST variables to protect against SQL injections
	$sterilizedUsername = $mysqli->escape_string($_POST['username']);
	$sterilizedEnteredPassword = $mysqli->escape_string($_POST['password']);
	
	$result = $mysqli->query("SELECT * FROM members WHERE nickname='$sterilizedUsername'") or die($mysqli->error());
	
	$get_level = $mysqli->query("SELECT level FROM members WHERE nickname='$sterilizedUsername'") or die($mysqli->error());
	
	if($result->num_rows == 0) { //user doesn't exist
		userDoesntExist();
	} else {
		$user = $result->fetch_assoc();
		$userlevel = $get_level->fetch_assoc();
		
		$level = (int)$userlevel['level'];
		
		if( password_verify($sterilizedEnteredPassword, $user['passhash'])) {
			
			$_SESSION['username'] = $user['nickname'];
			
			$usernameLogin = $_SESSION['username'];
			
			$sql = "UPDATE members SET status='1' WHERE nickname='$usernameLogin'";
			
			if($mysqli->query($sql) === TRUE) {
				header('Location: chat.php');
			} else {
				echo "Error: " . $sql . "<br>" . $mysqli->error;
			}
			
		} else {
			
			if($level >= 2){
				//header('Location: error.php');
				$_SESSION['message'] = '<p>Error: This nickname is a registered member.<br> Wrong Password!</p>';
				header('Location: error.php');
			} else {
				$_SESSION['message'] = "<p>Error: This Account is being currently being used.</p>";
				header('Location: error.php');
			}

		}
	}
	
function userDoesntExist() {
	include ('settings.php');
	
	// Escape all $_POST variables to protect against SQL injections
	$mysqli = mysqli_connect($host, $dbuser, $dbpass, $dbname) or die($mysqli->error);
	
	$newSterilizedUsername = $mysqli->escape_string($_POST['username']);
	$newSterilizedPassword = $mysqli->escape_string(password_hash($_POST['password'], PASSWORD_BCRYPT));
	$hash = $mysqli->escape_string(md5( rand(0, 1000) ) );
	
	$result = $mysqli->query("SELECT * FROM members WHERE nickname='$newSterilizedUsername'") or die($mysqli->error());
	
	//if the username is not found or already in use
	if ($result->num_rows > 0) {
		$_SESSION['message'] = '<p>Error: That nickname is already in use</p>';
		header("Location: error.php");
	} else {
		$sql = "INSERT INTO members (nickname, passhash, status, regedby) VALUES ('$newSterilizedUsername', '$newSterilizedPassword', '1', 'Login')";
		
		if($mysqli->query($sql) === TRUE) {
			
			$_SESSION['username'] = $newSterilizedUsername;
			$_SESSION['password'] = $newSterilizedPassword;
			
			header('Location: chat.php');
		} else {
			echo "Error: " . $sql . "<br>" . $mysqli->error;
		}
	}
}


?>