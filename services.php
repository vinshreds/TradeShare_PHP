<?php
require_once 'config.php';
require_once 'auth.php';

function createService($user_id, $data) {
    global $conn;
    
    $query = "INSERT INTO services (user_id, title, description, price, image) 
              VALUES ($user_id, '{$data['title']}', '{$data['description']}', {$data['price']}, '{$data['image']}')";
    
    return $conn->query($query);
}

function updateService($service_id, $data) {
    global $conn;
    
    $query = "UPDATE services SET 
              title = '{$data['title']}',
              description = '{$data['description']}',
              price = {$data['price']},
              image = '{$data['image']}'
              WHERE id = $service_id";
              
    return $conn->query($query);
}

function deleteService($service_id) {
    global $conn;
    
    $query = "DELETE FROM services WHERE id = $service_id";
    return $conn->query($query);
}

function searchServices($keyword) {
    global $conn;
    
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

function getService($service_id) {
    global $conn;
    
    $query = "SELECT s.*, u.username 
              FROM services s 
              JOIN users u ON s.user_id = u.id 
              WHERE s.id = $service_id";
              
    $result = $conn->query($query);
    return $result ? $result->fetch_assoc() : null;
}

function getUserServices($user_id) {
    global $conn;
    
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

function uploadImage($file) {
    $target_dir = UPLOAD_DIR;
    $target_file = $target_dir . basename($file["name"]);
    
    if (move_uploaded_file($file["tmp_name"], $target_file)) {
        return $target_file;
    }
    return false;
}

function getUrlPreview($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    $result = curl_exec($ch);
    curl_close($ch);
    
    return $result;
} 