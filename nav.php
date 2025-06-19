

<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm" id="mainNav">
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
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'index.php') ? 'active' : ''; ?>" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'shop.php') ? 'active' : ''; ?>" href="shop.php">Shop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'about.php' || basename($_SERVER['PHP_SELF']) == 'about.html') ? 'active' : ''; ?>" href="about.php">About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'active' : ''; ?>" href="contact.php">Contact</a>
                </li>
            </ul>

            <div class="d-flex align-items-center">
                <button class="btn btn-outline-primary position-relative me-2" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                    <i class="bi bi-bag"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartCount">0</span>
                </button>

                <?php if (isset($_SESSION['username'])): ?>
                    <!-- User is logged in - show username and logout -->
                    <div class="dropdown me-2">
                        <button class="btn btn-outline-primary dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person me-1"></i>
                            <?php echo strtoupper(htmlspecialchars($_SESSION['username'])); ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person-circle me-2"></i>Profile</a></li>
                            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="admin.php"><i class="bi bi-gear me-2"></i>Admin Panel</a></li>
                            <?php endif; ?>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <!-- User is not logged in - show login button -->
                    <a href="login.php" class="btn btn-outline-primary position-relative me-2">
                        <i class="bi bi-person"></i>
                        <span class="d-none d-sm-inline ms-1">Login</span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Welcome message for logged-in users (only show on main pages, not login/admin) -->
<?php 
$current_page = basename($_SERVER['PHP_SELF']);
$show_welcome = !in_array($current_page, ['login.php', 'admin.php', 'logout.php']);
?>
<?php if (isset($_SESSION['username']) && $show_welcome): ?>
<div class="alert alert-success alert-dismissible fade show m-0" role="alert">
    <div class="container">
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2"></i>
            <span>Welcome back, <strong><?php echo strtoupper(htmlspecialchars($_SESSION['username'])); ?></strong>! 
            <?php
            switch($current_page) {
                case 'shop.php':
                    echo 'Happy shopping!';
                    break;
                case 'contact.php':
                    echo 'Need help? We\'re here for you!';
                    break;
                case 'about.php':
                case 'about.html':
                    echo 'Learn more about our story!';
                    break;
                default:
                    echo 'Happy shopping!';
            }
            ?>
            </span>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>