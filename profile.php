<?php 
session_start();
include 'db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get user information
$username = $_SESSION['username'];
$sql_user = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($sql_user);
$stmt->bind_param("s", $username);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// Get user's favorite products (based on a simple favorites table or just show popular products)
$sql_favorites = "SELECT * FROM products ORDER BY rating DESC, reviews_count DESC LIMIT 6";
$favorites_result = $conn->query($sql_favorites);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - TSUBAKI FLORAL</title>
    
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    
    <style>
        :root {
            --primary-color: #d4546a;
            --secondary-color: #f8d7da;
            --accent-color: #86c5a6;
            --dark-color: #2c3e50;
            --light-color: #f8f9fa;
            --muted-color: #6c757d;
            --success-color: #86c5a6;
            --border-radius: 12px;
            --transition: all 0.3s ease;
            --shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            --shadow-hover: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            line-height: 1.6;
            color: var(--dark-color);
            background-color: var(--light-color);
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            font-weight: 600;
            line-height: 1.2;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, #c44569 100%);
            border: none;
            border-radius: var(--border-radius);
            padding: 12px 24px;
            font-weight: 500;
            transition: var(--transition);
            box-shadow: 0 4px 15px rgba(212, 84, 106, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 84, 106, 0.4);
            background: linear-gradient(135deg, #c44569 0%, var(--primary-color) 100%);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            border-radius: var(--border-radius);
            padding: 12px 24px;
            font-weight: 500;
            transition: var(--transition);
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow-hover);
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        .navbar {
            padding: 1rem 0;
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95) !important;
            transition: var(--transition);
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem !important;
            color: var(--primary-color) !important;
            transition: var(--transition);
        }

        .navbar-brand:hover {
            transform: scale(1.05);
        }

        .nav-link {
            font-weight: 500;
            color: var(--dark-color) !important;
            transition: var(--transition);
            position: relative;
            padding: 0.75rem 1rem !important;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary-color) !important;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background: var(--primary-color);
            transition: var(--transition);
            transform: translateX(-50%);
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 80%;
        }

        .card {
            border-radius: var(--border-radius);
            transition: var(--transition);
            overflow: hidden;
            border: none;
            box-shadow: var(--shadow);
        }

        .card:hover {
            box-shadow: var(--shadow-hover);
        }

        .profile-header {
            background: linear-gradient(135deg, var(--primary-color) 0%, #c44569 100%);
            color: white;
            border-radius: var(--border-radius);
            padding: 2rem;
            margin-bottom: 2rem;
        }

        .profile-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .order-item {
            border-bottom: 1px solid #e9ecef;
            padding: 1rem 0;
            transition: var(--transition);
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .order-item:hover {
            background-color: #f8f9fa;
            border-radius: var(--border-radius);
            padding: 1rem;
            margin: 0 -1rem;
        }

        .order-image {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 20px;
        }

        .badge-success {
            background-color: var(--success-color);
        }

        .badge-warning {
            background-color: #ffc107;
        }

        .badge-info {
            background-color: #17a2b8;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="index.php">
                <i class="bi bi-flower1 text-primary me-2"></i>
                TSUBAKI FLORAL
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="shop.php">Shop</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.html">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center">
                    <button class="btn btn-outline-primary position-relative me-2" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                        <i class="bi bi-bag"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartCount">0</span>
                    </button>

                    <div class="dropdown me-2">
                        <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person me-1"></i>
                            <?php echo strtoupper(htmlspecialchars($_SESSION['username'])); ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item active" href="profile.php"><i class="bi bi-person-circle me-2"></i>Profile</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Profile Content -->
    <div class="container py-5">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="row align-items-center">
                <div class="col-md-auto">
                    <div class="profile-avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                </div>
                <div class="col-md">
                    <h2 class="mb-2"><?php echo htmlspecialchars($user['name'] ?? $user['username']); ?></h2>
                    <p class="mb-0 opacity-75">
                        <i class="bi bi-envelope me-2"></i>
                        <?php echo htmlspecialchars($user['email']); ?>
                    </p>
                    <p class="mb-0 opacity-75">
                        <i class="bi bi-calendar me-2"></i>
                        Member since <?php echo date('F Y', strtotime($user['created_at'] ?? 'now')); ?>
                    </p>
                </div>
            </div>
        </div>

        <!-- Account Information & Preferences -->
        <div class="row g-4">
            <!-- Account Details -->
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-person-gear me-2 text-primary"></i>
                            Account Details
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Full Name</label>
                            <p class="text-muted"><?php echo htmlspecialchars($user['name'] ?? $user['username']); ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email Address</label>
                            <p class="text-muted"><?php echo htmlspecialchars($user['email']); ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Username</label>
                            <p class="text-muted"><?php echo htmlspecialchars($user['username']); ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Member Since</label>
                            <p class="text-muted"><?php echo date('F d, Y', strtotime($user['created_at'] ?? 'now')); ?></p>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-outline-primary" onclick="alert('Edit profile feature coming soon!')">
                                <i class="bi bi-pencil me-2"></i>
                                Edit Profile
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delivery Preferences -->
            <div class="col-lg-6">
                <div class="card h-100">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-geo-alt me-2 text-primary"></i>
                            Delivery Preferences
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Preferred Delivery Time</label>
                            <p class="text-muted">Morning (9:00 AM - 12:00 PM)</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Default Address</label>
                            <p class="text-muted">Not set</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Special Instructions</label>
                            <p class="text-muted">Leave at front door if no answer</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Newsletter</label>
                            <p class="text-muted">
                                <span class="badge badge-success">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Subscribed
                                </span>
                            </p>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-outline-primary" onclick="alert('Update preferences feature coming soon!')">
                                <i class="bi bi-gear me-2"></i>
                                Update Preferences
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommended for You -->
        <div class="card mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-heart me-2 text-primary"></i>
                    Recommended for You
                </h5>
                <small class="text-muted">Based on popular choices</small>
            </div>
            <div class="card-body">
                <?php if ($favorites_result->num_rows > 0): ?>
                    <div class="row g-3">
                        <?php while($product = $favorites_result->fetch_assoc()): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="position-relative">
                                        <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                             class="card-img-top" alt="<?php echo htmlspecialchars($product['name']); ?>"
                                             style="height: 200px; object-fit: cover;">
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <button class="btn btn-sm btn-light rounded-circle" title="Add to Favorites">
                                                <i class="bi bi-heart"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="card-body p-3">
                                        <h6 class="card-title mb-2"><?php echo htmlspecialchars($product['name']); ?></h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="fw-bold text-primary">¥<?php echo number_format($product['price'], 0); ?></span>
                                            <div class="text-warning small">
                                                <?php
                                                $rating = round($product['rating']);
                                                for ($i = 1; $i <= 5; $i++) {
                                                    if ($i <= $rating) {
                                                        echo '<i class="bi bi-star-fill"></i>';
                                                    } else {
                                                        echo '<i class="bi bi-star"></i>';
                                                    }
                                                }
                                                ?>
                                            </div>
                                        </div>
                                        <div class="d-grid mt-2">
                                            <button class="btn btn-primary btn-sm">
                                                <i class="bi bi-bag-plus me-1"></i>
                                                Add to Cart
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-flower1 display-4 text-muted mb-3"></i>
                        <h6 class="text-muted">No recommendations available</h6>
                        <p class="text-muted mb-0">Browse our shop to discover beautiful arrangements</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php $conn->close(); ?>