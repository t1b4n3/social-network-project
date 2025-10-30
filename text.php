<?php 
session_start();

require_once "./includes/db_functions.php";
require_once "./includes/config.php";

if (!isset($_SESSION['user_id']) && !isset($_SESSION['username'])) {
	header("Location: ./login.php");
	exit;
}


$prev_messages = retrieve_messages($pdo, $_SESSION['username'], $_GET['to']);


if (isset($_POST['send_message'])) {
	send_message($pdo, $_SESSION['username'], $_GET['to'], $_POST['message']);
	header("Refresh: 0");
}


?>

<!DOCTYPE html>
<html>
	<head>
		<title>Dashboard</title>
		<link rel='stylesheet' href='./css/dashboard.css'>
		<link rel='stylesheet' href='./css/style.css'>
	</head>
	<body>

<!-- NAVBAR -->
		<nav class='top_navbar'>
			<a href="./index.php">Home</a>
			<a href='./profile.php?username=<?php echo htmlspecialchars($_SESSION['username']); ?>'>Profile</a>
			<a href='./new_post.php'>Add Post</a>
			<a href='./messages.php'>Message</a>
			<a href="./search.php">Search</a>	
			<a href="./logout.php">Logout</a>
			
			<div class="nav-search">
        			<?php include 'search_form.php'; ?>
    			</div>	
		</nav>


		<div id='prev_messages'>
			<?php foreach ($prev_messages as $prev_text): ?>
				<?php
					$sender_username = $prev_text['sender_username'];
					$receiver_username = $prev_text['receiver_username'];
					$message = $prev_text['message'];
					?>

				<?php if ($sender_username == $_SESSION['username']): ?>
					<div id='user_bubble'> <!-- Algin right -->
						<?php echo htmlspecialchars($message) ?>
					</div>
				<?php else: ?>
					<div id='other_user_bubble'> <!-- Algin left  -->
						<?php echo htmlspecialchars($message) ?>
					</div>
				<?php endif;?>


			<?php endforeach;?>
	
		</div>


		<div id='send_messages_form'>
			<form method='post' action=''> 		
				<textarea name='message' placeholder="Type a message  "></textarea>
				<input type='submit' name='send_message' value="Send">	
			</form>
		</div>



	</body>

</html>
