<?php include 'db_connect.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="TSUBAKI FLORAL - Elegant floral arrangements for every occasion. Premium flowers, beautiful bouquets, and professional floral design services.">
    <meta name="keywords" content="flowers, bouquets, floral arrangements, wedding flowers, delivery, fresh flowers">
    <title>TSUBAKI FLORAL</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="images/favicon.ico">

    <link href="assets/style.css" rel="stylesheet">

</head>
<body>

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
                        <a class="nav-link active" href="index.php">Home</a>
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
                    <div class="me-3">
                        <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse"
                                data-bs-target="#searchCollapse" aria-expanded="false" aria-controls="searchCollapse">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>

                <button class="btn btn-outline-primary position-relative me-2" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas">
                    <i class="bi bi-bag"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cartCount">0</span>
                </button>


                </div>
            </div>
        </div>

        <div class="collapse" id="searchCollapse">
            <div class="container py-3 border-top">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search flowers, arrangements..."
                                   aria-label="Search" id="searchInput">
                            <button class="btn btn-primary" type="button" id="searchBtn">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                        <div id="searchSuggestions" class="mt-2"></div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <section class="hero-section position-relative overflow-hidden">
        <div class="container-fluid p-0">
            <div class="row g-0 min-vh-75">
                <div class="col-lg-6 d-flex align-items-center">
                    <div class="container">
                        <div class="row">
                            <div class="col-10 col-md-8 mx-auto text-center text-lg-start">
                                <h1 class="display-3 fw-bold mb-4 hero-title">
                                    Elegant Floral
                                    <span class="text-primary d-block">Arrangements</span>
                                </h1>
                                <p class="lead mb-4 text-muted">
                                    Discover our curated collection of premium flowers and bespoke arrangements.
                                    From intimate bouquets to grand celebrations, we bring nature's beauty to your special moments.
                                </p>
                                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center justify-content-lg-start">
                                    <a href="shop.php" class="btn btn-primary btn-lg px-4 py-3">
                                        <i class="bi bi-flower2 me-2"></i>
                                        Shop Bouquets
                                    </a>
                                    <a href="about.html" class="btn btn-outline-secondary btn-lg px-4 py-3">
                                        Learn More
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image-container h-100 position-relative">
                        <img src="assets/images/img1.jpg" class="card-img-top" alt="Beautiful floral arrangement"
                             class="img-fluid h-100 w-100 object-cover">
                        <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <section class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Featured Arrangements</h2>
                <p class="lead text-muted">Our most popular and stunning creations</p>
            </div>

            <div class="row g-4">
                <?php
                $sql_featured = "SELECT * FROM products ORDER BY reviews_count DESC LIMIT 3"; // Example: order by reviews for "featured"
                $result_featured = $conn->query($sql_featured);

                if ($result_featured->num_rows > 0) {
                    while($row_featured = $result_featured->fetch_assoc()) {
                ?>
                <div class="col-sm-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100 product-card">
                        <div class="position-relative overflow-hidden">
                            <img src="<?php echo htmlspecialchars($row_featured["image_url"]); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row_featured["name"]); ?>">
                            <div class="product-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <button class="btn btn-light btn-sm me-2" title="Quick View">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-primary btn-sm" title="Add to Cart">
                                        <i class="bi bi-bag-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold mb-2"><?php echo htmlspecialchars($row_featured["name"]); ?></h5>
                            <p class="card-text text-muted small mb-3"><?php echo htmlspecialchars($row_featured["description"]); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0 fw-bold text-primary">¥<?php echo number_format($row_featured["price"], 0); ?></span>
                                <div class="text-warning">
                                    <?php
                                    $rating_featured = round($row_featured["rating"]);
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $rating_featured) {
                                            echo '<i class="bi bi-star-fill"></i>';
                                        } else {
                                            echo '<i class="bi bi-star"></i>';
                                        }
                                    }
                                    ?>
                                    <span class="text-muted small ms-1">(<?php echo htmlspecialchars($row_featured["reviews_count"]); ?>)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    }
                } else {
                    echo "<p class='text-center w-100'>No featured products found.</p>";
                }
                ?>
            </div>

            <div class="text-center mt-5">
                 <a href="shop.php" class="btn btn-primary btn-lg px-4 py-3">
                    View All Products
                    <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </section>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-3">Our Services</h2>
                <p class="lead text-muted">Premium floral services tailored to your needs</p>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-3 text-center">
                    <div class="service-icon mb-3">
                        <i class="bi bi-truck display-4 text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Same-Day Delivery</h5>
                    <p class="text-muted">Order before 2 PM for same-day delivery within the city</p>
                </div>

                <div class="col-md-6 col-lg-3 text-center">
                    <div class="service-icon mb-3">
                        <i class="bi bi-heart display-4 text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Custom Arrangements</h5>
                    <p class="text-muted">Personalized floral designs for your special occasions</p>
                </div>

                <div class="col-md-6 col-lg-3 text-center">
                    <div class="service-icon mb-3">
                        <i class="bi bi-calendar-event display-4 text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Event Planning</h5>
                    <p class="text-muted">Complete floral design services for weddings and events</p>
                </div>

                <div class="col-md-6 col-lg-3 text-center">
                    <div class="service-icon mb-3">
                        <i class="bi bi-shield-check display-4 text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-3">Fresh Guarantee</h5>
                    <p class="text-muted">7-day freshness guarantee on all our floral arrangements</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-primary text-white">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h3 class="fw-bold mb-3">Stay in Bloom</h3>
                    <p class="mb-0">Subscribe to receive exclusive offers, seasonal arrangements, and floral care tips.</p>
                </div>
                <div class="col-lg-6">
                    <form class="row g-2" id="newsletterForm">
                        <div class="col-sm-8">
                            <input type="email" class="form-control form-control-lg"
                                   placeholder="Enter your email address" required>
                        </div>
                        <div class="col-sm-4">
                            <button type="submit" class="btn btn-light btn-lg w-100" >
                                Subscribe
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-light py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-flower1 me-2"></i>
                        TSUBAKI FLORAL
                    </h5>
                    <p class="mb-3">
                        Creating beautiful moments with elegant floral arrangements.
                        Premium flowers delivered fresh to your door.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-light"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-instagram fs-5"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-twitter fs-5"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-pinterest fs-5"></i></a>
                    </div>
                </div>

            </div>

            <hr class="my-4">

            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">
                        &copy; 2024 TSUBAKI FLORAL. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <button type="button" class="btn btn-primary btn-floating position-fixed bottom-0 end-0 m-4 d-none"
            id="backToTopBtn" style="z-index: 1000;">
        <i class="bi bi-arrow-up"></i>
    </button>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

    <script src="assets/script.js"></script>

</body>
</html>
<?php $conn->close(); // Close connection here as well ?>