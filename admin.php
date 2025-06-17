<?php
session_start();

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php"); // Redirect to a login page if not logged in
    exit;
}

include 'db_connect.php';

$message = '';

// Add Product
if (isset($_POST['add_product'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['price']);
    $image_url = $conn->real_escape_string($_POST['image_url']);
    $rating = $conn->real_escape_string($_POST['rating']);
    $reviews_count = $conn->real_escape_string($_POST['reviews_count']);

    $sql = "INSERT INTO products (name, description, price, image_url, rating, reviews_count) VALUES ('$name', '$description', '$price', '$image_url', '$rating', '$reviews_count')";
    if ($conn->query($sql) === TRUE) {
        $message = '<div class="alert alert-success">Product added successfully!</div>';
    } else {
        $message = '<div class="alert alert-danger">Error: ' . $sql . '<br>' . $conn->error . '</div>';
    }
}

// Edit Product
if (isset($_POST['edit_product'])) {
    $id = $conn->real_escape_string($_POST['product_id']);
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['price']);
    $image_url = $conn->real_escape_string($_POST['image_url']);
    $rating = $conn->real_escape_string($_POST['rating']);
    $reviews_count = $conn->real_escape_string($_POST['reviews_count']);

    $sql = "UPDATE products SET name='$name', description='$description', price='$price', image_url='$image_url', rating='$rating', reviews_count='$reviews_count' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        $message = '<div class="alert alert-success">Product updated successfully!</div>';
    } else {
        $message = '<div class="alert alert-danger">Error updating record: ' . $conn->error . '</div>';
    }
}

// Delete Product
if (isset($_GET['delete'])) {
    $id = $conn->real_escape_string($_GET['delete']);
    $sql = "DELETE FROM products WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        $message = '<div class="alert alert-success">Product deleted successfully!</div>';
    } else {
        $message = '<div class="alert alert-danger">Error deleting record: ' . $conn->error . '</div>';
    }
}

// Fetch products for display
$products = [];
$sql = "SELECT * FROM products ORDER BY id DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - TSUBAKI FLORAL</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet">
    <style>
        .product-image-thumbnail {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="admin.php">TSUBAKI FLORAL Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">View Store</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-5">
        <h1 class="mb-4">Product Management</h1>
        <?php echo $message; ?>

        <div class="card mb-5 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Add New Product</h4>
            </div>
            <div class="card-body">
                <form action="admin.php" method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Price (¥)</label>
                        <input type="number" class="form-control" id="price" name="price" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="image_url" class="form-label">Image URL</label>
                        <input type="url" class="form-control" id="image_url" name="image_url">
                    </div>
                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating (0.0-5.0)</label>
                        <input type="number" class="form-control" id="rating" name="rating" step="0.1" min="0" max="5" value="0.0">
                    </div>
                    <div class="mb-3">
                        <label for="reviews_count" class="form-label">Reviews Count</label>
                        <input type="number" class="form-control" id="reviews_count" name="reviews_count" min="0" value="0">
                    </div>
                    <button type="submit" name="add_product" class="btn btn-primary">Add Product</button>
                </form>
            </div>
        </div>

        <h2 class="mb-3">Existing Products</h2>
        <?php if (!empty($products)): ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Rating</th>
                        <th>Reviews</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['id']); ?></td>
                        <td><img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-image-thumbnail"></td>
                        <td><?php echo htmlspecialchars($product['name']); ?></td>
                        <td><?php echo htmlspecialchars(substr($product['description'], 0, 50)) . '...'; ?></td>
                        <td>¥<?php echo number_format($product['price'], 0); ?></td>
                        <td><?php echo htmlspecialchars($product['rating']); ?></td>
                        <td><?php echo htmlspecialchars($product['reviews_count']); ?></td>
                        <td>
                            <button class="btn btn-sm btn-info me-2" data-bs-toggle="modal" data-bs-target="#editProductModal"
                                data-id="<?php echo $product['id']; ?>"
                                data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                data-description="<?php echo htmlspecialchars($product['description']); ?>"
                                data-price="<?php echo htmlspecialchars($product['price']); ?>"
                                data-image_url="<?php echo htmlspecialchars($product['image_url']); ?>"
                                data-rating="<?php echo htmlspecialchars($product['rating']); ?>"
                                data-reviews_count="<?php echo htmlspecialchars($product['reviews_count']); ?>">
                                <i class="bi bi-pencil"></i> Edit
                            </button>
                            <a href="admin.php?delete=<?php echo $product['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">
                                <i class="bi bi-trash"></i> Delete
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <p>No products in the database.</p>
        <?php endif; ?>
    </div>

    <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title" id="editProductModalLabel">Edit Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="admin.php" method="POST">
                    <div class="modal-body">
                        <input type="hidden" id="edit_product_id" name="product_id">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_price" class="form-label">Price (¥)</label>
                            <input type="number" class="form-control" id="edit_price" name="price" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_image_url" class="form-label">Image URL</label>
                            <input type="url" class="form-control" id="edit_image_url" name="image_url">
                        </div>
                        <div class="mb-3">
                            <label for="edit_rating" class="form-label">Rating (0.0-5.0)</label>
                            <input type="number" class="form-control" id="edit_rating" name="rating" step="0.1" min="0" max="5">
                        </div>
                        <div class="mb-3">
                            <label for="edit_reviews_count" class="form-label">Reviews Count</label>
                            <input type="number" class="form-control" id="edit_reviews_count" name="reviews_count" min="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" name="edit_product" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    <script>
        // Populate edit modal with product data
        var editProductModal = document.getElementById('editProductModal');
        editProductModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var id = button.getAttribute('data-id');
            var name = button.getAttribute('data-name');
            var description = button.getAttribute('data-description');
            var price = button.getAttribute('data-price');
            var imageUrl = button.getAttribute('data-image_url');
            var rating = button.getAttribute('data-rating');
            var reviewsCount = button.getAttribute('data-reviews_count');

            var modalIdInput = editProductModal.querySelector('#edit_product_id');
            var modalNameInput = editProductModal.querySelector('#edit_name');
            var modalDescriptionInput = editProductModal.querySelector('#edit_description');
            var modalPriceInput = editProductModal.querySelector('#edit_price');
            var modalImageUrlInput = editProductModal.querySelector('#edit_image_url');
            var modalRatingInput = editProductModal.querySelector('#edit_rating');
            var modalReviewsCountInput = editProductModal.querySelector('#edit_reviews_count');

            modalIdInput.value = id;
            modalNameInput.value = name;
            modalDescriptionInput.value = description;
            modalPriceInput.value = price;
            modalImageUrlInput.value = imageUrl;
            modalRatingInput.value = rating;
            modalReviewsCountInput.value = reviewsCount;
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>