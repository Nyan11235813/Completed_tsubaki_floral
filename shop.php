<?php session_start();include 'db_connect.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Shop TSUBAKI FLORAL - Browse our premium selection of fresh flowers, bouquets, arrangements, and gifts. Find the perfect floral design for any occasion.">
    <meta name="keywords" content="flowers, bouquets, floral arrangements, wedding flowers, delivery, fresh flowers, shop, online store">
    <title>Shop - TSUBAKI FLORAL</title>

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link href="assets/style.css" rel="stylesheet">
</head>
<body>

<?php include 'nav.php'; ?>

    <section class="py-5 bg-light text-center">
        <div class="container">
            <h1 class="display-4 fw-bold mb-3">Our Collections</h1>
            <p class="lead text-muted">Discover the perfect floral arrangement for every moment.</p>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <?php
                // Check if database connection exists
                if (!isset($conn) || $conn->connect_error) {
                    echo "<div class='col-12'><div class='alert alert-danger'>Database connection failed: " . (isset($conn) ? $conn->connect_error : "Connection not established") . "</div></div>";
                } else {
                    $sql = "SELECT * FROM products ORDER BY id DESC";
                    $result = $conn->query($sql);

                    // Check if query was successful
                    if ($result === false) {
                        echo "<div class='col-12'><div class='alert alert-danger'>Query failed: " . $conn->error . "</div></div>";
                    } elseif ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                ?>
                <div class="col-sm-6 col-lg-4">
                    <div class="card border-0 shadow-sm h-100 product-card">
                        <div class="position-relative overflow-hidden">
                            <img src="<?php echo htmlspecialchars($row["image_url"]); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row["name"]); ?>">
                            <div class="product-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <button class="btn btn-light btn-sm me-2" title="Quick View" data-quick-view="<?php echo htmlspecialchars($row["id"]); ?>">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-primary btn-sm" title="Add to Cart" data-add-to-cart="<?php echo htmlspecialchars($row["id"]); ?>">
                                        <i class="bi bi-bag-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold mb-2"><?php echo htmlspecialchars($row["name"]); ?></h5>
                            <p class="card-text text-muted small mb-3"><?php echo htmlspecialchars($row["description"]); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="h5 mb-0 fw-bold text-primary">¥<?php echo number_format($row["price"], 0); ?></span>
                                <div class="text-warning">
                                    <?php
                                    $rating = isset($row["rating"]) ? round($row["rating"]) : 5;
                                    for ($i = 1; $i <= 5; $i++) {
                                        if ($i <= $rating) {
                                            echo '<i class="bi bi-star-fill"></i>';
                                        } else {
                                            echo '<i class="bi bi-star"></i>';
                                        }
                                    }
                                    ?>
                                    <span class="text-muted small ms-1">(<?php echo isset($row["reviews_count"]) ? htmlspecialchars($row["reviews_count"]) : '0'; ?>)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                        }
                    } else {
                        echo "<div class='col-12 text-center'><p class='lead'>No products found.</p></div>";
                    }
                }
                
                // Close connection if it exists
                if (isset($conn) && !$conn->connect_error) {
                    $conn->close();
                }
                ?>
            </div>

            <nav aria-label="Product page navigation" class="mt-5">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                    </li>
                    <li class="page-item active" aria-current="page"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Next</a>
                    </li>
                </ul>
            </nav>
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
                    </div>
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