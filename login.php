<?php
session_start();

require_once "./includes/db_functions.php";
require_once "./includes/config.php";   
$login_failed = Null;

function login() {
	global $pdo;
	$email = $_POST['email'];
	$password = $_POST['password'];

	$user_data = login_user($pdo, $email, $password);
	if ($user_data === false) {
		$login_failed = "Failed to login";
	} else {
		$_SESSION['user_id'] = $user_data['id'];
		$_SESSION['email'] = $user_data['email'];
		$_SESSION['profile_picture'] = $user_data['profile_picture'];
		$_SESSION['username'] = $user_data['username'];
		$_SESSION['full_name'] = $user_data['full_name'];
		header('Location: ./index.php');
		exit();
	}
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login_button'])) {
	login();
} 

?>

<!DOCTYPE html>
<html>
	<head> 

	<link rel='stylesheet' href='./css/authentication.css'>
	</head>
	<body>
	<div id='login_page'>
		<h2> Login </h2>
		<form method='post'>
			<!-- username -->
			<label for='email'>Email: </label> <input type='email' name='email' required> <br>
			<label for='password'>Password: </label> <input type='password' name='password' required> <br>
			<input type='submit' name='login_button' value='Login'> 
			<hr> <br>
			<!-- <label>Register here</label>
			 <input type='submit' name='goto_register' value='Register'> -->
		</form>

		<a href='./register.php'>Register Here</a>
	</div>

	</body>
</html>
