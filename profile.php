<?php
require_once 'config.php';
require_once 'auth.php';
require_once 'services.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: index.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Intentionally vulnerable profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'full_name' => $_POST['full_name'],
        'email' => $_POST['email']
    ];
    
    // No input validation
    if (updateProfile($user_id, $data)) {
        header('Location: profile.php?success=1');
        exit;
    }
}

// Intentionally vulnerable profile picture upload
if (isset($_FILES['profile_picture'])) {
    $file = $_FILES['profile_picture'];
    // No file type validation
    if (uploadImage($file)) {
        $conn->query("UPDATE users SET profile_picture = '" . $file['name'] . "' WHERE id = $user_id");
    }
}

// Get user services
$services = getUserServices($user_id);

// Get user data
$query = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($query);
$user = $result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - TradeShare</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
    <?php
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
    require_once 'config.php';
    require_once 'auth.php';

    $user_id = $_SESSION['user_id'];
    $user = get_user_by_id($user_id);
    ?>

    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-tools"></i> TradeShare
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php"><i class="fas fa-home"></i> Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="services.php"><i class="fas fa-briefcase"></i> Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="messages.php"><i class="fas fa-envelope"></i> Messages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="profile.php"><i class="fas fa-user"></i> Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        <img src="<?php echo $user['profile_image'] ?? 'images/default-avatar.png'; ?>" 
                             class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                        <h4><?php echo htmlspecialchars($user['username']); ?></h4>
                        <p class="text-muted">
                            <i class="fas fa-briefcase"></i> 
                            <?php echo $user['is_tradesman'] ? 'Tradesman' : 'Client'; ?>
                        </p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                            <i class="fas fa-edit"></i> Edit Profile
                        </button>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-star"></i> Ratings & Reviews</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $reviews = get_user_reviews($user_id);
                        if ($reviews): ?>
                            <?php foreach ($reviews as $review): ?>
                                <div class="review mb-3">
                                    <div class="d-flex justify-content-between">
                                        <strong><?php echo htmlspecialchars($review['reviewer_name']); ?></strong>
                                        <div class="stars">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <i class="fas fa-star <?php echo $i <= $review['rating'] ? 'text-warning' : 'text-muted'; ?>"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <p class="mb-0"><?php echo htmlspecialchars($review['comment']); ?></p>
                                    <small class="text-muted"><?php echo date('M d, Y', strtotime($review['created_at'])); ?></small>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="text-muted">No reviews yet</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> About</h5>
                    </div>
                    <div class="card-body">
                        <p><?php echo nl2br(htmlspecialchars($user['bio'] ?? 'No bio available')); ?></p>
                    </div>
                </div>

                <?php if ($user['is_tradesman']): ?>
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-briefcase"></i> My Services</h5>
                    </div>
                    <div class="card-body">
                        <?php
                        $services = get_user_services($user_id);
                        if ($services): ?>
                            <div class="row">
                                <?php foreach ($services as $service): ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card h-100">
                                            <div class="card-body">
                                                <h6><?php echo htmlspecialchars($service['title']); ?></h6>
                                                <p class="text-muted"><?php echo htmlspecialchars($service['description']); ?></p>
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="badge bg-primary"><?php echo htmlspecialchars($service['category']); ?></span>
                                                    <span class="text-primary">$<?php echo number_format($service['price'], 2); ?></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-muted">No services listed yet</p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="update_profile.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-user"></i> Username</label>
                            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-envelope"></i> Email</label>
                            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-image"></i> Profile Image</label>
                            <input type="file" name="profile_image" class="form-control" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label"><i class="fas fa-info-circle"></i> Bio</label>
                            <textarea name="bio" class="form-control" rows="4"><?php echo htmlspecialchars($user['bio'] ?? ''); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Changes
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <footer class="footer mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5><i class="fas fa-tools"></i> TradeShare</h5>
                    <p>Connecting skilled tradesmen with clients since 2024</p>
                </div>
                <div class="col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="about.php" class="text-white">About Us</a></li>
                        <li><a href="contact.php" class="text-white">Contact</a></li>
                        <li><a href="terms.php" class="text-white">Terms of Service</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Connect With Us</h5>
                    <div class="social-links">
                        <a href="#" class="text-white me-2"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 