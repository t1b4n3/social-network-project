<?php 
session_start();

require_once "./includes/db_functions.php";
require_once "./includes/config.php";

$my_profile = false;
if ($_SESSION['username'] == $_GET['username']) {
	$my_profile = true;
}


$user_data = get_user_by_username($pdo, $_GET['username']);
$user_id = $user_data['id'];
$post_ids = get_users_posts($pdo, $user_id);

?>
<!DOCTYPE html>
<html>
	<head>
		<link rel='stylesheet' href='./css/style.css'>
		<link rel='stylesheet' href='./css/profile.css'>
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
		
		<div id='show_profile'>
			<div id='show_fullname'>
				<?php echo htmlspecialchars($user_data['full_name']); ?>
			</div>
			<img src="<?php echo htmlspecialchars($user_data['profile_picture']); ?>" alt='profile picture' id='pp'>
			<div id='show_username'>
				<?php echo htmlspecialchars($user_data['username']); ?>
			</div>	

			<div id='edit_profile'>
				<?php if ($my_profile == true): ?>	
					<a href='./edit_profile.php?username=<?php echo htmlspecialchars($_SESSION['username']); ?> 'class="btn btn-primary">Edit Profile</a>
				
				<?php else:?>
			</div>
		</div>

			<a href='text.php?to=<?php echo $_GET['username']; ?>' class="btn btn-primary">Send Message</a>
			<?php endif; ?>
			

	<!-- show user posts -->

		<?php foreach (array_reverse($post_ids) as $i):
			$post_id = $i;
			$post_data = get_posts_by_id($pdo, $post_id);
			$content = $post_data['content'];
			$image = $post_data['image'];
			$date = $post_data['created_at'];
			$post_user_id = $post_data['user_id'];

			$user_data = get_user_by_id($pdo, $post_user_id);
			$pp_url  = $user_data['profile_picture'];
			$username = $user_data['username'];
		?>

		<div id='posts_view'>
			<div id='diplay_user_data'>
				<div id='pp'>
				<?php #if (isset($_FILES['profile_picture'])): ?>
					<img src="<?php echo htmlspecialchars($pp_url); ?>" alt="profile picture">
				<?php #endif; ?>
				</div>
				<div id='post_username'>
				<a href="./profile.php?username=<?php echo htmlspecialchars($username);?>"><?php echo htmlspecialchars($username); ?></a>
				</div>
			</div>
			<div id='post_image'>
				
				<?php if ($image !== NULL): ?>
					<img src="<?php echo htmlspecialchars($image);?>" alt="POST">
				<?php endif; ?>
			</div>
			<div id='post_content'>
				<?php echo htmlspecialchars($content);?>
			</div>

			<div id='edit_post'>
				<?php if ($_SESSION['username'] == $username): ?>
				<a href="./edit_post.php?post_id=<?php echo $post_id; ?>">Edit Post</a>
				<?php endif ?>
			</div>
		</div>		

		<?php endforeach; ?>





	</body>

</html>
