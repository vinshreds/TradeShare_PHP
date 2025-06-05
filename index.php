<?php
require_once 'config.php';
require_once 'auth.php';
require_once 'services.php';

// Intentionally vulnerable search handling
$search_results = [];
if (isset($_GET['search'])) {
    $search_results = searchServices($_GET['search']);
}

// Get all services if no search
if (empty($search_results)) {
    $query = "SELECT s.*, u.username FROM services s JOIN users u ON s.user_id = u.id ORDER BY s.created_at DESC";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $search_results[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TradeShare - Find Local Tradesmen</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
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
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="messages.php"><i class="fas fa-envelope"></i> Messages</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="profile.php"><i class="fas fa-user"></i> Profile</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="register.php"><i class="fas fa-user-plus"></i> Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero">
        <div class="container text-center">
            <h1>Find Local Tradesmen</h1>
            <p>Connect with skilled professionals for all your home and business needs</p>
            <div class="search-container">
                <form class="search-form" action="search.php" method="GET">
                    <input type="text" name="q" class="search-input" placeholder="What service do you need?">
                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i>
                        Search
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <h2 class="text-center mb-4">Popular Categories</h2>
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-hammer service-icon"></i>
                        <h5 class="card-title">Construction</h5>
                        <p class="card-text">Building and renovation services</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-wrench service-icon"></i>
                        <h5 class="card-title">Plumbing</h5>
                        <p class="card-text">Plumbing and drainage services</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-bolt service-icon"></i>
                        <h5 class="card-title">Electrical</h5>
                        <p class="card-text">Electrical installation and repair</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-paint-roller service-icon"></i>
                        <h5 class="card-title">Painting</h5>
                        <p class="card-text">Interior and exterior painting</p>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="text-center mb-4 mt-5">Recent Services</h2>
        <div class="row">
            <?php
            // Fetch recent services from database
            $sql = "SELECT s.*, u.username FROM services s 
                    JOIN users u ON s.user_id = u.id 
                    ORDER BY s.created_at DESC LIMIT 8";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    ?>
                    <div class="col-md-6 mb-4">
                        <div class="card service-card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($row['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($row['description']); ?></p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="category-badge"><?php echo htmlspecialchars($row['category']); ?></span>
                                    <small class="text-muted">Posted by <?php echo htmlspecialchars($row['username']); ?></small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>

    <footer class="footer">
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