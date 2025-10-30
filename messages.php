<?php 
// show users and allows to search

/* 
if no messages where sent in the past, just show search bar else show previosly messeged users. 
 */
session_start();

require_once "./includes/db_functions.php";
require_once "./includes/config.php";


if ($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['search_'])) {
	$users = searchUsers($pdo, $_GET['search_user'], $_SESSION['user_id']);
}


$chat_users = get_chat_users($pdo, $_SESSION['username']);

?>

<!DOCTYPE html>
<html>
        <head>
                <title>Message</title>
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
                </nav>

			<div id="chat_users">
			    <h3>Chats</h3>
			    <ul>
			        <?php foreach ($chat_users as $user): ?>
			            <li>
			                <a href="text.php?to=<?php echo htmlspecialchars($user['sender_username']); ?>">
			                    <?php echo htmlspecialchars($user['sender_username']); ?>
			                </a>
			            </li>
			        <?php endforeach; ?>
			    </ul>
			</div>

		
	</body>
</html>


