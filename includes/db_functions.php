<?php

function send_message($pdo, $sender_username, $receiver_username, $message) {
	$sql = "INSERT INTO messages (sender_username, receiver_username, message) VALUES (?, ?, ?)";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$sender_username, $receiver_username, $message]);
}



function get_chat_users($pdo, $username) {
    $sql = "
        SELECT m1.*
        FROM messages m1
        JOIN (
            SELECT 
                CASE 
                    WHEN sender_username = ? THEN receiver_username
                    ELSE sender_username
                END AS chat_with,
                MAX(created_at) AS last_msg_time
            FROM messages
            WHERE sender_username = ? OR receiver_username = ?
            GROUP BY chat_with
        ) m2 ON (
            (m1.sender_username = ? AND m1.receiver_username = m2.chat_with OR 
             m1.sender_username = m2.chat_with AND m1.receiver_username = ?)
            AND m1.created_at = m2.last_msg_time
        )
        ORDER BY m1.created_at DESC
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$username, $username, $username, $username, $username]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function retrieve_messages($pdo, $username1, $username2) {
	$sql = "SELECT sender_username, receiver_username, message, created_at
	       	FROM messages 
		WHERE (sender_username = ? OR receiver_username = ?) 
		AND (sender_username = ? OR receiver_username = ?)";

	$stmt = $pdo->prepare($sql);
	$stmt->execute([$username1, $username1, $username2, $username2]);
	return $stmt->fetchALL(PDO::FETCH_ASSOC);
}


function get_users_posts($pdo, $id) {
	$stmt = $pdo->prepare("SELECT id FROM posts WHERE user_id = ?");
	$stmt->execute([$id]);
	return $stmt->fetchALL(PDO::FETCH_COLUMN);	
}

function searchUsers($pdo, $query, $current_user_id) {
    $search_term = "%$query%";
    
    $sql = "SELECT id, username, full_name, email, profile_picture 
            FROM users 
            WHERE (full_name LIKE ? OR email LIKE ? OR username LIKE ?) 
            AND id != ? 
            ORDER BY full_name ASC 
            LIMIT 50";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$search_term, $search_term, $search_term, $current_user_id]);
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function get_user_by_id($pdo, $id) {
	$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
	$stmt->execute([$id]);
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function get_user_by_username($pdo, $username) {
	$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
	$stmt->execute([$username]);
	return $stmt->fetch(PDO::FETCH_ASSOC);
}

function register_user($pdo, $full_name, $email, $password, $username) {
	$stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, username) VALUES (?, ?, ?, ?)");
	#$stmt->bind_param("sss", $full_name, $email, $password, $username);
	// check if email already exists
	$check = $pdo->prepare("SELECT * FROM users WHERE email = ?");
	$check->execute([$email]);
	$results = $check->fetch(PDO::FETCH_ASSOC);
	if ($results) {
		return false;
	}
	// check username
	$check = $pdo->prepare("SELECT * FROM users WHERE username = ?");
	$check->execute([$username]);
	$results = $check->fetch(PDO::FETCH_ASSOC);
	if ($results) {
		return false;
	}

	$stmt->execute([$full_name, $email, $password, $username]);
	return true;
}

function login_user($pdo, $email, $password) {
	$stmt = $pdo->prepare("SELECT password FROM users WHERE email = ?");
	#$stmt->bind_pararm("sss", $email);
	$stmt->execute([$email]);
	$result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && password_verify($password, $result['password'])) {
		$stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
		$stmt->execute([$email]);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		if (!$result) {
			return false;
		}
		$id = $result['id'];

		return get_user_by_id($pdo, $id);
        } else {
            return false;
        }
}

function get_posts_by_id($pdo, $id) {
	$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
	$stmt->execute([$id]);
	return $stmt->fetch(PDO::FETCH_ASSOC);
}	

function get_number_of_posts($pdo) {
	$stmt = $pdo->prepare("SELECT * FROM posts");
	$stmt->execute();
	$rows = $stmt->fetchAll();
	return count($rows);
}

function save_post($pdo, $content, $image, $user_id) {
	$stmt = $pdo->prepare("INSERT INTO posts (user_id, content, image) VALUES (?, ?, ?)");
	$stmt->execute([$user_id, $content, $image]);		
}

function edit_post($pdo, $post_id, $content, $image) {
	if ($image !== NULL && $content !== NULL) {
		// Both content and image provided
		$stmt = $pdo->prepare("UPDATE posts SET content = ?, image = ? WHERE id = ?");
		$stmt->execute([$content, $image, $post_id]);
	} elseif ($content !== NULL) {
		// Only content provided
		$stmt = $pdo->prepare("UPDATE posts SET content = ? WHERE id = ?");
		$stmt->execute([$content, $post_id]);
	} elseif ($image !== NULL) {
		// Only image provided
		$stmt = $pdo->prepare("UPDATE posts SET image = ? WHERE id = ?");
		$stmt->execute([$image, $post_id]);
	}
}


function edit_profile($pdo, $id, $username = null, $full_name = null, $file_path = null) {
    try {
        $pdo->beginTransaction();
        $fields = [];
        $params = [];

        if ($username !== null) {
            $fields[] = "username = ?";
            $params[] = $username;
        }

        if ($full_name !== null) {
            $fields[] = "full_name = ?";
            $params[] = $full_name;
        }

        if ($file_path !== null) {
            $fields[] = "profile_picture = ?";
            $params[] = $file_path;
        }

        // Only run the query if there’s something to update
        if (!empty($fields)) {
            $sql = "UPDATE users SET " . implode(", ", $fields) . " WHERE id = ?";
            $params[] = $id;

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
        }
        $pdo->commit();

    } catch (Exception $e) {
        $pdo->rollBack();
        throw $e;
    }
}



























?>
