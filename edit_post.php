<?php 
session_start();

require_once "./includes/db_functions.php";
require_once "./includes/config.php";


function UploadFile() {
	$dir = "uploads/posts/";
	$file = $dir . uniqid() . "_" . basename($_FILES["image"]['name']);
	if (move_uploaded_file($_FILES['image']["tmp_name"], $file)) {
		$file_upload_success = true;
	}

	return $file;
}

if (isset($_POST['add_post'])) {
	if (isset($_FILES["image"]) && isset($_POST['content']) ) {
		$file = UploadFile();
		edit_post($pdo, $_GET['post_id'], $_POST['content'], $file);
		header("Location: ./index.php");
	} elseif (!isset($_POST['content']) && isset($_FILES['image']) ) {
		$file = UploadFile();
		edit_post($pdo, $_GET['post_id'], NULL, $file);  
	} elseif (!isset($_FILES['image']) && isset($_POST['content']) ){
		edit_post($pdo, $_GET['post_id'], $_POST['content'], NULL);
	}
	header("Location: ./index.php");
}

?>

<!DOCTYPE html>
<html>
	<head>
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



		<h2> Edit Post</h2>

		<form class='add-post-form' method='post' enctype='multipart/form-data'>
			<label>Enter Conent</label> 		
			<textarea name='content' placeholder="enter your comment here"></textarea>
			
			<label for='post-image-file'>Upload Image (Optional)</label>
			<input type='file' name='image' id='post-image-file'>
			<hr>
				
			<input type='submit' name='add_post' value="Upload Post">
		</form>
		
	</body>
</html>

