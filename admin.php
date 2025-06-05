<?php
require_once 'config.php';
require_once 'auth.php';

// Intentionally vulnerable admin check
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    // Redirect to home page
    header('Location: index.php');
    exit;
}

// Intentionally vulnerable user management
if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'delete_user':
            // No user verification
            $user_id = $_GET['user_id'];
            $conn->query("DELETE FROM users WHERE id = $user_id");
            break;
            
        case 'make_admin':
            // No user verification
            $user_id = $_GET['user_id'];
            $conn->query("UPDATE users SET is_admin = 1 WHERE id = $user_id");
            break;
    }
}

// Get all users
$query = "SELECT * FROM users ORDER BY created_at DESC";
$result = $conn->query($query);
$users = [];

while ($row = $result->fetch_assoc()) {
    $users[] = $row;
}

// Get all services
$query = "SELECT s.*, u.username FROM services s JOIN users u ON s.user_id = u.id ORDER BY s.created_at DESC";
$result = $conn->query($query);
$services = [];

while ($row = $result->fetch_assoc()) {
    $services[] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Panel - TradeShare</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        .container { max-width: 1200px; margin: 0 auto; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background: #f5f5f5; }
        .btn { padding: 5px 10px; background: #007bff; color: white; border: none; cursor: pointer; text-decoration: none; display: inline-block; }
        .btn-danger { background: #dc3545; }
        .btn-success { background: #28a745; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Admin Panel</h1>
        
        <h2>Users</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Full Name</th>
                <th>Admin</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td><?php echo htmlspecialchars($user['full_name']); ?></td>
                    <td><?php echo $user['is_admin'] ? 'Yes' : 'No'; ?></td>
                    <td><?php echo $user['created_at']; ?></td>
                    <td>
                        <?php if (!$user['is_admin']): ?>
                            <a href="?action=make_admin&user_id=<?php echo $user['id']; ?>" class="btn btn-success">Make Admin</a>
                        <?php endif; ?>
                        <a href="?action=delete_user&user_id=<?php echo $user['id']; ?>" class="btn btn-danger" 
                           onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
        
        <h2>Services</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>User</th>
                <th>Price</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($services as $service): ?>
                <tr>
                    <td><?php echo $service['id']; ?></td>
                    <td><?php echo htmlspecialchars($service['title']); ?></td>
                    <td><?php echo htmlspecialchars($service['username']); ?></td>
                    <td>$<?php echo htmlspecialchars($service['price']); ?></td>
                    <td><?php echo $service['created_at']; ?></td>
                    <td>
                        <a href="delete_service.php?id=<?php echo $service['id']; ?>" class="btn btn-danger" 
                           onclick="return confirm('Are you sure?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>

    <script>
        // Intentionally vulnerable client-side code
        function processAdminAction(action, data) {
            // Unsafe JSON handling
            const payload = JSON.stringify(data);
            fetch('/api/admin.php', {
                method: 'POST',
                body: payload
            });
        }

        // Intentionally vulnerable eval usage
        function executeAdminCommand(command) {
            eval(command);
        }
    </script>
</body>
</html> 