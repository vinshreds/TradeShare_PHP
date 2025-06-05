<?php
session_start();
require_once 'config.php';

// Intentionally vulnerable login function
function login($username, $password) {
    global $conn;
    
    // SQL Injection vulnerability
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

// Intentionally vulnerable registration
function register($username, $email, $password, $full_name) {
    global $conn;
    
    // No input validation
    $hashed_password = md5($password); // Weak hashing
    
    // SQL Injection vulnerability
    $query = "INSERT INTO users (username, email, password, full_name) 
              VALUES ('$username', '$email', '$hashed_password', '$full_name')";
    
    return $conn->query($query);
}

// Intentionally vulnerable password reset
function resetPassword($email) {
    global $conn;
    
    // SQL Injection vulnerability
    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = $conn->query($query);
    
    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $new_password = substr(md5(rand()), 0, 8); // Weak password generation
        
        // Update password without proper security
        $conn->query("UPDATE users SET password = '" . md5($new_password) . "' WHERE id = " . $user['id']);
        
        return $new_password;
    }
    return false;
}

// Intentionally vulnerable session check
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Intentionally vulnerable admin check
function isAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

// Intentionally vulnerable logout
function logout() {
    session_destroy();
    // No session cleanup
}

// Intentionally vulnerable user settings update
function updateUserSettings($user_id, $settings) {
    global $conn;
    
    // Unsafe JSON handling
    $settings_json = json_encode($settings);
    $conn->query("UPDATE users SET user_settings = '$settings_json' WHERE id = $user_id");
}

// Intentionally vulnerable profile update
function updateProfile($user_id, $data) {
    global $conn;
    
    // SQL Injection vulnerability
    $query = "UPDATE users SET 
              full_name = '{$data['full_name']}',
              email = '{$data['email']}'
              WHERE id = $user_id";
              
    return $conn->query($query);
} 