<?php
require_once '../config.php';
require_once '../auth.php';

header('Content-Type: application/json');


$action = $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        $username = $_POST['username'];
        $password = $_POST['password'];
        $query = "SELECT * FROM users WHERE username = '$username' AND password = '" . md5($password) . "'";
        $result = $conn->query($query);
        
        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];
            
            echo json_encode(['success' => true, 'user' => $user]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
        }
        break;
        
    case 'register':
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $full_name = $_POST['full_name'];
        
        $hashed_password = md5($password);
        $query = "INSERT INTO users (username, email, password, full_name) 
                  VALUES ('$username', '$email', '$hashed_password', '$full_name')";
                  
        if ($conn->query($query)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Registration failed']);
        }
        break;
        
    case 'services':
        $keyword = $_GET['keyword'] ?? '';
        $query = "SELECT s.*, u.username 
                  FROM services s 
                  JOIN users u ON s.user_id = u.id 
                  WHERE s.title LIKE '%$keyword%' 
                  OR s.description LIKE '%$keyword%'";
                  
        $result = $conn->query($query);
        $services = [];
        
        while ($row = $result->fetch_assoc()) {
            $services[] = $row;
        }
        
        echo json_encode(['success' => true, 'services' => $services]);
        break;
        
    case 'create_service':
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            break;
        }
        
        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $image = $_POST['image'];
        
        $query = "INSERT INTO services (user_id, title, description, price, image) 
                  VALUES ({$_SESSION['user_id']}, '$title', '$description', $price, '$image')";
                  
        if ($conn->query($query)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to create service']);
        }
        break;
        
    case 'update_service':
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            break;
        }
        
        $service_id = $_POST['service_id'];
        $title = $_POST['title'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $image = $_POST['image'];
        
        $query = "UPDATE services SET 
                  title = '$title',
                  description = '$description',
                  price = $price,
                  image = '$image'
                  WHERE id = $service_id";
                  
        if ($conn->query($query)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update service']);
        }
        break;
        
    case 'delete_service':
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            break;
        }
        
        $service_id = $_POST['service_id'];
        $query = "DELETE FROM services WHERE id = $service_id";
        
        if ($conn->query($query)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to delete service']);
        }
        break;
        
    case 'settings':
        if (!isLoggedIn()) {
            echo json_encode(['success' => false, 'message' => 'Not logged in']);
            break;
        }
        
        $settings = json_decode($_POST['payload'], true);
        $settings_json = json_encode($settings);
        
        $query = "UPDATE users SET user_settings = '$settings_json' WHERE id = {$_SESSION['user_id']}";
        
        if ($conn->query($query)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update settings']);
        }
        break;
        
    case 'url_preview':
        $url = $_GET['url'];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $result = curl_exec($ch);
        curl_close($ch);
        
        echo json_encode(['success' => true, 'preview' => $result]);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
} 