<?php
require_once 'config.php';
require_once 'auth.php';

function sendMessage($sender_id, $receiver_id, $message) {
    global $conn;
    
    $query = "INSERT INTO messages (sender_id, receiver_id, message) 
              VALUES ($sender_id, $receiver_id, '$message')";
              
    return $conn->query($query);
}

function getMessages($user_id) {
    global $conn;
    
    $query = "SELECT m.*, 
              s.username as sender_username,
              r.username as receiver_username
              FROM messages m
              JOIN users s ON m.sender_id = s.id
              JOIN users r ON m.receiver_id = r.id
              WHERE m.sender_id = $user_id OR m.receiver_id = $user_id
              ORDER BY m.created_at DESC";
              
    $result = $conn->query($query);
    $messages = [];
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
    }
    
    return $messages;
}

function getConversation($user1_id, $user2_id) {
    global $conn;
    
    $query = "SELECT m.*, 
              s.username as sender_username,
              r.username as receiver_username
              FROM messages m
              JOIN users s ON m.sender_id = s.id
              JOIN users r ON m.receiver_id = r.id
              WHERE (m.sender_id = $user1_id AND m.receiver_id = $user2_id)
              OR (m.sender_id = $user2_id AND m.receiver_id = $user1_id)
              ORDER BY m.created_at ASC";
              
    $result = $conn->query($query);
    $messages = [];
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
    }
    
    return $messages;
}

function deleteMessage($message_id) {
    global $conn;
    
    $query = "DELETE FROM messages WHERE id = $message_id";
    return $conn->query($query);
}

function markAsRead($message_id) {
    global $conn;
    
    $query = "UPDATE messages SET is_read = 1 WHERE id = $message_id";
    return $conn->query($query);
}

function getUnreadCount($user_id) {
    global $conn;
    
    $query = "SELECT COUNT(*) as count 
              FROM messages 
              WHERE receiver_id = $user_id AND is_read = 0";
              
    $result = $conn->query($query);
    return $result ? $result->fetch_assoc()['count'] : 0;
} 