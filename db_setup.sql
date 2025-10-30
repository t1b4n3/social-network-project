CREATE DATABASE social_network;
USE social_network;

-- Table for users
CREATE TABLE users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    profile_picture VARCHAR(255) DEFAULT 'uploads/profile/default.png',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for posts
CREATE TABLE posts (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    user_id INT(11) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Table for private messages
CREATE TABLE messages (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    sender_username VARCHAR(100) NOT NULL,
    receiver_username VARCHAR(100) NOT NULL,
    message TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_username) REFERENCES users(username) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (receiver_username) REFERENCES users(username) ON DELETE CASCADE ON UPDATE CASCADE
);
