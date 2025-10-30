<?php 
session_start();

require_once "./includes/db_functions.php";
require_once "./includes/config.php";

if (!isset($_SESSION['user_id'])) {
	header("Location: ./login.php");
	exit;
}

$num_posts = get_number_of_posts($pdo);
function get_posts_data() {
	global $content, $image, $date, $post_user_id;

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

		<h2> Dashboard</h2>
		

		<!-- POSTS -->
		<?php for ($i = $num_posts; $i >= 1; $i--):
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
				<div id='profile_d'>
				<?php #if (isset($_FILES['profile_picture'])): ?>
					
					<img src="<?php echo htmlspecialchars($pp_url); ?>" alt='profile picture' id='pp'>
				<?php #endif; ?>
				</div>
				<div id='post_username'>
				<a href='./profile.php?username=<?php echo htmlspecialchars($username);?>' class="btn btn-primary"><?php echo htmlspecialchars($username); ?></a>
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

		<?php endfor; ?>
	</body>
</html>

