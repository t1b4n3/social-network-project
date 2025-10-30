<?php 
session_start();

require_once "./includes/db_functions.php";
require_once "./includes/config.php";


if ($_SESSION['username'] !== $_GET['username']) {
	header("Location: ./index.php");
	exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_profile'])) {
    $full_name = trim($_POST['full_name'] ?? '');
    $username  = trim($_POST['username'] ?? '');
    $file = null;
    $errors = [];

    // Handle file upload
    if (!empty($_FILES['image']['name'])) {
        $dir = "./uploads/profile/";
        
        // Create directory if it doesn't exist
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        $file_name = basename($_FILES['image']['name']);
        $file_path = $dir . uniqid() . "_" . $file_name;

        // Validate file type
        $allowed_types = ['image/jpeg', 'image/png', 'image/jpg'];
        $file_type = mime_content_type($_FILES['image']['tmp_name']);
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "Only JPEG and PNG files are allowed.";
        }

        // Validate file size (max 5MB)
        else if ($_FILES['image']['size'] > 5 * 1024 * 1024) {
            $errors[] = "File size must be less than 5MB.";
        }

        // Move uploaded file if no errors
        else if (!move_uploaded_file($_FILES['image']['tmp_name'], $file_path)) {
            $errors[] = "Failed to upload file.";
        } else {
            $file = $file_path;
            error_log("File uploaded successfully: " . $file_path);
        }
    }

    // Only proceed if no errors
    if (empty($errors)) {
        // Debug: Check what's being passed
        error_log("Calling edit_profile with:");
        error_log("Username: " . ($username ?: 'NULL'));
        error_log("Full Name: " . ($full_name ?: 'NULL'));
        error_log("File: " . ($file ?: 'NULL'));
        
        edit_profile($pdo, $_SESSION['user_id'], $username ?: null, $full_name ?: null, $file ?: null);
        
        // Redirect or show success message
        

        // Update session
    if (!empty($username)) $_SESSION['username'] = $username;
    if (!empty($full_name)) $_SESSION['full_name'] = $full_name;

    // Redirect safely
    header("Location: ./profile.php?username=" . urlencode($_SESSION['username']));

        exit;
    } else {
        // Handle errors - show to user or log them
        foreach ($errors as $error) {
            error_log("File upload error: " . $error);
        }
        // You might want to show these errors to the user
    }


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




		<h2>Edit Profile</h2>
		
		<form method='post'> 
			<label>Full Name: </label> <input type='text' name='full_name'> <br>
		
			<label>Username: </label> <input type='text' name='username'> <br>

			<label>New Profile Picture</label> 
			<input type='file' name='image' id='post-image-file'>
			<hr>
			<input type='submit' name='edit_profile' value="Edit Profile">

		</form>


	</body>

 </html>

