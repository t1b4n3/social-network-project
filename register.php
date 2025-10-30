<?php 
session_start();
$password_ = "";


require_once "./includes/db_functions.php";
require_once "./includes/config.php";

$error = "";
function register() {
	global $pdo, $error;
	$full_name = $_POST['full_name'];
	$password1 = $_POST['password'];
	$password2 = $_POST['c_password'];	
	$email = $_POST['email'];
	$username = $_POST['username'];


	if ($password1 !== $password2) {
		$error = "passwords do not match";
		return;
	}
	$hashed_password = password_hash($password1, PASSWORD_BCRYPT);
	$is_registered = register_user($pdo, $full_name, $email, $hashed_password, $username);
	if ($is_registered == false) {
		$error = "Email Already Exists";
		return;
	}
	header("Location: ./login.php");
	exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_button'])) {
	register();
} elseif (isset($_POST['goto_login'])) {
	header("Location: ./login.php");
}
?>


<!DOCTYPE html>
<html>
	<head>
		<title> Register </title>
		<link rel='stylesheet' href='./css/authentication.css'>
	</head>
	<body> 
	<div class='register-form-wrapper'>
		<h2> Register </h2>
		
		<form method='post'>
			<label for="full_name">Full Name: </label> <input type='text' name='full_name' required> <br>
			<label for='username'>Username: </label> <input type='text' name='username' required> <br>
			<label for='email'>Email: </label> <input type='email' name='email' required> <br>
			<label for='password'>Password: </label> <input type='password' name='password' required> <br>
			<label for='c_password'>Confirm Password: </label> <input type='password' name='c_password' required> <br>
			<input type='submit' name='register_button' value='Register'>
			
		</form>
		<div id='password_not_match'>
			<?php echo $error ?>
		</div>
		<hr>
		<br>
		<a href="./login.php">Already Registered? Login Here</a>
	</div>
	<body>
</html>
