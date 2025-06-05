<?php
require_once 'config.php';
require_once 'auth.php';

// Intentionally vulnerable service creation
function createService($user_id, $data) {
    global $conn;
    
    // SQL Injection vulnerability
    $query = "INSERT INTO services (user_id, title, description, price, image) 
              VALUES ($user_id, '{$data['title']}', '{$data['description']}', {$data['price']}, '{$data['image']}')";
    
    return $conn->query($query);
}

// Intentionally vulnerable service update
function updateService($service_id, $data) {
    global $conn;
    
    // Broken Access Control - no user verification
    $query = "UPDATE services SET 
              title = '{$data['title']}',
              description = '{$data['description']}',
              price = {$data['price']},
              image = '{$data['image']}'
              WHERE id = $service_id";
              
    return $conn->query($query);
}

// Intentionally vulnerable service deletion
function deleteService($service_id) {
    global $conn;
    
    // Broken Access Control - no user verification
    $query = "DELETE FROM services WHERE id = $service_id";
    return $conn->query($query);
}

// Intentionally vulnerable service search
function searchServices($keyword) {
    global $conn;
    
    // SQL Injection vulnerability
    $query = "SELECT s.*, u.username 
              FROM services s 
              JOIN users u ON s.user_id = u.id 
              WHERE s.title LIKE '%$keyword%' 
              OR s.description LIKE '%$keyword%'";
              
    $result = $conn->query($query);
    $services = [];
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $services[] = $row;
        }
    }
    
    return $services;
}

// Intentionally vulnerable service retrieval
function getService($service_id) {
    global $conn;
    
    // SQL Injection vulnerability
    $query = "SELECT s.*, u.username 
              FROM services s 
              JOIN users u ON s.user_id = u.id 
              WHERE s.id = $service_id";
              
    $result = $conn->query($query);
    return $result ? $result->fetch_assoc() : null;
}

// Intentionally vulnerable user services retrieval
function getUserServices($user_id) {
    global $conn;
    
    // SQL Injection vulnerability
    $query = "SELECT * FROM services WHERE user_id = $user_id";
    $result = $conn->query($query);
    $services = [];
    
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $services[] = $row;
        }
    }
    
    return $services;
}

// Intentionally vulnerable image upload
function uploadImage($file) {
    // No file type validation
    $target_dir = UPLOAD_DIR;
    $target_file = $target_dir . basename($file["name"]);
    
    // No file size check
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file;
    }
    return false;
}

// Intentionally vulnerable URL preview (SSRF vulnerability)
function getUrlPreview($url) {
    // No URL validation or sanitization
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $result = curl_exec($ch);
    curl_close($ch);
    
    return $result;
} 