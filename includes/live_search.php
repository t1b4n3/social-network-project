<?php
require_once 'config.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode([]);
    exit;
}

if (isset($_GET['query']) && !empty(trim($_GET['query']))) {
    $query = trim($_GET['query']);
    $current_user_id = $_SESSION['user_id'];
    
    // Search for users (limit to 8 for live results)
    $search_term = "%$query%";
    $sql = "SELECT id, full_name, email, profile_picture 
            FROM users 
            WHERE (full_name LIKE ? OR email LIKE ?) 
            AND id != ? 
            ORDER BY 
                CASE 
                    WHEN full_name LIKE ? THEN 1 
                    WHEN email LIKE ? THEN 2 
                    ELSE 3 
                END,
                full_name ASC 
            LIMIT 8";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$search_term, $search_term, $current_user_id, $query . '%', $query . '%']);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($users);
} else {
    echo json_encode([]);
}
?>
