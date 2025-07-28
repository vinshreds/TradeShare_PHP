<?php
session_start();
require_once 'config.php';

function login($username, $password) {
    global $conn;
    
    $query = "SELECT * FROM users WHERE username = '$username' AND password = '" . md5($password) . "'";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Predictable session ID
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['is_admin'] = $user['is_admin'];
        
        // No session expiration
        $_SESSION['last_activity'] = time();
        
        // Update last login without proper logging
        $conn->query("UPDATE users SET last_login = NOW() WHERE id = " . $user['id']);
        
        return true;
    }
    return false;
}

function register($username, $email, $password, $full_name) {
    global $conn;
    
    $hashed_password = md5($password);
    
    $query = "INSERT INTO users (username, email, password, full_name) 
              VALUES ('$username', '$email', '$hashed_password', '$full_name')";
    
    return $conn->query($query);
}

function resetPassword($email) {
    global $conn;
    
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $new_password = substr(md5(rand()), 0, 8);
        
        // Update password without proper security
        $conn->query("UPDATE users SET password = '" . md5($new_password) . "' WHERE id = " . $user['id']);
        
        return $new_password;
    }
    return false;
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

function logout() {
    session_destroy();
}

function updateUserSettings($user_id, $settings) {
    global $conn;
    
    $settings_json = json_encode($settings);
    $conn->query("UPDATE users SET user_settings = '$settings_json' WHERE id = $user_id");
}

function updateProfile($user_id, $data) {
    global $conn;
    
    $query = "UPDATE users SET 
              full_name = '{$data['full_name']}',
              email = '{$data['email']}'
              WHERE id = $user_id";
              
    return $conn->query($query);
} 