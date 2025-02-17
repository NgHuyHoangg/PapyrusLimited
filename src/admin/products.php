<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        $name = htmlspecialchars($_POST['name']);
        $category_id = intval($_POST['category_id']);
        $price = floatval($_POST['price']);
        $description = htmlspecialchars($_POST['description']);
        $material = htmlspecialchars($_POST['material']);

        $stmt = $conn->prepare("INSERT INTO products (name, category_id, price, description, material) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sidss", $name, $category_id, $price, $description, $material);
        if (!$stmt->execute()) {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }

    if (isset($_POST['delete_product'])) {
        $product_id = intval($_POST['product_id']);
        $stmt = $conn->prepare("DELETE FROM products WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        if (!$stmt->execute()) {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

$products = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="products.css">
    <script>
        function deleteProduct(productId) {
            if (confirm('Are you sure you want to delete this product?')) {
                fetch('', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: 'delete_product=1&product_id=' + productId
                })
                .then(response => response.text())
                .then(data => {
                    window.location.reload();
                })
                .catch(error => console.error('Error:', error));
            }
        }
    </script>
</head>
<body>
<?php
include "header.html";
?>
    <h1>Product Management</h1>
    <form method="POST">
        <input type="text" name="name" placeholder="Product Name" required>
        <input type="number" name="category_id" placeholder="Category ID" required>
        <input type="number" name="price" placeholder="Price" required>
        <input type="text" name="description" placeholder="Description">
        <input type="text" name="material" placeholder="Material">
        <button type="submit" name="add_product">Add Product</button>
    </form>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Description</th>
            <th>Material</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $products->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['product_id']) ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['catagory_id']) ?></td>
                <td><?= htmlspecialchars($row['price']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td><?= htmlspecialchars($row['material']) ?></td>
                <td>
                    <button onclick="deleteProduct(<?= htmlspecialchars($row['product_id']) ?>)">Delete</button>
                    <a href="edit1.php?product_id=<?= htmlspecialchars($row['product_id']) ?>">
                        <button type="button">Change Detail</button>
                    </a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
