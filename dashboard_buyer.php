<?php
session_start();
require_once 'includes/db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'buyer') {
    echo "Access denied.";
    exit();
}

$buyer_id = $_SESSION['user']['id'];
$message = "";

// Handles add to cart
if (isset($_GET['add']) && is_numeric($_GET['add'])) {
    $product_id = intval($_GET['add']);

    $check = $conn->prepare("SELECT id FROM cart WHERE buyer_id = ? AND product_id = ?");
    $check->bind_param("ii", $buyer_id, $product_id);
    $check->execute();
    $check->store_result();

    if ($check->num_rows == 0) {
        $stmt = $conn->prepare("INSERT INTO cart (buyer_id, product_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $buyer_id, $product_id);
        if ($stmt->execute()) {
            $message = "Added to cart.";
        } else {
            $message = "Failed to add to cart.";
        }
        $stmt->close();
    } else {
        $message = "Already in cart.";
    }
    $check->close();
}

// Handle filters
$category = isset($_GET['category']) ? $_GET['category'] : '';
$barter = isset($_GET['barter']) ? $_GET['barter'] : '';

$query = "SELECT * FROM products WHERE 1=1";
$params = [];
$types = "";

if (!empty($category)) {
    $query .= " AND category LIKE ?";
    $params[] = "%" . $category . "%";
    $types .= "s";
}
if (!empty($barter)) {
    $query .= " AND barter_option = ?";
    $params[] = $barter;
    $types .= "s";
}

$stmt = $conn->prepare($query . " ORDER BY id DESC");
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Buyer Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 30px;
        }

        h1, h2 {
            text-align: center;
            color: #333;
        }

        p {
            text-align: center;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a.button, button {
            background-color: #28a745;
            color: white;
            padding: 10px 15px;
            border: none;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            cursor: pointer;
        }

        a.button:hover, button:hover {
            background-color: #218838;
        }

        .message {
            background: #e0ffe0;
            padding: 10px;
            margin: 20px auto;
            border-left: 5px solid green;
            width: 60%;
            text-align: center;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: center;
        }

        select, input[type="text"] {
            padding: 10px;
            width: 200px;
        }

        .product {
            display: flex;
            gap: 20px;
            background: white;
            margin: 20px auto;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            max-width: 800px;
        }

        .product img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 4px;
        }

        .info {
            flex: 1;
        }

        .info h3 {
            margin: 0;
            font-size: 20px;
            color: #222;
        }

        .info p {
            margin: 5px 0;
            color: #555;
        }

        .info .price {
            color: #007bff;
            font-weight: bold;
        }

        .actions {
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user']['username']); ?> (Buyer)</h1>
    <p><a href="logout.php">Logout</a> | <a href="cart.php">🛒 View Cart</a></p>

    <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <h2>Search & Filter Products</h2>
    <form method="get" action="">
        <input type="text" name="category" placeholder="Category (e.g., electronics)" value="<?php echo htmlspecialchars($category); ?>">
        <select name="barter">
            <option value="">All Payment Types</option>
            <option value="cash" <?php if ($barter === 'cash') echo 'selected'; ?>>Cash/Card</option>
            <option value="barter" <?php if ($barter === 'barter') echo 'selected'; ?>>Barter</option>
            <option value="both" <?php if ($barter === 'both') echo 'selected'; ?>>Both</option>
        </select>
        <button type="submit">Filter</button>
    </form>

    <h2>Available Products</h2>
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="product">
                <img src="<?php echo htmlspecialchars($row['image']); ?>" alt="Product">
                <div class="info">
                    <h3><?php echo htmlspecialchars($row['title']); ?></h3>
                    <p class="price">R<?php echo number_format($row['price'], 2); ?></p>
                    <p><strong>Area:</strong> <?php echo htmlspecialchars($row['area']); ?></p>
                    <p><strong>Quantity:</strong> <?php echo (int)$row['quantity']; ?></p>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($row['category']); ?></p>
                    <p><strong>Barter Option:</strong> <?php echo htmlspecialchars($row['barter_option']); ?></p>
                    <div class="actions">
                        <a class="button" href="?add=<?php echo $row['id']; ?>">Add to Cart</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p style="text-align: center;">No products found matching your filters.</p>
    <?php endif; ?>

</body>
</html>

<?php
$stmt->close();
$conn->close();
?>