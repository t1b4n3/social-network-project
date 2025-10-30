<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/db_functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$search_query = "";
$users = [];

// Process search if query is provided
if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
    $search_query = trim($_GET['query']);
    $users = searchUsers($pdo, $search_query, $_SESSION['user_id']);
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Users - Social Platform</title>
    <link rel="stylesheet" href="css/style.css">
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


    <div class="container">
        <div class="search-page">
            <h1>Search Users</h1>
            
            <!-- Search Form -->
            <form action="search.php" method="GET" class="search-form">
                <div class="form-group">
                    <input type="text" name="query" 
                           value="<?php echo htmlspecialchars($search_query); ?>"
                           placeholder="Enter name or email address..." 
                           class="form-control" required>
                    <button type="submit" class="btn btn-primary">Search</button>
                </div>
            </form>

            <!-- Search Results -->
            <?php if (!empty($search_query)): ?>
                <div class="search-results">
                    <h2>Search Results for "<?php echo htmlspecialchars($search_query); ?>"</h2>
                    
                    <?php if (empty($users)): ?>
                        <p class="no-results">No users found matching your search.</p>
                    <?php else: ?>
                        <div class="users-list">
                            <?php foreach ($users as $user): ?>
                                <div class="user-card">
                                    <div class="user-avatar">
                                        <img src="uploads/<?php echo htmlspecialchars($user['profile_picture']); ?>" 
                                             alt="<?php echo htmlspecialchars($user['full_name']); ?>"
                                             onerror="this.src='uploads/profile/default.png'">
                                    </div>
                                    <div class="user-info">
                                        <h3><?php echo htmlspecialchars($user['full_name']); ?></h3>
                                        <p class="user-email"><?php echo htmlspecialchars($user['email']); ?></p>
                                    </div>
                                    <div class="user-actions">
                                        <a href="profile.php?username=<?php echo $user['username']; ?>" 
                                           class="btn btn-secondary">View Profile</a>
                                        <a href="text.php?to=<?php echo $user['username']; ?>" 
                                           class="btn btn-primary">Send Message</a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
