<?php session_start();include 'db_connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - TSUBAKI FLORAL</title>
    <!-- Bootstrap & Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>
    <!-- Navigation -->
    <?php include 'nav.php'; ?>

    <!-- Hero Section -->
    <section class="py-5 bg-light">
        <div class="container text-center">
                <h1 class="display-4 fw-bold mb-3">About TSUBAKI FLORAL</h1>
                <p class="lead text-muted">Crafting beautiful moments with nature's finest blooms since 2018</p>
        </div>
    </section>

    <!-- Main Content -->
    <section class="py-5">
        <div class="container">
            <div class="row g-5 align-items-center">
                <!-- Story Section -->
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-4">Our Story</h2>
                    <p class="mb-4">Founded with a passion for bringing nature's beauty into everyday life, TSUBAKI FLORAL has been creating stunning floral arrangements that celebrate life's precious moments. Our journey began with a simple belief: every flower tells a story, and every arrangement should capture the emotions of the moment.</p>
                    <p class="mb-4">From intimate bouquets to grand wedding ceremonies, we pour our heart into every creation, ensuring that each arrangement reflects the unique beauty and sentiment of the occasion.</p>
                </div>
                <div class="col-lg-6">
                    <img src="assets/images/img2.jpg" class="img-fluid rounded shadow" alt="Our floral studio">
                </div>
            </div>

            <!-- Values Section -->
            <div class="row g-4 mt-5">
                <div class="col-md-4 text-center">
                    <div class="mb-3">
                        <i class="bi bi-flower2 display-4 text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Artistry</h5>
                    <p class="text-muted">Each arrangement is a unique work of art, carefully designed to capture natural beauty.</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="mb-3">
                        <i class="bi bi-gem display-4 text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Premium</h5>
                    <p class="text-muted">We select only the finest flowers and materials to create exceptional arrangements.</p>
                </div>
                <div class="col-md-4 text-center">
                    <div class="mb-3">
                        <i class="bi bi-infinity display-4 text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Timeless</h5>
                    <p class="text-muted">Our designs create lasting memories that bloom beyond the moment.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer (simplified) -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container text-center">
            <p class="mb-0">&copy; 2024 TSUBAKI FLORAL. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

    <!-- Custom JavaScript Placeholder -->
    <script src="assets/script.js"></script>
</body>
</html>