<?php
	echo	"<link rel = \"stylesheet\" type = \"text/css\" href = \"css/chat_stylesheet.css\" />";
	include ('settings.php');
	
	session_start();
	
	$title = $chatname . " - Chat";
	
	//establish database connection
	$mysqli = mysqli_connect($host, $dbuser, $dbpass, $dbname) or die($mysqli->error);
	
	// Escape all $_POST variables to protect against SQL & XSS injections
	$unfilteredUsername= $_POST['username'];
	$XSSfilteredUsername = htmlspecialchars($unfilteredUsername, ENT_QUOTES, 'UTF-8');
	$sterilizedUsername = $mysqli->escape_string($XSSfilteredUsername);
	
	//sterilize password;
	$unfilteredPassword= $_POST['password'];
	$XSSfilteredPassword = htmlspecialchars($unfilteredPassword, ENT_QUOTES, 'UTF-8');
	$sterilizedEnteredPassword = $mysqli->escape_string($XSSfilteredPassword);
	
	$_SESSION['username'] = $sterilizedUsername;
	$_SESSION['password'] = $sterilizedEnteredPassword;
	
	
	$result = $mysqli->query("SELECT * FROM members WHERE nickname='$sterilizedUsername'") or die($mysqli->error());
	
	$get_level = $mysqli->query("SELECT level FROM members WHERE nickname='$sterilizedUsername'") or die($mysqli->error());
	
	if($result->num_rows == 0) { //user doesn't exist
		userDoesntExist();
	} else {
		$user = $result->fetch_assoc();
		$userlevel = $get_level->fetch_assoc();
		
		$level = (int)$userlevel['level'];
		
		if( password_verify($sterilizedEnteredPassword, $user['passhash'])) {
			
			$sql = "UPDATE members SET status='1' WHERE nickname='$sterilizedUsername'";
			
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
	
	
	$newUnfilteredUsername= $_POST['username'];
	$newXSSfilteredUsername = htmlspecialchars($newUnfilteredUsername, ENT_QUOTES, 'UTF-8');
	$NewSterilizedUsername = $mysqli->escape_string($newXSSfilteredUsername);

	// Escape all $_POST variables to protect against SQL & XSS injections
	$unfilteredPassword= $_POST['password'];
	$XSSfilteredPassword = htmlspecialchars($unfilteredPassword, ENT_QUOTES, 'UTF-8');
	$newSterilizedPassword = $mysqli->escape_string(password_hash($XSSfilteredPassword, PASSWORD_BCRYPT));
	
	$hash = $mysqli->escape_string(md5( rand(0, 1000) ) );
	
	$result = $mysqli->query("SELECT * FROM members WHERE nickname='$NewSterilizedUsername'") or die($mysqli->error());
	
	//Check if the password contains special characters, if it does then redirect to an error page
	if(!preg_match("/[^a-z0-9 . $ % & * # @ ]/i", $unfilteredPassword)){
		//Check if the username contains special characters, if it does then redirect to an error page.
		if(!preg_match("/[^a-z0-9]/i", $newUnfilteredUsername)){
			//if the username is not found or already in use
			if ($result->num_rows > 0) {
				$_SESSION['message'] = '<p>Error: That nickname is already in use</p>';
				header("Location: error.php");
			} else {
				$sql = "INSERT INTO members (nickname, passhash, status, regedby) VALUES ('$NewSterilizedUsername', '$newSterilizedPassword', '1', 'Login')";
				
				if($mysqli->query($sql) === TRUE) {
					
					$_SESSION['username'] = $NewSterilizedUsername;
					$_SESSION['password'] = $newSterilizedPassword;
					
					header('Location: chat.php');
				} else {
					echo "Error: " . $sql . "<br>" . $mysqli->error;
				}
			}
		} else {
			$_SESSION['message'] = '<p>Error: Your username contains Special Characters, please try again only using A-Z, a-z, 0-9</p>';
			header("Location: error.php");
		}
	} else {
		$_SESSION['message'] = '<p>Error: Your password contains Special Characters, please try again only using A-Z, a-z, 0-9</p>';
		header("Location: error.php");
	}
}


?>